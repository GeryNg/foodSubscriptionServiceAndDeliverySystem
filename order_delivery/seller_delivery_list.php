<?php
$page_title = "Delivery List";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_access = $_SESSION['access'];
$seller_id = $_SESSION['seller_id'];
$today = date('Y-m-d');

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

// Query to fetch Lunch deliveries
$query_lunch = "
    SELECT 
        d.delivery_id,
        d.order_id,
        d.cust_id,
        c.Name AS customer_name,
        p.id AS plan_id,
        p.plan_name,
        o.Quantity,
        CONCAT(a.line1, ', ', a.line2, ', ', a.city, ', ', a.state, ', ', a.postal_code, ', ', a.country) AS full_address,
        d.status
    FROM 
        delivery d
    INNER JOIN 
        order_cust o ON d.order_id = o.Order_ID
    INNER JOIN 
        customer c ON d.cust_id = c.Cust_ID
    INNER JOIN 
        plan p ON o.Plan_ID = p.id
    INNER JOIN 
        address a ON d.address_id = a.address_id
    WHERE 
        d.delivery_date = :today AND d.seller_id = :seller_id AND o.Meal = 'Lunch'
";
$stmt_lunch = $db->prepare($query_lunch);
$stmt_lunch->bindParam(':today', $today);
$stmt_lunch->bindParam(':seller_id', $seller_id);
$stmt_lunch->execute();
$lunch_deliveries = $stmt_lunch->fetchAll(PDO::FETCH_ASSOC);

// Query to fetch Dinner deliveries
$query_dinner = "
    SELECT 
        d.delivery_id,
        d.order_id,
        d.cust_id,
        c.Name AS customer_name,
        p.id AS plan_id,
        p.plan_name,
        o.Quantity,
        CONCAT(a.line1, ', ', a.line2, ', ', a.city, ', ', a.state, ', ', a.postal_code, ', ', a.country) AS full_address,
        d.status
    FROM 
        delivery d
    INNER JOIN 
        order_cust o ON d.order_id = o.Order_ID
    INNER JOIN 
        customer c ON d.cust_id = c.Cust_ID
    INNER JOIN 
        plan p ON o.Plan_ID = p.id
    INNER JOIN 
        address a ON d.address_id = a.address_id
    WHERE 
        d.delivery_date = :today AND d.seller_id = :seller_id AND o.Meal = 'Dinner'
";
$stmt_dinner = $db->prepare($query_dinner);
$stmt_dinner->bindParam(':today', $today);
$stmt_dinner->bindParam(':seller_id', $seller_id);
$stmt_dinner->execute();
$dinner_deliveries = $stmt_dinner->fetchAll(PDO::FETCH_ASSOC);

$on_delivery_lunch_count = count(array_filter($lunch_deliveries, function ($delivery) {
    return $delivery['status'] === 'on delivery';
}));

$on_delivery_dinner_count = count(array_filter($dinner_deliveries, function ($delivery) {
    return $delivery['status'] === 'on delivery';
}));
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

        .btn-group {
            display: flex;
            width: 100%;

        }

        .button {
            background-color: #5C67F2;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            text-transform: uppercase;
            cursor: pointer;
            margin: 10px 5px 10px 5px;
            font-weight: 900;
        }

        .button1 {
            background-color: #e67300;
        }

        .button2 {
            background-color: #005ce6;
        }

        .button3 {
            background-color: #29a329;
        }

        @media only screen and (max-width: 500px) {
            h4 {
                font-size: 15px;
            }

            .totalQuantuty {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="margin-top: 20px;">
        <h1 class="h1 mb-2 text-gray-800" style="font-weight: 600;">Delivery List Table</h1>
        <hr />
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary"><strong>Lunch Section</strong></h4>
                <div class="totalQuantuty"><strong>Pending Delivery Item: </strong>
                    <div class="number"><?php echo $on_delivery_lunch_count; ?></div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable LunchTable" id="lunchTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Delivery ID</th>
                                <th>Order ID</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Plan ID</th>
                                <th>Plan Name</th>
                                <th>Quantity</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Delivery ID</th>
                                <th>Order ID</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Plan ID</th>
                                <th>Plan Name</th>
                                <th>Quantity</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($lunch_deliveries as $delivery): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($delivery['delivery_id']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['order_id']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['cust_id']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['plan_id']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['plan_name']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['Quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['full_address']); ?></td>
                                    <td>
                                        <form method="post" action="update_delivery_status.php">
                                            <input type="hidden" name="delivery_id" value="<?php echo htmlspecialchars($delivery['delivery_id']); ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="order accepted" <?php echo ($delivery['status'] === 'order accepted') ? 'selected' : ''; ?>>Order Accepted</option>
                                                <option value="food preparing" <?php echo ($delivery['status'] === 'food preparing') ? 'selected' : ''; ?>>Food Preparing</option>
                                                <option value="on delivery" <?php echo ($delivery['status'] === 'on delivery') ? 'selected' : ''; ?>>On Delivery</option>
                                                <option value="done delivery" <?php echo ($delivery['status'] === 'done delivery') ? 'selected' : ''; ?>>Done Delivery</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br />
                    <div class="btn_group">
                        <button class="button button1" id="button1">Set All On Prepare</button>
                        <button class="button button2" id="button2">Set All On Delivery</button>
                        <button class="button button3" id="button3">Set All Done Delivery</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary"><strong>Dinner Section</strong></h4>
                <div class="totalQuantuty"><strong>Pending Delivery Item: </strong>
                    <div class="number"><?php echo $on_delivery_dinner_count; ?></div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable DinnerTable" id="dinnerTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Delivery ID</th>
                                <th>Order ID</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Plan ID</th>
                                <th>Plan Name</th>
                                <th>Quantity</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Delivery ID</th>
                                <th>Order ID</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Plan ID</th>
                                <th>Plan Name</th>
                                <th>Quantity</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($dinner_deliveries as $delivery): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($delivery['delivery_id']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['order_id']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['cust_id']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['plan_id']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['plan_name']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['Quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery['full_address']); ?></td>
                                    <td>
                                        <form method="post" action="update_delivery_status.php">
                                            <input type="hidden" name="delivery_id" value="<?php echo htmlspecialchars($delivery['delivery_id']); ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="order accepted" <?php echo ($delivery['status'] === 'order accepted') ? 'selected' : ''; ?>>Order Accepted</option>
                                                <option value="food preparing" <?php echo ($delivery['status'] === 'food preparing') ? 'selected' : ''; ?>>Food Preparing</option>
                                                <option value="on delivery" <?php echo ($delivery['status'] === 'on delivery') ? 'selected' : ''; ?>>On Delivery</option>
                                                <option value="done delivery" <?php echo ($delivery['status'] === 'done delivery') ? 'selected' : ''; ?>>Done Delivery</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br />
                    <div class="btn_group">
                        <button class="button button1" id="button4">Set All On Prepare</button>
                        <button class="button button2" id="button5">Set All On Delivery</button>
                        <button class="button button3" id="button6">Set All Done Delivery</button>
                    </div>
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
    <script>
        //Format for table
        $(document).ready(function() {
            $('#lunchTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });

            $('#dinnerTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });

        //Update the delivery status
        document.addEventListener('DOMContentLoaded', function() {
            function updateDeliveryStatuses(status, meal) {
                const deliveryIds = [];
                document.querySelectorAll(`.${meal}Table tbody tr`).forEach(row => {
                    const deliveryId = row.querySelector('td').innerText;
                    deliveryIds.push(deliveryId);
                });

                fetch('update_delivery_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            deliveryIds: deliveryIds,
                            status: status,
                            meal: meal
                        })
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result === 'success') {
                            window.location.reload();
                        } else {
                            alert('Failed to update statuses.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the statuses.');
                    });
            }

            document.getElementById('button1').addEventListener('click', function() {
                updateDeliveryStatuses('food preparing', 'Lunch');
            });

            document.getElementById('button2').addEventListener('click', function() {
                updateDeliveryStatuses('on delivery', 'Lunch');
            });

            document.getElementById('button3').addEventListener('click', function() {
                updateDeliveryStatuses('done delivery', 'Lunch');
            });

            document.getElementById('button4').addEventListener('click', function() {
                updateDeliveryStatuses('food preparing', 'Dinner');
            });

            document.getElementById('button5').addEventListener('click', function() {
                updateDeliveryStatuses('on delivery', 'Dinner');
            });

            document.getElementById('button6').addEventListener('click', function() {
                updateDeliveryStatuses('done delivery', 'Dinner');
            });
        });
    </script>
</body>

</html>