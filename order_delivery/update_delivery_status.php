<?php
include_once '../resource/Database.php';

$data = json_decode(file_get_contents('php://input'), true);
$deliveryIds = $data['deliveryIds'];
$status = $data['status'];
$meal = $data['meal'];

if (empty($deliveryIds) || !in_array($status, ['order accepted', 'food preparing', 'on delivery', 'done delivery']) || !in_array($meal, ['Lunch', 'Dinner'])) {
    echo 'Invalid data';
    exit;
}

$placeholders = implode(',', array_fill(0, count($deliveryIds), '?'));
$mealCondition = ($meal === 'Lunch') ? 'o.Meal = "Lunch"' : 'o.Meal = "Dinner"';
$query = "
    UPDATE delivery d
    INNER JOIN order_cust o ON d.order_id = o.Order_ID
    SET d.status = ?
    WHERE d.delivery_id IN ($placeholders) AND $mealCondition
";
$params = array_merge([$status], $deliveryIds);

try {
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    echo 'success';
} catch (PDOException $e) {
    echo 'error';
}
?>
