<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$outgoing_id = $_SESSION['id'];
$searchTerm = $_POST['searchTerm'];

try {
    $sql = "SELECT seller.*, users.avatar 
            FROM seller 
            JOIN users ON seller.user_id = users.id 
            WHERE seller.name LIKE :searchTerm";
    $stmt = $db->prepare($sql);
    
    $searchTerm = "%$searchTerm%";
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() > 0) {
        include 'data.php';
    } else {
        $output .= 'No seller found related to your search term';
    }

    echo $output;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
