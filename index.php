<?php
$page_title = "Homepage";
include_once 'resource/session.php';
include_once 'partials/headers.php';
?>
<link rel="stylesheet" href="../css/index.css">

<style>
    .container-fluid {
        background-color: #fff;
        padding: 20px;
        border-radius: 20px;
        max-width: 95%;
        display: flex;
    }
</style>

<section class="hero-section">
    <div class="hero-container">
        <div class="hero-text">
            <h1>Spend Quality Time with your Family</h1>
            <?php if (!isset($_SESSION['username'])): ?>
                <div class="buttons">
                    <a href="/login_management/login.php" class="btn btn-signin">Sign In</a>
                    <a href="/login_management/singup.php" class="btn btn-signup">Sign Up</a>
                </div>
            <?php else: ?>
                <a href="../Restaurant/restaurants.php" class="btn btn-order">Order Now</a>
            <?php endif; ?>
        </div>

        <div class="hero-image">
            <img src="/image/Chinese Dinner.png" alt="Hero Image">
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
            <img src="image\66ae66152d3a62.91511676.png" alt="Secondary Image">
        </div>
    </div>
</section>

<section class="hero-section">
    <div class="hero-container">
        <div class="secondary-image">
            <img src="image\66ae690e789a86.59485598.jpeg" alt="Secondary Image">
        </div>
        <div class="hero-text">
            <h2>Food need not be fussy</h2>
            <p>Homecook soul food that will remind you of your mother's cooking</p>
        </div>
    </div>
</section>

<div class="container-fluid">
    <div class="container">
    <h3>Know more about Makan Apa</h3><br />
    <h6>We share GOOD FOOD and the love from home chefs.</h6><br />
    <p>MakanApa started with one goal in mind — to be the home-cooked food delivery platform in Malaysia with a mission to share home recipes to everyone. Since 2017, we have been actively delivering throughout the whole Klang Valley. Through our website, our customers can order from home chefs in their neighborhood. We will then deliver the hearty meals to your doorsteps.</p><br />
    <p>In order to maintain our food quality, all our food goes through three very strict processes before selling it to our customers, which are validation, standardization and commercialization. The reason behind is that we want our customer to be happy and enjoy our food safely. Our customers enjoy our home-cooked meals because our home chefs prepare the meals from the heart.</p><br />
    <p>In addition, we always ensure our food recipes constantly meet the only requirement — good food. Besides that, we have missions to fulfil to contribute to the society:</p><br />
                <ul>
                    <li>Empowering home entrepreneurs</li>
                    <li>No secret recipes</li>
                    <li>Sharing home-cooked food</li>
                    <li>Creates 100 of profitable food businesses</li>
                </ul>
            </div>
            <div class="container">
                <br>
                <br>
                <br>
                <br>
                <br>
                <img src="https://smallbizclub.com/wp-content/uploads/2021/07/bigstock-Food-Delivery-App-Order-With-P-396058997.jpg" alt="" style="border-radius: 20px;">
            </div>
</div>


<!--add footer for the future-->
<?php include_once 'partials/footer.php'; ?>