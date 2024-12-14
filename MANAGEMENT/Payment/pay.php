<?php
// Include database connection
include('../../DATABASE/dbConnector.php');

// Initialize total variables
$totalOverdue = 0;
$totalPaidRent = 0;

// Fetch Total Overdue Rent
$queryDue = "SELECT t.fname, t.lname, r.total_rent - r.amount_paid AS due_amount, r.rent_period_end, r.status 
             FROM rental_payments r 
             JOIN tenant_details t ON r.tenant_id = t.tc_id 
             WHERE r.balance > 0"; 
$resultDue = mysqli_query($conn, $queryDue);

// Calculate the total overdue amount
while($row = mysqli_fetch_assoc($resultDue)) {
    $totalOverdue += $row['due_amount']; // Summing up the due amounts
}

// Fetch Total Paid Rent
$queryPaid = "SELECT t.fname, t.lname, r.amount_paid, r.payment_date 
              FROM rental_payments r 
              JOIN tenant_details t ON r.tenant_id = t.tc_id 
              WHERE r.balance = 0 AND r.status = 'Paid'"; // Tenants with zero balance and Paid status
$resultPaid = mysqli_query($conn, $queryPaid);

// Calculate the total paid amount
while($row = mysqli_fetch_assoc($resultPaid)) {
    $totalPaidRent += $row['amount_paid']; // Summing up the paid amounts
}

// Fetch Payment History
$queryHistory = "SELECT t.fname, t.lname, p.payment_date, p.payment_amount 
                 FROM payment_history p 
                 JOIN tenant_details t ON p.tenant_id = t.tc_id";
$resultHistory = mysqli_query($conn, $queryHistory);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <link rel="stylesheet" href="pay.css?v=1.11">
</head>
<body>

    <div class="wrapper">
        <div class="dash">
            <div class="sum">
                <!-- Total Overdue Rent -->
                <div class="due">
                    <p>₱<?php echo number_format($totalOverdue, 2); ?></p>
                    <h1>Total Overdue Rent</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>Tenant Name</th>
                                <th>Due Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
    
                        mysqli_data_seek($resultDue, 0); 
                        while($row = mysqli_fetch_assoc($resultDue)): ?>
                            <tr>
                                <td><?php echo $row['fname'] . " " . $row['lname']; ?></td>
                                <td>₱<?php echo number_format($row['due_amount'], 2); ?></td>
                                <td><?php echo $row['rent_period_end']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

               <!-- Total Paid Rent -->
                <div class="paid">
                    <p>₱<?php echo number_format($totalPaidRent, 2); ?></p>
                    <h1>Total Paid Rent</h1>
                    <table id="paidRentTable">
                        <thead>
                            <tr>
                                <th>Tenant Name</th>
                                <th>Amount Paid</th>
                                <th>Last Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Reset result pointer for displaying paid tenants
                        mysqli_data_seek($resultPaid, 0); 
                        while($row = mysqli_fetch_assoc($resultPaid)): ?>
                            <tr>
                                <td><?php echo $row['fname'] . " " . $row['lname']; ?></td>
                                <td>₱<?php echo number_format($row['amount_paid'], 2); ?></td>
                                <td><?php echo $row['payment_date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- Search Bar and Process Payment Button -->
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchTable()">
                <span id="process-payment-btn" onclick="showPaymentForm()">Process Payment</span>
            </div>

            <!-- Payment History Table -->
            <div class="table">
                <table id="paymentHistoryTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Tenant Name</th>
                            <th>Payment Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($row = mysqli_fetch_assoc($resultHistory)): ?>
                        <tr>
                            <td><?php echo $row['payment_date']; ?></td>
                            <td><?php echo $row['fname'] . " " . $row['lname']; ?></td>
                            <td>₱<?php echo number_format($row['payment_amount'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Payment Form Modal (Initially hidden) -->
    <div id="paymentForm" class="payment-form">
        <span class="close-btn" onclick="closePaymentForm()">×</span>
        <h2>Process Payment</h2>
        <form method="POST" action="process_payment.php">
            <label for="tenantName">Tenant Name:</label>
            <input type="text" id="tenantName" name="tenantName" placeholder="Enter tenant name" required>

            <label for="roomNumber">Room Number:</label>
            <input type="text" id="roomNumber" name="roomNumber" placeholder="Enter room number" required>

            <label for="paymentAmount">Payment Amount:</label>
            <input type="number" id="paymentAmount" name="paymentAmount" placeholder="Amount" required>

            <button type="submit">Submit Payment</button>
        </form>
    </div>

    <script>
        // Show the payment form when the Process Payment button is clicked
        function showPaymentForm() {
            const form = document.getElementById('paymentForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }

        // Close the modal when the close button is clicked
        function closePaymentForm() {
            const form = document.getElementById('paymentForm');
            form.style.display = 'none';
        }

        // Search function for filtering the table
        let timeout;
        function searchTable() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                let input = document.getElementById('searchInput');
                let filter = input.value.toUpperCase();
                let paymentTable = document.getElementById('paymentHistoryTable');
                let rows = paymentTable.getElementsByTagName("tr");

                // Loop through all rows in the Payment History table
                for (let i = 1; i < rows.length; i++) {
                    let cells = rows[i].getElementsByTagName("td");
                    let match = false;

                    // Loop through each cell in the row
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j]) {
                            let textValue = cells[j].textContent || cells[j].innerText;
                            if (textValue.toUpperCase().indexOf(filter) > -1) {
                                match = true;
                            }
                        }
                    }

                    // If a match is found, display the row, otherwise hide it
                    rows[i].style.display = match ? "" : "none";
                }
            }, 300); // 300ms delay after typing stops
        }
    </script>

</body>
</html>
