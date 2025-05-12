<?php
include('message_database.php');
session_start();
$conn = mysqli_connect("localhost", "new_user", "password", "project_2025");
echo fetch_user_chat_history($_SESSION['user_id'],$_POST['to_user_id'],$conn);


?>