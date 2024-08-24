<?php
$page_title = "Customer Support - Live chat";
include_once '../partials/headers.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

try {
    $stmt = $db->prepare("SELECT users.*, customer.Name FROM users 
                          JOIN customer ON users.id = customer.user_id 
                          WHERE users.id = :id");
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $profile_picture_url = $user['avatar'] ?: '';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                            <span><?php echo htmlspecialchars($user['Name']); ?></span>
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

    <script type="text/javascript" src="../js/users.js"></script>
    <br>
    <br>
    <br>
    <br>
    <?php include_once '../partials/footer.php';?>
</body>
</html>


