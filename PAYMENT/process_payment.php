<?php
require '../vendor/autoload.php'; 
include_once(__DIR__ . "/../DATABASE/dbConnector.php");

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

$tenant_id = isset($_POST['tenant_id']) ? (int)$_POST['tenant_id'] : 0;
$deposit_amount = isset($_POST['deposit']) ? (float)$_POST['deposit'] : 0;

if ($tenant_id == 0 || $deposit_amount <= 0) {
    echo "Invalid tenant ID or deposit amount.";
    exit();
}

// PayPal API credentials
$clientId = 'AYRLJm_vimKMK9UpA20P1lwhZX0w3g3RS2iyopGh_b8hw4-w26dHVjG0rkNZq-ignVqJajBNmerzTpXT';
$clientSecret = 'EMnSxccdgVlDPUZTa7jLGd6SbdKALNjJ_UJtoJcYzaBQOmBD4Rw1oX7eZm1KHR-pSANjthBrjzUVZTA7';

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret)
);

// Payer details
$payer = new Payer();
$payer->setPaymentMethod("paypal");

// Amount to be paid
$amount = new Amount();
$amount->setCurrency("PHP")->setTotal($deposit_amount);

// Transaction details
$transaction = new Transaction();
$transaction->setAmount($amount)
            ->setDescription("Deposit payment for Tenant ID: $tenant_id");

// Redirect URLs
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("http://localhost/BHMS/PAYMENT/payment_success.php?tenant_id=$tenant_id")
             ->setCancelUrl("http://localhost/BHMS/PAYMENT/payment_cancel.php");

// Payment object
$payment = new Payment();
$payment->setIntent("sale")
        ->setPayer($payer)
        ->setTransactions([$transaction])
        ->setRedirectUrls($redirectUrls);

try {
    $payment->create($apiContext);
    header("Location: " . $payment->getApprovalLink());
    exit();
} catch (Exception $e) {
    echo "Error creating payment: " . $e->getMessage();
    exit();
}
?>
