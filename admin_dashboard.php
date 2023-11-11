<?php
session_start();
function isAdminLoggedIn() {
    return (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true);
}
if (isset($_POST['logout'])) {
    if (isAdminLoggedIn()) {
        session_destroy();
        header("Location: admin.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Welcome to the Admin Page</h1>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <a href="view_logins.php" class="card-link">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">View User Logins</h5>
                            <p class="card-text">Click here to view all user logins.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="editor/index.php" class="card-link">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Editor</h5>
                            <p class="card-text">Click here to access the editor.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="viewcount.php" class="card-link">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">View Counts and Extra</h5>
                            <p class="card-text">This card's content is coming soon.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="cv.php" class="card-link">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Category</h5>
                            <p class="card-text">Click here to access the new category.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="container text-center mt-3">
        <form method="post">
            <button type="submit" name="logout" class="btn btn-primary">Logout</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
