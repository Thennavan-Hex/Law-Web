<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['username'];
    $pass = $_POST['password'];
    $conn = new mysqli('localhost', 'root', '', 'law');
    if ($conn->connect_error) {
        die("Connection Failed");
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt_result = $stmt->get_result();
        if ($stmt_result->num_rows > 0) {
            $data = $stmt_result->fetch_assoc();
            if (password_verify($pass, $data['password'])) {
                $_SESSION['user_id'] = $data['id']; 
                header("Location: index.php");
                exit;
            } else {
                $error_message = "Invalid Email or password";
            }
        } else {
            $error_message = "Invalid Email or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="box-container">
            <div class="row">
                <div class="col-md-6 lottie-container">
                    <lottie-player src="https://lottie.host/c045d9a1-f1f9-4f1e-838b-4345eab9e8ca/GijBVCz994.json" background speed="1" style="width: 100%; max-width: 400px; height: auto;" direction="1" mode="normal" loop autoplay hover></lottie-player>
                </div>
                <div class="col-md-6">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="username" class="form-label">Email</label>
                            <input type="email" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary login-btn">Login</button>
                        <p>New user, <a href="signin.php">sign in for Free</a>.</p>
                    </form>
                    <?php
                    if (isset($error_message)) {
                        echo '<p class="text-danger">' . $error_message . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <meta name="google-signin-client_id" content="YOUR_CLIENT_ID">
    <script>
        function onSignIn(googleUser) {
            var profile = googleUser.getBasicProfile();
            console.log('ID: ' + profile.getId());
            console.log('Name: ' + profile.getName());
            console.log('Image URL: ' + profile.getImageUrl());
            console.log('Email: ' + profile.getEmail());
        }
    </script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
</body>
</html>
