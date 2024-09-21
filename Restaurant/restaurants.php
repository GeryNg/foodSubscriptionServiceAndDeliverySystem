<?php
$page_title = "Restaurants";
include '../resource/Database.php';
include '../partials/headers.php';

// Process the data
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sellers = [];

$sql = "SELECT seller.profile_pic, seller.name, seller.detail, seller.address, seller.id 
        FROM seller 
        WHERE seller.access = 'verify'";
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

    // Fetch average rating for the current restaurant
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

    $sellers[] = [
        'profile_pic' => $profile_pic,
        'name' => $name,
        'detail' => $detail,
        'address' => $address,
        'id' => $id,
        'avg_rating' => $avg_rating,
        'review_count' => $review_count
    ];
}
?>

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
    <div class="container" style="margin-top: 3%;">
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
            <?php foreach ($sellers as $seller): ?>
                <article class='restaurant-card'>
                    <div class='article-wrapper'>
                        <figure>
                            <img src='<?php echo $seller['profile_pic']; ?>' alt='profile_pic' class='profile-pic' />
                        </figure>
                        <div class='article-body'>
                            <div class='restaurant-title'>
                                <h2><?php echo $seller['name']; ?></h2>
                            </div>
                            <div class='rating-display'>
                                <span class='star'>&#9733;</span>
                                <span class='rating-score'><?php echo $seller['avg_rating']; ?></span>
                                <span class='rating-count'>/<?php echo ($seller['review_count'] > 100 ? '100+' : $seller['review_count']); ?></span>
                            </div>
                            <p class='detail'><?php echo $seller['detail']; ?></p>
                            <a href='restaurant_plan.php?id=<?php echo $seller['id']; ?>' class='read-more'>
                                Read more <span class='sr-only'>about <?php echo $seller['name']; ?></span>
                                <svg xmlns='http://www.w3.org/2000/svg' class='icon' viewBox='0 0 20 20' fill='currentColor'>
                                    <path fill-rule='evenodd' d='M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z' clip-rule='evenodd' />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </div>
    <br/>
    <br/>
    <?php include '../partials/footer.php'; ?>
</body>

</html>