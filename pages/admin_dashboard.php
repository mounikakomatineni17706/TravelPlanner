<?php
session_start();
require_once "../db/connect.php";

// Redirect non-admin users
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit;
}

try {
    // Fetch all non-admin users
    $stmt = $conn->prepare("SELECT user_id, username, email, created_at, role FROM users WHERE LOWER(role) != 'admin'");
    $stmt->execute();
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
       html, body {
    height: 100%;
    background-image: url('../images/adminbg.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

body {
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
}

footer {
    position: relative;
    bottom: 0;
    width: 100%;
}

.table-responsive {
    margin-top: 20px;
}

.table {
    background-color: rgba(255, 255, 255, 0.8); /* White background with some transparency */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Adding shadow for emphasis */
}

.table th, .table td {
    vertical-align: middle;
    padding: 12px 15px;
    text-align: center;
}

.table thead {
    background-color: #343a40; /* Dark background for the header */
    color: #fff; /* White text for the header */
}

.table tbody tr:nth-child(odd) {
    background-color: #f8f9fa; /* Light grey background for odd rows */
}

.table tbody tr:nth-child(even) {
    background-color: #e9ecef; /* Slightly darker background for even rows */
}

.table-hover tbody tr:hover {
    background-color: #f1f1f1; /* Highlight on row hover */
}

.table td {
    font-size: 14px; /* Slightly smaller font for the table cells */
}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Registered Users</h2>

    <?php if (count($users) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <a href="view_user.php?user_id=<?php echo urlencode($user['user_id']); ?>" class="btn btn-sm btn-info">Show</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No users found.</div>
    <?php endif; ?>
</div>

<footer class="bg-dark text-light py-4 mt-auto">
        <div class="container text-center">
            <p>Travel Planner &copy; 2025</p>
        </div>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

