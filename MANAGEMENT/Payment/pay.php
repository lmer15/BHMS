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
                                <th>Balance</th>
                                <th>Last Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>

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
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Payment Form Modal (Initially hidden) -->
    <div id="paymentForm" class="payment-form">
        <span class="close-btn" onclick="closePaymentForm()">×</span>
        <h2>Process Payment</h2>
        <form method="POST" action="">
            <label for="tenantName">Tenant Name:</label>
            <input type="text" id="tenantName" name="tenantName" placeholder="Enter tenant name" required>

            <label for="roomNumber">Room Number:</label>
            <input type="text" id="roomNumber" name="tenantName" placeholder="Enter tenant name" required>

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
