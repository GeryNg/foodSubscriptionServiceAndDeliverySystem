<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';
include_once '../resource/utilities.php';

if (isset($_POST['passwordResetBtn'])) {
    $form_errors = array();

    // Required fields
    $required_fields = array('email', 'securityQuestion1', 'securityAnswer1', 'securityQuestion2', 'securityAnswer2', 'new_password', 'confirm_password');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    // Fields to check length
    $fields_to_check_length = array('new_password' => 8, 'confirm_password' => 8);
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    // Email validation
    $form_errors = array_merge($form_errors, check_email($_POST));

    if (empty($form_errors)) {
        // Collect form data and store in variables
        $email = $_POST['email'];
        $securityQuestion1Id = filter_var($_POST['securityQuestion1'], FILTER_SANITIZE_NUMBER_INT);
        $securityAnswer1 = filter_var($_POST['securityAnswer1']);
        $securityQuestion2Id = filter_var($_POST['securityQuestion2'], FILTER_SANITIZE_NUMBER_INT);
        $securityAnswer2 = filter_var($_POST['securityAnswer2']);
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];

        $securityQuestions = array(
            "1" => "What was your first pet's name?",
            "2" => "What is your mother's maiden name?",
            "3" => "What was the name of your first school?",
            "4" => "What is your favorite food?",
            "5" => "What city were you born in?"
        );
        $securityQuestion1Text = $securityQuestions[$securityQuestion1Id];
        $securityQuestion2Text = $securityQuestions[$securityQuestion2Id];

        if ($password1 != $password2) {
            $result = "<p style='padding: 20px; color: #58151c;'>New Password and Confirm Password do not match</p>";
        } else {
            try {
                $sqlQuery = "SELECT id, security_question1, security_answer1, security_question2, security_answer2 FROM users WHERE email = :email";
                $statement = $db->prepare($sqlQuery);
                $statement->execute(array(':email' => $email));

                if ($statement->rowCount() == 1) {
                    $user = $statement->fetch(PDO::FETCH_ASSOC);

                    if (
                        ($user['security_question1'] == $securityQuestion1Text && $securityAnswer1 == $user['security_answer1'] &&
                         $user['security_question2'] == $securityQuestion2Text && $securityAnswer2 == $user['security_answer2']) ||
                        ($user['security_question1'] == $securityQuestion2Text && $securityAnswer2 == $user['security_answer1'] &&
                         $user['security_question2'] == $securityQuestion1Text && $securityAnswer1 == $user['security_answer2'])
                    ) {
                        $hashed_password = password_hash($password1, PASSWORD_DEFAULT);
                        $sqlUpdate = "UPDATE users SET password = :password WHERE email = :email";
                        $statement = $db->prepare($sqlUpdate);
                        $statement->execute(array(':password' => $hashed_password, ':email' => $email));

                        echo "<script>
                                swal({
                                title: \"Password Reset Successfully!\",
                                text: \"Your password has been reset successfully\",
                                icon: 'success',
                                button: \"Go To Homepage\",
                                });
                                setTimeout(function(){
                                window.location.href = '../index.php';
                                }, 3000);
                                </script>";
                    } else {
                        $result = "<p style='padding: 10px; color: #58151c;'>Security questions and answers do not match</p>";
                    }
                } else {
                    $result = "<p style='padding: 10px; color: #58151c;'>The email address provided does not exist in our database, please try again</p>";
                }
            } catch (PDOException $ex) {
                $result = "<p style='padding: 10px; color: #58151c;'>An error occurred: " . $ex->getMessage() . "</p>";
            }
        }
    }
}
