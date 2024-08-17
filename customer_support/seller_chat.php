<?php
$page_title = "Customer Support - Chat";
include_once '../resource/Database.php';
include_once '../resource/session.php';
include_once '../partials/staff_nav.php';

$chat_partner_id = $_GET['user_id'];
$seller_id = $_SESSION['seller_id'];

try {
    $sqlSeller = "SELECT user_id FROM seller WHERE id = :seller_id";
    $stmtSeller = $db->prepare($sqlSeller);
    $stmtSeller->bindParam(':seller_id', $seller_id, PDO::PARAM_INT);
    $stmtSeller->execute();

    $sellerData = $stmtSeller->fetch(PDO::FETCH_ASSOC);
    $user_id = $sellerData['user_id'];

    $sql = "SELECT c.Name AS customer_name, u.avatar, u.status 
            FROM users u 
            LEFT JOIN customer c ON c.user_id = u.id
            WHERE u.id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $chat_partner_id, PDO::PARAM_INT);
    $stmt->execute();
    $chat_partner = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chat_partner) {
        throw new Exception("User not found");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../css/custom.css" rel="stylesheet" />
</head>
<body>
<div class="content1">
        <div class="wrapper">
            <section class="chat-area">
                <header>
                    <a href="seller_live_chat.php" class="back-icon"><i class="bi bi-chevron-left"></i></a>
                    <img src="../seller_profile_pic/<?php echo htmlspecialchars($chat_partner['avatar']); ?>" alt="Profile Picture">
                    <div class="details">
                        <span><?php echo htmlspecialchars($chat_partner['customer_name']); ?></span>
                        <p><?php echo htmlspecialchars($chat_partner['status']); ?></p>
                    </div>
                </header>
                <div class="chat-box"></div>
                <form action="#" class="typing-area">
                    <input type="text" class="incoming_id" name="incoming_id" value="<?php echo htmlspecialchars($chat_partner_id); ?>" hidden>
                    <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                    <button type="submit" class="send-button"><i class="bi bi-arrow-right-circle"></i></button>
                </form>
            </section>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadMessages() {
        var chatBox = $(".chat-box");
        var scrollTop = chatBox.scrollTop();
        var scrollHeight = chatBox[0].scrollHeight;
        var clientHeight = chatBox[0].clientHeight;
        var isNearBottom = (scrollHeight - scrollTop - clientHeight) < 5;

        $.ajax({
            url: "seller_get_message.php",
            type: "POST",
            data: {incoming_id: $(".incoming_id").val()},
            success: function(data) {
                chatBox.html(data);
                
                if (isNearBottom) {
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                } else {
                    chatBox.scrollTop(scrollTop);
                }
            },
            error: function() {
                console.error("Failed to load messages.");
            }
        });
    }

    loadMessages();

    setInterval(loadMessages, 5000);

    $(".typing-area").submit(function(e) {
        e.preventDefault();
        var message = $(".input-field").val().trim();
        console.log("Message submitted:", message);
        if (message !== "") {
            $.ajax({
                url: "../customer_support/seller-insert-chat.php",
                type: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    console.log("Message sent successfully");
                    $(".input-field").val("");
                    loadMessages();
                },
                error: function(xhr, status, error) {
                    console.error("Error sending message:", error);
                }
            });
        } else {
            console.log("Empty message, not sending");
        }
    });
});
</script>
</body>
</html>
