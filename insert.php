<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<<<<<<< HEAD

if($_SERVER['REQUEST_METHOD'] === 'POST') {
$first_name = $_POST["fname"];
$last_name = $_POST["lname"];
$phone_no = $_POST["phone_no"];
$email = $_POST["email"];
$password = $_POST["password"];
if (!preg_match("/^[A-Za-z]{2,30}$/", $first_name)) {
   echo "Invalid first name. Only letters allowed";
   exit;
}
if (!preg_match("/^[A-Za-z]{2,30}$/", $last_name)) {
   echo "Invalid last name. Only letters allowed";
   exit;
}
if (!preg_match("/^[0-9]{10}$/", $phone_no)) {
   echo "Invalid phone number ,exactly 10 digits.";
   exit;
}
if (!preg_match("/^[\w\.-]+@[\w\.-]+\.\w{2,6}$/", $email)) {
   echo "Invalid email format.";
   exit;
}
if (!preg_match("/^.{6,}$/", $password)) {
   echo "Password must be at least 6 characters.";
   exit;
}
// Connect to the database
$conn = mysqli_connect("localhost", "new_user", "password", "project_2025") or die("Connection failed");


// Sanitize input to prevent SQL injection
// Create the SQL query (with backticks for columns with spaces)
$sql = "INSERT INTO user (firstname, lastname, phonenumber, email, password)
       VALUES ('{$first_name}', '{$last_name}', '{$phone_no}', '{$email}', '{$password}')";


// Execute the query and check if it was successful
if (mysqli_query($conn, $sql)) {
   echo "1";  // Success
} else {
   echo "0";  // Failure
}


// Close the connection
mysqli_close($conn);
}
?>


=======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST["fname"]);
    $last_name = trim($_POST["lname"]);
    $phone_no = trim($_POST["phone_no"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $errors = [];

    // Validations
    if (!preg_match("/^[A-Za-z]{2,30}$/", $first_name)) {
        $errors['fname'] = "Invalid first name. Only letters allowed (2–30 chars).";
    }

    if (!preg_match("/^[A-Za-z]{2,30}$/", $last_name)) {
        $errors['lname'] = "Invalid last name. Only letters allowed (2–30 chars).";
    }

    if (!preg_match("/^[0-9]{10}$/", $phone_no)) {
        $errors['phone_no'] = "Phone number must be exactly 10 digits.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters.";
    }

    if (!empty($errors)) {
        foreach ($errors as $key => $msg) {
            echo "$key:$msg\n";
        }
        exit;
    }

    $conn = new mysqli("localhost", "new_user", "password", "project_2025");

    if ($conn->connect_error) {
        echo "error:Database connection failed.\n";
        exit;
    }

    // Check if email already exists using prepared statement


   
    $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, phonenumber, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $phone_no, $email, $password);

    if ($stmt->execute()) {
        echo "1";
    } else {
        echo "error:Failed to insert user.\n";
    }

    $stmt->close();
    $conn->close();
}
?>
>>>>>>> 6a6c31d9b0cd5acb4a19861ac83bf640f55f4b7f
