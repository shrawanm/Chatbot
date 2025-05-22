const form = document.getElementById('chat-form');
const input = document.getElementById('user-input');
const chatbox = document.getElementById('chatbox');

form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const userMessage = input.value.trim();
    if (!userMessage) return;

    appendMessage(userMessage, 'user');
    input.value = '';

    const botReply = await sendMessageToServer(userMessage);
    appendMessage(botReply, 'bot');
});

function appendMessage(text, sender) {
    const msgDiv = document.createElement('div');
    msgDiv.classList.add('message', sender);
    msgDiv.textContent = text;
    chatbox.appendChild(msgDiv);
    chatbox.scrollTop = chatbox.scrollHeight;
}

async function sendMessageToServer(message) {
    const response = await fetch('real_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `message=${encodeURIComponent(message)}`
    });
    const data = await response.json();
    return data.reply || "No reply from server.";
}