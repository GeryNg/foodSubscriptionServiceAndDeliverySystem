<?php include '../resource/Database.php'; ?>
<?php include '../resource/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Addresses</title>
    <link rel="stylesheet" href="../css/address_management.css">
    <link rel="icon" type="image/x-icon" href="../image/logo-circle.png">
</head>
<body>
    <?php include '../partials/headers.php'; ?>   
    <div class="container">
        <h2>My Addresses</h2>
        <div class="address-container">
            <div class="add-new-address">
                <a href="add_address.php" class="btn-add">+ Add New Address</a>
            </div>

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

                echo "<div class='address-card'>";
                echo "<p><strong>$line1</strong> $line2, $city, $state, $postal_code, $country</p>";

                echo "<div class='address-actions'>";
                echo "<a href='edit_address.php?address_id=$address_id'>Edit</a>";
                echo "<a href='delete_address.php?address_id=$address_id' class='delete-link'>Delete</a>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br>
    <br>
    <br>
    <br>
    <br>
    <?php include '../partials/footer.php'; ?>
</body>
</html>
