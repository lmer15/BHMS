<?php 
include_once(__DIR__ . "/../DATABASE/dbConnector.php"); 
$tenant_id = isset($_GET['tenant_id']) ? (int)$_GET['tenant_id'] : 0;  

if ($tenant_id == 0) { 
    echo "Invalid tenant ID."; 
    exit(); 
}  

// Fetch deposit amount for the tenant's room 
$query = "SELECT r.room_deporate FROM booking b JOIN room r ON b.room_id = r.room_id WHERE b.tenant_id = ?";
$stmt = mysqli_prepare($conn, $query); 
mysqli_stmt_bind_param($stmt, "i", $tenant_id); 
mysqli_stmt_execute($stmt); 
$result = mysqli_stmt_get_result($stmt);  

if ($row = mysqli_fetch_assoc($result)) { 
    $deposit_amount = $row['room_deporate']; 
} else { 
    echo "Room details not found."; 
    exit(); 
} 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Deposit</title>
    <script src="../imported_links.js" defer></script>
    <style>
        /* General Styles */
        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background-color: black;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Heading Style */
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #4CAF50;
            font-weight: bold;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
        }

        input[type="number"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
            margin-bottom: 10px; 
        }

        input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Submit Button Styling */
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%; 
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-submit:hover {
            background-color: white;
            color: #4CAF50;
            border: solid 1px #4CAF50;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .container {
                padding: 20px;
                width: 100%;
            }

            h1 {
                font-size: 20px;
            }

            input[type="number"] {
                padding: 10px;
                font-size: 14px;
            }

            .btn-submit {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pay Deposit</h1>
        <form action="process_payment.php" method="POST">
            <input type="hidden" name="tenant_id" value="<?php echo htmlspecialchars($tenant_id); ?>">

            <div class="form-group">
                <label for="deposit">Deposit Amount (<?php echo htmlspecialchars($deposit_amount); ?> PHP):</label>
                <input type="number" id="deposit" name="deposit" value="<?php echo htmlspecialchars($deposit_amount); ?>" readonly>
            </div>

            <button type="submit" class="btn-submit">
                <img src="https://www.paypalobjects.com/webstatic/icon/pp258.png" alt="PayPal" style="width: 30px; vertical-align: middle; margin-right: 10px;">
                Proceed to Payment
            </button>
        </form>
    </div>

    <script>
        // JavaScript to warn the user before leaving the page
        window.addEventListener('beforeunload', function (event) {
            var message = "Are you sure you want to leave? You may lose your progress.";
            event.returnValue = message; // Standard for most browsers
            return message; // For some older browsers
        });
    </script>
</body>
</html>
