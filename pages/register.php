<?php
session_start();
require_once "../db/connect.php";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errors = [];
$username = $email = $role = '';
$roles = ['User', 'Admin']; // Define allowed roles

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate username
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username is required';
    } else {
        $username = validateInput($_POST['username']);
        if (strlen($username) < 3) {
            $errors['username'] = 'Username must be at least 3 characters';
        }
    }
    
    // Validate email
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email is required';
    } else {
        $email = validateInput($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
    }

    // Validate role
    if (empty($_POST['role']) || !in_array($_POST['role'], $roles)) {
        $errors['role'] = 'Please select a valid role';
    } else {
        $role = validateInput($_POST['role']);
    }
    
    // Validate password
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($_POST['password']) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }
    
    // Validate password confirmation
    if ($_POST['password'] !== $_POST['password_confirm']) {
        $errors['password_confirm'] = 'Passwords do not match';
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        try {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            $user = $stmt->fetch();

            if ($user) {
                if ($user['username'] === $username) {
                    $errors['username'] = 'Username already exists';
                }
                if ($user['email'] === $email) {
                    $errors['email'] = 'Email already exists';
                }
            } else {
                // Hash password
                $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password, $role]);

                if ($role === 'Admin') {
                    $success = "Your admin registration request has been received. Please wait for approval.";
                } else {
                    $success = "Registration successful! You can now login.";
                    $username = $email = $role = ''; // Reset form values
                }
            }
        } catch (PDOException $e) {
            $errors['db'] = "Registration failed: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-image: url('../images/bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Optional: Keeps the background fixed during scrolling */
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
                    <a class="nav-link" href="../about.php" style="color: white;">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../contact.php" style="color: white;">Contact</a>
                </li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_login.php">Admin Login</a>
                    </li>
                    <li class="nav-item"><a class="nav-link active" href="register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="form-container">
                    <h2 class="text-center mb-4">Create an Account</h2>
                    
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <div class="text-center mt-3">
                            <a href="login.php" class="btn btn-primary">Login Now</a>
                        </div>
                    <?php else: ?>
                        <?php if (isset($errors['db'])): ?>
                            <div class="alert alert-danger"><?php echo $errors['db']; ?></div>
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
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                    id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-select <?php echo isset($errors['role']) ? 'is-invalid' : ''; ?>">
                                    <option value="">Select role</option>
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?php echo $r; ?>" <?php echo ($role === $r) ? 'selected' : ''; ?>>
                                            <?php echo $r; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['role'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['role']; ?></div>
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

                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control <?php echo isset($errors['password_confirm']) ? 'is-invalid' : ''; ?>" 
                                    id="password_confirm" name="password_confirm">
                                <?php if (isset($errors['password_confirm'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password_confirm']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            Already have an account? <a href="login.php">Login here</a>
                        </div>
                    <?php endif; ?>
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
