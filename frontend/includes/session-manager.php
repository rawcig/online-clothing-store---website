<?php
// Centralized Session Management

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Set session parameters for security
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS'])); // Use secure cookies if HTTPS is available
    ini_set('session.cookie_samesite', 'Lax'); // CSRF protection

    // Set session save path
    $savePath = __DIR__ . '/../tmp';
    if (!is_dir($savePath)) {
        mkdir($savePath, 0777, true);
    }
    ini_set('session.save_path', $savePath);

    session_start();
    
    // Regenerate session ID periodically to prevent session fixation
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) { // 30 minutes
        // Change session ID every 30 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
    
    // Initialize CSRF token if not exists
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

/**
 * Function to check if user is logged in
 * @return bool Whether user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Function to get current user ID
 * @return int|null Current user ID or null if not logged in
 */
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Function to get current user information
 * @return array|null Current user data or null if not logged in
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    // This function would typically fetch user data from database
    // For now, we'll return basic session data
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'email' => $_SESSION['email'] ?? ''
    ];
}

/**
 * Function to require login for certain pages
 * @param string $redirect_page Page to redirect to after login
 * @return void
 */
function requireLogin($redirect_page = null) {
    if (!isLoggedIn()) {
        // Store the intended page in session
        if ($redirect_page) {
            $_SESSION['redirect_after_login'] = $redirect_page;
        } else {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        }
        
        // Redirect to login page
        header('Location: ../index.php?pages=login');
        exit;
    }
}

/**
 * Function to get total cart items (sum of quantities)
 * @return int Total number of items in cart
 */
function getCartItemCount() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        return 0;
    }
    
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += (int)$item['quantity'];
    }
    
    return $count;
}

/**
 * Function to get number of unique cart items
 * @return int Number of unique products in cart
 */
function getCartUniqueItemsCount() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        return 0;
    }
    
    return count($_SESSION['cart']);
}

/**
 * Function to get cart total
 * @return float Total price of items in cart
 */
function getCartTotal() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        return 0;
    }
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    
    return $total;
}

/**
 * Sanitize cart data
 * @param array $cart Cart data to sanitize
 * @return array Sanitized cart data
 */
function sanitizeCart($cart) {
    if (!is_array($cart)) {
        return [];
    }
    
    $sanitizedCart = [];
    foreach ($cart as $key => $item) {
        if (!is_array($item)) {
            continue;
        }
        
        $sanitizedCart[$key] = [
            'id' => (int)($item['id'] ?? 0),
            'name' => filter_var($item['name'] ?? '', FILTER_SANITIZE_STRING),
            'price' => floatval($item['price'] ?? 0),
            'color' => filter_var($item['color'] ?? '', FILTER_SANITIZE_STRING),
            'size' => filter_var($item['size'] ?? '', FILTER_SANITIZE_STRING),
            'quantity' => max(1, (int)($item['quantity'] ?? 1)),
            'img' => filter_var($item['img'] ?? '', FILTER_SANITIZE_URL)
        ];
    }
    
    return $sanitizedCart;
}

/**
 * Validate cart item data
 * @param array $item Item data to validate
 * @return bool Whether item data is valid
 */
function validateCartItem($item) {
    if (!is_array($item)) {
        return false;
    }
    
    $requiredFields = ['id', 'name', 'price'];
    foreach ($requiredFields as $field) {
        if (empty($item[$field])) {
            return false;
        }
    }
    
    if ($item['id'] <= 0 || $item['price'] <= 0) {
        return false;
    }
    
    if ($item['quantity'] <= 0) {
        $item['quantity'] = 1;
    }
    
    return true;
}
?>