<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Plan</title>
    <link rel="stylesheet" href="../css/order.css">
    <script>
        // Function to set the minimum start date
        function setMinStartDate() {
            const startDateInput = document.getElementById('start_date');
            const currentDate = new Date();
            // Add one day to the current date
            currentDate.setDate(currentDate.getDate() + 1);
            // Format the date as YYYY-MM-DD
            const minDate = currentDate.toISOString().split('T')[0];
            // Set the minimum date attribute
            startDateInput.setAttribute('min', minDate);
        }

        // Function to validate the start date
        function validateStartDate() {
            const startDateInput = document.getElementById('start_date');
            const startDate = new Date(startDateInput.value);
            const currentDate = new Date();
            currentDate.setDate(currentDate.getDate() + 1);
            // Check if the selected date is earlier than the minimum date
            if (startDate < currentDate) {
                alert("The start date must be at least one day later than the current date.");
                startDateInput.value = ''; // Clear the input
                return false;
            }
            return true;
        }

        // Initialize the minimum start date when the page loads
        window.onload = function() {
            setMinStartDate();
        };
    </script>
</head>
<body>
    <?php include '../partials/headers.php'; ?>
    <div class="container">
        <div class="plan-details">
            <div class="plan-images">
                <!-- Image slideshow for the plan -->
                <img src="path_to_image1.jpg" alt="Plan Image" class="plan-image">
                <img src="path_to_image2.jpg" alt="Plan Image" class="plan-image">
                <img src="path_to_image3.jpg" alt="Plan Image" class="plan-image">
            </div>
            <div class="plan-info">
                <h1>Package A (5 days)</h1>
                <p>Package A includes 1 bowl of rice, 1 vegetarian dish, 1 meat or fish, and 1 random dish of egg, tofu, or Aunty Lau’s handmade.</p>
                <p>Price: $98.05</p>
            </div>
        </div>

        <div class="order-form">
            <form action="#" method="POST" onsubmit="return validateStartDate();">
                <!-- Quantity -->
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" required>

                <!-- Delivery Address -->
                <label for="delivery_address">Delivery Address:</label>
                <select name="delivery_address_id" id="delivery_address" required>
                    <option value="1">Address 1</option>
                    <option value="2">Address 2</option>
                    <!-- Add more addresses dynamically -->
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

                <!-- Grand Total (display only, calculated dynamically with JavaScript) -->
                <label for="grand_total">Grand Total:</label>
                <input type="text" name="grand_total" id="grand_total" value="$98.05" readonly>

                <!-- Submit Button -->
                <button type="submit" class="btn">Add Order</button>
            </form>
        </div>
    </div>
    <?php include '../partials/footer.php'; ?>
</body>
</html>
