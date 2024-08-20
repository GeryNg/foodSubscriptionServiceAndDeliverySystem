<?php
include_once '../resource/Database.php';
include_once '../resource/utilities.php';

if ((isset($_SESSION['id']) || isset($_GET['user_identify'])) && !isset($_POST['updateProfileBtn'])) {
    if (isset($_GET['user_identify'])) {
        $url_encode_id = $_GET['user_identify'];
        $decode_id = base64_decode($url_encode_id);
        $user_id_array = explode("encodeuserid", $decode_id);
        $user_id = $user_id_array[1];
    } else {
        $id = $_SESSION['id'];
    }

    $sqlQuery = "SELECT * FROM users WHERE id = :id";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array(':id' => $id));

    while ($rs = $statement->fetch()) {
        $username = $rs['username'];
        $email = $rs['email'];
        $date_joined = (new DateTime($rs["join_date"]))->format('M d, Y');
    }

    $user_pic = "../uploads/" . $username . ".jpg";
    $default = "../uploads/default.jpg";

    if (file_exists($user_pic)) {
        $profile_picture = $user_pic;
    } else {
        $profile_picture = $default;
    }

    $encode_id = base64_encode("encodeuserid{$id}");
} else if (isset($_POST['updateProfileBtn'])) {
    $form_errors = array();

    // Form validation
    $required_fields = array('email', 'username');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    $fields_to_check_length = array('username' => 4);
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    $form_errors = array_merge($form_errors, check_email($_POST));

    // Validate and process image upload
    $avatar = isset($_FILES['avatar']['name']) ? $_FILES['avatar']['name'] : null;
    if ($avatar != null) {
        $allowedFormats = ['jpg', 'jpeg', 'png'];
        $fileTmpName = $_FILES['avatar']['tmp_name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileError = $_FILES['avatar']['error'];
        $fileExtension = strtolower(pathinfo($avatar, PATHINFO_EXTENSION));

        if ($fileError === 0) {
            if (in_array($fileExtension, $allowedFormats)) {
                if ($fileSize <= 2 * 1024 * 1024) { // Check for file size (e.g., 2MB limit)
                    $newFileName = uniqid('', true) . "." . $fileExtension;
                    $targetDir = "../uploads/";
                    $targetPath = $targetDir . $newFileName;

                    if (move_uploaded_file($fileTmpName, $targetPath)) {
                        $imageUrl = $targetPath;
                    } else {
                        $form_errors[] = "Failed to move uploaded file: $avatar";
                    }
                } else {
                    $form_errors[] = "File size exceeds the limit: $avatar";
                }
            } else {
                $form_errors[] = "Invalid file format for: $avatar";
            }
        } else {
            $form_errors[] = "Error uploading file $avatar: " . uploadErrorToString($fileError);
        }
    }

    $email = $_POST['email'];
    $username = $_POST['username'];
    $hiddenid = $_POST['hidden_id'];

    if (empty($form_errors)) {
        try {
            $sqlUpdate = "UPDATE users SET username = :username, email = :email";
            if (isset($imageUrl)) {
                $sqlUpdate .= ", avatar = :avatar";
            }
            $sqlUpdate .= " WHERE id = :id";

            $statement = $db->prepare($sqlUpdate);
            $params = array(':username' => $username, ':email' => $email, ':id' => $hiddenid);
            if (isset($imageUrl)) {
                $params[':avatar'] = $imageUrl;
            }
            $statement->execute($params);

            if ($statement->rowCount() == 1 || uploadAvatar($username)) {
                echo "<script type=\"text/javascript\">
                    swal({
                        title: \"Good job!\",
                        text: \"Profile updated successfully!\",
                        icon: \"success\",
                        button: \"OK\"
                    }).then(function() {
                        window.location.href = 'profile.php';
                    });
                </script>";
            } else {
                echo "<script type=\"text/javascript\">
                    swal({
                        title: \"Nothing Happened\",
                        text: \"You have not made any changes\",
                        icon: \"info\",
                        button: \"OK\"
                    }).then(function() {
                        window.location.href = 'profile.php';
                    });
                </script>";
            }
        } catch (PDOException $ex) {
            $result = flashMessage("An error occurred: " . $ex->getMessage());
        }
    } else {
        if (count($form_errors) == 1) {
            $result = flashMessage("There was 1 error in the form<br>");
        } else {
            $result = flashMessage("There were " . count($form_errors) . " errors in the form <br>");
        }
        $result .= show_errors($form_errors);
    }
}
