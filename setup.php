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
}
$tableExists = tableExists($conn, 'blogs');
if (!$tableExists) {
    $sql = "CREATE TABLE blogs (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        cover_image VARCHAR(255) DEFAULT NULL,
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
$tableExists = tableExists($conn, 'category');
if (!$tableExists) {
    $sql = "CREATE TABLE categories (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql) === TRUE) {
        echo "Table category created successfully.";
    } else {
        echo "Error creating table category: " . $conn->error;
    }
} else {
    echo "Category Table already created.";
}
$tableExists = tableExists($conn, 'category_blog');
if (!$tableExists) {
    $sql = "CREATE TABLE category_blog (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        category_id INT(11) UNSIGNED NOT NULL,
        blog_id INT(11) UNSIGNED NOT NULL,
        FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE,
        FOREIGN KEY (blog_id) REFERENCES blogs (id) ON DELETE CASCADE
    )";
    if ($conn->query($sql) === TRUE) {
        echo "Table category_blog created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
}
$tableExists = tableExists($conn, 'favorites');
if (!$tableExists) {
    $sql = "CREATE TABLE favorites (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) UNSIGNED NOT NULL,
        blog_id INT(11) UNSIGNED NOT NULL
    )";
    if ($conn->query($sql) === TRUE) {
        echo "Table favorites created successfully.";
    } else {
        echo "Error creating table favorites: " . $conn->error;
    }
    $sql = "ALTER TABLE favorites
            ADD CONSTRAINT fk_user_id
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
            ADD CONSTRAINT fk_blog_id
            FOREIGN KEY (blog_id) REFERENCES blogs (id) ON DELETE CASCADE";

    if ($conn->query($sql) === TRUE) {
        echo "Foreign key constraints added successfully.";
    } else {
        echo "Error adding foreign key constraints: " . $conn->error;
    }
} else {
    echo "Favorites Table already created.";
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $blog['title']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .star-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
            color: #ccc;
        }
        .star-icon.favorite {
            color: #FFD700; 
        }
    </style>
</head>
<body>
        <a href="blog.php" class="btn btn-primary mt-3">Back to Blog List</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("add-to-favorites").addEventListener("click", function (event) {
                event.preventDefault();
                toggleFavorite(<?php echo $blog['id']; ?>);
            });
            function toggleFavorite(blogId) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        var starIcon = document.getElementById("add-to-favorites");
                        if (this.responseText === "added") {
                            starIcon.classList.add("favorite");
                        } else if (this.responseText === "removed") {
                            starIcon.classList.remove("favorite");
                        }
                    }
                };
                xhttp.open("POST", "toggle_favorite.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("blog_id=" + blogId);
            }
        });
    </script>
</body>
</html>
