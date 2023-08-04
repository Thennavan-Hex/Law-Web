<?php
session_start();
if (isset($_SESSION['msg']))
    unset($_SESSION['msg']);
if (isset($_SESSION['POST']))
    unset($_SESSION['POST']);
$current_name = $_POST['filename'];
$new_name = $_POST['fname'];
$content = $_POST['content'];
$i = 0;
if (!is_dir("./pages"))
    mkdir("./pages");
if ($current_name != $new_name) {
    $nname = $new_name;
    while (true) {
        if (is_file("./pages/{$nname}.html")) {
            $nname = $new_name . "_" . ($i++);
        } else {
            break;
        }
    }
    $new_name = $nname;
}
if (!empty($current_name) && $current_name != $new_name) {
    rename("./pages/{$current_name}.html", "./pages/{$new_name}.html");
}
$save = file_put_contents("./pages/{$new_name}.html", $content);
if ($save > 0) {
    $_SESSION['msg']['type'] = 'success';
    $_SESSION['msg']['text'] = 'Page Content Successfully Saved.';
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'law';
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        $_SESSION['msg']['type'] = 'danger';
        $_SESSION['msg']['text'] = 'Database Connection Failed. Page Content saved but blog not inserted.';
        $_SESSION['POST'] = $_POST;
        header('location:'.$_SERVER['HTTP_REFERER']);
        exit;
    }
    $title = $new_name;
    $created_at = date("Y-m-d H:i:s");
    $stmt_check = $conn->prepare("SELECT id FROM blogs WHERE title = ?");
    $stmt_check->bind_param("s", $title);
    $stmt_check->execute();
    $stmt_check_result = $stmt_check->get_result();
    if ($stmt_check_result->num_rows > 0) {
        $stmt_update = $conn->prepare("UPDATE blogs SET content = ?, created_at = ? WHERE title = ?");
        $stmt_update->bind_param("sss", $content, $created_at, $title);
        if ($stmt_update->execute()) {
            $_SESSION['msg']['type'] = 'success';
            $_SESSION['msg']['text'] = 'Page Content and Blog Successfully Updated.';
            header('location:./');
        } else {
            $_SESSION['msg']['type'] = 'danger';
            $_SESSION['msg']['text'] = 'Page Content saved but blog update failed.';
            $_SESSION['POST'] = $_POST;
            header('location:'.$_SERVER['HTTP_REFERER']);
        }
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO blogs (title, content, created_at) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("sss", $title, $content, $created_at);
        if ($stmt_insert->execute()) {
            $_SESSION['msg']['type'] = 'success';
            $_SESSION['msg']['text'] = 'Page Content and Blog Successfully Saved.';
            header('location:./');
        } else {
            $_SESSION['msg']['type'] = 'danger';
            $_SESSION['msg']['text'] = 'Page Content saved but blog insertion failed.';
            $_SESSION['POST'] = $_POST;
            header('location:'.$_SERVER['HTTP_REFERER']);
        }
    }
    $conn->close();
} else {
    $_SESSION['msg']['type'] = 'danger';
    $_SESSION['msg']['text'] = 'Page Content has failed to save.';
    $_SESSION['POST'] = $_POST;
    header('location:'.$_SERVER['HTTP_REFERER']);
}
?>
