<?php
// Database connection parameters
$host = 'sql312.infinityfree.com';
$db_name = 'if0_38796325_travel_planner';
$username = 'if0_38796325';
$password = '2IoUFALdQggv'; // Default XAMPP has no password

// Create connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Helper function for showing success/error messages
function showMessage($message, $type = 'success') {
    return "<div class='alert alert-$type'>$message</div>";
}

// Validate form inputs
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}
?>