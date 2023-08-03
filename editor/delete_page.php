<?php
session_start();
if(isset($_SESSION['msg']))
unset($_SESSION['msg']);
if(isset($_SESSION['POST']))
unset($_SESSION['POST']);
$page = $_GET['page'];
if(is_file("./pages/{$page}")){
    $delete = unlink("./pages/{$page}");
    if($delete){
        $_SESSION['msg']['type'] = 'success';
        $_SESSION['msg']['text'] = 'Page Content Successfully deleted.';
        $conn = new mysqli('localhost', 'root', '', 'law');
        if ($conn->connect_error) {
            $_SESSION['msg']['type'] = 'danger';
            $_SESSION['msg']['text'] = 'Database Connection Failed.';
            header('location:'.$_SERVER['HTTP_REFERER']);
            exit;
        }

        $title = basename($page, '.html'); 
        $stmt = $conn->prepare("DELETE FROM blogs WHERE title = ?");
        $stmt->bind_param("s", $title);
        
        if ($stmt->execute()) {
            $_SESSION['msg']['type'] = 'success';
            $_SESSION['msg']['text'] = 'Page Content and Blog Successfully deleted.';
        } else {
            $_SESSION['msg']['type'] = 'danger';
            $_SESSION['msg']['text'] = 'Page Content deleted, but blog deletion failed.';
        }
        $stmt->close();
        $conn->close();
    }else{
        $_SESSION['msg']['type'] = 'danger';
        $_SESSION['msg']['text'] = 'Page Content has failed to delete.';
    }
}else{
    $_SESSION['msg']['type'] = 'danger';
    $_SESSION['msg']['text'] = 'Page Content is unknown.';
}
header('location:'.$_SERVER['HTTP_REFERER']);
?>
