<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'law';
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];
    $sql = "SELECT * FROM blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
    } else {
        header("Location: blog.php");
        exit;
    }
} else {
    header("Location: blog.php");
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $blog['title']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-3"><?php echo $blog['title']; ?></h1>
        <p><?php echo $blog['created_at']; ?></p>
        <div class="mt-3">
            <?php echo $blog['content']; ?>
        </div>
        <a href="blog.php" class="btn btn-primary mt-3">Back to Blog List</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
