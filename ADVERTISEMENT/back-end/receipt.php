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
            r.room_number, r.room_type, r.rental_rates, r.room_deporate 
          FROM tenant_details td
          JOIN booking b ON td.tc_id = b.tenant_id
          JOIN room r ON b.room_id = r.room_id
          WHERE b.tenant_id = ?";

$stmt = mysqli_prepare($conn, $query);

// Check if the statement was prepared successfully
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
    $deposit_amount = $row['room_deporate'];  // Assuming room_deporate is the deposit amount
} else {
    // Handle the case where no data is found
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
    <link rel="stylesheet" href="receipt.css?v=1.5">
    <script src="../../imported_links.js" defer></script>

    <script>
        // Add event listener to trigger a warning before leaving the page
        window.addEventListener("beforeunload", function(event) {
            const message = "Are you sure you want to leave this page? Any unsaved progress will be lost.";
            event.returnValue = message;
            return message;
        });

        function confirmCancellation(tenant_id) {
            // Show confirmation dialog with OK and Cancel buttons
            const confirmation = confirm("Are you sure you want to cancel the booking? This action cannot be undone.");
            if (confirmation) {
                cancelBooking(tenant_id);
            }
        }

        // AJAX function to cancel booking
        function cancelBooking(tenant_id) {
            // Disable the button to prevent multiple clicks
            document.getElementById("cancelBtn").disabled = true;
            const errorMessage = document.getElementById("errorMessage");

            // Remove the beforeunload event listener to prevent the warning message
            window.removeEventListener("beforeunload", function(event) {
                const message = "Are you sure you want to leave this page? Any unsaved progress will be lost.";
                event.returnValue = message;
                return message;
            });

            // Make the AJAX request to cancel_booking.php
            fetch('cancel_booking.php?tenant_id=' + tenant_id)
                .then(response => response.json())
                .then(response => {
                    if (response.errors) {
                        // Display the errors
                        errorMessage.innerHTML = response.errors.join('<br>');
                        errorMessage.style.display = 'block';
                    } else if (response.success) {
                        alert(response.success); // Success alert

                        // After a short delay, redirect the iframe to rooms.php
                        setTimeout(function() {
                            var iframe = window.parent.document.getElementById('content-iframe');
                            if (iframe) {
                                iframe.src = 'rooms.php'; // Change the iframe source
                            }
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again.');
                });
        }

    </script>

</head>
<body>
    <section id="wrapper">
        <section id="receipt-section">
            <h1>Booking Receipt</h1>
            <div class="receipt-details">
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($tenant_name); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($contact_number); ?></p>
                <p><strong>Room Number:</strong> <?php echo htmlspecialchars($room_number); ?></p>
                <p><strong>Room Type:</strong> <?php echo htmlspecialchars($room_type); ?></p>
                <p><strong>Monthly Rental Rate:</strong> <?php echo htmlspecialchars($rental_rates); ?> PHP</p>
                <p><strong>Deposit Amount:</strong> <?php echo htmlspecialchars($deposit_amount); ?> PHP</p>
            </div>

            <div class="btn">
                <span>Pay deposit amount to complete the booking:</span>
                <button id="pay-deposit-btn" onclick="handlePayment(<?php echo $tenant_id; ?>)">Pay</button>
                <span>or</span>
                <button id="cancelBtn" type="button" onclick="confirmCancellation(<?php echo $tenant_id; ?>)">Cancel</button>
            </div>

            <!-- Error message display -->
            <div id="errorMessage" style="color: red; display: none;"></div>
        </section>
    </section>
</body>
</html>
