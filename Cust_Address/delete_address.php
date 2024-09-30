<?php
include '../resource/Database.php';
include '../resource/session.php';

if (isset($_GET['address_id']) && is_numeric($_GET['address_id'])) {
    $address_id = intval($_GET['address_id']);

    try {
        // Check if the address is associated with any existing orders
        $sql = "SELECT COUNT(*) FROM order_cust WHERE delivery_address_id = :address_id";
        $statement = $db->prepare($sql);
        $statement->bindParam(':address_id', $address_id, PDO::PARAM_INT);
        $statement->execute();
        $count = $statement->fetchColumn();

        if ($count > 0) {
            // If the address is associated with existing orders, prevent deletion and show an alert
            echo "<script>
                    alert('This address is associated with existing orders and cannot be deleted.');
                    window.location.href = 'address_management.php';
                  </script>";
        } else {
            // If not associated with any orders, proceed with deletion
            $sql = "DELETE FROM address WHERE address_id = :address_id";
            $statement = $db->prepare($sql);
            $statement->bindParam(':address_id', $address_id, PDO::PARAM_INT);

            if ($statement->execute()) {
                echo "<script>
                        alert('Address deleted successfully!');
                        window.location.href = 'address_management.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Failed to delete address.');
                        window.location.href = 'address_management.php';
                      </script>";
            }
        }
    } catch (Exception $e) {
        echo "<script>
                alert('An error occurred: " . addslashes($e->getMessage()) . "');
                window.location.href = 'address_management.php';
              </script>";
    }
} else {
    // If address_id is not set or not valid, show an error
    echo "<script>
            alert('Invalid address ID.');
            window.location.href = 'address_management.php';
          </script>";
}
?>
