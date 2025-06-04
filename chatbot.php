<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chatbot - My Portfolio</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/chatbot_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">Nacht</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php#projects">Projects</a></li>
                        <li class="nav-item"><a class="nav-link" href="business_form.php">Business Inquiry</a></li>
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="chatbot.php">AI Chatbot</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-5 pt-5">
        <section id="chatbot-section" class="py-5">
            <h2 class="text-center mb-4"> Chat with Nacht AI</h2>
            <div class="chat-container">
                <div class="chat-box" id="chatBox">
                    <div class="chat-message bot">
                        <img src="assets/img/bot-avatar.jpg" alt="Bot" class="avatar">
                        <p>Hello! this is Nacht how can i help you today?</p>
                    </div>
                </div>
                <div class="chat-input-area">
                    <input type="text" id="userInput" placeholder="Type your message..." aria-label="User message input">
                    <button id="sendMessageBtn" aria-label="Send message"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p>&copy; <?php echo date("Y"); ?> Your Name. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/chatbot.js"></script>
</body>
</html>
