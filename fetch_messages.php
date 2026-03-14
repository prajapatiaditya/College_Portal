<?php
session_start();
include("db.php");

$user_id = $_SESSION['id'];

$query = "SELECT m.message, m.sender_id, m.date, u.name 
          FROM messages m 
          JOIN users u ON m.sender_id = u.id
          ORDER BY m.id ASC";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){

    $class = ($row['sender_id'] == $user_id) ? "msg admin" : "msg student";

    // Date & Time format
    $time = date("d M Y, h:i A", strtotime($row['date']));

    echo "
    <div class='$class'>
        <b>{$row['name']}</b><br>
        {$row['message']}
        <div class='time'>$time</div>
    </div>
    ";
}
?>
