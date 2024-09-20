<?php
$page_title = "Order Plan";
include '../resource/Database.php';
include '../partials/headers.php';

// Initialize variables
$plan = null;
$plan_id = $planPrice = null;
$addons = [];

// Check if the plan ID is provided
if (isset($_GET['plan_id'])) {
    $plan_id = htmlspecialchars($_GET['plan_id'], ENT_QUOTES, 'UTF-8');

    // Fetch plan details from the database
    $sql = "SELECT plan.plan_name, plan.description, plan.price, plan.image_urls 
            FROM plan 
            WHERE plan.id = :plan_id";
    $statement = $db->prepare($sql);
    $statement->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
    $statement->execute();
    $plan = $statement->fetch();

    if ($plan) {
        $planName = htmlspecialchars($plan['plan_name'], ENT_QUOTES, 'UTF-8');
        $planDescription = htmlspecialchars($plan['description'], ENT_QUOTES, 'UTF-8');
        $planPrice = htmlspecialchars($plan['price'], ENT_QUOTES, 'UTF-8');
        $planImages = explode(',', htmlspecialchars($plan['image_urls'], ENT_QUOTES, 'UTF-8'));

        // Fetch add-ons related to the plan
        $sql_addons = "SELECT * FROM addons WHERE plan_id = :plan_id";
        $addon_statement = $db->prepare($sql_addons);
        $addon_statement->bindParam(':plan_id', $plan_id, PDO::PARAM_INT);
        $addon_statement->execute();
        $addons = $addon_statement->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Plan</title>
    <link rel="stylesheet" href="../css/order.css">
    <link rel="icon" type="image/x-icon" href="../image/logo-circle.png">
    <style>
        .slideshow-container {
            position: relative;
            height: 400px;
            max-width: 400px;
            margin: auto;
            overflow: hidden;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            transition: 0.5s;
        }

        .slide img {
            width: 100%;
            height: auto;
        }

        .slider-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .slider-controls .prev,
        .slider-controls .next {
            cursor: pointer;
            padding: 16px;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            user-select: none;
        }

        .slider-controls .prev:hover,
        .slider-controls .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        #addonsContainer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        #addonsContainer.open {
            max-height: 1000px;
            /* Adjust according to the expected content height */
            transition: max-height 0.5s ease-in;
        }
        .active-thumbnail {
    border: 2px solid #5C67F2; /* Adjust the color */
    opacity: 0.8;
}
    </style>
</head>

<body>
    <div class="main-container">
        <div class="left-section">
            <?php if ($plan): ?>
                <div class='plan-images'>
                    <div class="slideshow-container">
                        <div class="slider">
                            <?php foreach ($planImages as $index => $image): ?>
                                <div class="slide">
                                    <img src='<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>' class='plan-image' alt='Plan Image'>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Slider Navigation -->
                        <div class="slider-controls">
                            <span class="prev">&#10094;</span>
                            <span class="next">&#10095;</span>
                        </div>
                    </div>

                    <div class='thumbnail-row'>
                        <?php foreach ($planImages as $index => $image): ?>
                            <div class='thumbnail-column'>
                                <img class='demo cursor' src='../plan/<?php echo trim($image); ?>' style='width:100%' onclick='showSlides(<?php echo $index; ?>)' alt='Image'>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class='plan-info'>
                    <h1><?php echo $planName; ?></h1>
                    <p><?php echo $planDescription; ?></p>
                    <p class='price'>Price: RM<?php echo $planPrice; ?></p>
                </div>
            <?php else: ?>
                <p>Plan not found.</p>
            <?php endif; ?>
        </div>

        <div class="right-section">
            <div class="order-form">
                <form action="add_order.php" method="POST" onsubmit="return validateDates();">
                    <!-- Hidden field to store the Plan ID -->
                    <input type="hidden" name="plan_id" value="<?php echo $plan_id; ?>">
                    <input type="hidden" name="plan_price" value="<?php echo $planPrice; ?>">

                    <!-- Quantity -->
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" required>

                    <!-- Meal Selection -->
                    <label for="meal">Meal:</label>
                    <select name="meal" id="meal" required>
                        <option value="Lunch">Lunch</option>
                        <option value="Dinner">Dinner</option>
                    </select>

                    <!-- Delivery Address -->
                    <label for="delivery_address">Delivery Address:</label>
                    <select name="delivery_address_id" id="delivery_address" required>
                        <?php
                        // Fetch the user's addresses
                        $cust_id = $_SESSION['Cust_ID'];
                        $sql = "SELECT * FROM address WHERE Cust_ID = :cust_id";
                        $statement = $db->prepare($sql);
                        $statement->bindParam(':cust_id', $cust_id, PDO::PARAM_STR_CHAR);
                        $statement->execute();

                        while ($row = $statement->fetch()) {
                            $address_id = htmlspecialchars($row['address_id'], ENT_QUOTES, 'UTF-8');
                            $line1 = htmlspecialchars($row['line1'], ENT_QUOTES, 'UTF-8');
                            $line2 = htmlspecialchars($row['line2'], ENT_QUOTES, 'UTF-8');
                            $city = htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8');
                            $state = htmlspecialchars($row['state'], ENT_QUOTES, 'UTF-8');
                            $postal_code = htmlspecialchars($row['postal_code'], ENT_QUOTES, 'UTF-8');
                            $country = htmlspecialchars($row['country'], ENT_QUOTES, 'UTF-8');

                            echo "<option value='$address_id'>$line1 $line2, $city, $state, $postal_code, $country</option>";
                        }
                        ?>
                    </select>

                    <!-- Instructions -->
                    <label for="instructions">Instructions:</label>
                    <textarea name="instructions" id="instructions" maxlength="255" placeholder="Any specific instructions?"></textarea>

                    <!-- Start Date -->
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" required>

                    <!-- End Date -->
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" id="end_date" required>

                    <!-- Duration -->
                    <label for="duration">Duration (days):</label>
                    <input type="text" name="duration" id="duration" readonly>

                    <!-- Add-ons Section with Toggle -->
                    <label>
                        <input type="checkbox" id="showAddonsCheckbox" onclick="toggleAddons()" style="max-width:5%" />
                        Show Add-ons
                    </label>
                    <div id="addonsContainer" style="display: none;">
                        <div id="addons">
                            <?php
                            if ($addons) {
                                foreach ($addons as $addon) {
                                    $addonName = htmlspecialchars($addon['addon_name'], ENT_QUOTES, 'UTF-8');
                                    $addonPrice = htmlspecialchars($addon['addon_price'], ENT_QUOTES, 'UTF-8');
                                    $addonImage = htmlspecialchars($addon['addon_image'], ENT_QUOTES, 'UTF-8');

                                    echo "<div class='addon-item'>";
                                    echo "<img src='" . $addonImage . "' alt='Addon Image' style='width:100px; height:100px; object-fit:cover; border:1px solid #ccc;'>";
                                    echo "<p>$addonName - RM$addonPrice</p>";
                                    echo "<label for='addon_quantity_{$addon['id']}'>Quantity:</label>";
                                    echo "<input type='number' name='addon_quantity[{$addon['id']}]' value='0' min='0'>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>No add-ons available for this plan.</p>";
                            }
                            ?>
                        </div>
                    </div>


                    <!-- Grand Total (display only, calculated dynamically with JavaScript) -->
                    <label for="grand_total">Grand Total:</label>
                    <input type="text" name="grand_total" id="grand_total" value="RM <?php echo $planPrice; ?>" readonly>

                    <!-- Checkout Button -->
                    <button type="submit" class="btn">Checkout</button>
                </form>
            </div>
        </div>
    </div>
    <?php include '../partials/footer.php'; ?>
    <script>
        function toggleAddons() {
            const checkbox = document.getElementById('showAddonsCheckbox');
            const addonsContainer = document.getElementById('addonsContainer');

            if (checkbox.checked) {
                addonsContainer.style.display = 'block';
                addonsContainer.classList.add('open');
            } else {
                addonsContainer.style.display = 'none';
                addonsContainer.classList.remove('open');
            }
        }
    </script>
    <script>
        let currentIndex1 = 0;
const slides1 = document.querySelectorAll('.slide');
const totalSlides1 = slides.length;

function showSlides(index) {
    if (index >= totalSlides1) {
        currentIndex1 = 0;
    } else if (index < 0) {
        currentIndex1 = totalSlides1 - 1;
    } else {
        currentIndex1 = index;
    }

    const offset = -currentIndex * 100;
    document.querySelector('.slider').style.transform = `translateX(${offset}%)`;

    // Update active thumbnail
    updateActiveThumbnail(currentIndex);
}

// Update active thumbnail styling
function updateActiveThumbnail(index) {
    const thumbnails = document.querySelectorAll('.thumbnail-column img');
    thumbnails.forEach((thumbnail, i) => {
        thumbnail.classList.remove('active-thumbnail');
        if (i === index) {
            thumbnail.classList.add('active-thumbnail');
        }
    });
}

// Initially show the first slide
showSlides(currentIndex);
    </script>
    <script>
        let currentIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showSlides(index) {
            if (index >= totalSlides) {
                currentIndex = 0;
            } else if (index < 0) {
                currentIndex = totalSlides - 1;
            } else {
                currentIndex = index;
            }

            const offset = -currentIndex * 100;
            document.querySelector('.slider').style.transform = `translateX(${offset}%)`;
        }

        document.querySelector('.next').addEventListener('click', () => {
            showSlides(currentIndex + 1);
        });

        document.querySelector('.prev').addEventListener('click', () => {
            showSlides(currentIndex - 1);
        });

        showSlides(currentIndex);

        // JavaScript for calculating totals
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const quantityInput = document.getElementById('quantity');
        const grandTotalInput = document.getElementById('grand_total');
        const planPrice = parseFloat("<?php echo $planPrice; ?>");

        function calculateDuration() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const quantity = parseInt(quantityInput.value) || 1;

            if (startDate && endDate && startDate <= endDate) {
                const duration = (endDate - startDate) / (1000 * 60 * 60 * 24) + 1;
                document.getElementById('duration').value = duration;
                const grandTotal = planPrice * duration * quantity;
                grandTotalInput.value = "RM " + grandTotal.toFixed(2);
            }
        }

        // Attach event listeners to calculate totals on change
        startDateInput.addEventListener('change', calculateDuration);
        endDateInput.addEventListener('change', calculateDuration);
        quantityInput.addEventListener('input', calculateDuration);
    </script>
</body>

</html>