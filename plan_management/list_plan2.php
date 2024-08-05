<?php
$page_title = "List Plan2";
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'];

$query = "SELECT * FROM plan WHERE seller_id = :seller_id ORDER BY status ASC";
$stmt = $db->prepare($query);
$stmt->execute([':seller_id' => $seller_id]);
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../css/list_plan.css" rel="stylesheet">
</head>

<body>
    <h1 style="margin:20px;">Plan List</h1>
    <br/>
    <hr/>
    <br/>
    <div class="main">
        <ul class="cards">
            <?php foreach ($plans as $plan): $image_urls = explode(',', $plan['image_urls']);?>
            <li class="cards_item">
                <div class="card">
                    <div class="card_image">
                        <div class="slideshow">
                        <?php foreach ($image_urls as $index => $url): ?>
                            <div id="slide-<?php echo $index+1; ?>" class="slide">
                                <a href="#slide-<?php echo ($index == 0) ? count($image_urls) : $index; ?>"></a>
                                <a href="#slide-<?php echo ($index+2) > count($image_urls) ? 1 : ($index+2); ?>"></a>
                                <img src="<?php echo htmlspecialchars($url); ?>" />
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <span class="card_price"><a href="edit_plan.php?id=<?php echo $plan['id']; ?>"><i class="bi bi-pencil-square"></i></a></span>
                    </div>
                    <div class="card_content">
                        <h2 class="card_title"><?php echo htmlspecialchars($plan['plan_name']); ?></h2>
                        <div class="card_text">
                            <p><?php echo htmlspecialchars($plan['description']); ?></p>
                            <hr />
                            <div class="card_text2">
                                <p><strong>Date From:</strong> <?php echo htmlspecialchars($plan['date_from']); ?></p>
                                <p><strong>Date To:</strong> <?php echo htmlspecialchars($plan['date_to']); ?></p>
                                <p><strong>Price:</strong> RM <?php echo htmlspecialchars($plan['price']); ?></p>
                                <p><strong>Sections:</strong> <?php echo htmlspecialchars($plan['section']); ?></p>
                                <p><strong>Status:</strong> <?php echo htmlspecialchars($plan['status']); ?></p>
                                <span class="card_price2"><a href="delete_plan.php?id=<?php echo $plan['id']; ?>" onclick="return confirm('Are you sure you want to delete this plan?');"><i class="bi bi-trash"></i></a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let slides = document.querySelectorAll('.slide');
            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.remove('active');
                    if (i === index) {
                        slide.classList.add('active');
                    }
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            showSlide(currentSlide);
            setInterval(nextSlide, 8000);
        });
    </script>
</body>

</html>