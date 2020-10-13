<?php 
require '../../config/config.php';
	
	if(isset($_GET['id']))
		$post_id = $_GET['id'];

	if(isset($_POST['result'])) {
		if($_POST['result'] == 'true')
			$query = mysqli_query($con, "UPDATE comment SET removed='yes' WHERE id='$id'");
	}

?>