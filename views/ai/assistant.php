<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Blood Network - Gemini AI Assistant</title>
    <style>
        .chat-container {
            max-width: 800px;
            margin: 20px auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .chat-header {
            background-color: #c0392b;
            color: white;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-size: 18px;
            font-weight: bold;
        }
        .chat-box {
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            background-color: #ffffff;
        }
        .message {
            margin-bottom: 15px;
            line-height: 1.4;
        }
        .user-message {
            text-align: right;
        }
        .user-message .text {
            background-color: #3498db;
            color: white;
            display: inline-block;
            padding: 10px 15px;
            border-radius: 15px 15px 0px 15px;
        }
        .ai-message {
            text-align: left;
        }
        .ai-message .text {
            background-color: #ecf0f1;
            color: #2c3e50;
            display: inline-block;
            padding: 10px 15px;
            border-radius: 15px 15px 15px 0px;
            white-space: pre-wrap;
        }
        .input-area {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
        }
        .input-area input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .input-area button {
            padding: 10px 20px;
            background-color: #c0392b;
            color: white;
            border: none;
            border-radius: 4px;
            margin-left: 10px;
            cursor: pointer;
            font-weight: bold;
        }
        .input-area button:hover {
            background-color: #a93226;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>

    <div class="chat-container">
        <div class="chat-header">
            Gemini AI Smart Blood Assistant
        </div>
        <div class="chat-box" id="chatBox">
            <div class="message ai-message">
                <div class="text">Hello! I am your Gemini AI assistant for Smart Blood Network. Ask me anything about blood donation, eligibility, donor matching, or health guidelines.</div>
            </div>
        </div>
        <div class="input-area">
            <input type="text" id="userInput" placeholder="Type your query here..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()" id="sendBtn">Send</button>
        </div>
    </div>

    <script>
        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }

        function sendMessage() {
            var inputField = document.getElementById('userInput');
            var message = inputField.value.trim();
            if (message === '') return;

            var chatBox = document.getElementById('chatBox');

            var userDiv = document.createElement('div');
            userDiv.className = 'message user-message';
            userDiv.innerHTML = '<div class="text">' + escapeHtml(message) + '</div>';
            chatBox.appendChild(userDiv);

            inputField.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            var loadingDiv = document.createElement('div');
            loadingDiv.className = 'message ai-message';
            loadingDiv.id = 'loadingMsg';
            loadingDiv.innerHTML = '<div class="text">Thinking...</div>';
            chatBox.appendChild(loadingDiv);
            chatBox.scrollTop = chatBox.scrollHeight;

            fetch('../../controllers/AIController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: message })
            })
            .then(function(response) {
                return response.text();
            })
            .then(function(text) {
                var loading = document.getElementById('loadingMsg');
                if (loading) loading.remove();

                var aiDiv = document.createElement('div');
                aiDiv.className = 'message ai-message';

                var data;
                try {
                    data = JSON.parse(text);
                } catch(e) {
                    aiDiv.innerHTML = '<div class="text">Server Output Error: ' + escapeHtml(text) + '</div>';
                    chatBox.appendChild(aiDiv);
                    chatBox.scrollTop = chatBox.scrollHeight;
                    return;
                }

                if (data.status === 'success') {
                    aiDiv.innerHTML = '<div class="text">' + escapeHtml(data.reply) + '</div>';
                } else {
                    aiDiv.innerHTML = '<div class="text">Error: ' + escapeHtml(data.message) + '</div>';
                }

                chatBox.appendChild(aiDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(function(error) {
                var loading = document.getElementById('loadingMsg');
                if (loading) loading.remove();

                var aiDiv = document.createElement('div');
                aiDiv.className = 'message ai-message';
                aiDiv.innerHTML = '<div class="text">Fetch Error: ' + escapeHtml(error.message) + '</div>';
                chatBox.appendChild(aiDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            });
        }

        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    </script>
</body>
</html>