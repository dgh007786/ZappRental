<?php
session_start(); 
include 'db_connection.php'; // Include your database connection script
ini_set('display_errors', 1);
echo "Error: " . $stmt->error;

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
        echo "<script>alert('Username already exists. Redirecting to Login Page'); window.location = 'login.html';</script>";
        exit(); // Stop script execution
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

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert data
    $stmt = $conn->prepare("INSERT INTO adg_customer (emailid, phonenumber, cust_fname, cust_lname, custcity, custstate, custst, custzipcode, custcountry, cust_type, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisssss", $emailid, $phonenumber, $cust_fname, $cust_lname, $custcity, $custstate, $custst, $custzipcode, $custcountry, $cust_type, $username, $hashed_password);
    
    // Execute and check for errors
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        // Check if a session variable exists and has a value
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
