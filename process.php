<?php
if (isset($_POST['createCategory'])) {
    $conn = new mysqli("localhost", "root", "", "law");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $categoryName = $_POST['categoryName'];
    $selectedBlogs = $_POST['selectedBlogs'];
    $checkCategorySql = "SELECT id FROM categories WHERE category_name = ?";
    $stmt = $conn->prepare($checkCategorySql);
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: cc.php"); 
        exit;
    }
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
    $stmt->close();
    $conn->close();
    header("Location: cc.php");
    exit;
}
?>
