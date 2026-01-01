<?php
$conn = mysqli_connect("localhost", "new_user", "password", "project_2025") or die("Connection failed");

$query = "DELETE FROM user "; // Deletes all rows
mysqli_query($conn, $query);

mysqli_close($conn);

?>
