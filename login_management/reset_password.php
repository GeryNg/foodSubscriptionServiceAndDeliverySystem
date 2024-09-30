<?php
$page_title = "User Authentication - Password Reset";
include_once '../partials/headers.php';
include_once "../resource/Database.php";
include_once "../resource/session.php";
include_once "../resource/utilities.php";

// Check token
$token = $_GET["token"];
$token_hash = hash("sha256", $token);

$sql = "SELECT * FROM users WHERE reset_token_hash = :token_hash";
$stmt = $db->prepare($sql);
$stmt->bindParam(':token_hash', $token_hash);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false) {
    die("Token not found");
}
if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}
?>

<?php
if (isset($_POST['passwordResetBtn'])) {
    $form_errors = array();

    // Required fields
    $required_fields = array('new_password', 'confirm_password');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    // Fields to check length
    $fields_to_check_length = array('new_password' => 8, 'confirm_password' => 8);
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    if (empty($form_errors)) {
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];

        if ($password1 != $password2) {
            $result = "<p style='padding: 20px; color: #58151c;'>New Password and Confirm Password do not match</p>";
        } else {
            try {
                $hashed_password = password_hash($password1, PASSWORD_DEFAULT);
                $sqlUpdate = "UPDATE users SET password = :password WHERE reset_token_hash = :token_hash";
                $statement = $db->prepare($sqlUpdate);
                $statement->execute(array(':password' => $hashed_password, ':token_hash' => $token_hash));

                echo "<script>
                swal({
                    title: 'Password Reset Successfully!',
                    text: 'Your password has been reset successfully',
                    type: 'success',
                    confirmButtonText: 'Go To Homepage'
                }, function() {
                    window.location.href = '../index.php';
                });
                </script>";
        
            } catch (PDOException $ex) {
                $result = "<p style='padding: 10px; color: #58151c;'>An error occurred: " . $ex->getMessage() . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE HTML>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        .form-signin {
            max-width: 500px;
            padding: 3rem 5rem 3rem 5rem;
            margin: auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .form-floating > .form-control, .form-floating > .form-control-plaintext {
            margin: 5px;
        }
        .form-floating > .form-select {
            margin: 5px;
            height: calc(3.5rem + 2px);
        }
        p {
            margin-bottom: 5px !important;
        }
    </style>
</head>

<body>
    <main class="form-signin">
        <form method="post" action="">
            <img class="mb-4" src="../image/logo-rounded.png" alt="Logo" width="80" height="80">
            <h1 class="h3 mb-3 fw-normal">Reset Password Form</h1>
            <?php if (isset($result) || !empty($form_errors)) : ?>
                <div>
                    <?php echo show_combined_messages($result, $form_errors); ?>
                </div>
            <?php endif; ?>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" name="new_password" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPasswordConfirm" name="confirm_password" placeholder="Confirm Password" required>
                <label for="floatingPasswordConfirm">Confirm Password</label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit" name="passwordResetBtn" value="Reset Password">Reset Password</button>
            <p class="mt-5 mb-3 text-body-secondary">© 2024-2024</p>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
</body>
</html>

<?php include_once '../partials/footer.php';?>
