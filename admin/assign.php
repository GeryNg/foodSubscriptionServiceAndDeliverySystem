<?php
$page_title = "Assign list";
include_once '../partials/admin_nav.php';
include_once '../resource/Database.php';

$query = "SELECT * FROM seller WHERE access != 'inactive'";
$stmt = $db->prepare($query);
$stmt->execute();
$sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query_pending = "SELECT COUNT(*) as pending_count FROM seller WHERE access = 'pending'";
$stmt_pending = $db->prepare($query_pending);
$stmt_pending->execute();
$pending_count = $stmt_pending->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
            background-color: transparent !important;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .totalQuantuty {
            position: absolute;
            right: 3%;
            display: flex;
            align-items: center;
        }

        .totalQuantuty .number {
            background-color: green;
            color: #fff;
            padding: 8px;
            font-weight: 500;
            border-radius: 5px;
            margin: 8px;
        }

        .btn {
            margin: 5px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1>Assign Table</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../admin//admin_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Assign</li>
        </ol>
        <hr />
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary"><strong>Assign</strong></h4>
                <div class="totalQuantuty">
                    <strong> Pending Assign Account: </strong>
                    <div class="number"><?php echo htmlspecialchars($pending_count); ?></div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Seller Name</th>
                                <th>Contact Number</th>
                                <th>Address</th>
                                <th>Bank Company</th>
                                <th>Bank Account</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Seller Name</th>
                                <th>Contact Number</th>
                                <th>Address</th>
                                <th>Bank Company</th>
                                <th>Bank Account</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($sellers as $seller): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($seller['id']); ?></td>
                                    <td><?php echo htmlspecialchars($seller['name']); ?></td>
                                    <td><?php echo htmlspecialchars($seller['contact_number']); ?></td>
                                    <td><?php echo htmlspecialchars($seller['address']); ?></td>
                                    <td><?php echo htmlspecialchars($seller['bank_company']); ?></td>
                                    <td><?php echo htmlspecialchars($seller['bank_account']); ?></td>
                                    <td><?php echo htmlspecialchars($seller['access']); ?></td>
                                    <td>
                                        <form class="status-form" method="post" action="update_seller_status.php">
                                            <input type="hidden" name="seller_id" value="<?php echo htmlspecialchars($seller['id']); ?>">
                                            <select name="access" onchange="submitForm(this)" required>
                                                <option value="pending" <?php echo ($seller['access'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="verify" <?php echo ($seller['access'] == 'verify') ? 'selected' : ''; ?>>Verify</option>
                                                <option value="rejected" <?php echo ($seller['access'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td><a href="assign_access.php?seller_id=<?php echo htmlspecialchars($seller['id']); ?>" class="btn btn-success btn-sm">View</a></td>
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

    <script>
        document.querySelectorAll('.status-form select').forEach(select => {
            select.addEventListener('change', function() {
                if (confirm('Are you sure you want to update the status?')) {
                    let form = this.closest('form');
                    if (form) {
                        let formData = new FormData(form);
                        fetch('update_seller_status.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.text())
                            .then(result => {
                                if (result === 'success') {
                                    window.location.reload();
                                } else {
                                    alert('Failed to update status.');
                                }
                            });
                    }
                }
            });
        });
    </script>
</body>

</html>