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
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    header('Location: index.php');
    exit();
}
if (isset($_POST['update_profile'])) {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];

    $update_password = !empty($new_password);

    if ($update_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    } else {
        $hashed_password = $user['password'];
    }
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssi", $new_username, $new_email, $hashed_password, $user_id);
    if ($stmt->execute()) {
        $_SESSION['msg']['type'] = 'success';
        $_SESSION['msg']['text'] = 'Profile Updated Successfully.';
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['msg']['type'] = 'danger';
        $_SESSION['msg']['text'] = 'Profile Update Failed.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Profile</h1>
        <?php if (isset($_SESSION['msg'])) { ?>
            <div class="alert alert-<?php echo $_SESSION['msg']['type']; ?>">
                <?php echo $_SESSION['msg']['text']; ?>
            </div>
        <?php unset($_SESSION['msg']); } ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password (optional)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button>
        </form>
    </div>
</body>
</html>
