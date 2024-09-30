<?php
include '../resource/Database.php';
include '../resource/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plan_id = intval($_POST['plan_id']);
    $quantity = intval($_POST['quantity']);
    $meal = $_POST['meal'];
    $delivery_address_id = $_SESSION['selected_address_id'];
    $instructions = $_POST['instructions'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $addon_quantities = $_POST['addon_quantity'] ?? [];

    // Fetch the price of the plan
    $sql = "SELECT price FROM plan WHERE id = :plan_id";
    $statement = $db->prepare($sql);
    $statement->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
    $statement->execute();
    $plan = $statement->fetch();

    if ($plan) {
        $price = $plan['price'];
        $duration = (new DateTime($end_date))->diff(new DateTime($start_date))->days + 1;

        $grandTotal = $price * $quantity * $duration;

        $sql = "INSERT INTO order_cust (OrderDate, GrandTotal, Status, Meal, Duration, StartDate, EndDate, Quantity, Cust_ID, Plan_ID, delivery_address_id, instructions) 
                VALUES (NOW(), :grandTotal, 'Inactive', :meal, :duration, :start_date, :end_date, :quantity, :cust_id, :plan_id, :delivery_address_id, :instructions)";
        $statement = $db->prepare($sql);
        $statement->bindParam(':grandTotal', $grandTotal, PDO::PARAM_STR);
        $statement->bindParam(':meal', $meal, PDO::PARAM_STR);
        $statement->bindParam(':duration', $duration, PDO::PARAM_INT);
        $statement->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $statement->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $statement->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $statement->bindParam(':cust_id', $_SESSION['Cust_ID'], PDO::PARAM_STR);
        $statement->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
        $statement->bindParam(':delivery_address_id', $delivery_address_id, PDO::PARAM_INT);
        $statement->bindParam(':instructions', $instructions, PDO::PARAM_STR);

        if ($statement->execute()) {
            $order_id = $db->lastInsertId();

            if (!empty($addon_quantities)) {
                $addon_sql = "INSERT INTO order_addon (order_id, addon_id, addon_quantity) VALUES (:order_id, :addon_id, :addon_quantity)";
                $addon_statement = $db->prepare($addon_sql);

                foreach ($addon_quantities as $addon_id => $addon_quantity) {
                    if ($addon_quantity > 0) {
                        $addon_statement->execute([
                            ':order_id' => $order_id,
                            ':addon_id' => $addon_id,
                            ':addon_quantity' => $addon_quantity
                        ]);
                    }
                }
            }

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
?>
