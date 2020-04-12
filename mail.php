<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $to = 'connect@gbitstudios.com';
        $name = $_POST["name"];
        $email = $_POST["email"];
        $subject = $_POST["subject"];
        $text = $_POST["message"];
        
        $secretKey = '6Ldwbb8UAAAAAI2GQJSVKXFXvWQtySJblPd2SMSb';
        $captcha = $_POST['g-recaptcha-response'];

        if(!$captcha){
              echo '<p class="alert alert-warning">Please check the captcha form.</p>';
              exit;
        }
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
        $responseKeys = json_decode($response,true);

        if(intval($responseKeys["success"]) !== 1) {
              echo '<p class="alert alert-warning">Please check the captcha form.</p>';
        } else {

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= "From: " . $email . "\r\n"; // Sender's E-mail
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $message ='<table style="width:100%">
                <tr><td>Name: '.$name.'</td></tr>
                <tr><td>Email: '.$email.'</td></tr>
                <tr><td>Subject: '.$subject.'</td></tr>
                <tr><td>Message: '.$text.'</td></tr>
                
            </table>';

            if (@mail($to, $email, $message, $headers))
            {
                # Set a 200 (okay) response code.
                http_response_code(200);
                echo '<p class="alert alert-success">The message has been sent.</p>';
            }else{
                # Set a 500 (internal server error) response code.
                http_response_code(500);
                echo '<p class="alert alert-danger">The message could not be sent.</p>';
            }
        }
    } else {
        # Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo '<p class="alert alert-warning">There was a problem with your submission, please try again.</p>';
    }
?>