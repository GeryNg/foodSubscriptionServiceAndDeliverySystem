<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';
include_once '../resource/utilities.php';
include_once '../resource/updatePlanStatus.php';
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../partials/error.php");
    exit;
}

$user_id = $_SESSION['id'];
$access = $_SESSION['access'];
$requests_open = 0;

try {
    // Fetch user data from users table
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

try {
    // Fetch value from seller table
    $query = "SELECT requests_open FROM seller WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
        $requests_open = $row['requests_open'];
    }
} catch (PDOException $ex) {
    echo "An error occurred: " . $ex->getMessage();
}

//Toggle request link (Subaccount Button)
if (isset($_POST['toggleRequest'])) {
    $requests_open = isset($_POST['requests_open']) ? 1 : 0;
    $seller_id = $_SESSION['seller_id'];
    $linked_seller_id = $_SESSION['linked_seller_id'];

    try {
        $stmt = $db->prepare("UPDATE seller SET requests_open = :requests_open WHERE id = :seller_id");
        $stmt->execute([':requests_open' => $requests_open, ':seller_id' => $seller_id]);

        echo "<script>alert('Request settings updated.');</script>";
    } catch (PDOException $ex) {
        echo "<script>alert('An error occurred: " . $ex->getMessage() . "');</script>";
    }
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
        .weather-widget {
            margin-right: 20px;
            display: flex;
            align-items: center;
        }

        .weather-widget img {
            width: 30px;
            margin-right: 10px;
        }

        .weather-widget span {
            font-size: 14px;
        }

        .alert {
            margin-bottom: 0 !important;
        }
    </style>
</head>

<body style="background-color: #f5f5f5;" id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../partials/seller_dashboard.php">
                <img src="../image/logo-circle.png" alt="logo" style="width: 50px;">
                <div class="sidebar-brand-text mx-3">Makan Apa</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <li class="nav-item <?php echo $current_page == 'seller_dashboard.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../partials/seller_dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Plan
            </div>
            <li class="nav-item  <?php echo $current_page == 'add_plan.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../plan_management/add_plan.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Add Plan</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <li class="nav-item  <?php echo $current_page == 'list_plan.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../plan_management/list_plan.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>List Plan</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Order & Delivery
            </div>
            <li class="nav-item  <?php echo $current_page == 'seller_order_list.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../order_delivery/seller_order_list.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Order list</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <li class="nav-item  <?php echo $current_page == 'seller_delivery_list.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../order_delivery/seller_delivery_list.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Delivery List</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Customer Support
            </div>
            <li class="nav-item <?php echo $current_page == 'seller_list_feeback.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../feeback/seller_list_feeback.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Feeback</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <li class="nav-item <?php echo $current_page == 'seller_live_chat.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="../customer_support/seller_live_chat.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Live chat</span></a>
            </li>
            <div class="text-center d-none d-md-inline" style="margin-left: auto !important; margin-right: auto !important;">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
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
                        <div class="weather-widget" id="weather">
                            <img src="../image/weather-icon.png" alt="weather">
                            <span>Loading...</span>
                        </div>

                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($username); ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?php echo htmlspecialchars($avatar); ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../profile_management/seller_profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <?php if ($access === 'verify' && $requests_open === 0 && !$linked_seller_id): ?>
                                    <a class="dropdown-item" href="#" id="openRequestLink">
                                        <i class="fas fa-file-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Open Request Link
                                    </a>
                                    <form id="toggleRequestForm" method="post" action="" style="display:none;">
                                        <input type="hidden" name="requests_open" value="1">
                                        <button type="submit" name="toggleRequest"></button>
                                    </form>
                                <?php elseif ($access === 'verify' && $requests_open === 1 && !$linked_seller_id): ?>
                                    <a class="dropdown-item" href="../profile_management/accept_link.php" id="applyLinkAccount">
                                        <i class="fas fa-check-square fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Apply Link Account
                                    </a>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../login_management/logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <a class="scroll-to-top rounded" href="#page-top" style="z-index: 99;">
                    <i class="fas fa-angle-up"></i>
                </a>
            </div>

            <?php
            if (isset($_SESSION['id'])) {
                $id = $_SESSION['id'];
                $username = $_SESSION['username'];
                $access = $_SESSION['access'];

                if ($access === 'inactive') {
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Account Inactive!</strong> Your account is currently inactive. 
                            <a href="../profile_management/active_account.php" class="btn btn-primary btn-sm">Activate Account</a>
                            </div>';
                } elseif ($access === 'pending') {
                    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Account Pending!</strong> Your account is under review. Please wait for up to 3 working days.
                            </div>';
                } elseif ($access === 'rejected') {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Account Rejected!</strong> Your account request has been rejected. 
                            <a href="../profile_management/active_account.php" class="btn btn-primary btn-sm">Submit New Request</a>
                            </div>';
                } elseif ($access === 'linked') {
                    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Account Linked!</strong> Your account is under review. Please wait for up to 3 working days.
                            </div>';
                } elseif ($access === 'verify') {
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">Unauthorized access.</div>';
            }

            ?>
            <script src="../vendor/jquery/jquery.min.js"></script>
            <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
            <script src="../js/sb-admin-2.min.js"></script>
            <script src="../vendor/chart.js/Chart.min.js"></script>
            <script src="../js/demo/chart-area-demo.js"></script>
            <script src="../js/demo/chart-pie-demo.js"></script>
            <script>
                //Back to top button
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

                //Weather API
                function fetchWeatherData(latitude, longitude) {
    const apiKey = '#'; // Replace with your OpenWeatherMap API key
    const apiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&units=metric&appid=${apiKey}`;

    console.log("Fetching weather data from URL:", apiUrl);

    fetch(apiUrl)
        .then(response => {
            console.log("API response status:", response.status);
            return response.json();
        })
        .then(data => {
            console.log("Weather data received:", data);
            const temperature = data.main.temp;
            const weatherDescription = data.weather[0].description;
            const weatherElement = document.getElementById('weather');

            weatherElement.innerHTML = `
                <img src="../image/weather-icon.png" alt="weather">
                <span>${temperature}°C - ${weatherDescription}</span>
            `;
        })
        .catch(error => {
            console.log("Error fetching weather data:", error);
            document.getElementById('weather').innerHTML = 'Unable to fetch weather data';
        });
}

navigator.geolocation.getCurrentPosition(function(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;
    fetchWeatherData(latitude, longitude);
}, function(error) {
    console.log("Geolocation error:", error);
    document.getElementById('weather').innerHTML = 'Unable to fetch weather data';
});

                //Request link
                document.getElementById('openRequestLink').addEventListener('click', function(e) {
                    e.preventDefault();
                    swal({
                        title: "Are you sure?",
                        text: "Do you want to open requests for linking?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willOpen) => {
                        if (willOpen) {
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", window.location.href, true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === XMLHttpRequest.DONE) {
                                    if (xhr.status === 200) {
                                        swal("Success!", "Request settings updated.", "success");
                                    } else {
                                        swal("Error!", "An error occurred while updating the request settings.", "error");
                                    }
                                }
                            };

                            xhr.send("toggleRequest=true&requests_open=1");
                        }
                    });
                });
            </script>
</body>

</html>