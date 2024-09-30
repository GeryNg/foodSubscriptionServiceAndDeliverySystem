<?php
include '../resource/Database.php';
include '../resource/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address_id'])) {
    $selected_address_id = $_POST['address_id'];
    $_SESSION['selected_address_id'] = $selected_address_id;

    header("Location: ../Restaurant/restaurants.php");
    exit();
} else {
    echo "<script>alert('Failed to change address. Please try again.'); window.location.href = '../Restaurant/restaurant.php';</script>";
}
?>
