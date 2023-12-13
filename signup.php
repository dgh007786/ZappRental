<?php 
include 'db_connection.php'; 
ini_set('display_errors', 1);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $username = $conn->real_escape_string($_POST['username']);
    
    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM adg_customer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Username already exists
        echo "<script>alert('Username already exists. Redirecting to Login Page'); window.location = 'login.php';</script>";
        exit();
    }

    // Retrieve and sanitize user input
    $emailid = $conn->real_escape_string($_POST['emailid']);
    $phonenumber = $conn->real_escape_string($_POST['phonenumber']);
    $cust_fname = $conn->real_escape_string($_POST['cust_fname']);
    $cust_lname = $conn->real_escape_string($_POST['cust_lname']);
    $custcity = $conn->real_escape_string($_POST['custcity']);
    $custstate = $conn->real_escape_string($_POST['custstate']);
    $custst = $conn->real_escape_string($_POST['custst']);
    $custzipcode = $conn->real_escape_string($_POST['custzipcode']);
    $custcountry = $conn->real_escape_string($_POST['custcountry']);
    $cust_type = $conn->real_escape_string($_POST['cust_type']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into adg_customer
    $stmt = $conn->prepare("INSERT INTO adg_customer (emailid, phonenumber, cust_fname, cust_lname, custcity, custstate, custst, custzipcode, custcountry, cust_type, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisssss", $emailid, $phonenumber, $cust_fname, $cust_lname, $custcity, $custstate, $custst, $custzipcode, $custcountry, $cust_type, $username, $hashed_password);
    $cust_id = $conn->insert_id;

    if ($stmt->execute()) {
        $cust_id = $conn->insert_id;
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        
        if ($_POST['cust_type'] == 'C') {
            // Corporate customer
            $corporation_name = $conn->real_escape_string($_POST['corporation_name']);
            $registration_number = $conn->real_escape_string($_POST['registration_number']);
            $corporate_discount = rand(10, 30); // Generate random discount
    
            $stmt = $conn->prepare("INSERT INTO ADG_corporate (custid, corporation_name, registration_number, corporate_discount) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $cust_id, $corporation_name, $registration_number, $corporate_discount);
            $stmt->execute();
        }
        else if ($_POST['cust_type'] == 'I') {
            // Individual customer
            $driver_license_number = $conn->real_escape_string($_POST['driver_license_number']);
            $insurance_company_name = $conn->real_escape_string($_POST['insurance_company_name']);
            $insurance_policy_number = $conn->real_escape_string($_POST['insurance_policy_number']);
            $individual_discount = rand(10, 30); // Generate random discount
    
            $stmt = $conn->prepare("INSERT INTO adg_individual (custid, fname, lname, driver_license_number, insurance_company_name, insurance_policy_number, individual_discount) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssi", $cust_id, $cust_fname, $cust_lname, $driver_license_number, $insurance_company_name, $insurance_policy_number, $individual_discount);
            $stmt->execute();
        }
       // Redirect based on employee status
       if(isset($_POST['is_employee']) && $_POST['is_employee'] === 'yes') {
        $_SESSION['role'] = 'employee';
        header("Location: employee_dashboard.php");
            } else {
                header("Location: index.php");
            }
        } else {
            echo "Error: " . $stmt->error;
        }
            $stmt->close();
    $conn->close();
}
?>
<?php
session_start();
include 'db_connection.php'; // Your database connection file

$userLoggedIn = isset($_SESSION['username']);
$username = $userLoggedIn ? htmlspecialchars($_SESSION['username']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>ZappRental - Sign Up</title>
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
                <div class="col-md-10 mb-5" data-aos="fade">

                    <h2 class="mb-5 text-black">Sign Up</h2>
                    <form action="signup.php" method="post" class="bg-white p-md-5 p-4 mb-5 border rounded">
                        <!-- Form fields here -->
                        <!-- Email -->
                        <p>Already have an account? <a href="login.php">Log in here</a>.</p>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="emailid">Email:</label>
                                <input type="email" id="emailid" name="emailid" class="form-control" placeholder="Email Address" required>
                            </div>
                        </div>
                        <!-- Phone Number -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="phonenumber">Phone Number:</label>
                                <input type="text" id="phonenumber" name="phonenumber" class="form-control" placeholder="Phone Number" required>
                            </div>
                        </div>
                        <!-- First Name -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="cust_fname">First Name:</label>
                                <input type="text" id="cust_fname" name="cust_fname" class="form-control" placeholder="First Name" required>
                            </div>
                        </div>
                        <!-- Last Name -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="cust_lname">Last Name:</label>
                                <input type="text" id="cust_lname" name="cust_lname" class="form-control" placeholder="Last Name" required>
                            </div>
                        </div>
                      <!-- City -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="custcity">City:</label>
                                <input type="text" id="custcity" name="custcity" class="form-control" placeholder="City" required>
                            </div>
                        </div>
                        <!-- State -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="custstate">State:</label>
                                <input type="text" id="custstate" name="custstate" class="form-control" placeholder="State" required>
                            </div>
                        </div>
                        <!-- Street -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="custst">Street:</label>
                                <input type="text" id="custst" name="custst" class="form-control" placeholder="Street" required>
                            </div>
                        </div>
                        <!-- Zip Code -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="custzipcode">Zip Code:</label>
                                <input type="text" id="custzipcode" name="custzipcode" class="form-control" placeholder="Zip Code" required>
                            </div>
                        </div>
                        <!-- Country -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="custcountry">Country:</label>
                                <input type="text" id="custcountry" name="custcountry" class="form-control" placeholder="Country" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="cust_type">Customer Type:</label>
                                <select name="cust_type" id="cust_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="C">Corporate</option>
                                    <option value="I">Individual</option>
                                </select>
                            </div>
                        </div>
                        <!-- Additional fields for Corporate -->
                        <div id="corporateFields" style="display: none;">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label class="text-black" for="corporation_name">Corporation Name:</label>
                                    <input type="text" id="corporation_name" name="corporation_name" class="form-control" placeholder="Corporation Name">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label class="text-black" for="registration_number">Registration Number:</label>
                                    <input type="text" id="registration_number" name="registration_number" class="form-control" placeholder="Registration Number">
                                </div>
                            </div>
                        </div>

                        <!-- Additional fields for Individual -->
                        <div id="individualFields" style="display: none;">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label class="text-black" for="driver_license_number">Driver License Number:</label>
                                    <input type="text" id="driver_license_number" name="driver_license_number" class="form-control" placeholder="Driver License Number">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label class="text-black" for="insurance_company_name">Insurance Company Name:</label>
                                    <input type="text" id="insurance_company_name" name="insurance_company_name" class="form-control" placeholder="Insurance Company Name">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label class="text-black" for="insurance_policy_number">Insurance Policy Number:</label>
                                    <input type="text" id="insurance_policy_number" name="insurance_policy_number" class="form-control" placeholder="Insurance Policy Number">
                                </div>
                            </div>
                        </div>
                        <!-- Username -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="username">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="is_employee">Are you an employee?</label>
                                <input type="checkbox" id="is_employee" name="is_employee" value="yes">
                            </div>
                        </div>
                        <!-- Submit button -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="submit" value="Sign Up" class="btn btn-primary btn-md text-white">
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
    <script>
        document.getElementById('cust_type').addEventListener('change', function() {
        var selectedType = this.value;
        document.getElementById('corporateFields').style.display = (selectedType === 'C') ? 'block' : 'none';
        document.getElementById('individualFields').style.display = (selectedType === 'I') ? 'block' : 'none';
    });
    // Example JavaScript to redirect user if they are already signed up
    // This part should be adapted based on your actual login check logic
    document.getElementById('signupForm').addEventListener('submit', function(event) {
        var username = document.getElementById('username').value;
        // Here, you would check if the user is already signed up, perhaps via an AJAX call to your server
        // For demonstration, let's assume a function `isUserSignedUp(username)` returns true if the user exists
        if (isUserSignedUp(username)) {
            event.preventDefault(); // Stop form submission
            window.location.href = 'login.php'; // Redirect to login page
        }
    });
    
    function isUserSignedUp(username) {
        // Placeholder for actual sign-up check logic
        return false; // This should be replaced with actual check
    }
    </script>

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
