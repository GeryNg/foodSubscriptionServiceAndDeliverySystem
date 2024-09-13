<head>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';
include_once '../partials/staff_nav.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['requestWithdrawBtn'])) {
    $seller_id = $_SESSION['seller_id'] ?? '';
    $withdraw_amount = floatval($_POST['withdraw_amount']);
    $platform_fee = $withdraw_amount * 0.06;  // 6% fee
    $amount_processed = $withdraw_amount - $platform_fee;

    // Validate if the seller has sufficient balance
    $sql = "SELECT balance FROM wallet WHERE seller_id = :seller_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmt->execute();
    $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $current_balance = $wallet['balance'] ?? 0.00;

    if ($withdraw_amount > 0 && $withdraw_amount <= $current_balance) {
        try {
            // Insert transaction into the database
            $sql_insert = "INSERT INTO `transaction` (status, amount, amountProcessed, transactionType, seller_id) 
                           VALUES ('Pending', :amount, :amountProcessed, 'Withdraw', :seller_id)";
            $stmt_insert = $db->prepare($sql_insert);
            $stmt_insert->bindParam(':amount', $withdraw_amount, PDO::PARAM_STR);
            $stmt_insert->bindParam(':amountProcessed', $amount_processed, PDO::PARAM_STR);
            $stmt_insert->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);

            if ($stmt_insert->execute()) {
                // Update the wallet balance
                $new_balance = $current_balance - $withdraw_amount;
                $sql_update = "UPDATE wallet SET balance = :new_balance WHERE seller_id = :seller_id";
                $stmt_update = $db->prepare($sql_update);
                $stmt_update->bindParam(':new_balance', $new_balance, PDO::PARAM_STR);
                $stmt_update->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
                $stmt_update->execute();

                // Display success SweetAlert
                echo "<script>
                swal({
                  title: \"Withdrawal requested!\",
                  text: \"Your withdrawal request for RM" . number_format($withdraw_amount, 2) . " has been submitted.\",
                  icon: 'success',
                  button: \"Got It!\",
                });
                setTimeout(function(){
                window.location.href = '../wallet/seller_wallet.php';
                }, 3000);
                </script>";
            } else {
                throw new Exception("Error inserting transaction.");
            }
        } catch (Exception $e) {
            echo "<script>
                    swal({
                        title: 'Error!',
                        text: 'Something went wrong: " . $e->getMessage() . "',
                        type: 'error'
                    });
                  </script>";
        }
    } else {
        echo "<script>
                swal({
                    title: 'Invalid Amount',
                    text: 'The withdraw amount must be greater than 0 and less than or equal to your balance.',
                    type: 'error'
                });
              </script>";
    }
}
?>
