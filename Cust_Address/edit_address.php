<?php
include '../resource/session.php';
include '../resource/Database.php';

// Check if the address_id is set in the query string
if (isset($_GET['address_id']) && is_numeric($_GET['address_id'])) {
    $address_id = intval($_GET['address_id']);

    // Fetch the address details from the database
    $sql = "SELECT * FROM address WHERE address_id = :address_id";
    $statement = $db->prepare($sql);
    $statement->bindParam(':address_id', $address_id, PDO::PARAM_INT);
    $statement->execute();
    $address = $statement->fetch();

    if (!$address) {
        echo "<script>alert('Address not found.'); window.location.href='address_management.php';</script>";
        exit();
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $line1 = htmlspecialchars($_POST['line1'], ENT_QUOTES, 'UTF-8');
        $line2 = htmlspecialchars($_POST['line2'], ENT_QUOTES, 'UTF-8');
        $city = htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8');
        $state = htmlspecialchars($_POST['state'], ENT_QUOTES, 'UTF-8');
        $postal_code = htmlspecialchars($_POST['postal_code'], ENT_QUOTES, 'UTF-8');
        $country = htmlspecialchars($_POST['country'], ENT_QUOTES, 'UTF-8');
        $latitude = htmlspecialchars($_POST['latitude'], ENT_QUOTES, 'UTF-8');
        $longitude = htmlspecialchars($_POST['longitude'], ENT_QUOTES, 'UTF-8');

        // Update the address in the database
        $sql = "UPDATE address SET line1 = :line1, line2 = :line2, city = :city, state = :state, postal_code = :postal_code, country = :country, latitude = :latitude, longitude = :longitude WHERE address_id = :address_id";
        $statement = $db->prepare($sql);
        $statement->bindParam(':line1', $line1, PDO::PARAM_STR);
        $statement->bindParam(':line2', $line2, PDO::PARAM_STR);
        $statement->bindParam(':city', $city, PDO::PARAM_STR);
        $statement->bindParam(':state', $state, PDO::PARAM_STR);
        $statement->bindParam(':postal_code', $postal_code, PDO::PARAM_STR);
        $statement->bindParam(':country', $country, PDO::PARAM_STR);
        $statement->bindParam(':latitude', $latitude, PDO::PARAM_STR);
        $statement->bindParam(':longitude', $longitude, PDO::PARAM_STR);
        $statement->bindParam(':address_id', $address_id, PDO::PARAM_INT);

        if ($statement->execute()) {
            // If the update is successful, display a success message using JavaScript
            echo "<script>alert('Address updated successfully!'); window.location.href='address_management.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to update address. Please try again.'); window.location.href='edit_address.php?address_id=$address_id';</script>";
            exit();
        }
    }
} else {
    echo "<script>alert('Invalid address ID.'); window.location.href='address_management.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Address</title>
    <link rel="stylesheet" href="../css/address_management.css">
</head>
<body>
    <?php include '../partials/headers.php'; ?>

    <div class="container1">
        <h2>Edit Address</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="line1">Address Line 1</label>
                <input type="text" name="line1" id="line1" value="<?php echo htmlspecialchars($address['line1'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="line2">Address Line 2</label>
                <input type="text" name="line2" id="line2" value="<?php echo htmlspecialchars($address['line2'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($address['city'], ENT_QUOTES, 'UTF-8'); ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <input type="text" name="state" id="state" value="<?php echo htmlspecialchars($address['state'], ENT_QUOTES, 'UTF-8'); ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code</label>
                <input type="text" name="postal_code" id="postal_code" value="<?php echo htmlspecialchars($address['postal_code'], ENT_QUOTES, 'UTF-8'); ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($address['country'], ENT_QUOTES, 'UTF-8'); ?>" required readonly>
            </div>

            <input type="hidden" name="latitude" id="latitude" value="<?php echo htmlspecialchars($address['latitude'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="longitude" id="longitude" value="<?php echo htmlspecialchars($address['longitude'], ENT_QUOTES, 'UTF-8'); ?>">

            <button type="submit" class="btn-submit">Update Address</button>
            <a href="address_management.php" class="btn-cancel">Cancel</a>
        </form>
    </div>

    <?php include '../partials/footer.php'; ?>
</body>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5sNxHZwLHZ4KigiYcQKGjbrEVhbKLNFo&libraries=places"></script>
    <script>
        function initAutocomplete() {
            const addressField = document.getElementById('line2');
            const autocomplete = new google.maps.places.Autocomplete(addressField);

            autocomplete.setFields(['address_component', 'geometry']);

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    alert("No details available for input: '" + place.name + "'");
                    return;
                }

                const latitude = place.geometry.location.lat();
                const longitude = place.geometry.location.lng();

                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;

                let postcode = '', city = '', state = '', country = '';

                place.address_components.forEach(function(component) {
                    const types = component.types;
                    if (types.includes('postal_code')) {
                        postcode = component.long_name;
                    }
                    if (types.includes('locality')) {
                        city = component.long_name;
                    } else if (types.includes('administrative_area_level_2')) {
                        city = component.long_name;
                    }
                    if (types.includes('administrative_area_level_1')) {
                        state = component.long_name;
                    }
                    if (types.includes('country')) {
                        country = component.long_name;
                    }
                });

                if (postcode) {
                    document.getElementById('postal_code').value = postcode;
                    document.getElementById('postal_code').readOnly = true;
                } else {
                    document.getElementById('postal_code').value = "";
                    document.getElementById('postal_code').readOnly = false;
                    alert('No postcode found for this address. Please enter manually.');
                }
            });
        }

        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    </script>
</html>
