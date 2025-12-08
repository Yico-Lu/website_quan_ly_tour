// Auto hide alerts after specified time
document.addEventListener('DOMContentLoaded', function() {
    // Function to auto hide alert
    function autoHideAlert(alert, delay) {
        setTimeout(function() {
            // Add fade out animation
            alert.style.transition = 'opacity 0.5s ease-out, max-height 0.5s ease-out';
            alert.style.opacity = '0';
            alert.style.maxHeight = '0';
            alert.style.padding = '0';
            alert.style.margin = '0';

            // Remove from DOM after animation
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        }, delay);
    }

    // Auto hide success alerts (5 seconds)
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(function(alert) {
        autoHideAlert(alert, 5000);
    });

    // Auto hide error alerts (7 seconds - keep longer for errors)
    const errorAlerts = document.querySelectorAll('.alert-danger');
    errorAlerts.forEach(function(alert) {
        autoHideAlert(alert, 7000);
    });

    // Auto hide warning alerts (5 seconds)
    const warningAlerts = document.querySelectorAll('.alert-warning');
    warningAlerts.forEach(function(alert) {
        autoHideAlert(alert, 5000);
    });

    // Auto hide info/primary alerts (4 seconds)
    const infoAlerts = document.querySelectorAll('.alert-info, .alert-primary');
    infoAlerts.forEach(function(alert) {
        autoHideAlert(alert, 4000);
    });

    // Auto hide secondary alerts (3 seconds)
    const secondaryAlerts = document.querySelectorAll('.alert-secondary');
    secondaryAlerts.forEach(function(alert) {
        autoHideAlert(alert, 3000);
    });
});
