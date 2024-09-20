<?php
$page_title = "Accept Link";
include_once '../partials/staff_nav.php';
include_once '../resource/session.php';
include_once '../resource/Database.php';

$seller_access = $_SESSION['access'];
$seller_id = $_SESSION['seller_id'];
$user_id = $_SESSION['id'];

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}


try {
    $query = "
        SELECT lr.id AS request_id, u.avatar, u.username, u.email, u.join_date, lr.linked_date 
        FROM link_requests lr
        JOIN users u ON lr.user_id = u.id
        WHERE lr.seller_id = :seller_id AND lr.status = 'pending'
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_STR_CHAR);
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "An error occurred: " . $ex->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="margin-top: 20px;">
        <h1 class="h1 mb-2 text-gray-800" style="font-weight: 600;">Apply Request List</h1>
        <hr />
        <div class="card shadow mb-4">
            <div class="card-header py-3"></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Join Date</th>
                                <th>Linked_date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Avatar</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Join Date</th>
                                <th>Linked_date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($request['avatar']); ?>" alt="Avatar" width="60">
                                    </td>
                                    <td><?php echo htmlspecialchars($request['username']); ?></td>
                                    <td><?php echo htmlspecialchars($request['email']); ?></td>
                                    <td><?php echo htmlspecialchars($request['join_date']); ?></td>
                                    <td><?php echo htmlspecialchars($request['linked_date']); ?></td>
                                    <td>
                                        <form method="post" action="process_link.php" style="display:inline;">
                                            <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['request_id']); ?>">
                                            <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                        </form>
                                        <form method="post" action="process_link.php" style="display:inline;">
                                            <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['request_id']); ?>">
                                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../js/demo/datatables-demo.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
</body>

</html>