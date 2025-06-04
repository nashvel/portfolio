<?php
require_once 'init_session.php';

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['login_error'] = 'Please log in to access this page.';
    header('Location: admin_login.php');
    exit;
}

require_once 'db.php'; // Database connection
require_once 'admin_helpers.php'; // For hasNewInquiries()
$admin_username = $_SESSION['admin_username'] ?? 'Admin';

// Generate a CSRF token for the logout form if one doesn't exist
if (empty($_SESSION['logout_csrf_token'])) {
    $_SESSION['logout_csrf_token'] = bin2hex(random_bytes(32));
}

// Call hasNewInquiries before 'last_notification_view_time' is updated by this page load.
$has_new_inquiries = hasNewInquiries($pdo);

// Fetch recent inquiries
$recent_inquiries = [];
$fetch_error = null;
try {
    $sql_recent = "SELECT id, name, email, message, business_type, created_at FROM messages ORDER BY created_at DESC LIMIT 5";
    $stmt = $pdo->prepare($sql_recent);
    $stmt->execute();
    $recent_inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error fetching recent inquiries: ' . $e->getMessage());
    $fetch_error = 'Could not retrieve recent inquiries from the database.';
}

// Mark notifications as seen by storing the timestamp of the newest inquiry or current time
if (!empty($recent_inquiries)) {
    $_SESSION['last_notification_view_time'] = $recent_inquiries[0]['created_at'];
} elseif (!$fetch_error) { // Only update if there wasn't an error and no inquiries (i.e., table is empty or all are older)
    // If no inquiries, set last view time to now, so any new one will be 'new'
    // Or, if the table is truly empty, this means all (zero) have been seen.
    $_SESSION['last_notification_view_time'] = date('Y-m-d H:i:s'); 
}
// If $fetch_error is true, we don't update the last_notification_view_time, 
// so the badge might persist until the error is resolved and notifications can be properly viewed.

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="admin-body">
    <nav class="navbar navbar-expand-lg sticky-top manga-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            Welcome, <?php echo htmlspecialchars($admin_username); ?>!
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="logout.php" method="POST" style="display: inline;">
                            <input type="hidden" name="logout_csrf_token" value="<?php echo htmlspecialchars($_SESSION['logout_csrf_token']); ?>">
                            <button type="submit" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt me-1"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="admin-dashboard-header rounded p-3 mb-4">
             <h1><i class="fas fa-bell me-2"></i>Recent Inquiries</h1>
        </div>

        <?php if ($fetch_error): ?>
            <div class="alert alert-danger manga-alert" role="alert">
                <p class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($fetch_error); ?></p>
            </div>
        <?php elseif (empty($recent_inquiries)):
            // Check if there are any inquiries at all to differentiate message
            $total_inquiries_count = 0;
            try {
                $sql_count = "SELECT COUNT(*) FROM messages";
                $count_stmt = $pdo->prepare($sql_count);
                $count_stmt->execute();
                $total_inquiries_count = (int) $count_stmt->fetchColumn();
            } catch (PDOException $e) { /* Do nothing, error already logged or handled by $fetch_error */ }

            if ($total_inquiries_count === 0) : ?>
                <div class="alert alert-secondary manga-alert" role="alert">
                    <p class="mb-0"><i class="fas fa-info-circle me-2"></i>No inquiries found in the system yet.</p>
                </div>
            <?php else: ?>
                <div class="alert alert-secondary manga-alert" role="alert">
                    <p class="mb-0"><i class="fas fa-check-circle me-2"></i>No new notifications at the moment, or all inquiries have been displayed.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <?php foreach ($recent_inquiries as $inquiry): ?>
                <div class="alert manga-alert mb-3" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-user-tie me-2"></i><?php echo htmlspecialchars($inquiry['name']); ?></h5>
                    <p class="mb-1">
                        <strong>Business Type:</strong> <?php echo htmlspecialchars($inquiry['business_type']); ?><br>
                        <strong>Received:</strong> <?php echo htmlspecialchars(date('M d, Y H:i A', strtotime($inquiry['created_at']))); ?>
                    </p>
                    <hr class="my-2">
                    <p class="mb-0 small-text">
                        <?php echo nl2br(htmlspecialchars(substr($inquiry['message'], 0, 100))); ?><?php echo strlen($inquiry['message']) > 100 ? '...' : ''; ?>
                    </p>
                </div>
            <?php endforeach; ?>
            <div class="mt-3 text-center">
                 <a href="view_inquiries.php" class="btn manga-button"><i class="fas fa-list-alt me-2"></i>View All Inquiries</a>
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <a href="admin_dashboard.php" class="btn manga-btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
        </div>
    </div>

    <!-- Mobile Bottom Navigation (Copied from admin_dashboard.php for consistency) -->
    <nav class="mobile-bottom-nav">
        <a href="admin_dashboard.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="view_inquiries.php" class="nav-item">
            <i class="fas fa-envelope-open-text"></i>
            <span>Inquiries</span>
        </a>
        <a href="admin_notifications.php" class="nav-item active <?php echo $has_new_inquiries ? 'has-badge' : ''; ?>"> <!-- Active state for current page -->
            <i class="fas fa-bell"></i>
            <span>Alerts</span>
            <?php if ($has_new_inquiries): ?><span class="notification-badge"></span><?php endif; ?>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
