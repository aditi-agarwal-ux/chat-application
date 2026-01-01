<?php
session_start();
<<<<<<< HEAD


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
=======
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
>>>>>>> 6a6c31d9b0cd5acb4a19861ac83bf640f55f4b7f
?>


