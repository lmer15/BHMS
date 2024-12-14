<?php
// Include database connection
include('../../DATABASE/dbConnector.php');

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $tenantName = mysqli_real_escape_string($conn, $_POST['tenantName']);
    $roomNumber = mysqli_real_escape_string($conn, $_POST['roomNumber']);
    $paymentAmount = mysqli_real_escape_string($conn, $_POST['paymentAmount']);

    // Get tenant ID based on the tenant name and room number
    $queryTenant = "SELECT tc_id FROM tenant_details WHERE fname = '$tenantName' AND room_number = '$roomNumber' LIMIT 1";
    $resultTenant = mysqli_query($conn, $queryTenant);

    if (mysqli_num_rows($resultTenant) > 0) {
        $tenant = mysqli_fetch_assoc($resultTenant);
        $tenantId = $tenant['tc_id'];

        // Insert payment record into the rental payments table
        $queryPayment = "INSERT INTO rental_payments (tenant_id, amount_paid, payment_date, balance, status) 
                         VALUES ('$tenantId', '$paymentAmount', NOW(), 0, 'Paid')";
        if (mysqli_query($conn, $queryPayment)) {
            // Update the tenant's balance (assuming rent balance gets deducted when paid)
            $queryUpdateBalance = "UPDATE rental_payments 
                                   SET balance = balance - '$paymentAmount' 
                                   WHERE tenant_id = '$tenantId' AND balance > 0";
            mysqli_query($conn, $queryUpdateBalance);

            // Insert into the payment history table
            $queryHistory = "INSERT INTO payment_history (tenant_id, payment_date, payment_amount) 
                             VALUES ('$tenantId', NOW(), '$paymentAmount')";
            mysqli_query($conn, $queryHistory);

            // Redirect to the payment history page or confirmation page
            header("Location: pay.php?status=success");
            exit();
        } else {
            echo "Error processing payment: " . mysqli_error($conn);
        }
    } else {
        echo "Tenant not found!";
    }
} else {
    echo "Invalid request method!";
}

// Close database connection
mysqli_close($conn);
?>
