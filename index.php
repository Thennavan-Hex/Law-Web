<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $welcomeMessage = "Welcome!";
} else {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <div>
        <h1><?php echo $welcomeMessage; ?></h1>
        <p>You are now logged in. Welcome to our website!</p>
        <a href="logout.php">Logout</a> 
    </div>
</body>
</html>
