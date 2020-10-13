<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require '../config/config.php';

if(isset($_POST["email"])) {

    $emailTo = $_POST["email"];

    $code = uniqid(true);
    $query = mysqli_query($con, "INSERT INTO resetPasswords(code, email) VALUES('$code', '$emailTo')");
    if(!$query){
        exit("Error");
    }
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'youremail@gmail.com';                     // SMTP username
        $mail->Password   = '@2seema$';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('youremail@gmail.com', 'Site Name');
        $mail->addAddress($emailTo);     // Add a recipient
        $mail->addReplyTo('no-reply@gmail.com', 'No reply');

        // Content
        $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/resetPassword.php?code=$code";
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Your Password reset link';
        $mail->Body    = "<h1>You requested a password reset</h1>
                            Click <a href='$url'>This link</a> to do so";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Reset Password link has been sent to your email';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    exit();
}

?>

<form method="POST" style="width: auto;height: auto;padding: 10px;justify-content: center;display: grid; border-radius: 10px; box-shadow: 1px 1px 5px #000; margin-top: 50px;">
    <input type="text" name="email" autocomplete="off" style="width: 100vh; max-width:250px; height:35px;">
    <br>
    <input type="submit" name="submit" value="Request Link" style="width: 100vh; max-width:250px; height:35px;margin-top: 10px;font-size: 15px;background-color: #4080ff;border: none;border-radius: 25px;color: #fff;font-weight: bold;">
    <p style="width: auto;max-width: 250px;">
        Enter your user email address to request reset password link
    </p>
</form>