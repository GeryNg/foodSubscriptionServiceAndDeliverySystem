<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$outgoing_id = $_SESSION['id'];
$searchTerm = $_POST['searchTerm'];

try {
    $sql = "SELECT customer.*, users.avatar 
            FROM customer 
            JOIN users ON customer.user_id = users.id 
            WHERE customer.Name LIKE :searchTerm";
    $stmt = $db->prepare($sql);
    
    $searchTerm = "%$searchTerm%";
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() > 0) {
        include 'seller_data.php';
    } else {
        $output .= 'No customer found related to your search term';
    }

    echo $output;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
