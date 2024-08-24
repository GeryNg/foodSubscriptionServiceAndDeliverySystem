<?php
$page_title = "Delivery List";
include_once '../partials/headers.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';
date_default_timezone_set('Asia/Kuala_Lumpur');

$cust_id = $_SESSION['Cust_ID'];
$today = date('Y-m-d');

// Fetch delivery details for the given customer ID
$query = $db->prepare("SELECT * FROM delivery WHERE cust_id = :cust_id AND delivery_date = :today");
$query->bindParam(':cust_id', $cust_id, PDO::PARAM_INT);
$query->bindParam(':today', $today);
$query->execute();
$deliveries = $query->fetchAll(PDO::FETCH_ASSOC);

if ($delivery) {
    $order_id = $delivery['order_id'];
    $delivery_id = $delivery['delivery_id'];
    $delivery_date = $delivery['delivery_date'];
    $status = $delivery['status'];
} else {
    $order_id = $delivery_id = $delivery_date = $status = 'No delivery found';
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .card {
        z-index: 0;
        background-color: #ECEFF1;
        padding-bottom: 20px;
        margin-top: 90px;
        margin-bottom: 90px;
        border-radius: 10px;
    }

    .top {
        padding-top: 40px;
        padding-left: 13% !important;
        padding-right: 13% !important;
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: #455A64;
        padding-left: 0px;
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
    }

    #progressbar li {
        list-style-type: none;
        font-size: 13px;
        width: 25%;
        position: relative;
        text-align: center;
    }

    #progressbar li.step0:before {
        width: 40px;
        height: 40px;
        line-height: 40px;
        display: block;
        font-size: 20px;
        background: #C5CAE9;
        color: #fff;
        border-radius: 50%;
        margin: auto;
        padding: 0px;
        content: "\f058";
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 12px;
        background: #C5CAE9;
        position: absolute;
        left: 0;
        top: 20px;
        z-index: -1;
    }

    #progressbar li.active:before {
        background: #651FFF;
        font-family: FontAwesome;
        content: "\f00c";
    }

    #progressbar li.active:after {
        background: #651FFF;
    }

    #progressbar li:last-child:after {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        position: absolute;
        left: -50%;
    }

    #progressbar li:nth-child(2):after,
    #progressbar li:nth-child(3):after {
        left: -50%;
    }

    #progressbar li:first-child:after {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        position: absolute;
        left: 50%;
    }

    .icon {
        width: 60px;
        height: 60px;
        margin-bottom: 10px;
    }

    .icon-content {
        text-align: center;
    }

    .icon-content p {
        margin: 0;
        font-weight: bold;
    }

    .top {
        padding-top: 40px;
        padding-left: 13% !important;
        padding-right: 13% !important;
    }

    .card {
        z-index: 0;
        background-color: #ECEFF1;
        padding-bottom: 20px;
        margin-top: 90px;
        margin-bottom: 90px;
        border-radius: 10px;
    }

    .container {
        margin: auto;
    }

    @media screen and (max-width: 992px) {
        .icon-content {
            width: 50%;
        }
    }
</style>

<div class="container px-1 px-md-4 py-5 mx-auto">
    <h1 class="h1 mb-2 text-gray-800" style="font-weight: 600;">Delivery List</h1>
    <hr />
    <?php if (empty($deliveries)): ?>
        <p>No deliveries found for today.</p>
    <?php else: ?>
        <?php foreach ($deliveries as $delivery): ?>
            <div class="card">
                <div class="row d-flex justify-content-between px-3 top">
                    <div class="d-flex">
                        <h5>ORDER <span class="text-primary font-weight-bold">#<?php echo htmlspecialchars($delivery['order_id']); ?></span></h5>
                    </div>
                    <div class="d-flex flex-column text-sm-right">
                        <p class="mb-0">Delivery Date <span><?php echo htmlspecialchars($delivery['delivery_date']); ?></span></p>
                        <p>DeliveryID <span class="font-weight-bold">#<?php echo htmlspecialchars($delivery['delivery_id']); ?></span></p>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-12">
                        <ul id="progressbar" class="text-center">
                            <li class="<?php echo ($delivery['status'] === 'order accepted' || $delivery['status'] === 'food preparing' || $delivery['status'] === 'on delivery' || $delivery['status'] === 'done delivery') ? 'active step0' : 'step0'; ?>"></li>
                            <li class="<?php echo ($delivery['status'] === 'food preparing' || $delivery['status'] === 'on delivery' || $delivery['status'] === 'done delivery') ? 'active step0' : 'step0'; ?>"></li>
                            <li class="<?php echo ($delivery['status'] === 'on delivery' || $delivery['status'] === 'done delivery') ? 'active step0' : 'step0'; ?>"></li>
                            <li class="<?php echo ($delivery['status'] === 'done delivery') ? 'active step0' : 'step0'; ?>"></li>
                        </ul>
                    </div>
                </div>
                <div class="row d-flex justify-content-center top">
                    <div class="col d-flex justify-content-center">
                        <div class="icon-content">
                            <img class="icon" src="https://i.imgur.com/9nnc9Et.png">
                            <p class="font-weight-bold">Order<br>Accepted</p>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                        <div class="icon-content">
                            <img class="icon" src="https://i.imgur.com/u1AzR7w.png">
                            <p class="font-weight-bold">Food<br>Preparing</p>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                        <div class="icon-content">
                            <img class="icon" src="https://i.imgur.com/TkPm63y.png">
                            <p class="font-weight-bold">On<br>Delivery</p>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                        <div class="icon-content">
                            <img class="icon" src="https://i.imgur.com/HdsziHP.png">
                            <p class="font-weight-bold">Done<br>Delivery</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<br/>
<br/>
<br/>
<br/>


<?php include_once '../partials/footer.php';?>