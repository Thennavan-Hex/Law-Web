<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php"); 
  exit(); 
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php"); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
    <a class="navbar-brand" href="index.php">NECLAW</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="fa fa-bars"></span> Menu
    </button>
    <div class="collapse navbar-collapse" id="ftco-nav">
      <ul class="navbar-nav ml-auto mr-md-3">
        <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="cc.php" class="nav-link">Category</a></li>
        <li class="nav-item"><a href="#" class="nav-link">About</a></li>
        <li class="nav-item"><a href="contactus.php" class="nav-link">Contact</a></li>
        <li class="nav-item dropdown">
          <?php
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'law';

            $conn = new mysqli($host, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection Failed: " . $conn->connect_error);
            }
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt_result = $stmt->get_result();
            
            if ($stmt_result->num_rows > 0) {
                $row = $stmt_result->fetch_assoc();
                $name = $row["username"];
            } else {
                $name = "Guest";
            }
            $conn->close();
            echo '<li class="nav-item dropdown">';
            echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            echo $name;
            echo '</a>';
            echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">';
            echo '<a class="dropdown-item" href="editprofile.php">Edit Profile</a>';
            echo '<a class="dropdown-item" href="fav.php">Bookmarks</a>';
            echo '<form method="POST" class="dropdown-item">';
            echo '<button type="submit" name="logout" class="btn btn-link">Logout</button>';
            echo '</form>';
            echo '</div>';
            echo '</li>';
          ?>
        </ul>
      </div>
 </nav>
 <style>
    .blog-card {
      background-color: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(0, 0, 0, 0.2);
      border-radius: 10px;
      transition: transform 0.3s;
      margin-bottom: 20px; 
    }
    
    .blog-card:hover {
      transform: scale(1.05);
    }

    .read-more-btn {
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 8px 15px;
      font-size: 14px;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .read-more-btn:hover {
      background-color: #0056b3;
    }
  </style>
  <div class="container mt-5">
    <div class="row">
      <?php
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'law';
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }
        $columnExists = $conn->query("SHOW COLUMNS FROM blogs LIKE 'cover_image'");
        $useImage = $columnExists->num_rows > 0;
        $sql = "SELECT id, title, content, cover_image FROM blogs";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $blog_id = $row["id"];
            $blog_title = $row["title"];
            $blog_image = $useImage ? $row["cover_image"] : '';
            $image_path = $blog_image ? $blog_image : 'img/law.jpg';
            echo '<div class="col-md-4">';
            echo '<div class="card blog-card">';
            echo '<img src="' . $image_path . '" class="card-img-top" alt="Blog Image">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $blog_title . '</h5>';
            echo '<a href="view_blog.php?id=' . $blog_id . '" class="btn btn-primary read-more-btn">Read More</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
          }
        } else {
          echo '<p>No blogs found.</p>';
        }

        $conn->close();
      ?>
    </div>
  </div>
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
