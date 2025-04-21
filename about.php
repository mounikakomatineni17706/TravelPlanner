<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Travel Planner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-image: url('images/bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Optional: Keeps the background fixed during scrolling */
        }
    </style>
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
                    <li class="nav-item">
                        <a class="nav-link" href="about.php" style="color: white;">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php" style="color: white;">Contact</a>
                    </li>
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
                <h1 class="display-4" style="color: white;">About Us</h1>
                <p class="lead" style="color: white;" >Get to know more about Travel Planner and how we can help you plan your next adventure.</p>
            </div>
        </div>

        <!-- About Section -->
        <div class="row mt-5 align-items-center">
            <div class="col-md-6">
                <img src="images/about.jpg" alt="About Travel Planner" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h2 style="color: white;">Our Mission</h2>
                <p style="color: white;">At Travel Planner, we are committed to helping travelers organize their trips and make their travel dreams a reality. From detailed itineraries to budget management, we offer an all-in-one platform to make planning simple and fun.</p>
                <h3 style="color: white;">Why Choose Us?</h3>
                <ul>
                    <li style="color: white;">Easy-to-use trip planner</li>
                    <li style="color: white;">Track your itineraries and bookings</li>
                    <li style="color: white;">Connect with like-minded travelers</li>
                    <li style="color: white;">Access to exclusive deals on popular destinations</li>
                </ul>
            </div>
        </div>

        <!-- Our Team Section -->
        <div class="row mt-5 text-center">
            <h2 class="mb-4" style="color: white;">Meet Our Team</h2>

            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/team1.jpg" class="card-img-top" alt="Team Member 1">
                    <div class="card-body">
                        <h5 class="card-title">John Doe</h5>
                        <p class="card-text">Founder & CEO - Passionate about travel and helping others experience the world.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/team2.jpg" class="card-img-top" alt="Team Member 2">
                    <div class="card-body">
                        <h5 class="card-title">Jane Smith</h5>
                        <p class="card-text">Co-Founder & CTO - Focused on creating a seamless user experience for travelers.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/team3.jpg" class="card-img-top" alt="Team Member 3">
                    <div class="card-body">
                        <h5 class="card-title">Alice Johnson</h5>
                        <p class="card-text">Marketing Lead - Ensures everyone knows about Travel Planner's awesome features.</p>
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

