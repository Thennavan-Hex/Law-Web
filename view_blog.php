<?php
$conn = new mysqli('localhost', 'root', '', 'law');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
$blog = null;
$is_favorite = false; 
if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];
    $sql = "SELECT * FROM blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
        $user_id = 1; 
        $sql_favorite = "SELECT * FROM favorites WHERE user_id = ? AND blog_id = ?";
        $stmt_favorite = $conn->prepare($sql_favorite);
        $stmt_favorite->bind_param("ii", $user_id, $blog_id);
        $stmt_favorite->execute();
        $result_favorite = $stmt_favorite->get_result();
        $is_favorite = $result_favorite->num_rows > 0;
    } else {
        header("Location: blog.php");
        exit;
    }
} else {
    header("Location: blog.php");
    exit;
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
        .bookmark-icon {
            font-size: 24px;
            margin-right: 5px;
            cursor: pointer;
        }
        .bookmark-icon.animate {
            animation: pulse 0.3s;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
        .bookmark-icon.active {
            color: #f0ad4e;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($blog !== null) { ?>
            <h1 class="mt-3">
                <i class="fas <?php echo $is_favorite ? 'fa-bookmark text-warning active' : 'fa-bookmark text-secondary'; ?> bookmark-icon" onclick="toggleBookmark()"></i>
                <?php echo $blog['title']; ?>
            </h1>
            <p><?php echo $blog['created_at']; ?></p>
            <div class="mt-3">
                <?php echo $blog['content']; ?>
            </div>
            <a href="blog.php" class="btn btn-primary mt-3">Back to Blog List</a>
        <?php } else { ?>
            <p>Blog not found.</p>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleBookmark() {
            var icon = document.querySelector('.bookmark-icon');
            var isFavorite = icon.classList.contains('text-warning');
            icon.classList.toggle('text-warning', !isFavorite);
            icon.classList.toggle('animate', !isFavorite); 
            var xhttp = new XMLHttpRequest();
            var formData = new FormData();
            formData.append('blog_id', <?php echo $blog_id; ?>);
            formData.append('is_favorite', isFavorite ? 0 : 1);
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        var response = JSON.parse(this.responseText);
                        console.log(response);
                    } else {
                        console.error('Error updating bookmark');
                    }
                }
            };
            xhttp.open('POST', 'bookmark_handler.php', true);
            xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhttp.send(formData);
        }
    </script>
</body>
</html>
