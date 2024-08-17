<?php
$page_title = "Accept Link";
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];

    try {
        $query = "UPDATE link_requests SET status = 'accepted' WHERE id = :request_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "<script>
        swal({
          title: \"Success\",
          text: \"Your application has been submitted!\",
          icon: 'success',
        });
        setTimeout(function(){
        window.location.href = 'accept_link.php';
        }, 2000);
        </script>";
    } catch (PDOException $ex) {
        echo "An error occurred: " . $ex->getMessage();
    }
}
