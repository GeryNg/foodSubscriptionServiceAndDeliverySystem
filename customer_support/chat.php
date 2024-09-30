<?php
$page_title = "Customer Support - Chat";
include_once '../resource/Database.php';
include_once '../resource/session.php';
include_once '../partials/headers.php';

if (!isset($_SESSION['id']) || !isset($_GET['seller_id'])) {
    header("Location: ../login_management/login.php");
    exit;
}

$seller_id = $_GET['seller_id'];
$user_id = $_SESSION['id'];

try {
    $sql = "SELECT s.name AS seller_name, s.profile_pic AS avatar, u.status 
            FROM seller s
            JOIN users u ON s.user_id = u.id
            WHERE s.id = :seller_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmt->execute();
    $chat_partner = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chat_partner) {
        throw new Exception("Seller not found");
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
                <a href="live_chat.php" class="back-icon"><i class="bi bi-chevron-left"></i></a>
                <img src="../seller_profile_pic/<?php echo htmlspecialchars($chat_partner['avatar']); ?>" alt="Profile Picture">
                <div class="details">
                    <span><?php echo htmlspecialchars($chat_partner['seller_name']); ?></span>
                    <p><?php echo htmlspecialchars($chat_partner['status']); ?></p>
                </div>
            </header>
            <div class="chat-box"></div>
            <form action="#" class="typing-area">
                <input type="text" class="incoming_id" name="incoming_id" value="<?php echo htmlspecialchars($seller_id); ?>" hidden>
                <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                <button type="submit" class="send-button"><i class="bi bi-arrow-right-circle"></i></button>
            </form>
        </section>
    </div>
</div>
</body>
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
                url: "get_message.php",
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
                    url: "../customer_support/insert-chat.php",
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
<?php include '../partials/footer.php'; ?>
</html>
