<?php

require_once './config.php';

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

    header('Location: panel/index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === panel_username && $password === panel_password) {
        
        $_SESSION['logged_in'] = true;
        header('Location: panel/index.php');
        exit();
    } else {
        $error = "invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <title>Login - Phemedrone</title>
</head>
<body>
    <div id="particles-js"></div>
    <div class="container">
        <h1>Phemedrone Stealer</h1>
        <form method="POST" action="index.php">
            <div class="input-group">
                <input type="text" name="username" id="username" required placeholder=" ">
                <label for="username">Username</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" required placeholder=" ">
                <label for="password">Password</label>
            </div>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) { ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
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
