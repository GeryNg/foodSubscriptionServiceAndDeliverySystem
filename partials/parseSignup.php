<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';
include_once '../resource/utilities.php';

function generateUserId($db) {
    $query = "SELECT MAX(id) AS max_id FROM users";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxId = $row['max_id'];

    $newId = $maxId ? intval(substr($maxId, 1)) + 1 : 1;

    return 'U' . str_pad($newId, 5, '0', STR_PAD_LEFT);
}

function generateCustomerId($db) {
    $query = "SELECT MAX(Cust_ID) AS max_id FROM customer"; // Updated to use Cust_ID
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxId = $row['max_id'];

    $newId = $maxId ? intval(substr($maxId, 1)) + 1 : 1;

    return 'C' . str_pad($newId, 5, '0', STR_PAD_LEFT);
}

if (isset($_POST['signupBtn'])) {
    $form_errors = array();

    // Required fields
    $required_fields = array('email', 'username', 'password1', 'password2', 'name', 'gender', 'phoneNumber', 'securityQuestion1', 'securityAnswer1', 'securityQuestion2', 'securityAnswer2');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    // Fields to check length
    $fields_to_check_length = array('username' => 4, 'password1' => 8, 'password2' => 8);
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    // Email validation
    $form_errors = array_merge($form_errors, check_email($_POST));

    // Collect form data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = filter_var($_POST['username']);
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $name = filter_var($_POST['name']);
    $gender = filter_var($_POST['gender']);
    $phoneNumber = filter_var($_POST['phoneNumber']);
    $securityQuestion1 = filter_var($_POST['securityQuestion1']);
    $securityAnswer1 = filter_var($_POST['securityAnswer1']);
    $securityQuestion2 = filter_var($_POST['securityQuestion2']);
    $securityAnswer2 = filter_var($_POST['securityAnswer2']);
    $securityQuestions = array(
        "1" => "What was your first pet's name?",
        "2" => "What is your mother's maiden name?",
        "3" => "What was the name of your first school?",
        "4" => "What is your favorite food?",
        "5" => "What city were you born in?"
    );

    // Check for duplicate entries
    if ($password1 != $password2) {
        $result = "<p style='padding: 8px; color: #58151c;'>Password and Confirm Password do not match</p>";
    } elseif (checkDuplicateEntries("users", "email", $email, $db)) {
        $result = flashMessage("Email is already taken, please try another one");
    } elseif (checkDuplicateEntries("users", "username", $username, $db)) {
        $result = flashMessage("Username is already taken, please try another one");
    } elseif (empty($form_errors)) {
        // Hashing the password
        $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

        // Generate new user ID
        $newUserId = generateUserId($db);

        try {
            // Insert user into the database
            $sqlInsertUser = "INSERT INTO users (id, username, email, password, role, join_date, security_question1, security_answer1, security_question2, security_answer2) 
                              VALUES (:id, :username, :email, :password, :role, now(), :security_question1, :security_answer1, :security_question2, :security_answer2)";
            $statement = $db->prepare($sqlInsertUser);
            $statement->execute(
                array(
                    ':id' => $newUserId,
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $hashed_password,
                    ':role' => 'customer',
                    ':security_question1' => $securityQuestions[$securityQuestion1],
                    ':security_answer1' => $securityAnswer1,
                    ':security_question2' => $securityQuestions[$securityQuestion2],
                    ':security_answer2' => $securityAnswer2
                )
            );

            if ($statement->rowCount() == 1) {
                // Insert customer with generated customer ID
                $newCustomerId = generateCustomerId($db);
                $sqlInsertCustomer = "INSERT INTO customer (Cust_ID, user_id, Name, Gender, Phone_num) 
                                      VALUES (:Cust_ID, :user_id, :name, :gender, :phone_num)";
                $statementCustomer = $db->prepare($sqlInsertCustomer);
                $statementCustomer->execute(array(
                    ':Cust_ID' => $newCustomerId,
                    ':user_id' => $newUserId,
                    ':name' => $name,
                    ':gender' => $gender,
                    ':phone_num' => $phoneNumber
                ));

                echo "<script>
                swal({
                  title: \"Registration Successful!\",
                  text: \"Your account has been created\",
                  icon: 'success',
                  button: \"Go To Login\",
                });
                setTimeout(function(){
                window.location.href = '../login_management/login.php';
                }, 3000);
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
    }
}
?>
