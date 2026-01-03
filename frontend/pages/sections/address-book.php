<?php
session_start();

// Include session manager for authentication functions
require_once '../../includes/session-manager.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Store the intended page in session
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Set error message and redirect to login
    $_SESSION['error_message'] = 'Please login to manage your addresses.';
    header('Location: ../../frontend/index.php?pages=login');
    exit;
}

// Get current user information
$current_user = getCurrentUser();

// In a real implementation, you would fetch addresses from database
// For now, we'll create sample data
$addresses = [
    [
        'id' => 1,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address' => '123 Main St, Phnom Penh, PP 12345',
        'is_default' => true,
        'type' => 'Both'
    ],
    [
        'id' => 2,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'address' => '456 Second Ave, Siem Reap, SR 54321',
        'is_default' => false,
        'type' => 'Shipping'
    ]
];
?>

<!--Page Title-->
<div class="page.section.header.text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">Address Book</h1>
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
            <h2>My Addresses</h2>
          </div>
          <div class="col-md-6 text-md-right">
            <a href="index.php?pages=account" class="btn btn-secondary">Back to Account</a>
            <a href="index.php?pages=address-edit" class="btn btn-primary">Add New Address</a>
          </div>
        </div>
        
        <?php if (empty($addresses)): ?>
          <div class="alert alert-info">
            <p>You haven't saved any addresses yet.</p>
            <a href="index.php?pages=address-edit" class="btn btn-primary">Add New Address</a>
          </div>
        <?php else: ?>
          <div class="row">
            <?php foreach ($addresses as $address): ?>
            <div class="col-md-6 mb-4">
              <div class="card">
                <div class="card-header">
                  <div class="d-flex justify-content-between align-items-center">
                    <h3>
                      <?= htmlspecialchars($address['first_name']) ?> <?= htmlspecialchars($address['last_name']) ?>
                      <?php if ($address['is_default']): ?>
                        <span class="badge badge-primary">Default</span>
                      <?php endif; ?>
                    </h3>
                    <span class="badge badge-secondary"><?= htmlspecialchars($address['type']) ?></span>
                  </div>
                </div>
                <div class="card-body">
                  <p><?= htmlspecialchars($address['address']) ?></p>
                  <div class="mt-3">
                    <a href="index.php?pages=address-edit&id=<?= $address['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                    <?php if (!$address['is_default']): ?>
                      <a href="index.php?pages=address-set-default&id=<?= $address['id'] ?>" class="btn btn-success btn-sm">Set as Default</a>
                    <?php endif; ?>
                    <a href="index.php?pages=address-delete&id=<?= $address['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this address?');">Delete</a>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<style>
.badge-primary {
    background-color: #007bff;
}

.badge-secondary {
    background-color: #6c757d;
}
</style>