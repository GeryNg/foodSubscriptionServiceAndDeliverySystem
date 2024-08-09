<?php
include_once '../resource/Database.php';

$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$response = array('announcements' => [], 'totalPages' => 0);

try {
    $stmt = $db->prepare("SELECT * FROM announcement ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $response['announcements'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmtTotal = $db->query("SELECT COUNT(*) FROM announcement");
    $totalAnnouncements = $stmtTotal->fetchColumn();
    $response['totalPages'] = ceil($totalAnnouncements / $limit);
    
} catch (PDOException $e) {
    $response['error'] = "Error fetching announcements: " . $e->getMessage();
}

echo json_encode($response);
