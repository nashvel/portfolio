<?php
require_once 'init_session.php';

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
    <title>Nacht</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Nacht</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
        <li class="nav-item"><a class="nav-link" href="business_form.php">Business Inquiry</a></li>
        <li class="nav-item"><a class="nav-link" href="ai_assistant.php">Chat with AI</a></li>
      </ul>
    </div>
  </div>
</nav>

<header class="container mb-4 text-center pt-4">
    <h1>I'm Nacht, a 3rd year BSIT student</h1>
    <p class="lead">Welcome to my portfolio! Here are some projects I've worked on. If you'd like to hire me or buy a project, please contact me below.</p>
    <div class="model-container mt-4">
        <div id="avatarCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#avatarCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#avatarCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <model-viewer 
                        src="https://cdn.jsdelivr.net/gh/nashvel/portfolio/assets/models/geto_suguru_fanart.glb"
                        alt="Geto Suguru 3D Avatar"
                        auto-rotate
                        camera-controls
                        camera-orbit="0deg 75deg 5m"
                        disable-pan
                        ar
                        shadow-intensity="1"
                        style="width: 100%; height: 400px; --mv-toolbar-size: 30px; border-radius: 15px;">
                        <div class="progress-bar hide" slot="progress-bar"><div class="update-bar"></div></div>
                    </model-viewer>
                </div>
                <div class="carousel-item">
                    <model-viewer 
                        src="https://cdn.jsdelivr.net/gh/nashvel/portfolio/assets/models/tatsumaki_-_one_punch_man.glb"
                        alt="Tatsumaki 3D Avatar"
                        auto-rotate
                        camera-controls
                        camera-orbit="0deg 75deg 7m" 
                        disable-pan
                        ar
                        shadow-intensity="1"
                        style="width: 100%; height: 400px; --mv-toolbar-size: 30px; border-radius: 15px;">
                        <div class="progress-bar hide" slot="progress-bar"><div class="update-bar"></div></div>
                    </model-viewer>
                </div>
            </div>
        </div>
    </div>
</header>

<section id="business-section" class="container mb-5 text-center">
    <h2 class="mb-4">Interested in Collaborating?</h2>
    <p class="lead">If you have a project, capstone, or any business proposal, let's connect!</p>
    <a href="business_form.php" class="btn btn-primary btn-lg">Make a Business Inquiry</a>
</section>

<footer>
    <div class="container">
        &copy; <?php echo date('Y'); ?> Nacht
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
</body>
</html>
