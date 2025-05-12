<?php
session_start();
if(!isset($_SESSION['email'])) {
  header('location:login.php');
  exit();
}
$_SESSION['from_chat'] = true;

$conn = mysqli_connect("localhost", "new_user", "password", "project_2025") or die("Connection failed");
$email=$_SESSION['email'];
$query = "SELECT * FROM user WHERE email = '$email'";
$result=mysqli_query($conn,$query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['fname']=$row['firstname'];
    $_SESSION['lname']=$row['lastname'];

}
?>
<html>
<head>
   <title>Chat Application using PHP Ajax jQuery</title>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emojionearea@3.4.1/dist/emojionearea.min.css">
   <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/emojionearea@3.4.1/dist/emojionearea.min.js"></script>
</head>
<body>
   <div class="container">
       <br />
       <!-- <h3 align="center">Dahbored of chat application</h3><br /> -->
       <div class="table-responsive">
           <!-- <h4 align="center">Online Users</h4> -->
           <p align="right" ><a href="update_profile.php">Edit Profile</a></p>
           <p align="right"> Hi  <?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?> - <a href="logout.php? logoutid=<?php echo $_SESSION['email'];?>">Logout</a></p>
           <div class="chat-container">
           <div id="user_details"></div>
           <div id="user_model_details"></div>
           </div>
        </div>
   </div>
</body>
</html>
<style>
.img {
            width: 50px;
            height: 50px;
            background-size: contain;
            background-repeat: no-repeat;
            border-radius: 50%;
        }

        .chat-container {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-top: 20px;
        }

        #user_details {
            flex-shrink: 0;
            min-width: 300px;
        }

        .user_dialog {
            position: relative;
            z-index: 9999;
        }

        @media (max-width: 768px) {
            .chat-container {
                flex-direction: column;
            }
        }
</style>
<script>
$(document).ready(function(){
   fetch_user();
   setInterval(function(){
       update_last_activity();
       fetch_user();
       update_chat_history_data();
   }, 5000); // Refresh every 5 seconds

   function fetch_user(){
       $.ajax({
           url: "fetch_user.php",
           method: "POST",
           success: function(data){
               $('#user_details').html(data);
           }
       });
   }

   function update_last_activity(){
       $.ajax({
           url: "update_last_activity.php"
       });
   }

   function make_chat_dialog_box(to_user_id, to_user_name){
       var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="Chat with '+to_user_name+'">';
       modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y:scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
       modal_content += fetch_user_chat_history(to_user_id); // Preload history when chat opens
       modal_content += '</div>';
       modal_content += '<div class="form-group">';
       modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>';
       modal_content += '</div><div class="form-group" align="right">';
       modal_content += '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>';
       $('#user_model_details').html(modal_content);
   }

   $(document).on('click', '.start_chat', function(){
       var to_user_id = $(this).data('touserid');
       var to_user_name = $(this).data('tousername');
       make_chat_dialog_box(to_user_id, to_user_name);
       $("#user_dialog_"+to_user_id).dialog({
           autoOpen: false,
           height:520,
           width: 840,
           position: {
        my: "left top",
        at: "right top",
        of: "#user_details"
    },
           
       });
       $('#user_dialog_'+to_user_id).dialog('open');
       $('#chat_message_'+to_user_id).emojioneArea({
         pickerPosition:"top",
         toneStyle:"bullet"
       });
   });

   $(document).on('click', '.send_chat', function(){
       var to_user_id = $(this).attr('id');
       var chat_message = $.trim($('#chat_message_'+to_user_id).val());
       if(chat_message !== ''){
           $.ajax({
               url: "insert_chat.php",
               method: "POST",
               data: {to_user_id: to_user_id, chat_message: chat_message},
               success: function(data){
                 var element = $('#chat_message_'+to_user_id).emojioneArea();
                 element[0].emojioneArea.setText('');
                 $('#chat_message_' + to_user_id).val('');
                 $('#chat_history_'+to_user_id).html(data); // Update chat history
                
                }
           })
       } else {
           alert('Type something');
       }
   });

   function fetch_user_chat_history(to_user_id)
   {
       $.ajax({
           url:"fetch_user_chat_history.php",
           method:"POST",
           data:{to_user_id:to_user_id},
           success:function(data){
               $('#chat_history_'+to_user_id).html(data);
           }
       });
   }

   function update_chat_history_data(){
       $('.chat_history').each(function(){
           var to_user_id = $(this).data('touserid');
           fetch_user_chat_history(to_user_id);
       });
   }
  
});
</script>
