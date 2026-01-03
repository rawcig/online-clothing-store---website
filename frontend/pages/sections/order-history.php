<?php
session_start();

// Include session manager for authentication functions
require_once '../../includes/session-manager.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Store the intended page in session
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Set error message and redirect to login
    $_SESSION['error_message'] = 'Please login to view your order history.';
    header('Location: ../../frontend/index.php?pages=login');
    exit;
}

// Get current user information
$current_user = getCurrentUser();

// In a real implementation, you would fetch orders from database
// For now, we'll create sample data
$orders = [
    [
        'id' => 1001,
        'order_number' => 'ORD-20251015-ABC123',
        'date' => '2025-10-15',
        'status' => 'Delivered',
        'total' => 89.99,
        'items' => 3
    ],
    [
        'id' => 1002,
        'order_number' => 'ORD-20251010-DEF456',
        'date' => '2025-10-10',
        'status' => 'Processing',
        'total' => 125.50,
        'items' => 2
    ],
    [
        'id' => 1003,
        'order_number' => 'ORD-20251005-GHI789',
        'date' => '2025-10-05',
        'status' => 'Cancelled',
        'total' => 45.75,
        'items' => 1
    ]
];
?>

<!--Page Title-->
<div class="page section-header text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">Order History</h1>
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
            <h2>My Orders</h2>
          </div>
          <div class="col-md-6 text-md-right">
            <a href="index.php?pages=account" class="btn btn-secondary">Back to Account</a>
          </div>
        </div>
        
        <?php if (empty($orders)): ?>
          <div class="alert alert-info">
            <p>You haven't placed any orders yet.</p>
            <a href="index.php?pages=product" class="btn btn-primary">Start Shopping</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Order Number</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Items</th>
                  <th>Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                  <td><?= htmlspecialchars($order['order_number']) ?></td>
                  <td><?= htmlspecialchars($order['date']) ?></td>
                  <td>
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
                  </td>
                  <td><?= $order['items'] ?></td>
                  <td>$<?= number_format($order['total'], 2) ?></td>
                  <td>
                    <a href="index.php?pages=order-detail&id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">View</a>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
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