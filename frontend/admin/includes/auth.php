<?php
session_start();

// Simple authentication check
if (!function_exists('isAuthenticated')) {
    function isAuthenticated() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
}

// Login function
if (!function_exists('login')) {
    function login($username, $password) {
        // Check against hardcoded credentials (admin/admin123)
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['login_time'] = time();
            return true;
        }
        return false;
    }
}

// Logout function
if (!function_exists('logout')) {
    function logout() {
        session_destroy();
        session_start(); // Restart session
    }
}

// Check if user is trying to log in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        // Redirect to product management page after successful login
        header('Location: index.php?pages=product-management');
        exit();
    } else {
        $login_error = "Invalid credentials. Please use username: admin and password: admin123";
    }
}

// Protect admin pages
if (preg_match('/pages=(?!master)/', $_SERVER['QUERY_STRING']) && !isAuthenticated()) {
    // If user is not authenticated and trying to access a protected page, redirect to main page
    header('Location: index.php?pages=main');
    exit();
}
?>