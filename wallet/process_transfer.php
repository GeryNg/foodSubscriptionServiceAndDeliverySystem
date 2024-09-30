<?php
include_once '../resource/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = $_POST['transaction_id'];

    try {
        // Validate the transaction ID and check that it's pending
        $sql = "SELECT id, amount, seller_id FROM transaction WHERE id = :transaction_id AND transactionType = 'Withdraw' AND status = 'Pending'";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':transaction_id', $transaction_id, PDO::PARAM_INT);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            $amount = $transaction['amount'];  // Use 'amount' instead of 'amountProcessed'
            $seller_id = $transaction['seller_id'];

            // Update the transaction status to 'Successful'
            $sql_update = "UPDATE transaction SET status = 'Successful' WHERE id = :transaction_id";
            $stmt_update = $db->prepare($sql_update);
            $stmt_update->bindParam(':transaction_id', $transaction_id, PDO::PARAM_INT);
            $stmt_update->execute();

            // Fetch the seller's current wallet balance
            $sql_wallet = "SELECT balance FROM wallet WHERE seller_id = :seller_id";
            $stmt_wallet = $db->prepare($sql_wallet);
            $stmt_wallet->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
            $stmt_wallet->execute();
            $wallet = $stmt_wallet->fetch(PDO::FETCH_ASSOC);

            if ($wallet) {
                // Subtract the 'amount' from the wallet balance
                $new_balance = $wallet['balance'] - $amount;

                // Update the wallet with the new balance
                $sql_update_wallet = "UPDATE wallet SET balance = :new_balance WHERE seller_id = :seller_id";
                $stmt_update_wallet = $db->prepare($sql_update_wallet);
                $stmt_update_wallet->bindParam(':new_balance', $new_balance, PDO::PARAM_STR);
                $stmt_update_wallet->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
                $stmt_update_wallet->execute();

                echo json_encode(['status' => 'success', 'message' => 'Payout completed successfully, and wallet balance updated.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Seller wallet not found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Transaction not found or already processed.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
