// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            // Add fade out effect
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';

            // Remove alert after fade out
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000); // Hide after 5 seconds
    });
});

