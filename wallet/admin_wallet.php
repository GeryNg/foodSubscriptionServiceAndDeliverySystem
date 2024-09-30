<?php
$page_title = "Wallet Withdraw";
include_once '../partials/admin_nav.php';
include_once '../resource/Database.php';

try {
    $sql = "SELECT id, amount, amountProcessed, datetime, status, seller_id FROM transaction WHERE transactionType = 'Withdraw' AND status = 'Pending'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sqlPending = "SELECT COUNT(*) AS pending_count FROM transaction WHERE transactionType = 'Withdraw' AND status = 'Pending'";
    $stmtPending = $db->prepare($sqlPending);
    $stmtPending->execute();
    $pending_count = $stmtPending->fetch(PDO::FETCH_ASSOC)['pending_count'] ?? 0;
} catch (PDOException $e) {
    echo "Error fetching transactions: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_API_KEY&currency=MYR"></script>
    <style>
        .container-fluid {
            margin-bottom: 5%;
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            margin: 3rem 0 0.5rem 0;
            font-weight: 800;
            line-height: 1.2;
        }

        .breadcrumb {
            background-color: transparent !important;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .totalQuantuty {
            position: absolute;
            right: 3%;
            display: flex;
            align-items: center;
        }

        .totalQuantuty .number {
            background-color: green;
            color: #fff;
            padding: 8px;
            font-weight: 500;
            border-radius: 5px;
            margin: 8px;
        }

        .btn {
            margin: 5px;
        }

        #paypal-button-container {
            display: none;
        }

        /* Styling for the transaction detail display */
        #transaction-detail-container {
            display: none;
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8f9fc;
        }

        #transaction-detail-container h3 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        #transaction-detail-container p {
            margin: 5px 0;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1>Wallet Withdraw</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../admin/admin_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Wallet Withdraw</li>
        </ol>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary"><strong>Wallet Transfer</strong></h4>
                <div class="totalQuantuty">
                    <strong>Pending Wallet Transfer:</strong>
                    <div class="number"><?php echo htmlspecialchars($pending_count); ?></div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Amount</th>
                                <th>Amount Processed</th>
                                <th>Date Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Amount</th>
                                <th>Amount Processed</th>
                                <th>Date Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($transaction['amount'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($transaction['amountProcessed'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars(date("Y-m-d H:i:s", strtotime($transaction['datetime']))); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['status']); ?></td>
                                    <td>
                                        <button
                                            data-transaction-id="<?php echo htmlspecialchars($transaction['id']); ?>"
                                            data-amount="<?php echo htmlspecialchars($transaction['amountProcessed']); ?>"
                                            data-seller-id="<?php echo htmlspecialchars($transaction['seller_id']); ?>"
                                            class="btn btn-success btn-sm transfer-btn">
                                            Transfer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="transaction-detail-container">
            <h3>Transaction Details</h3>
            <p><strong>Transaction ID:</strong> <span id="detail-transaction-id"></span></p>
            <p><strong>Amount:</strong> RM <span id="detail-amount"></span></p>
            <p><strong>Seller ID:</strong> <span id="detail-seller-id"></span></p>
        </div>

        <div style="margin-top:20px;" id="paypal-button-container"></div>
    </div>

    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../js/demo/datatables-demo.js"></script>

    <script>
        let isRendered = false;

        $(document).ready(function() {
            $('.transfer-btn').on('click', function() {
                var transactionId = $(this).data('transaction-id');
                var amount = parseFloat($(this).data('amount'));
                var sellerId = $(this).data('seller-id');

                if (isNaN(amount)) {
                    alert('Invalid amount.');
                    return;
                }

                $('#detail-transaction-id').text(transactionId);
                $('#detail-amount').text(amount.toFixed(2));
                $('#detail-seller-id').text(sellerId);

                $('#transaction-detail-container').show();

                if (!isRendered) {
                    paypal.Buttons({
                        createOrder: function(data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: amount.toFixed(2)
                                    }
                                }]
                            });
                        },
                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(details) {
                                $.ajax({
                                    url: 'process_transfer.php',
                                    type: 'POST',
                                    data: {
                                        transaction_id: transactionId,
                                        paypal_transaction_id: details.id
                                    },
                                    success: function(response) {
                                        var result = JSON.parse(response);
                                        if (result.status === 'success') {
                                            alert('Transfer completed successfully!');
                                            location.reload();
                                        } else {
                                            alert('Transfer failed: ' + result.message);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        alert('An error occurred during transfer. Please try again.');
                                    }
                                });
                            });
                        },
                        onCancel: function(data) {
                            alert('Transfer was cancelled.');
                        }
                    }).render('#paypal-button-container');

                    isRendered = true;
                }

                $('#paypal-button-container').show();
            });
        });
    </script>
</body>

</html>