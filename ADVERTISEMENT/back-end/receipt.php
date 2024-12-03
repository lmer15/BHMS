<?php
// Fetch tenant details using tenant_id from URL
$tenant_id = isset($_GET['tenant_id']) ? (int)$_GET['tenant_id'] : 0;

if ($tenant_id == 0) {
    echo "Invalid tenant ID.";
    exit();
}

// Fetch tenant and booking details from the database
$query = "SELECT td.first_name, td.last_name, td.email_address, td.contact_number, r.room_number, r.room_type 
          FROM tenant_details td 
          JOIN booking b ON td.id = b.tenant_id
          JOIN room r ON b.room_id = r.room_id 
          WHERE td.id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $tenant_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $tenant_name = $row['first_name'] . ' ' . $row['last_name'];
    $room_number = $row['room_number'];
    $room_type = $row['room_type'];
    $email = $row['email_address'];
    $contact_number = $row['contact_number'];

    // Assuming a fixed deposit amount or fetch from the room table
    $deposit_amount = 1000; // Example deposit amount
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - BHMS</title>
    <link rel="stylesheet" href="StyleForm.css?v=1.2">
</head>
<body>
    <section id="wrapper">
        <section id="receipt-section">
            <h1>Booking Receipt</h1>
            <div class="receipt-details">
                <p><strong>Tenant Name:</strong> <?php echo htmlspecialchars($tenant_name); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($contact_number); ?></p>
                <p><strong>Room Number:</strong> <?php echo htmlspecialchars($room_number); ?></p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($room_type); ?></p>
                <p><strong>Deposit Amount:</strong> <?php echo htmlspecialchars($deposit_amount); ?> PHP</p>
            </div>

            <button id="pay-deposit-btn" onclick="window.location.href='pay_deposit.php?tenant_id=<?php echo $tenant_id; ?>'">Pay Deposit</button>
        </section>
    </section>
</body>
</html>
