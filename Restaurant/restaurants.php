<?php include '../resource/Database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <link rel="stylesheet" href="../css/restaurant.css">
    <link rel="icon" type="image/x-icon" href="../image/logo-circle.png">
</head>
<body>
    <?php include '../partials/headers.php'; ?>
    <div class="container">
        <h1>All Restaurants</h1>
        <form method="GET" action="" class="search-bar">
            <input type="text" name="search" placeholder="Search for restaurants, cuisines, and dishes" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M16.17 11A6.5 6.5 0 1111 16.17 6.5 6.5 0 0116.17 11z" />
                </svg>
            </button>
        </form>

        <section class="articles">
            <?php
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

            // Modify the SQL query to only select sellers with access = 'verify'
            $sql = "SELECT seller.profile_pic, seller.name, seller.detail, seller.address, seller.id FROM seller WHERE seller.access = 'verify'";
            if (!empty($searchTerm)) {
                $sql .= " AND seller.name LIKE :searchTerm";
            }
            $sql .= " ORDER BY seller.name";

            $statement = $db->prepare($sql);
            if (!empty($searchTerm)) {
                $statement->bindValue(':searchTerm', '%' . $searchTerm . '%');
            }
            $statement->execute();

            while ($row = $statement->fetch()) {
                $profile_pic = htmlspecialchars($row['profile_pic'], ENT_QUOTES, 'UTF-8');
                $name = htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8');
                $detail = htmlspecialchars($row["detail"], ENT_QUOTES, 'UTF-8');
                $address = htmlspecialchars($row["address"], ENT_QUOTES, 'UTF-8');
                $id = htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8');
                
                echo "<article class='restaurant-card'>";
                echo "<div class='article-wrapper'>";
                echo "<figure>"; 
                echo "<img src='" . $profile_pic . "' alt='profile_pic' class='profile-pic'/>";
                echo "</figure>";
                echo "<div class='article-body'>";
                echo "<h2>" . $name . "</h2>";
                echo "<p class='detail'>" . $detail . "</p>";              
                echo "<p class='address'>Address: " . $address . "</p>";
                echo "<a href='restaurant_plan.php?id=" . $id . "' class='read-more'>Read more <span class='sr-only'>about " . $name . "</span>";
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
    <?php include '../partials/footer.php'; ?>
</body>
</html>
