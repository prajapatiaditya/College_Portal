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
    echo "<a href='delete_message.php?id=".$m['id']."' onclick=\"return confirm('Delete this message?')\" style='color:red;'><button style='font-size:10px; border-radius:20px; border:2px solid #fd3838a4; background-color:#dc3545; color:white;'>X</button></a><br>";

}

if($m['file']!=""){

    $ext = strtolower(pathinfo($m['file'], PATHINFO_EXTENSION));
    $filePath = "../uploads/".$m['file'];

    /* IMAGE */
    if(in_array($ext,['jpg','jpeg','png','gif'])){
        echo "<img src='$filePath' width='150'><br>";
    }

    /* VIDEO */
    elseif(in_array($ext,['mp4','webm','ogg'])){
        echo "<video width='200' controls>
        <source src='$filePath'>
        </video><br>";
    }

    /* DOCUMENT */
    else{
        echo "📄 File<br>";
    }

    /* ✅ DOWNLOAD BUTTON FOR ALL FILES */
    echo "<a href='$filePath' download='".$m['file']."'> <button style='font-size:10px; border-radius:20px; border:2px solid #6348ff83; background-color:#007bff; color:white; margin-right:5px;'>📩</button></a>";

    /* DELETE BUTTON */
    echo "<a href='delete_message.php?id=".$m['id']."' onclick=\"return confirm('Delete this message?')\" style='color:red;'><button style='font-size:10px; border-radius:20px; border:2px solid #fd3838a4; background-color:#dc3545; color:white;'>X</button></a><br>";

}

echo date('d M h:i A', strtotime($m['created_at'] ?? date('Y-m-d H:i:s')));

echo "</div>";
}
?>