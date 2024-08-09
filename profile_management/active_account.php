<?php
$page_title = "Activate Account";
include_once '../partials/staff_nav.php';
include_once '../partials/parseActiveAccount.php';
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f3f3;
        }
        .main-content {
            padding: 20px;
            background-color: #f3f3f3;
        }
        .container {
            background: white;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        label {
            margin-bottom: 10px;
            display: block;
            color: #666;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        button {
            background-color: #5C67F2;
            color: white;
            border: none;
            padding: 10px 20px;
            text-transform: uppercase;
            cursor: pointer;
            float: right;
            margin-top: 10px;
        }
        button:hover {
            background-color: #7a85ff;
        }
        form {
            overflow: auto;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container">
            <h1>Activate Account</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <br />

                <?php if (isset($result) || !empty($form_errors)): ?>
                    <div>
                        <?php echo show_combined_messages($result, $form_errors); ?>
                    </div>
                <?php endif; ?>
                <div class="clearfix"></div>

                <label>Name: <input type="text" name="seller_name" value="<?php if (isset($_POST['seller_name']))
                    echo htmlspecialchars($_POST['seller_name']); ?>"></label><br>
                <label>Restaurant Profile Picture: </label>
                    <input type="file" name="profile_pic" accept=".jpg, .jpeg, .png" multiple><br><br>
                <label>Describe your restaurant: <textarea name="description"><?php if (isset($_POST['description']))
                    echo htmlspecialchars($_POST['description']); ?></textarea></label><br>
                <label>Contact Number: <input type="text" name="contact_num" value="<?php if (isset($_POST['contact_num']))
                    echo htmlspecialchars($_POST['contact_num']); ?>"></label><br>
                <label>Address: <textarea name="address"><?php if (isset($_POST['address']))
                    echo htmlspecialchars($_POST['address']); ?></textarea></label><br>
                <label>Bank Company:
                    <select name="bank" id="bank">
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
                <label>Bank Account: <input type="number" name="bank_account_number" value="<?php if (isset($_POST['bank_account_number']))
                    echo htmlspecialchars($_POST['bank_account_number']); ?>"></label><br>

                <label>Document needed for approval (Business owner/Partner’s NRIC, e-SSM Business Profile, Certificate of Registration of Business (Form D), Halal License, Liquor license, Business Premises License & other): </label><br>
                <input type="file" name="images[]" accept=".jpg, .jpeg, .png" multiple><br><br>
                <button type="submit" name="activeAccountBtn" value="Activate Account">Activate Account</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.js"></script>
</body>
</html>
