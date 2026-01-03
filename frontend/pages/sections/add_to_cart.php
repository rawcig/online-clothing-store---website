<?php
// Turn off error display to prevent HTML output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Start output buffering to prevent any accidental output
ob_start();

// Minimal session handling
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.save_path', __DIR__ . '/../../tmp');
    if (!is_dir(__DIR__ . '/../../tmp')) {
        mkdir(__DIR__ . '/../../tmp', 0777, true);
    }
    session_start();
}

// Capture any output that might have been sent and clean it
ob_end_clean();

// Database connection (minimal version to avoid conflicts)
$host = "localhost";
$user = "root";
$password = "";
$dbname = "clothing_store";

// Create connection without using the shared file to avoid conflicts
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error', 
        'message' => 'Database connection failed'
    ]);
    exit;
}

// Process cart operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add'; // Default to add if no action specified
    
    // Verify CSRF token
    if (!isset($_POST['token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['token'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request'
        ]);
        exit;
    }

    switch ($action) {
        case 'remove':
            // Remove item from cart
            $index = (int)($_POST['id'] ?? -1);
            
            if (!isset($_SESSION['cart'][$index])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Item not found in cart'
                ]);
                exit;
            }
            
            // Remove the item at the specified index
            unset($_SESSION['cart'][$index]);
            // Re-index the array after removal
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            
            // Calculate updated cart count
            $totalItems = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalItems += $item['quantity'];
            }
            
            // Make sure no output has been sent before JSON
            if (ob_get_level()) {
                ob_clean();
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Item removed from cart',
                'cartCount' => $totalItems,  // Total quantity of items in cart
                'cartItems' => count($_SESSION['cart'])  // Number of different products in cart
            ]);
            exit;
            
        case 'add':
        default:
            // Sanitize and validate input for adding item
            $id       = (int)($_POST['id'] ?? 0);
            $name     = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
            $price    = floatval($_POST['price'] ?? 0);
            $color    = filter_var($_POST['color'] ?? '', FILTER_SANITIZE_STRING);
            $size     = filter_var($_POST['size'] ?? '', FILTER_SANITIZE_STRING);
            $quantity = max(1, (int)($_POST['quantity'] ?? 1)); // Minimum quantity is 1
            $img      = filter_var($_POST['img'] ?? '', FILTER_SANITIZE_URL);

            // Debug: Log received data
            error_log("Add to cart - ID: $id, Name: $name, Price: $price, Color: '$color', Size: '$size', Qty: $quantity, Img: $img");
            error_log("Raw POST data: " . print_r($_POST, true));

            // Validate required fields
            if ($id <= 0 || empty($name) || $price <= 0) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid product data'
                ]);
                exit;
            }

            // Initialize cart if not exists
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Check if item already exists in cart based on product ID, color, and size
            $item_found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $id && $item['color'] == $color && $item['size'] == $size) {
                    $item['quantity'] += $quantity;
                    $item_found = true;
                    break;
                }
            }
            unset($item); // Break reference

            // Add new item if not found
            if (!$item_found) {
                $_SESSION['cart'][] = [
                    'id' => $id,
                    'name' => $name,
                    'price' => $price,
                    'color' => $color,
                    'size' => $size,
                    'quantity' => $quantity,
                    'img' => $img
                ];
                
                // Debug: Log added item
                error_log("Added new item to cart - Color: '$color', Size: '$size'");
            }
            
            // Debug: Log entire cart
            error_log("Cart contents: " . print_r($_SESSION['cart'], true));

            // Calculate updated cart count (sum of all quantities)
            $totalItems = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalItems += $item['quantity'];
            }

            // Make sure no output has been sent before JSON
            if (ob_get_level()) {
                ob_clean();
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Product added to cart',
                'cartCount' => $totalItems,  // Total quantity of items in cart
                'cartItems' => count($_SESSION['cart'])  // Number of different products in cart
            ]);
            exit;
    }
} else {
    // Not a POST request
    if (ob_get_level()) {
        ob_clean();
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}
?>