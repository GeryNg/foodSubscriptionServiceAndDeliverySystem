<?php
include_once '../resource/Database.php';

function updatePlanStatuses($db)
{
    try {
        $today = date('Y-m-d');
        $sqlUpdateActive = "UPDATE plan SET status = 'active' WHERE date_from <= :today AND date_to >= :today";
        $stmtActive = $db->prepare($sqlUpdateActive);
        $stmtActive->execute([':today' => $today]);
        $sqlUpdateInactive = "UPDATE plan SET status = 'inactive' WHERE date_from > :today OR date_to < :today";
        $stmtInactive = $db->prepare($sqlUpdateInactive);
        $stmtInactive->execute([':today' => $today]);

    } catch (PDOException $e) {
        echo "Failed to update statuses: " . $e->getMessage();
    }
}

updatePlanStatuses($db);
?>

