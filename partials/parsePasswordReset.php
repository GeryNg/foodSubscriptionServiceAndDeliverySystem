<?php
include_once 'resource/Database.php';
include_once 'resource/session.php';
include_once 'resource/utilities.php';
include_once 'resource/send_email.php';

if (isset($_POST['passwordResetBtn'])) {
    $form_errors = array();

    //check validate
    $required_fields = array( 'new_password', 'confirm_password');

    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    $fields_to_check_length = array('new_password' => 8, 'confirm_password' => 8);

    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));


    if (empty($form_errors)) {
        //collect form data and store in variables
        $id = $_POST['user_id'];
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];

        if ($password1 != $password2) {
            $result = "<p style='padding: 20px; color: #58151c;'>New Password and confirm password does not match</p>";
        } else {
            try {
                $sqlQuery = "SELECT id FROM users WHERE id =:id ";
                $statement = $db->prepare($sqlQuery);
                $statement->execute(array(':id' => $id));
                if ($statement->rowCount() == 1) {
                    $hashed_password = password_hash($password1, PASSWORD_DEFAULT);
                    $sqlUpdate = "UPDATE users SET password =:password WHERE id=:id";
                    $statement = $db->prepare($sqlUpdate);
                    $statement->execute(array(':password' => $hashed_password, ':id' => $id));
                    echo "<script>
                    swal({
                      title: \"Password Reset Successfully!\",
                      text: \"Your password had reset successfully\",
                      icon: 'success',
                      button: \"Go To Login\",
                    });
                    setTimeout(function(){
                    window.location.href = 'login.php';
                    }, 10000);
                    </script>";
                    //$result = "<p style='padding: 20px; border: 1px solid gray; color: green;'>Password Reset Success</p>";
                } else {
                    $result = "<p style='padding: 10px; color: #58151c;'>The email address provided does not exist in our database, please try again</p>";
                }
            } catch (PDOEXception $ex) {
                $result = "<p style='padding: 10px; color: #58151c;'>An error occured: " . $ex->getMessage() . "</p>";
            }
        }

    } else {
        if (count($form_errors) == 1) {
            $result = flashMessage("There was 1 error in the form<br>");
        } else {
            $result = flashMessage("There were " . count($form_errors) . " error in the form <br>");
        }
    }
} else if (isset($_POST['passwordRecoveryBtn'])){

    $form_errors = array();

    $required_fields = array('email');

    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    $form_errors = array_merge($form_errors, check_email($_POST));

    if (empty($form_errors)) {
        $email - $_POST['email'];

        try {
            $sqlQuery = "SELECT * FORM users WHERE email =:email";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(":email" => $email));

            if ($rs = $statement->fetch) {
                $usernmae = $rs['username'];
                $email = $rs['email'];
                $user_id = $rs['id'];
                $encode_id = base64_encode("encodeuserid{$user_id}");

                //prepare email body
                $mail_body = '<html>
                        <body style="background-color:#CCCCCC; color:#000; font-family: Arial, Helvetica, sans-serif;
                                            line-height:1.8em;">
                        <h2>User Authentication: Code A Secured Login System</h2>
                        <p>Dear ' . $username . '<br><br> To reset your login password, copy the token below and 
                        click on the Reset Password link then paste the token in the token field on the form:
                        <br /><br />
                        Token: ' . $reset_token . ' <br />
                        This token will expire after 1 hour
                        </p>
                        <p><a href="http://auth.dev/forgot_password.php"> Reset Password</a></p>
                        <p><strong>&copy;' . date('Y') . ' DEVSCREENCAST</strong></p>
                        </body>
                        </html>';
                $mail->addAddress($email, $username);
                $mail->Subject = "Password Recovery Message from DEVSCREENCAST";
                $mail->Body = $mail_body;

                //Error Handling for PHPMailer
                if (!$mail->Send()) {
                    $result = "<script type=\"text/javascript\">
                         swal(\"Error\",\" Email sending failed: $mail->ErrorInfo \",\"error\");</script>";
                } else {
                    $result = "<script type=\"text/javascript\">
                            swal({
                            title: \"Password Recovery!\",
                            text: \"Password Reset link sent successfully, please check your email address.\",
                            type: 'success',
                            confirmButtonText: \"Thank You!\" });
                        </script>";
                }
            } else {
                $result = "<script type=\"text/javascript\">
                            swal({
                            title: \"OOPS!!\",
                            text: \"The email address provided does not exist in our database, please try again.\",
                            type: 'error',
                            confirmButtonText: \"Ok!\" });
                        </script>";
            }
        } catch (PDOException $ex) {
            $result = flashMessage("An error occurred: " . $ex->getMessage());
        }
    } else{
            if(count($form_errors) == 1){
                $result = flashMessage("There was 1 error in the form<br>");
            }else{
                $result = flashMessage("There were " .count($form_errors). " errors in the form <br>");
            }
        }

}
?>
