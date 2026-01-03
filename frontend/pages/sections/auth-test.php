<?php
session_start();

// Include UserAuth class
require_once '../../includes/classes/UserAuth.php';

// Include database connection
require_once '../../includes/config/database.php';

// Initialize UserAuth
$auth = new UserAuth($conn);

// Test if user is logged in
$is_logged_in = $auth->isLoggedIn();
$current_user = $auth->getCurrentUser();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Authentication Test</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Authentication Test Page</h1>
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Authentication Status</h5>
                        
                        <?php if ($is_logged_in): ?>
                            <div class="alert alert-success">
                                <p>User is logged in</p>
                                <p>User ID: <?= htmlspecialchars($current_user['id'] ?? 'N/A') ?></p>
                                <p>Username: <?= htmlspecialchars($current_user['username'] ?? 'N/A') ?></p>
                                <p>Email: <?= htmlspecialchars($current_user['email'] ?? 'N/A') ?></p>
                                <p>Name: <?= htmlspecialchars(($current_user['first_name'] ?? '') . ' ' . ($current_user['last_name'] ?? '')) ?></p>
                            </div>
                            
                            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <p>User is not logged in</p>
                            </div>
                            
                            <a href="../auth/login.php" class="btn btn-primary">Login</a>
                            <a href="../auth/register.php" class="btn btn-secondary">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>