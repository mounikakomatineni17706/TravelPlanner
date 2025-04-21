<?php
session_start();
require_once "../db/connect.php";

// Redirect if not admin
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit;
}

// Check for valid user_id
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    echo "Invalid user ID.";
    exit;
}

$userId = $_GET['user_id'];

// Fetch trips for the selected user
try {
    $stmt = $conn->prepare("SELECT * FROM trips WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmt->execute();
    $trips = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching trips: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Trip Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Trip Details</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">Admin: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Back to Dashboard</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container mt-5 mb-5">
    <h3 class="mb-4">Trips for User ID: <?php echo htmlspecialchars($userId); ?></h3>

    <?php if (count($trips) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Trip ID</th>
                        <th>Destination</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Description</th>
                        <th>Budget</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trips as $trip): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trip['trip_id']); ?></td>
                            <td><?php echo htmlspecialchars($trip['destination']); ?></td>
                            <td><?php echo htmlspecialchars($trip['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($trip['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($trip['description']); ?></td>
                            <td><?php echo htmlspecialchars($trip['budget']); ?></td>
                            <td><?php echo htmlspecialchars($trip['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($trip['updated_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No trips found for this user.</div>
    <?php endif; ?>
</main>

<footer class="bg-dark text-light py-4">
    <div class="container text-center">
        <p>Travel Planner &copy; 2025</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
