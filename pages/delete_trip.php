<?php
session_start();
require_once "../db/connect.php";

// Check if user is logged in
requireLogin();

// Check if trip ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "Invalid trip ID";
    $_SESSION['message_type'] = "danger";
    header("Location: dashboard.php");
    exit;
}

$trip_id = validateInput($_GET['id']);

// Verify the trip belongs to the current user
try {
    $stmt = $conn->prepare("SELECT trip_id FROM trips WHERE trip_id = ? AND user_id = ?");
    $stmt->execute([$trip_id, $_SESSION['user_id']]);
    $trip = $stmt->fetch();
    
    // If trip doesn't exist or doesn't belong to user
    if (!$trip) {
        $_SESSION['message'] = "Trip not found or access denied";
        $_SESSION['message_type'] = "danger";
        header("Location: dashboard.php");
        exit;
    }
    
    // Delete trip
    $stmt = $conn->prepare("DELETE FROM trips WHERE trip_id = ? AND user_id = ?");
    $stmt->execute([$trip_id, $_SESSION['user_id']]);
    
    // Set success message
    $_SESSION['message'] = "Trip deleted successfully!";
    $_SESSION['message_type'] = "success";
    
} catch (PDOException $e) {
    $_SESSION['message'] = "Error deleting trip: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

// Redirect to dashboard
header("Location: dashboard.php");
exit;
?>