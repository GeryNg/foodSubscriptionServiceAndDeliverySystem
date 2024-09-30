<?php
require('../vendor2/fpdf.php');
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_id = $_SESSION['seller_id'] ?? '';

if (empty($seller_id)) {
    echo "Error: You do not have access to this resource.";
    exit;
}

try {
    // Fetch transaction details for the seller
    $sql = "SELECT amount, status, transactionType, datetime FROM transaction WHERE seller_id = :seller_id ORDER BY datetime DESC";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_STR);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$transactions) {
        echo "No transactions available.";
        exit;
    }

    // Initialize FPDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set Title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'Transaction Statement', 0, 1, 'C');

    // Seller Info
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, 'Seller ID: ' . $seller_id, 0, 1, 'C');
    
    // Column headers with numbering column
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 10, '#', 1);          // Numbering column
    $pdf->Cell(40, 10, 'Date', 1);
    $pdf->Cell(40, 10, 'Amount', 1);
    $pdf->Cell(50, 10, 'Type', 1);       // Increase size for Type to 50 for better display
    $pdf->Cell(40, 10, 'Status', 1);
    $pdf->Ln();

    // Transaction data with row numbering
    $pdf->SetFont('Arial', '', 12);
    $rowNumber = 1; // Start numbering from 1
    foreach ($transactions as $transaction) {
        $pdf->Cell(10, 10, $rowNumber, 1);  // Display row number
        $pdf->Cell(40, 10, date("Y-m-d", strtotime($transaction['datetime'])), 1);
        $pdf->Cell(40, 10, 'RM ' . number_format($transaction['amount'], 2), 1);
        $pdf->Cell(50, 10, $transaction['transactionType'], 1);
        $pdf->Cell(40, 10, $transaction['status'], 1);
        $pdf->Ln();
        $rowNumber++;  // Increment row number
    }

    // Output the PDF
    $pdf->Output('I', 'Transaction_Statement.pdf');

} catch (PDOException $e) {
    echo "Error generating statement: " . $e->getMessage();
}
?>
