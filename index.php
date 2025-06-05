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
    <title>Nacht Labs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500&family=Google+Sans+Text:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Nacht Labs</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
        <li class="nav-item"><a class="nav-link" href="#skills">Skills</a></li>
        <li class="nav-item"><a class="nav-link" href="business_form.php">Business Inquiry</a></li>
        <li class="nav-item"><a class="nav-link" href="ai_assistant.php">Chat with AI</a></li>
      </ul>
    </div>
  </div>
</nav>

<header class="hero-section">
    <div class="container">
        <h1>The home for creative tech experiments</h1>
        <p>I'm Nacht, a 3rd year BSIT student passionate about creating innovative digital experiences using cutting-edge technologies</p>
        
        <div class="model-container">
            <div id="avatarCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#avatarCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#avatarCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <model-viewer
                            src="assets/models/geto_suguru_fanart.glb"
                            alt="Geto Suguru 3D Avatar"
                            auto-rotate
                            camera-controls
                            camera-orbit="0deg 75deg 5m"
                            disable-pan
                            ar
                            shadow-intensity="1"
                            loading="eager"
                            reveal="auto">
                            <div class="progress-bar hide" slot="progress-bar"><div class="update-bar"></div></div>
                        </model-viewer>
                    </div>
                    <div class="carousel-item">
                        <model-viewer
                            src="assets/models/tatsumaki_-_one_punch_man.glb"
                            alt="Tatsumaki 3D Avatar"
                            auto-rotate
                            camera-controls
                            camera-orbit="0deg 75deg 7m"
                            disable-pan
                            ar
                            shadow-intensity="1"
                            loading="eager"
                            reveal="auto">
                            <div class="progress-bar hide" slot="progress-bar"><div class="update-bar"></div></div>
                        </model-viewer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="featured-section">
    <div class="container">
        <p class="featured-heading">FEATURED</p>
        <h2 class="featured-title">Web Development Studio</h2>
        <p class="featured-description">Crafting responsive, user-friendly, and visually appealing websites that deliver exceptional user experiences</p>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card-section">
                    <h3>Frontend Magic</h3>
                    <p>Creating beautiful, responsive interfaces with modern frameworks like React and traditional technologies like HTML5, CSS3, and JavaScript</p>
                    <div class="card-image-container">
                        <img src="https://images.unsplash.com/photo-1581276879432-15e50529f34b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80" alt="Code editor showing frontend code">
                    </div>
                    <a href="#projects" class="btn btn-primary">View Projects</a>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card-section blue">
                    <h3>Backend Systems</h3>
                    <p>Building robust, scalable backend solutions with PHP, Laravel, Node.js, and database management systems</p>
                    <div class="card-image-container">
                        <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1774&q=80" alt="Server racks representing backend">
                    </div>
                    <a href="#projects" class="btn btn-primary">Explore Skills</a>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card-section orange">
                    <h3>3D Modeling</h3>
                    <p>Creating immersive 3D experiences and models that can be integrated into web applications for next-gen interactivity</p>
                    <div class="card-image-container">
                        <img src="https://images.unsplash.com/photo-1617791160536-598cf32026fb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1664&q=80" alt="3D modeling workspace">
                    </div>
                    <a href="#" class="btn btn-primary">View Gallery</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="projects" class="projects-section">
    <div class="container">
        <div class="projects-heading">
            <h2>Recent Projects</h2>
            <p>A showcase of my recent work across various domains and technologies</p>
        </div>
        
        <div class="project-grid animate-on-scroll">
            <div class="project-card project-item web-app">
                <div class="project-card-image">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80" alt="E-commerce Platform">
                </div>
                <div class="project-card-content">
                    <h3>E-commerce Platform</h3>
                    <p>A full-featured online store with product management, cart functionality, and secure payment processing.</p>
                    <div class="project-tags">
                        <span class="badge bg-light text-dark">PHP</span>
                        <span class="badge bg-light text-dark">MySQL</span>
                        <span class="badge bg-light text-dark">JavaScript</span>
                    </div>
                </div>
            </div>
            
            <div class="project-card project-item mobile-app">
                <div class="project-card-image">
                    <img src="https://images.unsplash.com/photo-1585399000684-d2f72660f092?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1771&q=80" alt="Student Management App">
                </div>
                <div class="project-card-content">
                    <h3>Student Management App</h3>
                    <p>A mobile application for tracking student attendance, assignments, and performance metrics.</p>
                    <div class="project-tags">
                        <span class="badge bg-light text-dark">React Native</span>
                        <span class="badge bg-light text-dark">Firebase</span>
                    </div>
                </div>
            </div>
            
            <div class="project-card project-item web-app">
                <div class="project-card-image">
                    <img src="https://images.unsplash.com/photo-1504868584819-f8e8b4b6d7e3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1776&q=80" alt="Portfolio Website">
                </div>
                <div class="project-card-content">
                    <h3>Portfolio Website</h3>
                    <p>A responsive portfolio website showcasing projects and skills with interactive elements.</p>
                    <div class="project-tags">
                        <span class="badge bg-light text-dark">HTML/CSS</span>
                        <span class="badge bg-light text-dark">JavaScript</span>
                        <span class="badge bg-light text-dark">Bootstrap</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="view-all-btn">
            <a href="#" class="btn btn-secondary">View All Projects</a>
        </div>
    </div>
</section>

<section id="skills" class="featured-section">
    <div class="container">
        <p class="featured-heading">TECHNICAL SKILLS</p>
        <h2 class="featured-title">Technologies I Work With</h2>
        <p class="featured-description">A comprehensive set of technologies I've mastered throughout my academic and personal projects</p>
        
        <div class="row text-center">
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="skill-item animate-on-scroll">
                    <h4>Frontend</h4>
                    <ul class="list-unstyled">
                        <li>HTML5/CSS3</li>
                        <li>JavaScript/jQuery</li>
                        <li>Bootstrap</li>
                        <li>React.js</li>
                        <li>Responsive Design</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="skill-item animate-on-scroll">
                    <h4>Backend</h4>
                    <ul class="list-unstyled">
                        <li>PHP</li>
                        <li>Laravel</li>
                        <li>Node.js</li>
                        <li>RESTful APIs</li>
                        <li>Python</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="skill-item animate-on-scroll">
                    <h4>Database</h4>
                    <ul class="list-unstyled">
                        <li>MySQL</li>
                        <li>MongoDB</li>
                        <li>Firebase</li>
                        <li>SQL Server</li>
                        <li>Database Design</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="skill-item animate-on-scroll">
                    <h4>Tools & Others</h4>
                    <ul class="list-unstyled">
                        <li>Git/GitHub</li>
                        <li>VS Code</li>
                        <li>Adobe XD/Figma</li>
                        <li>3D Modeling</li>
                        <li>UI/UX Design</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="business-section" class="business-section">
    <div class="container">
        <h2>Let's Bring Your Ideas to Life</h2>
        <p>Whether you need a website, mobile application, or a complete digital solution for your business or academic project, I'm here to help. Let's collaborate to create something amazing together!</p>
        <a href="business_form.php" class="btn btn-primary btn-lg">Make a Business Inquiry</a>
    </div>
</section>

<section class="featured-section">
    <div class="container">
        <p class="featured-heading">EXPERIMENTAL</p>
        <h2 class="featured-title">AI Assistant</h2>
        <p class="featured-description">Chat with my custom AI assistant to learn more about my services or get quick answers about web development</p>
        
        <div class="text-center">
            <a href="ai_assistant.php" class="btn btn-primary">Chat with AI</a>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="footer-brand">Nacht Labs</div>
            </div>
            <div class="col-md-6">
                <div class="footer-links">
                    <a href="#projects">Projects</a>
                    <a href="#skills">Skills</a>
                    <a href="business_form.php">Business Inquiry</a>
                    <a href="ai_assistant.php">AI Assistant</a>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            &copy; <?php echo date('Y'); ?> Nacht. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
</body>
</html>
