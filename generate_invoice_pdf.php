<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Invoice', 0, 1, 'C');
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Database connection (use your database credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve invoice data from the adg_invoice table
$sql = "SELECT * FROM adg_invoice";
$result = $conn->query($sql);

// Create a PDF object
$pdf = new PDF();
$pdf->AddPage();

// Set font
$pdf->SetFont('Arial', '', 12);

// Output invoice data in the PDF
if ($result->num_rows > 0) {
    $pdf->Cell(40, 10, 'Invoice ID', 1);
    $pdf->Cell(60, 10, 'Invoice Date', 1);
    $pdf->Ln();

    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['invoiceid'], 1);
        $pdf->Cell(60, 10, $row['invoicedate'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(100, 10, 'No invoice data found', 1);
}

// Close the database connection
$conn->close();

// Output the PDF
$pdf->Output();
?>