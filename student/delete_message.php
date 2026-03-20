<?php
session_start();
include("../db.php");

if(isset($_GET['id'])){
    $msg_id = $_GET['id'];

    mysqli_query($conn, "DELETE FROM messages WHERE id='$msg_id'");

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>