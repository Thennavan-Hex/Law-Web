<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .category-card {
        background-color: <?php echo getRandomColor(); ?>;
        color: white;
    }
    .category-card a {
        text-decoration: none;
    }
</style>
    <title>Blog Categories</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">My Blog</a>
            <button class="btn btn-success ml-auto" id="addCategoryBtn" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>
        </div>
    </nav>
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                        </div>
                        <div class="mb-3">
                            <label for="blogNames" class="form-label">Select Blog Names</label>
                            <div class="list-group">
                                <?php
                                $conn = new mysqli("localhost", "root", "", "law");
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }
                                $sql = "SELECT id, title FROM blogs";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<label class="list-group-item">';
                                        echo '<input type="checkbox" name="selectedBlogs[]" value="' . $row['id'] . '"> ' . $row['title'];
                                        echo '</label>';
                                    }
                                }
                                $conn->close();
                                ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="createCategory">Create Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h3>Categories</h3>
                <div class="row">
                    <?php
                    $conn = new mysqli("localhost", "root", "", "law");

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createCategory'])) {
                        $categoryName = $_POST['categoryName'];
                        $selectedBlogs = $_POST['selectedBlogs'];
                        $checkCategorySql = "SELECT id FROM categories WHERE category_name = ?";
                        $stmt = $conn->prepare($checkCategorySql);
                        $stmt->bind_param("s", $categoryName);
                        $stmt->execute();
                        $stmt->store_result();
                        if ($stmt->num_rows == 0) {
                            $insertCategorySql = "INSERT INTO categories (category_name) VALUES (?)";
                            $stmt = $conn->prepare($insertCategorySql);
                            $stmt->bind_param("s", $categoryName);
                            $stmt->execute();
                            $category_id = $stmt->insert_id;
                            $insertCategoryBlogSql = "INSERT INTO category_blog (category_id, blog_id) VALUES (?, ?)";
                            $stmt = $conn->prepare($insertCategoryBlogSql);

                            foreach ($selectedBlogs as $blogId) {
                                $stmt->bind_param("ii", $category_id, $blogId);
                                $stmt->execute();
                            }
                        }

                        $stmt->close();
                    }
                    $sql = "SELECT id, category_name FROM categories";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="col-md-4 mb-3">';
                            echo '<div class="card category-card">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title"><a href="category_blogs.php?category_id=' . $row['id'] . '">' . $row['category_name'] . '</a></h5>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    else {
                        echo '<div class="col-md-12">';
                        echo '<p>No categories available.</p>';
                        echo '</div>';
                    }

                    $conn->close();
                    function getRandomColor() {
                        $colors = array(
                            "#FF5733", "#FFBD33", "#FFDA33", "#C6FF33", "#33FFA8", "#33FFDA", 
                            "#33B6FF", "#7A33FF", "#D633FF", "#FF33E8", "#FF336B"
                        );
                        return $colors[array_rand($colors)];
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
