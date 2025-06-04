<?php
require_once 'init_session.php';
require_once 'db.php'; // For PDO
require_once 'admin_helpers.php'; // For hasNewInquiries()

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['login_error'] = 'Please log in to access the admin dashboard.';
    header('Location: admin_login.php');
    exit;
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';

// Generate a CSRF token for the logout form if one doesn't exist
if (empty($_SESSION['logout_csrf_token'])) {
    $_SESSION['logout_csrf_token'] = bin2hex(random_bytes(32));
}

$has_new_inquiries = hasNewInquiries($pdo);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="admin-body">
    <nav class="navbar navbar-expand-lg navbar-expand-lg sticky-top manga-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
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
             <h1>Dashboard</h1>
        </div>

        <p>Welcome to the admin dashboard. This is where you will manage inquiries and other site content.</p>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-envelope-open-text me-2"></i>View Inquiries</h5>
                        <p class="card-text">Click here to view all submitted business inquiries.</p>
                        <a href="view_inquiries.php" class="btn btn-primary">View Inquiries</a> <!-- Create this page later -->
                    </div>
                </div>
            </div>
            <!-- Add more cards for other admin functionalities later -->
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="#" class="nav-item active">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="view_inquiries.php" class="nav-item">
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
