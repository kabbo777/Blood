<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-white py-3">
            <h4 class="mb-0 text-danger fw-bold">AI Medical Assistant & FAQ</h4>
        </div>
        <div class="card-body">
            <!-- Chat Box -->
            <div id="chatBox" class="border rounded p-3 mb-3" style="height: 400px; overflow-y: auto; background-color: #f9f9f9;">
                <p class="text-muted text-center mb-4"><small>Start a conversation with the AI assistant!</small></p>
            </div>
            
            <!-- Input Area -->
            <div class="input-group">
                <input type="text" id="userInput" class="form-control" placeholder="Ask about blood donation guidelines, eligibility, tips..." autocomplete="off">
                <button class="btn btn-danger px-4" id="sendBtn" onclick="sendMessage()">Ask Assistant</button>
            </div>
        </div>
    </div>
</div>

<script>
function sendMessage() {
    const inputField = document.getElementById('userInput');
    const message = inputField.value.trim();
    if (!message) return;

    const chatBox = document.getElementById('chatBox');
    const sendBtn = document.getElementById('sendBtn');
    
    // Add User Message to UI
    chatBox.innerHTML += `<div class="mb-3 text-end">
                            <span class="d-inline-block bg-primary text-white px-3 py-2 rounded shadow-sm">${message}</span>
                            <div class="small text-muted mt-1"><strong>You</strong></div>
                          </div>`;
    inputField.value = '';
    
    // Disable input while waiting
    inputField.disabled = true;
    sendBtn.disabled = true;
    sendBtn.innerHTML = "Typing...";
    
    chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll

    // Send to PHP Backend
    fetch('/smart_blood_network/chatbot/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        // Add Bot Message to UI
        const reply = data.reply || "Error: No response received.";
        chatBox.innerHTML += `<div class="mb-3 text-start">
                                <span class="d-inline-block bg-white border text-dark px-3 py-2 rounded shadow-sm">${reply}</span>
                                <div class="small text-danger mt-1"><strong>AI Assistant</strong></div>
                              </div>`;
    })
    .catch(error => {
        chatBox.innerHTML += `<div class="mb-3 text-start text-danger"><em>System Error: Could not connect to the AI.</em></div>`;
        console.error("Chatbot Error:", error);
    })
    .finally(() => {
        // Re-enable input
        inputField.disabled = false;
        sendBtn.disabled = false;
        sendBtn.innerHTML = "Ask Assistant";
        inputField.focus();
        chatBox.scrollTop = chatBox.scrollHeight;
    });
}

// Allow pressing "Enter" to send message
document.getElementById('userInput').addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        sendMessage();
    }
});
</script>
</body>
</html>