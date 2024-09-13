<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'];
$fingerprint = $_SESSION['fingerprint']; // Get the fingerprint from the session

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

$latitude = $data['latitude'] ?? null;
$longitude = $data['longitude'] ?? null;
$status = $data['status'] ?? null;

if ($status && $seller_id && $fingerprint) {
    try {
        // Check if the current fingerprint matches the one in the database
        $query = "SELECT fingerprint FROM seller_location WHERE seller_id = :seller_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':seller_id', $seller_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $row['fingerprint'] === $fingerprint) {
            // Fingerprint matches, allow updating the location
            if ($status === 'open' && $latitude && $longitude) {
                // Update the seller's location and set status to 'open'
                $query = "
                    UPDATE seller_location 
                    SET latitude = :latitude, longitude = :longitude, status = 'open', timestamp = CURRENT_TIMESTAMP
                    WHERE seller_id = :seller_id AND fingerprint = :fingerprint
                ";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':latitude', $latitude);
                $stmt->bindParam(':longitude', $longitude);
                $stmt->bindParam(':seller_id', $seller_id);
                $stmt->bindParam(':fingerprint', $fingerprint);

                if ($stmt->execute()) {
                    echo 'success';
                } else {
                    echo 'error: Failed to update location.';
                }
            } elseif ($status === 'close') {
                // Set the fingerprint to NULL when status is 'close'
                $query = "
                    UPDATE seller_location
                    SET fingerprint = NULL, status = 'close', timestamp = CURRENT_TIMESTAMP
                    WHERE seller_id = :seller_id AND fingerprint = :fingerprint
                ";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':seller_id', $seller_id);
                $stmt->bindParam(':fingerprint', $fingerprint);

                if ($stmt->execute()) {
                    echo 'success';
                } else {
                    echo 'error: Failed to update fingerprint to NULL.';
                }
            } else {
                echo 'error: Invalid status or missing location data.';
            }
        } else {
            // Fingerprint does not match
            echo 'error: Unauthorized fingerprint. Cannot update location.';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
} else {
    echo 'error: Missing required data (status, seller_id, or fingerprint).';
}
