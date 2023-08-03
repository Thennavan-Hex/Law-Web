<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'law';
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM blogs ORDER BY created_at DESC";
$result = $conn->query($sql);
$blogs = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-3">Blog Page</h1>
        <?php if (empty($blogs)) { ?>
            <p>No blogs found.</p>
        <?php } else { ?>
            <ul class="list-group mt-3">
                <?php foreach ($blogs as $blog) { ?>
                    <li class="list-group-item">
                        <h3><a href="view_blog.php?id=<?php echo $blog['id']; ?>"><?php echo $blog['title']; ?></a></h3>
                        <small><?php echo $blog['created_at']; ?></small>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
        <a href="index.php" class="btn btn-primary mt-3">Back</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
