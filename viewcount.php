<?php
session_start();
if (!isset($_SESSION['view_count'])) {
    $_SESSION['view_count'] = 1;
} else {
    $_SESSION['view_count']++;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Count Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">View Count Page</h1>
        <p class="text-center">This page has been viewed <?php echo $_SESSION['view_count']; ?> times.</p>
        <div class="text-center mt-3">
            <a href="admin.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
