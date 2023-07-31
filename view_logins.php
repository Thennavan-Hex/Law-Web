<!DOCTYPE html>
<html>
<head>
    <title>User Logins</title>
    <!-- Add Bootstrap 5 CSS link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">User Logins</h1>

        <?php
        $conn = new mysqli('localhost', 'root', '', 'law');
        if ($conn->connect_error) {
            die("Connection Failed");
        }
        function isEmptyOrSpaces($str)
        {
            return preg_match('/^\s*$/', $str);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $dob = $_POST['dob'];
            $rememberMe = isset($_POST['rememberMe']) ? 1 : 0;
            if (!isEmptyOrSpaces($username) && !isEmptyOrSpaces($email) && !isEmptyOrSpaces($password) && !isEmptyOrSpaces($dob)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, dob, remember_me) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $username, $email, $hashedPassword, $dob, $rememberMe);
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success" role="alert">User added successfully.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error: ' . $stmt->error . '</div>';
                }

                $stmt->close();
            } else {
                echo '<div class="alert alert-danger" role="alert">Please fill all required fields.</div>';
            }
        }
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        $conn->close();
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Date of Birth</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>********</td>";
                        echo "<td>" . $row['dob'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <h2 class="mt-4">Add User</h2>
        <div class="mb-3">
            <button class="btn btn-primary" onclick="toggleAddUserForm()">Add User</button>
        </div>
        <div id="addUserFormContainer" style="display: none;">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth:</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>

        <div class="text-center mt-3">
            <a href="admin.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleAddUserForm() {
            var formContainer = document.getElementById("addUserFormContainer");
            if (formContainer.style.display === "none") {
                formContainer.style.display = "block";
            } else {
                formContainer.style.display = "none";
            }
        }
    </script>
</body>
</html>
