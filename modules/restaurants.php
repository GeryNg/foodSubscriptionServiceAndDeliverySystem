<!-- modules/restaurants.php -->
<?php include '../resource/Database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../partials/headers.php'; ?>
    <div class="container">
        <h1>Restaurants</h1>
        <div class="restaurant-list">
            <?php
            $sql = "SELECT * FROM seller";
            $statement = $db->prepare($sql);
            $statement->execute();
            
            while($row = $statement->fetch()) {
                echo "<div class='restaurant'>";
                echo "<h2>" . $row["name"] . "</h2>";
                echo "<p>" . $row["detail"] . "</p>";
                echo "<p>Contact Number: " . $row["contact_number"] . "</p>";
                echo "<p>Address: " . $row["address"] . "</p>";
                echo "</div>";
            }            
            ?>
            
        </div>
    </div>
    
</body>
</html>
