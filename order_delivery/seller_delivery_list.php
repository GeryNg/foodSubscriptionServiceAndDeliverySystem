<?php
$page_title = "Delivery List";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';

$seller_access = $_SESSION['access'];

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
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
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .totalQuantuty{
            position: absolute;
            right: 3%;
            display: flex;
            align-items: center;
        }
        .totalQuantuty .number{
            background-color: green;
            color: #fff;
            padding: 8px;
            font-weight: 500;
            border-radius: 5px;
            margin: 8px;
        }
    </style>
</head>
<body>
<div class="container-fluid" style="margin-top: 20px;">
        <h1 class="h1 mb-2 text-gray-800" style="font-weight: 600;">Delivery List Table</h1><hr/>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary"><strong>Delivery</strong></h6>
                <div class="totalQuantuty"><strong>Pending Delivery Item: </strong><div class="number">32</div></div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Duration</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Cust_ID</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Duration</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Cust_ID</th>
                            </tr>
                        </tfoot>
                        <tbody>
                                <tr>
                                    <td>003</td>
                                    <td>06/08/2024</td>
                                    <td>06/08/2024</td>
                                    <td>31/08/2024</td>
                                    <td>25</td>
                                    <td>2</td>
                                    <td>Active</td>
                                    <td>0024</td>
                                </tr>
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
</body>
</html>