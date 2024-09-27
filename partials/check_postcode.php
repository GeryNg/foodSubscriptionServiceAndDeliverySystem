<?php
include_once '../resource/Database.php';

if (isset($_POST['postcode'])) {
    $postcode = htmlspecialchars($_POST['postcode']);

    // Query to check if postcode exists in the address_book table
    $stmt = $db->prepare("SELECT * FROM address_book WHERE postcode = ?");
    $stmt->execute([$postcode]);

    if ($stmt->rowCount() > 0) {
        // Postcode exists
        echo json_encode(['success' => true]);
    } else {
        // Postcode does not exist
        echo json_encode(['success' => false, 'message' => 'Postcode not supported']);
    }
}
