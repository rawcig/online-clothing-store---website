<?php

// Display error message if exists
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger text-center">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']);
}

// Display success message if exists
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success text-center">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}

// Check if user is being redirected from checkout
$from_checkout = isset($_SESSION['redirect_after_login']) && strpos($_SESSION['redirect_after_login'], 'checkout') !== false;
?>

<!--Page Title-->
<div class="page section-header text-center">
<div class="page-title">
  <div class="wrapper">
    <h1 class="page-width">Login</h1>
  </div>
</div>
</div>
<!--End Page Title-->

<div class="container">
<div class="row">
  <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
    <div class="mb-4">
      <?php if ($from_checkout): ?>
        <div class="alert alert-info">
          <p><strong>Checkout Notice:</strong> You need to login to complete your purchase. Please login below or <a href="index.php?pages=register">create an account</a>.</p>
        </div>
      <?php endif; ?>
      
      <form method="post" action="auth/process_login.php" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
              <label for="CustomerEmail">Email <span class="required-f">*</span></label>
              <input type="email" name="email" placeholder="Enter your email" id="CustomerEmail" class=""
                autocorrect="off" autocapitalize="off" autofocus required>
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
              <label for="CustomerPassword">Password <span class="required-f">*</span></label>
              <input type="password" value="" name="password" placeholder="Enter your password" id="CustomerPassword"
                class="" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
            <input type="submit" class="btn mb-3" value="Sign In">
            <p class="mb-4">
              <a href="index.php?pages=404" id="RecoverPassword">Forgot your password?</a> &nbsp; | &nbsp;
              <a href="index.php?pages=register" id="customer_register_link">Create account</a>
            </p>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>