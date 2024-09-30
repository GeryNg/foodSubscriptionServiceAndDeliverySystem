<?php
$page_title = "List Plan";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'];
$seller_access = $_SESSION['access'];

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

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
    <style>
        .container-fluid {
            margin-bottom: 5%;
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            margin: 3rem 0 0.5rem 0;
            font-weight: 800;
            line-height: 1.2;
        }

        .breadcrumb {
            background-color: transparent;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1>Plan List</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../partials/seller_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Plan List</li>
        </ol>
        <hr />
        <div class="main">
        <ul class="cards">
    <?php foreach ($plans as $plan): $image_urls = explode(',', $plan['image_urls']); ?>
        <li class="cards_item">
            <!-- Conditionally apply the class based on plan status -->
            <div class="card <?php echo ($plan['status'] === 'active') ? 'active-plan' : 'gray-effect'; ?>">
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
                            <p hidden><strong>Plan id: </strong> <?php echo htmlspecialchars($plan['id']); ?></p>
                            <p><strong>Date From:</strong> <?php echo htmlspecialchars($plan['date_from']); ?></p>
                            <p><strong>Date To:</strong> <?php echo htmlspecialchars($plan['date_to']); ?></p>
                            <p><strong>Price:</strong> RM <?php echo htmlspecialchars($plan['price']); ?></p>
                            <p><strong>Sections:</strong> <?php echo htmlspecialchars($plan['section']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($plan['status']); ?></p>
                            <span class="card_price2">
                                <a href="javascript:void(0);" class="delete-plan" data-id="<?php echo $plan['id']; ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>



        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-plan').forEach(function(deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    const planId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete the plan with ID: ${planId}`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `delete_plan.php?id=${planId}`;
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>