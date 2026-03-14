<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "college_portal";

$conn = new mysqli("localhost", "root", "", "college_portal");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
