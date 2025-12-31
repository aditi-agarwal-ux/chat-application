<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$email=$_SESSION['email'];
$conn = mysqli_connect("localhost", "new_user", "password", "project_2025") or die("Connection failed");


// Define the missing function
function fetch_user_last_activity($email, $conn) {
   $query = "SELECT status FROM user WHERE email = '$email' ORDER BY status DESC LIMIT 1";
   $result = mysqli_query($conn, $query);
   if ($row = mysqli_fetch_assoc($result)) {
       return $row['status'];
   }
   return null;
}


// Fetch all users except the current one
$query = "SELECT * FROM user WHERE email != '$email'";
$result = mysqli_query($conn, $query);
$output = '
<table class="table table-bordered table-striped">
<tr>
 <td>Username</td>
 <td>Status</td>
 <td>Action</td>
</tr>
';


$current_time = strtotime(date('Y-m-d H:i:s'));
foreach($result as $row)
{
   $status_time = strtotime($row['status']);
   $status = ($current_time - $status_time <= 10)
       ? '<span class="label label-success">Online</span>'
       : '<span class="label label-danger">Offline</span>';


$output .= '
<tr>
 <td>'.$row['firstname'].'</td>
 <td>'.$status.'</td>
 <td><button type="button" class="btn btn-info btn-xs start_chat">Start Chat</button></td>
</tr>
';
}


$output .= '</table>';


echo $output;
?>
