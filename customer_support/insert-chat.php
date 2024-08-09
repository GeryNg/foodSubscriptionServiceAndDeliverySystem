<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';

if (!isset($_SESSION['id'])) {
    echo "Error: User not logged in";
    exit;
}

$outgoing_id = $_SESSION['id'];
$incoming_id = $_POST['incoming_id'];
$message = $_POST['message'];

if (empty($incoming_id) || empty($message)) {
    echo "Error: Invalid input";
    exit;
}

try {
    $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (:incoming_id, :outgoing_id, :message)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':incoming_id', $incoming_id, PDO::PARAM_INT);
    $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        echo "Message sent successfully";
    } else {
        echo "Failed to send message";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
