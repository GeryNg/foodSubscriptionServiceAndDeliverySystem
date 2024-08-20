<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';
include_once '../partials/staff_nav.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    try {
        $db->beginTransaction();

        if ($action === 'accept') {
            // Accept action logic (if needed)
            $query = "UPDATE link_requests SET status = 'accepted' WHERE id = :request_id";
        } elseif ($action === 'reject') {
            // Get the user_id from the link_request
            $query = "SELECT user_id FROM link_requests WHERE id = :request_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new Exception('Link request not found');
            }

            $user_id = $result['user_id'];

            $query = "SELECT id AS seller_id FROM seller WHERE user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new Exception('Seller not found');
            }

            $seller_id = $result['seller_id'];

            // Update the access field in the seller table
            $query = "UPDATE seller SET access = 'rejected' WHERE id = :seller_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_INT);
            $stmt->execute();

            // Delete the link request
            $query = "DELETE FROM link_requests WHERE id = :request_id";
        } else {
            throw new Exception('Invalid action');
        }

        $stmt = $db->prepare($query);
        $stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
        $stmt->execute();

        $message = $action === 'accept' ? 'accepted' : 'rejected';
        $db->commit();

        echo "<script>
                swal({
                title: \"Success\",
                text: \"The link request has been $message!\",
                icon: 'success',
                button: \"OK\",
                });
                setTimeout(function(){
                window.location.href = '../profile_management/accept_link.php';
                }, 3000);
            </script>";
    } catch (PDOException $ex) {
        $db->rollBack();
        echo "<script>
                swal({
                title: \"Error\",
                text: \"Database error: " . addslashes($ex->getMessage()) . "\",
                icon: 'error',
                button: \"OK\",
                });
                setTimeout(function(){
                window.location.href = '../profile_management/accept_link.php';
                }, 3000);
            </script>";
    } catch (Exception $e) {
        $db->rollBack();
        echo "<script>
                swal({
                title: \"Error\",
                text: \"An error occurred: " . addslashes($e->getMessage()) . "\",
                icon: 'error',
                button: \"OK\",
                });
                setTimeout(function(){
                window.location.href = '../profile_management/accept_link.php';
                }, 3000);
            </script>";
    }
}
?>
