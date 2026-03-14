<?php
session_start();
include("../db.php");

$sender_id = $_SESSION['id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

$file_name = "";

if(isset($_FILES['file']) && $_FILES['file']['name']!=""){

    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    $file_name = time()."_".$file;

    move_uploaded_file($tmp,"../uploads/".$file_name);
}

mysqli_query($conn,"INSERT INTO messages(sender_id,receiver_id,message,file)
VALUES('$sender_id','$receiver_id','$message','$file_name')");
?>