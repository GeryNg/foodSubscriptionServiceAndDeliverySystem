<?php
include_once 'Database.php';
date_default_timezone_set('Asia/Kuala_Lumpur');

function updatePlanStatuses($db)
{
    try {
        $today = date('Y-m-d');

        // Update plan statuses based on the current date
        $sqlUpdateActive = "UPDATE plan SET status = 'active' WHERE date_from <= :today AND date_to >= :today";
        $stmtActive = $db->prepare($sqlUpdateActive);
        $stmtActive->execute([':today' => $today]);

        $sqlUpdateInactive = "UPDATE plan SET status = 'inactive' WHERE date_from > :today OR date_to < :today";
        $stmtInactive = $db->prepare($sqlUpdateInactive);
        $stmtInactive->execute([':today' => $today]);

        // Call function to generate and insert delivery IDs
        generateAndInsertDeliveryIDs($db);

        // Call function to create wallets for verified sellers
        createWalletForVerifiedSellers($db);

    } catch (PDOException $e) {
        echo "Failed to update statuses: " . $e->getMessage();
    }
}

function generateAndInsertDeliveryIDs($db)
{
    try {
        $today = date('Y-m-d');

        $orderQuery = "SELECT Order_ID, Plan_ID, delivery_address_id, Cust_ID FROM order_cust WHERE Status = 'Active'";
        $stmt = $db->prepare($orderQuery);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as $order) {
            $planQuery = "SELECT seller_id FROM plan WHERE id = :plan_id";
            $stmtPlan = $db->prepare($planQuery);
            $stmtPlan->execute([':plan_id' => $order['Plan_ID']]);
            $seller = $stmtPlan->fetch(PDO::FETCH_ASSOC);

            if ($seller) {
                $sellerId = $seller['seller_id'];
                $orderId = str_pad($order['Order_ID'], 4, '0', STR_PAD_LEFT);
                $newDeliveryId = date('Ymd') . $orderId;

                $checkQuery = "SELECT COUNT(*) FROM delivery WHERE delivery_id = :delivery_id";
                $stmtCheck = $db->prepare($checkQuery);
                $stmtCheck->execute([':delivery_id' => $newDeliveryId]);
                $count = $stmtCheck->fetchColumn();

                if ($count == 0) {
                    $insertDeliveryQuery = "INSERT INTO delivery (delivery_id, order_id, seller_id, delivery_date, address_id, cust_id) 
                                            VALUES (:delivery_id, :order_id, :seller_id, :date_created, :address_id, :cust_id)";
                    $stmtInsert = $db->prepare($insertDeliveryQuery);
                    $stmtInsert->execute([
                        ':delivery_id' => $newDeliveryId,
                        ':order_id' => $order['Order_ID'],
                        ':seller_id' => $sellerId,
                        ':date_created' => $today,
                        ':address_id' => $order['delivery_address_id'],
                        ':cust_id' => $order['Cust_ID']
                    ]);
                }
            } else {
                echo "Failed to find seller for plan_id: " . $order['Plan_ID'] . "<br>";
            }
        }

    } catch (PDOException $e) {
        echo "Failed to generate or insert delivery IDs: " . $e->getMessage();
    }
}

function createWalletForVerifiedSellers($db)
{
    try {
        $sellerQuery = "
            SELECT s.id AS seller_id
            FROM seller s
            LEFT JOIN wallet w ON s.id = w.seller_id
            WHERE s.access = 'verify' AND w.seller_id IS NULL
        ";
        $stmt = $db->prepare($sellerQuery);
        $stmt->execute();
        $sellersWithoutWallets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($sellersWithoutWallets as $seller) {
            $sellerId = $seller['seller_id'];

            $insertWalletQuery = "
                INSERT INTO wallet (balance, revenue, seller_id) 
                VALUES (0.00, 0.00, :seller_id)
            ";
            $stmtInsert = $db->prepare($insertWalletQuery);
            $stmtInsert->execute([':seller_id' => $sellerId]);

            //echo "Wallet created for seller: " . $sellerId . "<br>";
        }

    } catch (PDOException $e) {
        //echo "Failed to create wallet: " . $e->getMessage();
    }
}

updatePlanStatuses($db);
?>
