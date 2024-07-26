<?php
$page_title = "User Authentication - Homepage";
include_once 'resource/session.php';
include_once 'partials/headers.php';#
?>

<div class="container">
    <div class="flag">
        <h1>User Authentication System</h1>
        <p class="lead">Learn to Code A login and registration System with PHP.<br />
            Enhance your PHP skills and make more cash.</p>
            <p>hello world</p>
        <?php if (!isset($_SESSION['username'])): ?>
        <p>You are currently not signed in. <a href="/login_management/login.php">Login</a> Not yet a member? <a href="/login_management/singup.php">Signup</a></p>
        <br />
        <p>Want to join us as a seller? <a href="/login_management/seller_singup.php">Join Us</a></p>
        <?php else: ?>
        <p>You are now logged in as <?php echo htmlspecialchars($_SESSION['username']); ?>. <a href="./login_management/logout.php">Logout</a></p>
        <?php endif; ?>
    </div>
</div>

    <!--add footer for the future-->
