<?php
require_once 'init_session.php';
require_once 'db.php'; // Database connection
require_once 'admin_helpers.php'; // For hasNewInquiries()

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['login_error'] = 'Please log in to view inquiries.';
    header('Location: admin_login.php');
    exit;
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';

// Generate a CSRF token for the logout form if one doesn't exist
if (empty($_SESSION['logout_csrf_token'])) {
    $_SESSION['logout_csrf_token'] = bin2hex(random_bytes(32));
}

$has_new_inquiries = hasNewInquiries($pdo);

// Fetch all messages from the database
$messages = [];
try {
    $sql = "SELECT id, name, email, message, business_type, capstone_type, language, dev_focus, db_type, latitude, longitude, user_agent, created_at FROM messages ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error, e.g., log it or display a user-friendly message
    error_log('Error fetching inquiries: ' . $e->getMessage());
    $page_error = 'Could not retrieve inquiries from the database.';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inquiries - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin_style.css">
    <style>
        .table-responsive {
            max-height: 70vh; /* Adjust as needed */
        }
        th {
            position: sticky;
            top: 0;
            background: white; /* To prevent text overlap during scroll */
            z-index: 10;
        }
    </style>
</head>
<body class="admin-body">
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top manga-navbar">
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
                            <button type="submit" class="btn btn-danger"><i class="fas fa-sign-out-alt me-1"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Submitted Inquiries</h2>
            <a href="admin_dashboard.php" class="btn manga-button">Back to Dashboard</a>
        </div>

        <?php if (isset($page_error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($page_error); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($messages) && !isset($page_error)): ?>
            <div class="alert alert-info" role="alert">
                No inquiries found.
            </div>
        <?php elseif (!empty($messages)): ?>
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-striped table-hover table-bordered manga-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Business Type</th>
                            <th>Capstone Type</th>
                            <th>Language/Framework</th>
                            <th>Dev Focus</th>
                            <th>DB Type</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>User Agent</th>
                            <th>Received At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['id']); ?></td>
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td style="max-width: 200px; overflow-wrap: break-word;"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
                                <td><?php echo htmlspecialchars($msg['business_type']); ?></td>
                                <td><?php echo htmlspecialchars($msg['capstone_type'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($msg['language'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($msg['dev_focus'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($msg['db_type'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($msg['latitude'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($msg['longitude'] ?? 'N/A'); ?></td>
                                <td style="max-width: 200px; overflow-wrap: break-word;"><?php echo htmlspecialchars($msg['user_agent'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($msg['created_at']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="admin_dashboard.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="view_inquiries.php" class="nav-item active">
            <i class="fas fa-envelope-open-text"></i>
            <span>Inquiries</span>
        </a>
        <a href="admin_notifications.php" class="nav-item <?php echo $has_new_inquiries ? 'has-badge' : ''; ?>">
            <i class="fas fa-bell"></i>
            <span>Alerts</span>
            <?php if ($has_new_inquiries): ?><span class="notification-badge"></span><?php endif; ?>
        </a>
    </nav>
</body>
</html>
