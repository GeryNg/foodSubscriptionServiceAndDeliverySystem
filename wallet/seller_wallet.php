<?php
$page_title = "Wallet";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_access = $_SESSION['access'] ?? '';

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

try {
    $sql = "SELECT balance, revenue FROM wallet WHERE seller_id = :seller_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmt->execute();
    $wallet = $stmt->fetch(PDO::FETCH_ASSOC);

    $balance = $wallet['balance'] ?? 0.00;
    $revenue = $wallet['revenue'] ?? 0.00;

    // Fetch transactions related to this seller
    $sqlTransactions = "SELECT amount, status, transactionType, datetime FROM transaction WHERE seller_id = :seller_id ORDER BY datetime DESC";
    $stmtTransactions = $db->prepare($sqlTransactions);
    $stmtTransactions->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmtTransactions->execute();
    $transactions = $stmtTransactions->fetchAll(PDO::FETCH_ASSOC);

    // Calculate pending withdraw total
    $sqlPendingWithdraw = "SELECT SUM(amount) as pending_withdraw FROM transaction WHERE seller_id = :seller_id AND transactionType = 'Withdraw' AND status = 'Pending'";
    $stmtPendingWithdraw = $db->prepare($sqlPendingWithdraw);
    $stmtPendingWithdraw->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmtPendingWithdraw->execute();
    $pendingWithdraw = $stmtPendingWithdraw->fetch(PDO::FETCH_ASSOC)['pending_withdraw'] ?? 0.00;
} catch (PDOException $e) {
    echo "Error fetching wallet data: " . $e->getMessage();
    exit;
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    .container-fluid {
        margin-bottom: 5%;
    }

    .col-md-7 {
        max-width: 50%;
    }

    h1 {
        color: #333;
        font-size: 2.5rem;
        margin: 3rem 0 0.5rem 0;
        font-weight: 800;
        line-height: 1.2;
    }

    .breadcrumb {
        background-color: transparent;
    }

    .container1 {
        margin: 20px auto;
        padding: 0 35px 0 35px;
    }

    .box-right {
        padding: 30px 25px;
        background-color: white;
        border-radius: 15px
    }

    .box-left {
        padding: 20px 20px;
        margin-left: 4%;
        background-color: white;
        border-radius: 15px
    }

    .textmuted {
        color: #7a7a7a
    }

    .bg-green {
        background-color: #d4f8f2;
        color: #06e67a;
        padding: 3px 0;
        display: inline;
        border-radius: 25px;
        font-size: 11px
    }

    .p-blue {
        font-size: 14px;
        color: #1976d2
    }

    .fas.fa-circle {
        font-size: 12px
    }

    .p-org {
        font-size: 14px;
        color: #333333;
    }

    .h7 {
        font-size: 1.2rem;
        margin-bottom: 0;
        color: #333333;
    }

    .h8 {
        font-size: 1rem;
        color: #333333;
    }

    .h9 {
        font-size: 10px
    }

    .bg-blue {
        background-color: #dfe9fc9c;
        border-radius: 5px
    }

    .form-control {
        box-shadow: none !important
    }

    .scrollable-container {
        max-height: 300px;
        overflow-y: auto;
        padding: 20px 10px 20px 10px;
    }

    .row1 {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .border {
        border: none;
    }

    .label-input-container {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .currency-label {
        left: 10px;
        font-weight: bold;
    }

    .label-input-container label {
        width: 200px;
        margin-right: 10px;
        text-align: left;
        font-weight: bold;
    }

    .label-input-container input {
        border-radius: 5px;
        border: 1px solid rgb(92, 103, 242, 0.5);
        flex-grow: 1;
        margin-right: 10px;
        margin-left: 10px;
    }

    .button-container {
        text-align: right;
    }

    .button1 {
        background-color: #5C67F2;
        color: white;
        border: none;
        padding: 10px 20px;
        text-transform: uppercase;
        cursor: pointer;
        margin-top: 10px;
        font-weight: bold;
        border-radius: 10px;
    }

    .activity_status {
        font-weight: 900;
        font-size: 13px;
        margin: auto;
    }

    .text-center1 {
        margin: auto;
    }

    .border {
        border: none !important;
    }

    .border:hover {
        animation: infinite;
        background-color: #ffffff;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .boxes {
        width: 60px;
        height: 60px;
        padding: 10px;
        border: 4px solid rgb(186, 186, 186, 0.2);
        border-radius: 10px;
        margin-right: auto;
        margin-left: auto;
    }

    .selection-year {
        display: flex;
        justify-content: space-between;
    }

    .year-dropdown {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: transparent;
        padding: 10px 30px 10px 10px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 15px;
        position: relative;
        background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="gray"%3E%3Cpath fill-rule="evenodd" d="M5.292 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /%3E%3C/svg%3E');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 15px;
        width: 150px;
    }

    .year-dropdown:focus {
        outline: none;
        border-color: #5C67F2;
    }

    #balance-hint {
        display: none;
        color: red;
        font-size: 0.9em;
        margin-top: 5px;
    }

    .max-btn {
        background-color: #5C67F2;
        color: white;
        border: none;
        padding: 6px 12px;
        cursor: pointer;
        border-radius: 5px;
        margin-left: 10px;
        font-size: 14px;
        text-transform: uppercase;
    }

    @media(max-width:430px) {
        .h8 {
            font-size: 11px
        }

        .h7 {
            font-size: 13px
        }

        ::placeholder {
            font-size: 10px
        }

        .box {
            margin-top: 25px;
        }

        .box-right {
            margin-right: 0;
        }
    }
</style>

<div class="container-fluid">
    <h1>Wallet</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../partials/seller_dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Wallet</li>
    </ol>
    <div class="container1">
        <div class="row ">
            <div class="col-md-7 col-12">
                <div class="row">
                    <div class="col-12 px-0 mb-4">
                        <div class="row box-right">
                            <div class="col-md-8 ps-0 ">
                                <p class="ps-3 textmuted fw-bold h6 mb-0" style="color: #333; font-weight:900; font-size:x-large;">TOTAL REVENUE</p>
                                <p class="h1 fw-bold d-flex"> <span class=" fas fa-dollar-sign textmuted pe-1 h6 align-text-top mt-1"></span><?php echo number_format($revenue, 2); ?></p>
                                <p class="ms-3 px-2 bg-green">+10% since last month</p>
                            </div>
                            <div class="col-md-4">
                                <p class="p-blue"> <span class="fas fa-circle pe-2"></span>Wallet Balance </p>
                                <p class="fw-bold mb-3"><span class="fas fa-dollar-sign pe-1"></span><?php echo number_format($balance, 2); ?></p>
                                <p class="p-org"><span class="fas fa-circle pe-2"></span>Pending Withdraw</p>
                                <p class="fw-bold"><span class="fas fa-dollar-sign pe-1"></span><?php echo number_format($pendingWithdraw, 2); ?><span class="textmuted"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 px-0 mb-4">
                        <div class="box-right">
                            <p class="fw-bold h7">Overview Income</p>
                            <p class="textmuted h8">Here will show the income in chart.</p>
                            <div class="d-flex pb-2 selection-year">
                                <select id="yearSelector" class="year-dropdown">
                                    <option value="2024">Monthly (2024)</option>
                                    <option value="2023">Monthly (2023)</option>
                                    <option value="2022">Monthly (2022)</option>
                                    <option value="2021">Monthly (2021)</option>
                                </select>
                                <div style="display: flex;">
                                    <h2 style="color:#333; font-weight:bold">$12344</h2>
                                </div>
                            </div>
                            <canvas id="myChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-12 ps-md-5 p-0 box">
                <div class="col-12 px-0">
                    <div class="box-left">
                        <p class="fw-bold h7">Withdraw</p>
                        <p class="textmuted h8">Request For Withdraw</p><br />
                        <div class=" h8 mb-2">
                            <form action="request_withdraw.php" method="POST" id="withdrawForm">
                                <div class="label-input-container">
                                    <label for="withdraw-amount">Withdraw Amount:</label>
                                    <div class="input-container">
                                        <span class="currency-label">RM</span>
                                        <input type="number" id="withdraw-amount" name="withdraw_amount" placeholder="Enter amount">
                                        <button type="button" class="max-btn" onclick="setMaxWithdraw()">Max</button>
                                    </div>
                                </div>
                                <p id="balance-hint">The amount cannot be greater than the available balance.</p>
                                <div class="label-input-container">
                                    <label for="platform-fee">Platform fee (6%):</label>
                                    <div class="input-container">
                                        <span class="currency-label">RM</span>
                                        <input type="number" id="platform-fee" readonly>
                                    </div>
                                </div>
                                <div class="label-input-container">
                                    <label for="total-withdraw">Total Withdraw Amount:</label>
                                    <div class="input-container">
                                        <span class="currency-label">RM</span>
                                        <input type="number" id="total-withdraw" readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="balance" value="<?php echo $balance; ?>">
                                <div class="button-container">
                                    <button type="submit" name="requestWithdrawBtn" value="RequestWithdraw" class="button1">Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="max-balance" value="<?php echo $balance; ?>">
                <div class="col-12 px-0" style="margin-top: 25px;">
                    <div class="box-left">
                        <p class="fw-bold h7">Wallet Activity</p>
                        <p class="textmuted h8">Here will show all the Transactions.</p>
                        <div class="h8">
                            <div class="scrollable-container">
                                <?php if (!empty($transactions)): ?>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <div class="row1 border">
                                            <div class="p-0 text-center">
                                                <div class="activity_status p-2">
                                                    <div class="boxes">
                                                        <?php if ($transaction['transactionType'] == 'Withdraw'): ?>
                                                            <i class='fas fa-long-arrow-alt-down' style='font-size:30px;color: #363062'></i>
                                                        <?php else: ?>
                                                            <i class='fas fa-long-arrow-alt-up' style='font-size:30px;color: #61c277'></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-0 text-center1">
                                                <div class="activity_status p-2"><?php echo htmlspecialchars($transaction['transactionType']); ?></div>
                                            </div>
                                            <div class="p-0 text-center1">
                                                <p class="activity_status p-2"><?php echo htmlspecialchars(date("Y-m-d", strtotime($transaction['datetime']))); ?></p>
                                                <p class="activity_status p-2"><?php echo htmlspecialchars(date("H:i:s A", strtotime($transaction['datetime']))); ?></p>
                                            </div>
                                            <div class="p-0 text-center1">
                                                <p class="activity_status p-2">
                                                    <?php echo ($transaction['transactionType'] == 'Withdraw' ? '-' : '+') . 'RM' . number_format($transaction['amount'], 2); ?>
                                                </p>
                                            </div>
                                            <div class="p-0 text-center1">
                                                <div class="activity_status p-2" style="color: <?php echo $transaction['status'] == 'Successful' ? '#61c277' : '#858796'; ?>;">
                                                    <?php echo htmlspecialchars($transaction['status']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No transactions available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function calculateWithdraw() {
        var withdrawAmount = parseFloat(document.getElementById('withdraw-amount').value) || 0;
        var balance = parseFloat(document.getElementById('max-balance').value) || 0;
        var platformFee = 0;
        var totalWithdraw = 0;
        var hint = document.getElementById('balance-hint');

        if (withdrawAmount > balance) {
            hint.style.display = 'block';
        } else {
            hint.style.display = 'none';
        }

        platformFee = withdrawAmount * 0.06;
        totalWithdraw = withdrawAmount - platformFee;

        document.getElementById('platform-fee').value = platformFee.toFixed(2);
        document.getElementById('total-withdraw').value = totalWithdraw.toFixed(2);
    }

    function setMaxWithdraw() {
        var balance = parseFloat(document.getElementById('max-balance').value) || 0;
        document.getElementById('withdraw-amount').value = balance.toFixed(2);
        calculateWithdraw();
    }

    document.getElementById('withdraw-amount').addEventListener('input', calculateWithdraw);
</script>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var chartData = {
        '2024': [12, 19, 3, 5, 2, 3, 10],
        '2023': [15, 13, 8, 4, 5, 7, 9],
        '2022': [8, 10, 5, 3, 6, 9, 12],
        '2021': [20, 15, 10, 12, 14, 13, 16]
    };

    var sampleLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
    var selectedYear = '2024'; // Default year

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: sampleLabels,
            datasets: [{
                label: 'Income for ' + selectedYear,
                data: chartData[selectedYear],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(201, 203, 207, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    document.getElementById('yearSelector').addEventListener('change', function() {
        selectedYear = this.value;
        myChart.data.datasets[0].data = chartData[selectedYear];
        myChart.data.datasets[0].label = 'Income for ' + selectedYear;
        myChart.update();
    });
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>