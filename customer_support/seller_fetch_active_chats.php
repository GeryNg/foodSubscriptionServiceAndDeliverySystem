<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'] ?? '';

try {
    // Query to select active chats where the seller is either the sender or receiver
    $sql = "SELECT DISTINCT u.id AS user_id, c.Name AS customer_name, u.avatar, u.status
            FROM messages m
            JOIN users u ON u.id = CASE 
                WHEN m.incoming_msg_id = :seller_id THEN m.outgoing_msg_id 
                ELSE m.incoming_msg_id 
            END
            JOIN customer c ON c.user_id = u.id
            WHERE (m.incoming_msg_id = :seller_id OR m.outgoing_msg_id = :seller_id)
            ORDER BY m.msg_id DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Fetch the most recent message between seller and customer
            $sql2 = "SELECT msg, outgoing_msg_id 
                     FROM messages 
                     WHERE (incoming_msg_id = :customer_user_id AND outgoing_msg_id = :seller_id) 
                     OR (incoming_msg_id = :seller_id AND outgoing_msg_id = :customer_user_id) 
                     ORDER BY msg_id DESC LIMIT 1";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':customer_user_id', $row['user_id'], PDO::PARAM_STR);
            $stmt2->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
            $stmt2->execute();
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            if ($stmt2->rowCount() > 0) {
                $result = $row2['msg'];
                $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
                $you = ($seller_id == $row2['outgoing_msg_id']) ? "You: " : "";
                $boldClass = ($seller_id != $row2['outgoing_msg_id']) ? "font-bold" : "";
            } else {
                $msg = "No active message";
                $you = "";
                $boldClass = "";
            }

            $profilePicPath = !empty($row['avatar']) ? $row['avatar'] : 'default.jpg';
            $avatar = '../uploads/' . $profilePicPath;

            $output .= '<a href="seller_chat.php?user_id=' . htmlspecialchars($row['user_id']) . '">
                            <div class="content">
                                <img src="' . htmlspecialchars($avatar) . '" alt="Profile Picture">
                                <div class="details">
                                    <span>' . htmlspecialchars($row['customer_name']) . '</span>
                                    <p class="' . htmlspecialchars($boldClass) . '">' . htmlspecialchars($you . $msg) . '</p>
                                </div>
                            </div>
                            <div class="status-dot ' . (($row['status'] === "Offline now") ? "offline" : "online") . '">
                                <i class="fas fa-circle"></i>
                            </div>
                        </a>';
        }
    } else {
        $output .= 'No active chats found.';
    }

    echo $output;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
