<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['dob'])) {
        $uname = $_POST['username'];
        $uemail = $_POST['email'];
        $upass = $_POST['password'];
        $udate = $_POST['dob'];
        $uname = trim($uname);
        $uemail = filter_var($uemail, FILTER_SANITIZE_EMAIL);
        $udate = trim($udate);

        if (empty($uname) || empty($uemail) || empty($upass) || empty($udate)) {
            echo "All form fields are required.";
            exit;
        }
        $hashed_password = password_hash($upass, PASSWORD_DEFAULT);
        $conn = new mysqli('localhost', 'root', '', 'law');
        if ($conn->connect_error) {
            die("Connection Failed");
        } else {
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check_stmt->bind_param("s", $uemail);
            $check_stmt->execute();
            $check_stmt_result = $check_stmt->get_result();
            if ($check_stmt_result->num_rows > 0) {
                echo "This email is already registered. Please log in.";
                exit;
            }
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, dob, remember_me) VALUES (?, ?, ?, ?, ?)");
            $rememberMe = isset($_POST['rememberMe']) ? 1 : 0;
            $stmt->bind_param("ssssi", $uname, $uemail, $hashed_password, $udate, $rememberMe);
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
