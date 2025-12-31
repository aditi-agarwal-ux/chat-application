<?php
session_start();


if (!isset($_SESSION['email'])) {
   exit();
}
$email=$_SESSION['email'];
$conn = mysqli_connect("localhost", "new_user", "password", "project_2025") or die("Connection failed");
$query="update users
set status=now()
where email='$email'";
$conn = mysqli_connect("localhost", "new_user", "password", "project_2025") or die("Connection failed");
$result=mysqli_query($conn,$query);
?>


