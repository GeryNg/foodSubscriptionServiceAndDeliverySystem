<?php include '../resource/Database.php'; ?>
<?php include '../resource/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="../css/order_history.css">
</head>
<body>
    <?php include '../partials/headers.php'; ?>   

    <div class="container">
        <div class="order-history-container">
            <?php
            // Fetch the user's order history, sorted in ascending order by Order_ID or OrderDate
            $cust_id = $_SESSION['Cust_ID']; // Assuming the user ID is stored in session
            $sql = "SELECT order_cust.Order_ID, order_cust.OrderDate, order_cust.Duration, 
                           order_cust.StartDate, order_cust.EndDate, order_cust.Quantity, 
                           order_cust.Status, order_cust.GrandTotal, plan.plan_name 
                    FROM order_cust 
                    INNER JOIN plan ON order_cust.Plan_ID = plan.id 
                    WHERE order_cust.Cust_ID = :cust_id 
                    ORDER BY order_cust.Order_ID ASC";  // Order by ascending order of Order_ID
            $statement = $db->prepare($sql);
            $statement->bindParam(':cust_id', $cust_id, PDO::PARAM_INT);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                while ($row = $statement->fetch()) {
                    $order_id = htmlspecialchars($row['Order_ID'], ENT_QUOTES, 'UTF-8');
                    $plan_name = htmlspecialchars($row['plan_name'], ENT_QUOTES, 'UTF-8');
                    $order_date = htmlspecialchars($row['OrderDate'], ENT_QUOTES, 'UTF-8');
                    $duration = htmlspecialchars($row['Duration'], ENT_QUOTES, 'UTF-8');
                    $start_date = htmlspecialchars($row['StartDate'], ENT_QUOTES, 'UTF-8');
                    $end_date = htmlspecialchars($row['EndDate'], ENT_QUOTES, 'UTF-8');
                    $quantity = htmlspecialchars($row['Quantity'], ENT_QUOTES, 'UTF-8');
                    $status = htmlspecialchars($row['Status'], ENT_QUOTES, 'UTF-8');
                    $grand_total = htmlspecialchars($row['GrandTotal'], ENT_QUOTES, 'UTF-8');

                    echo "<div class='order-card'>";
                    echo "<h3>Order #$order_id</h3>";
                    echo "<p><strong>Plan:</strong> $plan_name</p>";
                    echo "<p><strong>Order Date:</strong> $order_date</p>";
                    echo "<p><strong>Duration:</strong> $duration days ($start_date to $end_date)</p>";
                    echo "<p><strong>Quantity:</strong> $quantity</p>";
                    echo "<p><strong>Status:</strong> $status</p>";
                    echo "<p><strong>Grand Total:</strong> RM $grand_total</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No orders found.</p>";
            }
            ?>
        </div>
    </div>

    <?php include '../partials/footer.php'; ?>
</body>
</html>
