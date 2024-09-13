<?php
$page_title = "Customer Support - Live chat";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_access = $_SESSION['access'] ?? '';
$seller_id = $_SESSION['seller_id'] ?? '';
$user_id = $_SESSION['id'] ?? '';

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

try {
    // Fetch seller name and profile picture from the seller table using seller_id from session
    $stmtSeller = $db->prepare("SELECT name AS seller_name, profile_pic 
                                FROM seller 
                                WHERE id = :seller_id");
    $stmtSeller->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmtSeller->execute();
    $seller = $stmtSeller->fetch(PDO::FETCH_ASSOC);

    if ($seller) {
        $seller_name = $seller['seller_name'];
        $profile_picture_url = $seller['profile_pic'] ?: '../uploads/default_seller.jpg';
    } else {
        echo '<p>Seller not found with ID: ' . htmlspecialchars($seller_id) . '</p>';
        exit();
    }

    // Fetch user status from users table using user_id from session
    $stmtUser = $db->prepare("SELECT status FROM users WHERE id = :user_id");
    $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtUser->execute();
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $user_status = $user['status'] ?? 'Offline';
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
    <style>
        .container-fluid {
            margin-bottom: 5%;
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            margin: 3rem 0 0.5rem 0;
            font-weight: 800;
            line-height: 1.2;
        }

        .breadcrumb {
            background-color: transparent;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1>Live Chat</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../partials/seller_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Live Chat</li>
        </ol>
        <div class="content1">
            <div class="wrapper">
                <section class="users">
                    <header>
                        <div class="content">
                            <img src="<?php echo htmlspecialchars($profile_picture_url); ?>" alt="Profile Picture">
                            <div class="details">
                                <span><?php echo htmlspecialchars($seller_name); ?></span>
                                <p><?php echo htmlspecialchars($user_status); ?></p>
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
    </div>
    <script type="text/javascript" src="../js/seller_users.js"></script>
</body>

</html>