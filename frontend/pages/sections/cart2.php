<?php
  // Include centralized session management
  include_once __DIR__ . '/../../../includes/session-manager.php';
  $cart = $_SESSION['cart'] ?? [];
?>

<!-- Page Title -->
<div class="page section-header text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">Your Cart</h1>
    </div>
  </div>
</div>
<!-- End Page Title -->

<div class="container">
  <div class="row">
    <!-- Cart Table -->
    <?php
    if (!empty($_SESSION['cart'])) {
      echo "<table>
              <tr>
                <th>Product</th>
                <th>Color</th>
                <th>Size</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>";
      
      $grandTotal = 0;
      foreach ($_SESSION['cart'] as $item) {
        $total = $item['price'] * $item['quantity'];
        $grandTotal += $total;
        echo "<tr>
                <td>{$item['name']}</td>
                <td>{$item['color']}</td>
                <td>{$item['size']}</td>
                <td>\${$item['price']}</td>
                <td>{$item['quantity']}</td>
                <td>\${$total}</td>
              </tr>";
      }
      echo "<tr><td colspan='5'><strong>Grand Total</strong></td>
            <td><strong>\$$grandTotal</strong></td></tr>";
      echo "</table>";
    } else {
      echo "<p style='text-align:center;'>Your cart is empty 💤</p>";
    }
    ?>




    <!-- Cart Sidebar -->
    <div class="col-12 col-sm-12 col-md-4 col-lg-4 cart__footer">
      <div class="cart-note mb-3">
        <div class="solid-border">
          <h5><label for="CartSpecialInstructions" class="cart-note__label small--text-center">
            Add a note to your order
          </label></h5>
          <textarea name="note" id="CartSpecialInstructions" class="cart-note__input"></textarea>
        </div>
      </div>

      <div class="solid-border p-3">
        <div class="row mb-2">
          <span class="col-6 cart__subtotal-title"><strong>Subtotal</strong></span>
          <span class="col-6 cart__subtotal-title cart__subtotal text-right">
            <span class="money"></span>
          </span>
        </div>
        <div class="cart__Delivery mb-3">
          Delivery &amp; taxes calculated at checkout
        </div>

        <p class="cart_tearm">
          <label>
            <input type="checkbox" name="tearm" id="cartTearm" class="checkbox" value="tearm" required>
            I agree with the terms and conditions
          </label>
        </p>

        <a href="index.php?page=sections/checkout" class="btn btn--small-wide checkout w-100">
          Checkout
        </a>

        <div class="paymnet-img mt-3">
          <img src="assets/images/payment-img.jpg" alt="Payment" class="img-fluid">
        </div>
      </div>
    </div>

  </div>
</div>

