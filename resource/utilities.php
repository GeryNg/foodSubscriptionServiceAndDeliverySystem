<?php
use Ds\Set;
use MongoDB\Driver\Session;
function check_empty_fields($required_fields_array)
{
    $form_errors = array();

    foreach ($required_fields_array as $name_of_field) {
        if (!isset($_POST[$name_of_field]) || trim($_POST[$name_of_field]) == '') {
            $form_errors[] = $name_of_field . " is a required field";
        }
    }
    return $form_errors;
}

function check_min_length($field_to_check_length)
{
    $form_errors = array();

    foreach ($field_to_check_length as $name_of_field => $minimum_length_required) {
        if (strlen(trim($_POST[$name_of_field])) < $minimum_length_required) {
            $form_errors[] = $name_of_field . " is too short, must be at least {$minimum_length_required} characters long";
        }
    }
    return $form_errors;
}

function check_email($data)
{
    $form_errors = array();
    $key = 'email';

    if (array_key_exists($key, $data)) {
        if (!empty($_POST[$key])) {
            $email = filter_var($_POST[$key], FILTER_SANITIZE_EMAIL);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $form_errors[] = $_POST[$key] . ' is not a valid email address.';
            }
        }
    }
    return $form_errors;
}

function check_numeric($field_to_check_numeric)
{
    $form_errors = array();

    foreach ($field_to_check_numeric as $name_of_field) {
        if (!is_numeric($_POST[$name_of_field])) {
            $form_errors[] = $name_of_field . " must be a number.";
        } elseif ($_POST[$name_of_field] <= 0) {
            $form_errors[] = $name_of_field . " must be a positive number.";
        }
    }
    return $form_errors;
}

function show_errors($form_errors_array)
{
    $errors = "<ul>";

    foreach ($form_errors_array as $the_error) {
        $errors .= "<li>{$the_error}</li>";
    }
    $errors .= "</ul>";
    return $errors;
}

function flashMessage($message, $type = "error")
{
    $class = ($type === "success") ? "alert-success" : "alert-danger";
    return "<div class=' {$class}'>{$message}</div>";
}

function show_combined_messages($flashMessage, $form_errors_array)
{
    if (empty($flashMessage) && empty($form_errors_array)) {
        return "";
    }

    $messages = "<div class='alert alert-danger'>";
    if ($flashMessage) {
        $messages .= $flashMessage;
    }
    if (!empty($form_errors_array)) {
        $messages .= show_errors($form_errors_array);
    }
    $messages .= "</div>";
    return $messages;
}

function checkDuplicateEntries($table, $column_name, $value, $db)
{
    try {
        $sqlQuery = "SELECT * FROM " . $table . " WHERE " . $column_name . " = :" . $column_name;
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':' . $column_name => $value));

        if ($row = $statement->fetch()) {
            return true;
        }
        return false;
    } catch (PDOException $ex) {
        return false;
    }
}

function rememberMe($user_id)
{
    $encryptCookieData = base64_encode("UaQteh5i4y3dntstemYODEC{$user_id}");
    // Make sure there is no output before this point
    setcookie('rememberUserCookie', $encryptCookieData, time() + 60 * 60 * 24 * 5, "/");
}

function isCookieValid($db)
{
    $isvalid = false;

    if (isset($_COOKIE["rememberUserCookie"])) {
        $decryptCookieData = base64_decode($_COOKIE['rememberUserCookie']);
        $user_id = explode("UaQteh5i4y3dntstemYODEC", $decryptCookieData);
        $userID = $user_id[1];

        $sqlQuery = "SELECT * FROM users WHERE id = :id";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(":id" => $userID));

        if ($row = $statement->fetch()) {
            $id = $row['id'];
            $username = $row['username'];
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            $isvalid = true;
        } else {
            $isvalid = false;
            signout();
        }
    }
    return $isvalid;
}

function signout()
{
    unset($_SESSION['username']);
    unset($_SESSION['id']);

    if (isset($_COOKIE['rememberUserCookie'])) {
        unset($_COOKIE['rememberUserCookie']);
        setcookie('rememberUserCookie', null, -1, '/');
    }
    session_destroy();
    session_regenerate_id(true);
    header("Location: ../index.php");
    exit();
}

// Guard from users using many browsers to run the website
function guard()
{
    $isValid = true;
    $inactive = 60 * 10; // 10 mins
    $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

    if (isset($_SESSION['fingerprint']) && $_SESSION['fingerprint'] != $fingerprint) {
        $isValid = false;
        signout();
    } elseif (isset($_SESSION['last_active']) && (time() - $_SESSION['last_active']) > $inactive && $_SESSION['username']) {
        $isValid = false;
        signout();
    } else {
        $_SESSION['last_active'] = time();
        $_SESSION['fingerprint'] = $fingerprint;
    }
    return $isValid;
}

function isValidImage($file)
{
    $form_errors = array();

    // Split file name into an array using the dot (.)
    $part = explode(".", $file);

    // Target the last element in the array
    $extension = end($part);

    // Validate allowed image extensions
    switch (strtolower($extension)) {
        case 'jpg':
        case 'gif':
        case 'bmp':
        case 'png':
            return $form_errors;
    }

    $form_errors[] = $extension . " is not a valid image extension";
    return $form_errors;
}

function uploadAvatar($username)
{
    $isImagemoved = false;

    if ($_FILES['avatar']['tmp_name']) {
        // File in the temp location
        $temp_file = $_FILES['avatar']['tmp_name'];
        $ds = DIRECTORY_SEPARATOR;
        $avatar_name = $username . ".jpg";

        $path = "uploads" . $ds . $avatar_name; // upload/demo.jpg

        if (move_uploaded_file($temp_file, $path)) {
            $isImagemoved = true;
        }
    }
    return $isImagemoved;
}

?>