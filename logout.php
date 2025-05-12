<?php
session_start();
$conn = mysqli_connect("localhost", "new_user", "password", "project_2025");
$id=$_GET['logoutid'];
$status="offline";
$query2="UPDATE user SET status = '{$status}' WHERE email= '$id'";
$result2=mysqli_query($conn,$query2);
if($result2){
    session_unset();
    session_destroy();
    header("Location:login.php");
    exit;
}
else{
    echo "ERROR";
}
mysqli_close($conn);
?>
