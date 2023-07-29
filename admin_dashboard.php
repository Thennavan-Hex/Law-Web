<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
</head>
<body>
    <h1>Welcome to the Admin Page</h1>
    <a href="admin_logout.php">Logout</a>
</body>
</html>
