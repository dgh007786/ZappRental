<?php
session_start();
include 'db_connection.php'; // Ensure you have the right path to your db_connection file

$userLoggedIn = isset($_SESSION['username']);
$username = $userLoggedIn ? htmlspecialchars($_SESSION['username']) : '';

// Fetch user data from the database
$custid = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM adg_customer WHERE custid = ?");
$stmt->bind_param("i", $custid);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    // User data found
} else {
    echo "User not found."; // User data not found
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Account</title>
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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .profile-section {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .profile-info {
            margin-bottom: 10px;
        }
        .profile-info strong {
            font-weight: 600;
        }
        .profile-info span {
            display: block;
            color: #555;
        }
        .profile-header {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 20px;
            font-weight: 700;
        }
    </style>
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

    <div class="site-section bg-light margintp">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 mb-5" data-aos="fade">
                    <h2 class="profile-header mb-5 text-black">My Account</h2>

                    <div class="profile-section">
                        <div class="profile-info"><strong>Email:</strong> <?php echo htmlspecialchars($row['emailid']); ?></div>
                        <div class="profile-info"><strong>Phone Number:</strong> <?php echo htmlspecialchars($row['phonenumber']); ?></div>
                        <div class="profile-info"><strong>First Name:</strong> <?php echo htmlspecialchars($row['cust_fname']); ?></div>
                        <div class="profile-info"><strong>Last Name:</strong> <?php echo htmlspecialchars($row['cust_lname']); ?></div>
                        <div class="profile-info"><strong>City:</strong> <?php echo htmlspecialchars($row['custcity']); ?></div>
                        <div class="profile-info"><strong>State:</strong> <?php echo htmlspecialchars($row['custstate']); ?></div>
                        <div class="profile-info"><strong>Street:</strong> <?php echo htmlspecialchars($row['custst']); ?></div>
                        <div class="profile-info"><strong>Zip Code:</strong> <?php echo htmlspecialchars($row['custzipcode']); ?></div>
                        <div class="profile-info"><strong>Country:</strong> <?php echo htmlspecialchars($row['custcountry']); ?></div>
                        <div class="profile-info"><strong>Customer Type:</strong> 
                          <?php
                            if ($row['cust_type'] == 'C') {
                                echo 'Corporate';
                            } else if ($row['cust_type'] == 'I') {
                                echo 'Individual';
                            }
                          ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <!-- ... Footer Content ... -->
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
