<?php
$page_title = "User Authentication - Seller Profile";
include_once '../resource/session.php';
include_once '../partials/staff_nav.php';
include_once '../partials/parseSellerProfile.php';

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $current_seller_id = $_SESSION['seller_id'];

    //fetch user information
    $sqlQuery = "SELECT * FROM users WHERE id = :id";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array(':id' => $id));

    while ($rs = $statement->fetch()) {
        $username = $rs['username'];
        $email = $rs['email'];
        $date_joined = (new DateTime($rs["join_date"]))->format('M d, Y');
        $avatar = $rs['avatar'];
    }

    $user_pic = !empty($avatar) ? $avatar : "../uploads/default.jpg";

    // Fetch the first restaurant based on the current seller ID in session
    $sqlQueryFirstRestaurant = "SELECT * FROM seller WHERE id = :seller_id AND user_id = :user_id";
    $statementFirstRestaurant = $db->prepare($sqlQueryFirstRestaurant);
    $statementFirstRestaurant->execute(array(':seller_id' => $current_seller_id, ':user_id' => $id));
    $first_restaurant = $statementFirstRestaurant->fetch(PDO::FETCH_ASSOC);

    $profile_pic = !empty($first_restaurant['profile_pic']) ? $first_restaurant['profile_pic'] : "../uploads/default_seller.jpg";

    // Fetch all restaurants for this user
    $sqlQueryRestaurants = "SELECT * FROM seller WHERE user_id = :user_id";
    $statementRestaurants = $db->prepare($sqlQueryRestaurants);
    $statementRestaurants->execute(array(':user_id' => $id));
    $restaurants = $statementRestaurants->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "<p>User not found.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Authentication - Seller Profile</title>
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

        .container1 {
            margin: 20px auto;
            padding: 0 35px 0 35px;
        }

        .pull-right {
            margin-top: 10px;
            float: right !important;
        }

        .badge-status {
            margin-left: 10px;
            padding: 5px 10px;
            font-size: 1rem;
        }

        .card-body {
            position: relative;
            padding-bottom: 60px;
        }

        .fixed-bottom-buttons {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        .fixed-bottom-buttons .btn {
            margin-right: 5px;
        }
    </style>
    <link href="../css/add_btn_design.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <h1>Profile</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../partials/seller_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
        <div class="container1">
            <div class="main-body">
                <div class="row gutters-sm">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="<?php echo htmlspecialchars($user_pic); ?>" alt="Profile Picture" class="rounded-circle" width="200">
                                    <div class="mt-3">
                                        <h4><?php if (isset($username)) echo htmlspecialchars($username); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h2>User Information</h2>
                                    </div>
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Full Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?php if (isset($username)) echo htmlspecialchars($username); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?php if (isset($email)) echo htmlspecialchars($email); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Date Joined</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?php if (isset($date_joined)) echo htmlspecialchars($date_joined); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a class="btn btn-info pull-right" href="seller_edit_profile.php?user_identity=<?php if (isset($encode_id)) echo htmlspecialchars($encode_id); ?>">Edit Profile</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seller Information Section -->
                    <?php if ($_SESSION['access'] !== 'unknown'): ?>
                    <div class="row">
                        <!-- First Card: Displaying information of the first restaurant -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3><?php echo htmlspecialchars($first_restaurant['name']); ?></h3>
                                        <span class="badge badge-status <?php echo ($_SESSION['access'] === 'pending' ? 'badge-warning' : ($_SESSION['access'] === 'inactive' ? 'badge-info' : ($_SESSION['access'] === 'rejected' ? 'badge-danger' : ($_SESSION['access'] === 'verify' ? 'badge-success' : '')))); ?>">
                                            <?php echo ucfirst($_SESSION['access']); ?>
                                        </span>
                                    </div>
                                    <!-- Displaying first restaurant's profile picture -->
                                    <center><img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Seller Profile Picture" class="rounded-circle" width="150"></center>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 text-info">
                                            <i class="material-icons">Name</i>
                                        </div>
                                        <div class="col-sm-8">
                                            <p><?php echo htmlspecialchars($first_restaurant['name']); ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 text-info">
                                            <i class="material-icons">Detail</i>
                                        </div>
                                        <div class="col-sm-8">
                                            <p><?php echo htmlspecialchars($first_restaurant['detail']); ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 text-info">
                                            <i class="material-icons">Contact Number</i>
                                        </div>
                                        <div class="col-sm-8">
                                            <p><?php echo htmlspecialchars($first_restaurant['contact_number']); ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 text-info">
                                            <i class="material-icons">Address</i>
                                        </div>
                                        <div class="col-sm-8">
                                            <p><?php echo htmlspecialchars($first_restaurant['address']); ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 text-info">
                                            <i class="material-icons">Bank Account</i>
                                        </div>
                                        <div class="col-sm-8">
                                            <p><?php echo htmlspecialchars($first_restaurant['bank_account']); ?></p>
                                        </div>
                                    </div>
                                    <div class="fixed-bottom-buttons">
                                        <a href="seller_edit_information.php?seller_id=<?php echo htmlspecialchars($first_restaurant['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loop through and display information of remaining restaurants -->
                        <?php foreach ($restaurants as $restaurant): ?>
                            <?php if ($restaurant['id'] == $first_restaurant['id']) continue; ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h3><?php echo htmlspecialchars($restaurant['name']); ?></h3>
                                            <span class="badge badge-status <?php echo ($restaurant['access'] === 'pending' ? 'badge-warning' : ($restaurant['access'] === 'inactive' ? 'badge-info' : ($restaurant['access'] === 'rejected' ? 'badge-danger' : ($restaurant['access'] === 'verify' ? 'badge-success' : '')))); ?>">
                                                <?php echo ucfirst($restaurant['access']); ?>
                                            </span>
                                        </div>
                                        <!-- Displaying restaurant profile picture -->
                                        <?php
                                        $restaurant_pic = !empty($restaurant['profile_pic']) ? $restaurant['profile_pic'] : "../uploads/default_seller.jpg";
                                        ?>
                                        <center><img src="<?php echo htmlspecialchars($restaurant_pic); ?>" alt="Restaurant Profile Picture" class="rounded-circle" width="150"></center>
                                        <div class="row mb-3">
                                            <div class="col-sm-4 text-info">
                                                <i class="material-icons">Name</i>
                                            </div>
                                            <div class="col-sm-8">
                                                <p><?php echo htmlspecialchars($restaurant['name']); ?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4 text-info">
                                                <i class="material-icons">Detail</i>
                                            </div>
                                            <div class="col-sm-8">
                                                <p><?php echo htmlspecialchars($restaurant['detail']); ?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4 text-info">
                                                <i class="material-icons">Contact Number</i>
                                            </div>
                                            <div class="col-sm-8">
                                                <p><?php echo htmlspecialchars($restaurant['contact_number']); ?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4 text-info">
                                                <i class="material-icons">Address</i>
                                            </div>
                                            <div class="col-sm-8">
                                                <p><?php echo htmlspecialchars($restaurant['address']); ?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-4 text-info">
                                                <i class="material-icons">Bank Account</i>
                                            </div>
                                            <div class="col-sm-8">
                                                <p><?php echo htmlspecialchars($restaurant['bank_account']); ?></p>
                                            </div>
                                        </div>
                                        <div class="fixed-bottom-buttons" style="display: flex;">
                                            <form action="switch_seller.php" method="GET" onsubmit="return showLoader();">
                                                <input type="hidden" name="seller_id" value="<?php echo htmlspecialchars($restaurant['id']); ?>">
                                                <button type="submit" class="btn btn-info btn-sm">Switch Account</button>
                                            </form>
                                            <a href="seller_edit_information.php?seller_id=<?php echo htmlspecialchars($restaurant['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- End of Seller Information Section -->

                        <!-- Second card with the plus menu -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <nav class="menu">
                                        <input type="checkbox" href="#" class="menu-open" name="menu-open" id="menu-open" />
                                        <label class="menu-open-button" for="menu-open">
                                            <i class="fas fa-plus-circle" style="color:#333;"></i>
                                        </label>
                                        <a href="../profile_management/add_restaurant.php" class="menu-item blue" title="Add Restaurant"><i class="fas fa-home"></i></a>
                                        <a href="#"></a>
                                        <a href="#"></a>
                                        <a href="../profile_management/link_account.php" class="menu-item purple" title="Link Restaurant"><i class="fas fa-link"></i></a>
                                        <a href="#"></a>
                                        <a href="#"></a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!-- End of Seller Information Section -->
                </div>
            </div>
        </div>
    </div>
    <script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
