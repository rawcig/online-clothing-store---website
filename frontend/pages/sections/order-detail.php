<?php
session_start();

// Include session manager for authentication functions
require_once '../../includes/session-manager.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Store the intended page in session
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Set error message and redirect to login
    $_SESSION['error_message'] = 'Please login to view order details.';
    header('Location: ../../frontend/index.php?pages=login');
    exit;
}

// Get current user information
$current_user = getCurrentUser();

// Get order ID from URL parameter
$order_id = (int)($_GET['id'] ?? 0);

// In a real implementation, you would fetch order details from database
// For now, we'll create sample data
$order = [
    'id' => $order_id,
    'order_number' => 'ORD-20251015-ABC123',
    'date' => '2025-10-15',
    'status' => 'Delivered',
    'subtotal' => 79.99,
    'shipping' => 10.00,
    'total' => 89.99,
    'payment_method' => 'Credit Card',
    'billing_address' => [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address' => '123 Main St',
        'city' => 'Phnom Penh',
        'state' => 'PP',
        'postal_code' => '12345',
        'country' => 'Cambodia'
    ],
    'shipping_address' => [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address' => '123 Main St',
        'city' => 'Phnom Penh',
        'state' => 'PP',
        'postal_code' => '12345',
        'country' => 'Cambodia'
    ]
];

// Sample order items
$order_items = [
    [
        'id' => 1,
        'name' => 'Regular T-Shirts With Printed',
        'price' => 29.99,
        'quantity' => 2,
        'subtotal' => 59.98,
        'size' => 'M',
        'color' => 'Black'
    ],
    [
        'id' => 2,
        'name' => 'Regular Hoodie',
        'price' => 19.99,
        'quantity' => 1,
        'subtotal' => 19.99,
        'size' => 'L',
        'color' => 'Gray'
    ]
];

// If order ID is invalid, redirect to order history
if ($order_id <= 0) {
    header('Location: index.php?pages=order-history');
    exit;
}
?>

<!--Page Title-->
<div class="page section-header text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">Order Details</h1>
    </div>
  </div>
</div>
<!--End Page Title-->

<div class="container">
  <div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col">
      <!-- Success/Error Messages -->
      <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
          <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
      <?php endif; ?>
      
      <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
          <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>
      
      <div class="mb-4">
        <div class="row mb-4">
          <div class="col-md-6">
            <h2>Order #<?= htmlspecialchars($order['order_number']) ?></h2>
            <p>Placed on <?= htmlspecialchars($order['date']) ?></p>
          </div>
          <div class="col-md-6 text-md-right">
            <a href="index.php?pages=order-history" class="btn btn-secondary">Back to Orders</a>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-8">
            <div class="card mb-4">
              <div class="card-header">
                <h3>Order Items</h3>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($order_items as $item): ?>
                      <tr>
                        <td>
                          <div><?= htmlspecialchars($item['name']) ?></div>
                          <div class="small text-muted">
                            Size: <?= htmlspecialchars($item['size']) ?>, 
                            Color: <?= htmlspecialchars($item['color']) ?>
                          </div>
                        </td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($item['subtotal'], 2) ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-header">
                <h3>Order Summary</h3>
              </div>
              <div class="card-body">
                <table class="table table-borderless">
                  <tr>
                    <td class="text-left">Subtotal:</td>
                    <td class="text-right">$<?= number_format($order['subtotal'], 2) ?></td>
                  </tr>
                  <tr>
                    <td class="text-left">Shipping:</td>
                    <td class="text-right">$<?= number_format($order['shipping'], 2) ?></td>
                  </tr>
                  <tr class="font-weight-bold">
                    <td class="text-left">Total:</td>
                    <td class="text-right">$<?= number_format($order['total'], 2) ?></td>
                  </tr>
                </table>
                
                <div class="mt-3">
                  <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                  <p>
                    <strong>Status:</strong>
                    <?php
                    $status_class = '';
                    switch ($order['status']) {
                        case 'Delivered':
                            $status_class = 'badge-success';
                            break;
                        case 'Processing':
                            $status_class = 'badge-warning';
                            break;
                        case 'Cancelled':
                            $status_class = 'badge-danger';
                            break;
                        default:
                            $status_class = 'badge-secondary';
                    }
                    ?>
                    <span class="badge <?= $status_class ?>"><?= htmlspecialchars($order['status']) ?></span>
                  </p>
                </div>
              </div>
            </div>
            
            <div class="card mb-4">
              <div class="card-header">
                <h3>Billing Address</h3>
              </div>
              <div class="card-body">
                <address>
                  <?= htmlspecialchars($order['billing_address']['first_name']) ?> <?= htmlspecialchars($order['billing_address']['last_name']) ?><br>
                  <?= htmlspecialchars($order['billing_address']['address']) ?><br>
                  <?= htmlspecialchars($order['billing_address']['city']) ?>, <?= htmlspecialchars($order['billing_address']['state']) ?> <?= htmlspecialchars($order['billing_address']['postal_code']) ?><br>
                  <?= htmlspecialchars($order['billing_address']['country']) ?>
                </address>
              </div>
            </div>
            
            <div class="card">
              <div class="card-header">
                <h3>Shipping Address</h3>
              </div>
              <div class="card-body">
                <address>
                  <?= htmlspecialchars($order['shipping_address']['first_name']) ?> <?= htmlspecialchars($order['shipping_address']['last_name']) ?><br>
                  <?= htmlspecialchars($order['shipping_address']['address']) ?><br>
                  <?= htmlspecialchars($order['shipping_address']['city']) ?>, <?= htmlspecialchars($order['shipping_address']['state']) ?> <?= htmlspecialchars($order['shipping_address']['postal_code']) ?><br>
                  <?= htmlspecialchars($order['shipping_address']['country']) ?>
                </address>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.badge-success {
    background-color: #28a745;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-danger {
    background-color: #dc3545;
}

.badge-secondary {
    background-color: #6c757d;
}
</style>