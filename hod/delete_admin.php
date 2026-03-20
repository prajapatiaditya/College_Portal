<?php
session_start();
include("../db.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    mysqli_query($conn,"DELETE FROM users WHERE id='$id' AND role='admin'");

    header("Location: manage_admin.php");
    exit();
}
?>