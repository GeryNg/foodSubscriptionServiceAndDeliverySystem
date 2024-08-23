<?php
$page_title = "Dashboard";
include_once '../partials/admin_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';
include_once '../resource/utilities.php';

$announcements = [];
$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    $stmt = $db->prepare("SELECT * FROM announcement ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtTotal = $db->query("SELECT COUNT(*) FROM announcement");
    $totalAnnouncements = $stmtTotal->fetchColumn();
    $totalPages = ceil($totalAnnouncements / $limit);

    //fetch active plans
    $stmtActivePlans = $db->prepare("SELECT COUNT(*) FROM plan WHERE status = :status");
    $status = 'active';
    $stmtActivePlans->bindParam(':status', $status, PDO::PARAM_STR);
    $stmtActivePlans->execute();
    $activePlansCount = $stmtActivePlans->fetchColumn();

    //fetch for the this month
    $currentMonth = date('Y-m');
    $stmtOrders = $db->prepare("SELECT COUNT(*) FROM order_cust WHERE DATE_FORMAT(OrderDate, '%Y-%m') = :currentMonth");
    $stmtOrders->bindParam(':currentMonth', $currentMonth, PDO::PARAM_STR);
    $stmtOrders->execute();
    $totalOrders = $stmtOrders->fetchColumn();

    //fetch count of sellers with access 'verify'
    $stmtVerifiedSellers = $db->prepare("SELECT COUNT(*) FROM seller WHERE access = :access");
    $accessVerified = 'verify';
    $stmtVerifiedSellers->bindParam(':access', $accessVerified, PDO::PARAM_STR);
    $stmtVerifiedSellers->execute();
    $verifiedSellersCount = $stmtVerifiedSellers->fetchColumn();

    //fetch count of sellers with access 'pending'
    $stmtPendingSellers = $db->prepare("SELECT COUNT(*) FROM seller WHERE access = :access");
    $accessPending = 'pending';
    $stmtPendingSellers->bindParam(':access', $accessPending, PDO::PARAM_STR);
    $stmtPendingSellers->execute();
    $pendingSellersCount = $stmtPendingSellers->fetchColumn();
} catch (PDOException $e) {
    echo "Error fetching announcements: " . $e->getMessage();
}

if (isset($_POST['AddAnnouncementBtn'])) {
    $form_errors = array();

    // Required fields
    $required_fields = ['title', 'content'];
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    // Validate and process single image upload
    $imageUrl = '';
    if (!empty($_FILES['image']['name'])) {
        $allowedFormats = ['jpg', 'jpeg', 'png'];
        $fileName = $_FILES['image']['name'];
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $fileError = $_FILES['image']['error'];
        $fileType = $_FILES['image']['type'];

        if ($fileError === 0) {
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowedFormats)) {
                if ($fileSize <= 2 * 1024 * 1024) { // 2MB limit
                    $newFileName = uniqid('', true) . "." . $fileExtension;
                    $targetDir = "image/";
                    $targetPath = $targetDir . $newFileName;

                    if (move_uploaded_file($fileTmpName, $targetPath)) {
                        $imageUrl = $targetPath;
                    } else {
                        $form_errors[] = "Failed to move uploaded file: $fileName";
                    }
                } else {
                    $form_errors[] = "File size exceeds the limit: $fileName";
                }
            } else {
                $form_errors[] = "Invalid file format for: $fileName";
            }
        } else {
            $form_errors[] = "Error uploading file $fileName: " . uploadErrorToString($fileError);
        }
    }

    if (empty($form_errors)) {
        try {
            $id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

            $title = htmlspecialchars($_POST['title']);
            $content = htmlspecialchars($_POST['content']);

            $stmt = $db->prepare("INSERT INTO announcement (title, content, image_url, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $imageUrl, $id]);

            if ($stmt->rowCount() == 1) {
                echo "<script>
                swal({
                  title: \"Announcement Added!\",
                  text: \"The announcement has been added successfully.\",
                  icon: 'success',
                  button: \"OK\",
                });
                setTimeout(function(){
                window.location.href = '../admin/admin_dashboard.php';
                }, 2000);
                </script>";
                exit;
            } else {
                $result = "Failed to save the announcement. Please try again.";
            }
        } catch (PDOException $e) {
            $result = "Failed to save the announcement: " . $e->getMessage();
        }
    } else {
        $result = count($form_errors) == 1
            ? flashMessage("There was 1 error in the form<br>")
            : flashMessage("There were " . count($form_errors) . " errors in the form <br>");
    }
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
        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 5px;
            box-sizing: border-box;
        }

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

        .announcement-image {
            width: 500px;
            height: 500px;
            object-fit: cover;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .announcement-card {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="margin:20px;">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Plan Active</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($activePlansCount); ?></div>
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
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Order (This Month)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($totalOrders); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
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
                                    Seller Amount</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($verifiedSellersCount); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-child fa-2x text-gray-300"></i>
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
                                    Assign Pending</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($pendingSellersCount); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-edit fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Leave a announcement</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <!-- Your form fields here -->
                            <p><strong>Title of announcement: </strong><input type="text" name="title" required></p>
                            <p><strong>Content of announcement: </strong><textarea name="content" required></textarea></p>
                            <p><strong>Image (if any): </strong><input type="file" name="image" accept=".jpg, .jpeg, .png"></p>
                            <button type="submit" name="AddAnnouncementBtn" value="AddAnnouncement" class="button1">Add announcement</button>
                        </form>
                    </div>
                </div>
                <?php foreach ($announcements as $announcement): ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($announcement['title']); ?></h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($announcement['image_url'])): ?>
                                <div class="text-center">
                                    <img class="announcement-image"
                                        src="<?php echo htmlspecialchars($announcement['image_url']); ?>" alt="Announcement Image">
                                </div>
                            <?php endif; ?>
                            <p style="color: gray;"><?php echo date("d/m/Y", strtotime($announcement['date'])); ?></p>
                            <p><?php echo htmlspecialchars($announcement['content']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if ($page < $totalPages): ?>
                    <button id="showMoreBtn" class="button1">Show More</button>
                <?php endif; ?>

                <div id="moreAnnouncements" style="display: none;"></div>
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