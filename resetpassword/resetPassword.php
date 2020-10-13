<?php
include("../config/config.php");
// require '../config/config.php';

if(!isset($_GET["code"])) {
    exit("Can't find page");
}

$code = $_GET["code"];

$getEmailQuery = mysqli_query($con, "SELECT email FROM resetPasswords WHERE code='$code'");
if(mysqli_num_rows($getEmailQuery) == 0){
    exit("Can't find page");
}



if(isset($_POST["password"])) {
    $pw = $_POST["password"];
    $pw = md5($pw);

    $row = mysqli_fetch_array($getEmailQuery);
    $email = $row["email"];

    $query = mysqli_query($con, "UPDATE users SET password='$pw' WHERE email='$email'");

    if($query) {
        $query = mysqli_query($con, "DELETE FROM resetPasswords WHERE code='$code'");
        exit("Password updated");
    }
    else {
        exit("Something went wrong");
    }
}

?>

<form method="POST" style="width: 100%;height: auto;padding: 10px;justify-content: center;display: grid;">
    <input type="password" name="password" placeholder="New password" id="showpaswordser2" style="width: 100vh; max-width:250px; height:40px; font-size: 15px;border-radius: 25px;padding: 10px;border: 1px solid;">
    <br>
    <span id="eyesignerred2" onclick="toggler2()" style="background-color: #fff; color: #979797;margin: 10px; text-align: center; box-shadow: 2px 2px 3px #979797; border: 1px solid #ecebeb;">
        Show Password
    </span>
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
    <br>
    <input type="submit" name="submit" value="Update password" style="width: 100vh; max-width:250px; height:35px;margin-top: 10px;font-size: 15px;background-color: #4080ff;border: none;border-radius: 25px;color: #fff;font-weight: bold;">
</form>