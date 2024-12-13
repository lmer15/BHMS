<?php
session_start();
include '../../DATABASE/dbConnector.php';

if (!isset($_SESSION['tc_id'])) {
    die('You must be logged in to access the dashboard.');
}

$tenant_id = $_SESSION['tc_id'];

// Fetch Payment History using MySQLi
$query_payment = "SELECT payment_date, payment_amount, payment_status 
                  FROM payment_history 
                  WHERE tenant_id = ? 
                  ORDER BY payment_date DESC LIMIT 6";
$stmt_payment = $conn->prepare($query_payment);
$stmt_payment->bind_param('i', $tenant_id);  // 'i' denotes integer
$stmt_payment->execute();
$result_payment = $stmt_payment->get_result();
$payments = $result_payment->fetch_all(MYSQLI_ASSOC);

// Fetch Maintenance Requests using MySQLi
$query_maintenance = "SELECT date_requested, item_name, item_desc, status 
                      FROM maintenance_requests 
                      WHERE tenant_id = ? 
                      ORDER BY date_requested DESC LIMIT 4";
$stmt_maintenance = $conn->prepare($query_maintenance);
$stmt_maintenance->bind_param('i', $tenant_id);
$stmt_maintenance->execute();
$result_maintenance = $stmt_maintenance->get_result();
$maintenance_requests = $result_maintenance->fetch_all(MYSQLI_ASSOC);

// Fetch Pending Payments using MySQLi
$query_pending_payments = "SELECT rent_period_start, rent_period_end, balance, payment_date 
                           FROM rental_payments 
                           WHERE tenant_id = ? AND balance > 0 
                           AND (status = 'Pending' OR status = 'Overdue')
                           ORDER BY rent_period_start DESC";
$stmt_pending_payments = $conn->prepare($query_pending_payments);
$stmt_pending_payments->bind_param('i', $tenant_id);
$stmt_pending_payments->execute();
$result_pending_payments = $stmt_pending_payments->get_result();
$pending_payments = $result_pending_payments->fetch_all(MYSQLI_ASSOC);

// Fetch Notifications using MySQLi
$query_notifications = "SELECT message, created_at 
                        FROM notifications
                        WHERE type = 'maintenance-admin' 
                        ORDER BY created_at DESC";
$result_notifications = $conn->query($query_notifications);
$notifications = $result_notifications->fetch_all(MYSQLI_ASSOC);

// Fetch Latest "What's New" Service using MySQLi
$query_whats_new = "SELECT amiser_title, amiser_desc 
                    FROM aminities_services 
                    ORDER BY amiser_id DESC LIMIT 1";
$result_whats_new = $conn->query($query_whats_new);
$whats_new = $result_whats_new->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USER DASHBOARD</title>
    <link rel="stylesheet" href="styletDashboard.css?v=1.0">
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <section id="homepage">    
        <div class="left-section">
            <section class="payment">
                <div class="payment-head">
                    <a href="#"><i class='bx bx-wallet icon'></i><span>PAYMENT HISTORY</span></a>
                </div>
                <div class="payment-table-container">
                    <div class="payment-table">
                        <div class="table-header">
                            <div class="table-header-item">Date</div>
                            <div class="table-header-item">Amount</div>
                            <div class="table-header-item">Status</div>
                        </div>
                        <?php foreach ($payments as $payment): ?>
                            <div class="table-row">
                                <div class="table-item"><?= date('F j, Y', strtotime($payment['payment_date'])) ?></div>
                                <div class="table-item">₱<?= number_format($payment['payment_amount'], 2) ?></div>
                                <div class="table-item"><?= $payment['payment_status'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="maintenance">
                <div class="maintenance-head">
                    <a href="#"><i class='bx bx-wrench icon'></i><span>MAINTENANCE REQUEST</span></a>
                </div>
                <div class="maintenance-table">
                    <div class="maintenance-table-header">
                        <div class="maintenance-header-item">Date Requested</div>
                        <div class="maintenance-header-item">Details</div>
                        <div class="maintenance-header-item">Status</div>
                    </div>
                    <?php foreach ($maintenance_requests as $request): ?>
                        <div class="maintenance-table-row">
                            <div class="maintenance-item"><?= date('F j, Y', strtotime($request['date_requested'])) ?></div>
                            <div class="maintenance-item">
                                <h1><?= htmlspecialchars($request['item_name']) ?></h1>
                                <p><?= htmlspecialchars($request['item_desc']) ?></p>
                            </div>
                            <div class="maintenance-item">
                                <div class="tooltip">
                                    <i class="fas fa-<?= ($request['status'] == 'Completed' ? 'check-circle' : 'spinner') ?>"></i> <?= $request['status'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <section class="pending-notif">
            <div class="paywhat">
                <div class="wrapper-list">
                    <h2>PENDING PAYMENTS</h2>
                    <div class="pending-payment">
                        <div class="total-pending">
                            <h1>₱<?= number_format(array_sum(array_column($pending_payments, 'balance')), 2) ?></h1>
                            <p>Total Pending Bills</p>
                        </div>
                        <div class="lists">
                            <?php foreach ($pending_payments as $payment): ?>
                                <div class="list-payments">
                                    <p><i class='bx bx-error icon'></i>Your balance bill from <?= date('F j, Y', strtotime($payment['rent_period_start'])) ?> to <?= date('F j, Y', strtotime($payment['rent_period_end'])) ?>: <span class="amount">₱<?= number_format($payment['balance'], 2) ?></span></p> 
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="whatsnew">
                    <h1>What's New?</h1>
                    <div class="content">
                        <img src="../image/rb_7944.png" alt="What's New?">
                        <div class="text-content">
                            <h1><?= htmlspecialchars($whats_new['amiser_title']) ?></h1>
                            <p><?= htmlspecialchars($whats_new['amiser_desc']) ?> <a href="#">Learn more.</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="notif">
                <h2> <i class="fas fa-bell icon"></i>NOTIFICATIONS</h2>
                <div class="notif-container">
                    <?php foreach ($notifications as $notif): ?>
                        <div class="notifi">
                            <h2 class="title">Maintenance</h2>
                            <p class="description"><?= htmlspecialchars($notif['message']) ?></p>
                            <div class="timestamp">
                                <span>🕒</span> <?= date('F j, Y \a\t g:i A', strtotime($notif['created_at'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </section>  
</body>
</html>
