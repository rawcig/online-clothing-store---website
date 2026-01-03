<?php
// Initialize cart and calculate totals
// Session is already started in index.php, so we don't call session_start() here

// Include session manager for authentication functions
require_once 'includes/session-manager.php';

// Get cart from session
$cart = $_SESSION['cart'] ?? [];

// Calculate totals
$subtotal = 0;
$delivery_amount = 2.00;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal + $delivery_amount;

// Check for errors from checkout process
$errors = $_SESSION['checkout_errors'] ?? [];
if (!empty($errors)) {
    echo '<div class="alert alert-danger">';
    foreach ($errors as $error) {
        echo '<p>' . htmlspecialchars($error) . '</p>';
    }
    echo '</div>';
    unset($_SESSION['checkout_errors']);
}

// Check if user is logged in
$is_logged_in = isLoggedIn();
?>

<!--Page Title-->
<div class="page section-header text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">Checkout</h1>
    </div>
  </div>
</div>
<!--End Page Title-->

<?php if (!$is_logged_in): ?>
<!-- Login Required Message -->
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="alert alert-warning text-center">
        <h3>Login Required</h3>
        <p>Please login to complete your purchase.</p>
        <a href="index.php?pages=login" class="btn btn-primary">Login Now</a>
        <p class="mt-3">Don't have an account? <a href="index.php?pages=register">Register here</a></p>
      </div>
    </div>
  </div>
</div>
<!-- End Login Required Message -->

<?php else: ?>
<!-- Checkout Form for Logged In Users -->
<div class="container">
  <div class="row">
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
      <div class="customer-box returning-customer">
        <h3><i class="icon anm anm-user-al"></i> Returning customer? <a href="#customer-login" id="customer"
            class="text-white text-decoration-underline" data-toggle="collapse">Click here to login</a></h3>
        <div id="customer-login" class="collapse customer-content">
          <div class="customer-info">
            <p class="coupon-text">If you have shopped with us before, please enter your details in the boxes
              below. If you are a new customer, please proceed to the Billing &amp; Delivery section.</p>
            <form>
              <div class="row">
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                  <label for="exampleInputEmail1">Email address <span class="required-f">*</span></label>
                  <input type="email" class="no-margin" id="exampleInputEmail1">
                </div>
                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                  <label for="exampleInputPassword1">Password <span class="required-f">*</span></label>
                  <input type="password" id="exampleInputPassword1">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-check width-100 margin-20px-bottom">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" value=""> Remember me!
                    </label>
                    <a href="#" class="float-right">Forgot your password?</a>
                  </div>
                  <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
      <div class="customer-box customer-coupon">
        <h3 class="font-15 xs-font-13"><i class="icon anm anm-gift-l"></i> Have a coupon? <a href="#have-coupon"
            class="text-white text-decoration-underline" data-toggle="collapse">Click here to enter your code</a>
        </h3>
        <div id="have-coupon" class="collapse coupon-checkout-content">
          <div class="discount-coupon">
            <div id="coupon" class="coupon-dec tab-pane active">
              <p class="margin-10px-bottom">Enter your coupon code if you have one.</p>
              <label class="required get" for="coupon-code"><span class="required-f">*</span> Coupon</label>
              <input id="coupon-code" name="coupon_code" required="" type="text" class="mb-3">
              <button class="coupon-btn btn" type="button">Apply Coupon</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row billing-fields">
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 sm-margin-30px-bottom">
      <div class="create-ac-content bg-light-gray padding-20px-all">
        <form id="main-checkout-form" action="includes/classes/checkout-process.php" method="post">
          <fieldset>
            <h2 class="login-title mb-3">Billing details</h2>
            <div class="row">
              <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                <label for="input-firstname">First Name <span class="required-f">*</span></label>
                <input name="firstname" value="<?php echo isset($_SESSION['user_info']['firstname']) ? htmlspecialchars($_SESSION['user_info']['firstname']) : ''; ?>" id="input-firstname" type="text" required class="form-control">
              </div>
              <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                <label for="input-lastname">Last Name <span class="required-f">*</span></label>
                <input name="lastname" value="<?php echo isset($_SESSION['user_info']['lastname']) ? htmlspecialchars($_SESSION['user_info']['lastname']) : ''; ?>" id="input-lastname" type="text" required class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6 col-lg-6 col-xl-6">
                <label for="input-email">E-Mail (optional)</label>
                <input name="email" value="<?php echo isset($_SESSION['user_info']['email']) ? htmlspecialchars($_SESSION['user_info']['email']) : ''; ?>" id="input-email" type="email" class="form-control">
              </div>
              <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                <label for="input-telephone">Telephone <span class="required-f">*</span></label>
                <input name="telephone" value="<?php echo isset($_SESSION['user_info']['phone']) ? htmlspecialchars($_SESSION['user_info']['phone']) : ''; ?>" id="input-telephone" type="tel" required class="form-control">
              </div>
            </div>
          </fieldset>

          <fieldset>
            <div class="row">
              <div class="form-group col-md-12 col-lg-12 col-xl-12 required">
                <label for="input-address">Full Address <span class="required-f">*</span></label>
                <textarea name="address" id="input-address" class="form-control" rows="3" placeholder="Enter your complete address" required></textarea>
              </div>
            </div>
          </fieldset>

         
          <fieldset>
            <div class="row">
              <div class="form-group col-md-12 col-lg-12 col-xl-12">
                <label for="order_notes">Order Notes (optional)</label>
                <textarea name="order_notes" id="order_notes" class="form-control resize-both" rows="2" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
      <div class="your-order-payment">
        <div class="your-order">
          <h2 class="order-title mb-4">Your Order</h2>

          <div class="table-responsive-sm order-table">
            <table class="bg-white table table-bordered table-hover text-center">
              <thead>
                <tr>
                  <th class="text-left">Product Name</th>
                  <th>Price</th>
                  <th>Size</th>
                  <th>Qty</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($cart)): ?>
                  <?php foreach ($cart as $item): ?>
                <tr>
                  <td class="text-left"><?php echo htmlspecialchars($item['name']); ?></td>
                  <td>$<?php echo number_format($item['price'], 2); ?></td>
                  <td><?php echo !empty($item['size']) ? htmlspecialchars($item['size']) : 'N/A'; ?></td>
                  <td><?php echo $item['quantity']; ?></td>
                  <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                <tr>
                  <td colspan="5">Your cart is empty</td>
                </tr>
                <?php endif; ?>
              </tbody>
              <tfoot class="font-weight-600">
                <tr>
                  <td colspan="4" class="text-right">Subtotal</td>
                  <td>$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <tr>
                  <td colspan="4" class="text-right">Delivery</td>
                  <td>$<?php echo number_format($delivery_amount, 2); ?></td>
                </tr>
                <tr>
                  <td colspan="4" class="text-right">Total</td>
                  <td>$<?php echo number_format($total, 2); ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <hr />

        <div class="your-payment">
          <h2 class="payment-title mb-3">Payment Method</h2>
          <div class="payment-method">
            <div class="payment-options">
              <h3 class="payment-title mb-4">Payment Method</h3>
              
              <div class="payment-methods-grid">
                <!-- Cash on Delivery -->
                <div class="payment-method-option">
                  <input type="radio" name="payment_method" value="cod" id="payment_method_1" class="payment-radio" required checked>
                  <label class="payment-method-label" for="payment_method_1">
                    <div class="payment-icon">
                      <i class="anm anm-cash-register"></i>
                    </div>
                    <div class="payment-info">
                      <h4>Cash on Delivery</h4>
                      <p>Pay with cash when your order is delivered</p>
                    </div>
                    <div class="payment-checkmark">
                      <i class="anm anm-check"></i>
                    </div>
                  </label>
                </div>
                
                <!-- Bank Transfer -->
                <div class="payment-method-option">
                  <input type="radio" name="payment_method" value="bank_transfer" id="payment_method_2" class="payment-radio" required>
                  <label class="payment-method-label" for="payment_method_2">
                    <div class="payment-icon">
                      <i class="anm anm-university"></i>
                    </div>
                    <div class="payment-info">
                      <h4>Bank Transfer</h4>
                      <p>Transfer money directly to our account</p>
                    </div>
                    <div class="payment-checkmark">
                      <i class="anm anm-check"></i>
                    </div>
                  </label>
                </div>
                
                <!-- ABA QR Payment -->
                <div class="payment-method-option">
                  <input type="radio" name="payment_method" value="aba_qr" id="payment_method_3" class="payment-radio" required>
                  <label class="payment-method-label" for="payment_method_3">
                    <div class="payment-icon">
                      <i class="anm anm-qrcode"></i>
                    </div>
                    <div class="payment-info">
                      <h4>QR Payment</h4>
                      <p>Scan QR code to complete payment</p>
                    </div>
                    <div class="payment-checkmark">
                      <i class="anm anm-check"></i>
                    </div>
                  </label>
                </div>
                
                <!-- Credit Card -->
                <div class="payment-method-option">
                  <input type="radio" name="payment_method" value="credit_card" id="payment_method_4" class="payment-radio" required>
                  <label class="payment-method-label" for="payment_method_4">
                    <div class="payment-icon">
                      <i class="anm anm-credit-card"></i>
                    </div>
                    <div class="payment-info">
                      <h4>Credit Card</h4>
                      <p>Secure payment with your card</p>
                    </div>
                    <div class="payment-checkmark">
                      <i class="anm anm-check"></i>
                    </div>
                  </label>
                </div>
              </div>

              <div class="order-button-payment mt-5">
                <button class="btn btn-success btn-lg btn-block py-3" value="Place order" type="submit" form="main-checkout-form">
                  <i class="anm anm-check-circle-o mr-2"></i>CONFIRM & PLACE ORDER
                </button>
                <p class="text-center mt-3 text-muted">
                  <i class="anm anm-lock"></i> Your information is securely encrypted
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Payment method selection enhancement
  const paymentRadios = document.querySelectorAll('.payment-radio');
  const paymentContents = document.querySelectorAll('.payment-content');
  
  // Add visual feedback to payment method cards
  paymentRadios.forEach(radio => {
    // Add event listener to highlight selected payment method
    radio.addEventListener('change', function() {
      // Remove 'selected' class from all cards
      document.querySelectorAll('.card').forEach(card => {
        card.classList.remove('payment-selected');
      });
      
      // Add 'selected' class to current card
      const card = this.closest('.card');
      if (card) {
        card.classList.add('payment-selected');
      }
      
      // Hide all payment content sections
      paymentContents.forEach(content => {
        content.style.display = 'none';
      });
      
      // Show content for selected payment method
      const contentId = this.id.replace('payment_method_', 'payment_method_') + '_content';
      const contentToShow = document.getElementById(contentId);
      if (contentToShow) {
        contentToShow.style.display = 'block';
      }
    });
  });
  
  // Initialize the default selected payment method
  const defaultRadio = document.getElementById('payment_method_1');
  if (defaultRadio) {
    defaultRadio.checked = true;
    defaultRadio.dispatchEvent(new Event('change'));
  }
  
  // Add CSS for visual feedback
  const style = document.createElement('style');
  style.textContent = `
    .payment-selected {
      border: 2px solid #007bff !important;
      box-shadow: 0 0 10px rgba(0,123,255,0.5) !important;
    }
    .payment-label {
      cursor: pointer;
      font-weight: 500;
    }
    .order-button-payment .btn {
      padding: 15px 20px;
      font-size: 18px;
      font-weight: bold;
      transition: all 0.3s ease;
    }
    .order-button-payment .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    /* New payment method styles */
    .payment-options {
      background: #f8f9fa;
      padding: 25px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    
    .payment-title {
      color: #333;
      font-weight: 600;
      text-align: center;
      margin-bottom: 25px;
    }
    
    .payment-methods-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 15px;
      margin-bottom: 25px;
    }
    
    .payment-method-option {
      margin-bottom: 10px;
    }
    
    .payment-method-label {
      display: flex;
      align-items: center;
      background: white;
      padding: 15px;
      border: 2px solid #e9ecef;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .payment-method-label:hover {
      border-color: #007bff;
      transform: translateY(-2px);
    }
    
    .payment-method-label input[type="radio"]:checked + & {
      border-color: #28a745;
      box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
    }
    
    .payment-icon {
      width: 40px;
      height: 40px;
      background: #f1f3f5;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
      font-size: 18px;
      color: #007bff;
    }
    
    .payment-info {
      flex: 1;
    }
    
    .payment-info h4 {
      margin: 0 0 5px 0;
      font-size: 16px;
      color: #333;
    }
    
    .payment-info p {
      margin: 0;
      font-size: 14px;
      color: #6c757d;
    }
    
    .payment-checkmark {
      position: absolute;
      top: -8px;
      right: -8px;
      width: 24px;
      height: 24px;
      background: #28a745;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 12px;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .payment-method-label input[type="radio"]:checked + & .payment-checkmark {
      opacity: 1;
    }
    
    .payment-method-label input[type="radio"] {
      display: none;
    }
    
    /* Enhanced order button */
    .btn-success {
      background: linear-gradient(135deg, #28a745, #218838);
      border: none;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    
    .btn-success:hover {
      background: linear-gradient(135deg, #218838, #1e7e34);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
    }
  `;
  document.head.appendChild(style);
});
</script>
<?php endif; ?>