<?php
$page_title = "List Plan";
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';

$query = "SELECT * FROM plan";
$stmt = $db->query($query);
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
</head>
<body>
    <div class="container-fluid" style="margin-top: 20px;">
        <h1 class="h3 mb-2 text-gray-800">Tables</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Plan List</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th>Sections</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th>Sections</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($plans as $plan): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($plan['plan_name']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['description']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['price']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['date_from']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['date_to']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['section']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['status']); ?></td>
                                    <td>
                                        <a href="edit_plan.php?id=<?php echo $plan['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete_plan.php?id=<?php echo $plan['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this plan?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../js/demo/datatables-demo.js"></script>
</body>
</html>
