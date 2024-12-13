<?php

include_once '../../DATABASE/dbConnector.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
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
    ORDER BY 
        ph.payment_date DESC
";
$result = $conn->query($sql);

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
            <span>$34,857.45</span>
            <p>Your due rent in the month of July</p>
        </div>

        <div class="payment-history">
            <h2>Payment History</h2>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($payment = $result->fetch_assoc()) {
                    $paymentDate = date('F d, Y', strtotime($payment['payment_date']));
                    $paymentMonth = date('F', strtotime($payment['payment_date']));
                    $balance = number_format($payment['balance'], 2);
                    $paymentAmount = number_format($payment['payment_amount'], 2);

                    echo '<div class="payment-item">';
                    echo '<p><strong>Payment Amount:</strong> $' . $paymentAmount . '</p>';
                    echo '<p><strong>Month Paid:</strong> ' . htmlspecialchars($paymentMonth) . '</p>';
                    echo '<p><strong>Payment Date:</strong> ' . $paymentDate . '</p>';
                    echo '<p><strong>Payment Type:</strong> ' . htmlspecialchars($payment['payment_type']) . '</p>';
                    echo '<p><strong>Payment Status:</strong> ' . htmlspecialchars($payment['payment_status']) . '</p>';
                    echo '<p><strong>Rent Period:</strong> ' . date('F d, Y', strtotime($payment['rent_period_start'])) . ' to ' . date('F d, Y', strtotime($payment['rent_period_end'])) . '</p>';
                    echo '<p><strong>Balance:</strong> $' . $balance . '</p>';
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
