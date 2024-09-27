<?php
$page_title = "Wallet";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_access = $_SESSION['access'] ?? '';
$seller_id = $_SESSION['seller_id'] ?? '';

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

try {
    // Fetch wallet balance and revenue
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

    // Fetch the total revenue for the current and previous months
    $sqlCurrentMonthRevenue = "
        SELECT SUM(amount) as current_month_revenue 
        FROM transaction 
        WHERE seller_id = :seller_id 
        AND transactionType = 'Sale' 
        AND MONTH(datetime) = MONTH(CURRENT_DATE()) 
        AND YEAR(datetime) = YEAR(CURRENT_DATE())";
    $stmtCurrentMonthRevenue = $db->prepare($sqlCurrentMonthRevenue);
    $stmtCurrentMonthRevenue->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmtCurrentMonthRevenue->execute();
    $currentMonthRevenue = $stmtCurrentMonthRevenue->fetch(PDO::FETCH_ASSOC)['current_month_revenue'] ?? 0.00;

    $sqlLastMonthRevenue = "
        SELECT SUM(amount) as last_month_revenue 
        FROM transaction 
        WHERE seller_id = :seller_id 
        AND transactionType = 'Sale' 
        AND MONTH(datetime) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) 
        AND YEAR(datetime) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)";
    $stmtLastMonthRevenue = $db->prepare($sqlLastMonthRevenue);
    $stmtLastMonthRevenue->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmtLastMonthRevenue->execute();
    $lastMonthRevenue = $stmtLastMonthRevenue->fetch(PDO::FETCH_ASSOC)['last_month_revenue'] ?? 0.00;

    // Calculate percentage change
    if ($lastMonthRevenue > 0) {
        $percentageChange = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
    } else {
        $percentageChange = $currentMonthRevenue > 0 ? 100 : 0;
    }

    $revenueChangeClass = $percentageChange >= 0 ? 'bg-green' : 'bg-red';
    $changeText = $percentageChange >= 0 ? '+' : '';

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



// Chart data: Fetch monthly income data for the chart
if (!empty($seller_id)) {
    // Get the year from GET parameter or default to current year
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

    // SQL to fetch monthly income
    $sqlMonthlyIncome = "
        SELECT MONTH(payment.PaymentDate) AS month, SUM(payment.PaymentAmount) AS total_income
        FROM payment
        INNER JOIN order_cust ON payment.Order_ID = order_cust.Order_ID
        INNER JOIN plan ON order_cust.Plan_ID = plan.id
        WHERE plan.seller_id = :seller_id
        AND YEAR(payment.PaymentDate) = :year
        GROUP BY MONTH(payment.PaymentDate)";

    $stmtMonthlyIncome = $db->prepare($sqlMonthlyIncome);
    $stmtMonthlyIncome->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmtMonthlyIncome->bindParam(':year', $year, PDO::PARAM_INT);
    $stmtMonthlyIncome->execute();
    $monthlyIncome = $stmtMonthlyIncome->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for the chart, initialize with 0 for each month
    $monthlyIncomeData = array_fill(1, 12, 0.00);
    foreach ($monthlyIncome as $income) {
        $month = (int)$income['month'];
        $monthlyIncomeData[$month] = (float)$income['total_income'];
    }

    // Pass data to JavaScript
    echo '<script>';
    echo 'var monthlyIncomeData = ' . json_encode(array_values($monthlyIncomeData)) . ';';
    echo 'console.log("monthlyIncomeData from PHP:", monthlyIncomeData);'; // Debug the data passed to JavaScript
    echo 'var selectedYear = ' . json_encode($year) . ';';
    echo '</script>';
} else {
    echo '<script>';
    echo 'var monthlyIncomeData = [];';
    echo 'var selectedYear = ' . json_encode(date('Y')) . ';';
    echo '</script>';
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

    .bg-red {
        background-color: #F9E7D4;
        color: #ED4425;
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
                                <p class="ms-3 px-2 <?php echo $revenueChangeClass; ?>">
                                    <?php echo $changeText . number_format(abs($percentageChange), 2); ?>% since last month
                                </p>
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
                                    <h2 style="color:#333; font-weight:bold" id="totalIncomeText">RM 0</h2>
                                </div>
                            </div>
                            <canvas id="myChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-12 ps-md-5 p-0 box">
                <!-- Withdraw Section -->
                <div class="col-12 px-0">
                    <div class="box-left">
                        <p class="fw-bold h7">Withdraw</p>
                        <p class="textmuted h8">Request For Withdraw</p><br />
                        <div class="h8 mb-2">
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

                <!-- Wallet Activity Section -->
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

<script>
    // The income data passed from PHP
</script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.getElementById('withdraw-amount').addEventListener('input', function() {
        var withdrawAmount = parseFloat(this.value) || 0;
        var platformFee = withdrawAmount * 0.06; // 6% fee
        var totalWithdrawAmount = withdrawAmount - platformFee;

        document.getElementById('platform-fee').value = platformFee.toFixed(2);
        document.getElementById('total-withdraw').value = totalWithdrawAmount.toFixed(2);
    });

    function setMaxWithdraw() {
        var maxAmount = <?php echo $balance; ?>;
        document.getElementById('withdraw-amount').value = maxAmount;
        var platformFee = maxAmount * 0.06; // 6% fee
        var totalWithdrawAmount = maxAmount - platformFee;

        document.getElementById('platform-fee').value = platformFee.toFixed(2);
        document.getElementById('total-withdraw').value = totalWithdrawAmount.toFixed(2);
    }

    function setMaxWithdraw() {
        var balance = parseFloat(document.getElementById('max-balance').value) || 0;
        document.getElementById('withdraw-amount').value = balance.toFixed(2);
        calculateWithdraw();
    }

    document.getElementById('withdraw-amount').addEventListener('input', calculateWithdraw);
</script>

<script>
    // Log the data passed from PHP to check its correctness
    console.log("monthlyIncomeData from PHP:", monthlyIncomeData);

    // Function to format number as currency
    function formatCurrency(num) {
        return 'RM ' + parseFloat(num).toFixed(2);
    }

    // Calculate total income from the monthly income data
    var totalIncome = monthlyIncomeData.reduce(function(a, b) {
        return a + b;
    }, 0);
    document.getElementById('totalIncomeText').innerText = formatCurrency(totalIncome);

    document.addEventListener('DOMContentLoaded', function() {
        // Ensure the canvas element exists
        let ctx = document.getElementById('myChart');
        if (ctx) {
            let chartContext = ctx.getContext('2d');

            // Initialize the Chart.js chart
            let myChart = new Chart(chartContext, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Monthly Income (RM)',
                        data: monthlyIncomeData,
                        backgroundColor: 'rgba(92, 103, 242, 0.5)',
                        borderColor: 'rgba(92, 103, 242, 1)',
                        borderWidth: 1,
                        borderRadius: 5,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.dataset.data[context.dataIndex];
                                    return 'Income: RM ' + parseFloat(value).toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'RM ' + value;
                                }
                            }
                        }
                    }
                }
            });
        } else {
            console.error('Canvas element not found.');
        }
    });
</script>