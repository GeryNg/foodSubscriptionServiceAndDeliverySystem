<?php
$page_title = "Feedback List";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_access = $_SESSION['access'];
$seller_id = $_SESSION['seller_id'];

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}

$query1 = "SELECT id AS plan_id, plan_name, image_urls FROM plan WHERE seller_id = :seller_id LIMIT 1";
$stmt1 = $db->prepare($query1);
$stmt1->bindParam(':seller_id', $seller_id);
$stmt1->execute();
$planDetails = $stmt1->fetch(PDO::FETCH_ASSOC);

$sellerPlan = $planDetails['plan_id'];
$planName = $planDetails['plan_name'];

$imageUrls = explode(',', $planDetails['image_urls']);
$planImage = $imageUrls[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Bree+Serif&family=EB+Garamond:ital,wght@0,500;1,800&display=swap');

        .container1,
        .container2 {
            box-shadow: 0 15px 30px 1px grey;
            background: rgba(255, 255, 255, 0.90);
            text-align: center;
            border-radius: 10px;
            overflow: hidden;
            margin: 2em auto;
            height: 300px;
            width: 80%;
        }

        .container1 .product-details,
        .container2 .product-details {
            position: relative;
            text-align: left;
            overflow: hidden;
            padding: 30px;
            height: 100%;
            float: left;
            width: 60%;
        }

        .container1 .product-details h1,
        .container2 .product-details h1 {
            font-family: 'Bebas Neue', cursive;
            display: inline-block;
            position: relative;
            font-size: 30px;
            color: #344055;
            margin: 0;
        }

        .hint-star {
            display: inline-block;
            margin-left: 0.5em;
            color: gold;
            width: 50%;
        }

        .container1 .product-details>p,
        .container2 .product-details>p {
            font-family: 'EB Garamond', serif;
            text-align: center;
            font-size: 20px;
            color: #7d7d7d;
        }

        .control {
            position: absolute;
            bottom: 5%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 100%;
        }

        .container1 .product-image,
        .container2 .product-image {
            transition: all 0.3s ease-out;
            display: inline-block;
            position: relative;
            overflow: hidden;
            height: 100%;
            float: right;
            width: 22%;
            display: inline-block;
        }

        .container2 .product-image {
            float: left;
        }

        .container1 img,
        .container2 img {
            width: 100%;
            height: 100%;
        }

        .product-image:hover img {
            transition: all 0.3s ease-out;
        }

        .product-image:hover img {
            transform: scale(1.2, 1.2);
        }

        @media (max-width: 768px) {
            .container1,
            .container2 {
                height: 170px;
                margin: 1em auto;
                width: 95%;
            }

            .container1 .product-details,
            .container2 .product-details {
                width: 70%;
                padding: 10px;
            }

            .container1 .product-image,
            .container2 .product-image {
                width: 30%;
            }

            .container1 .product-details h1,
            .container2 .product-details h1 {
                font-size: 15px;
            }

            .hint-star {
                margin-left: 0.10em;
                width: 90%;
                font-size: 12px;
            }

            .container1 .product-details>p,
            .container2 .product-details>p {
                font-size: 9px;
            }

            .control {
                position: absolute;
                bottom: 0;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 5px;
                width: 100%;
                margin-bottom: 0 !important;
            }

            .control p {
                font-size: 7px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container-fluid" style="margin-top: 20px;">
        <h1 class="h1 mb-2 text-gray-800" style="font-weight: 600;">Feedback</h1>
        <br />
        <div id="feedback-container">
            <?php
            $query2 = "
            SELECT 
                f.Feedback_ID, 
                f.Cust_ID, 
                f.Order_ID, 
                f.Comment, 
                f.Rating, 
                f.FeedbackDate, 
                oc.Plan_ID
            FROM 
                feedback f
            JOIN 
                order_cust oc ON f.Order_ID = oc.Order_ID
            WHERE 
                oc.Plan_ID = :plan_id
            ORDER BY 
                f.FeedbackDate DESC
            LIMIT 5";
            
            $stmt2 = $db->prepare($query2);
            $stmt2->bindParam(':plan_id', $sellerPlan);
            $stmt2->execute();
            $feedbackList = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $counter = 0;

            foreach ($feedbackList as $feedback):
                $containerClass = ($counter % 2 == 0) ? 'container1' : 'container2';
                $imageFloatClass = ($counter % 2 == 0) ? 'float-right' : 'float-left';
            ?>
                <div class="<?php echo $containerClass; ?>">
                    <div class="product-image <?php echo $imageFloatClass; ?>">
                        <img src="<?php echo htmlspecialchars($planImage); ?>" alt="">
                    </div>
                    <div class="product-details">
                        <h1><?php echo htmlspecialchars($planName); ?></h1>
                        <span class="hint-star star">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <i class="fa fa-star<?php echo ($i < $feedback['Rating']) ? '' : '-o'; ?>" aria-hidden="true"></i>
                            <?php endfor; ?>
                        </span>
                        <p class="information">"<?php echo htmlspecialchars($feedback['Comment']); ?>"</p>
                        <div class="control">
                            <p><strong>Order ID: </strong> <?php echo htmlspecialchars($feedback['Order_ID']); ?></p>
                            <p><strong>Customer ID: </strong> <?php echo htmlspecialchars($feedback['Cust_ID']); ?></p>
                            <p><strong>Date: </strong> <?php echo htmlspecialchars($feedback['FeedbackDate']); ?></p>
                        </div>
                    </div>
                </div>
            <?php
                $counter++;
            endforeach;
            ?>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <button id="loadMore" class="btn btn-primary">Load More</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var limit = 5;
        var offset = 5;

        $(document).ready(function () {
            $('#loadMore').click(function () {
                loadFeedback();
            });

            function loadFeedback() {
                $.ajax({
                    url: 'load_feedback.php',
                    method: 'POST',
                    data: {
                        limit: limit,
                        offset: offset
                    },
                    success: function (data) {
                        $('#feedback-container').append(data);
                        offset += limit; 
                    }
                });
            }
        });
    </script>
</body>

</html>
