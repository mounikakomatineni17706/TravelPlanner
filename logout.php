<?php
session_start();

// Unset all session variables
$_SESSION = [];

// If a session cookie is used, destroy it
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Remove remember-me cookie if it exists
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to the homepage (replace with your homepage URL if needed)
header("Location: index.php");  // or any other path for your homepage
exit;
?>
