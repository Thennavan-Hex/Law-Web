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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_blog_to_category'])) {
    $category_id = $_POST['category_id'];
    $blog_id = $_POST['blog_id'];

    $stmt = $conn->prepare("INSERT INTO category (blog_id, category_name) VALUES (?, ?)");
    $stmt->bind_param("ii", $blog_id, $category_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Blog added to category successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to add blog to category.";
    }
}
$blog_id = $_GET['blog_id'];
$sql = "SELECT id, title FROM blogs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$blog_title = $row['title'];
$category_id = $_GET['category_id'];
$sql = "SELECT id, name FROM categories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$category_name = $row['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Blog to Category - Admin</title>
</head>
<body>
    <h1>Add Blog to Category - Admin</h1>

    <h2>Add Blog "<?php echo $blog_title; ?>" to Category "<?php echo $category_name; ?>"</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
        <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">
        <button type="submit" name="add_blog_to_category">Add Blog to Category</button>
    </form>
</body>
</html>
