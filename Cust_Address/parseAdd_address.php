<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';
include_once '../resource/utilities.php';

// Check if form is submitted
if (isset($_POST['addAddressBtn'])) {
    // Initialize an array to store error messages
    $form_errors = array();

    // Collect form data
    $line1 = htmlspecialchars($_POST['line1']);
    $line2 = htmlspecialchars($_POST['line2']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $postal_code = htmlspecialchars($_POST['postal_code']);
    $country = htmlspecialchars($_POST['country']);
    $cust_id = $_SESSION['Cust_ID'];

    // Required fields
    $required_fields = array('line1', 'city', 'state', 'postal_code', 'country');

    // Check for empty fields
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    // If no errors, proceed with database insertion
    if (empty($form_errors)) {
        try {
            // SQL query to insert new address
            $sqlInsert = "INSERT INTO address (Cust_ID, line1, line2, city, state, postal_code, country) 
                          VALUES (:cust_id, :line1, :line2, :city, :state, :postal_code, :country)";

            $statement = $db->prepare($sqlInsert);
            $statement->execute(array(':cust_id' => $cust_id, ':line1' => $line1, ':line2' => $line2, ':city' => $city, ':state' => $state, ':postal_code' => $postal_code, ':country' => $country));

            // If insertion is successful
            if ($statement->rowCount() === 1) {
                $result = "<p style='color: green;'>Address added successfully</p>";
            }
        } catch (PDOException $ex) {
            $result = "<p style='color: red;'>An error occurred: " . $ex->getMessage() . "</p>";
        }
    } else {
        // Display errors
        if (count($form_errors) == 1) {
            $result = "<p style='color: red;'>There was 1 error in the form<br></p>";
        } else {
            $result = "<p style='color: red;'>There were " . count($form_errors) . " errors in the form<br></p>";
        }
    }
}
