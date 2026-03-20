<?php
session_start();
include("db.php");

$message = "";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check user in database
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // Redirect based on role
        if($user['role'] == 'admin'){
            header("Location: admin/dashboard.php");
        } else if ($user['role'] == 'student'){
            header("Location: student/dashboard.php");
        }
        elseif($user['role'] == 'hod'){
            header("Location: hod/manage_admin.php"); // your current page
        }
        exit();
    } else {
        $message = "Invalid Username or Password";
    }
}
?>

<!-- ================= LOGIN PAGE ================= -->

<!DOCTYPE html>
<html>
<head>
    <title>Login - College Portal</title>
    <link rel="stylesheet" href="index1.css">
</head>
<body id="login_pagebody">

<div id="login_box" class="login-container">
    <h2>Login</h2>
    <?php if($message != "") { echo "<p style='color:red;'>$message</p>"; } ?>
    <form method="post" action="">
        
        <input type="text" name="username" placeholder="Enter Username" required><br><br>

       
        <input type="password" name="password" placeholder="Enter Password" required><br><br>

        <button id="login-btn" type="submit" name="login">Login</button>
    </form>
</div>
</body>
</html>
