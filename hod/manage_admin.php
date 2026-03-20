<?php
session_start();
include('../db.php');

/* ✅ SECURITY: ONLY HOD CAN ACCESS */
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'hod'){
    header("Location: ../index.php");
    exit();
}

/* ADD ADMIN */
if(isset($_POST['add_admin']))
{
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    mysqli_query($conn,"INSERT INTO users(name,role,username,password)
    VALUES('$name','admin','$username','$password')");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>HOD Dashboard</title>
<link rel="stylesheet" href="hod.css">
</head>

<body>

<a href="../logout.php"><button>Logout</button></a>

<div class="container">

<h2>Add Admin</h2>

<form method="POST">

<label>Name</label><br>
<input type="text" name="name" required><br>

<label>Username</label><br>
<input type="text" name="username" required><br>

<label>Password</label><br>
<input type="password" name="password" required><br>

<button type="submit" name="add_admin">Add Admin</button>

</form>

<hr>

<h2>Admin List</h2>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Name</th>
<th>Username</th>
<th>Action</th>
</tr>

<?php
$result = mysqli_query($conn,"SELECT * FROM users WHERE role='admin'");

while($row = mysqli_fetch_assoc($result))
{
?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['username']; ?></td>

<td>
<a href="delete_admin.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this admin?')" style="color:red;">Delete</a>
</td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>