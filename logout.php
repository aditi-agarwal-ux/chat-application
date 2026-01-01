<?php
session_start();
<<<<<<< HEAD
session_unset();
session_destroy();
header("Location:login.php");
exit;
=======
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
>>>>>>> 6a6c31d9b0cd5acb4a19861ac83bf640f55f4b7f
?>
