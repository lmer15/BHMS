<?php
require_once '../../DATABASE/dbConnector.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registrationError = '';
    $business_name = trim($_POST['business_name'] ?? '');
    $acronym = trim($_POST['acronym'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $established_year = trim($_POST['established_year'] ?? '');

    if (!$business_name || !$email || !$phone || !$address || !$description) {
        $registrationError = 'All required fields must be filled.';
    }

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logoTmpName = $_FILES['logo']['tmp_name'];
        $logoName = $_FILES['logo']['name'];
        $logoType = $_FILES['logo']['type'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($logoType, $allowedTypes)) {
            $registrationError = 'Invalid logo format. Only JPEG, PNG, and GIF are allowed.';
        } else {
            $uploadDir = '../../uploads/business_logos/';

            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    die(json_encode(['status' => 'error', 'message' => 'Failed to create upload directory.']));
                }
            }

            $uniqueName = uniqid() . '_' . basename($logoName);
            $logoPath = $uploadDir . $uniqueName;
            
            // Move uploaded file
            if (!move_uploaded_file($logoTmpName, $logoPath)) {
                die(json_encode(['status' => 'error', 'message' => 'Failed to save uploaded file.']));
            }
            
        }
    } else {
        $registrationError = 'Please upload a logo.';
    }

    if (!$registrationError) {
        $result = $conn->query("SELECT COUNT(*) AS count FROM business_profiles");
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Data already exists. Only one profile can be added.']);
        } else {
            $stmt = $conn->prepare("INSERT INTO business_profiles (business_name, business_acronym, business_logo, business_email, business_phone, business_address, business_description) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $business_name, $acronym, $logoPath, $email, $phone, $address, $description);
    
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Business profile registered successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database error. Please try again.']);
            }
            $stmt->close();
        }
    }
    
}
?>
