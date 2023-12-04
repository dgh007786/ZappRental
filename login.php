<?php
session_start();

include 'db_connection.php'; // Your database connection file

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
            $_SESSION['user_id'] = $user['custid'];
            $_SESSION['username'] = $username;
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            header('Location: index.php');
            exit();
        } else {
            // Invalid credentials
            echo "<script>alert('Invalid username or password.'); window.location = 'login.html';</script>";
            exit();
        }
    } else {
        // Username not found
        echo "<script>alert('Invalid username or password.'); window.location = 'login.html';</script>";
        exit();
    }
}
?>
