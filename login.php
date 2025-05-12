<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// include('database_connection.php');
session_start();


if(isset($_SESSION['email']))
{
 header('location:chat.php');
 exit();
}
$conn = mysqli_connect("localhost", "new_user", "password", "project_2025") or die("Connection failed");

$message = '';

if (isset($_POST["login"])) {
$email = $_POST["email"];
$password = $_POST["password"];

$query = "SELECT * FROM user WHERE email = '$email'";
$result=mysqli_query($conn,$query);

if ($result && mysqli_num_rows($result) > 0) {
$row = mysqli_fetch_assoc($result);
    if (password_verify($password,$row["password"])) {
        $_SESSION['email'] = $row['email'];
        $_SESSION['fname']=$row['firstname'];
        $_SESSION['lname']=$row['lastname'];
        $_SESSION['user_id']=$row['user_id'];
        $status='online';
        $query2="UPDATE user SET status = '{$status}' WHERE email= '$email'";
        $result2=mysqli_query($conn,$query2);
        header('location:chat.php');
        exit();
    } else {
        $message = "<label>Wrong Password</label>";
    }
} else {
    $message = "<label>Wrong Email</label>";
}
}
mysqli_close($conn);
?>
<html>
  <head>
      <title>Chat Application login page</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  </head>
  <body>
      <div class="container">
 <br>
 <br>
 <div class="panel panel-default">
    <div class="panel-heading">Chat Application Login</div>
  <div class="panel-body">
   <form method="post">
    <p class="text-danger"><?php echo $message; ?></p>
    <div class="form-group">
     <label>Enter Email</label>
     <input type="email" name="email" class="form-control" required />
    </div>
    <div class="form-group">
     <label>Enter Password</label>
     <input type="password" name="password" class="form-control" required />
    </div>
    <div class="form-group">
     <input type="submit" name="login" class="btn btn-info" value="Login" />
    </div>
   </form>
  </div>
 </div>
</div>
  </body>
</html>
