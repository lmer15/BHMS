<?php
require '../vendor/autoload.php'; 
include_once(__DIR__ . "/../DATABASE/dbConnector.php");

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

$paymentId = isset($_GET['paymentId']) ? $_GET['paymentId'] : '';
$PayerID = isset($_GET['PayerID']) ? $_GET['PayerID'] : '';
$tenant_id = isset($_GET['tenant_id']) ? (int)$_GET['tenant_id'] : 0;

if (empty($paymentId) || empty($PayerID) || $tenant_id == 0) {
    echo "Invalid payment details.";
    exit();
}

// PayPal API credentials
$clientId = 'AYRLJm_vimKMK9UpA20P1lwhZX0w3g3RS2iyopGh_b8hw4-w26dHVjG0rkNZq-ignVqJajBNmerzTpXT';
$clientSecret = 'EMnSxccdgVlDPUZTa7jLGd6SbdKALNjJ_UJtoJcYzaBQOmBD4Rw1oX7eZm1KHR-pSANjthBrjzUVZTA7';

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret)
);

// Get the payment details
try {
    $payment = Payment::get($paymentId, $apiContext);
} catch (Exception $e) {
    echo "Error retrieving payment details: " . $e->getMessage();
    exit();
}

// Execute the payment
$execution = new PaymentExecution();
$execution->setPayerId($PayerID);

try {
    $payment->execute($execution, $apiContext);

    // Generate a unique transaction code
    $transaction_code = 'TXN-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);

    // Insert payment details into the database
    $payment_amount = $payment->getTransactions()[0]->getAmount()->getTotal();
    $query = "INSERT INTO payment_history (payment_transactions, payment_id, tenant_id, payment_date, payment_amount, balance, payment_type, payment_status) 
              VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    $payment_type = 'Deposit'; // Payment type
    $payment_status = 'Completed'; // Payment status

    mysqli_stmt_bind_param($stmt, "ssisds", $transaction_code, $paymentId, $tenant_id, $payment_amount, $payment_amount, $payment_type, $payment_status);
    mysqli_stmt_execute($stmt);

    // Update booking and user accounts tables
    $query = "UPDATE booking SET status = 'paid' WHERE tenant_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $tenant_id);
    mysqli_stmt_execute($stmt);

    $query = "UPDATE user_accounts SET status = 'approved' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $tenant_id);
    mysqli_stmt_execute($stmt);

    echo "Payment successful! Transaction ID: $transaction_code. Thank you for your deposit.";

} catch (Exception $e) {
    echo "Error executing payment: " . $e->getMessage();
    exit();
}
?>
