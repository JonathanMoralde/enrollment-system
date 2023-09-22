<?php

session_start();
include_once "connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';

function sendemail_verify($email,  $name, $verify_token){
    $mail = new PHPMailer(true);

    try{
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = "smtp.gmail.com";
    $mail->Username = "olrmshs@gmail.com";
    $mail->Password = "wxui xnlt bvzb vrls";

    $mail->SMTPSecure = "tls";

    $mail->Port = 587;


    $mail->setFrom("olrmshs@gmail.com",$name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Email Verification from Our Lady of the Roses Montessori Learning Center";

    $email_template = "
    <h2>You have registered with Our Lady of the Roses Montessori Learning Center</h2>
    <h5>Verify your email address by clicking the below given link</h5>
    <br></br>
    <a href='http://localhost/enrollment-system/verify_email.php?token=$verify_token'> Click Here </a>
    ";
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debugging
    // $mail->Debugoutput = 'html'; // Display debug output as HTML
    
   $mail->Body = $email_template;
   $mail->send();
//    echo 'Message has been sent';
    }
    catch (Exception $e){
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

//checks if value of name="email_add" is set
if(isset($_POST['email'])) {

    //transfers value of name="" from form to variable
    //$r_username = $_POST['reg_username'];

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $name = 'Montessori Learning Center';
    $verify_token = md5(rand());
    sendemail_verify("$email", "$name", "$verify_token");

    

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $msg="User already exists.";
    } else {

        $sql = "INSERT INTO user (email, pwd, verify_token) VALUES ('$email', '$pwd', '$verify_token')";

        if (mysqli_query($conn, $sql)) {
   
            echo $msg="<h4>Registration Successfull! Please verify your Email Address.</h4>";
            header("location: success_page.php");
            
        } else {
            $msg="Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    // header("location: login.php");
}


?>