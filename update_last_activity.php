<?php
session_start();
// if (!isset($_SESSION['email'])) {
//    exit();
// }
$email=$_SESSION['email'];
$conn = mysqli_connect("localhost", "new_user", "password", "project_2025") or die("Connection failed");
$query="update user
set time_stamp=NOW()
where email='$email'";
$result=mysqli_query($conn,$query);




mysqli_close($conn);
?>


