<!-- modules/restaurant_plan.php -->
<?php include '../resource/Database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Plan</title>
    <link rel="stylesheet" href="../css/restaurant_plan.css">
</head>
<body>
    <?php include '../partials/headers.php'; ?>
    <div class="container">
        <?php
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');
            
            // Fetch restaurant details
            $sql = "SELECT users.avatar, seller.name, seller.detail, seller.address 
                    FROM seller 
                    JOIN users ON seller.user_id = users.id 
                    WHERE seller.id = :id;";
            $statement = $db->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            
            if ($row = $statement->fetch()) {
                $avatar = htmlspecialchars($row['avatar'], ENT_QUOTES, 'UTF-8');
                $name = htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8');
                $detail = htmlspecialchars($row["detail"], ENT_QUOTES, 'UTF-8');
                $address = htmlspecialchars($row["address"], ENT_QUOTES, 'UTF-8');
                
                echo "<h1>" . $name . "</h1>";
                echo "<img src='" . $avatar . "' alt='avatar_image' class='avatar_image'/>";
                echo "<p>" . $detail . "</p>";              
                echo "<p>Address: " . $address . "</p>";
                
                // Fetch plans
                $sql = "SELECT plan.image_urls, plan.plan_name, plan.description, plan.price 
                        FROM plan 
                        WHERE plan.seller_id = :id;";
                $statement = $db->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
                $statement->execute();

                echo "<h2>Plans</h2>";
                echo "<section class='articles'>";
                
                while ($plan = $statement->fetch()) {
                    $planImage = htmlspecialchars($plan['image_urls'], ENT_QUOTES, 'UTF-8');
                    $planName = htmlspecialchars($plan['plan_name'], ENT_QUOTES, 'UTF-8');
                    $planDescription = htmlspecialchars($plan['description'], ENT_QUOTES, 'UTF-8');
                    $planPrice = htmlspecialchars($plan['price'], ENT_QUOTES, 'UTF-8');
                    
                    echo "<article>";
                    echo "<div class='article-wrapper'>";
                    echo "<figure>";
                    echo "<img src='" . $planImage . "' alt='plan_image' class='plan_image'/>";
                    echo "</figure>";
                    echo "<div class='article-body'>";
                    echo "<h2>" . $planName . "</h2>";
                    echo "<p>" . $planDescription . "</p>";
                    echo "<p>Price: $" . $planPrice . "</p>";
                    echo "<a href='#' class='read-more'>Read more <span class='sr-only'>about " . $planName . "</span>";
                    echo "<svg xmlns='http://www.w3.org/2000/svg' class='icon' viewBox='0 0 20 20' fill='currentColor'>";
                    echo "<path fill-rule='evenodd' d='M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z' clip-rule='evenodd' />";
                    echo "</svg>";
                    echo "</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</article>";
                }
                
                echo "</section>";
            } else {
                echo "<p>Restaurant not found.</p>";
            }
        } else {
            echo "<p>No restaurant ID provided.</p>";
        }
        ?>
    </div>
</body>
</html>
