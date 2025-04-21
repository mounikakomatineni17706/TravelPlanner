<?php
session_start();
require_once "../db/connect.php";

// Check if user is logged in
requireLogin();

// Initialize
$searchTerm = '';
$trips = [];

try {
    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $searchTerm = trim($_GET['search']);
        $stmt = $conn->prepare("SELECT * FROM trips WHERE user_id = ? AND (destination LIKE ? OR description LIKE ?) ORDER BY start_date DESC");
        $likeSearch = "%$searchTerm%";
        $stmt->execute([$_SESSION['user_id'], $likeSearch, $likeSearch]);
    } else {
        $stmt = $conn->prepare("SELECT * FROM trips WHERE user_id = ? ORDER BY start_date DESC");
        $stmt->execute([$_SESSION['user_id']]);
    }
    $trips = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error retrieving trips: " . $e->getMessage();
}

// Message handling
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
$messageType = '';
if (isset($_SESSION['message_type'])) {
    $messageType = $_SESSION['message_type'];
    unset($_SESSION['message_type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background-color: #c4dce0;
        }

        .container {
            flex: 1;
        }

        footer {
            position: relative;
            bottom: 0;
            width: 100%;
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
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <h1 class="mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

            <form class="d-flex" method="GET" action="dashboard.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Search trips..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>

            <a href="add_trip.php" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Add New Trip
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif (empty($trips)): ?>
            <div class="alert alert-info">
                <p><?php echo $searchTerm ? 'No trips matched your search.' : 'You haven\'t added any trips yet.'; ?></p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($trips as $trip): ?>
                    <div class="col-md-4">
                        <div class="card trip-card">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($trip['destination']); ?></h3>
                                <p class="trip-dates">
                                    <i class="bi bi-calendar"></i>
                                    <?php echo date('M d, Y', strtotime($trip['start_date'])); ?> -
                                    <?php echo date('M d, Y', strtotime($trip['end_date'])); ?>
                                </p>

                                <?php if (!empty($trip['budget'])): ?>
                                    <p><i class="bi bi-currency-dollar"></i> Budget: $<?php echo number_format($trip['budget'], 2); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($trip['description'])): ?>
                                    <p class="card-text">
                                        <?php echo nl2br(htmlspecialchars(substr($trip['description'], 0, 100))); ?>
                                        <?php if (strlen($trip['description']) > 100): ?>...<?php endif; ?>
                                    </p>
                                <?php endif; ?>

                                <div class="mt-3">
                                    <a href="view_trips.php?id=<?php echo $trip['trip_id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="edit_trip.php?id=<?php echo $trip['trip_id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="delete_trip.php?id=<?php echo $trip['trip_id']; ?>" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this trip?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-light py-4">
        <div class="container text-center">
            <p>Travel Planner &copy; 2025</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
