<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'law';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Categories - User</title>
</head>
<body>
    <h1>View Categories - User</h1>

    <h2>Categories</h2>
    <ul>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<li><a href="view_blogs_in_category.php?category_id=' . $row['id'] . '">' . $row['name'] . '</a></li>';
            }
        } else {
            echo '<li>No categories found.</li>';
        }
        ?>
    </ul>
</body>
</html>
