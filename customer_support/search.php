<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$outgoing_id = $_SESSION['id'];
$searchTerm = $_POST['searchTerm'];

try {
    $sql = "SELECT s.id AS seller_id, s.name AS seller_name, s.profile_pic 
            FROM seller s
            WHERE s.name LIKE :searchTerm
            AND s.access = 'verify'";
    
    $stmt = $db->prepare($sql);
    $searchTerm = "%$searchTerm%";
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $seller_id = $row['seller_id'];

            $sqlStatus = "SELECT status FROM users WHERE id = :seller_user_id";
            $stmtStatus = $db->prepare($sqlStatus);
            $stmtStatus->bindParam(':seller_user_id', $seller_id, PDO::PARAM_STR);
            $stmtStatus->execute();
            $userStatus = $stmtStatus->fetch(PDO::FETCH_ASSOC);

            $sql2 = "SELECT msg, outgoing_msg_id 
                     FROM messages 
                     WHERE (incoming_msg_id = :seller_id AND outgoing_msg_id = :outgoing_id) 
                        OR (incoming_msg_id = :outgoing_id AND outgoing_msg_id = :seller_id) 
                     ORDER BY msg_id DESC LIMIT 1";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
            $stmt2->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_STR);
            $stmt2->execute();
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            $result = ($stmt2->rowCount() > 0) ? $row2['msg'] : "No message yet";
            $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
            $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
            
            $profilePicPath = !empty($row['profile_pic']) ? $row['profile_pic'] : 'default.jpg';
            $avatar = '../seller_profile_pic/' . $profilePicPath;

            $output .= '<a href="chat.php?seller_id=' . htmlspecialchars($seller_id) . '">
                        <div class="content">
                            <img src="' . htmlspecialchars($avatar) . '" alt="Profile Picture">
                            <div class="details">
                                <span>' . htmlspecialchars($row['seller_name']) . '</span>
                                <p>' . htmlspecialchars($you . $msg) . '</p>
                            </div>
                        </div>
                    </a>';
        }
    } else {
        $output .= 'No sellers found related to your search term.';
    }

    echo $output;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
