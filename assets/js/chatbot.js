document.addEventListener('DOMContentLoaded', () => {
    const chatBox = document.getElementById('chatBox');
    const userInput = document.getElementById('userInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    let typingIndicator;

    function addMessage(message, sender, isTyping = false) {
        if (isTyping) {
            // Remove previous typing indicator if any
            if (typingIndicator) {
                typingIndicator.remove();
            }
            typingIndicator = document.createElement('div');
            typingIndicator.classList.add('chat-message', 'bot', 'typing-indicator');
            const avatar = document.createElement('img');
            avatar.src = 'assets/img/bot-avatar.jpg';
            avatar.alt = 'Bot';
            avatar.classList.add('avatar');
            const messageP = document.createElement('p');
            messageP.innerHTML = '<span>.</span><span>.</span><span>.</span>'; // Simple dot animation
            typingIndicator.appendChild(avatar);
            typingIndicator.appendChild(messageP);
            chatBox.appendChild(typingIndicator);
        } else {
            // Remove typing indicator if it exists and we are adding a real message
            if (typingIndicator) {
                typingIndicator.remove();
                typingIndicator = null;
            }
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-message', sender);

            const avatar = document.createElement('img');
            avatar.src = sender === 'user' ? 'assets/img/user-avatar.png' : 'assets/img/bot-avatar.jpg';
            avatar.alt = sender === 'user' ? 'User' : 'Bot';
            avatar.classList.add('avatar');

            const messageP = document.createElement('p');
            messageP.textContent = message;

            if (sender === 'user') {
                messageDiv.appendChild(messageP);
                messageDiv.appendChild(avatar);
            } else {
                messageDiv.appendChild(avatar);
                messageDiv.appendChild(messageP);
            }
            
            chatBox.appendChild(messageDiv);
        }
        chatBox.scrollTop = chatBox.scrollHeight; // Scroll to bottom
    }

    async function getAIResponse(userMessage) {
        addMessage('', 'bot', true); // Show typing indicator

        try {
            const response = await fetch('ai_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: userMessage })
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ reply: 'HTTP error: ' + response.status }));
                throw new Error(errorData.reply || 'Network response was not ok.');
            }

            const data = await response.json();
            addMessage(data.reply, 'bot');

        } catch (error) {
            console.error('Error fetching AI response:', error);
            addMessage('Sorry, I encountered an error. Please try again. (' + error.message + ')', 'bot');
        }
    }

    function handleSendMessage() {
        const message = userInput.value.trim();
        if (message) {
            addMessage(message, 'user');
            userInput.value = '';
            getAIResponse(message);
        }
    }

    sendMessageBtn.addEventListener('click', handleSendMessage);
    userInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            handleSendMessage();
        }
    });

    // Initial bot message is now in chatbot.php HTML
});
