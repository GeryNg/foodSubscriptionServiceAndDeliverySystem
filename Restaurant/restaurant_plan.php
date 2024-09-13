<?php include '../resource/Database.php'; ?>
<?php include '../resource/session.php'; ?> 

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
    <div class="container1" style="margin-top: 3%;">
        <?php
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');
            
            // Fetch restaurant details
            $sql = "SELECT seller.profile_pic, seller.name, seller.detail, seller.address 
                    FROM seller 
                    WHERE seller.id = :id;";
            $statement = $db->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            
            if ($row = $statement->fetch()) {
                $profile_pic = htmlspecialchars($row['profile_pic'], ENT_QUOTES, 'UTF-8');
                $name = htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8');
                $detail = htmlspecialchars($row["detail"], ENT_QUOTES, 'UTF-8');
                $address = htmlspecialchars($row["address"], ENT_QUOTES, 'UTF-8');
                
                echo "<div class='restaurant-info'>";
                echo "<img src='" . $profile_pic . "' alt='profile_image' class='avatar_image'/>";
                echo "<div class='restaurant-details'>";
                echo "<h1>" . $name . "</h1>";
                echo "<p>" . $detail . "</p>";              
                echo "<p>Address: " . $address . "</p>";
                echo "</div>";
                
                // Fetch the average rating and count the number of feedback entries
                $sql_avg_rating = "SELECT AVG(Rating) as avg_rating, COUNT(*) as review_count
                                   FROM feedback f 
                                   JOIN order_cust oc ON f.Order_ID = oc.Order_ID 
                                   WHERE oc.Plan_ID IN (SELECT id FROM plan WHERE seller_id = :seller_id)";
                $statement_avg = $db->prepare($sql_avg_rating);
                $statement_avg->bindParam(':seller_id', $id, PDO::PARAM_INT);
                $statement_avg->execute();
                $rating_result = $statement_avg->fetch();
                
                $avg_rating = round($rating_result['avg_rating'], 1);
                $review_count = $rating_result['review_count'];

                // Pass the average rating and review count to the JavaScript
                echo "<div id='star-rating-container' class='star-rating' data-rating='" . $avg_rating . "' data-count='" . $review_count . "'></div>";
                
                echo "</div>"; // Closing restaurant-info div
                
                // Fetch plans
                $sql = "SELECT plan.id, plan.image_urls, plan.plan_name, plan.description, plan.price 
                        FROM plan 
                        WHERE plan.seller_id = :id;";
                $statement = $db->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
                $statement->execute();

                echo "<h2>Plans</h2>";
                echo "<section class='articles'>";
                
                while ($plan = $statement->fetch()) {
                    $planId = htmlspecialchars($plan['id'], ENT_QUOTES, 'UTF-8');
                    $planImageUrls = htmlspecialchars($plan['image_urls'], ENT_QUOTES, 'UTF-8');
                    $planImages = explode(',', $planImageUrls); // Split the image URLs by comma
                    $firstPlanImage = trim($planImages[0]); // Get the first image URL and trim any extra whitespace

                    $planName = htmlspecialchars($plan['plan_name'], ENT_QUOTES, 'UTF-8');
                    $planDescription = htmlspecialchars($plan['description'], ENT_QUOTES, 'UTF-8');
                    $planPrice = htmlspecialchars($plan['price'], ENT_QUOTES, 'UTF-8');
                    
                    echo "<article>";
                    echo "<div class='article-wrapper'>";
                    echo "<figure>";
                    echo "<img src='" . $firstPlanImage . "' alt='plan_image' class='plan_image'/>";
                    echo "</figure>";
                    echo "<div class='article-body'>";
                    echo "<h2>" . $planName . "</h2>";
                    echo "<p>" . $planDescription . "</p>";
                    echo "<p>Price: RM" . $planPrice . "</p>";
                    
                    // Check if the user is logged in before displaying the "Read more" link
                    if (isset($_SESSION['username'])) {
                        echo "<a href='../order_delivery/orders.php?plan_id=" . $planId . "' class='read-more'>Read more <span class='sr-only'>about " . $planName . "</span>";
                    } else {
                        echo "<a href='../login_management/login.php' class='read-more'>Log in to order &nbsp;&nbsp;<span class='sr-only'>about " . $planName . "</span>";
                    }

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
    <?php include '../partials/footer.php'; ?>

    <!-- JavaScript for Star Rating -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starRatingContainer = document.getElementById('star-rating-container');
            const rating = parseFloat(starRatingContainer.getAttribute('data-rating'));
            const reviewCount = starRatingContainer.getAttribute('data-count');

            // Build the star rating display
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '&#9733;'; // Filled star
                } else if (i - rating < 1) {
                    stars += '&#9733;'; // Partially filled star (optional, can be a half star here)
                } else {
                    stars += '&#9734;'; // Empty star
                }
            }

            // Add the rating score and count display
            const ratingDisplay = `<span class='rating-score'>${rating}</span>/5 <span class='rating-count'>(${reviewCount}+)</span>`;

            starRatingContainer.innerHTML = `<span class="star">&#9733;</span> ${ratingDisplay}`;
        });
    </script>
</body>
</html>
