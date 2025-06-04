<?php
session_start();
// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Inquiry - Nacht </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Nacht</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php#projects">Projects</a></li>
        <li class="nav-item"><a class="nav-link active" href="business_form.php">Business Inquiry</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5 mb-5">
    <section id="business-inquiry-section">
        <h2 class="mb-4">Business Inquiry</h2>
        <p>Please provide details about your business needs.</p>
        <form id="business-inquiry-form" method="POST" action="contact.php" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            
            <div class="mb-3">
                <label for="business_type" class="form-label">What type of business is it?</label>
                <select class="form-select" id="business_type" name="business_type" required>
                    <option value="" selected disabled>Select type...</option>
                    <option value="Capstone">Capstone</option>
                    <option value="Project">Project</option>
                    <option value="Activity">Activity</option>
                    <option value="Buy a system">Buy a system</option>
                </select>
            </div>

            <div class="mb-3" id="capstone_type_group" style="display:none;">
                <label for="capstone_type" class="form-label">Select Capstone Type:</label>
                <select class="form-select" id="capstone_type" name="capstone_type">
                    <option value="" selected disabled>Select capstone type...</option>
                    <option value="Website">Website</option>
                    <option value="Gui">GUI Application</option>
                    <option value="App">Mobile App</option>
                </select>
            </div>

            <div class="mb-3" id="language_group" style="display:none;">
                <label for="language" class="form-label">Select Language/Framework:</label>
                <select class="form-select" id="language" name="language">
                    <option value="" selected disabled>Select language...</option>
                    <!-- Options will be populated by JavaScript -->
                </select>
            </div>

            <div class="mb-3" id="dev_focus_group" style="display:none;">
                <label for="dev_focus" class="form-label">Development Focus:</label>
                <select class="form-select" id="dev_focus" name="dev_focus">
                    <option value="" selected disabled>Select focus...</option>
                    <option value="Frontend">Frontend</option>
                    <option value="Backend">Backend</option>
                    <option value="Both">Both</option>
                </select>
            </div>

            <div class="mb-3" id="db_type_group" style="display:none;">
                <label for="db_type" class="form-label">Database Type:</label>
                <select class="form-select" id="db_type" name="db_type">
                    <option value="" selected disabled>Select database type...</option>
                    <option value="MySQL">MySQL</option>
                    <option value="No DB (offline)">No DB (offline)</option>
                    <option value="Other">Other (Specify in message)</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="name" name="name" required maxlength="100">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="email" name="email" required maxlength="150">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Additional Message/Details</label>
                <textarea class="form-control" id="message" name="message" rows="4" required maxlength="1000"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Inquiry</button>
            <div id="business-success-message" class="alert alert-success mt-3" style="display:none;">Inquiry sent! Thank you.</div>
            <div id="business-error-message" class="alert alert-danger mt-3" style="display:none;">There was an error. Please try again.</div>
        </form>
    </section>
</div>

<footer>
    <div class="container">
        &copy; <?php echo date('Y'); ?> Nacht
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
