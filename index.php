<?php
$page_title = "User Authentication - Homepage";
include_once 'resource/session.php';
include_once 'partials/headers.php';
?>
<link rel="stylesheet" href="../css/index.css">
<div class="container">
    <div class="flag">
        <h1>User Authentication System</h1>
        <p class="lead">Learn to Code A login and registration System with PHP.<br />
            Enhance your PHP skills and make more cash.</p>
        <?php if (!isset($_SESSION['username'])): ?>
        <p>You are currently not signed in. <a href="/login_management/login.php">Login</a> Not yet a member? <a href="/login_management/singup.php">Signup</a></p>
        <br />
        <p>Want to join us as a seller? <a href="/login_management/seller_singup.php">Join Us</a></p>
        <?php else: ?>
        <p>You are now logged in as <?php echo htmlspecialchars($_SESSION['username']); ?>. <a href="./login_management/logout.php">Logout</a></p>
        <?php endif; ?>
    </div>
</div>

<<<<<<< HEAD
=======
<section class="hero-section">
        <div class="hero-container">
            <div class="hero-text">
                <h1>Don't have time to cook?</h1>
                <p>We provide Food to your doorstep for as long as you like</p>
                <a href="../Restaurant/restaurants.php" class="btn btn-modern">Order Now</a>
            </div>
            <div class="hero-image">
                <img src="seller_profile_pic\66ae66152d3a62.91511676.png" alt="Hero Image">
            </div>
        </div>
</section>

<section class="secondary-section">
        <div class="secondary-container">
            <div class="secondary-image">
                <img src="uploads\66ae690e789a86.59485598.jpeg" alt="Secondary Image">
            </div>
            <div class="secondary-text">
                <h2>Food need not be fussy</h2>
                <p>Homecook soul food that will remind you of your mother's cooking</p>
            </div>
        </div>
    </section>



    <!--add footer for the future-->
>>>>>>> 84149511000bf35883a70367543dfe3b88d01b53
<?php include_once 'partials/footer.php';?>    <!--add footer for the future-->