
<?php
include('../db.php');

if(isset($_POST['add_student']))
{
$name=$_POST['name'];
$username=$_POST['username'];
$password=$_POST['password'];

$sql="INSERT INTO users(name,role,username,password)
VALUES('$name','student','$username','$password')";

mysqli_query($conn,$sql);
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Students</title>
<link rel="stylesheet" href="manage_students.css">
<a href="dashboard.php"><button id="logout-btn"> ⪡ </button></a>

</head>

<body>

<div class="container">

<h2>Add Student</h2>

<form method="POST">

<label>Name</label><br>
<input type="text" name="name" required><br>

<label>Username</label><br>
<input type="text" name="username" required><br>

<label>Password</label><br>
<input type="password" name="password" required><br>

<button type="submit" name="add_student">Add Student</button>

</form>

<hr>

<h2>Student List</h2>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Username</th>
<th>Action</th>
</tr>

<?php

$result=mysqli_query($conn,"SELECT * FROM users WHERE role='student'");

while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['username']; ?></td>

<td>
<a class="delete-btn" href="delete_student.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</div>
</div>




</div>
</body>
</html>


