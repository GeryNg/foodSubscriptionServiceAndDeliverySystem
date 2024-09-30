    <?php
    $page_title = "Add Other Restaurant";
    include_once '../partials/staff_nav.php';
    include_once '../partials/parse_add_restaurant.php';
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php echo htmlspecialchars($page_title); ?></title>
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
        <style>
            .container-fluid {
                margin-bottom: 5%;
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
                background: white;
                margin: 20px auto;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            label {
                margin-bottom: 10px;
                display: block;
                font-weight: bold;
                color: #666;
            }

            input[type="text"],
            input[type="number"],
            textarea,
            select {
                width: 100%;
                padding: 8px;
                margin-top: 5px;
                margin-bottom: 10px;
                box-sizing: border-box;
            }

            select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background: url('data:image/svg+xml;utf8,<svg fill="gray" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 10px center;
                background-color: white;
                background-size: 20px;
                padding-right: 40px;
            }

            .button1 {
                background-color: #5C67F2;
                color: white;
                border: none;
                padding: 10px 20px;
                text-transform: uppercase;
                cursor: pointer;
                float: right;
                margin-top: 10px;
                font-weight: bold;
                border-radius: 10px;
            }

            .button1:hover {
                background-color: #7a85ff;
            }

            form {
                overflow: auto;
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body>
        <div class="container-fluid">
            <h1>Add Other Restaurant</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../partials/seller_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Other Restaurant</li>
            </ol>
            <div class="container1">
                <form action="" method="post" enctype="multipart/form-data">
                    <br />
                    <?php if (isset($result) || !empty($form_errors)): ?>
                        <div>
                            <?php echo show_combined_messages($result, $form_errors); ?>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix" style="margin-bottom: 30px;"></div>

                    <label for="sellerName">Name:</label>
                    <input type="text" name="seller_name" class="form-control" id="sellerName" value="<?php if (isset($_POST['seller_name'])) echo htmlspecialchars($_POST['seller_name']); ?>" required>
                    <br>

                    <label for="profilePic">Restaurant Profile Picture:</label>
                    <input type="file" name="profile_pic" class="form-control" id="profilePic" accept=".jpg, .jpeg, .png" required>
                    <br /><br />

                    <label for="description">Describe your restaurant:</label>
                    <textarea name="description" class="form-control" id="description" required><?php if (isset($_POST['description'])) echo htmlspecialchars($_POST['description']); ?></textarea>
                    <br>

                    <label for="contactNum">Contact Number:</label>
                    <input type="text" name="contact_num" class="form-control" id="contactNum" value="<?php if (isset($_POST['contact_num'])) echo htmlspecialchars($_POST['contact_num']); ?>" required>
                    <br>

                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" class="form-control"  oninput="getCoordinates()" required>
                    <br>

                    <label for="unitNumber">Unit Number / Block / Door Number:</label>
                    <input type="text" name="unit_number" id="unitNumber" class="form-control" required>
                    <br>

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <label for="postcode">Postcode:</label>
                    <input type="text" name="postcode" id="postcode" class="form-control" readonly required>
                    <br>

                    <label for="city">City:</label>
                    <input type="text" name="city" id="city" class="form-control" readonly required>
                    <br>

                    <label for="state">State:</label>
                    <input type="text" name="state" id="state" class="form-control" readonly required>
                    <br>

                    <label for="bank">Bank Company:
                        <select name="bank" id="bank" class="form-control" required>
                            <option value="Affin Bank">Affin Bank</option>
                            <option value="Affin Islamic Bank">Affin Islamic Bank</option>
                            <option value="Alliance Bank Malaysia">Alliance Bank Malaysia</option>
                            <option value="Alliance Islamic Bank">Alliance Islamic Bank</option>
                            <option value="Ambank (M) Berhad">Ambank (M) Berhad</option>
                            <option value="Bank Islam Malaysia">Bank Islam Malaysia</option>
                            <option value="Bank of China (Malaysia)">Bank of China (Malaysia)</option>
                            <option value="CIMB Bank Berhad">CIMB Bank Berhad</option>
                            <option value="CIMB Islamic Bank">CIMB Islamic Bank</option>
                            <option value="Citibank Berhad">Citibank Berhad</option>
                            <option value="GX Bank Berhad">GX Bank Berhad</option>
                            <option value="Hong Leong Bank Berhad">Hong Leong Bank Berhad</option>
                            <option value="Hong Leong Islamic Bank">Hong Leong Islamic Bank</option>
                            <option value="HSBC Bank Malaysia">HSBC Bank Malaysia</option>
                            <option value="Maybank Berhad">Maybank Berhad</option>
                            <option value="Maybank Islamic Berhad">Maybank Islamic Berhad</option>
                            <option value="OCBC Bank (Malaysia)">OCBC Bank (Malaysia)</option>
                            <option value="Public Bank Berhad">Public Bank Berhad</option>
                            <option value="Public Islamic Bank">Public Islamic Bank</option>
                            <option value="RHB Bank Berhad">RHB Bank Berhad</option>
                            <option value="RHB Islamic Bank Berhad">RHB Islamic Bank Berhad</option>
                        </select>
                    </label>
                    <br />

                    <label for="bankAccount">Bank Account:</label>
                    <input type="number" name="bank_account_number" class="form-control" id="bankAccount" value="<?php if (isset($_POST['bank_account_number'])) echo htmlspecialchars($_POST['bank_account_number']); ?>" required>
                    <br>

                    <label for="documents">Document needed for approval (NRIC, SSM, etc.):</label>
                    <input type="file" name="images[]" class="form-control" id="documents" accept=".jpg, .jpeg, .png" multiple>
                    <br><br />

                    <button type="submit" name="activeAccountBtn" class="button1" id="activateBtn" value="Activate Account">Add Restaurant</button>
                    <br /><br />
                </form>
            </div>
        </div>

        <div id="myModal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="img01">
            <div id="caption"></div>
        </div>

        <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
        <script>
            function initAutocomplete() {
                const addressField = document.getElementById('address');
                const autocomplete = new google.maps.places.Autocomplete(addressField);

                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    if (!place.geometry) {
                        return;
                    }

                    const location = place.geometry.location;
                    document.getElementById('latitude').value = location.lat();
                    document.getElementById('longitude').value = location.lng();

                    let postcode = '',
                        city = '',
                        state = '';

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
                    });

                    document.getElementById('city').value = city;
                    document.getElementById('state').value = state;

                    if (postcode) {
                        document.getElementById('postcode').value = postcode;
                        document.getElementById('postcode').readOnly = true;
                    } else {
                        document.getElementById('postcode').value = "";
                        document.getElementById('postcode').readOnly = false;
                        alert('No postcode found for this address. Please enter manually.');
                    }
                });
            }

            google.maps.event.addDomListener(window, 'load', initAutocomplete);
        </script>
    </body>

    </html>