<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

$userLoggedIn = isset($_SESSION['username']);
$username = $userLoggedIn ? htmlspecialchars($_SESSION['username']) : '';

$office_id = $_GET['office_id'] ?? null;
$class_id = $_GET['classid'] ?? null;
$pickup_date = $_GET['pickup_date'] ?? null;
$dropoff_date = $_GET['dropoff_date'] ?? null;

$vehicles = [];

if ($office_id && $class_id && $pickup_date && $dropoff_date) {
    $sql = "SELECT * FROM adg_vehicle
            WHERE office_id = ?
            AND classid = ?
            AND vin NOT IN (
                SELECT vin FROM adg_rentalservice
                WHERE (pickup_date BETWEEN ? AND ?)
                OR (dropoff_date BETWEEN ? AND ?)
            )";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }
    $stmt->bind_param("iissss", $office_id, $class_id, $pickup_date, $dropoff_date, $pickup_date, $dropoff_date);
    if (!$stmt->execute()) {
        echo "Error executing query: " . $stmt->error;
        exit;
    }
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>ZappRental</title>
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

      <!-- Add your header/navigation code here -->


  
      <!-- Car Listings Section -->
      <div class="site-section bg-light">
        <div class="container margintop">
          <?php if (empty($vehicles)): ?>
            <p class="text-center">No available listings based on your search criteria.</p>
          <?php else: ?>
            <div class="row">
              <?php foreach ($vehicles as $row): ?>
                <?php
                $imageFileName = $row["vehiclemake"] . " " . $row["vehiclemodel"]; 
                $imagePath = "images/car/" . $imageFileName . ".png";
                ?>
                <div class='col-md-6 col-lg-4 mb-4'>
                  <div class='listing d-block align-items-stretch'>
                    <div class='listing-img h-100 mr-4'><img src='<?php echo $imagePath; ?>' alt='<?php echo $row["vehiclemodel"]; ?>' class='img-fluid'></div>
                    <div class='listing-contents h-100'>
                      <h3><?php echo $row["vehiclemake"] . " " . $row["vehiclemodel"]; ?></h3>
                      <p><strong>Year:</strong> <?php echo $row["vehicleyear"]; ?></p>
                      <a href='booking.php?vin=<?php echo $row["vin"]; ?>&pickup_date=<?php echo urlencode($pickup_date); ?>&dropoff_date=<?php echo urlencode($dropoff_date); ?>' class='btn btn-primary btn-sm'>Rent Now</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-lg-12 text-center">
              <a href="listing.php" class="btn btn-primary">View All Listings</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <!-- Add your footer code here -->

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

<?php
$conn->close();
?>
