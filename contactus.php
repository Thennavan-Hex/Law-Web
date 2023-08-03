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
        $error_message = '';
        if (empty($uname) || empty($uemail) || empty($upass) || empty($udate)) {
            $error_message = "All form fields are required.";
        } else {
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
                    $error_message = "This email is already registered. Please log in.";
                } else {
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password, dob, remember_me) VALUES (?, ?, ?, ?, ?)");
                    $rememberMe = isset($_POST['rememberMe']) ? 1 : 0;
                    $stmt->bind_param("ssssi", $uname, $uemail, $hashed_password, $udate, $rememberMe);
                    if ($stmt->execute()) {
                        $stmt->close();
                        $conn->close();
                        header("Location: index.php");
                        exit;
                    } else {
                        $error_message = "Error: " . $stmt->error;
                    }
                    $stmt->close();
                }
                $conn->close();
            }
        }
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger mt-3">' . $error_message . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger mt-3">Form fields are missing!</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="contact.css">
</head>
<body>
    <small>Enter message (optional) and click button "Send"</small>
    <div class="wrapper centered">
        <article class="letter">
            <div class="side">
                <h1>Contact us</h1>
                <p>
                    <textarea id="message" placeholder="Your message"></textarea>
                </p>
            </div>
            <div class="side">
                <p>
                    <input type="text" id="name" placeholder="Your name">
                </p>
                <p>
                    <input type="email" id="email" placeholder="Your email" required>
                </p>
                <p>
                    <button id="sendLetter">Send</button>
                </p>
            </div>
        </article>
        <div class="envelope front"></div>
        <div class="envelope back"></div>
    </div>
    <p class="result-message centered">Thank you for your message</p>
    <button onclick="goBack()" class="back-button">Back</button>

    <script>
        function goBack() {
            window.history.back();
        }
        document.getElementById("sendLetter").addEventListener("click", function() {
            var message = document.getElementById("message").value;
            var name = document.getElementById("name").value;
            var email = document.getElementById("email").value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "process_form.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("username=" + encodeURIComponent(name) + "&email=" + encodeURIComponent(email) + "&password=" + "&dob=" + encodeURIComponent(message));
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.querySelector(".result-message").style.opacity = "1";
                    document.querySelector(".result-message").style.transform = "translateY(0)";
                } else {
                    console.log("Error sending the form data.");
                }
            };
            document.getElementById("email").value = email;
        });
    </script>
</body>
</html>
