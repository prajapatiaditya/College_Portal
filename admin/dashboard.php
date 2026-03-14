<?php
session_start();
include("../db.php");

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$admin_id = $_SESSION['id'];
$name = $_SESSION['name'];

$student_id = $_GET['student_id'] ?? null;

$students = mysqli_query($conn, "SELECT id, name FROM users WHERE role='student'");

if ($student_id) {
    $chat = mysqli_query($conn, "
        SELECT * FROM messages
        WHERE (sender_id=$admin_id AND receiver_id=$student_id)
           OR (sender_id=$student_id AND receiver_id=$admin_id)
        ORDER BY created_at ASC
    ");
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style11.css">

</head>
<body>

<div id="header_bar">
     <a href="manage_students.php"><button id="logout-btn">+</button></a>
    <h2 id="welcome">Welcome <?php echo $name; ?></h2>
    <a href="../logout.php"><button id="logout-btn">Logout</button></a>
   
</div>

<div id="main-container">

    <!-- LEFT PANEL -->
    <div id="left-panel">
        <h3>Students</h3>
        <?php while($s = mysqli_fetch_assoc($students)){ ?>
            <a href="?student_id=<?php echo $s['id']; ?>" class="contact">
                <?php echo $s['name']; ?>
            </a>
        <?php } ?>
    </div>

    <!-- RIGHT PANEL -->
    <div id="right-panel">

        <div id="chat-header">
            <?php
            if($student_id){
                $st = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id=$student_id"));
                echo "Chat with: ".$st['name'];
            } else {
                echo "Select a student to start chat";
            }
            ?>
        </div>

        <div id="chat-box" class="chat-box">
            <?php if($student_id){ while($m = mysqli_fetch_assoc($chat)){ ?>
                <div class="<?php echo ($m['sender_id']==$admin_id) ? 'admin-msg' : 'student-msg'; ?>">
                    <?php echo $m['message']; ?>
                    <small>
                        <?php echo !empty($m['created_at']) ? date('d M h:i A', strtotime($m['created_at'])) : ''; ?>
                    </small>
                </div>
            <?php }} ?>
        </div>

        <?php if($student_id){ ?>
      <form id="sendForm" enctype="multipart/form-data" style="display:flex; gap:10px; margin-top:10px;">

<input type="hidden" name="receiver_id" value="<?php echo $student_id; ?>">

<textarea id="textArea" name="message" rows="1" placeholder="Type message..."></textarea>


<input type="file" name="file" id="file-upload">


<button id="sendBtn" type="submit">Send</button>

</form>
        <?php } ?>

    </div>
<script>

const form = document.getElementById("sendForm");
const chatBox = document.getElementById("chat-box");

/* ===== AUTO SCROLL FUNCTION ===== */

function scrollToBottom(){

let chatBox = document.getElementById("chat-box");

if(chatBox){
chatBox.scrollTop = chatBox.scrollHeight;
}

}

window.onload = scrollToBottom;
/* ===== PAGE LOAD SCROLL ===== */

window.onload = function(){
    scrollToBottom();
}

/* ===== SEND MESSAGE ===== */

if(form){

form.addEventListener("submit",function(e){

e.preventDefault();

let formData = new FormData(form);

fetch("send_message.php",{
method:"POST",
body:formData
})
.then(res=>res.text())
.then(()=>{

document.getElementById("textArea").value="";

loadMessages();

});

});

}

/* ===== LOAD MESSAGES ===== */

function loadMessages(){

fetch("fetch_messages.php?student_id=<?php echo $student_id; ?>")

.then(res=>res.text())

.then(data=>{

chatBox.innerHTML = data;

scrollToBottom();

});

}

/* ===== AUTO REFRESH CHAT ===== */

<?php if($student_id){ ?>

setInterval(loadMessages,2000);

<?php } ?>

</script>
</body>
</html>