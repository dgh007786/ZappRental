<?php
include 'db_connection.php'; // Your database connection file
error_reporting(E_ALL);

$userLoggedIn = isset($_SESSION['username']);
$username = $userLoggedIn ? htmlspecialchars($_SESSION['username']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $login_type = $conn->real_escape_string($_POST['login_type']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    if ($login_type === "customer") {
    echo "Customer login selected."; 
    // Check if username exists
    $stmt = $conn->prepare("SELECT custid, password FROM adg_customer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['custid'];
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            // Invalid credentials
            $login_error = 'Invalid username or password.';
        }
    } else {
        // Username not found
        $login_error = 'Invalid username or password.';
    }
    $stmt->close();
    }
    elseif ($login_type === "employee") {
        echo "Employee login selected.";
        // Handle employee login
        // Check if username exists in adg_employee table
        $stmt = $conn->prepare("SELECT employeeid, emppasswd FROM adg_employee WHERE empusername = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($employee = $result->fetch_assoc()) {
            // Verify password
            if ($password === $employee['emppasswd']) {
                session_start();
                $_SESSION['employee_id'] = $employee['employeeid'];
                $_SESSION['employee_username'] = $username;
                header('Location: employee_dashboard.php'); // Redirect to the employee dashboard
                exit();
            } else {
                // Invalid credentials
                $login_error = 'Invalid employee username or password.';
            }
        } else {
            // Employee username not found
            $login_error = 'Invalid employee username or password.';
        }
        $stmt->close();
    }
    else {
        // Invalid login type
        $login_error = 'Invalid login type.';
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>ZappRental - Login</title>
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
</head>
<body>

<div class="site-wrap" id="home-section">
    <!-- Navigation, add your PHP login check logic here -->
    <!-- ... -->
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

    <!-- Hero Image -->
    <!-- Login Section -->
    <div class="site-section bg-light margintp">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 mb-5" data-aos="fade">

                    <h2 class="mb-5 text-black">Log In</h2>

                    <form action="login.php" method="post" class="bg-white p-md-5 p-4 mb-5 border rounded">
                    <?php if (!empty($login_error)): ?>
                            <div class="alert alert-danger"><?php echo $login_error; ?></div>
                    <?php endif; ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="login_type" id="customer_login" value="customer" checked>
                        <label class="form-check-label" for="customer_login">
                            Customer Login
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="login_type" id="employee_login" value="employee">
                        <label class="form-check-label" for="employee_login">
                            Employee Login
                        </label>
                    </div>
                        <div class="row form-group">
                            <div class="col-md-12 mb-3 mb-md-0">
                                <label class="text-black" for="username">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="submit" value="Log In" class="btn btn-primary btn-md text-white">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <div>
        <!-- Footer -->
    <footer class="site-footer">
        <!-- ... -->
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
