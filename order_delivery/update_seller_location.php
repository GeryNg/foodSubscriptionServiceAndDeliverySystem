<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'];
$fingerprint = $_SESSION['fingerprint'];

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

$latitude = $data['latitude'] ?? null;
$longitude = $data['longitude'] ?? null;
$status = $data['status'] ?? null;

if ($status && $seller_id && $fingerprint) {
    try {
        // Check if the seller has a location record
        $query = "SELECT * FROM seller_location WHERE seller_id = :seller_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':seller_id', $seller_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Seller exists, update location, status, and fingerprint
            $query = "
                UPDATE seller_location 
                SET latitude = :latitude, longitude = :longitude, fingerprint = :fingerprint, status = :status, timestamp = CURRENT_TIMESTAMP
                WHERE seller_id = :seller_id
            ";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':latitude', $latitude);
            $stmt->bindParam(':longitude', $longitude);
            $stmt->bindParam(':fingerprint', $fingerprint);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':seller_id', $seller_id);

            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo 'error: Failed to update location and status.';
            }
        } else {
            // Seller does not exist, create a new record
            $query = "
                INSERT INTO seller_location (seller_id, latitude, longitude, fingerprint, status, timestamp)
                VALUES (:seller_id, :latitude, :longitude, :fingerprint, :status, CURRENT_TIMESTAMP)
            ";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':seller_id', $seller_id);
            $stmt->bindParam(':latitude', $latitude);
            $stmt->bindParam(':longitude', $longitude);
            $stmt->bindParam(':fingerprint', $fingerprint);
            $stmt->bindParam(':status', $status);

            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo 'error: Failed to create new location record.';
            }
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
} else {
    echo 'error: Missing required data (status, seller_id, or fingerprint).';
}
