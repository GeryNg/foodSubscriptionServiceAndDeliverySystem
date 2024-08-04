<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$outgoing_id = $_SESSION['id'];
$searchTerm = $_POST['searchTerm'];

try {
    $sql = "SELECT DISTINCT u.id AS user_id, c.Name AS customer_name, u.avatar 
            FROM customer c
            JOIN users u ON c.user_id = u.id
            LEFT JOIN messages m ON (u.id = CASE WHEN m.incoming_msg_id = :outgoing_id THEN m.outgoing_msg_id ELSE m.incoming_msg_id END)
            WHERE (m.incoming_msg_id = :outgoing_id OR m.outgoing_msg_id = :outgoing_id OR m.incoming_msg_id IS NULL)
            AND c.Name LIKE :searchTerm
            ORDER BY m.msg_id DESC";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
    $searchTerm = "%$searchTerm%";
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sqlStatus = "SELECT status FROM users WHERE id = :user_id";
            $stmtStatus = $db->prepare($sqlStatus);
            $stmtStatus->bindParam(':user_id', $row['user_id'], PDO::PARAM_INT);
            $stmtStatus->execute();
            $userStatus = $stmtStatus->fetch(PDO::FETCH_ASSOC);

            $sql2 = "SELECT msg FROM messages WHERE (incoming_msg_id = :customer_user_id AND outgoing_msg_id = :outgoing_id) 
                     OR (incoming_msg_id = :outgoing_id AND outgoing_msg_id = :customer_user_id) 
                     ORDER BY msg_id DESC LIMIT 1";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':customer_user_id', $row['user_id'], PDO::PARAM_INT);
            $stmt2->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
            $stmt2->execute();
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $result = ($stmt2->rowCount() > 0) ? $row2['msg'] : "No message yet";
            $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;

            $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
            $hide_me = ($outgoing_id == $row['user_id']) ? "hide" : "";

            $profilePicPath = !empty($row['avatar']) ? $row['avatar'] : 'default.jpg';
            $avatar = '../uploads/' . $profilePicPath;

            $output .= '<a href="seller_chat.php?user_id=' . htmlspecialchars($row['user_id']) . '" class="' . htmlspecialchars($hide_me) . '">
                        <div class="content">
                            <img src="' . htmlspecialchars($avatar) . '" alt="Profile Picture">
                            <div class="details">
                                <span>' . htmlspecialchars($row['customer_name']) . '</span>
                                <p>' . htmlspecialchars($you . $msg) . '</p>
                            </div>
                        </div>
                        <div class="status-dot ' . (($userStatus['status'] === "Offline now") ? "offline" : "online") . '"><i class="fas fa-circle"></i></div>
                    </a>';
        }
    } else {
        $output .= 'No active chats found.';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
