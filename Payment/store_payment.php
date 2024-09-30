<?php
include '../resource/Database.php';
include '../resource/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['order_id']) && isset($_POST['grand_total'])) {
        $order_id = intval($_POST['order_id']);
        $grand_total = floatval($_POST['grand_total']);
        $payment_date = date('Y-m-d'); // Current date

        try {
            // Start transaction
            $db->beginTransaction();

            // Insert payment record into the payment table
            $sql = "INSERT INTO payment (PaymentAmount, PaymentDate, Order_ID) 
                    VALUES (:paymentAmount, :paymentDate, :order_id)";
            $statement = $db->prepare($sql);
            $statement->bindParam(':paymentAmount', $grand_total, PDO::PARAM_STR);
            $statement->bindParam(':paymentDate', $payment_date, PDO::PARAM_STR);
            $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);

            if ($statement->execute()) {
                // Fetch the Plan_ID from the order
                $sql = "SELECT Plan_ID FROM order_cust WHERE Order_ID = :order_id";
                $statement = $db->prepare($sql);
                $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $statement->execute();
                $order = $statement->fetch();

                if ($order) {
                    $plan_id = $order['Plan_ID'];

                    // Fetch the seller_id from the plan table
                    $sql = "SELECT seller_id FROM plan WHERE id = :plan_id";
                    $statement = $db->prepare($sql);
                    $statement->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
                    $statement->execute();
                    $plan = $statement->fetch();

                    if ($plan) {
                        $seller_id = $plan['seller_id'];

                        // Update the wallet's revenue and balance
                        $sql = "UPDATE wallet SET 
                                balance = balance + :paymentAmount, 
                                revenue = revenue + :paymentAmount 
                                WHERE seller_id = :seller_id";
                        $statement = $db->prepare($sql);
                        $statement->bindParam(':paymentAmount', $grand_total, PDO::PARAM_STR);
                        $statement->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);

                        if ($statement->execute()) {
                            // Commit the transaction
                            $db->commit();
                            // Redirect to order history page
                            header('Location: ../order_delivery/order_history.php');
                            exit();
                        } else {
                            // Rollback transaction in case of failure
                            $db->rollBack();
                            echo "<p>Failed to update wallet. Transaction rolled back.</p>";
                        }
                    } else {
                        // Rollback transaction if no seller_id found
                        $db->rollBack();
                        echo "<p>Seller not found for the given plan. Transaction rolled back.</p>";
                    }
                } else {
                    // Rollback transaction if no order found
                    $db->rollBack();
                    echo "<p>Order not found. Transaction rolled back.</p>";
                }
            } else {
                // Rollback transaction in case of failure
                $db->rollBack();
                echo "<p>Failed to execute payment insertion. Transaction rolled back.</p>";
            }
        } catch (Exception $e) {
            // Rollback transaction in case of an exception
            $db->rollBack();
            echo "<p>An error occurred during the transaction. Transaction rolled back.</p>";
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Invalid request. Order ID or Grand Total is missing.</p>";
    }
} else {
    echo "<p>Invalid request method. Only POST requests are allowed.</p>";
}
