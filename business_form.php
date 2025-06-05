<?php
session_start();

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

<div class="business-form-container">
    <div class="container">
        <section id="business-inquiry-section">
            <h2>Business Inquiry</h2>
            <p>Let's discuss your project requirements and bring your ideas to life</p>
            <form id="business-inquiry-form" method="POST" action="contact.php" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                
                <div class="form-step">
                    <label for="business_type" class="form-label">🎯 What type of project is this?</label>
                    <select class="form-select" id="business_type" name="business_type" required>
                        <option value="" selected disabled>Choose your project type...</option>
                        <option value="Capstone">🎓 Capstone Project</option>
                        <option value="Project">💼 Business Project</option>
                        <option value="Activity">📝 Academic Activity</option>
                        <option value="Buy a system">🛒 Purchase Complete System</option>
                    </select>
                </div>

                <div class="form-step" id="capstone_type_group" style="display:none;">
                    <label for="capstone_type" class="form-label">🔧 Select Development Type</label>
                    <select class="form-select" id="capstone_type" name="capstone_type">
                        <option value="" selected disabled>Choose development type...</option>
                        <option value="Website">🌐 Website Development</option>
                        <option value="Gui">🖥️ Desktop GUI Application</option>
                        <option value="App">📱 Mobile Application</option>
                    </select>
                </div>

                <div class="form-step" id="language_group" style="display:none;">
                    <label for="language" class="form-label">⚡ Preferred Technology Stack</label>
                    <select class="form-select" id="language" name="language">
                        <option value="" selected disabled>Select technology...</option>
                    </select>
                </div>

                <div class="form-step" id="dev_focus_group" style="display:none;">
                    <label for="dev_focus" class="form-label">🎨 Development Focus</label>
                    <select class="form-select" id="dev_focus" name="dev_focus">
                        <option value="" selected disabled>Choose focus area...</option>
                        <option value="Frontend">🎨 Frontend (User Interface)</option>
                        <option value="Backend">⚙️ Backend (Server Logic)</option>
                        <option value="Both">🔄 Full Stack (Both)</option>
                    </select>
                </div>

                <div class="form-step" id="db_type_group" style="display:none;">
                    <label for="db_type" class="form-label">🗄️ Database Requirements</label>
                    <select class="form-select" id="db_type" name="db_type">
                        <option value="" selected disabled>Select database type...</option>
                        <option value="MySQL">🐬 MySQL Database</option>
                        <option value="No DB (offline)">📁 No Database (Offline)</option>
                        <option value="Other">🔧 Other (Specify below)</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-step">
                            <label for="name" class="form-label">👤 Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="100" placeholder="Enter your full name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-step">
                            <label for="email" class="form-label">📧 Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required maxlength="150" placeholder="your.email@example.com">
                        </div>
                    </div>
                </div>
                
                <div class="form-step">
                    <label for="message" class="form-label">💬 Project Details & Requirements</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required maxlength="1000" placeholder="Please describe your project requirements, features needed, timeline, budget considerations, and any specific preferences..."></textarea>
                </div>
                
                <div class="form-step">
                    <button type="submit" class="btn btn-primary">
                        🚀 Send Inquiry
                    </button>
                </div>
                
                <div id="business-success-message" class="alert alert-success mt-3" style="display:none;">
                    ✅ Inquiry sent successfully! I'll get back to you within 24 hours.
                </div>
                <div id="business-error-message" class="alert alert-danger mt-3" style="display:none;">
                    ❌ There was an error sending your inquiry. Please try again.
                </div>
            </form>
        </section>
    </div>
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
