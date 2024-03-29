<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

$userLoggedIn = isset($_SESSION['username']);
$username = $userLoggedIn ? htmlspecialchars($_SESSION['username']) : '';

// Check if VIN, pickup_date, and dropoff_date are passed
$vin = isset($_GET['vin']) ? $_GET['vin'] : null;
$pickup_date = isset($_GET['pickup_date']) ? $_GET['pickup_date'] : null;
$dropoff_date = isset($_GET['dropoff_date']) ? $_GET['dropoff_date'] : null;

// Proceed only if all required parameters are provided
if (!$vin || !$pickup_date || !$dropoff_date) {
    echo "Required parameters are missing.";
    exit;
}

// Fetch vehicle details
$sql = "SELECT adg_vehicle.*, adg_vehicleclass.daily_rate, adg_office.* 
        FROM adg_vehicle 
        JOIN adg_vehicleclass ON adg_vehicle.classid = adg_vehicleclass.classid 
        JOIN adg_office ON adg_vehicle.office_id = adg_office.office_id
        WHERE vin = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $vin);
$stmt->execute();
$vehicle = $stmt->get_result()->fetch_assoc();
$start_odometer = 0;
$end_odometer = rand(100, 500); 
$daily_odometer_limit = 100;
$pickup_location = $vehicle['officecity']; 
$dropoff_location = $vehicle['officecity']; 

// Check if vehicle data is fetched
if (!$vehicle) {
    echo "Vehicle not found.";
    exit;
}

// Calculate rental duration and price
$days = (strtotime($dropoff_date) - strtotime($pickup_date)) / (60 * 60 * 24);
$total_price = $days * $vehicle['daily_rate'];

// On form submission (booking confirmation)
if (isset($_POST['confirm_booking'])) {
        // Sanitize and validate inputs
        $cardtype = isset($_POST['cardtype']) ? htmlspecialchars($_POST['cardtype']) : null;
        $cardnumber = isset($_POST['cardnumber']) ? preg_replace('/\D/', '', $_POST['cardnumber']) : null; // Remove non-digits
        $card_fname = isset($_POST['card_fname']) ? htmlspecialchars($_POST['card_fname']) : null;
        $card_lname = isset($_POST['card_lname']) ? htmlspecialchars($_POST['card_lname']) : null;
        $cardexpiry = isset($_POST['cardexpiry']) ? htmlspecialchars($_POST['cardexpiry']) : null; // Assuming it's a string
        $card_cvv = isset($_POST['card_cvv']) ? preg_replace('/\D/', '', $_POST['card_cvv']) : null; // Remove non-digits        
    // Assuming user ID is stored in session
    $custid = $_SESSION['user_id'] ?? null;

    // Check if user is logged in
    if (!$custid) {
        echo "You must be logged in to book a vehicle.";
        exit;
    }

    // Extract card details from POST request
    $cardType = $_POST['cardtype'] ?? '';
    $cardNumber = $_POST['cardnumber'] ?? '';
    $cardFName = $_POST['card_fname'] ?? '';
    $cardLName = $_POST['card_lname'] ?? '';
    $cardExpiry = $_POST['cardexpiry'] ?? '';
    $cardCVV = $_POST['card_cvv'] ?? '';

    // Define regular expressions and validation rules
    $validCardTypes = ['VISA', 'MASTERCARD', 'AMEX', 'DISCOVER'];
    $cardNumberPattern = '/^\d{13,19}$/'; // Validate card number length (between 13 and 19 digits)
    $expiryPattern = '/^(0[1-9]|1[0-2])\/[0-9]{2}$/'; // Validate MM/YY format for expiry date
    $cvvPattern = '/^\d{3,4}$/'; // Validate CVV (3 or 4 digits)

    // Initialize an array to store validation errors
    $errors = [];

    // Validate card type
    if (!in_array($cardType, $validCardTypes)) {
    $errors['cardtype'] = 'Invalid card type.';
    }

    // Validate card number
    if (!preg_match($cardNumberPattern, $cardNumber)) {
    $errors['cardnumber'] = 'Invalid card number.';
    }

    // Validate cardholder first name
    if (empty($cardFName)) {
    $errors['card_fname'] = 'Cardholder first name is required.';
    }

    // Validate cardholder last name
    if (empty($cardLName)) {
    $errors['card_lname'] = 'Cardholder last name is required.';
    }

    // Validate expiry date
    $expiryPattern = '/^(0[1-9]|1[0-2])\/[0-9]{2}$/'; // Validate MM/YY format for expiry date
    if (!preg_match($expiryPattern, $cardExpiry)) {
        $errors['cardexpiry'] = 'Invalid expiry date. Use MM/YY format.';
    }

    // Validate CVV
    if (!preg_match($cvvPattern, $cardCVV)) {
    $errors['card_cvv'] = 'Invalid CVV. Must be 3 or 4 digits.';
    }

    // Check if there are any validation errors
    if (!empty($errors)) {
            // Display validation errors in a pop-up or alert
        echo "<script>alert('Validation errors:\\n";
        foreach ($errors as $field => $message) {
            echo "$field: $message\\n";
        }
        echo "');</script>";
    } else {
        // Start Transaction
        $conn->begin_transaction();
        try {

        // Check for overlapping bookings
        $checkSql = "SELECT COUNT(*) FROM adg_rentalservice WHERE vin = ? AND (pickup_date <= ? AND dropoff_date >= ?)";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("sss", $vin, $dropoff_date, $pickup_date);
        $checkStmt->execute();
        $overlapCount = $checkStmt->get_result()->fetch_array()[0];

        if ($overlapCount > 0) {
        throw new Exception("This vehicle is already booked for the selected dates.");
        }
        // Convert 'MM/YY' to 'YYYY-MM-DD'
        $expiryParts = explode('/', $cardExpiry);
        $expiryYear = '20' . $expiryParts[1]; // Assuming the year is in 'YY' format, you might need to adjust this if needed
        $expiryMonth = $expiryParts[0];
        $expiryDate = date('Y-m-d', strtotime("{$expiryYear}-{$expiryMonth}-01"));
    
        $paymentSql = "INSERT INTO adg_card_details (cardtype, cardnumber, card_fname, card_lname, cardexpiry, card_cvv) VALUES (?, ?, ?, ?, ?, ?)";
        $paymentStmt = $conn->prepare($paymentSql);
        $paymentStmt->bind_param("ssssss", $cardType, $cardNumber, $cardFName, $cardLName, $expiryDate, $cardCVV);
    
        // Insert into adg_rentalservice
        $sql = "INSERT INTO adg_rentalservice (pickup_date, dropoff_date, start_odometer, end_odometer, daily_odometer_limit, pickup_location, dropoff_location, custid, vin) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdddssis", $pickup_date, $dropoff_date, $start_odometer, $end_odometer, $daily_odometer_limit, $pickup_location, $dropoff_location, $custid, $vin);
            
        // Commit the transaction
        $conn->commit();
        // Check if both insertions were successful
        if ($paymentStmt->execute() && $stmt->execute()) {
            // Store invoice data in the session
            $_SESSION['invoice_data'] = [
                'vehicle' => $vehicle['vehiclemake'] . " " . $vehicle['vehiclemodel'],
                'pickup_date' => $pickup_date,
                'dropoff_date' => $dropoff_date,
                'total_price' => $total_price,
            ];
            header('Location: invoice.php');
            exit;
            // Update vehicle status here
            $updateSql = "UPDATE adg_vehicle SET statusid = 1 WHERE vin = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("s", $vin);
            $updateStmt->execute();
        }
     }
     catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo "<script>alert('Error occurred: " . $e->getMessage() . "');</script>";
    }
}
}
?>


<!doctype html>
<html lang="en">
<head>
    <title>ZappRental - Booking Details</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  </head>

<body>


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
    <!-- Vehicle and Booking Details Section -->
    <div class="container margintop">
        <div class="row">
        <div class="col-lg-12">
        <div class="d-flex flex-wrap align-items-stretch">
            <div class="col-lg-7">
                <div class="vehicle-info bg-light p-4 rounded mb-4">
                <img src="images/car/<?php echo $vehicle['vehiclemake'] . " " . $vehicle['vehiclemodel']; ?>.png" alt="<?php echo $vehicle['vehiclemake'] . ' ' . $vehicle['vehiclemodel']; ?>" class="img-fluid mb-3">
                    <div class="row">
                            <div class="booking-details bg-light p-4 rounded">
                                <!-- Vehicle and Booking Info -->
                                <h3><?php echo $vehicle['vehiclemake'] . " " . $vehicle['vehiclemodel']; ?></h3>
                                <p><strong>Year:</strong> <?php echo $vehicle['vehicleyear']; ?></p>
                                <p><strong>License Plate:</strong> <?php echo $vehicle['licenseplate']; ?></p>
                                <p><strong>Office Location:</strong> <?php echo $vehicle['officecity'] . ', ' . $vehicle['officestate']; ?></p>
                                <p><strong>Pick-up Date:</strong> <?php echo $pickup_date; ?></p>
                                <p><strong>Drop-off Date:</strong> <?php echo $dropoff_date; ?></p>
                                <p><strong>Total Price:</strong> $<?php echo $total_price; ?></p>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="payment-details bg-light p-4 rounded">
                        <h4>Payment Details</h4>
                        <form method="post">
                            <div class="form-group">
                                <label for="cardtype">Card Type</label>
                                <select name="cardtype" id="cardtype" class="form-control">
                                    <option value="VISA">VISA</option>
                                    <option value="MASTERCARD">MASTERCARD</option>
                                    <option value="AMEX">AMEX</option>
                                    <option value="DISCOVER">DISCOVER</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cardnumber">Card Number</label>
                                <input type="text" name="cardnumber" id="cardnumber" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="card_fname">First Name</label>
                                <input type="text" name="card_fname" id="card_fname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="card_lname">Last Name</label>
                                <input type="text" name="card_lname" id="card_lname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="cardexpiry">Expiry Date (MM/YY)</label>
                                <input type="text" name="cardexpiry" id="cardexpiry" placeholder="MM/YY" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="card_cvv">CVV</label>
                                <input type="text" name="card_cvv" id="card_cvv" class="form-control">
                            </div>
                            <input type="submit" name="confirm_booking" value="Confirm Booking" class="btn btn-primary btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </div>


              
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

<?php $conn->close(); ?>