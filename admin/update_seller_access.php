<?php
include_once '../resource/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seller_id = $_POST['seller_id'];
    $access = $_POST['AcceptBtn'] ?? $_POST['RejectBtn'];

    $query = "UPDATE seller SET access = :access WHERE id = :seller_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':access', $access, PDO::PARAM_STR);
    $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: assign.php?status=success");
    } else {
        echo "Failed to update seller status.";
    }
} else {
    echo "Invalid request.";
}
?>
