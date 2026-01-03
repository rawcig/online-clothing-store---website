<?php
// session_start();

// Include session manager for authentication functions
require_once 'includes/session-manager.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Store the intended page in session
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Set error message and redirect to login
    $_SESSION['error_message'] = 'Please login to edit your account.';
    header('Location: ../../frontend/index.php?pages=login');
    exit;
}

// Get current user information
$current_user = getCurrentUser();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Validate email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = 'Invalid email format';
        header('Location: ../../index.php?pages=account-edit');
        exit;
    }
    
    // In a real implementation, you would update the database here
    // For now, we'll just set success message
    $_SESSION['success_message'] = 'Account information updated successfully';
    header('Location: ../../index.php?pages=account');
    exit;
}
?>

<!--Page Title-->
<div class="page section-header text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">Edit Account</h1>
    </div>
  </div>
</div>
<!--End Page Title-->

<div class="container">
  <div class="row">
    <div class="col-12 col-sm-12 col-md-8 col-lg-6 main-col offset-md-2 offset-lg-3">
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
        <form method="post" action="" id="EditAccountForm" accept-charset="UTF-8" class="contact-form">
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="FirstName">First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($current_user['first_name'] ?? '') ?>" placeholder="First Name" id="FirstName" class="form-control">
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="LastName">Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($current_user['last_name'] ?? '') ?>" placeholder="Last Name" id="LastName" class="form-control">
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="CustomerEmail">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($current_user['email'] ?? '') ?>" placeholder="Email" id="CustomerEmail" class="form-control"
                  autocorrect="off" autocapitalize="off" autofocus>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="CustomerPhone">Phone</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($current_user['phone'] ?? '') ?>" placeholder="Phone" id="CustomerPhone" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
              <input type="submit" class="btn mb-3" value="Update Account">
              <a href="index.php?pages=account" class="btn btn-secondary mb-3">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>