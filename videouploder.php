<?php 
include("includes/header.php");

if(isset($_POST['post'])){

	$uploadOk = 1;
	$videoName = $_FILES['fileToUploadvid']['name'];
	$errorMessage = "";

	if($videoName != "") {
		$targetDir = "assets/videos/posts/";
		$videoName = $targetDir . uniqid() . basename($videoName);
		$videoFileType = pathinfo($videoName, PATHINFO_EXTENSION);

		if($_FILES['fileToUploadvid']['size'] > 1000000000) {
			$errorMessage = "Sorry your file is too large";
			$uploadOk = 0;
		}

		if(strtolower($videoFileType) != "mp4") {
			$errorMessage = "Sorry, only mp4 files are allowed";
			$uploadOk = 0;
		}

		if($uploadOk) {
			
			if(move_uploaded_file($_FILES['fileToUploadvid']['tmp_name'], $videoName)) {
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
		$post->submitPost($_POST['post_text'], 'none', 'none', $videoName);
		header("location: index.php");
	}
	else {
		echo "<div style='text-align:center;' class='alert alert-danger'>
				$errorMessage
			</div>";
	}

}
?>
<div class="main_column column">
	<script type="text/javascript">
		function showPreview(event){
			if(event.target.files.length > 0) {
				var src = URL.createObjectURL(event.target.files[0]);
				var preview = document.getElementById("fileToUploadvid-preview");
				preview.src = src;
				preview.style.display = "block";
			}
		}
	</script>
	<div class="preview" style="width: 100%; height: auto;">
		<video id="fileToUploadvid-preview" style="width: auto; height: auto; max-height: 300px; max-width: 100%; margin: 0 auto; padding-top: 40px; display: none;"></video>
	</div>

	<form class="post_form" action="videouploder.php" method="POST" enctype="multipart/form-data">
		<input type="file" name="fileToUploadvid" id="fileToUploadvid" onchange="showPreview(event);" style="display: none;">
		<label for="fileToUploadvid" class="fileuplodingthing"><i class="fas fa-file-video" aria-hidden="true"></i></label>
		<textarea name="post_text" id="post_text" placeholder="Write something here..."></textarea>
		<input type="submit" name="post" id="post_button" value="Post">
	</form>
	<p>
		Slect Video File and click post
	</p>
</div>