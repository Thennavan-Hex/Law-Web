<?php
session_start();
if(isset($_SESSION['msg']))
unset($_SESSION['msg']);
if(isset($_SESSION['POST']))
unset($_SESSION['POST']);
$current_name = $_POST['filename'];
$new_name = $_POST['fname'];
$content = $_POST['content'];
$i = 0;
if(!is_dir("./pages"))
    mkdir("./pages");
if($current_name != $new_name){
    $nname = $new_name;
    while(true){
        if(is_file("./pages/{$nname}.html")){
            $nname = $new_name."_".($i++);
        }else{
            break;
        }
    }
    $new_name = $nname;
}
if(!empty($current_name) && $current_name != $new_name){
    rename("./pages/{$current_name}.html","./pages/{$new_name}.html");
}
$save = file_put_contents("./pages/{$new_name}.html",$content);
if($save > 0){
    $_SESSION['msg']['type'] = 'success';
    $_SESSION['msg']['text'] = 'Page Content Successfully Saved.';
    header('location:./');
}else{
    $_SESSION['msg']['type'] = 'danger';
    $_SESSION['msg']['text'] = 'Page Content has failed to save.';
    $_SESSION['POST'] = $_POST;
    header('location:'.$_SERVER['HTTP_REFERER']);
}
?>