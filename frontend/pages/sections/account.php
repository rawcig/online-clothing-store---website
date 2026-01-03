<?php
// session_start();

// Include session manager for authentication functions
// require_once '/includes/session-manager.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Store the intended page in session
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Set error message and redirect to login
    $_SESSION['error_message'] = 'Please login to view your account.';
    header('Location: ../../frontend/index.php?pages=login');
    exit;
}

// Get current user information
$current_user = getCurrentUser();
?>

<!--Page Title-->
<div class="page section-header text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">My Account</h1>
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
          <?php echo htmlspecialchars($_SESSION['success_message']); ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
      <?php endif; ?>
      
      <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
          <?php echo htmlspecialchars($_SESSION['error_message']); ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>
      
      <div class="dashboard">
        <div class="mb-4">
          <h2>Welcome, <?php echo htmlspecialchars($current_user['username'] ?? ''); ?>!</h2>
          <p>Email: <?php echo htmlspecialchars($current_user['email'] ?? ''); ?></p>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-header">
                <h3>Account Information</h3>
              </div>
              <div class="card-body">
                <p><strong>Member since:</strong> <?php echo date('F j, Y', strtotime($current_user['created_at'] ?? 'now')); ?></p>
                <p><strong>Last login:</strong> Just now</p>
                <a href="index.php?pages=account-edit" class="btn btn-primary">Edit Account</a>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-header">
                <h3>Order History</h3>
              </div>
              <div class="card-body">
                <p>You haven't placed any orders yet.</p>
                <a href="index.php?pages=product" class="btn btn-primary">Start Shopping</a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-header">
                <h3>Wishlist</h3>
              </div>
              <div class="card-body">
                <p>You don't have any items in your wishlist.</p>
                <a href="index.php?pages=product" class="btn btn-primary">Browse Products</a>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-header">
                <h3>Addresses</h3>
              </div>
              <div class="card-body">
                <p>You haven't saved any addresses yet.</p>
                <a href="index.php?pages=address-book" class="btn btn-primary">Manage Addresses</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>