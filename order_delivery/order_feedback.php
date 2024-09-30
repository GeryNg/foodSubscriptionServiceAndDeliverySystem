<?php include '../resource/Database.php'; ?>
<?php include '../resource/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Your Experience</title>
    <link rel="stylesheet" href="../css/feedback.css">
</head>
<body>
    <?php include '../partials/headers.php'; ?>

    <div class="feedback-container">
        <h1>Rate your experience</h1>

        <?php
        if (isset($_GET['order_id'])) {
            $order_id = intval($_GET['order_id']);

            // Fetch the seller's name and the order's start date
            $sql = "SELECT s.name, oc.StartDate FROM order_cust oc 
                    JOIN plan p ON oc.Plan_ID = p.id
                    JOIN seller s ON p.seller_id = s.id
                    WHERE oc.Order_ID = :order_id";
            $statement = $db->prepare($sql);
            $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch();
            $seller_name = htmlspecialchars($result['name'], ENT_QUOTES, 'UTF-8');
            $start_date = $result['StartDate'];
            $current_date = date('Y-m-d');

            // Check if this order has already been rated
            $sql = "SELECT Rating, Comment, FeedbackDate FROM feedback WHERE Order_ID = :order_id AND Cust_ID = :cust_id";
            $statement = $db->prepare($sql);
            $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $statement->bindParam(':cust_id', $_SESSION['Cust_ID'], PDO::PARAM_STR_CHAR);
            $statement->execute();
            $feedback = $statement->fetch();

            if ($feedback) {
                // If already rated, show the rating and comment
                $rating = intval($feedback['Rating']);
                echo "<p>Rated on " . htmlspecialchars($feedback['FeedbackDate'], ENT_QUOTES, 'UTF-8') . ": ";
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        echo "<span class='icon' style='color:#f39c12;'>★</span>";
                    } else {
                        echo "<span class='icon' style='color:#ddd;'>★</span>";
                    }
                }
                echo "</p>";
                echo "<p>Comment: " . htmlspecialchars($feedback['Comment'], ENT_QUOTES, 'UTF-8') . "</p>";
            } else {
                // If not rated, show the rating form
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $rating = $_POST['rating'];
                    $comment = $_POST['comment'];
                    $feedback_date = $_POST['feedback_date'];

                    $sql = "INSERT INTO feedback (Cust_ID, Order_ID, Comment, Rating, FeedbackDate) 
                            VALUES (:cust_id, :order_id, :comment, :rating, :feedback_date)";
                    $statement = $db->prepare($sql);
                    $statement->bindParam(':cust_id', $_SESSION['Cust_ID'], PDO::PARAM_STR_CHAR);
                    $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                    $statement->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $statement->bindParam(':rating', $rating, PDO::PARAM_STR);
                    $statement->bindParam(':feedback_date', $feedback_date, PDO::PARAM_STR);

                    if ($statement->execute()) {
                        echo "<script>
                                alert('Feedback submitted');
                                window.location.href = 'order_history.php';
                              </script>";
                    } else {
                        echo "<p>Failed to submit feedback. Please try again.</p>";
                    }
                } else {
                    ?>

                    <form action="" method="POST">
                        <div class="rating">
                            <label>
                                <input type="radio" name="rating" value="1" required>
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="rating" value="2">
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="rating" value="3">
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="rating" value="4">
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="rating" value="5">
                                <span class="icon">★</span>
                            </label>
                        </div>

                        <p>How did you like your food prepared by <strong><?php echo $seller_name; ?></strong>?</p>

                        <!-- Date input field -->
                        <label for="feedback_date">Feedback Date:</label>
                        <input type="date" id="feedback_date" name="feedback_date" min="<?php echo $start_date; ?>" max="<?php echo $current_date; ?>" required>

                        <textarea name="comment" placeholder="Tell us about your experience!" required></textarea>

                        <button type="submit" class="submit-feedback">Send</button>
                    </form>

                    <?php
                }
            }
        } else {
            echo "<p>Invalid order ID.</p>";
        }
        ?>

        <!-- Displaying all feedbacks related to this order -->
        <h2>All Feedbacks</h2>
        <?php
        $sql = "SELECT * FROM feedback WHERE Order_ID = :order_id ORDER BY FeedbackDate DESC";
        $statement = $db->prepare($sql);
        $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $statement->execute();
        $feedbacks = $statement->fetchAll();

        if (count($feedbacks) > 0) {
            foreach ($feedbacks as $fb) {
                $rating = intval($fb['Rating']);
                $comment = htmlspecialchars($fb['Comment'], ENT_QUOTES, 'UTF-8');
                $feedback_date = htmlspecialchars($fb['FeedbackDate'], ENT_QUOTES, 'UTF-8');

                echo "<div class='feedback-entry'>";
                echo "<p>Date: $feedback_date</p>";
                echo "<p>Rating: ";
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        echo "<span class='icon' style='color:#f39c12;'>★</span>";
                    } else {
                        echo "<span class='icon' style='color:#ddd;'>★</span>";
                    }
                }
                echo "</p>";
                echo "<p>Comment: $comment</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No feedbacks yet for this order.</p>";
        }
        ?>
    </div>

    <?php include '../partials/footer.php'; ?>

    <script>
        // JavaScript for star rating hover and click functionality
        const stars = document.querySelectorAll('.rating .icon');
        let selectedRating = 0;

        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                resetStars();
                highlightStars(index);
            });

            star.addEventListener('click', () => {
                selectedRating = index + 1;
                document.querySelectorAll('.rating input')[index].checked = true;
            });

            star.addEventListener('mouseleave', () => {
                resetStars();
                highlightStars(selectedRating - 1);
            });
        });

        function resetStars() {
            stars.forEach(star => {
                star.style.color = '#ddd';
            });
        }

        function highlightStars(index) {
            for (let i = 0; i <= index; i++) { 
                stars[i].style.color = '#f39c12';
            }
        }
    </script>
</body>
</html>
