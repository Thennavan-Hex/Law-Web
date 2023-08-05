<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .blog-card {
            border: 1px solid #dee2e6;
            margin-bottom: 10px;
        }
    </style>
    <title>Category Blogs</title>
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
                if (isset($_GET['category_id'])) {
                    $category_id = $_GET['category_id'];
                    $categorySql = "SELECT category_name FROM categories WHERE id = ?";
                    $stmt = $conn->prepare($categorySql);
                    $stmt->bind_param("i", $category_id);
                    $stmt->execute();
                    $stmt->bind_result($category_name);
                    $stmt->fetch();
                    $stmt->close();
                    echo '<h3>Blogs in Category: ' . $category_name . '</h3>';
                    $blogsSql = "SELECT b.id, b.title, b.content FROM blogs b
                                 JOIN category_blog cb ON b.id = cb.blog_id
                                 WHERE cb.category_id = ?";
                    $stmt = $conn->prepare($blogsSql);
                    $stmt->bind_param("i", $category_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="card blog-card">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                            echo '<a href="view_blog.php?blog_id=' . $row['id'] . '" class="btn btn-primary">Read More</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No blogs in this category.</p>';
                    }

                    $stmt->close();
                }
                $conn->close();
                ?>
                <div class="mt-3">
                    <a href="cv.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
