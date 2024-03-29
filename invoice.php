<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('db_connection.php'); // Include your database connection file here
require('fpdf.php'); // Include the FPDF library

$userLoggedIn = isset($_SESSION['username']);
$username = $userLoggedIn ? htmlspecialchars($_SESSION['username']) : '';

// Check if the PDF file exists and serve it for download
if (isset($_GET['download'])) {
    $pdf_file = 'invoice.pdf';

    if (file_exists($pdf_file)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="invoice.pdf"');
        readfile($pdf_file);
        exit;
    }
}

// Check if the invoice data is available in the session
if (isset($_SESSION['invoice_data'])) {
    $invoice_data = $_SESSION['invoice_data'];

    // Extract data from the session
    $vehicle = $invoice_data['vehicle'];
    $pickup_date = $invoice_data['pickup_date'];
    $dropoff_date = $invoice_data['dropoff_date'];
    $total_price = $invoice_data['total_price'];

    // Create a PDF object
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial', '', 12);

    // Output invoice data in the PDF
    $pdf->Cell(40, 10, 'Vehicle', 1);
    $pdf->Cell(60, 10, $vehicle, 1);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Pick-up Date', 1);
    $pdf->Cell(60, 10, $pickup_date, 1);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Drop-off Date', 1);
    $pdf->Cell(60, 10, $dropoff_date, 1);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Total Price', 1);
    $pdf->Cell(60, 10, '$' . $total_price, 1);
    $pdf->Ln();

    // Save the PDF to a file
    $pdf->Output('invoice.pdf', 'F');
} else {
    // Invoice data not found in the session, handle this case as needed
    echo "Invoice data not found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Copy the <head> section from index.php here -->
    <title>Invoice</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/aos.css">
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Copy the <header> section from index.php here -->
    <div class="site-wrap" id="home-section">

      <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
          <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
          </div>
        </div>
        <div class="site-mobile-menu-body"></div>
      </div>



      <header class="site-navbar site-navbar-target" role="banner">

        <div class="container">
          <div class="row align-items-center position-relative">

            <div class="col-3">
              <div class="site-logo">
                <a href="index.php"><strong>ZappRental</strong></a>
              </div>
            </div>

            <div class="col-9  text-right">
              
              <span class="d-inline-block d-lg-none"><a href="#" class=" site-menu-toggle js-menu-toggle py-5 "><span class="icon-menu h3 text-black"></span></a></span>

              <nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
                <ul class="site-menu main-menu js-clone-nav ml-auto">
                  <li class="active"><a href="index.php" class="nav-link">Home</a></li>
                  <li><a href="listing.php" class="nav-link">Listing</a></li>
                  <!-- <li><a href="testimonials.html" class="nav-link">Testimonials</a></li>
                  <li><a href="blog.html" class="nav-link">Blog</a></li> -->
                  <li><a href="about.php" class="nav-link">About</a></li>
                  <li><a href="contact.php" class="nav-link">Contact</a></li>
                  <li>
                  <?php if ($userLoggedIn): ?>
                      <a href="account.php" class="nav-link"><?php echo $username; ?><i class="fa fa-angle-down"></i></a><small><a href="logout.php" class="nav-link">Log Out</a></small>
                  <?php else: ?>
                      <a href="signup.php" class="nav-link">Sign Up/Login</a>
                  <?php endif; ?>
                  </li>
                </ul>
              </nav>
            </div>

            
          </div>
        </div>

      </header>

    <div class="container margintop">
        <h1>Invoice Details</h1>
        <p><strong>Vehicle:</strong> <?php echo $vehicle; ?></p>
        <p><strong>Pick-up Date:</strong> <?php echo $pickup_date; ?></p>
        <p><strong>Drop-off Date:</strong> <?php echo $dropoff_date; ?></p>
        <p><strong>Total Price:</strong> $<?php echo $total_price; ?></p>

        <!-- Download PDF Button -->
        <a href="invoice.php?download=1" class="btn btn-primary">Download PDF</a>
    </div>

    <!-- Copy the <footer> section from index.php here -->
    <footer class="site-footer">
        <div class="container">
          <div class="row mt-5 text-center">
            <div class="col-md-12">
              <div class="border-top pt-5">
                <p>
              Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Gunjan, Dhairya & Aditya
              </p>
              </div>
            </div>

          </div>
        </div>
      </footer>

    </div>
    <!-- Copy the script tags from index.php here -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/main.js"></script>   
</body>
</html>
