<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require('../../authentication/connection.php');
require 'vendor/autoload.php';


$email = $_GET['email'];

function send_link($email){
  require('../../authentication/connection.php');
  $mail = new PHPMailer();
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 465;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->SMTPAuth = true;
  $mail->Username = 'johnmessi114@gmail.com';
  $mail->Password = 'not public';
  $mail->setFrom('johnmessi114@gmail.com', 'Nitish Kushwaha');
  $mail->addReplyTo('johnmessi114@gmail.com', 'Nitish Kushwaha');
  $mail->addAddress($email);
  $mail->isHTML(true);

  $mail->Subject = 'Email verification';

  $expFormat = mktime(
   date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
   );
   $expDate = date("Y-m-d H:i:s",$expFormat);
   $verification_token = md5($email);
   $addKey = substr(md5(uniqid(rand(),1)),3,10);
   $verification_token = $verification_token . $addKey;

  $email_template = "
  <h5>This is your reset password link.</h5>
  <br>
  <a href='http://localhost/faculty_forget/reset/reset.php?token=$verification_token&email=$email'>Verify Link</a>
  <h5>DO NOT SHARE THIS LINK WITH ANYONE</h5>
  ";

  $mail->Body = $email_template;

  if(!$mail->send()){
    echo "Error : 1";

  }else{

    $add_user = "INSERT INTO token (`email` , `token`, `expirydate`) VALUES ('$email', '$verification_token', '$expDate')";
    if($conn->query($add_user)){
      echo "Verification link sent!!";
    }else {
      echo "Error : 2";
    }

  }
}

$check_student = "SELECT * FROM faculty WHERE faculty_id='$email' ";
$result = $conn->query($check_student);

if($result->num_rows == 0){
  echo "User Doesn't exists!!";
}
else{
  send_link($email);

}
?>
