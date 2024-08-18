<?php
include '../resource/Database.php';
include '../resource/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging: Check if the POST data is received
    echo "<p>Received POST data:</p>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    if (isset($_POST['order_id']) && isset($_POST['grand_total'])) {
        $order_id = intval($_POST['order_id']);
        $grand_total = floatval($_POST['grand_total']);
        $payment_date = date('Y-m-d'); // Current date

        try {
            // Start transaction
            $db->beginTransaction();
            echo "<p>Transaction started successfully.</p>";

            // Insert payment record into the payment table
            $sql = "INSERT INTO payment (PaymentAmount, PaymentDate, Order_ID) 
                    VALUES (:paymentAmount, :paymentDate, :order_id)";
            $statement = $db->prepare($sql);
            $statement->bindParam(':paymentAmount', $grand_total, PDO::PARAM_STR);
            $statement->bindParam(':paymentDate', $payment_date, PDO::PARAM_STR);
            $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);

            if ($statement->execute()) {
                // Commit transaction
                $db->commit();
                echo "<p>Payment recorded successfully. Transaction committed.</p>";

                // Redirect to orders page after successful payment
                header('Location: ../order_delivery/order_history.php');
                exit();
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
?>
