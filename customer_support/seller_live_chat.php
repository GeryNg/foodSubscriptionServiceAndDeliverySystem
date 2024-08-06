<?php
$page_title = "Customer Support - Live chat";
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_access = $_SESSION['access'] ?? '';

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

try {
    $stmt = $db->prepare("SELECT users.*, seller.name AS seller_name, seller.profile_pic 
                          FROM users 
                          JOIN seller ON users.id = seller.user_id 
                          WHERE users.id = :id");
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $profile_picture_url = $user['profile_pic'] ?: '';
    } else {
        header("Location: ../login_management/logout.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="../css/custom.css" rel="stylesheet" />
</head>
<body>
    <div class="content1">
        <div class="wrapper">
            <section class="users">
                <header>
                    <div class="content">
                        <img src="<?php echo htmlspecialchars($profile_picture_url); ?>" alt="Profile Picture">
                        <div class="details">
                            <span><?php echo htmlspecialchars($user['seller_name']); ?></span>
                            <p><?php echo htmlspecialchars($user['status']); ?></p>
                        </div>
                    </div>
                    <a href="../login_management/logout.php" class="logout">Logout</a>
                </header>
                <div class="search">
                    <span class="text">Select a user to start chat</span>
                    <input type="text" placeholder="Enter name to search...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <div class="users-list">
                    <!-- List of users will be populated here -->
                </div>
            </section>
        </div>
    </div>

    <script type="text/javascript" src="../js/seller_users.js"></script>
</body>
</html>
