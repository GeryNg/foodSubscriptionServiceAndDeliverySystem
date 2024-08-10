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
        <?php
        if (isset($result)) echo $result;
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="line1">Address Line 1</label>
                <input type="text" name="line1" id="line1" required>
            </div>
            <div class="form-group">
                <label for="line2">Address Line 2</label>
                <input type="text" name="line2" id="line2">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" id="city" required>
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <input type="text" name="state" id="state" required>
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code</label>
                <input type="text" name="postal_code" id="postal_code" required>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" name="country" id="country" required>
            </div>
            <button type="submit" name="addAddressBtn" class="btn-submit">Add Address</button>
            <a href="address_management.php" class="btn-cancel">Cancel</a>
        </form>
    </div>

    <?php include '../partials/footer.php'; ?>
</body>
</html>
