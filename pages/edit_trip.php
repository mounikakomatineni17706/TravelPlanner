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
$errors = [];

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
    
    // Set variables from trip data
    $destination = $trip['destination'];
    $start_date = $trip['start_date'];
    $end_date = $trip['end_date'];
    $description = $trip['description'];
    $budget = $trip['budget'];
    
} catch (PDOException $e) {
    $_SESSION['message'] = "Error retrieving trip details: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header("Location: dashboard.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate destination
    if (empty($_POST['destination'])) {
        $errors['destination'] = 'Destination is required';
    } else {
        $destination = validateInput($_POST['destination']);
    }
    
    // Validate start date
    if (empty($_POST['start_date'])) {
        $errors['start_date'] = 'Start date is required';
    } else {
        $start_date = validateInput($_POST['start_date']);
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $start_date)) {
            $errors['start_date'] = 'Invalid date format (YYYY-MM-DD)';
        }
    }
    
    // Validate end date
    if (empty($_POST['end_date'])) {
        $errors['end_date'] = 'End date is required';
    } else {
        $end_date = validateInput($_POST['end_date']);
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $end_date)) {
            $errors['end_date'] = 'Invalid date format (YYYY-MM-DD)';
        } elseif ($start_date && strtotime($end_date) < strtotime($start_date)) {
            $errors['end_date'] = 'End date must be after start date';
        }
    }
    
    // Validate budget (optional)
    if (!empty($_POST['budget'])) {
        $budget = validateInput($_POST['budget']);
        if (!is_numeric($budget) || $budget < 0) {
            $errors['budget'] = 'Budget must be a positive number';
        }
    } else {
        $budget = null;
    }
    
    // Get description (optional)
    $description = !empty($_POST['description']) ? validateInput($_POST['description']) : '';
    
    // If no errors, proceed with updating the trip
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("UPDATE trips SET destination = ?, start_date = ?, end_date = ?, description = ?, budget = ? WHERE trip_id = ? AND user_id = ?");
            $stmt->execute([
                $destination,
                $start_date,
                $end_date,
                $description,
                $budget,
                $trip_id,
                $_SESSION['user_id']
            ]);
            
            // Set success message
            $_SESSION['message'] = "Trip updated successfully!";
            $_SESSION['message_type'] = "success";
            
            // Redirect to view trip
            header("Location: view_trips.php?id=" . $trip_id);
            exit;
        } catch (PDOException $e) {
            $errors['db'] = "Error updating trip: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Trip - Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
    body {
        background-color: #dee1ec; /* Replace with your desired color */
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
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="form-container">
                    <h2 class="mb-4">Edit Trip</h2>
                    
                    <?php if (isset($errors['db'])): ?>
                        <div class="alert alert-danger"><?php echo $errors['db']; ?></div>
                    <?php endif; ?>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $trip_id; ?>" method="POST">
                        <div class="mb-3">
                            <label for="destination" class="form-label">Destination</label>
                            <input type="text" class="form-control <?php echo isset($errors['destination']) ? 'is-invalid' : ''; ?>" 
                                id="destination" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
                            <?php if (isset($errors['destination'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['destination']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control <?php echo isset($errors['start_date']) ? 'is-invalid' : ''; ?>" 
                                    id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                                <?php if (isset($errors['start_date'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['start_date']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control <?php echo isset($errors['end_date']) ? 'is-invalid' : ''; ?>" 
                                    id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                                <?php if (isset($errors['end_date'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['end_date']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="budget" class="form-label">Budget (optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control <?php echo isset($errors['budget']) ? 'is-invalid' : ''; ?>" 
                                    id="budget" name="budget" value="<?php echo htmlspecialchars($budget); ?>">
                                <?php if (isset($errors['budget'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['budget']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (optional)</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="view_trips.php?id=<?php echo $trip_id; ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Trip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p>Travel Planner &copy; 2025</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>