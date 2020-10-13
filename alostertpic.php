<?php 
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['u']))
	$user_to = $_GET['u'];
else {
	$user_to = $message_obj->getMostRecentUser();
	if($user_to == false)
		$user_to = 'new';
}

if($user_to != "new")
	$user_to_obj = new User($con, $user_to);

if(isset($_POST['post_message'])) {

	if(isset($_POST['message_body'])) {
		$body = mysqli_real_escape_string($con, $_POST['message_body']);
		$date = date("Y-m-d H:i:s");
		$message_obj->sendMessage($user_to, $body, $date);
	}

}

 ?>
 

	<div id="conversations" style="width: 100%; height: 100%; padding-top: 20px;">
		<div class="loaded_conversations">
			<?php echo $message_obj->getConvos(); ?>
		</div>		
	</div>
	<a href="messages.php?u=new" style="position: fixed; height: 56px; width: 56px; background-color: #000; bottom: 10px; left: 10px; text-decoration: none; text-align: center; font-size: 40px; padding: 6px; border: none; border-radius: 50%; z-index: 3; box-shadow: 0 0 10px 0 #000;"><i class="fa fa-user-plus"></i></a>
