<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$first_name = $last_name = $phone_no = $email = $password = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST["fname"]);
    $last_name = trim($_POST["lname"]);
    $phone_no = trim($_POST["phone_no"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $_SESSION['email'] = trim($_POST['email']);
    $status = "";

    // Validations
    if (empty($first_name)) {
        $errors['fname'] = "Please enter a first name.";
    } elseif (!preg_match("/^[A-Za-z0-9]{2,30}$/", $first_name)) {
        $errors['fname'] = "Invalid first name. Only letters and numbers are allowed (2–30 chars).";
    }

    if (empty($last_name)) {
        $errors['lname'] = "Please enter a last name.";
    } elseif (!preg_match("/^[A-Za-z0-9]{2,30}$/", $last_name)) {
        $errors['lname'] = "Invalid last name. Only letters and numbers are allowed (2–30 chars).";
    }

    if (empty($phone_no)) {
        $errors['phone_no'] = "Please enter a phone number.";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone_no)) {
        $errors['phone_no'] = "Phone number must be exactly 10 digits.";
    }

    if (empty($email)) {
        $errors['email'] = "Please enter an email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors['password'] = "Please enter a password.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $conn = new mysqli("localhost", "new_user", "password", "project_2025");

        if ($conn->connect_error) {
            echo "error:Database connection failed.\n";
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, phonenumber, email, password, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $phone_no, $email, $hashed_password, $status);

        if ($stmt->execute()) {
            header("Location: chat.php");
            exit;
        } else {
            echo "error: Failed to insert user.\n";
        }

        $stmt->close();
        $conn->close();
    }
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        table {
            border: 2px solid black;
            border-collapse: collapse;
        }
        td {
            padding: 8px;
        }
        input {
            width: 200px;
            height: 30px;
            font-size: 16px;
        }
        .error {
            font-size: 14px;
            color: red;
        }
    </style>
</head>
<body>
    <center>
        <form method="POST" class="custom-form" style="margin-top: 60px;" action="regis.php">
            <table>
                <th colspan="2"><h2>Registration Form of Chat Application</h2></th>
                <tr>
                    <td>
                        <label for="fname">First Name:</label><br>
                        <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($first_name) ?>" required>
                        <?php if (!empty($errors['fname'])) echo "<div class='error'>{$errors['fname']}</div>"; ?>
                    </td>
                    <td>
                        <label for="lname">Last Name:</label><br>
                        <input type="text" id="lname" name="lname" value="<?= htmlspecialchars($last_name) ?>">
                        <?php if (!empty($errors['lname'])) echo "<div class='error'>{$errors['lname']}</div>"; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label for="phone_no">Phone Number:</label><br>
                        <input type="text" id="phone_no" name="phone_no" value="<?= htmlspecialchars($phone_no) ?>" style="width: 430px;">
                        <?php if (!empty($errors['phone_no'])) echo "<div class='error'>{$errors['phone_no']}</div>"; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label for="email">Email:</label><br>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" style="width: 430px;">
                        <?php if (!empty($errors['email'])) echo "<div class='error'>{$errors['email']}</div>"; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label for="password">Password:</label><br>
                        <input type="password" id="password" name="password" style="width: 430px;">
                        <?php if (!empty($errors['password'])) echo "<div class='error'>{$errors['password']}</div>"; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit" style="width: 440px;height: 37px;font-size:15px;">Continue to Chat</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        Already signed up? <a href="login.php">Login now</a>
                    </td>
                </tr>
            </table>
        </form>
    </center>
</body>
</html>
