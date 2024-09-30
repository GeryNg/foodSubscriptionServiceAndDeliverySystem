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
$query->bindParam(':cust_id', $cust_id, PDO::PARAM_STR_CHAR);
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

$query = $db->prepare("SELECT latitude, longitude FROM delivery WHERE cust_id = :cust_id AND status = 'on delivery' LIMIT 1");
$query->bindParam(':cust_id', $cust_id, PDO::PARAM_STR);
$query->execute();

$location = $query->fetch(PDO::FETCH_ASSOC);

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


    .map {
        height: 450px;
        width: 800px;
        margin-top: 20px;
        margin-bottom: 20px;
        border: 2px solid #ddd;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        margin-left: auto;
        margin-right: auto;
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
                <?php if ($delivery['status'] === 'on delivery'): ?>
                    <div class="map" id="map-<?php echo $delivery['delivery_id']; ?>" style="height: 450px; width: 800px;"></div>
                <?php else: ?>
                    <p></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<br />
<br />
<br />
<br />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<script>
var maps = {};
var sellerMarkers = {};

function initMap(deliveryId, lat, lng) {
    var mapId = 'map-' + deliveryId;

    if (!maps[deliveryId]) {
        maps[deliveryId] = L.map(mapId).setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap'
        }).addTo(maps[deliveryId]);

        var sellerIcon = L.icon({
            iconUrl: 'https://icon-library.com/images/van-icon-png/van-icon-png-29.jpg',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -30]
        });

        sellerMarkers[deliveryId] = L.marker([lat, lng], {
            icon: sellerIcon
        }).addTo(maps[deliveryId])
            .bindPopup("Seller is here");
    } else {
        sellerMarkers[deliveryId].setLatLng([lat, lng]).update();
        maps[deliveryId].setView([lat, lng], 14);
    }
}

function fetchSellerLocation(deliveryId) {
    fetch('get_seller_location.php?cust_id=<?php echo $cust_id; ?>')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.latitude && data.longitude && data.latitude !== '0.00000000' && data.longitude !== '0.0000') {
                var lat = parseFloat(data.latitude);
                var lng = parseFloat(data.longitude);

                initMap(deliveryId, lat, lng);
            } else {
                console.error('Invalid seller location data:', data);
                showErrorMessage("Seller location is unavailable or invalid. Please try again later.", deliveryId);
            }
        })
        .catch(error => {
            console.error('Error fetching seller location:', error);
            showErrorMessage("Unable to fetch seller location. Please try again later.", deliveryId);
        });
}

function showErrorMessage(message, deliveryId) {
    var mapContainer = document.getElementById('map-' + deliveryId);
    mapContainer.innerHTML = `<div style="padding: 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;">
        ${message}
    </div>`;
}

document.addEventListener('DOMContentLoaded', function () {
    <?php foreach ($deliveries as $delivery): ?>
        fetchSellerLocation('<?php echo $delivery['delivery_id']; ?>');
    <?php endforeach; ?>

    setInterval(function() {
        <?php foreach ($deliveries as $delivery): ?>
            fetchSellerLocation('<?php echo $delivery['delivery_id']; ?>');
        <?php endforeach; ?>
    }, 15000);
});
</script>

<?php include_once '../partials/footer.php'; ?>