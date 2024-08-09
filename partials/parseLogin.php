<?php
use MongoDB\BSON\Javascript;
include_once '../resource/session.php';
include_once '../resource/Database.php';
include_once '../resource/utilities.php';

if (isset($_POST['loginBtn'])) {
    $form_errors = array();

    //validate
    $required_fields = array('username', 'password');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    if (empty($form_errors)) {
        //collect form data
        $user = $_POST['username'];
        $password = $_POST['password'];

        isset($_POST['remember']) ? $remember = $_POST['remember'] : $remember = "";

        $sqlQuery = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':username' => $user));

        if ($row = $statement->fetch()) {
            $id = $row['id'];
            $hashed_password = $row['password'];
            $username = $row['username'];
            $role = $row['role'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['Status'] = 'Online';
                
                $sqlUpdate = "UPDATE users SET Status = 'Online' WHERE id = :id";
                $statement = $db->prepare($sqlUpdate);
                $statement->execute(array(':id' => $id));

                //guard
                $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
                $_SESSION['last_active'] = time();
                $_SESSION['fingerprint'] = $fingerprint;

                //remember me
                if ($remember === 'yes') {
                    rememberMe($id);
                }

                if ($role === 'seller') {
                    $sqlQuerySeller = "SELECT id AS seller_id, access FROM seller WHERE user_id = :user_id";
                    $statementSeller = $db->prepare($sqlQuerySeller);
                    $statementSeller->execute(array(':user_id' => $id));
                    $sellerRow = $statementSeller->fetch();

                    if ($sellerRow) {
                        $_SESSION['access'] = $sellerRow['access'];
                        $_SESSION['seller_id'] = $sellerRow['seller_id'];
                    }
                }

                // Determine redirect based on role
                if ($role === 'customer') {
                    $redirect_url = '../index.php';
                } elseif ($role === 'seller') {
                    $redirect_url = '../partials/seller_dashboard.php';
                } elseif ($role === 'admin') {
                    $redirect_url = '../admin/admin_dashboard.php';
                }else {
                    $redirect_url = '../index.php';
                }

                echo "<script>
                swal({
                  title: \"Welcome back, $username!\",
                  text: \"You're being logged in.\",
                  icon: 'success',
                  timer: 3000,
                  button: false,
                });
                setTimeout(function(){
                window.location.href = '$redirect_url';
                }, 3000);
                </script>";

            } else {
                $result = flashMessage("You have entered an invalid password");
            }
        } else {
            $result = flashMessage("You have entered an invalid username");
        }
    } else {
        if (count($form_errors) == 1) {
            $result = flashMessage("There was 1 error in the form<br>");
        } else {
            $result = flashMessage("There were " . count($form_errors) . " errors in the form <br>");
        }
    }
}

?>
