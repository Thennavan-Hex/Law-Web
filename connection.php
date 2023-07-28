<?php
$servername = 'localhost';
$username  = 'root';
$password = '';
$dbname = '';

$conn = mysqli_connect($servername,$username,$password,$dbname);

if(!$conn)
{
    die("Connection faield :" .mysqli_connect_error());
}
?>