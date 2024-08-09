<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$user_id = $_SESSION['id'];
$query = "SELECT join_date FROM users WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$userJoinDate = $stmt->fetch(PDO::FETCH_ASSOC)['join_date'];

$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    $stmt = $db->prepare("
        SELECT * FROM announcement 
        WHERE date >= :join_date 
        ORDER BY id DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':join_date', $userJoinDate);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmtTotal = $db->prepare("
        SELECT COUNT(*) FROM announcement 
        WHERE date >= :join_date
    ");
    $stmtTotal->bindValue(':join_date', $userJoinDate);
    $stmtTotal->execute();
    $totalAnnouncements = $stmtTotal->fetchColumn();
    $totalPages = ceil($totalAnnouncements / $limit);
    
    echo json_encode([
        'announcements' => $announcements,
        'totalPages' => $totalPages
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'error' => "Error fetching announcements: " . $e->getMessage()
    ]);
}
?>
