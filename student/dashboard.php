<?php
session_start();
include("../db.php");

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['id'];
$name = $_SESSION['name'];

$chat_with_id = $_GET['chat_with_id'] ?? null;

$admins = mysqli_query($conn, "SELECT id, name FROM users WHERE role='admin'");

if ($chat_with_id) {
    $chat = mysqli_query($conn, "
        SELECT * FROM messages
        WHERE (sender_id=$student_id AND receiver_id=$chat_with_id)
           OR (sender_id=$chat_with_id AND receiver_id=$student_id)
        ORDER BY created_at ASC
    ");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style11.css">
</head>

<body>

<div id="header_bar">
    <h2 id="welcome">Welcome <?php echo $name; ?></h2>
    <a href="../logout.php"><button id="logout-btn">Logout</button></a>
</div>

<div id="main-container">

    <!-- LEFT PANEL -->
    <div id="left-panel">
        <h3>Contacts</h3>
        <?php while($a = mysqli_fetch_assoc($admins)){ ?>
            <a href="?chat_with_id=<?php echo $a['id']; ?>" class="contact">
                <?php echo $a['name']; ?>
            </a>
        <?php } ?>
    </div>

    <!-- RIGHT PANEL -->
    <div id="right-panel">

        <div id="chat-header">
            <?php
            if($chat_with_id){
                $st = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id=$chat_with_id"));
                echo "Chat with: ".$st['name'];
            } else {
                echo "Select a contact to start chat";
            }
            ?>
        </div>

        <div id="chat-box" class="chat-box">
            <?php if($chat_with_id){ while($m = mysqli_fetch_assoc($chat)){ ?>
                <div class="<?php echo ($m['sender_id']==$student_id) ? 'sent' : 'received'; ?>">
                    <?php echo $m['message']; ?>
                    <small>
                        <?php echo !empty($m['created_at']) ? date('d M h:i A', strtotime($m['created_at'])) : ''; ?>
                    </small>
                </div>
            <?php }} ?>
        </div>

        <?php if($chat_with_id){ ?>
        <form id="sendForm" enctype="multipart/form-data" style="display:flex; gap:5px; margin-top:10px;">

<input type="hidden" name="receiver_id" value="<?php echo $chat_with_id; ?>">

<textarea id="textArea" name="message" rows="1" placeholder="Type message..."></textarea>

<input type="file" name="file" id="file-upload">

<button id="sendBtn" type="submit">Send</button>

</form>
        <?php } ?>

    </div>
</div>

<script>
const form = document.getElementById("sendForm");
const chatBox = document.getElementById("chat-box");

function scrollToBottom(){
    chatBox.scrollTop = chatBox.scrollHeight;
}

if(form){

form.addEventListener("submit", function(e){

e.preventDefault();

let formData = new FormData(form);

fetch("send_message.php",{
method:"POST",
body: formData
})
.then(res=>res.text())
.then(data=>{
form.reset();
});

});

}

<?php if($chat_with_id){ ?>

let lastData = chatBox.innerHTML;

setInterval(()=>{
    fetch("fetch_messages.php?student_id=<?php echo $chat_with_id; ?>")
    .then(res => res.text())
    .then(data => {
        if(data.trim() !== lastData.trim()){
            chatBox.innerHTML = data;
            lastData = data;
            scrollToBottom();
        }
    });
},2000);

scrollToBottom();

<?php } ?>
</script>

</body>
</html>