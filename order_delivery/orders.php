<?php include '../resource/Database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Plan</title>
    <link rel="stylesheet" href="../css/order.css">
</head>
<body>
    <?php include '../partials/headers.php'; ?>
    <div class="main-container">
        <div class="left-section">
            <?php
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

                    echo "<div class='plan-images'>";
                    echo "<div class='slideshow-container'>";
                    foreach ($planImages as $index => $image) {
                        echo "<div class='mySlides'>";
                        echo "<img src='" . trim($image) . "' style='width:100%'>";
                        echo "</div>";
                    }
                    echo "<a class='prev' onclick='plusSlides(-1)'>&#10094;</a>";
                    echo "<a class='next' onclick='plusSlides(1)'>&#10095;</a>";
                    echo "</div>";

                    // Thumbnails
                    echo "<div class='thumbnail-row'>";
                    foreach ($planImages as $index => $image) {
                        echo "<div class='thumbnail-column'>";
                        echo "<img class='demo cursor' src='" . trim($image) . "' style='width:100%' onclick='currentSlide(" . ($index + 1) . ")' alt='Image'>";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</div>";

                    echo "<div class='plan-info'>";
                    echo "<h1>" . $planName . "</h1>";
                    echo "<p>" . $planDescription . "</p>";
                    echo "<p class='price'>Price: $" . $planPrice . "</p>";
                    echo "</div>";
                } else {
                    echo "<p>Plan not found.</p>";
                }
            } else {
                echo "<p>No plan ID provided.</p>";
            }
            ?>
        </div>

        <div class="right-section">
            <div class="order-form">
                <form action="add_order.php" method="POST" onsubmit="return validateDates();">
                    <!-- Hidden field to store the Plan ID -->
                    <input type="hidden" name="plan_id" value="<?php echo $plan_id; ?>">

                    <!-- Quantity -->
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" required>

                    <!-- Delivery Address -->
                    <label for="delivery_address">Delivery Address:</label>
                    <select name="delivery_address_id" id="delivery_address" required>
                        <?php
                        // Fetch the user's addresses
                        $cust_id = $_SESSION['Cust_ID'];
                        $sql = "SELECT * FROM address WHERE Cust_ID = :cust_id";
                        $statement = $db->prepare($sql);
                        $statement->bindParam(':cust_id', $cust_id, PDO::PARAM_INT);
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

                    <!-- Grand Total (display only, calculated dynamically with JavaScript) -->
                    <label for="grand_total">Grand Total:</label>
                    <input type="text" name="grand_total" id="grand_total" value="$<?php echo $planPrice; ?>" readonly>

                    <!-- Submit Button -->
                    <button type="submit" class="btn">Add Order</button>
                </form>
            </div>
        </div>
    </div>
    <?php include '../partials/footer.php'; ?>

    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("demo");
            if (n > slides.length) {slideIndex = 1}    
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.display = "block";  
            dots[slideIndex-1].className += " active";
        }

        // Set minimum start date as tomorrow and calculate duration
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const durationInput = document.getElementById('duration');
        const grandTotalInput = document.getElementById('grand_total');

        const today = new Date();
        today.setDate(today.getDate() + 1);
        const minDate = today.toISOString().split('T')[0];
        startDateInput.setAttribute('min', minDate);

        startDateInput.addEventListener('change', calculateDuration);
        endDateInput.addEventListener('change', calculateDuration);

        function calculateDuration() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDate && endDate && endDate >= startDate) {
                const timeDiff = Math.abs(endDate - startDate);
                const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1; // Include both start and end dates
                durationInput.value = daysDiff;

                const pricePerDay = <?php echo $planPrice; ?>;
                const grandTotal = pricePerDay * daysDiff;
                grandTotalInput.value = "$" + grandTotal.toFixed(2);
            } else {
                durationInput.value = '';
                grandTotalInput.value = "$<?php echo $planPrice; ?>";
            }
        }

        function validateDates() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (!startDate || !endDate || endDate < startDate) {
                alert("Please ensure the start date is before the end date.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
