<?php
$page_title = "Link Account";
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['company_id']) && !empty($_POST['company_id'])) {
        $company_id = filter_var($_POST['company_id'], FILTER_SANITIZE_NUMBER_INT);
        $user_id = $_SESSION['id'];

        try {
            $query = "SELECT id FROM seller WHERE id = :company_id AND requests_open = 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Insert a new link request
                $insertQuery = "INSERT INTO link_requests (user_id, seller_id, status) VALUES (:user_id, :company_id, 'pending')";
                $insertStmt = $db->prepare($insertQuery);
                $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
                $insertStmt->execute();

                echo "<script>
                swal({
                  title: \"Success\",
                  text: \"Your application has been submitted!\",
                  icon: 'success',
                });
                setTimeout(function(){
                window.location.href = 'seller_profile.php';
                }, 2000);
                </script>";
            } else {
                echo "<script>Swal.fire('Error', 'Invalid company or the request is not open.', 'error');</script>";//got problem
            }
        } catch (PDOException $ex) {
            echo "<script>Swal.fire('Error', 'An error occurred: " . addslashes($ex->getMessage()) . "', 'error');</script>";//got problem
        }
    } else {
        echo "<script>Swal.fire('Error', 'Company ID is missing.', 'error');</script>";//got problem
    }

    exit;
}
