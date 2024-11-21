<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TENANT PROFILE</title>
    <link rel="stylesheet" href="tProfile.css">
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
                        <span>Elmer Solitario Rapon</span>
                        <span>@lmer15</span>
                    </div>
                
                    <a href="" id="updateLink"><i class="fas fa-edit"></i></a>
                </div>                

                <div class="descpro">
                    <h1>PERSONAL DETAILS</h1>
                    <span>Name</span>
                    <span>Elmer Solitario Rapon</span>

                    <span>Gender</span>
                    <span>Male</span>

                    <span>Email Address</span>
                    <span>raponelmer15@gmail.com</span>

                    <span>Contact Number</span>
                    <span>+63 906 838 7448</span>
                    
                    <span>Religion</span>
                    <span>Roman Catholic</span>

                    <span>Nationality</span>
                    <span>Filipino</span>

                    <span>Occupation</span>
                    <span>Software Engineer</span>

                </div>

                
                <div class="roominfo">
                    <h1>ROOM DETAILS</h1>
                    <span>Room Number</span>
                    <span>RM0031</span>

                    <span>Room Type</span>
                    <span>Double-Deck</span>

                    <span>Date Check-In</span>
                    <span>December 16, 2024</span>

                    <span>Time Check-In</span>
                    <span>7:35 AM</span>

                    <span>Number of Occupants</span>
                    <span>4</span>

                    <span>Rental Monthly Rates</span>
                    <span style="color: rgb(228, 31, 31);">₱4,455.00</span>

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
`               </form>

            </div>

            <!-- Room Details Section -->
            <div id="roomDetails" class="info-section" style="display: none;">                
                <div class="room-detail-container">
                    <!-- Room Image -->
                    <div class="room-image">
                        <img src="../../ADVERTISEMENT/images/RM8.jpg" alt="Room RM0031" style="width: 100%; border-radius: 8px;">
                    </div>

                    <!-- Room Details -->
                    <div class="room-details">
                        <div class="room-info">
                            <h1>Room Details</h1>
                            <div class="room-detail">
                                <span>Room Number:</span>
                                <span>RM0031</span>
                            </div>
                            <div class="room-detail">
                                <span>Room Type:</span>
                                <span>Double-Deck</span>
                            </div>
                            <div class="room-detail">
                                <span>Date of Check-In:</span>
                                <span>December 16, 2024</span>
                            </div>
                            <div class="room-detail">
                                <span>Check-In Time:</span>
                                <span>7:35 AM</span>
                            </div>
                            <div class="room-detail">
                                <span>Room Size:</span>
                                <span>25 m²</span>
                            </div>
                        </div>

                        <!-- Amenities Section -->
                         <div class="ami">
                            <h2>Amenities</h2>
                            <p>Air-conditioning, Mini-Kitchen, Own Bathroom, Wi-Fi, Daily Cleaning, Own Water Counter, Television, Heater. </p>
                        </div>

                        <!-- Room Conditions -->
                        <div class="room-conditions">
                            <h2>Room Conditions</h2>
                            <div class="room-condition">
                                <span>Deposit Amount:</span>
                                <span>₱2,000.00</span>
                            </div>
                            <div class="room-condition">
                                <span>Payment Frequency:</span>
                                <span>Monthly</span>
                            </div>
                            <div class="room-condition">
                                <span>Deposit Rate:</span>
                                <span>₱5,000.00</span>
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
                    <p class="error" id="error-message" style="color: red; display: none; font-size: small; font-weight: 500;">Invalid password. Please try again.</p>
                    <p class="error" id="password-mismatch" style="color: red; display: none; font-size: small; font-weight: 500;">Passwords do not match. Please try again.</p>
                    <form id="updateForm" method="post" action="updateDetails.php">
                        <!-- Using PHP to echo session values for pre-filling -->
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo isset($_SESSION['fname']) && isset($_SESSION['lname']) ? $_SESSION['fname'] . ' ' . $_SESSION['lname'] : ''; ?>" >

                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender">
                            <option value="male" <?php echo (isset($_SESSION['gender']) && $_SESSION['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo (isset($_SESSION['gender']) && $_SESSION['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                        </select>

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

</body>
</html>