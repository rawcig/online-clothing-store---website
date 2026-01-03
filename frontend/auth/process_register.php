<?php
// Include session manager
require_once '../includes/session-manager.php';

// Include database connection
require_once '../includes/config/database.php';

// Include UserAuth class
require_once '../includes/classes/UserAuth.php';

// Initialize UserAuth
$auth = new UserAuth($conn);

// Check if user is already logged in
if ($auth->isLoggedIn()) {
    header('Location: ../../frontend/index.php');
    exit;
}

// Process registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = 'Passwords do not match';
        header('Location: ../../frontend/index.php?pages=register');
        exit;
    }
    
    // Validate password strength (at least 6 characters)
    if (strlen($password) < 6) {
        $_SESSION['error_message'] = 'Password must be at least 6 characters long';
        header('Location: ../../frontend/index.php?pages=register');
        exit;
    }
    
    // Attempt to register
    $result = $auth->register($username, $email, $password, $first_name, $last_name);
    
    if ($result['success']) {
        // Registration successful
        $_SESSION['success_message'] = 'Registration successful. You can now login.';
        header('Location: ../../frontend/index.php?pages=login');
        exit;
    } else {
        // Registration failed, set error message
        $_SESSION['error_message'] = $result['message'];
        header('Location: ../../frontend/index.php?pages=register');
        exit;
    }
} else {
    // Not a POST request, redirect to registration page
    header('Location: ../../frontend/index.php?pages=register');
    exit;
}
?>