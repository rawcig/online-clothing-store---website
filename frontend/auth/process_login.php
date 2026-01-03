<?php
// Include session manager (handles session_start)
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

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Attempt to login
    $result = $auth->login($email, $password);
    
    if ($result['success']) {
        // Login successful
        
        // Set success message
        $_SESSION['success_message'] = 'Login successful. Welcome back!';
        
        // Redirect to intended page or account page
        $redirect_url = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '../../frontend/index.php?pages=account';
        unset($_SESSION['redirect_after_login']);
        
        header("Location: $redirect_url");
        exit;
    } else {
        // Login failed, set error message
        $_SESSION['error_message'] = $result['message'];
        header('Location: ../../frontend/index.php?pages=login');
        exit;
    }
} else {
    // Not a POST request, redirect to login page
    header('Location: ../../frontend/index.php?pages=login');
    exit;
}
?>