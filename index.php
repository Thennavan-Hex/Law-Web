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
        <li class="nav-item"><a href="#" class="nav-link">About</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
        <li class="nav-item dropdown">
          <?php
            session_start();
            if (isset($_SESSION['user_id'])) {
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
              echo '<a class="dropdown-item" href="#">Edit Profile</a>';
              echo '<a class="dropdown-item" href="#">Settings</a>';
              echo '<a class="dropdown-item" href="#">Logout</a>';
              echo '</div>';
              echo '</li>';
            } else {
              echo '<li class="nav-item"><a class="nav-link" href="#">Guest</a></li>';
            }
          ?>
        </ul>
      </div>
    </nav>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
