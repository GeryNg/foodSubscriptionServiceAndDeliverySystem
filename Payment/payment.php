<?php include '../resource/Database.php'; ?>
<?php include '../resource/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm and Pay</title>
    <link rel="stylesheet" href="../css/payment.css">
</head>
<body>
    <?php include '../partials/headers.php'; ?>
    <div class="payment-container">
        <h1>Confirm Your Order</h1>

        <?php
        if (isset($_GET['order_id'])) {
            $order_id = intval($_GET['order_id']);

            // Fetch order details
            $sql = "SELECT oc.*, p.plan_name, p.price FROM order_cust oc 
                    JOIN plan p ON oc.Plan_ID = p.id 
                    WHERE oc.Order_ID = :order_id";
            $statement = $db->prepare($sql);
            $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $statement->execute();
            $order = $statement->fetch();

            if ($order) {
                $planName = htmlspecialchars($order['plan_name'], ENT_QUOTES, 'UTF-8');
                $quantity = $order['Quantity'];
                $duration = $order['Duration'];
                $meal = htmlspecialchars($order['Meal'], ENT_QUOTES, 'UTF-8'); 
                $grandTotal = number_format($order['GrandTotal'], 2);

                echo "<p><strong>Plan:</strong> $planName</p>";
                echo "<p><strong>Quantity:</strong> $quantity</p>";
                echo "<p><strong>Meal:</strong> $meal</p>"; 
                echo "<p><strong>Start Date:</strong> {$order['StartDate']}</p>";
                echo "<p><strong>End Date:</strong> {$order['EndDate']}</p>";
                echo "<p><strong>Duration:</strong> $duration days</p>";
                echo "<p><strong>Grand Total:</strong> RM $grandTotal</p>";

                // PayPal payment button
                echo "<div id='paypal-button-container'></div>";
                echo "<form id='orderDetails' action='store_payment.php' method='POST' style='display: none;'>";
                echo "<input type='hidden' name='order_id' value='$order_id'>";
                echo "<input type='hidden' name='grand_total' value='$grandTotal'>";
                echo "</form>";
            } else {
                echo "<p>Order not found.</p>";
            }
        } else {
            echo "<p>Invalid request.</p>";
        }
        ?>
    </div>
    <?php include '../partials/footer.php'; ?>

    <!-- Load the PayPal script -->
    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_API_KEY&currency=MYR"></script>
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $grandTotal; ?>'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                    document.getElementById('orderDetails').submit();
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
