<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$outgoing_id = $_SESSION['id'];
$searchTerm = $_POST['searchTerm'];

try {
    // Fetch sellers based on search term
    $sql = "SELECT DISTINCT u.id AS user_id, s.name AS seller_name, u.avatar, s.profile_pic 
            FROM seller s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN messages m ON (u.id = CASE WHEN m.incoming_msg_id = :outgoing_id THEN m.outgoing_msg_id ELSE m.incoming_msg_id END)
            WHERE (m.incoming_msg_id = :outgoing_id OR m.outgoing_msg_id = :outgoing_id OR m.incoming_msg_id IS NULL)
            AND s.name LIKE :searchTerm
            AND s.access = 'verify'
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

            $sql2 = "SELECT msg FROM messages WHERE (incoming_msg_id = :seller_user_id AND outgoing_msg_id = :outgoing_id) 
                     OR (incoming_msg_id = :outgoing_id AND outgoing_msg_id = :seller_user_id) 
                     ORDER BY msg_id DESC LIMIT 1";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':seller_user_id', $row['user_id'], PDO::PARAM_INT);
            $stmt2->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
            $stmt2->execute();
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $result = ($stmt2->rowCount() > 0) ? $row2['msg'] : "No message yet";
            $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;

            $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
            $hide_me = ($outgoing_id == $row['user_id']) ? "hide" : "";

            $profilePicPath = !empty($row['profile_pic']) ? $row['profile_pic'] : 'default.jpg';
            $avatar = '../seller_profile_pic/' . $profilePicPath;

            $output .= '<a href="chat.php?user_id=' . htmlspecialchars($row['user_id']) . '" class="' . htmlspecialchars($hide_me) . '">
                        <div class="content">
                            <img src="' . htmlspecialchars($avatar) . '" alt="Profile Picture">
                            <div class="details">
                                <span>' . htmlspecialchars($row['seller_name']) . '</span>
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
