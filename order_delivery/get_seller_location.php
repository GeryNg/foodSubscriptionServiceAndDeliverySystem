<?php
include_once '../resource/Database.php';

$cust_id = $_GET['cust_id'];

$query = $db->prepare("SELECT latitude, longitude FROM delivery WHERE cust_id = :cust_id AND status IN ('on delivery', 'food preparing') LIMIT 1");
$query->bindParam(':cust_id', $cust_id);
$query->execute();

$location = $query->fetch(PDO::FETCH_ASSOC);

if ($location) {
    echo json_encode([
        'latitude' => $location['latitude'],
        'longitude' => $location['longitude']
    ]);
} else {
    echo json_encode([
        'error' => 'Location not found'
    ]);
}
?>
