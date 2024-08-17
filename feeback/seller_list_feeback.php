<?php
$page_title = "Feedback List";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_access = $_SESSION['access'];

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}
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

        .container1 .product-details {
            position: relative;
            text-align: left;
            overflow: hidden;
            padding: 30px;
            height: 100%;
            float: left;
            width: 60%;
        }

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

        .container1 .product-image {
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
            transition: all 0.3s ease-out;
            display: inline-block;
            position: relative;
            overflow: hidden;
            height: 100%;
            float: left;
            width: 22%;
            display: inline-block;
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
        <div class="container1">
            <div class="product-details">
                <h1>CHRISTMAS TREE</h1>
                <span class="hint-star star">
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                </span>
                <p class="information">" Let's spread the joy , here is Christmas , the most awaited day of the year.Christmas Tree is what one need the most. Here is the correct tree which will enhance your Christmas.</p>
                <div class="control">
                    <p><strong>Order ID: </strong> 003 </p>
                    <p><strong>Customer ID: </strong> 002 </p>
                    <p><strong>Date: </strong> 08/04/2024 </p>
                </div>
            </div>
            <div class="product-image">
                <img src="https://images.unsplash.com/photo-1606830733744-0ad778449672?ixid=MXwxMjA3fDB8MHxzZWFyY2h8Mzl8fGNocmlzdG1hcyUyMHRyZWV8ZW58MHx8MHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
            </div>
        </div>
        <div class="container2">
            <div class="product-image">
                <img src="https://images.unsplash.com/photo-1606830733744-0ad778449672?ixid=MXwxMjA3fDB8MHxzZWFyY2h8Mzl8fGNocmlzdG1hcyUyMHRyZWV8ZW58MHx8MHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
            </div>
            <div class="product-details">
                <h1>CHRISTMAS TREE</h1>
                <span class="hint-star star">
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                </span>
                <p class="information">" Let's spread the joy , here is Christmas , the most awaited day of the year.Christmas Tree is what one need the most. Here is the correct tree which will enhance your Christmas.</p>
                <div class="control">
                    <p><strong>Order ID: </strong> 003 </p>
                    <p><strong>Customer ID: </strong> 002 </p>
                    <p><strong>Date: </strong> 08/04/2024 </p>
                </div>
            </div>
        </div>
</body>

</html>