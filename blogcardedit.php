<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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
if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT title, image FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
if (isset($_POST['update_blog'])) {
    $new_title = $_POST['title'];
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['cover_image']['tmp_name'];
        $file_name = $_FILES['cover_image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($file_ext, $allowed_ext)) {
            $new_image_path = 'img/blog_covers/' . uniqid('', true) . '.' . $file_ext;
            move_uploaded_file($file_tmp, $new_image_path);
            if ($blog['image'] && file_exists($blog['image'])) {
                unlink($blog['image']);
            }
        } else {
            $_SESSION['msg']['type'] = 'danger';
            $_SESSION['msg']['text'] = 'Invalid file format. Only JPG, JPEG, PNG, and GIF are allowed.';
            header('Location: blogcardedit.php?id=' . $blog_id);
            exit();
        }
    } else {
        $new_image_path = $blog['image'];
    }
    $stmt = $conn->prepare("UPDATE blogs SET title = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_title, $new_image_path, $blog_id);

    if ($stmt->execute()) {
        $_SESSION['msg']['type'] = 'success';
        $_SESSION['msg']['text'] = 'Blog Updated Successfully.';
        header('Location: blog.php?id=' . $blog_id);
        exit();
    } else {
        $_SESSION['msg']['type'] = 'danger';
        $_SESSION['msg']['text'] = 'Blog Update Failed.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Blog</h1>
        <?php if (isset($_SESSION['msg'])) { ?>
            <div class="alert alert-<?php echo $_SESSION['msg']['type']; ?>">
                <?php echo $_SESSION['msg']['text']; ?>
            </div>
        <?php unset($_SESSION['msg']); } ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $blog['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="cover_image">Cover Image (optional)</label>
                <input type="file" class="form-control-file" id="cover_image" name="cover_image">
            </div>
            <button type="submit" class="btn btn-primary" name="update_blog">Update Blog</button>
        </form>
    </div>
</body>
</html>
