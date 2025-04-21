<?php
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === '123456789') {
        $_SESSION['user_id'] = 0;
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'admin';

        header("Location: admin_dashboard.php");
        exit;
    } else {
        $errors['login'] = 'Invalid admin credentials.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-image: url('../images/bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../index.php">Travel Planner</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="form-container">
                <h2 class="text-center mb-4">Admin Login</h2>

                <?php if (!empty($errors['login'])): ?>
                    <div class="alert alert-danger"><?php echo $errors['login']; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Login as Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<br><br>
<footer class="bg-dark text-light py-4 mt-5">
    <div class="container text-center">
        <p>Travel Planner &copy; 2025</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
