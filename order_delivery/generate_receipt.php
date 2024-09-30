<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $plan_id = $_POST['plan_id'];
        $meal = $_POST['meal'];

        // Fetch Seller Details
        $seller_id = $_SESSION['seller_id'];
        $seller_query = $db->prepare("SELECT * FROM seller WHERE id = :seller_id");
        $seller_query->bindParam(':seller_id', $seller_id);
        $seller_query->execute();
        $seller = $seller_query->fetch(PDO::FETCH_ASSOC);

        if (!$seller) {
            throw new Exception("Seller not found.");
        }

        // Fetch Plan Details
        $plan_query = $db->prepare("SELECT * FROM plan WHERE id = :plan_id");
        $plan_query->bindParam(':plan_id', $plan_id);
        $plan_query->execute();
        $plan = $plan_query->fetch(PDO::FETCH_ASSOC);

        if (!$plan) {
            throw new Exception("Plan not found.");
        }

        // Fetch Orders for the plan
        $order_query = $db->prepare("SELECT * FROM order_cust WHERE plan_id = :plan_id AND meal = :meal");
        $order_query->bindParam(':plan_id', $plan_id);
        $order_query->bindParam(':meal', $meal);
        $order_query->execute();
        $orders = $order_query->fetchAll(PDO::FETCH_ASSOC);

        if (!$orders) {
            throw new Exception("No orders found.");
        }

        foreach ($orders as $order) {
            // Fetch Customer Details
            $customer_query = $db->prepare("SELECT * FROM customer WHERE Cust_ID = :cust_id");
            $customer_query->bindParam(':cust_id', $order['Cust_ID']);
            $customer_query->execute();
            $customer = $customer_query->fetch(PDO::FETCH_ASSOC);

            if (!$customer) {
                throw new Exception("Customer not found for Order ID " . $order['Order_ID']);
            }

            // Fetch Address Details
            $address_query = $db->prepare("SELECT * FROM address WHERE address_id = :address_id");
            $address_query->bindParam(':address_id', $order['delivery_address_id']);
            $address_query->execute();
            $address = $address_query->fetch(PDO::FETCH_ASSOC);

            if (!$address) {
                throw new Exception("Address not found for Order ID " . $order['Order_ID']);
            }

            // Fetch Add-ons for the order
            $addon_query = $db->prepare("
                SELECT addons.addon_name 
                FROM order_addon 
                INNER JOIN addons ON order_addon.addon_id = addons.id 
                WHERE order_addon.order_id = :order_id
            ");
            $addon_query->bindParam(':order_id', $order['Order_ID']);
            $addon_query->execute();
            $addons = $addon_query->fetchAll(PDO::FETCH_ASSOC);

            echo '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <title>POS Receipt</title>
                        <style>
                        #invoice-POS {
                            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
                            padding: 2mm;
                            margin: 0 auto;
                            width: 44mm;
                            background: #FFF;
                        }
                        #invoice-POS h1 {
                            font-size: 1.5em;
                            color: #222;
                        }
                        #invoice-POS h2 {
                            font-size: .9em;
                        }
                        #invoice-POS h3 {
                            font-size: 1.2em;
                            font-weight: 300;
                            line-height: 2em;
                        }
                        #invoice-POS p {
                            font-size: .7em;
                            color: #666;
                            line-height: 1.2em;
                        }
                        #invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
                            border-bottom: 1px solid #EEE;
                        }
                        #invoice-POS #top {
                            min-height: 100px;
                        }
                        #invoice-POS #mid {
                            min-height: 80px;
                        }
                        #invoice-POS #bot {
                            min-height: 50px;
                        }
                        #invoice-POS #top .logo img {
                            height: 60px;
                            width: 60px;
                            display: block;
                        }
                        #invoice-POS .info {
                            display: block;
                            margin-left: 0;
                        }
                        #invoice-POS .title {
                            float: right;
                        }
                        #invoice-POS .title p {
                            text-align: right;
                        }
                        #invoice-POS table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        #invoice-POS .tabletitle {
                            font-size: .5em;
                            background: #EEE;
                        }
                        #invoice-POS .service {
                            border-bottom: 1px solid #EEE;
                        }
                        #invoice-POS .item {
                            width: 24mm;
                        }
                        #invoice-POS .itemtext {
                            font-size: .5em;
                        }
                        #invoice-POS #legalcopy {
                            margin-top: 5mm;
                        }
                        </style>
                    </head>
                    <body translate="no">
                        <div id="invoice-POS">
                            <center id="top">
                                <div class="logo">
                                    <img src="' . htmlspecialchars($seller['profile_pic']) . '" alt="Logo">
                                </div>
                                <div class="info">
                                    <h2>' . htmlspecialchars($seller['name']) . '</h2>
                                </div>
                            </center>

                            <div id="mid">
                                <div class="info">
                                    <h2>Contact Info</h2>
                                    <p>
                                        Address: ' . htmlspecialchars($seller['address']) . '<br>
                                        Phone: ' . htmlspecialchars($seller['contact_number']) . '<br>
                                    </p>
                                </div>
                            </div>

                            <div id="bot">
                                <h2>Section: ' . htmlspecialchars($meal) . '</h2>
                                <div id="table">
                                    <table>
                                        <tr class="tabletitle">
                                            <td class="item"><h2>Plan Name</h2></td>
                                            <td class="Hours"><h2>Quantity</h2></td>
                                            <td class="Rate"><h2>Instructions</h2></td>
                                        </tr>
                                        <tr class="service">
                                            <td class="tableitem"><p class="itemtext">' . htmlspecialchars($plan['plan_name']) . '</p></td>
                                            <td class="tableitem"><p class="itemtext">' . htmlspecialchars($order['Quantity']) . '</p></td>
                                            <td class="tableitem"><p class="itemtext">' . htmlspecialchars($order['instructions']) . '</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <p><strong>Add-ons:</strong></p>';
                                                if (!empty($addons)) {
                                                    foreach ($addons as $addon) {
                                                        echo '<p>' . htmlspecialchars($addon['addon_name']) . '</p>';
                                                    }
                                                } else {
                                                    echo '<p>No Add-ons</p>';
                                                }
                                                echo '
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div id="legalcopy">
                                                    <p>Order ID: ' . htmlspecialchars($order['Order_ID']) . '</p>
                                                    <p>Customer Name: ' . htmlspecialchars($customer['Name']) . '</p>
                                                    <p>Phone: ' . htmlspecialchars($customer['Phone_num']) . '</p>
                                                    <p>Address: ' . htmlspecialchars($address['line1']) . ', ' . htmlspecialchars($address['line2']) . ', ' . htmlspecialchars($address['city']) . ', ' . htmlspecialchars($address['state']) . ', ' . htmlspecialchars($address['postal_code']) . ', ' . htmlspecialchars($address['country']) . '</p>
                                                    <p class="legal"><strong>Thank you for ordering from us!</strong> We hope you enjoy your meal.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </body>
                    <div class="page-break"></div>
                    </html>';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
