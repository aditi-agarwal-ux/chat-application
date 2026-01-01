<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Access check
if (!isset($_SESSION['email'])) {
    echo "Access denied.";
    exit;
}

// Initialize variables
$first_name = $last_name = $phone_no = $email = $password = "";
$errors = [];

// Get original email to use in WHERE clause
$original_email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Pre-fill form from session
    $first_name = $_SESSION['fname'] ?? '';
    $last_name = $_SESSION['lname'] ?? '';
    $phone_no = $_SESSION['phone_no'] ?? '';
    $email = $_SESSION['email'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clean input
    $first_name = trim($_POST['fname']);
    $last_name = trim($_POST['lname']);
    $phone_no = trim($_POST['phone_no']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validations
    if (empty($first_name) || !preg_match("/^[A-Za-z0-9]{2,30}$/", $first_name)) {
        $errors['fname'] = "First name must be 2–30 letters or numbers.";
    }

    if (empty($last_name) || !preg_match("/^[A-Za-z0-9]{2,30}$/", $last_name)) {
        $errors['lname'] = "Last name must be 2–30 letters or numbers.";
    }

    if (empty($phone_no) || !preg_match("/^[0-9]{10}$/", $phone_no)) {
        $errors['phone_no'] = "Phone number must be exactly 10 digits.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (!empty($password) && strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters.";
    }

    // Update in database if no errors
    if (empty($errors)) {
        $conn = new mysqli("localhost", "new_user", "password", "project_2025");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare query depending on whether password is updated
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE user SET firstname = ?, lastname = ?, phonenumber = ?, email = ?, password = ? WHERE email = ?");
            $stmt->bind_param("ssssss", $first_name, $last_name, $phone_no, $email, $hashed_password, $original_email);
        } else {
            $stmt = $conn->prepare("UPDATE user SET firstname = ?, lastname = ?, phonenumber = ?, email = ? WHERE email = ?");
            $stmt->bind_param("sssss", $first_name, $last_name, $phone_no, $email, $original_email);
        }

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            // Update session
            $_SESSION['fname'] = $first_name;
            $_SESSION['lname'] = $last_name;
            $_SESSION['phone_no'] = $phone_no;
            $_SESSION['email'] = $email;

            header("Location: chat.php");
            exit;
        } else {
            echo "Error: Could not update (possibly no changes made).";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<center>
    <form method="POST" class="custom-form" style="margin-top: 60px;" action="update_profile.php">
        <table>
            <th colspan="2"><h2><b>Edit Profile</b></h2></th>
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
                    <small>Leave blank if you don't want to change it.</small>
                </td>
            </tr>
        </table>
        <br><br>
        <button type="submit"><b>Edit</b></button>
    </form>
</center>
