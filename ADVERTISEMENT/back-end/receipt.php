<?php
include_once(__DIR__ . "/../../DATABASE/dbConnector.php");
$tenant_id = isset($_GET['tenant_id']) ? (int)$_GET['tenant_id'] : 0;

if ($tenant_id == 0) {
    echo "Invalid tenant ID.";
    exit();
}

// Fetch tenant, booking, and room details from the database
$query = "SELECT 
            td.fname, td.lname, td.email_address, td.contact_number, 
            r.room_number, r.room_type, r.rental_rates, r.room_deporate, 
            b.booking_start_date, b.booking_end_date
          FROM tenant_details td
          JOIN booking b ON td.tc_id = b.tenant_id
          JOIN room r ON b.room_id = r.room_id
          WHERE b.tenant_id = ?";

$stmt = mysqli_prepare($conn, $query);

if ($stmt === false) {
    echo "Error preparing statement: " . mysqli_error($conn);
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $tenant_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Tenant Details
    $tenant_name = $row['fname'] . ' ' . $row['lname'];
    $email = $row['email_address'];
    $contact_number = $row['contact_number'];

    // Room Details
    $room_number = $row['room_number'];
    $room_type = $row['room_type'];
    $rental_rates = $row['rental_rates'];
    $deposit_amount = $row['room_deporate'];

    // Booking Details
    $dateofBook = $row['booking_start_date'];
    $dateofMoveIn = $row['booking_end_date'];
} else {
    // Handle the case where no data is found
    $dateofBook = "N/A";
    $dateofMoveIn = "N/A";
    $tenant_name = "N/A";
    $email = "N/A";
    $contact_number = "N/A";
    $room_number = "N/A";
    $room_type = "N/A";
    $rental_rates = "N/A";
    $deposit_amount = "N/A";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - BHMS</title>
    <link rel="stylesheet" href="receipt.css?v=1.10">
    <script src="../../imported_links.js" defer></script>

    <script>
        function redirectToHome() {
            const iframe = window.parent.document.getElementById('content-iframe');
                window.location.href = '../index.html';
        }

        function confirmCancellation(tenant_id) {
            const confirmation = confirm(
                "Are you sure you want to cancel the booking? This action cannot be undone."
            );
            if (confirmation) {
                cancelBooking(tenant_id);
            }
        }

        function cancelBooking(tenant_id) {
            document.getElementById("cancelBtn").disabled = true;

            // Make the AJAX request to cancel_booking.php
            fetch('cancel_booking.php?tenant_id=' + tenant_id)
                .then(response => response.json())
                .then(response => {
                    if (response.errors) {
                        alert(response.errors.join('\n'));
                    } else if (response.success) {
                        alert(response.success);
                        
                        // Redirect with a slight delay
                        setTimeout(() => {
                            window.location.href = '../rooms.php';
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again.');
                })
                .finally(() => {
                    document.getElementById("cancelBtn").disabled = false;
                });
        }

    </script>
</head>
<body>
    <section id="wrapper">
        <section id="receipt-section">
            <h1>Booking Receipt</h1>
            <p class="message"><i class='bx bx-check-circle'></i> Thank you for booking with us. Please proceed to the counter to pay your initial deposit and complete the necessary documents.</p>
            <div class="receipt-details">
                <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($dateofBook); ?></p>
                <p><strong>Move-In Date:</strong> <?php echo htmlspecialchars($dateofMoveIn); ?></p>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($tenant_name); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($contact_number); ?></p>
                <p><strong>Room Number:</strong> <?php echo htmlspecialchars($room_number); ?></p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($room_type); ?></p>
                <p><strong>Monthly Rental Rate:</strong> <?php echo htmlspecialchars($rental_rates); ?> PHP</p>
                <p><strong>Deposit Amount:</strong> <?php echo htmlspecialchars($deposit_amount); ?> PHP</p>
            </div>

            <div class="btn">
                <button id="pay-deposit-btn" onclick="redirectToHome()">Go Home</button>
                <span>or</span>
                <button id="cancelBtn" type="button" onclick="confirmCancellation(<?php echo $tenant_id; ?>)">Cancel</button>
                <button onclick="window.print()">Download Receipt</button>
            </div>

        </section>
    </section>
</body>
</html>
