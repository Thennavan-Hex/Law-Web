<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="box-container">
            <div class="row">
                <div class="col-md-6 lottie-container">
                    <lottie-player src="https://lottie.host/e2cbf711-dba0-4b9a-9e81-0e889c3ab2a7/mW7VnnyWLO.json" background speed="1" style="width: 100%; max-width: 400px; height: auto;" direction="1" mode="normal" loop autoplay hover ></lottie-player>
                </div>
                <div class="col-md-6">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="dob" class="form-label">Date of birth</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
            <label class="form-check-label" for="rememberMe">Remember me</label>
        </div>
        <p>If you already have an account, <a href="login.php">Login in here</a>.</p>
        <button type="submit" class="btn btn-primary login-btn">Sign In</button>
    </form>
    <div class="g-signin2" data-onsuccess="onSignIn"></div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['dob'])) {
            $uname = $_POST['username'];
            $uemail = $_POST['email'];
            $upass = $_POST['password'];
            $udate = $_POST['dob'];
            $uname = trim($uname);
            $uemail = filter_var($uemail, FILTER_SANITIZE_EMAIL);
            $udate = trim($udate);

            $error_message = '';

            if (empty($uname) || empty($uemail) || empty($upass) || empty($udate)) {
                $error_message = "All form fields are required.";
            } else {
                $hashed_password = password_hash($upass, PASSWORD_DEFAULT);
                $conn = new mysqli('localhost', 'root', '', 'law');
                if ($conn->connect_error) {
                    die("Connection Failed");
                } else {
                    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                    $check_stmt->bind_param("s", $uemail);
                    $check_stmt->execute();
                    $check_stmt_result = $check_stmt->get_result();
                    if ($check_stmt_result->num_rows > 0) {
                        $error_message = "This email is already registered. Please log in.";
                    } else {
                        $stmt = $conn->prepare("INSERT INTO users (username, email, password, dob, remember_me) VALUES (?, ?, ?, ?, ?)");
                        $rememberMe = isset($_POST['rememberMe']) ? 1 : 0;
                        $stmt->bind_param("ssssi", $uname, $uemail, $hashed_password, $udate, $rememberMe);
                        if ($stmt->execute()) {
                            $stmt->close();
                            $conn->close();
                            header("Location: index.php");
                            exit;
                        } else {
                            $error_message = "Error: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                    $conn->close();
                }
            }

            if (!empty($error_message)) {
                echo '<div class="alert alert-danger mt-3">' . $error_message . '</div>';
            }
        } else {
            echo '<div class="alert alert-danger mt-3">Form fields are missing!</div>';
        }
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
