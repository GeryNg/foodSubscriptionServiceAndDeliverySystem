<head>
    <style>
    .chat-area {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .chat-box {
        padding: 10px;
        background: #f1f1f1;
        overflow-y: auto;
    }

    .chat {
        margin: 10px 0;
        display: flex;
        align-items: flex-end;
    }

    .chat .details {
        max-width: 70%;
        border-radius: 20px;
        position: relative;
        word-wrap: break-word;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .chat .details p{
        margin: 0 !important;
    }

    .outgoing {
        justify-content: flex-end;
    }

    .outgoing .details {
        background: #4CAF50;
        color: #fff;
        border-radius: 20px 20px 0 20px;
    }

    .outgoing .details::after {
        content: "";
        position: absolute;
        bottom: -10px;
        right: 0;
        border-width: 10px;
        border-style: solid;
        border-color: #4CAF50 transparent transparent transparent;
    }

    .incoming {
        justify-content: flex-start;
    }

    .incoming .details {
        background: #2196F3;
        color: #fff;
        border-radius: 20px 20px 20px 0;
    }

    .incoming .details::after {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 0;
        border-width: 10px;
        border-style: solid;
        border-color: #2196F3 transparent transparent transparent;
    }

    .incoming img {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
    }
    </style>
</head>

<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

if (isset($_SESSION['id'])) {
    $seller_id = $_SESSION['seller_id'];
    $incoming_id = $_POST['incoming_id'];

    $output = "";

    try {
        $sql = "SELECT messages.msg, messages.outgoing_msg_id, messages.incoming_msg_id, users.avatar 
                FROM messages 
                LEFT JOIN users ON users.id = messages.outgoing_msg_id 
                WHERE (messages.outgoing_msg_id = :seller_id AND messages.incoming_msg_id = :incoming_id) 
                   OR (messages.outgoing_msg_id = :incoming_id AND messages.incoming_msg_id = :seller_id) 
                ORDER BY messages.msg_id ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_INT);
        $stmt->bindParam(':incoming_id', $incoming_id, PDO::PARAM_INT);
        $stmt->execute();

        $hasSellerMessages = false;  // Flag to check if any message involves the seller

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Check if the seller is involved in the conversation (either as outgoing or incoming)
                if ($row['outgoing_msg_id'] == $seller_id || $row['incoming_msg_id'] == $seller_id) {
                    $hasSellerMessages = true;  // Mark that a message involving the seller was found

                    if ($row['outgoing_msg_id'] == $seller_id) {
                        $output .= '<div class="chat outgoing">
                                        <div class="details">
                                            <p>' . htmlspecialchars($row['msg']) . '</p>
                                        </div>
                                    </div>';
                    } else {
                        $profilePicPath = !empty($row['avatar']) ? $row['avatar'] : 'default.jpg';
                        $avatar = '../uploads/' . htmlspecialchars($profilePicPath);

                        $output .= '<div class="chat incoming">
                                        <img src="' . $avatar . '" alt="Customer Profile Picture">
                                        <div class="details">
                                            <p>' . htmlspecialchars($row['msg']) . '</p>
                                        </div>
                                    </div>';
                    }
                }
            }
        }

        // If no messages involving the seller were found, display "No messages are available"
        if (!$hasSellerMessages) {
            $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
        }

        echo $output;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>