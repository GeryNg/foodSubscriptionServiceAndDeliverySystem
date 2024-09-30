<?php
$page_title = "Restaurant Plan";
include '../resource/Database.php';
include '../resource/session.php';
include '../partials/headers.php';

// Initialize variables
$restaurant = null;
$plans = [];
$avg_rating = $review_count = null;

// Check if the restaurant ID is provided
if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');

    // Fetch restaurant details
    $sql = "SELECT seller.profile_pic, seller.name, seller.detail, seller.address 
            FROM seller 
            WHERE seller.id = :id;";
    $statement = $db->prepare($sql);
    $statement->bindParam(':id', $id, PDO::PARAM_STR_CHAR);
    $statement->execute();

    $restaurant = $statement->fetch();

    if ($restaurant) {
        $profile_pic = htmlspecialchars($restaurant['profile_pic'], ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars($restaurant["name"], ENT_QUOTES, 'UTF-8');
        $detail = htmlspecialchars($restaurant["detail"], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($restaurant["address"], ENT_QUOTES, 'UTF-8');

        // Fetch average rating and review count
        $sql_avg_rating = "SELECT AVG(Rating) as avg_rating, COUNT(*) as review_count
                           FROM feedback f 
                           JOIN order_cust oc ON f.Order_ID = oc.Order_ID 
                           WHERE oc.Plan_ID IN (SELECT id FROM plan WHERE seller_id = :seller_id)";
        $statement_avg = $db->prepare($sql_avg_rating);
        $statement_avg->bindParam(':seller_id', $id, PDO::PARAM_STR_CHAR);
        $statement_avg->execute();
        $rating_result = $statement_avg->fetch();

        $avg_rating = round($rating_result['avg_rating'], 1);
        $review_count = $rating_result['review_count'];

        // Fetch plans
        $sql_plans = "SELECT plan.id, plan.image_urls, plan.plan_name, plan.description, plan.price 
        FROM plan 
        WHERE plan.seller_id = :id AND plan.status = 'active'";
        $statement_plans = $db->prepare($sql_plans);
        $statement_plans->bindParam(':id', $id, PDO::PARAM_STR);
        $statement_plans->execute();

        while ($plan = $statement_plans->fetch()) {
            $plans[] = [
                'id' => htmlspecialchars($plan['id'], ENT_QUOTES, 'UTF-8'),
                'image_urls' => htmlspecialchars($plan['image_urls'], ENT_QUOTES, 'UTF-8'),
                'plan_name' => htmlspecialchars($plan['plan_name'], ENT_QUOTES, 'UTF-8'),
                'description' => htmlspecialchars($plan['description'], ENT_QUOTES, 'UTF-8'),
                'price' => htmlspecialchars($plan['price'], ENT_QUOTES, 'UTF-8')
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Plan</title>
    <link rel="stylesheet" href="../css/restaurant_plan.css">
    <link rel="icon" type="image/x-icon" href="../image/logo-circle.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container1" style="margin-top: 3%;">
        <?php if ($restaurant): ?>
            <div class='restaurant-info'>
                <img src='<?php echo $profile_pic; ?>' alt='profile_image' class='avatar_image' />
                <div class='restaurant-details'>
                    <h1><?php echo $name; ?></h1>
                    <p><?php echo $detail; ?></p>
                    <p>Address: <?php echo $address; ?></p>
                    <a id="generate-qr-btn" class="share-icon" href="javascript:void(0);" style="font-size: 24px;">
                        <i class="fas fa-share-alt"></i>
                    </a>
                    <script>
                        document.getElementById('generate-qr-btn').addEventListener('click', function() {
                            const currentUrl = window.location.href;
                            const qrCodeUrl = `https://quickchart.io/qr?text=${encodeURIComponent(currentUrl)}&size=200`;
                            const description = "Check out this restaurant!";

                            const whatsappShareUrl = `https://wa.me/?text=${encodeURIComponent(description + ' ' + currentUrl)}`;
                            const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}`;
                            const emailShareUrl = `mailto:?subject=${encodeURIComponent(description)}&body=${encodeURIComponent('Check this out: ' + currentUrl)}`;

                            Swal.fire({
                                title: 'Share this page',
                                html: `
                                    <div id="qrCodeContainer">
                                        <img id="qrCodeImage" src="${qrCodeUrl}" alt="QR Code" style="width: 200px; height: 200px; margin-bottom: 10px;" />
                                        <p>${description}</p>
                                        <div style="display: flex; justify-content: center; gap: 15px; margin-top: 10px;">
                                            <a href="${whatsappShareUrl}" target="_blank" style="font-size: 24px;">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                            <a href="${facebookShareUrl}" target="_blank" style="font-size: 24px;">
                                                <i class="fab fa-facebook"></i>
                                            </a>
                                            <a href="${emailShareUrl}" target="_blank" style="font-size: 24px;">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </div>
                                        <button id="downloadBtn" class="swal2-styled swal2-confirm" style="background-color: #5c67f2;">Download QR Code</button>
                                    </div>
                                `,
                                showCloseButton: true,
                                confirmButtonText: 'Close',
                                didOpen: () => {
                                    document.getElementById('downloadBtn').addEventListener('click', function() {
                                        const qrCodeImage = document.getElementById('qrCodeImage').src;
                                        const link = document.createElement('a');
                                        link.href = qrCodeImage;
                                        link.download = 'QR_Code.png';
                                        link.style.display = 'none';
                                        document.body.appendChild(link);
                                        link.click();
                                        document.body.removeChild(link);
                                    });
                                }
                            });
                        });
                    </script>
                </div>

                <div id='star-rating-container' class='star-rating' data-rating='<?php echo $avg_rating; ?>' data-count='<?php echo $review_count; ?>'></div>
            </div>
            <br />
            <hr />
            <br />
            <h2>Plans</h2>
            <br />
            <section class='articles'>
                <?php foreach ($plans as $plan): ?>
                    <article>
                        <div class='article-wrapper'>
                            <figure>
                                <?php
                                $planImages = explode(',', $plan['image_urls']);
                                $firstPlanImage = trim($planImages[0]);
                                ?>
                                <img src='<?php echo $firstPlanImage; ?>' alt='plan_image' class='plan_image' />
                            </figure>
                            <div class='article-body'>
                                <h2><?php echo $plan['plan_name']; ?></h2>
                                <p><?php echo $plan['description']; ?></p>
                                <p>Price: RM<?php echo $plan['price']; ?></p>

                                <!-- Check if the user is logged in -->
                                <?php if (isset($_SESSION['username'])): ?>
                                    <a href='../order_delivery/orders.php?plan_id=<?php echo $plan['id']; ?>' class='read-more'>
                                        Read more <span class='sr-only'>about <?php echo $plan['plan_name']; ?></span>
                                    <?php else: ?>
                                        <a href='../login_management/login.php' class='read-more'>
                                            Log in to order &nbsp;&nbsp;<span class='sr-only'>about <?php echo $plan['plan_name']; ?></span>
                                        <?php endif; ?>
                                        <svg xmlns='http://www.w3.org/2000/svg' class='icon' viewBox='0 0 20 20' fill='currentColor'>
                                            <path fill-rule='evenodd' d='M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z' clip-rule='evenodd' />
                                        </svg>
                                        </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <p>Restaurant not found.</p>
        <?php endif; ?>
    </div>
    <?php include '../partials/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starRatingContainer = document.getElementById('star-rating-container');
            const rating = parseFloat(starRatingContainer.getAttribute('data-rating'));
            const reviewCount = starRatingContainer.getAttribute('data-count');

            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '&#9733;';
                } else if (i - rating < 1) {
                    stars += '&#9733;';
                } else {
                    stars += '&#9734;';
                }
            }

            const ratingDisplay = `<span class='rating-score'>${rating}</span>/5 <span class='rating-count'>(${reviewCount}+)</span>`;

            starRatingContainer.innerHTML = `<span class="star">&#9733;</span> ${ratingDisplay}`;
        });
    </script>
</body>

</html>