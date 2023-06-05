<?php
require('connection.php');
session_start();

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;



function sendMail($email,$v_code){
    require ("PHPMailer/PHPMailer.php");
    require ("PHPMailer/SMTP.php");
    require ("PHPMailer/Exception.php"); 

    $mail = new PHPMailer(true);

    try {
        //Server settings
        
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'm6736295@gmail.com';                     //SMTP username
        $mail->Password   = 'rhpkjadwsmjpqnpk';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('m6736295@gmail.com', 'Shailendra');
        $mail->addAddress($email);     //Add a recipient
       
    
        
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email verification from Shailendra';
        $mail->Body    = "Thanks for Registration! 
        Clicks the link below to verify the email address 
        <a href='http://localhost/emailverify/verify.php?email=$email&v_code=$v_code'>Verify</a>";
        
    
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }

}
# for login
if(isset($_POST['login']))
{
    $query="SELECT * FROM `registered_users` WHERE `email`='$_POST[email_username]' OR `username`='$_POST[email_username]' ";
    $result=mysqli_query($con,$query);
    if($result){
        if(mysqli_num_rows($result)==1){
            $result_fetch=mysqli_fetch_assoc($result);
            if(password_verify($_POST['password'],$result_fetch['password']))
            {
                #if password matched
               $_SESSION['logged_in']=true;
               $_SESSION['username']=$result_fetch['username'];
               header("location:index.php");
            }
            else{
                #if incorrect password
                echo"
                <script>
                alert('Incorrect Password');
                window.location.href='index.php';
                </script>
            ";
            }
        }
        else{
            echo"
            <script>
            alert('Email or Username Not Registered Please signup');
            window.location.href='index.php';
            </script>
        ";
        }
    }
    else{
        echo"
            <script>
            alert('can not run query');
            window.location.href='index.php';
            </script>
        ";
    }
}



# for registration
if(isset($_POST['register']))
{
    $user_exist_query="SELECT * FROM `registered_users`WHERE `username`='$_POST[username]' OR `email`='$_POST[email]' ";
    $result=mysqli_query($con,$user_exist_query);
    if($result){
        if(mysqli_num_rows($result)>0){
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['username']==$_POST['username'])
            {
                echo"
                <script>
                alert('$result_fetch[username]-Username already taken');
                window.location.href='index.php';
                </script>
                ";
            }
            else{
                echo"
                <script>
                alert('$result_fetch[email]-E-mail already registered');
                window.location.href='index.php';
                </script>
                ";
            }
        }
        else{
            $password=password_hash($_POST['password'],PASSWORD_BCRYPT);
            $v_code=bin2hex(random_bytes(16));
            $query="INSERT INTO `registered_users`(`full_name`, `username`, `email`, `password`, `verification_code`, `is_verified`) VALUES ('$_POST[fullname]','$_POST[username]','$_POST[email]','$password','$v_code','0')";
            if(mysqli_query($con,$query) && sendMail($_POST['email'],$v_code)){
                echo"
                <script>
                alert('Registration Successfull');
                window.location.href='index.php';
                </script>
                ";
            }
            else{
                echo"
                <script>
                alert('Server Down');
                window.location.href='index.php';
                </script>
                ";
            }
        }
    }
    else{
        echo"
        <script>
        alert('can not run query');
        window.location.href='index.php';
        </script>
        ";
    }
}



?>