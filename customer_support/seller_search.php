<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$outgoing_id = $_SESSION['seller_id'];  // Use seller_id from the session

try {
    // Search customers by name based on the search term
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
            $user_id = $row['user_id'];

            // Fetch the most recent message between the seller and the customer
            $sql2 = "SELECT msg, outgoing_msg_id, incoming_msg_id 
                     FROM messages 
                     WHERE (incoming_msg_id = :customer_user_id AND outgoing_msg_id = :outgoing_id) 
                     OR (incoming_msg_id = :outgoing_id AND outgoing_msg_id = :customer_user_id) 
                     ORDER BY msg_id DESC LIMIT 1";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':customer_user_id', $user_id, PDO::PARAM_INT);
            $stmt2->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
            $stmt2->execute();
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            // Logic to determine if there's an active chat between the seller and the customer
            if ($stmt2->rowCount() > 0) {
                // Check if either outgoing_msg_id or incoming_msg_id matches the seller's ID
                if ($row2['outgoing_msg_id'] == $outgoing_id || $row2['incoming_msg_id'] == $outgoing_id) {
                    $result = $row2['msg'];
                    $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;

                    // Check if the message was sent by the seller
                    $you = ($outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";  
                    $boldClass = ($outgoing_id != $row2['outgoing_msg_id']) ? "font-bold" : "";
                } else {
                    // If the message is not between the seller and customer, display "No active message"
                    $msg = "No active message";
                    $you = "";
                    $boldClass = "";
                }
            } else {
                // No message at all between seller and customer
                $msg = "No message yet";
                $you = "";
                $boldClass = "";
            }

            // Prepare profile picture path
            $profilePicPath = !empty($row['avatar']) ? $row['avatar'] : 'default.jpg';
            $avatar = '../uploads/' . $profilePicPath;

            // Build the output for each customer search result
            $output .= '<a href="seller_chat.php?user_id=' . htmlspecialchars($user_id) . '" class="customer-result">
                            <img src="' . htmlspecialchars($avatar) . '" alt="Profile Picture">
                            <div class="details">
                                <span>' . htmlspecialchars($row['Name']) . '</span>
                                <p class="' . htmlspecialchars($boldClass) . '">' . htmlspecialchars($you . $msg) . '</p>
                            </div>
                        </a><hr/>';
        }
    } else {
        $output .= 'No customers found';
    }

    echo $output;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
