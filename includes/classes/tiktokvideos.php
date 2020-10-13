<?php

if(isset($_GET['id'])) {
	$id = $_GET['id'];
}
else {
	$id = 0;
}

public function loadPostsFriendsvid($data, $limit) {

	$page = $data['page']; 
	$userLoggedIn = $this->user_obj->getUsername();

	if($page == 1) 
		$start = 0;
	else 
		$start = ($page - 1) * $limit;


	$str = ""; //String to return 
	$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

	if(mysqli_num_rows($data_query) > 0) {


		$num_iterations = 0; //Number of results checked (not necasserily posted)
		$count = 1;

		while($row = mysqli_fetch_array($data_query)) {
			$id = $row['id'];
			$body = $row['body'];
			$added_by = $row['added_by'];
			$date_time = $row['date_added'];
			$imagePath = $row['image'];
			$videoPath = $row['videos'];

			//Prepare user_to string so it can be included even if not posted to a user
			if($row['user_to'] == "none") {
				$user_to = "";
			}
			else {
				$user_to_obj = new User($this->con, $row['user_to']);
				$user_to_name = $user_to_obj->getFirstAndLastName();
				$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
			}

			//Check if user who posted, has their account closed
			$added_by_obj = new User($this->con, $added_by);
			if($added_by_obj->isClosed()) {
				continue;
			}

			$user_logged_obj = new User($this->con, $userLoggedIn);
			if($user_logged_obj->isFriend($added_by)){

				if($num_iterations++ < $start)
					continue; 


				//Once 10 posts have been loaded, break
				if($count > $limit) {
					break;
				}
				else {
					$count++;
				}

				if($userLoggedIn == $added_by)
					$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
				else 
					$delete_button = "";


				$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
				$user_row = mysqli_fetch_array($user_details_query);
				$first_name = $user_row['first_name'];
				$last_name = $user_row['last_name'];
				$profile_pic = $user_row['profile_pic'];


				?>
				<script> 
					function toggle<?php echo $id; ?>(event){
                            
					    var target = $(event.target);
					 
					    if (!target.is('a') && !target.is('button')) {
					        var element = document.getElementById("toggleComment<?php echo $id; ?>");
					 
					        if(element.style.display == "block")
					            element.style.display = "none";
					        else
					            element.style.display = "block";
					    }
					                                
					}

				</script>
				<?php

				$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
				$comments_check_num = mysqli_num_rows($comments_check);


				//Timeframe
				$date_time_now = date("Y-m-d H:i:s");
				$start_date = new DateTime($date_time); //Time of post
				$end_date = new DateTime($date_time_now); //Current time
				$interval = $start_date->diff($end_date); //Difference between dates 
				if($interval->y >= 1) {
					if($interval->y == 1)
						$time_message = $interval->y . " year ago"; //1 year ago
					else 
						$time_message = $interval->y . " years ago"; //1+ year ago
				}
				else if ($interval->m >= 1) {
					if($interval->d == 0) {
						$days = " ago";
					}
					else if($interval->d == 1) {
						$days = $interval->d . " day ago";
					}
					else {
						$days = $interval->d . " days ago";
					}


					if($interval->m == 1) {
						$time_message = $interval->m . " month ". $days;
					}
					else {
						$time_message = $interval->m . " months ". $days;
					}

				}
				else if($interval->d >= 1) {
					if($interval->d == 1) {
						$time_message = "Yesterday";
					}
					else {
						$time_message = $interval->d . " days ago";
					}
				}
				else if($interval->h >= 1) {
					if($interval->h == 1) {
						$time_message = $interval->h . " hour ago";
					}
					else {
						$time_message = $interval->h . " hours ago";
					}
				}
				else if($interval->i >= 1) {
					if($interval->i == 1) {
						$time_message = $interval->i . " minute ago";
					}
					else {
						$time_message = $interval->i . " minutes ago";
					}
				}
				else {
					if($interval->s < 30) {
						$time_message = "Just now";
					}
					else {
						$time_message = $interval->s . " seconds ago";
					}
				}

				if($imagePath != "" && $imagePath != "none") {
					$imageDiv = "<div class='postedImage'>
									<img src='$imagePath' alt='$first_name $last_name'>
								</div>";
				}
				else {
					$imageDiv = "";
				}

				if($videoPath != "" && $videoPath != "none") {
					$videoDiv = "<div class='videoclonethinder' style='margin: 5px auto;'>
									<video src='$videoPath' class='video__player' controls loop alt='$first_name $last_name' style='width: 100%;height: auto;max-height: 350px;'>
									</video>
								</div>";
				}
				else {
					$videoDiv = "";
				}

				$str .= "<div class='app__videos'>

					    	$videoDiv

					        <div class='videoFooter'>
					            <div class='videoFooter__text'>
					            	<img src='$profile_pic' class='tiktokprofilepic'>
					                <h3>@$added_by</h3>
					                <p>$body</p>
					            </div>
					        </div>

					        <div class='videoSidebar'>
					            <div class='videoSidebar__button'>
					                <i class='fas fa-heart fa-lg' aria-hidden='true'></i> 
					                <p>21516</p>
					            </div>

					            <div class='videoSidebar__button'>
					                <a href=""><img src='assets/images/icons/pink-comment.png' height='30px' /></a> 
					                <p>45656</p>
					            </div>

					            <div class='videoSidebar__button'>
					                <i class='fas fa-comment-alt fa-lg' aria-hidden='true'></i> 
					                <p>65166</p>
					            </div>
					        </div>
					    </div>";
			}

			?>
			<script>

				$(document).ready(function() {

					$('#post<?php echo $id; ?>').on('click', function() {
						bootbox.confirm("Are you sure you want to delete this post?", function(result) {

							$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

							if(result)
								location.reload();

						});
					});


				});

			</script>
			<?php

		} //End while loop

		if($count > $limit) 
			$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
						<input type='hidden' class='noMorePosts' value='false'>";
		else 
			$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;' class='noMorePostsText'> No more posts to show! <br> Add More Friends </p>";
	}

	echo $str;
}






?>