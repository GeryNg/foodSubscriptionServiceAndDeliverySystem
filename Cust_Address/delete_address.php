<?php
include '../resource/Database.php';
include '../resource/session.php';

if (isset($_GET['address_id']) && is_numeric($_GET['address_id'])) {
    $address_id = intval($_GET['address_id']);

    // Prepare the SQL query to delete the address
    $sql = "DELETE FROM address WHERE address_id = :address_id";
    $statement = $db->prepare($sql);
    $statement->bindParam(':address_id', $address_id, PDO::PARAM_INT);

    // Execute the query and check for success
    if ($statement->execute()) {
        $_SESSION['message'] = "Address deleted successfully!";
        header("Location: address_management.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to delete address.";
        header("Location: address_management.php");
        exit();
    }
} else {
    // If address_id is not set or not valid, show an error
    $_SESSION['error'] = "Invalid address ID.";
    header("Location: address_management.php");
    exit();
}
?>
