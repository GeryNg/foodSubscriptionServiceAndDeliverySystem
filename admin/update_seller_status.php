<?php
include_once '../resource/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_POST['seller_id'];
    $access = $_POST['access'];

    // Update the status in the database
    $query = "UPDATE seller SET access = :access WHERE id = :seller_id";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':access' => $access,
        ':seller_id' => $seller_id
    ]);

    echo 'success';
}
?>