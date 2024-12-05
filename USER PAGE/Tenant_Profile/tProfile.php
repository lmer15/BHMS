<?php
    session_start();
    require_once '../../DATABASE/dbConnector.php'; // Ensure the correct path

    // Check if tenant is logged in
    if (!isset($_SESSION['tc_id'])) {
        header("Location: login.php"); 
        exit();
    }

    $tenant_id = $_SESSION['tc_id'];

    // Fetch tenant details from the database using MySQLi
    $tenant_query = "SELECT * FROM tenant_details WHERE tc_id = ?";
    $tenant_stmt = mysqli_prepare($conn, $tenant_query);
    mysqli_stmt_bind_param($tenant_stmt, "i", $tenant_id); 
    mysqli_stmt_execute($tenant_stmt);
    $tenant_result = mysqli_stmt_get_result($tenant_stmt);
    $tenant = mysqli_fetch_assoc($tenant_result);
    $number_of_occupants =$tenant['number_of_occupants'];

    // Fetch user account details based on the tenant's user_id (foreign key) using MySQLi
    $user_query = "SELECT * FROM user_accounts WHERE id = ?";
    $user_stmt = mysqli_prepare($conn, $user_query);
    mysqli_stmt_bind_param($user_stmt, "i", $tenant['id']); 
    mysqli_stmt_execute($user_stmt);
    $user_result = mysqli_stmt_get_result($user_stmt);
    $user_account = mysqli_fetch_assoc($user_result);

    // Fetch room details based on the tenant's booking using MySQLi
    $booking_query = "SELECT * FROM booking b 
                    JOIN room r ON b.room_id = r.room_id
                    WHERE b.tenant_id = ? AND b.status = 'Booked'"; 
    $booking_stmt = mysqli_prepare($conn, $booking_query);
    mysqli_stmt_bind_param($booking_stmt, "i", $tenant_id); 
    mysqli_stmt_execute($booking_stmt);
    $room_details_result = mysqli_stmt_get_result($booking_stmt);
    $room_details = mysqli_fetch_assoc($room_details_result);

    // Fetch booking details
    $booking_start_date = $room_details['booking_start_date'];
    $booking_end_date = $room_details['booking_end_date'];
    $room_number = $room_details['room_number'];
    $room_image = $room_details['room_image'];
    $room_type = $room_details['room_type'];
    $room_size = $room_details['room_size'];
    $room_amenities = $room_details['room_aminities'];
    $room_utilities = $room_details['room_utilities'];
    $rental_rates = $room_details['rental_rates'];
    $payment_frequency = $room_details['room_payfre'];
    $deposit_rate = $room_details['room_deporate'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TENANT PROFILE</title>
    <link rel="stylesheet" href="tProfile.css?v=1.5">
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="profilewrapper">

<div class="profile-container">

    <div class="navigator">
        <span>GENERAL</span>
        <a href="">Personal Information</a>
        <a href="#" id="changePasswordLink">Change Password</a>

        <span>DOCUMENTS</span>
        <a href="#" id="roomDetailsLink">Room Details</a>
        <a href="" id="leaseLink">Lease Agreement</a>
    </div>

    <div class="profile">

        <div class="detpro">
            <div class="dp">
                <img id="profilePicture" src="../image/DP.png" alt="Profile Picture">
                <i class="bx bx-plus icon" onclick="triggerFileInput()"></i>
            </div>

            <div class="name-user">
                <span><?php echo htmlspecialchars($tenant['fname'] . ' ' . $tenant['lname']); ?></span>
                <span>@<?php echo htmlspecialchars($user_account['username']); ?></span>
            </div>

            <a href="" id="updateLink"><i class="fas fa-edit"></i></a>
        </div>

        <div class="descpro">
            <h1>PERSONAL DETAILS</h1>
            <span>Name</span>
            <span><?php echo htmlspecialchars($tenant['fname'] . ' ' . $tenant['lname']); ?></span>

            <span>Gender</span>
            <span><?php echo htmlspecialchars($tenant['gender']); ?></span>

            <span>Email Address</span>
            <span><?php echo htmlspecialchars($tenant['email_address']); ?></span>

            <span>Contact Number</span>
            <span><?php echo htmlspecialchars($tenant['contact_number']); ?></span>

            <span>Religion</span>
            <span><?php echo htmlspecialchars($tenant['religion']); ?></span>

            <span>Nationality</span>
            <span><?php echo htmlspecialchars($tenant['nationality']); ?></span>

            <span>Occupation</span>
            <span><?php echo htmlspecialchars($tenant['occupation']); ?></span>
        </div>

        <div class="roominfo">
            <h1>ROOM DETAILS</h1>
            <span>Room Number</span>
            <span><?php echo htmlspecialchars($room_number); ?></span>

            <span>Room Type</span>
            <span><?php echo htmlspecialchars($room_type); ?></span>

            <span>Date Check-In</span>
            <span><?php echo htmlspecialchars($booking_start_date); ?></span>

            <span>Time Check-In</span>
            <span><?php echo date('h:i A', strtotime($booking_start_date)); ?></span>

            <span>Room Size</span>
            <span><?php echo htmlspecialchars($room_size); ?> m²</span>

            <span>Amenities</span>
            <span><?php echo htmlspecialchars($room_amenities); ?></span>

            <span>Utilities</span>
            <span><?php echo htmlspecialchars($room_utilities); ?></span>

            <span>Rental Monthly Rates</span>
            <span style="color: rgb(228, 31, 31);">₱<?php echo number_format($rental_rates, 2); ?></span>
        </div>
                        
        <!-- Form for password change -->
        <form id="updatePass" method="POST" action="changePass.php">
            <div class="change-pass" style="display:none;">
                <h2>Change Password</h2>
                <label for="oldPassword">Old Password</label>
                <div class="password-field">
                    <input type="password" id="oldPassword" name="oldPassword" required>
                </div>

                <label for="newPassword">New Password</label>
                <div class="password-field">
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>

                <label for="confirmPassword">Confirm New Password</label>
                <div class="password-field">
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <p class="error" id="error-message" style="color: red; display: none; font-size: small; font-weight: 500;">Invalid password. Please try again.</p>
                <p class="error" id="password-mismatch" style="color: red; display: none; font-size: small; font-weight: 500;">Passwords do not match. Please try again.</p>
                <button type="submit">Save Changes</button>
            </div>
        </form>

            </div>

             <!-- Room Details Section -->
             <div id="roomDetails" class="info-section" style="display: none;">
                <div class="room-detail-container">
                    <!-- Room Image -->
                    <div class="room-image">
                        <img src="../../ADVERTISEMENT/ADD/uploads/<?php echo htmlspecialchars($room_image); ?>" alt="<?php echo htmlspecialchars($room_number); ?>" style="width: 100%; border-radius: 8px;">
                    </div>



                    <!-- Room Details -->
                    <div class="room-details">
                        <div class="room-info">
                            <h1>Room Details</h1>
                            <div class="room-detail">
                                <span>Room Number:</span>
                                <span><?php echo htmlspecialchars($room_number); ?></span>
                            </div>
                            <div class="room-detail">
                                <span>Room Type:</span>
                                <span><?php echo htmlspecialchars($room_type); ?></span>
                            </div>
                            <div class="room-detail">
                                <span>Date of Check-In:</span>
                                <span><?php echo htmlspecialchars($booking_start_date); ?></span>
                            </div>
                            <div class="room-detail">
                                <span>Check-In Time:</span>
                                <span><?php echo date('h:i A', strtotime($booking_start_date)); ?></span>
                            </div>
                            <div class="room-detail">
                                <span>Room Size:</span>
                                <span><?php echo htmlspecialchars($room_size); ?> m²</span>
                            </div>
                        </div>

                        <!-- Amenities Section -->
                        <div class="ami">
                            <div class="amin">
                                <h2>Amenities</h2>
                                <p><?php echo htmlspecialchars($room_amenities); ?></p>
                            </div>
                            <div class="uti">
                                <h2>Utilities</h2>
                                <p><?php echo htmlspecialchars($room_utilities); ?></p>
                            </div>
                        </div>

                        <!-- Room Conditions -->
                        <div class="room-conditions">
                            <h2>Room Conditions</h2>
                            <div class="room-condition">
                                <span>Deposit Amount:</span>
                                <span>₱<?php echo number_format($deposit_rate, 2); ?></span>
                            </div>
                            <div class="room-condition">
                                <span>Payment Frequency:</span>
                                <span><?php echo htmlspecialchars($payment_frequency); ?></span>
                            </div>
                            <div class="room-condition">
                                <span>Number of Occupants:</span>
                                <span><?php echo htmlspecialchars($number_of_occupants); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="lease-agreement" id="leaseAgreement" style="display: none;">
                <h1>Lease Agreement</h1>
                <p>Please download your lease agreement by clicking the link below:</p>
                <a href="../image/BHMS.png" download class="download-link">
                    <i class="fas fa-file-pdf"></i> Download Lease Agreement
                </a>
            </div>

        </div>

        <!-- Update Personal Details Modal -->
        <div id="updateModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Update Personal Information</h2>
                <p class="error" id="error-message" style="color: red; display: none; font-size: small; font-weight: 500;">Invalid input. Please try again.</p>
                <form id="updateForm" method="post" action="updateDetails.php">
                    <!-- Using PHP to echo session values for pre-filling -->
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($_SESSION['fname']) && isset($_SESSION['lname']) ? $_SESSION['fname'] . ' ' . $_SESSION['lname'] : ''; ?>" >

                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender">
                        <option value="male" <?php echo (isset($_SESSION['gender']) && $_SESSION['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo (isset($_SESSION['gender']) && $_SESSION['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                    </select>

                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>">

                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email_address']) ? $_SESSION['email_address'] : ''; ?>">

                    <label for="contact">Contact Number:</label>
                    <input type="tel" id="contact" name="contact" value="<?php echo isset($_SESSION['contact_number']) ? $_SESSION['contact_number'] : ''; ?>">

                    <label for="religion">Religion:</label>
                    <input type="text" id="religion" name="religion" value="<?php echo isset($_SESSION['religion']) ? $_SESSION['religion'] : ''; ?>">

                    <label for="nationality">Nationality:</label>
                    <input type="text" id="nationality" name="nationality" value="<?php echo isset($_SESSION['nationality']) ? $_SESSION['nationality'] : ''; ?>">

                    <label for="occupation">Occupation:</label>
                    <input type="text" id="occupation" name="occupation" value="<?php echo isset($_SESSION['occupation']) ? $_SESSION['occupation'] : ''; ?>">

                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </div>



        <!-- Hidden Form for Image Upload -->
        <form id="uploadForm" method="POST" action="upload.php" enctype="multipart/form-data" style="display: none;">
            <input type="file" name="profileImage" id="profileImageInput" accept="image/*" onchange="previewImage(event)">
            <input type="submit" value="Upload Profile Picture">
        </form>

    </div>

    <script src="tenantjs.js"></script>
    <script src="../Back-End/errorhandlers.js"></script>
    <script src="update.js"></script>

</body>
</html>