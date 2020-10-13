<?php  
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';
?>


<html>
<head>
	<title>Welcome to Swirlfeed!</title>
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/register.js"></script>
	<script src="assets/js/jquery-3.5.1.min.js"></script>
	<link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
</head>
<body>

	<?php  

	if(isset($_POST['register_button'])) {
		echo '
		<script>

		$(document).ready(function() {
			$("#first").hide();
			$("#second").show();
		});

		</script>

		';
	}


	?>

	<div class="wrapper">

		<script type="text/javascript">
			var state = false;
			function toggle(){
				if(state){
					document.getElementById("showpasword").setAttribute("type", "password");
					document.getElementById("eyesigner").style.color = '#979797';
					state = false;
				}
				else{
					document.getElementById("showpasword").setAttribute("type", "text");
					document.getElementById("eyesigner").style.color = '#2c00ff';
					state = true;
				}
			}
		</script>

		<div class="login_box">

			<div class="login_header">
				<h1>GOKOK!</h1>
				Login or sign up below!
			</div>
			<br>
			<div id="first">

				<form action="register.php" method="POST">
					<input type="email" name="log_email" placeholder="Email Address" value="<?php 
					if(isset($_SESSION['log_email'])) {
						echo $_SESSION['log_email'];
					} 
					?>" required>
					<br>
					<input type="password" name="log_password" placeholder="Password" id="showpasword">
					<span id="eyesigner" onclick="toggle()">
						<i class="fas fa-eye fa-lg"></i>
					</span>
					<br>
					<?php if(in_array("Email or password was incorrect<br>", $error_array)) echo  "Email or password was incorrect<br>"; ?>
					<input type="submit" name="login_button" value="Login">
					<br>
					<a href="#" id="signup" class="signup">Need an account? Register here!</a>
					<br><br><br>
					<span style="padding: 10px;background-color: #292347;color: #fff;border-radius: 20px;"><a href="resetpassword/requestReset.php" style="color: #fff;">forget pasword</a></span>

				</form>

			</div>

			<div id="second">
				<script type="text/javascript">
					var state = false;
					function toggler(){
						if(state){
							document.getElementById("showpaswordser").setAttribute("type", "password");
							document.getElementById("eyesignerred").style.color = '#979797';
							state = false;
						}
						else{
							document.getElementById("showpaswordser").setAttribute("type", "text");
							document.getElementById("eyesignerred").style.color = '#2c00ff';
							state = true;
						}
					}
				</script>
				<form action="register.php" method="POST">
					<input type="text" name="reg_fname" placeholder="First Name" value="<?php 
					if(isset($_SESSION['reg_fname'])) {
						echo $_SESSION['reg_fname'];
					} 
					?>" required>
					<br>
					<?php if(in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "Your first name must be between 2 and 25 characters<br>"; ?>
					
					


					<input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
					if(isset($_SESSION['reg_lname'])) {
						echo $_SESSION['reg_lname'];
					} 
					?>" required>
					<br>
					<?php if(in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "Your last name must be between 2 and 25 characters<br>"; ?>

					<input type="email" name="reg_email" placeholder="Email" value="<?php 
					if(isset($_SESSION['reg_email'])) {
						echo $_SESSION['reg_email'];
					} 
					?>" required>
					<br>

					<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
					if(isset($_SESSION['reg_email2'])) {
						echo $_SESSION['reg_email2'];
					} 
					?>" required>
					<br>
					<?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>"; 
					else if(in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>";
					else if(in_array("Emails don't match<br>", $error_array)) echo "Emails don't match<br>"; ?>


					<input type="password" name="reg_password" placeholder="Password" required id="showpaswordser">
					<span id="eyesignerred" onclick="toggler()">
						<i class="fas fa-eye fa-lg"></i>
					</span>
					<br>
					<script type="text/javascript">
						var state = false;
						function toggler2(){
							if(state){
								document.getElementById("showpaswordser2").setAttribute("type", "password");
								document.getElementById("eyesignerred2").style.color = '#979797';
								state = false;
							}
							else{
								document.getElementById("showpaswordser2").setAttribute("type", "text");
								document.getElementById("eyesignerred2").style.color = '#2c00ff';
								state = true;
							}
						}
					</script>
					<input type="password" name="reg_password2" placeholder="Confirm Password" required id="showpaswordser2">
					<span id="eyesignerred2" onclick="toggler2()">
						<i class="fas fa-eye fa-lg"></i>
					</span>
					<br>
					<?php if(in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>"; 
					else if(in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
					else if(in_array("Your password must be betwen 5 and 30 characters<br>", $error_array)) echo "Your password must be betwen 5 and 30 characters<br>"; ?>


					<input type="submit" name="register_button" value="Register">
					<br>

					<?php if(in_array("<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>", $error_array)) echo "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>"; ?>
					<a href="#" id="signin" class="signin">Already have an account? Sign in here!</a>
				</form>
			</div>

		</div>

	</div>


</body>
</html>