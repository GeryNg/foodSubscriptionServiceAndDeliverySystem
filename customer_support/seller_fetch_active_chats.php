<style>
    .font-bold {
        font-weight: bold;
        color: black;
    }
</style>
<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'];

try {
    $sqlSeller = "SELECT user_id FROM seller WHERE id = :seller_id";
    $stmtSeller = $db->prepare($sqlSeller);
    $stmtSeller->bindParam(':seller_id', $seller_id, PDO::PARAM_INT);
    $stmtSeller->execute();

    $sellerData = $stmtSeller->fetch(PDO::FETCH_ASSOC);
    $outgoing_id = $sellerData['user_id'];

    $sql = "SELECT DISTINCT u.id AS user_id, c.Name AS customer_name, u.avatar 
            FROM messages m
            JOIN users u ON u.id = CASE WHEN m.incoming_msg_id = :outgoing_id THEN m.outgoing_msg_id ELSE m.incoming_msg_id END
            JOIN customer c ON c.user_id = u.id
            WHERE (m.incoming_msg_id = :outgoing_id OR m.outgoing_msg_id = :outgoing_id)
            ORDER BY m.msg_id DESC";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sql2 = "SELECT msg, outgoing_msg_id FROM messages WHERE (incoming_msg_id = :customer_user_id AND outgoing_msg_id = :outgoing_id) 
            OR (incoming_msg_id = :outgoing_id AND outgoing_msg_id = :customer_user_id) 
            ORDER BY msg_id DESC LIMIT 1";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':customer_user_id', $row['user_id'], PDO::PARAM_INT);
            $stmt2->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
            $stmt2->execute();
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            if ($stmt2->rowCount() > 0) {

                $result = $row2['msg'];
                $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
                $you = ($outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
                $boldClass = ($outgoing_id != $row2['outgoing_msg_id']) ? "font-bold" : "";
            } else {
                $msg = "No message available";
                $you = "";
                $boldClass = "";
            }

            $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
            $hide_me = ($outgoing_id == $row['user_id']) ? "hide" : "";

            $profilePicPath = !empty($row['avatar']) ? $row['avatar'] : 'default.jpg';
            $avatar = '../uploads/' . $profilePicPath;

            $sqlStatus = "SELECT status FROM users WHERE id = :user_id";
            $stmtStatus = $db->prepare($sqlStatus);
            $stmtStatus->bindParam(':user_id', $row['user_id'], PDO::PARAM_INT);
            $stmtStatus->execute();
            $userStatus = $stmtStatus->fetch(PDO::FETCH_ASSOC);

            $output .= '<a href="seller_chat.php?user_id=' . htmlspecialchars($row['user_id']) . '" class="' . htmlspecialchars($hide_me) . '">
                        <div class="content">
                            <img src="' . htmlspecialchars($avatar) . '" alt="Profile Picture">
                            <div class="details">
                                <span>' . htmlspecialchars($row['customer_name']) . '</span>
                                <p class="' . htmlspecialchars($boldClass) . '">' . htmlspecialchars($you . $msg) . '</p>
                            </div>
                        </div>
                        <div class="status-dot ' . (($userStatus['status'] === "Offline now") ? "offline" : "online") . '"><i class="fas fa-circle"></i></div>
                    </a>';
        }
    } else {
        $output .= 'No active chats found.';
    }

    echo $output;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
