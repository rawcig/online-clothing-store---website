<?php
// User account widget
// This file displays login/logout links based on user authentication status

// Make sure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- User Account Widget -->
<div class="user-account-widget col-2 col-sm-3 col-md-3 col-lg-2 center">
    <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
        <!-- Logged in user -->
        <div class="user-actions">
            <a href="index.php?pages=account" class="user-link" title="My Account">
                <i class="icon anm anm-user-al"></i>
                <span class="user-name"><?= htmlspecialchars($_SESSION['username'] ?? 'Account') ?></span>
            </a>
            <a href="auth/logout.php" class="logout-link" title="Logout" onclick="return confirm('Are you sure you want to logout?');">
                <i class="icon anm anm-sign-out"></i>
                <span class="logout-text">Logout</span>
            </a>
        </div>
    <?php else: ?>
        <!-- Guest user -->
        <div class="user-actions">
            <a href="index.php?pages=login" class="login-link" title="Login">
                <i class="icon anm anm-user-al"></i>
                <span class="login-text">Login</span>
            </a>
            <a href="index.php?pages=register" class="register-link" title="Register">
                <i class="icon anm anm-user-add"></i>
                <span class="register-text">Register</span>
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.user-account-widget {
    display: flex;
    align-items: center;
    /* justify-content: flex-end; */
    margin-right: 15px;
}

.user-actions {
    display: flex;
    align-items: center;
}

.user-link, .login-link, .logout-link, .register-link {
    color: #333;
    font-size: 18px;
    text-decoration: none;
    display: flex;
    align-items: center;
    margin-left: 15px;
    transition: color 0.3s ease;
}

.user-link:hover, .login-link:hover, .logout-link:hover, .register-link:hover {
    color: #9c9c9cff;
}

.user-name, .login-text, .logout-text, .register-text {
    margin-left: 5px;
    font-size: 14px;
}

@media (max-width: 767px) {
    .user-account-widget {
        margin-right: 10px;
    }
    
    .user-link, .login-link, .logout-link, .register-link {
        margin-left: 10px;
        font-size: 16px;
    }
    
    .user-name, .login-text, .logout-text, .register-text {
        display: none; /* Hide text on mobile */
    }
}
</style>