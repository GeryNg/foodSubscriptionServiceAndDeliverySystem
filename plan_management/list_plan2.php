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
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <title><?php echo $page_title; ?></title>
</head>

<body>
    <div class="container-fluid" style="margin-top: 20px;">
        <h1 class="h3 mb-2 text-gray-800">Plan List</h1>
        <hr />
        <div class="main">
            <ul class="cards">
                <?php foreach ($plans as $plan): $image_urls = explode(',', $plan['image_urls']); ?>
                    <li class="cards_item">
                        <div class="card">
                            <div class="card_image">
                                <div class="slideshow">
                                    <?php foreach ($image_urls as $index => $url): ?>
                                        <div class="slide">
                                            <img src="<?php echo htmlspecialchars($url); ?>" />
                                        </div>
                                    <?php endforeach; ?>
                                    <button class="arrow arrow-left">&#128896;</button>
                                    <button class="arrow arrow-right">&#128898;</button>
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.slideshow').forEach(function(slideshow) {
                let slides = slideshow.querySelectorAll('.slide');
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

                function prevSlide() {
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                    showSlide(currentSlide);
                }

                showSlide(currentSlide);
                setInterval(nextSlide, 4500);

                slideshow.querySelector('.arrow-left').addEventListener('click', function() {
                    prevSlide();
                });

                slideshow.querySelector('.arrow-right').addEventListener('click', function() {
                    nextSlide();
                });
            });
        });
    </script>
</body>

</html>

