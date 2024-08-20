<?php
$page_title = "User Authentication - Password Recovery";
include_once '../resource/Database.php';
include_once '../resource/session.php';
include_once '../partials/headers.php';

if (!isset($db)) {
    die("Database connection not established.");
}

$email = $_POST["emailField"];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$sql = "UPDATE users
        SET reset_token_hash = ?, reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $db->prepare($sql);

if ($stmt === false) {
    die("Error preparing the statement.");
}

$stmt->execute([$token_hash, $expiry, $email]);

if ($stmt->rowCount() > 0) {
    $mail = require __DIR__ .  "/mailer.php";

    $mail->setFrom("makanapa024@gmail.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
    <p>Click <a href="http://localhost:3000/login_management/reset_password.php?token=$token">here</a> to reset your password.</p>
    END;

    try {
        $mail->send();
        echo "<script>
            swal({
                title: 'Success',
                text: 'Message sent, please check your inbox. Maybe in your spam folder',
                icon: 'success',
                button: 'OK'
            }).then(function() {
                window.location.href = '../login_management/login.php';
            });
        </script>";
    } catch (Exception $e) {
        echo "<script>
        swal({
            title: 'Error',
            text: 'Message could not be sent. Mailer error: {$mail->ErrorInfo}',
            type: 'error',
            confirmButtonText: 'OK'
        });
            setTimeout(function(){
            window.location.href = '../login_management/login.php';
            }, 3000);
        </script>";
    }
} else {
    echo "<script>
    swal({
        title: 'Error',
        text: 'No matching email found, or token not set.',
        type: 'error',
        confirmButtonText: 'OK'
    });
        setTimeout(function(){
        window.location.href = '../login_management/login.php';
        }, 3000);
    </script>";
}
