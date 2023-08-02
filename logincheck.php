<?php
$email = $_POST['username'];
$pass = $_POST['password'];
$conn = new mysqli('localhost', 'root', '', 'law');
if ($conn->connect_error) {
    die("Connection Failed");
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt_result = $stmt->get_result();
    if ($stmt_result->num_rows > 0) {
        $data = $stmt_result->fetch_assoc();
        if (password_verify($pass, $data['password'])) {
            session_start();
            $_SESSION['user_id'] = $data['id']; 
            header("Location: index.php");
            exit;
        } else {
            echo "<h2>Invalid Email or password</h2>"; 
        }
    } else {
        echo "<h2>Invalid Email or password</h2>";
    }
}
?>
