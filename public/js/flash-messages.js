// Hiển thị flash messages từ data attribute
document.addEventListener('DOMContentLoaded', function() {
    const flashMessagesEl = document.getElementById('flash-messages-data');
    if (flashMessagesEl) {
        try {
            const messages = JSON.parse(flashMessagesEl.textContent);
            messages.forEach(message => {
                if (message.type === 'error') {
                    alert('Lỗi: ' + message.message);
                } else {
                    alert(message.message);
                }
            });
        } catch (e) {
            console.error('Error parsing flash messages:', e);
        }
    }
});

