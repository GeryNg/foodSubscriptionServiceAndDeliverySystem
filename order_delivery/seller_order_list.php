<?php
$page_title = "Order list";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'];
$seller_access = $_SESSION['access'];

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

// Fetch Plan Detail
$query = "SELECT id, plan_name FROM plan WHERE seller_id = :seller_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':seller_id', $seller_id);
$stmt->execute();
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
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .totalQuantity {
            position: absolute;
            right: 3%;
            display: flex;
            align-items: center;
        }

        .totalQuantity .number {
            background-color: green;
            color: #fff;
            padding: 8px;
            font-weight: 500;
            border-radius: 5px;
            margin: 8px;
        }

        .toggle-btn-group {
            display: flex;
            margin-bottom: 10px;
        }

        .toggle-btn {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid #ccc;
            background-color: #f8f9fc;
        }

        .toggle-btn.active {
            background-color: #4e73df;
            color: white;
        }

        .meal-table {
            display: none;
        }

        .meal-table.active {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="margin-top: 20px;">
        <h1 class="h1 mb-2 text-gray-800" style="font-weight: 600;">Order List Table</h1>
        <hr />

        <div class="toggle-btn-group">
            <div class="toggle-btn active" data-meal="Lunch">Lunch</div>
            <div class="toggle-btn" data-meal="Dinner">Dinner</div>
        </div>

        <?php foreach ($plans as $plan): ?>
            <?php
            $meals = ['Lunch', 'Dinner'];
            foreach ($meals as $meal) {
                $query = "SELECT * FROM order_cust WHERE plan_id = :plan_id AND meal = :meal AND status = 'Active'";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':plan_id', $plan['id']);
                $stmt->bindParam(':meal', $meal);
                $stmt->execute();
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $totalQuantity = count($orders);
            ?>
                <div class="card shadow mb-4 meal-table <?php echo $meal === 'Lunch' ? 'active' : ''; ?>" data-meal="<?php echo $meal; ?>">
                    <div class="card-header py-3">
                        <h4 class="m-0 font-weight-bold text-primary"><strong><?php echo $plan['plan_name']; ?> - <?php echo $meal; ?> (Active)</strong></h4>
                        <div class="totalQuantity"><strong> Active Order Quantity: </strong>
                            <div class="number"><?php echo $totalQuantity; ?></div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered dataTable" id="dataTable_<?php echo $plan['id'] . '_' . $meal; ?>" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Order Date</th>
                                        <th>Quantity</th>
                                        <th>Cust_ID</th>
                                        <th>Instructions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Order Date</th>
                                        <th>Quantity</th>
                                        <th>Cust_ID</th>
                                        <th>Instructions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?php echo $order['Order_ID']; ?></td>
                                            <td><?php echo $order['OrderDate']; ?></td>
                                            <td><?php echo $order['Quantity']; ?></td>
                                            <td><?php echo $order['Cust_ID']; ?></td>
                                            <td><?php echo $order['instructions']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button class="btn btn-primary generate-receipt" data-plan-id="<?php echo $plan['id']; ?>" data-meal="<?php echo $meal; ?>">Generate Receipt</button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php endforeach; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary"><strong>All Orders</strong></h4>
                <button id="toggleMonthOrders" class="btn btn-primary" style="margin-left: 20px;">This Month's Orders</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTableAllOrders" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Meal</th>
                                <th>Status</th>
                                <th>Quantity</th>
                                <th>Cust_ID</th>
                                <th>Grand Total (RM)</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Meal</th>
                                <th>Status</th>
                                <th>Quantity</th>
                                <th>Cust_ID</th>
                                <th>Grand Total (RM)</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            $totalGrandTotal = 0;
                            $query = "SELECT * FROM order_cust WHERE plan_id IN (SELECT id FROM plan WHERE seller_id = :seller_id)";
                            $stmt = $db->prepare($query);
                            $stmt->bindParam(':seller_id', $seller_id);
                            $stmt->execute();
                            $allOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($allOrders as $order):
                                $totalGrandTotal += $order['GrandTotal'];
                            ?>
                                <tr>
                                    <td><?php echo $order['Order_ID']; ?></td>
                                    <td><?php echo $order['OrderDate']; ?></td>
                                    <td><?php echo $order['StartDate']; ?></td>
                                    <td><?php echo $order['EndDate']; ?></td>
                                    <td><?php echo $order['Meal']; ?></td>
                                    <td><?php echo $order['Status']; ?></td>
                                    <td><?php echo $order['Quantity']; ?></td>
                                    <td><?php echo $order['Cust_ID']; ?></td>
                                    <td><?php echo $order['GrandTotal']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" class="text-right"><strong>Total Grand Total (RM):</strong></td>
                                <td><strong><?php echo $totalGrandTotal; ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        //Table format fomr https://datatables.net/
        $(document).ready(function() {
            <?php foreach ($plans as $plan): ?>
                <?php foreach ($meals as $meal): ?>
                    $('#dataTable_<?php echo $plan['id'] . '_' . $meal; ?>').DataTable();
                <?php endforeach; ?>
            <?php endforeach; ?>

            $('#dataTableAllOrders').DataTable();

            $('.toggle-btn').on('click', function() {
                var meal = $(this).data('meal');

                $('.toggle-btn').removeClass('active');
                $(this).addClass('active');

                $('.meal-table').removeClass('active');
                $('.meal-table[data-meal="' + meal + '"]').addClass('active');
            });
        });

        //Toggle Button
        $(document).ready(function() {
            var dataTable = $('#dataTableAllOrders').DataTable();

            $('#toggleMonthOrders').on('click', function() {
                var date = new Date();
                var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
                var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

                dataTable.columns(1).search('').draw();

                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var orderDate = new Date(data[1]);
                    if (orderDate >= firstDay && orderDate <= lastDay) {
                        return true;
                    }
                    return false;
                });

                dataTable.draw();
            });
        });

        //Print receipt Function
        $(document).ready(function() {
            $('.generate-receipt').on('click', function() {
                var planId = $(this).data('plan-id');
                var meal = $(this).data('meal');
                $.ajax({
                    url: 'generate_receipt.php',
                    type: 'POST',
                    data: {
                        plan_id: planId,
                        meal: meal
                    },
                    success: function(response) {
                        var receiptWindow = window.open('', '_blank');
                        receiptWindow.document.write(response);
                        receiptWindow.document.close();
                        receiptWindow.print();
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred while generating the receipt.');
                    }
                });
            });
        });
    </script>
</body>

</html>