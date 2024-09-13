<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$user_id = $_SESSION['id'];

try {
    $sql = "SELECT s.id AS seller_id, s.name AS seller_name, s.profile_pic, m.msg, m.outgoing_msg_id, m.incoming_msg_id
            FROM messages m
            JOIN seller s ON s.id = CASE 
                WHEN m.incoming_msg_id = :user_id THEN m.outgoing_msg_id 
                ELSE m.incoming_msg_id 
            END
            WHERE (m.incoming_msg_id = :user_id OR m.outgoing_msg_id = :user_id)
            AND (m.msg_id IN (
                SELECT MAX(msg_id) 
                FROM messages 
                WHERE (incoming_msg_id = s.id OR outgoing_msg_id = s.id) 
                GROUP BY LEAST(incoming_msg_id, outgoing_msg_id), GREATEST(incoming_msg_id, outgoing_msg_id)
            ))
            ORDER BY m.msg_id DESC";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $you = ($row['outgoing_msg_id'] == $user_id) ? "You: " : "";

            $profilePicPath = !empty($row['profile_pic']) ? $row['profile_pic'] : '../uploads/default.jpg';

            $output .= '<a href="chat.php?seller_id=' . htmlspecialchars($row['seller_id']) . '">
                            <div class="content">
                                <img src="' . htmlspecialchars($profilePicPath) . '" alt="Profile Picture">
                                <div class="details">
                                    <span>' . htmlspecialchars($row['seller_name']) . '</span>
                                    <p>' . htmlspecialchars($you . $row['msg']) . '</p>
                                </div>
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
