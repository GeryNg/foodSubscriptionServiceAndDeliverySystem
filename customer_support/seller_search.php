<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$outgoing_id = $_SESSION['seller_id'];  // Use seller_id from the session

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

            if ($stmt2->rowCount() > 0) {
                if ($row2['outgoing_msg_id'] == $outgoing_id || $row2['incoming_msg_id'] == $outgoing_id) {
                    $result = $row2['msg'];
                    $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;

                    $you = ($outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";  
                    $boldClass = ($outgoing_id != $row2['outgoing_msg_id']) ? "font-bold" : "";
                } else {
                    $msg = "No active message";
                    $you = "";
                    $boldClass = "";
                }
            } else {
                $msg = "No message yet";
                $you = "";
                $boldClass = "";
            }

            $profilePicPath = !empty($row['avatar']) ? $row['avatar'] : 'default.jpg';
            $avatar = '../uploads/' . $profilePicPath;

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
