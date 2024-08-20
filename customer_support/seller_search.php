<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$outgoing_id = $_SESSION['id'];
$searchTerm = $_POST['searchTerm'];

try {
    $sql = "SELECT c.user_id, c.Name, u.avatar 
            FROM customer c
            JOIN users u ON c.user_id = u.id
            WHERE c.Name LIKE :searchTerm";
    $stmt = $db->prepare($sql);
    
    $searchTerm = "%$searchTerm%";
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Fetch last message and status from seller_data.php
            include 'seller_data.php';  // Display last chat message
        }
    } else {
        $output .= 'No customer found related to your search term';
    }

    echo $output;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
