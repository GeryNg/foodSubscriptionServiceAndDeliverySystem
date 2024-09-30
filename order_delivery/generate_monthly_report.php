<?php
include_once '../resource/Database.php';
include_once '../resource/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $seller_id = $_POST['seller_id'];

        $query = "
            SELECT 
                o.Order_ID,
                o.OrderDate,
                o.Quantity,
                o.GrandTotal,
                o.Status,
                o.Meal,
                p.plan_name,
                s.name AS seller_name,
                SUM(o.GrandTotal) AS total_amount
            FROM 
                order_cust o
            JOIN 
                plan p ON o.Plan_ID = p.id
            JOIN 
                seller s ON p.seller_id = s.id
            WHERE 
                p.seller_id = :seller_id
            AND 
                MONTH(o.OrderDate) = MONTH(CURRENT_DATE())
            AND 
                YEAR(o.OrderDate) = YEAR(CURRENT_DATE())
            GROUP BY 
                o.Order_ID;
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':seller_id', $seller_id);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$orders) {
            throw new Exception("No orders found for this month.");
        }

        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Monthly Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { text-align: center; font-size: 24px; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                table, th, td { border: 1px solid black; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .total { text-align: right; font-weight: bold; }
                .print-btn { margin: 20px 0; text-align: center; }
            </style>
        </head>
        <body>
            <h1>Monthly Orders Report</h1>
            <p><strong>Seller:</strong> " . htmlspecialchars($orders[0]['seller_name']) . "</p>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Meal</th>
                        <th>Quantity</th>
                        <th>Grand Total (RM)</th>
                    </tr>
                </thead>
                <tbody>";

        $totalAmount = 0;
        foreach ($orders as $order) {
            echo "
            <tr>
                <td>" . htmlspecialchars($order['Order_ID']) . "</td>
                <td>" . htmlspecialchars($order['OrderDate']) . "</td>
                <td>" . htmlspecialchars($order['Meal']) . "</td>
                <td>" . htmlspecialchars($order['Quantity']) . "</td>
                <td>" . number_format($order['GrandTotal'], 2) . "</td>
            </tr>";
            $totalAmount += $order['GrandTotal'];
        }

        echo "
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan='4' class='total'>Total Amount (RM):</td>
                        <td>" . number_format($totalAmount, 2) . "</td>
                    </tr>
                </tfoot>
            </table>

            <div class='print-btn'>
                <button onclick='window.print()'>Print or Save as PDF</button>
            </div>
        </body>
        </html>
        ";

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
