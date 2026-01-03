<?php
// session_start();

// Include session manager for authentication functions
// require_once '../../includes/session-manager.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Store the intended page in session
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Set error message and redirect to login
    $_SESSION['error_message'] = 'Please login to view your order confirmation.';
    header('Location: ../../frontend/index.php?pages=login');
    exit;
}

// Check if order success data exists
if (!isset($_SESSION['order_success'])) {
    // Redirect to home if no order success data
    header('Location: ../index.php');
    exit;
}

// Get order success data
$order_data = $_SESSION['order_success'];
$order_number = htmlspecialchars($order_data['order_number']);
$total_amount = htmlspecialchars($order_data['total_amount']);

// Clear the order success message from session
unset($_SESSION['order_success']);
?>

<!--Page Title-->
<div class="page section-header text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">Order Confirmation</h1>
    </div>
  </div>
</div>
<!--End Page Title-->

<div class="container">
  <div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col">
      <!-- Success Message -->
      <div class="alert alert-success text-center">
        <h2 class="mb-3"><i class="icon anm anm-check-circle"></i> Thank You for Your Order!</h2>
        <p class="mb-4">Your order has been placed successfully.</p>
        
        <div class="order-details bg-light p-4 rounded">
          <h3 class="mb-3">Order Details</h3>
          <div class="row">
            <div class="col-md-6">
              <p><strong>Order Number:</strong> <?php echo $order_number; ?></p>
            </div>
            <div class="col-md-6">
              <p><strong>Total Amount:</strong> $<?php echo number_format($total_amount, 2); ?></p>
            </div>
          </div>
        </div>
        
        <div class="mt-4">
          <p class="mb-3">We have sent a confirmation email with order details to your email address.</p>
          <a href="../../index.php?pages=account" class="btn btn-primary">View Order History</a>
          <a href="../../index.php?pages=product" class="btn btn-secondary">Continue Shopping</a>
        </div>
      </div>
      
      <!-- Order Summary -->
      <div class="row mt-5">
        <div class="col-12">
          <div class="your-order-payment">
            <div class="your-order">
              <h2 class="order-title mb-4">What happens next?</h2>
              
              <div class="table-responsive-sm order-table">
                <table class="bg-white table table-bordered table-hover text-center">
                  <thead>
                    <tr>
                      <th class="text-left">Step</th>
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><strong>1. Order Confirmation</strong></td>
                      <td>We've received your order and sent a confirmation email.</td>
                    </tr>
                    <tr>
                      <td class="text-left"><strong>2. Processing</strong></td>
                      <td>We're preparing your order for shipment.</td>
                    </tr>
                    <tr>
                      <td class="text-left"><strong>3. Shipment</strong></td>
                      <td>Your order will be shipped and you'll receive tracking information.</td>
                    </tr>
                    <tr>
                      <td class="text-left"><strong>4. Delivery</strong></td>
                      <td>Your order will be delivered to your address.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <hr />
            
            <div class="your-payment">
              <h2 class="payment-title mb-3">Need Help?</h2>
              <div class="payment-method">
                <div class="payment-accordion">
                  <div id="accordion" class="payment-section">
                    <div class="card mb-2">
                      <div class="card-header">
                        <a class="card-link" data-toggle="collapse" href="#collapseOne">Contact Us</a>
                      </div>
                      <div id="collapseOne" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                          <p class="no-margin font-15">If you have any questions about your order, please contact our customer service team:</p>
                          <ul class="list-unstyled mt-3">
                            <li><strong>Email:</strong> support@example.com</li>
                            <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                            <li><strong>Hours:</strong> Monday-Friday, 9AM-5PM</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="card mb-2">
                      <div class="card-header">
                        <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">Return Policy</a>
                      </div>
                      <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                          <p class="no-margin font-15">We offer a 30-day return policy. If you're not satisfied with your purchase, you can return it for a refund or exchange.</p>
                          <p class="mt-3"><strong>Note:</strong> Items must be in new, unused condition with all original packaging.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>