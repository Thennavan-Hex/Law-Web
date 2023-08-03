<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'law';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
function uploadCoverImage($blog_id, $image, $title)
{
    $conn = new mysqli('localhost', 'root', '', 'law');
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("UPDATE blogs SET cover_image = ?, title = ? WHERE id = ?");
    $stmt->bind_param("ssi", $image, $title, $blog_id);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        $_SESSION['success_message'] = "Cover image and title updated successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to update the cover image and title.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = $_POST['blog_id'];
    $new_title = $_POST['new_title'];
    $new_cover_image = $_FILES['new_coverImage']['tmp_name'];
    uploadCoverImage($blog_id, $new_cover_image, $new_title);
}

$sql = "SELECT id, title, content, cover_image FROM blogs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Cover Image and Title</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .blog-card {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            transition: transform 0.3s;
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
</head>
<body>
    <h1>Edit Cover Image and Title</h1>
    <div class="container mt-5">
        <div class="row">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $blog_id = $row['id'];
                    $blog_title = $row['title'];
                    $blog_cover_image = $row['cover_image'];
                    $image_path = $blog_cover_image ? $blog_cover_image : 'img/law.jpg';
                    ?>
                    <div class="col-md-4">
                        <div class="card blog-card">
                            <img src="<?php echo $image_path; ?>" class="card-img-top" alt="Blog Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $blog_title; ?></h5>
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#editModal<?php echo $blog_id; ?>">Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editModal<?php echo $blog_id; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Cover Image and Title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">
                                        <div class="form-group">
                                            <label for="new_title">New Title:</label>
                                            <input type="text" class="form-control" id="new_title" name="new_title" value="<?php echo $blog_title; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_coverImage">Select New Cover Image:</label>
                                            <input type="file" id="new_coverImage" name="new_coverImage" accept="image/*">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No blogs found.</p>';
            }
            ?>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
