<?php
include_once '../resource/Database.php';

if (isset($_GET['id'])) {
    $plan_id = $_GET['id'];

    try {
        $stmt = $db->prepare("SELECT * FROM plan WHERE id = ?");
        $stmt->execute([$plan_id]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($plan) {
            $image_urls = explode(',', $plan['image_urls']);
            foreach ($image_urls as $image_url) {
                $image_path = '../plan/' . basename($image_url);
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $stmt = $db->prepare("DELETE FROM plan WHERE id = ?");
            $stmt->execute([$plan_id]);

            header("Location: ../plan_management/list_plan.php?success=Plan deleted successfully");
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
?>