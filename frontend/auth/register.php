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
?>

<div class="page section-header text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">Create an Account</h1>
    </div>
  </div>
</div>
<!--End Page Title-->

<div class="container">
  <div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
      <div class="mb-4">
        <form method="post" action="auth/process_register.php" id="CustomerRegisterForm" accept-charset="UTF-8" class="contact-form">
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="FirstName">First Name <span class="required-f">*</span></label>
                <input type="text" name="first_name" placeholder="Enter your first name" id="FirstName" class="form-control"
                  autofocus required>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="LastName">Last Name <span class="required-f">*</span></label>
                <input type="text" name="last_name" placeholder="Enter your last name" id="LastName" class="form-control" required>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="CustomerUsername">Username <span class="required-f">*</span></label>
                <input type="text" name="username" placeholder="Choose a username" id="CustomerUsername" class="form-control"
                  autocorrect="off" autocapitalize="off" required>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="CustomerEmail">Email <span class="required-f">*</span></label>
                <input type="email" name="email" placeholder="Enter your email" id="CustomerEmail" class="form-control"
                  autocorrect="off" autocapitalize="off" required>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="CustomerPassword">Password <span class="required-f">*</span></label>
                <input type="password" value="" name="password" placeholder="Create a password (min. 6 characters)" id="CustomerPassword"
                  class="form-control" minlength="6" required>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="ConfirmPassword">Confirm Password <span class="required-f">*</span></label>
                <input type="password" value="" name="confirm_password" placeholder="Confirm your password" id="ConfirmPassword"
                  class="form-control" minlength="6" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
              <input type="submit" class="btn mb-3" value="Create Account">
              <p class="mb-4">
                Already have an account? <a href="index.php?pages=login" id="customer_login_link">Sign in</a>
              </p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>