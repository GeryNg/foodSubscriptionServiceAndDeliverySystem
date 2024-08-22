<?php
$page_title = "User Authentication - Homepage";
include_once 'resource/session.php';
include_once 'partials/headers.php';
?>
<link rel="stylesheet" href="../css/index.css">

<section class="hero-section">
    <div class="hero-container">
        <div class="hero-text">
            <h1>Spend Quality Time with your Family</h1>
            <?php if (!isset($_SESSION['username'])): ?>
                <div class="buttons">
                    <a href="/login_management/login.php" class="btn btn-signin">Sign In</a>
                    <a href="/login_management/signup.php" class="btn btn-signup">Sign Up</a>
                </div>
            <?php else: ?>
                <a href="../Restaurant/restaurants.php" class="btn btn-order">Order Now</a>
            <?php endif; ?>
        </div>
        
        <div class="hero-image">
            <img src="/uploads/Chinese Dinner.png" alt="Hero Image">
        </div>
    </div>
</section>

<section class="secondary-section">
        <div class="secondary-container">
            <div class="secondary-text">
                <h1>Don't have time to cook?</h1>
                <p>We provide Food to your doorstep for as long as you like</p>
                <a href="../Restaurant/restaurants.php" class="btn btn-modern">Browse</a>
            </div>
            <div class="secondary-image">
                <img src="seller_profile_pic\66ae66152d3a62.91511676.png" alt="Secondary Image">
            </div>
        </div>
</section>

<section class="hero-section">
        <div class="hero-container">
            <div class="secondary-image">
                <img src="uploads\66ae690e789a86.59485598.jpeg" alt="Secondary Image">
            </div>
            <div class="hero-text">
                <h2>Food need not be fussy</h2>
                <p>Homecook soul food that will remind you of your mother's cooking</p>               
            </div>
        </div>
</section>
<?php include_once 'partials/footer.php';?>  
