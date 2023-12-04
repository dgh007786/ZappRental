<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// if (!isset($_SESSION['user_id'])) {
//     $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
//     header('Location: login.html'); // Redirect to login if not logged in
//     exit();
// }
include 'db_connection.php'; 

// Fetch user data from the database
$custid = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM adg_customer WHERE custid = ?");
$stmt->bind_param("i", $custid);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    // User data found
    // ... Display user data
} else {
    echo "User not found."; // User data not found
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Account</title>
    <!-- Add your CSS links here -->
</head>
<body>

<h2>User Profile</h2>

<div>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['emailid']); ?></p>
    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($row['phonenumber']); ?></p>
    <p><strong>First Name:</strong> <?php echo htmlspecialchars($row['cust_fname']); ?></p>
    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($row['cust_lname']); ?></p>
    <p><strong>City:</strong> <?php echo htmlspecialchars($row['custcity']); ?></p>
    <p><strong>State:</strong> <?php echo htmlspecialchars($row['custstate']); ?></p>
    <p><strong>Street:</strong> <?php echo htmlspecialchars($row['custst']); ?></p>
    <p><strong>Zip Code:</strong> <?php echo htmlspecialchars($row['custzipcode']); ?></p>
    <p><strong>Country:</strong> <?php echo htmlspecialchars($row['custcountry']); ?></p>
    <p><strong>Customer Type:</strong> <?php echo htmlspecialchars($row['cust_type']); ?></p>
</div>

</body>
</html>
