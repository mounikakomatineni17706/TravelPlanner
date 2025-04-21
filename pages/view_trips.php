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

// Get trip details
try {
    $stmt = $conn->prepare("SELECT * FROM trips WHERE trip_id = ? AND user_id = ?");
    $stmt->execute([$trip_id, $_SESSION['user_id']]);
    $trip = $stmt->fetch();
    
    // If trip doesn't exist or doesn't belong to user
    if (!$trip) {
        $_SESSION['message'] = "Trip not found or access denied";
        $_SESSION['message_type'] = "danger";
        header("Location: dashboard.php");
        exit;
    }
    
    // Get activities for this trip (if any)
    $stmt = $conn->prepare("SELECT * FROM activities WHERE trip_id = ? ORDER BY activity_date ASC");
    $stmt->execute([$trip_id]);
    $activities = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $_SESSION['message'] = "Error retrieving trip details: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($trip['destination']); ?> - Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-image: url('../images/bg1.jpg'); /* Replace with your image path */
            background-size: cover; /* Ensures the background image covers the entire page */
            background-position: center; /* Keeps the image centered */
            background-repeat: no-repeat; /* Prevents repeating the background image */
            padding-bottom: 70px; /* Add some space to avoid footer overlap */
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #343a40;
            color: #fff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Travel Planner</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="mb-4">
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h1 class="card-title"><?php echo htmlspecialchars($trip['destination']); ?></h1>
                        
                        <div class="mb-3">
                            <span class="badge bg-primary">
                                <i class="bi bi-calendar"></i> 
                                <?php echo date('M d, Y', strtotime($trip['start_date'])); ?> - 
                                <?php echo date('M d, Y', strtotime($trip['end_date'])); ?>
                            </span>
                            
                            <?php
                            // Calculate trip duration
                            $start = new DateTime($trip['start_date']);
                            $end = new DateTime($trip['end_date']);
                            $interval = $start->diff($end);
                            $duration = $interval->days + 1;
                            ?>
                            <span class="badge bg-info text-dark">
                                <i class="bi bi-hourglass"></i> <?php echo $duration; ?> day<?php echo $duration != 1 ? 's' : ''; ?>
                            </span>
                        </div>
                        
                        <?php if (!empty($trip['description'])): ?>
                            <h5 class="mt-4">Description</h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($trip['description'])); ?></p>
                        <?php endif; ?>
                        
                        <div class="d-flex mt-4">
                            <a href="edit_trip.php?id=<?php echo $trip['trip_id']; ?>" class="btn btn-warning me-2">
                                <i class="bi bi-pencil"></i> Edit Trip
                            </a>
                            <a href="delete_trip.php?id=<?php echo $trip['trip_id']; ?>" class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this trip?');">
                                <i class="bi bi-trash"></i> Delete Trip
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Trip Details</h5>
                        
                        <ul class="list-group list-group-flush">
                            <?php if (!empty($trip['budget'])): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-currency-dollar"></i> Budget</span>
                                    <span class="fw-bold">$<?php echo number_format($trip['budget'], 2); ?></span>
                                </li>
                            <?php endif; ?>
                            
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-calendar-date"></i> Created</span>
                                <span><?php echo date('M d, Y', strtotime($trip['created_at'])); ?></span>
                            </li>
                            
                            <?php if ($trip['created_at'] != $trip['updated_at']): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-pencil-square"></i> Last Updated</span>
                                    <span><?php echo date('M d, Y', strtotime($trip['updated_at'])); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Activities section could go here if you want to implement it -->
                <?php if (!empty($activities)): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Activities</h5>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($activities as $activity): ?>
                                <li class="list-group-item">
                                    <h6><?php echo htmlspecialchars($activity['activity_name']); ?></h6>
                                    <?php if (!empty($activity['activity_date'])): ?>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i> 
                                            <?php echo date('M d, Y', strtotime($activity['activity_date'])); ?>
                                        </small>
                                    <?php endif; ?>
                                    <?php if (!empty($activity['location'])): ?>
                                        <p class="mb-1">
                                            <i class="bi bi-geo-alt"></i> 
                                            <?php echo htmlspecialchars($activity['location']); ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if (!empty($activity['notes'])): ?>
                                        <p class="mb-0 small"><?php echo htmlspecialchars($activity['notes']); ?></p>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-light py-4">
        <div class="container text-center">
            <p>Travel Planner &copy; 2025</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>