<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'];

if(isset($_POST['limit'], $_POST['offset'])) {
    $limit = (int) $_POST['limit'];
    $offset = (int) $_POST['offset'];

    $query = "
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
    LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':plan_id', $_SESSION['seller_id'], PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $feedbackList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $counter = $offset;

    foreach ($feedbackList as $feedback) {
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
    }
}
?>
