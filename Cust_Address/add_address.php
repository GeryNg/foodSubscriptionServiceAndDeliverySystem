<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';
include_once '../resource/utilities.php';
include_once 'parseAdd_address.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Address</title>
    <link rel="stylesheet" href="../css/address_management.css">
</head>

<body>
    <?php include '../partials/headers.php'; ?>

    <div class="container1">
        <h2>Add New Address</h2>
        <?php if (isset($result)) echo $result; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="line1">Unit Number / Block</label>
                <input type="text" name="line1" id="line1" required>
            </div>
            <div class="form-group">
                <label for="line2">Address</label>
                <input type="text" name="line2" id="line2" required>
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code</label>
                <input type="text" name="postal_code" id="postal_code" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" id="city" required readonly>
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <input type="text" name="state" id="state" required readonly>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" name="country" id="country" required readonly>
            </div>
            <input type="hidden" name="latitude" id="latitude" hidden>
            <input type="hidden" name="longitude" id="longitude" hidden>

            <button type="submit" name="addAddressBtn" class="btn-submit">Add Address</button>
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

            // Get latitude and longitude from place.geometry
            const latitude = place.geometry.location.lat();
            const longitude = place.geometry.location.lng();

            // Set the latitude and longitude fields
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;

            let postcode = '',
                city = '',
                state = '',
                country = '';

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

            document.getElementById('postal_code').value = postcode;
            document.getElementById('city').value = city;
            document.getElementById('state').value = state;
            document.getElementById('country').value = country;
        });
    }

    google.maps.event.addDomListener(window, 'load', initAutocomplete);
</script>

</html>