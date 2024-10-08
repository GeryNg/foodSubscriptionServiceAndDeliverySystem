<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';
include_once '../resource/utilities.php';
include_once '../resource/updatePlanStatus.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../partials/error.php");
    exit;
}

$user_id = $_SESSION['id'];
try {
    $query = "SELECT username, avatar FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
        $username = $row['username'];
        $avatar = $row['avatar'];
    }
} catch (PDOException $ex) {
    echo "An error occurred: " . $ex->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Default Title'; ?>
    </title>
    <link rel="icon" type="image/x-icon" href="../image/logo-circle.png">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <style>

    </style>
</head>

<body style="background-color: #f5f5f5;" id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../admin/admin_dashboard.php">
                <img src="../image/logo-circle.png" alt="logo" style="width: 50px;">
                <div class="sidebar-brand-text mx-3">Makan Apa</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <li class="nav-item <?php echo $current_page == 'admin_dashboard.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../admin/admin_dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Admin Function
            </div>
            <li class="nav-item  <?php echo $current_page == 'assign.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../admin/assign.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Assign</span></a>
            </li>
            <li class="nav-item  <?php echo $current_page == 'admin_wallet.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../wallet/admin_wallet.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Wallet</span></a>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" style="text-align:center !important;">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($username); ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?php echo htmlspecialchars($avatar); ?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../login_management/logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>
            </div>

            <script src="../vendor/jquery/jquery.min.js"></script>
            <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
            <script src="../js/sb-admin-2.min.js"></script>
            <script src="../vendor/chart.js/Chart.min.js"></script>
            <script src="../js/demo/chart-area-demo.js"></script>
            <script src="../js/demo/chart-pie-demo.js"></script>

            <script>
                $(document).scroll(function() {
                    var scrollDistance = $(this).scrollTop();
                    if (scrollDistance > 100) {
                        $('.scroll-to-top').fadeIn();
                    } else {
                        $('.scroll-to-top').fadeOut();
                    }
                });

                $(document).on('click', 'a.scroll-to-top', function(event) {
                    var $anchor = $(this);
                    $('html, body').stop().animate({
                        scrollTop: ($($anchor.attr('href')).offset().top)
                    }, 500, 'easeInOutExpo');
                    event.preventDefault();
                });
            </script>
</body>

</html>