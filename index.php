
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Travel Planner</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/login.php" style="color: white;">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/register.php" style="color: white;">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Image -->
    <div class="hero-image">
        <img src="images/hero.jpg" alt="Travel Adventure" class="img-fluid w-100">
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <h1 class="display-4">Plan Your Next Adventure</h1>
                <p class="lead">Organize your trips, track your itineraries, and make your travel dreams a reality.</p>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="pages/dashboard.php" class="btn btn-primary btn-lg">View My Trips</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- About Section -->
        <div class="row mt-5 align-items-center">
            <div class="col-md-6">
                <img src="images/about.jpg" alt="About Travel Planner" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h2>About Travel Planner</h2>
                <p>Travel Planner helps you turn your dream journeys into real itineraries. From booking details to budget planning, we make it easy to keep everything in one place so you can focus on enjoying your adventures.</p>
            </div>
        </div>

        <!-- Destinations Section -->
        <div class="row mt-5 text-center">
            <h2 class="mb-4">Popular Destinations</h2>

            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/paris.jpg" class="card-img-top" alt="Paris">
                    <div class="card-body">
                        <h5 class="card-title">Paris, France</h5>
                        <p class="card-text">Experience the romance of the Eiffel Tower and world-class cuisine.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/tokyo.jpg" class="card-img-top" alt="Tokyo">
                    <div class="card-body">
                        <h5 class="card-title">Tokyo, Japan</h5>
                        <p class="card-text">Dive into a vibrant culture of technology, tradition, and tasty street food.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/bali.jpg" class="card-img-top" alt="Bali">
                    <div class="card-body">
                        <h5 class="card-title">Bali, Indonesia</h5>
                        <p class="card-text">Relax on beautiful beaches or explore lush jungles and temples.</p>
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

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Travel Planner</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/login.php" style="color: white;">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/register.php" style="color: white;">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Image -->
    <div class="hero-image">
        <img src="images/hero.jpg" alt="Travel Adventure" class="img-fluid w-100">
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <h1 class="display-4">Plan Your Next Adventure</h1>
                <p class="lead">Organize your trips, track your itineraries, and make your travel dreams a reality.</p>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="pages/dashboard.php" class="btn btn-primary btn-lg">View My Trips</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- About Section -->
        <div class="row mt-5 align-items-center">
            <div class="col-md-6">
                <img src="images/about.jpg" alt="About Travel Planner" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h2>About Travel Planner</h2>
                <p>Travel Planner helps you turn your dream journeys into real itineraries. From booking details to budget planning, we make it easy to keep everything in one place so you can focus on enjoying your adventures.</p>
            </div>
        </div>

        <!-- Destinations Section -->
        <div class="row mt-5 text-center">
            <h2 class="mb-4">Popular Destinations</h2>

            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/paris.jpg" class="card-img-top" alt="Paris">
                    <div class="card-body">
                        <h5 class="card-title">Paris, France</h5>
                        <p class="card-text">Experience the romance of the Eiffel Tower and world-class cuisine.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/tokyo.jpg" class="card-img-top" alt="Tokyo">
                    <div class="card-body">
                        <h5 class="card-title">Tokyo, Japan</h5>
                        <p class="card-text">Dive into a vibrant culture of technology, tradition, and tasty street food.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/bali.jpg" class="card-img-top" alt="Bali">
                    <div class="card-body">
                        <h5 class="card-title">Bali, Indonesia</h5>
                        <p class="card-text">Relax on beautiful beaches or explore lush jungles and temples.</p>
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
