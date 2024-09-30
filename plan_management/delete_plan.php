<?php
include_once '../resource/Database.php';

if (isset($_GET['id'])) {
    $plan_id = $_GET['id'];

    try {
        $stmt = $db->prepare("SELECT * FROM plan WHERE id = ?");
        $stmt->execute([$plan_id]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($plan) {
            $stmt = $db->prepare("UPDATE plan SET status = 'deleted' WHERE id = ?");
            $stmt->execute([$plan_id]);

            header("Location: ../plan_management/list_plan.php?success=Plan marked as deleted successfully");
            exit;
        } else {
            header("Location: ../plan_management/list_plan.php?error=Plan not found");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: ../plan_management/list_plan.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: ../plan_management/list_plan.php?error=No plan ID provided");
    exit;
}
