<?php


$conn = mysqli_connect("localhost", "new_user", "password", "project_2025");


// Fetch chat history between two users
function fetch_user_chat_history($from_user_id, $to_user_id, $conn)
{
   $output = '';
   $query = "
   SELECT * FROM chat_message
   WHERE (from_user_id = ? AND to_user_id = ?)
      OR (from_user_id = ? AND to_user_id = ?)
   ORDER BY timestamp ASC
   ";


   $stmt = mysqli_prepare($conn, $query);
   mysqli_stmt_bind_param($stmt, "iiii", $from_user_id, $to_user_id, $to_user_id, $from_user_id);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);


   $output .= '<ul class="list-unstyled">';
   while($row = mysqli_fetch_assoc($result))
   {
       $user_name = '';
       $chat_message = htmlentities($row['chat_message']);


       if($row["from_user_id"] == $from_user_id)
       {
           $user_name = '<b class="text-success">You</b>';
       }
       else
       {
           $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $conn).'</b>';
       }


       $output .= '
       <li style="border-bottom:1px dotted #ccc">
           <p>'.$user_name.' - '.$chat_message.'
           <div align="right">
               - <small><em>'.$row['timestamp'].'</em></small>
           </div>
           </p>
       </li>';
   }
   $output .= '</ul>';


   return $output;
}


// Get user's name from user ID
function get_user_name($user_id, $conn){
   $query = "SELECT firstname FROM user WHERE user_id = ?";
   $stmt = mysqli_prepare($conn, $query);
   mysqli_stmt_bind_param($stmt, "i", $user_id);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);


   if ($row = mysqli_fetch_assoc($result)) {
       return $row['firstname'];
   }


   return 'Unknown';
}
?>


