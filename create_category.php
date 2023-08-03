<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'law';
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_category'])) {
    $category_name = $_POST['category_name'];

    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $category_name);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Category created successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to create category.";
    }
}
$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Category Editor - Admin</title>
</head>
<body>
    <h1>Category Editor - Admin</h1>
    <h2>Create New Category</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" required>
        <button type="submit" name="create_category">Create Category</button>
    </form>
    <h2>Existing Categories</h2>
    <ul>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<li><a href="add_blog_to_category.php?category_id=' . $row['id'] . '">' . $row['name'] . '</a></li>';
            }
        } else {
            echo '<li>No categories found.</li>';
        }
        ?>
    </ul>
</body>
</html>
