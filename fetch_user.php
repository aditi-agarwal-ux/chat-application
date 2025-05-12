<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['email'])) {
    echo "User not logged in.";
    exit;
}

$email = $_SESSION['email'];

$conn = mysqli_connect("localhost", "new_user", "password", "project_2025") or die("Connection failed");

// Fetch all users except the current one
$query = "SELECT * FROM user WHERE email != ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$output = '
<div style="text-align: left; width: fit-content;">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Username</td>
            <td>Status</td>
            <td>Action</td>
        </tr>
';

while ($row = mysqli_fetch_assoc($result)) {
    $full_name = $row['firstname'] . ' ' . $row['lastname'];
    $is_online = strtolower($row['status']) === 'online';

    $status_label = $is_online
        ? '<span class="label label-success">Online</span>'
        : '<span class="label label-danger">Offline</span>';

    $output .= '
        <tr>
            <td>' . htmlspecialchars($full_name) . '</td>
            <td>' . $status_label . '</td>
            <td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="' . $row['user_id'] . '" data-tousername="' . htmlspecialchars($row['firstname']) . '">Start Chat</button></td>
        </tr>
    ';
}

$output .= '
    </table>
</div>
';


echo $output;

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
