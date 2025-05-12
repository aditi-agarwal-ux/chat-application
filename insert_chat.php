<?php
session_start();

include("message_database.php");

if (!isset($_POST['to_user_id'], $_POST['chat_message']) || !isset($_SESSION['user_id'])) {
    echo 0;
    exit();
}

$to_user_id = $_POST['to_user_id'];
$from_user_id = $_SESSION['user_id'];
$chat_message = $_POST['chat_message'];
$status = 1;

$sql = "INSERT INTO chat_message (to_user_id, from_user_id, chat_message, status) VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iisi", $to_user_id, $from_user_id, $chat_message, $status);
    
    if (mysqli_stmt_execute($stmt)) {
        // Output the updated chat history after message is inserted
        echo fetch_user_chat_history($from_user_id, $to_user_id, $conn);
    } else {
        echo "0"; // query execution failed
    }

    mysqli_stmt_close($stmt);
} else {
    echo ""; // statement preparation failed
}

mysqli_close($conn);
?>
