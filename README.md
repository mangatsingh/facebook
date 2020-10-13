# facebook
## Demmo Site Visit this >>  googlescale.com

![2](https://1.bp.blogspot.com/-lWtfrjqXqpw/X4UFEC_NCAI/AAAAAAAABMA/UWjEoZBwbN4XeCvNe-yBa4TDGl0txJ-2wCLcBGAsYHQ/s555/Demo2.PNG)
![3](https://1.bp.blogspot.com/-3fQ83uSRTfc/X4UGXHcAr0I/AAAAAAAABMU/Hf9rnyIa_L83Ox_XpexhGFloa_p8hMf0QCLcBGAsYHQ/s559/facebook.PNG)
![3](https://1.bp.blogspot.com/-0OIJg22nWsc/X4UFF_N3j_I/AAAAAAAABME/emg5PAOQdKU2CpHqBuA-KTmT2YOgOCOnwCLcBGAsYHQ/s486/local.PNG)

## Reset Password PHP Mailer
Go  `resetpassword/requestReset.php` and change this
```php
// Line number 32 and 33
$mail->Username = 'youremail@gmail.com';                     // SMTP username this is email address
$mail->Password = 'password';                               // Enter email password
// And line Number 38
$mail->setFrom('youremail@gmail.com', 'Put Site Name');
```

## Connect data base
Go  `config/config.php` and change this
```php
// Line
$con = mysqli_connect("localhost", "root", "", "priblo"); //Connection variable
```

## SQL file in project folder
-gokokextra.sql
