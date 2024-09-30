<?php
$page_title = "Restaurants";
include '../resource/Database.php';
include '../resource/session.php';
include '../partials/headers.php';

$customer_id = $_SESSION['Cust_ID'];

// Fetch customer addresses
$sql_addresses = "SELECT address_id, CONCAT(line1, ', ', line2) AS full_address 
                  FROM address 
                  WHERE Cust_ID = :customer_id";
$statement_addresses = $db->prepare($sql_addresses);
$statement_addresses->bindParam(':customer_id', $customer_id, PDO::PARAM_STR_CHAR);
$statement_addresses->execute();
$addresses = $statement_addresses->fetchAll(PDO::FETCH_ASSOC);

// Set default selected address
$selected_address_id = isset($_SESSION['selected_address_id']) ? $_SESSION['selected_address_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address_id'])) {
    $selected_address_id = $_POST['address_id'];
    $_SESSION['selected_address_id'] = $selected_address_id;
    header("Location: ../Restaurant/restaurant.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_address'])) {
    unset($_SESSION['selected_address_id']);
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            clearAddress();
        });
    </script>";
}

// Retrieve selected customer address and its latitude/longitude
$selected_address = null;
$customer_lat = null;
$customer_lng = null;

if ($selected_address_id) {
    $sql_selected_address = "SELECT CONCAT(line1, ', ', line2) AS full_address, latitude, longitude
                             FROM address 
                             WHERE address_id = :address_id";
    $statement_selected_address = $db->prepare($sql_selected_address);
    $statement_selected_address->bindParam(':address_id', $selected_address_id, PDO::PARAM_INT);
    $statement_selected_address->execute();
    $address_data = $statement_selected_address->fetch(PDO::FETCH_ASSOC);

    $selected_address = $address_data['full_address'];
    $customer_lat = $address_data['latitude'];
    $customer_lng = $address_data['longitude'];
}

// Fetch restaurant data, calculate distance only if address is selected
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sellers = [];

if ($selected_address_id) {
    // Query with distance calculation if the customer address is selected
    $sql = "SELECT seller.profile_pic, seller.name, seller.detail, seller.address, seller.id,
                   seller.latitude, seller.longitude,
                   ( 6371 * acos( cos( radians(:customer_lat) ) * cos( radians( seller.latitude ) ) 
                   * cos( radians( seller.longitude ) - radians(:customer_lng) ) 
                   + sin( radians(:customer_lat) ) * sin( radians( seller.latitude ) ) ) ) AS distance
            FROM seller 
            WHERE seller.access = 'verify'";

    if (!empty($searchTerm)) {
        $sql .= " AND seller.name LIKE :searchTerm";
    }

    // Add ORDER BY distance ASC to sort by distance from near to far
    $sql .= " HAVING distance <= 10 ORDER BY distance ASC";
} else {
    // Query without distance calculation if no customer address is selected
    $sql = "SELECT seller.profile_pic, seller.name, seller.detail, seller.address, seller.id
            FROM seller 
            WHERE seller.access = 'verify'";

    if (!empty($searchTerm)) {
        $sql .= " AND seller.name LIKE :searchTerm";
    }

    $sql .= " ORDER BY seller.name ASC";
}

$statement = $db->prepare($sql);

if ($selected_address_id) {
    // Bind customer latitude and longitude only if an address is selected
    $statement->bindParam(':customer_lat', $customer_lat);
    $statement->bindParam(':customer_lng', $customer_lng);
}

if (!empty($searchTerm)) {
    $statement->bindValue(':searchTerm', '%' . $searchTerm . '%');
}

$statement->execute();

while ($row = $statement->fetch()) {
    $profile_pic = htmlspecialchars($row['profile_pic'], ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8');
    $detail = htmlspecialchars($row["detail"], ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars($row["address"], ENT_QUOTES, 'UTF-8');
    $id = htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8');
    
    $distance = isset($row['distance']) ? round($row['distance'], 2) : null;

    $sellers[] = [
        'profile_pic' => $profile_pic,
        'name' => $name,
        'detail' => $detail,
        'address' => $address,
        'id' => $id,
        'distance' => $distance
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <link rel="stylesheet" href="../css/restaurant.css">
    <link rel="icon" type="image/x-icon" href="../image/logo-circle.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container" style="margin-top: 3%;">
        <h1>All Restaurants</h1>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if (!$selected_address_id): ?>
                    clearAddress();
                <?php endif; ?>
            });

            function clearAddress() {
                const inputOptions = {
                    <?php
                    $addressCount = count($addresses);
                    $counter = 0;
                    foreach ($addresses as $address):
                        $counter++;
                        $truncated_address = substr($address['full_address'], 0, 100) . (strlen($address['full_address']) > 100 ? '...' : '');
                        echo "'{$address['address_id']}': '" . addslashes($truncated_address) . "'";
                        if ($counter < $addressCount) {
                            echo ',';
                        }
                    endforeach;
                    ?>
                };

                Swal.fire({
                    title: 'Select Your Address',
                    input: 'select',
                    inputOptions: inputOptions,
                    inputPlaceholder: 'Select your address',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value) {
                                resolve();
                            } else {
                                resolve('You need to select an address!');
                            }
                        });
                    }
                }).then((result) => {
                    if (result.value) {
                        const selectedAddress = result.value;
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'address_id';
                        input.value = selectedAddress;

                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        </script>

        <?php if ($selected_address): ?>
            <div class="selected-address-container">
                <p><b>Selected Address: </b><?php echo $selected_address; ?></p>
                <form method="POST" action="" class="change-address-form">
                    <input type="hidden" name="clear_address" value="true" />
                    <button type="submit" class="btn-change">Change</button>
                </form>
            </div>
        <?php endif; ?>

        <form method="GET" action="" class="search-bar">
            <input type="text" name="search" placeholder="Search for restaurants, cuisines, and dishes" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M16.17 11A6.5 6.5 0 1111 16.17 6.5 6.5 0 0116.17 11z" />
                </svg>
            </button>
        </form>

        <section class="articles">
            <?php foreach ($sellers as $seller): ?>
                <article class='restaurant-card'>
                    <div class='article-wrapper'>
                        <figure>
                            <img src='<?php echo $seller['profile_pic']; ?>' alt='profile_pic' class='profile-pic' />
                        </figure>
                        <div class='article-body'>
                            <div class='restaurant-title'>
                                <h2><?php echo $seller['name']; ?></h2>
                            </div>
                            <p class='detail'><?php echo $seller['detail']; ?></p>
                            <a href='restaurant_plan.php?id=<?php echo $seller['id']; ?>' class='read-more'>
                                Read more <span class='sr-only'>about <?php echo $seller['name']; ?></span>
                                <svg xmlns='http://www.w3.org/2000/svg' class='icon' viewBox='0 0 20 20' fill='currentColor'>
                                    <path fill-rule='evenodd' d='M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z' clip-rule='evenodd' />
                                </svg>
                            </a>
                            <?php if ($selected_address_id): ?>
                                <span class="distance"><?php echo $seller['distance']; ?> km away</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </div>
    <br />
    <br />
    <?php include '../partials/footer.php'; ?>
</body>

</html>
