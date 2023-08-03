<?php
$conn = new mysqli('localhost', 'root', '', 'law');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
$user_id = 1; 
$sql = "SELECT blogs.id, blogs.title, blogs.created_at 
        FROM blogs
        INNER JOIN favorites ON blogs.id = favorites.blog_id
        WHERE favorites.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookmarked_blogs = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bookmarked Blogs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-3">Bookmarked Blogs</h1>
        <div class="row">
            <?php foreach ($bookmarked_blogs as $blog) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $blog['title']; ?></h5>
                            <a href="view_blog.php?id=<?php echo $blog['id']; ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if (empty($bookmarked_blogs)) { ?>
            <p>No bookmarked blogs found.</p>
        <?php } ?>
    </div>
    
    <div class="text-center mt-3">
            <a href="index.php" class="btn btn-secondary">Back</a>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
