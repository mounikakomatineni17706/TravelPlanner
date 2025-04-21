<?php
session_start();
require_once "../db/connect.php";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if (strtolower($_SESSION['role']) === 'admin') { // Ensure the role is checked in lowercase
        header("Location: admin_dashboard.php");
        exit;
    } else {
        header("Location: dashboard.php");
        exit;
    }
}

$errors = [];
$username = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate username
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username is required';
    } else {
        $username = validateInput($_POST['username']);
    }

    // Validate password
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password is required';
    }

    // If no validation errors, attempt login
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($_POST['password'], $user['password'])) {
                // Login successful, set session - Changed 'id' to 'user_id' to match database column
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // Store the role as it is in the database
                
                // Set remember-me cookie if requested
                if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                    $token = bin2hex(random_bytes(16));
                    setcookie('remember_token', $token, time() + (86400 * 30), "/");
                }

                // Debug line - you can remove this after testing
                error_log("User {$user['username']} logged in with role: {$user['role']}");
                
                // Redirect based on role - case-insensitive comparison
                if (strtolower($user['role']) === 'admin') { // Ensure case-insensitive role check
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    header("Location: dashboard.php");
                    exit;
                }
            } else {
                $errors['login'] = 'Invalid username or password';
            }
        } catch (PDOException $e) {
            $errors['db'] = "Login failed: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
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
                        <a class="nav-link active" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="form-container">
                    <h2 class="text-center mb-4">Login to Your Account</h2>

                    <?php if (isset($errors['login']) || isset($errors['db'])): ?>
                        <div class="alert alert-danger">
                            <?php echo isset($errors['login']) ? $errors['login'] : $errors['db']; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                id="password" name="password">
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        Don't have an account? <a href="register.php">Register here</a>
                    </div>
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