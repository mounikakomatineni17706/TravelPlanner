<?php
// Database connection (adjust if your config is different)
$host = "localhost";
$dbname = "travel_planner";
$username = "root";
$password = ""; // Change if your MySQL has a password

// Create DB connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form values safely
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$message = htmlspecialchars(trim($_POST['message']));

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);

// Execute and check
if ($stmt->execute()) {
    echo "<script>alert('Thank you for contacting us! We will get back to you shortly.'); window.location.href='contact.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
