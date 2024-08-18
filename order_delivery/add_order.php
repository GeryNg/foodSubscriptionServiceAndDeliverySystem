<?php
include '../resource/Database.php';
include '../resource/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plan_id = intval($_POST['plan_id']);
    $quantity = intval($_POST['quantity']);
    $delivery_address_id = intval($_POST['delivery_address_id']);
    $instructions = $_POST['instructions'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Fetch the price of the plan
    $sql = "SELECT price FROM plan WHERE id = :plan_id";
    $statement = $db->prepare($sql);
    $statement->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
    $statement->execute();
    $plan = $statement->fetch();

    if ($plan) {
        $price = $plan['price'];
        $duration = (new DateTime($end_date))->diff(new DateTime($start_date))->days + 1;

        // Calculate the grand total
        $grandTotal = $price * $quantity * $duration;

        // Insert the order into the order_cust table
        $sql = "INSERT INTO order_cust (OrderDate, GrandTotal, Status, Duration, StartDate, EndDate, Quantity, Cust_ID, Plan_ID, delivery_address_id, instructions) 
                VALUES (NOW(), :grandTotal, 'Active', :duration, :start_date, :end_date, :quantity, :cust_id, :plan_id, :delivery_address_id, :instructions)";
        $statement = $db->prepare($sql);
        $statement->bindParam(':grandTotal', $grandTotal, PDO::PARAM_STR);
        $statement->bindParam(':duration', $duration, PDO::PARAM_INT);
        $statement->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $statement->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $statement->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $statement->bindParam(':cust_id', $_SESSION['Cust_ID'], PDO::PARAM_INT);
        $statement->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
        $statement->bindParam(':delivery_address_id', $delivery_address_id, PDO::PARAM_INT);
        $statement->bindParam(':instructions', $instructions, PDO::PARAM_STR);

        if ($statement->execute()) {
            // Get the last inserted order ID
            $order_id = $db->lastInsertId();

            // Redirect to payment page with order ID
            header("Location: ../Payment/payment.php?order_id=$order_id");
            exit();
        } else {
            echo "<script>alert('Failed to place order. Please try again.'); window.location.href = 'orders.php';</script>";
        }
    } else {
        echo "<script>alert('Plan not found. Please try again.'); window.location.href = 'orders.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request. Please try again.'); window.location.href = 'orders.php';</script>";
}
