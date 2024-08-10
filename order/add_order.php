<?php
include '../resource/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plan_id = $_POST['plan_id'];
    $pax = $_POST['pax'];
    $delivery_time = $_POST['delivery_time'];
    $delivery_date = $_POST['delivery_date'];
    $remarks = $_POST['remarks'];

    // Fetch plan price
    $sql = "SELECT price FROM plan WHERE id = :plan_id";
    $statement = $db->prepare($sql);
    $statement->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
    $statement->execute();
    $plan = $statement->fetch();

    if ($plan) {
        $price = $plan['price'];

        // Calculate the grand total
        $grandTotal = $price * $pax;

        // Insert order into order_cust table
        $sql = "INSERT INTO order_cust (OrderDate, GrandTotal, Status, Duration, StartDate, EndDate, Quantity, Cust_ID, Plan_ID, delivery_address_id, instructions) 
                VALUES (NOW(), :grandTotal, 'Active', 5, :startDate, :endDate, :pax, 1, :plan_id, 1, :remarks)";
        $statement = $db->prepare($sql);
        $statement->bindParam(':grandTotal', $grandTotal, PDO::PARAM_STR);
        $statement->bindParam(':startDate', $delivery_date, PDO::PARAM_STR);
        $statement->bindParam(':endDate', $delivery_date, PDO::PARAM_STR);
        $statement->bindParam(':pax', $pax, PDO::PARAM_INT);
        $statement->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
        $statement->bindParam(':remarks', $remarks, PDO::PARAM_STR);
        $statement->execute();

        header('Location: success.php');
        exit();
    } else {
        echo "Plan not found.";
    }
} else {
    echo "Invalid request.";
}
?>
