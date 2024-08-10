<?php
include '../resource/Database.php';
include '../resource/session.php';

if (isset($_GET['address_id'])) {
    $address_id = intval($_GET['address_id']);
    
    $sql = "DELETE FROM address WHERE address_id = :address_id";
    $statement = $db->prepare($sql);
    $statement->bindParam(':address_id', $address_id, PDO::PARAM_INT);

    if ($statement->execute()) {
        $_SESSION['message'] = "Address deleted successfully!";
        header("Location: address_management.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to delete address.";
        header("Location: address_management.php");
        exit();
    }
}
    else {
        echo"hello world";
    }
