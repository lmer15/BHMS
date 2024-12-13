<?php

include_once '../../DATABASE/dbConnector.php';

session_start(); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['tc_id'])) {
    $tenant_id = $_SESSION['tc_id'];  
    $sql_due_rent = "
        SELECT 
            rp.balance 
        FROM 
            rental_payments rp
        WHERE 
            rp.tenant_id = ? 
        ORDER BY 
            rp.rent_period_end DESC 
        LIMIT 1
    ";

    if ($stmt_due_rent = $conn->prepare($sql_due_rent)) {
        $stmt_due_rent->bind_param("i", $tenant_id); 
        $stmt_due_rent->execute();
        $result_due_rent = $stmt_due_rent->get_result();
        $due_rent = 0;

        if ($result_due_rent->num_rows > 0) {
            $row = $result_due_rent->fetch_assoc();
            $due_rent = $row['balance']; 
        }
    }
    $sql_payment_history = "
        SELECT 
            ph.payment_id, 
            ph.tenant_id, 
            ph.payment_date, 
            ph.payment_amount, 
            ph.payment_type, 
            ph.payment_status, 
            rp.rent_period_start, 
            rp.rent_period_end, 
            rp.total_rent, 
            rp.amount_paid, 
            rp.balance
        FROM 
            payment_history ph
        JOIN 
            rental_payments rp ON ph.payment_id = rp.payment_id
        WHERE
            ph.tenant_id = ?  -- Only get results for the logged-in tenant
        ORDER BY 
            ph.payment_date DESC
    ";
    if ($stmt_history = $conn->prepare($sql_payment_history)) {
        $stmt_history->bind_param("i", $tenant_id);  
        $stmt_history->execute();
        $result_history = $stmt_history->get_result();
    }

} else {
    echo '<p>You need to log in to view your payment history.</p>';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="pay.css?v=1.20">
</head>
<body>
    
    <div class="wrapper">

        <div class="notif">
            <span>$<?php echo number_format($due_rent, 2); ?></span>
            <p>Your due rent for the current month</p>
        </div>

        <div class="payment-history">
            <h2>Payment History</h2>
            <?php
            if ($result_history->num_rows > 0) {
                // Output data of each row
                while ($payment = $result_history->fetch_assoc()) {
                    $paymentDate = date('F d, Y', strtotime($payment['payment_date']));
                    $paymentMonth = date('F', strtotime($payment['payment_date']));
                    $balance = number_format($payment['balance'], 2);
                    $paymentAmount = number_format($payment['payment_amount'], 2);
                    $totalRent = number_format($payment['total_rent'], 2);
                    $amountPaid = number_format($payment['amount_paid'], 2);

                    echo '<div class="payment-item">';
                    echo '<p><strong>Payment Amount:</strong> $' . $paymentAmount . '</p>';
                    echo '<p><strong>Month Paid:</strong> ' . htmlspecialchars($paymentMonth) . '</p>';
                    echo '<p><strong>Payment Date:</strong> ' . $paymentDate . '</p>';
                    echo '<p><strong>Rent Period:</strong> ' . date('F d, Y', strtotime($payment['rent_period_start'])) . ' to ' . date('F d, Y', strtotime($payment['rent_period_end'])) . '</p>';
                    echo '<p><strong>Balance:</strong> $' . $balance . '</p>';
                    echo '<p><strong>Total Rent:</strong> $' . $totalRent . '</p>';
                    echo '<p><strong>Amount Paid:</strong> $' . $amountPaid . '</p>';
                    echo '<p><strong>Payment Status:</strong> ' . htmlspecialchars($payment['payment_status']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No payment history available.</p>';
            }
            ?>
        </div>

    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
