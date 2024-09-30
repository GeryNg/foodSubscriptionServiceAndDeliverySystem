<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$outgoing_id = $_SESSION['id'];

try {
    // Fetch all customers
    $sql = "SELECT c.user_id, c.Name, u.avatar 
            FROM customer c
            JOIN users u ON c.user_id = u.id";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $output = "";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user_id = $row['user_id'];

            // Fetch last message between seller and customer, if any
            $sql2 = "SELECT msg, outgoing_msg_id 
                     FROM messages 
                     WHERE (incoming_msg_id = :user_id AND outgoing_msg_id = :outgoing_id) 
                     OR (incoming_msg_id = :outgoing_id AND outgoing_msg_id = :user_id) 
                     ORDER BY msg_id DESC LIMIT 1";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt2->bindParam(':outgoing_id', $outgoing_id, PDO::PARAM_INT);
            $stmt2->execute();
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            $result = ($stmt2->rowCount() > 0) ? $row2['msg'] : "No message yet";
            $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
            $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";

            $profilePicPath = !empty($row['avatar']) ? $row['avatar'] : 'default.jpg';
            $avatar = '../uploads/' . $profilePicPath;

            $output .= '<a href="seller_chat.php?user_id=' . htmlspecialchars($user_id) . '" class="customer-result">
                            <img src="' . htmlspecialchars($avatar) . '" alt="Profile Picture">
                            <div class="details">
                                <span>' . htmlspecialchars($row['Name']) . '</span>
                                <p>' . htmlspecialchars($you . $msg) . '</p>
                            </div>
                        </a><hr/>';
        }
    } else {
        $output .= 'No customers found';
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>