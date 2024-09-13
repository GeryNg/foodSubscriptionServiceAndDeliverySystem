<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';

if (isset($_GET['seller_id'])) {
    $seller_id = $_GET['seller_id'];

    $sqlQuery = "SELECT access FROM seller WHERE id = :seller_id";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array(':seller_id' => $seller_id));

    if ($statement->rowCount() == 1) {
        $seller = $statement->fetch(PDO::FETCH_ASSOC);

        $_SESSION['seller_id'] = $seller_id;
        $_SESSION['access'] = $seller['access'];

        header("Location: seller_profile.php");
        exit();
    } else {
        echo "Invalid seller ID.";
    }
} else {
    echo "No seller ID provided.";
}