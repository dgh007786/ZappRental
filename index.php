<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

$userLoggedIn = isset($_SESSION['username']);
$username = $userLoggedIn ? htmlspecialchars($_SESSION['username']) : '';

$sql = "SELECT * FROM ADG_Vehicle LIMIT 6";
$result = $conn->query($sql);

$officeQuery = "SELECT office_id, officecity FROM adg_office";
$officeResult = $conn->query($officeQuery);

$classQuery = "SELECT classid, classname FROM adg_vehicleclass";
$classResult = $conn->query($classQuery);
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

      
      <div class="hero" style="background-image: url('images/hero_1_a.jpg');">
        
        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-lg-12">

              <div class="row mb-5">
                <div class="col-lg-7 intro">
                  <h1><strong>Rent a Car</strong> Easily with ZappRental</h1>
              </div>
                </div>
              </div>
              
              <form class="trip-form" action="searchresults.php" method="get">
                <div class="row align-items-center">
                    <!-- Office Location Select -->
                    <div class="mb-3 mb-md-0 col-md-2">
                        <select name="office_id" id="office_id" class="custom-select form-control">
                            <option value="">Location</option>
                            <?php while($office = $officeResult->fetch_assoc()): ?>
                                <option value="<?php echo $office['office_id']; ?>"><?php echo $office['officecity']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <!-- Vehicle Class Select -->
                    <div class="mb-3 mb-md-0 col-md-2">
                        <select name="classid" id="classid" class="custom-select form-control">
                            <option value="">Class</option>
                            <?php while($class = $classResult->fetch_assoc()): ?>
                                <option value="<?php echo $class['classid']; ?>"><?php echo $class['classname']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <!-- Pick-up Date -->
                    <div class="mb-3 mb-md-0 col-md-3">
                        <input type="date" name="pickup_date" placeholder="Pick up" class="form-control" min="2024-01-01" max="2024-01-31" required>
                    </div>
                    <div class="mb-3 mb-md-0 col-md-3">
                        <input type="date" name="dropoff_date" placeholder="Drop off" class="form-control" min="2024-01-01" max="2024-01-31" required>
                    </div>
                    <!-- Submit Button -->
                    <div class="mb-3 mb-md-0 col-md-2">
                        <input type="submit" value="Search" class="btn btn-primary btn-block py-3">
                    </div>
                </div>
            </form>

            </div>
          </div>
        </div>
      </div>
  


      <div class="site-section">
        <div class="container">
          <h2 class="section-heading"><strong>How it works?</strong></h2>
          <p class="mb-5">Follow these simple steps to rent your perfect car</p>    

          <div class="row mb-5">
            <div class="col-lg-4 mb-4 mb-lg-0">
              <div class="step">
                <span>1</span>
                <div class="step-inner">
                  <span class="number text-primary">01.</span>
                  <h3>Select a car</h3>
                  <p>Browse through our wide range of vehicles and pick the one that fits your needs and style.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
              <div class="step">
                <span>2</span>
                <div class="step-inner">
                  <span class="number text-primary">02.</span>
                  <h3>Fill up form</h3>
                  <p>Fill out the necessary details in our easy-to-use rental form to get started with your booking.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
              <div class="step">
                <span>3</span>
                <div class="step-inner">
                  <span class="number text-primary">03.</span>
                  <h3>Payment</h3>
                  <p>Choose your preferred payment method and complete the process to confirm your rental.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="site-section">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-7 text-center order-lg-2">
              <div class="img-wrap-1 mb-5">
                <img src="images/feature_01.png" alt="Image" class="img-fluid">
              </div>
            </div>
            <div class="col-lg-4 ml-auto order-lg-1">
            <h3 class="mb-4 section-heading"><strong>Exclusive Offers on Car Rentals</strong></h3>
              <p class="mb-5">Enjoy seamless car rentals with our exclusive offers. Whether you need a car for a business trip or a family vacation, we have just the right options for you.</p>
              
              <p><a href="#" class="btn btn-primary">Explore Offers</a></p>
            </div>
          </div>
        </div>
      </div>

      

      <div class="site-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <h2 class="section-heading"><strong>Top Car Listings</strong></h2>
                    <p class="mb-5">iscover our most popular cars that our customers love.</p>    
                </div>
            </div>
            
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                      $imageFileName = $row["vehiclemake"] . " " . $row["vehiclemodel"]; 
                      $imagePath = "images/car/" . $imageFileName . ".png";
                        echo "<div class='col-md-6 col-lg-4 mb-4'>";
                        echo "<div class='listing d-block align-items-stretch'>";
                        echo "<div class='listing-img h-100 mr-4'><img src='" . $imagePath . "' alt='" . $row["vehiclemodel"] . "' class='img-fluid'></div>";
                        echo "<div class='listing-contents h-100'>";
                        echo "<h3>" . $row["vehiclemake"] . " " . $row["vehiclemodel"] . "</h3>";
                        echo "<p><strong>Year:</strong> " . $row["vehicleyear"] . "</p>";
                        echo "<p><a href='#' class='btn btn-primary btn-sm'>Rent Now</a></p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='text-center'>No available listings at the moment.</p>";
                }
                ?>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <a href="listing.php" class="btn btn-primary">View All Listings</a>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-7">
        <h2 class="section-heading"><strong>Our Features</strong></h2>
        <p class="mb-5">Discover the advantages of renting with ZappRental</p>    
      </div>
    </div>

    <div class="row">
     
      <div class="col-lg-4 mb-5">
        <div class="service-1 dark">
          <span class="service-1-icon">
            <span class="icon-security"></span>
          </span>
          <div class="service-1-contents">
            <h3>Safe & Reliable</h3>
            <p>Experience peace of mind with vehicles that are regularly serviced and maintained to the highest safety standards.</p>
            <p class="mb-0"><a href="#">Our Commitment</a></p>
          </div>
        </div>
      </div>

      <div class="col-lg-4 mb-5">
        <div class="service-1 dark">
          <span class="service-1-icon">
            <span class="icon-clock-o"></span>
          </span>
          <div class="service-1-contents">
            <h3>24/7 Customer Support</h3>
            <p>Our dedicated team is available around the clock to assist you with any queries or issues for a hassle-free rental experience.</p>
            <p class="mb-0"><a href="#">Contact Us</a></p>
          </div>
        </div>
      </div>

      <div class="col-lg-4 mb-5">
        <div class="service-1 dark">
          <span class="service-1-icon">
            <span class="icon-thumbs-up"></span>
          </span>
          <div class="service-1-contents">
            <h3>Customer Satisfaction</h3>
            <p>We pride ourselves on providing excellent customer service, ensuring a pleasant and satisfactory rental experience.</p>
            <p class="mb-0"><a href="#">Read Reviews</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="site-section bg-primary py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-7 mb-4 mb-md-0">
          <h2 class="mb-0 text-white">Ready to Start Your Journey?</h2>
            <p class="mb-0 opa-7">Choose from our wide selection of vehicles and book your ride today!</p>
          </div>
          <div class="col-lg-5 text-md-right">
            <a href="#" class="btn btn-primary btn-white">Rent a car now</a>
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

<?php
$conn->close();
?>