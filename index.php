<?php 
include("includes/header.php");

if(isset($_POST['post'])){

	$uploadOk = 1;
	$imageName = $_FILES['fileToUpload']['name'];
	$errorMessage = "";

	if($imageName != "") {
		$targetDir = "assets/images/posts/";
		$imageName = $targetDir . uniqid() . basename($imageName);
		$imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

		if($_FILES['fileToUpload']['size'] > 10000000) {
			$errorMessage = "Sorry your file is too large";
			$uploadOk = 0;
		}

		if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg") {
			$errorMessage = "Sorry, only jpeg, jpg and png files are allowed";
			$uploadOk = 0;
		}

		if($uploadOk) {
			
			if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)) {
				//image uploaded okay
			}
			else {
				//image did not upload
				$uploadOk = 0;
			}
		}

	}

	if($uploadOk) {
		$post = new Post($con, $userLoggedIn);
		$post->submitPost($_POST['post_text'], 'none', $imageName, 'none');
		header("location: index.php");
	}
	else {
		echo "<div style='text-align:center;' class='alert alert-danger'>
				$errorMessage
			</div>";
	}

}
?>

	<script type="text/javascript">
		function showPreview(event){
			if(event.target.files.length > 0) {
				var src = URL.createObjectURL(event.target.files[0]);
				var preview = document.getElementById("fileToUpload-preview");
				preview.src = src;
				preview.style.display = "block";
			}
		}
	</script>
	<div class="preview" style="width: 100%; height: auto;">
		<img id="fileToUpload-preview" style="width: auto; height: auto; max-height: 300px; max-width: 100%; margin: 0 auto; padding-top: 40px; display: none;">
	</div>
	<div class="main_column column">
		<form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
			<input type="file" name="fileToUpload" id="fileToUpload" onchange="showPreview(event);">
			<label for="fileToUpload" class="fileuplodingthing"><i class="fas fa-file-image" aria-hidden="true"></i></label>
			<textarea name="post_text" id="post_text" placeholder="Write something here..."></textarea>
			<input type="submit" name="post" id="post_button" value="Post">
		</form>
		<div style="padding: 0; text-align: center;margin: -7px 0px 7px; background-color: #f4f4f4;"><a href="videouploder.php" style="color: blue;">Uplode Video</a></div>

		<div class="posts_area"></div>
		<!-- <button id="load_more">Load More Posts</button> -->
		<img id="loading" src="assets/images/icons/loading.gif">


	</div>

	<div class="user_details column">

		<h4>Popular</h4>

		<div class="trends">
			<?php 
			$query = mysqli_query($con, "SELECT * FROM trends ORDER BY hits DESC LIMIT 9");

			foreach ($query as $row) {
				
				$word = $row['title'];
				$word_dot = strlen($word) >= 14 ? "..." : "";

				$trimmed_word = str_split($word, 14);
				$trimmed_word = $trimmed_word[0];

				echo "<div style'padding: 1px'>";
				echo $trimmed_word . $word_dot;
				echo "<br></div><br>";


			}

			?>
		</div>


	</div>




	<script>
	//var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	//var inProgress = false;

	$(document).ready(function() {

		// inProgress = true;
		// $('#loading').show();

		// //Original ajax request for loading first posts 
		// $.ajax({
		// 	url: "includes/handlers/ajax_load_posts.php",
		// 	type: "POST",
		// 	data: "page=1&userLoggedIn=" + userLoggedIn,
		// 	cache:false,

		// 	success: function(data) {
		// 		$('#loading').hide();
		// 		$('.posts_area').html(data);
		// 		inProgress = false;
		// 	}
		// });

		// $(window).scroll(function() {
		// //$('#load_more').on("click", function() {

		// 	var height = $('.posts_area').height(); //Div containing posts
		// 	var scroll_top = $(this).scrollTop();
		// 	var page = $('.posts_area').find('.nextPage').val();
		// 	var noMorePosts = $('.posts_area').find('.noMorePosts').val();

		// 	//if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
		// 	//if (noMorePosts == 'false') {
		// 	if($(window).scrollTop() > $('body').height() / 2 && noMorePosts == 'false') {

		// 		if(inProgress) {
		// 			return;
		// 		}
				
		// 		inProgress = true;
		// 		$('#loading').show();

		// 		var ajaxReq = $.ajax({
		// 			url: "includes/handlers/ajax_load_posts.php",
		// 			type: "POST",
		// 			data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
		// 			cache:false,

		// 			success: function(response) {
		// 				$('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
		// 				$('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 
		// 				$('.posts_area').find('.noMorePostsText').remove(); //Removes current .nextpage 

		// 				$('#loading').hide();
		// 				$('.posts_area').append(response);
		// 				inProgress = false;
		// 			}
		// 		});

		// 	} //End if 

		// 	return false;

		// }); //End (window).scroll(function())


	});

	</script>


<script>
$(function(){

	var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	var inProgress = false;

	loadPosts(); //Load first posts

    $(window).scroll(function() {
    	var bottomElement = $(".status_post").last();
    	var noMorePosts = $('.posts_area').find('.noMorePosts').val();

        // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
        if (isElementInView(bottomElement[0]) && noMorePosts == 'false') {
            loadPosts();
        }
    });

    function loadPosts() {
        if(inProgress) { //If it is already in the process of loading some posts, just return
			return;
		}
		
		inProgress = true;
		$('#loading').show();

		var page = $('.posts_area').find('.nextPage').val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'

		$.ajax({
			url: "includes/handlers/ajax_load_posts.php",
			type: "POST",
			data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
			cache:false,

			success: function(response) {
				$('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
				$('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 
				$('.posts_area').find('.noMorePostsText').remove(); //Removes current .nextpage 

				$('#loading').hide();
				$(".posts_area").append(response);

				inProgress = false;
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




	</div>
</body>
</html>