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

                <div class="change-pass" style="display: block;">
                    <h2>Change Password</h2>
                    
                    <!-- Show error message if exists -->
                    <?php if (!empty($errorMessage)): ?>
                    <p class="error" style="color: red; font-size: small; font-weight: 500;"><?php echo $errorMessage; ?></p>
                    <?php endif; ?>

                    <!-- Show success message if exists -->
                    <?php if (!empty($successMessage)): ?>
                    <p class="success" style="color: green; font-size: small; font-weight: 500;"><?php echo $successMessage; ?></p>
                    <script>
                        // Show a pop-up after success
                        alert("Password changed successfully!");
                        // Redirect to profile page after 2 seconds
                        setTimeout(function() {
                            window.location.href = '../tProfile.php';
                        }, 2000);
                    </script>
                    <?php endif; ?>

                    <form id="updatePass" method="post" action="">
                        <label for="oldPassword">Old Password</label>
                        <div class="password-field">
                            <input type="password" id="oldPassword" name="oldPassword">
                        </div>

                        <label for="newPassword">New Password</label>
                        <div class="password-field">
                            <input type="password" id="newPassword" name="newPassword">
                        </div>

                        <label for="confirmPassword">Confirm New Password</label>
                        <div class="password-field">
                            <input type="password" id="confirmPassword" name="confirmPassword">
                        </div>

                        <button type="submit">Save Changes</button>
                    </form>
                </div>
                

                
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
                <form id="updateForm">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="Elmer Solitario Rapon">
  
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender">
                        <option value="male" selected>Male</option>
                        <option value="female">Female</option>
                    </select>

                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="raponelmer15@gmail.com">

                    <label for="contact">Contact Number:</label>
                    <input type="tel" id="contact" name="contact" value="+03 906 838 7448">

                    <label for="religion">Religion:</label>
                    <input type="text" id="religion" name="religion" value="Roman Catholic">

                    <label for="nationality">Nationality:</label>
                    <input type="text" id="nationality" name="nationality" value="Filipino">

                    <label for="occupation">Occupation:</label>
                    <input type="text" id="occupation" name="occupation" value="Software Engineer">

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