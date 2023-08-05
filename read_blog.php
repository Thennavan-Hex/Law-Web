<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .blog-content {
            margin-top: 20px;
        }
    </style>
    <title>Read Blog</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">My Blog</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <?php
                $conn = new mysqli("localhost", "root", "", "law");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if (isset($_GET['blog_id'])) {
                    $blog_id = $_GET['blog_id'];

                    $blogSql = "SELECT title, content FROM blogs WHERE id = ?";
                    $stmt = $conn->prepare($blogSql);
                    $stmt->bind_param("i", $blog_id);
                    $stmt->execute();
                    $stmt->bind_result($blog_title, $blog_content);
                    $stmt->fetch();
                    $stmt->close();

                    echo '<h3>' . $blog_title . '</h3>';
                    echo '<div class="blog-content">' . $blog_content . '</div>';
                }

                $conn->close();
                ?>
                <div class="mt-3">
                    <a href="category_blogs.php?category_id=<?php echo $_GET['category_id']; ?>" class="btn btn-primary">Back to Category</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
