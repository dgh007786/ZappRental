<?php
$servername = "localhost"; 
$username = "root";    
$password = "adg_car_rental";    
$dbname = "adg_car_rental";    

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
