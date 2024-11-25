function initializeLogs() {
    fetch('../api/init.php', {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Table successfully initialized');
        } else if (data.error) {
            console.error('Error initializing table:', data.error);
        }
    })
    .catch(error => {
        console.error('Request failed:', error);
    });
}

document.addEventListener('DOMContentLoaded', (event) => {
    initializeLogs();
});
