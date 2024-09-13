<?php
$page_title = "Link company";
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';

// Check if the seller has the required access
if ($seller_access == 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

$seller_id = $_SESSION['seller_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_request'])) {
    // Toggle the request_open status
    $current_status = $_POST['current_status'];
    $new_status = $current_status ? 0 : 1;

    $updateQuery = "UPDATE seller SET requests_open = :new_status WHERE id = :seller_id";
    $stmtUpdate = $db->prepare($updateQuery);
    $stmtUpdate->execute(array(':new_status' => $new_status, ':seller_id' => $seller_id));

    header("Location: link_company.php"); // Redirect to avoid form resubmission
    exit;
}

// Fetch the current request_open status for the seller
$queryStatus = "SELECT requests_open FROM seller WHERE id = :seller_id";
$stmtStatus = $db->prepare($queryStatus);
$stmtStatus->execute(array(':seller_id' => $seller_id));
$currentStatus = $stmtStatus->fetchColumn();

// Fetch all companies with request_open status
try {
    $query = "SELECT id, profile_pic, name, detail, address FROM seller WHERE requests_open = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "An error occurred: " . $ex->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
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

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1>Profile</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../partials/seller_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="../profile_management/seller_profile.php">Profile</a></li>
            <li class="breadcrumb-item active">Link Company</li>
        </ol>
        <div class="card shadow mb-4">
            <div class="card-header py-3"></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Company ID</th>
                                <th>Company Logo</th>
                                <th>Name</th>
                                <th>Detail</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Company ID</th>
                                <th>Company Logo</th>
                                <th>Name</th>
                                <th>Detail</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($companies as $company): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($company['id']); ?></td>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($company['profile_pic']); ?>" alt="Logo" width="60">
                                    </td>
                                    <td><?php echo htmlspecialchars($company['name']); ?></td>
                                    <td><?php echo htmlspecialchars($company['detail']); ?></td>
                                    <td><?php echo htmlspecialchars($company['address']); ?></td>
                                    <td>
                                        <form method="post" action="apply_for_link.php">
                                            <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company['id']); ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../js/demo/datatables-demo.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.all.min.js"></script>
</body>

</html>