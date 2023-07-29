<?php
$conn = new mysqli('localhost', 'root', '', 'law');
if ($conn->connect_error) {
    die("Connection Failed");
}
$password = password_hash('admin', PASSWORD_DEFAULT);
$sql = "CREATE TABLE admins (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);
INSERT INTO admins (username, password) VALUES ('admin', '$password')";

if ($conn->multi_query($sql) === TRUE) {
    $conn->close();
    header("Location: login.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
