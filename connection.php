<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['dob'])) {
        $uname = $_POST['username'];
        $uemail = $_POST['email'];
        $upass = $_POST['password'];
        $udate = $_POST['dob'];

        $conn = new mysqli('localhost', 'root', '', 'law');
        if ($conn->connect_error) {
            die("Connection Failed");
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, dob, remember_me) VALUES (?, ?, ?, ?, ?)");
            $rememberMe = isset($_POST['rememberMe']) ? 1 : 0;
            $stmt->bind_param("sssss", $uname, $uemail, $upass, $udate, $rememberMe);
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header("Location: index.php");
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $conn->close();
    } else {
        echo "Form fields are missing!";
    }
}
?>
