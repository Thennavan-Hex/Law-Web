<?php
$conn = new mysqli('localhost', 'root', '', 'law');
if ($conn->connect_error) {
    die("Connection Failed");
}
function tableExists($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    return $result->num_rows > 0;
}
$tableExists = tableExists($conn, 'admins');
if (!$tableExists) {
    $password = password_hash('admin', PASSWORD_DEFAULT);
    $sql = "CREATE TABLE admins (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL
    );
    INSERT INTO admins (username, password) VALUES ('admin', '$password')";

    if ($conn->multi_query($sql) === TRUE) {
        echo "Table admins created successfully and initial admin account inserted.";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Admin Table already created.";
}
$tableExists = tableExists($conn, 'users');
if (!$tableExists) {
    $testUserPassword = password_hash('testuser', PASSWORD_DEFAULT);
    $sql = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        dob DATE NOT NULL,
        remember_me TINYINT(1) NOT NULL
    );
    INSERT INTO users (username, email, password, dob, remember_me) 
    VALUES ('testuser', 'testuser@example.com', '$testUserPassword', '2000-01-01', 0)";

    if ($conn->multi_query($sql) === TRUE) {
        echo "Table users created successfully and test user inserted.";
    } else {
        echo "Error: " . $conn->error;
    }
    echo "<br>User Table is created.";
} else {
    echo "<br>User Table already created.";
}$tableExists = tableExists($conn, 'blogs');
if (!$tableExists) {
    $sql = "CREATE TABLE blogs (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Table blogs created successfully.";
    } else {
        echo "Error creating table: " . $conn->error;
    }
} else {
    echo "<br>Blog Table already created.";
}


$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Setup Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-3">Setup Page</h1>
        <?php if (!$tableExists) { ?>
            <p>Click the "Setup" button to create the tables and insert the initial admin account and test user.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button type="submit" name="setup" class="btn btn-primary">Setup</button>
            </form>
        <?php } else { ?>
            <p>Tables already created. You can proceed to the login page.</p>
            <a href="login.php" class="btn btn-primary">Go to Login</a>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
