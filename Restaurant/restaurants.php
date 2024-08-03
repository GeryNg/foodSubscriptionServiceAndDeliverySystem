<!-- Restaurant/restaurants.php -->
<?php include '../resource/Database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <link rel="stylesheet" href="../css/restaurant.css">
</head>
<body>
    <?php include '../partials/headers.php'; ?>
    <div class="container">
        <h1>Restaurants</h1>
        <section class="articles">
            <?php
            $sql = "SELECT users.avatar, seller.name, seller.detail, seller.address FROM seller JOIN users ON seller.user_id = users.id;";
            $statement = $db->prepare($sql);
            $statement->execute();
            
            while ($row = $statement->fetch()) {
                echo "<article>";
                echo "<div class='article-wrapper'>";
                echo "<figure>";
                $avatar = htmlspecialchars($row['avatar'], ENT_QUOTES, 'UTF-8');
                echo "<img src='" . $avatar . "' alt='avatar_image' class='avatar_image'/>";
                echo "</figure>";
                echo "<div class='article-body'>";
                echo "<h2>" . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . "</h2>";
                echo "<p>" . htmlspecialchars($row["detail"], ENT_QUOTES, 'UTF-8') . "</p>";              
                echo "<p>Address: " . htmlspecialchars($row["address"], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<a href='#' class='read-more'>View Our Menu <span class='sr-only'>about " . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . "</span>";
                echo "<svg xmlns='http://www.w3.org/2000/svg' class='icon' viewBox='0 0 20 20' fill='currentColor'>";
                echo "<path fill-rule='evenodd' d='M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z' clip-rule='evenodd' />";
                echo "</svg>";
                echo "</a>";
                echo "</div>";
                echo "</div>";
                echo "</article>";
            }
            ?>
        </section>
    </div>
</body>
</html>
