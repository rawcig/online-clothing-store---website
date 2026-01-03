<?php
// Include session manager (handles session_start and session management)
require_once '../../includes/session-manager.php';

// Include database connection
require_once '../config/database.php';

// Include UserAuth class
require_once 'UserAuth.php';

// Initialize UserAuth
$auth = new UserAuth($conn);

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    // Store the checkout page as redirect target
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Redirect to login page
    $_SESSION['error_message'] = 'Please login to complete your purchase.';
    header('Location: ../../index.php?pages=login');
    exit;
}

// Get user ID for logged-in user
$user_id = $auth->getCurrentUserId(); 

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Validate and sanitize input data
// Removed payment_method from required fields to allow bypass
$required_fields = ['firstname', 'lastname', 'telephone', 'address'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
    }
}

// Handle payment method - use default if not provided
if (empty($_POST['payment_method'])) {
    $_POST['payment_method'] = 'cod'; // Default to cash on delivery
}

// Validate email only if provided (since it's now optional)
$email = '';
if (!empty($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
}

if (!empty($errors)) {
    $_SESSION['checkout_errors'] = $errors;
    header('Location: ../../index.php?pages=checkout');
    exit;
}

// Sanitize input data
$firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$telephone = filter_var($_POST['telephone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$full_address = filter_var($_POST['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$order_notes = isset($_POST['order_notes']) ? filter_var($_POST['order_notes'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

// Payment method
$payment_method = $_POST['payment_method'] ?? 'cod'; // Default to Cash on Delivery

// No payment method validation - accept any payment method

try {
    $conn->begin_transaction();

    // Get cart items from session
    $cart = $_SESSION['cart'] ?? [];
    
    if (empty($cart)) {
        $errors[] = 'Your cart is empty';
        $_SESSION['checkout_errors'] = $errors;
        header('Location: ../../index.php?pages=checkout');
        exit;
    }

    // Calculate totals
    $subtotal = 0;
    $tax_amount = 0;
    $delivery_amount = 2.00; // Fixed delivery cost as shown in checkout.php
    $discount_amount = 0;
    
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    $total_amount = $subtotal + $delivery_amount - $discount_amount;

    // Generate unique order number
    $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());

    // Get user ID, using guest ID if not logged in
    $user_id = $auth->getCurrentUserId();
    if (!$user_id) {
        $user_id = $_SESSION['user_id'] ?? null;
    }
    // Use 0 for guest checkout or null if not set
    $user_id = $user_id ?? 0;

    // Insert into orders table - corrected parameter count
    $stmt = $conn->prepare("
        INSERT INTO orders 
        (user_id, order_number, status, subtotal, total_amount, currency, payment_method, payment_status, notes) 
        VALUES (?, ?, 'pending', ?, ?, 'USD', ?, 'pending', ?)
    ");
    
    $stmt->bind_param(
        'isddss',
        $user_id,
        $order_number,
        $subtotal,
        $total_amount,
        $payment_method,
        $order_notes
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Error creating order: ' . $conn->error);
    }
    
    $order_id = $conn->insert_id;

    // Create user address - handle guest users (user_id = 0) by allowing NULL
    // Parse the full address into components (simplified for this example)
    // In a production environment, you might want to use a more sophisticated address parser
    $address_components = explode(',', $full_address);
    $address_line1 = trim($address_components[0] ?? '');
    $city = trim($address_components[1] ?? '');
    $state = trim($address_components[2] ?? '');
    $postal_code = trim($address_components[3] ?? '');
    $country = trim($address_components[4] ?? '');
    
    // Insert address - simplified to match database schema
    $stmt_address = $conn->prepare("
        INSERT INTO user_addresses 
        (user_id, address_type, address_line1) 
        VALUES (?, 'both', ?)
    ");
    
    $stmt_address->bind_param(
        'is',
        $user_id,
        $full_address
    );
    
    if (!$stmt_address->execute()) {
        throw new Exception('Error saving address: ' . $conn->error);
    }
    
    $address_id = $conn->insert_id;

    // Update the order with address ID
    $stmt_update_order = $conn->prepare("UPDATE orders SET billing_address_id = ? WHERE id = ?");
    $stmt_update_order->bind_param('ii', $address_id, $order_id);
    $stmt_update_order->execute();

    // Insert order items
    $stmt_item = $conn->prepare("
        INSERT INTO order_items 
        (order_id, product_variant_id, product_name, product_price, quantity, total_price) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($cart as $item) {
        $item_total = $item['price'] * $item['quantity'];
        
        // Using the cart item id as product_variant_id (this should be updated to actual variant id)
        // The cart item 'id' field represents the product_variant_id in the system
        $stmt_item->bind_param(
            'iisidd',
            $order_id,
            $item['id'], // This represents the product_variant_id
            $item['name'],
            $item['price'],
            $item['quantity'],
            $item_total
        );
        
        if (!$stmt_item->execute()) {
            throw new Exception('Error creating order item: ' . $conn->error);
        }
    }

    // Clear the cart
    unset($_SESSION['cart']);

    $conn->commit();

    // Set success message
    $_SESSION['order_success'] = [
        'order_number' => $order_number,
        'total_amount' => $total_amount
    ];

    // Redirect to order confirmation page
    header('Location: ../../index.php?pages=order-confirmation');
    exit;

} catch (Exception $e) {
    $conn->rollback();
    error_log('Checkout Error: ' . $e->getMessage());
    error_log('Checkout Error Trace: ' . $e->getTraceAsString());
    
    // Provide more specific error information for debugging
    $errors[] = 'An error occurred while processing your order: ' . $e->getMessage();
    $_SESSION['checkout_errors'] = $errors;
    
    header('Location: ../../index.php?pages=checkout');
    exit;
}


