<?php
include '../resource/Database.php';

try {
    $db->beginTransaction();

    $sql = "UPDATE order_cust 
            SET Status = CASE 
                WHEN CURDATE() > EndDate THEN 'Finished'
                WHEN CURDATE() < StartDate THEN 'Inactive'
                ELSE 'Active'
            END";

    $statement = $db->prepare($sql);

    if ($statement->execute()) {
        //echo "Status updated successfully.";
    } else {
        //echo "Failed to update status: " . implode(":", $statement->errorInfo());
    }

    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    //echo "Failed to update status: " . $e->getMessage();
}
?>
