<?php
session_start();
include("../db.php");

$admin_id = $_SESSION['id'];
$student_id = $_GET['student_id'];

$result = mysqli_query($conn,"
SELECT * FROM messages
WHERE (sender_id=$admin_id AND receiver_id=$student_id)
OR (sender_id=$student_id AND receiver_id=$admin_id)
ORDER BY created_at ASC
");

while($m = mysqli_fetch_assoc($result)){

$class = ($m['sender_id']==$admin_id) ? 'admin-msg' : 'student-msg';

echo "<div class='$class'>";

if($m['message']!=""){
echo $m['message']."<br>";
}

if($m['file']!=""){

$ext = strtolower(pathinfo($m['file'],PATHINFO_EXTENSION));

/* IMAGE */

if(in_array($ext,['jpg','jpeg','png','gif'])){
echo "<img src='../uploads/".$m['file']."' width='150'><br>";
}

/* VIDEO */

elseif(in_array($ext,['mp4','webm','ogg'])){
echo "<video width='200' controls>
<source src='../uploads/".$m['file']."'>
</video><br>";
}

/* DOCUMENT */

else{
echo "<a href='../uploads/".$m['file']."' target='_blank'>Download File</a><br>";
}

}
echo date('d M h:i A', strtotime($m['created_at'] ?? date('Y-m-d H:i:s')));

echo "</div>";
}
?>