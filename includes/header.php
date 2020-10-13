<?php  
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");
include("includes/classes/Notification.php");


if (isset($_SESSION['username'])) {
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
}
else {
	header("Location: register.php");
}

?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>GOKOK | Home</title>

	<!-- Javascript -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="assets/js/jquery-3.5.1.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/fontawesome/js/all.js"></script>
	<script src="assets/js/bootbox.min.js"></script>
	<script src="assets/js/demo.js"></script>
	<script src="assets/js/jquery.Jcrop.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>
	<script src="assets/js/ajaxforgokok.js"></script>
	<script src="assets/js/popper.js"></script>


	<!-- CSS -->
	<link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/videos.css" type="text/css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat&family=Roboto&family=Mukta+Mahee">
	<script type="text/javascript">
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	</script>

</head>
<body>

	<div class="top_bar"> 

		<div class="logo">
			<a href="index.php">GOKOK&nbsp;</a>
		</div>


		<div class="search">

			<form action="search.php" method="GET" name="search_form">
				<input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">

				<div class="button_holder">
					<img src="assets/images/icons/magnifying_glass.png">
				</div>

			</form>

			<div class="search_results">
			</div>

			<div class="search_results_footer_empty">
			</div>



		</div><br><br>

		<nav>
			<?php
				//Unread messages 
				$messages = new Message($con, $userLoggedIn);
				$num_messages = $messages->getUnreadNumber();

				//Unread notifications 
				$notifications = new Notification($con, $userLoggedIn);
				$num_notifications = $notifications->getUnreadNumber();

				//Unread notifications 
				$user_obj = new User($con, $userLoggedIn);
				$num_requests = $user_obj->getNumberOfFriendRequests();
			?>


			<a href="<?php echo $userLoggedIn; ?>">
				<span style="width: 16%; display: inline-block; text-align: center;"><img src="<?php echo $user['profile_pic']; ?>" style="height: auto;width: 25px; border-radius: 50%; border:1px solid;"></span>
			</a>
			<a href="index.php">
				<span class="quikmixer"><i class="fas fa-home fa-lg"></i></span>
			</a>
			<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
				<span class="quikmixer"><i class="fas fa-envelope fa-lg"></i></span>
				<?php
				if($num_messages > 0)
				 echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
				?>
			</a>
			<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
				<span class="quikmixer"><i class="fas fa-bell fa-lg"></i></span>
				<?php
				if($num_notifications > 0)
				 echo '<span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>';
				?>
			</a>
			<a href="requests.php">
				<span class="quikmixer"><i class="fas fa-users fa-lg"></i></span>
				<?php
				if($num_requests > 0)
				 echo '<span class="notification_badge" id="unread_requests">' . $num_requests . '</span>';
				?>
			</a>
			<a href="chating.php">
				<span class="quikmixer"><i class="fab fa-facebook-messenger fa-lg"></i></span>
			</a>
			<a href="settings.php">
				<span class="quikmixer"><i class="fas fa-cog fa-lg"></i></span>
			</a>
		</nav>

		<div class="dropdown_data_window" style="height:0px; border:none;"></div>
		<input type="hidden" id="dropdown_data_type" value="">


	</div>


	<script>
	// var userLoggedIn = '<?php echo $userLoggedIn; ?>';

	// $(document).ready(function() {

	// 	$('.dropdown_data_window').scroll(function() {
	// 		var inner_height = $('.dropdown_data_window').innerHeight(); //Div containing data
	// 		var scroll_top = $('.dropdown_data_window').scrollTop();
	// 		var page = $('.dropdown_data_window').find('.nextPageDropdownData').val();
	// 		var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

	// 		if ((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false') {

	// 			var pageName; //Holds name of page to send ajax request to
	// 			var type = $('#dropdown_data_type').val();


	// 			if(type == 'notification')
	// 				pageName = "ajax_load_notifications.php";
	// 			else if(type == 'message')
	// 				pageName = "ajax_load_messages.php"


	// 			var ajaxReq = $.ajax({
	// 				url: "includes/handlers/" + pageName,
	// 				type: "POST",
	// 				data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
	// 				cache:false,

	// 				success: function(response) {
	// 					$('.dropdown_data_window').find('.nextPageDropdownData').remove(); //Removes current .nextpage 
	// 					$('.dropdown_data_window').find('.noMoreDropdownData').remove(); //Removes current .nextpage 


	// 					$('.dropdown_data_window').append(response);
	// 				}
	// 			});

	// 		} //End if 

	// 		return false;

	// 	}); //End (window).scroll(function())


	// });

	</script>

	<script>
	$(function(){

		var userLoggedIn = '<?php echo $userLoggedIn; ?>';
		var dropdownInProgress = false;

	    $(".dropdown_data_window").scroll(function() {
	    	var bottomElement = $(".dropdown_data_window a").last();
			var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

	        // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
	        if (isElementInView(bottomElement[0]) && noMoreData == 'false') {
	            loadPosts();
	        }
	    });

	    function loadPosts() {
	        if(dropdownInProgress) { //If it is already in the process of loading some posts, just return
				return;
			}
			
			dropdownInProgress = true;

			var page = $('.dropdown_data_window').find('.nextPageDropdownData').val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'

			var pageName; //Holds name of page to send ajax request to
			var type = $('#dropdown_data_type').val();

			if(type == 'notification')
				pageName = "ajax_load_notifications.php";
			else if(type == 'message')
				pageName = "ajax_load_messages.php";

			$.ajax({
				url: "includes/handlers/" + pageName,
				type: "POST",
				data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
				cache:false,

				success: function(response) {

					$('.dropdown_data_window').find('.nextPageDropdownData').remove(); //Removes current .nextpage 
					$('.dropdown_data_window').find('.noMoreDropdownData').remove();

					$('.dropdown_data_window').append(response);

					dropdownInProgress = false;
				}
			});
	    }

	    //Check if the element is in view
	    function isElementInView (el) {
	        var rect = el.getBoundingClientRect();

	        return (
	            rect.top >= 0 &&
	            rect.left >= 0 &&
	            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
	            rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
	        );
	    }
	});

	</script>


	<div class="wrapper">