<?php
$page_title = "Dashboard";
$current_page = basename(__FILE__);
include_once 'staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'];
$todayDate = date('Y-m-d');

//fetch the announcement
$user_id = $_SESSION['id'];
$query = "SELECT join_date FROM users WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$userJoinDate = $stmt->fetch(PDO::FETCH_ASSOC)['join_date'];

$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    $stmt = $db->prepare("
        SELECT * FROM announcement 
        WHERE date >= :join_date 
        ORDER BY id DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':join_date', $userJoinDate);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtTotal = $db->prepare("
        SELECT COUNT(*) FROM announcement 
        WHERE date >= :join_date
    ");
    $stmtTotal->bindValue(':join_date', $userJoinDate);
    $stmtTotal->execute();
    $totalAnnouncements = $stmtTotal->fetchColumn();
    $totalPages = ceil($totalAnnouncements / $limit);
} catch (PDOException $e) {
    echo json_encode([
        'error' => "Error fetching announcements: " . $e->getMessage()
    ]);
}

$accountStatus = isset($_SESSION['access']) ? $_SESSION['access'] : 'inactive';
$seller_id = $_SESSION['seller_id'];

//seller active_plans data
$query = "SELECT COUNT(*) AS active_plans FROM plan WHERE seller_id = :seller_id AND status = 'Active'";
$stmt = $db->prepare($query);
$stmt->execute([':seller_id' => $seller_id]);
$activePlanCount = $stmt->fetch(PDO::FETCH_ASSOC)['active_plans'];

//fetch orders data
$query = "SELECT COUNT(*) AS active_orders FROM order_cust WHERE Status = 'Active'";
$stmt = $db->prepare($query);
$stmt->execute();
$activeOrderCount = $stmt->fetch(PDO::FETCH_ASSOC)['active_orders'];

//fetch total deliveries for today
$queryTotal = "
    SELECT COUNT(*) AS total_deliveries 
    FROM delivery 
    WHERE seller_id = :seller_id 
    AND delivery_date = :delivery_date
";
$stmtTotal = $db->prepare($queryTotal);
$stmtTotal->execute([
    ':seller_id' => $seller_id,
    ':delivery_date' => $todayDate
]);
$totalDeliveries = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total_deliveries'];

//fetch the status is 'done delivery' for today
$queryDone = "
    SELECT COUNT(*) AS done_deliveries 
    FROM delivery 
    WHERE seller_id = :seller_id 
    AND delivery_date = :delivery_date 
    AND status = 'done delivery'
";
$stmtDone = $db->prepare($queryDone);
$stmtDone->execute([
    ':seller_id' => $seller_id,
    ':delivery_date' => $todayDate
]);
$doneDeliveries = $stmtDone->fetch(PDO::FETCH_ASSOC)['done_deliveries'];

//calculate the percentage
$percentageDone = ($totalDeliveries > 0) ? ($doneDeliveries / $totalDeliveries) * 100 : 0;

//fetch total feeback
$queryPlan = "SELECT id FROM plan WHERE seller_id = :seller_id LIMIT 1";
$stmtPlan = $db->prepare($queryPlan);
$stmtPlan->execute([':seller_id' => $seller_id]);
$sellerPlan = $stmtPlan->fetchColumn();

if ($sellerPlan) {
    $queryFeedback = "
        SELECT COUNT(*) AS total_feedback
        FROM feedback f
        JOIN order_cust oc ON f.Order_ID = oc.Order_ID
        WHERE oc.Plan_ID = :plan_id
    ";
    $stmtFeedback = $db->prepare($queryFeedback);
    $stmtFeedback->execute([':plan_id' => $sellerPlan]);
    $totalFeedback = $stmtFeedback->fetchColumn();
} else {
    $totalFeedback = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <style>
        .button1 {
            background-color: #5C67F2;
            color: white;
            border: none;
            padding: 10px 20px;
            text-transform: uppercase;
            cursor: pointer;
            float: right;
            margin-top: 10px;
        }

        .button1:hover {
            background-color: #7a85ff;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="margin:20px;">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h1 mb-0 text-gray-800" style="font-weight: 600;">Dashboard</h1>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Plan Active</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $activePlanCount; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list  fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Order</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $activeOrderCount; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Done Delivery</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo round($percentageDone, 2); ?>%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: <?php echo round($percentageDone, 2); ?>%" aria-valuenow="<?php echo round($percentageDone, 2); ?>" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Feedback</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalFeedback; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="myPieChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> #
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> #
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> #
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Announcement</h1>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <?php if ($accountStatus !== 'verify'): ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Active your Account</h6>
                        </div>
                        <div class="card-body">
                            <p>Seem like your Account haven't active yet, Click <a href="../profile_management/active_account.php">Active</a> to set up your website and grow your business with us.</p>
                            <a rel="nofollow" href="../profile_management/active_account.php">Click here to active your account &rarr;</a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($accountStatus !== 'verify'): ?>
                    <!-- Welcome Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Welcome to Makan Apa</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <img class="img-fluid px-3 px-sm-4 mt-3 mb-4"
                                    src="https://t3.ftcdn.net/jpg/00/87/97/06/240_F_87970620_Tdgw6WYdWnrZHn2uQwJpVDH4vr4PINSc.jpg" alt="...">
                            </div>
                            <p>
                                Welcome to Makan Apa! We're the go-to hub for food sellers who want to reach hungry customers without the hassle. With our user-friendly interface, robust delivery infrastructure, and a hungry customer base, we're here to supercharge your business growth!
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (empty($announcements)): ?>
                    <p>No announcements to display.</p>
                <?php else: ?>
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($announcement['title']); ?></h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($announcement['image_url'])): ?>
                                    <div class="text-center">
                                        <img class="announcement-image" src="<?php echo htmlspecialchars($announcement['image_url']); ?>" alt="Announcement Image">
                                    </div>
                                <?php endif; ?>
                                <p style="color: gray;"><?php echo date("d/m/Y", strtotime($announcement['date'])); ?></p>
                                <p><?php echo htmlspecialchars($announcement['content']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($page < $totalPages): ?>
                    <button id="showMoreBtn" class="button1">Show More</button>
                <?php endif; ?>

                <div id="moreAnnouncements" style="display: none;"></div>
            </div>
        </div>
    </div>

    </div>
    <script>
        document.getElementById('showMoreBtn').addEventListener('click', function() {
            let currentPage = <?php echo $page; ?>;
            let nextPage = currentPage + 1;
            let xhr = new XMLHttpRequest();
            xhr.open('GET', 'load_more_announcements.php?page=' + nextPage, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    let announcementsHtml = '';

                    response.announcements.forEach(function(announcement) {
                        announcementsHtml += `
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">${announcement.title}</h6>
                                </div>
                                <div class="card-body">
                                    ${announcement.image_url ? '<div class="text-center"><img class="announcement-image" src="' + announcement.image_url + '" alt="Announcement Image"></div>' : ''}
                                    <p style="color: gray;">${announcement.date}</p>
                                    <p>${announcement.content}</p>
                                </div>
                            </div>
                        `;
                    });

                    document.getElementById('moreAnnouncements').innerHTML = announcementsHtml;
                    document.getElementById('moreAnnouncements').style.display = 'block';

                    if (nextPage >= response.totalPages) {
                        document.getElementById('showMoreBtn').style.display = 'none';
                    }
                }
            };
            xhr.send();
        });
    </script>
</body>

</html>