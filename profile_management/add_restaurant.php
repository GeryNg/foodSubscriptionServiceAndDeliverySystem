<?php
$page_title = "Add Other Restaurant";
include_once '../partials/staff_nav.php';
include_once '../partials/parse_add_restaurant.php';
?>

<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
                <label>Name: </label>
                <input type="text" name="seller_name" class="form-control" value="<?php if (isset($_POST['seller_name'])) echo htmlspecialchars($_POST['seller_name']); ?>">
                <br>

                <label>Restaurant Profile Picture: </label>
                <input type="file" name="profile_pic" class="form-control" accept=".jpg, .jpeg, .png" multiple>
                <br /><br />

                <label>Describe your restaurant: </label>
                <textarea name="description" class="form-control"><?php if (isset($_POST['description'])) echo htmlspecialchars($_POST['description']); ?></textarea>
                <br>
                <label>Contact Number: </label>
                <input type="text" name="contact_num" class="form-control" value="<?php if (isset($_POST['contact_num'])) echo htmlspecialchars($_POST['contact_num']); ?>">
                <br>

                <label>Address: </label>
                <input type="text" name="address" class="form-control"><?php if (isset($_POST['address'])) echo htmlspecialchars($_POST['address']); ?>
                <br>

                <label>Postcode: </label>
                <input type="text" name="postcode" id="postcode" class="form-control" oninput="fetchCityState()">
                <div id="postcodeError" style="color: red;"></div>
                <br>

                <label>City: </label>
                <input type="text" name="city" id="city" class="form-control" readonly>
                <br>

                <label>State: </label>
                <input type="text" name="state" id="state" class="form-control" readonly>
                <br>

                <label>Bank Company:
                    <select name="bank" id="bank" class="form-control">
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

                <label>Bank Account: </label>
                <input type="number" name="bank_account_number" class="form-control" value="<?php if (isset($_POST['bank_account_number'])) echo htmlspecialchars($_POST['bank_account_number']); ?>">
                <br>

                <label>Document needed for approval (Business owner/Partner's NRIC, e-SSM Business Profile, Certificate of Registration of Business (Form D), Halal License, Liquor license, Business Premises License & other): </label>
                <input type="file" name="images[]" class="form-control" accept=".jpg, .jpeg, .png" multiple>
                <br><br>

                <button type="submit" name="activeAccountBtn" class="button1" value="Activate Account">Activate Account</button>
                <br />
                <br />
            </form>
        </div>
    </div>
    <script>
        function fetchCityState() {
            var postcode = document.getElementById('postcode').value;
            var cityField = document.getElementById('city');
            var stateField = document.getElementById('state');
            var postcodeError = document.getElementById('postcodeError');

            if (postcode.length > 0) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../partials/parse_add_restaurant.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                xhr.onreadystatechange = function() {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        try {
                            var response = JSON.parse(this.responseText);

                            if (response.success) {
                                cityField.value = response.city;
                                stateField.value = response.state;
                                postcodeError.textContent = "";
                            } else {
                                cityField.value = "";
                                stateField.value = "";
                                postcodeError.textContent = "This postcode area is not supported yet.";
                            }
                        } catch (e) {
                            cityField.value = "";
                            stateField.value = "";
                            postcodeError.textContent = "Unable to process response.";
                        }
                    }
                };
                xhr.send("postcode=" + encodeURIComponent(postcode));
            } else {
                cityField.value = "";
                stateField.value = "";
                postcodeError.textContent = "";
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.js"></script>
</body>

</html>