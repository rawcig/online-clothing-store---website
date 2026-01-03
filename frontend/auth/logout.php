<?php
// Include session manager (handles session_start)
require_once '../includes/session-manager.php';

// Include UserAuth class
require_once '../includes/classes/UserAuth.php';

// Include database connection
require_once '../includes/config/database.php';

// Initialize UserAuth
$auth = new UserAuth($conn);

// Logout the user
$auth->logout();

// Set success message
$_SESSION['success_message'] = 'You have been successfully logged out.';

// Redirect to home page
header('Location: ../index.php');
exit;
?>