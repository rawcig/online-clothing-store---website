<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? null;
$index_to_remove = $_GET['id'] ?? null;
$redirect = false;

// Handle cart actions
if ($action) {
    switch ($action) {
        case 'remove':
            if ($index_to_remove !== null) {
                $index_to_remove = (int)$index_to_remove;
                if (isset($_SESSION['cart'][$index_to_remove])) {
                    unset($_SESSION['cart'][$index_to_remove]);
                    // Re-index the array after removal
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                }
                $redirect = true;
            }
            break;

        case 'clear':
            $_SESSION['cart'] = [];
            $redirect = true;
            break;

        case 'update':
            if (!empty($_POST['updates'])) {
                foreach ($_POST['updates'] as $index => $quantity) {
                    $quantity = max(1, (int)$quantity);
                    if (isset($_SESSION['cart'][$index])) {
                        $_SESSION['cart'][$index]['quantity'] = $quantity;
                    }
                }
                $redirect = true;
            }
            break;
    }

    if ($redirect) {
        $_SESSION['cart_message'] = 'Cart updated successfully';
        ?>
        <script>
            window.location.href = 'index.php?pages=cart';
        </script>
        <?php
        exit;
    }
}

$cart = $_SESSION['cart'];

// Debug: Show cart contents (temporary)
if (isset($_GET['debug'])) {
    echo "<pre>Cart Contents: ";
    print_r($cart);
    echo "</pre>";
}
?>

<!-- Page Title -->
<div class="page section-header text-center">
    <div class="page-title">
        <div class="wrapper">
            <h1 class="page-width">Your Cart</h1>
        </div>
    </div>
</div>

<div class="container">
    <?php if (empty($cart)): ?>
        <div class="text-center mb-5">
            <div class="text-center">
                <p>Your cart is empty.</p>
                <a href="index.php?pages=product" class="btn">Pick a Product</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 main-col">
                <form action="index.php?pages=cart" method="post" class="cart style2">
                    <table>
                        <thead class="cart__row cart__header">
                            <tr>
                                <th colspan="2" class="text-center">Product</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Total</th>
                                <th class="action">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $grand_total = 0;
                            foreach ($cart as $index => $item):
                                $total = $item['price'] * $item['quantity'];
                                $grand_total += $total;
                            ?>
                            <tr class="cart__row border-bottom line1 cart-flex border-top">
                                <td class="cart__image-wrapper cart-flex-item">
                                    <a href="#">
                                        <img class="cart__image" src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    </a>
                                </td>
                                <td class="cart__meta small--text-left cart-flex-item">
                                    <div class="list-view-item__title">
                                        <a href="#"><?= htmlspecialchars($item['name']) ?></a>
                                    </div>
                                    <div class="cart__meta-text">
                                        <?php if (!empty($item['color'])): ?>
                                            Color: <?= htmlspecialchars($item['color']) ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($item['size'])): ?>
                                            Size: <?= htmlspecialchars($item['size']) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="cart__price-wrapper cart-flex-item">
                                    <span class="money">$<?= number_format($item['price'], 2) ?></span>
                                </td>
                                <td class="cart__update-wrapper cart-flex-item text-right">
                                    <div class="cart__qty text-center">
                                        <div class="qtyField">
                                            <a class="qtyBtn minus" href="javascript:void(0);"><i class="icon icon-minus"></i></a>
                                            <input class="cart__qty-input qty" 
                                                   type="number" 
                                                   name="updates[<?= $index ?>]" 
                                                   value="<?= $item['quantity'] ?>" 
                                                   min="1">
                                            <a class="qtyBtn plus" href="javascript:void(0);"><i class="icon icon-plus"></i></a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right small--hide cart-price">
                                    <div><span class="money">$<?= number_format($total, 2) ?></span></div>
                                </td>
                                <td class="text-center small--hide">
                                    <!-- Fixed remove link -->
                                    <a href="javascript:void(0);" 
                                       class="btn btn--secondary cart__remove" 
                                       title="Remove item"
                                       onclick="removeFromCart(<?= $index ?>)">
                                        <i class="icon icon anm anm-times-l"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-left">
                                    <a href="index.php?pages=product" class="btn--link cart-continue">
                                        <i class="icon icon-arrow-circle-left"></i> Continue shopping
                                    </a>
                                </td>
                                <td colspan="3" class="text-right">
                                    <button type="submit" name="action" value="update" class="btn btn--small btn--secondary">
                                        Update Cart
                                    </button>
                                    <a href="index.php?pages=cart&action=clear" 
                                       class="btn--link cart-update text-danger"
                                       onclick="return confirm('Clear entire cart?');">
                                        <i class="fa fa-trash"></i> Clear Cart
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>

            <!-- Cart Sidebar -->
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 cart__footer">
                <div class="solid-border p-3">
                    <div class="row mb-2">
                        <span class="col-6 cart__subtotal-title"><strong>Subtotal</strong></span>
                        <span class="col-6 cart__subtotal-title cart__subtotal text-right">
                            <span class="money">$<?= number_format($grand_total, 2) ?></span>
                        </span>
                    </div>
                    <div class="cart__Delivery mb-3">Delivery &amp; taxes calculated at checkout</div>
                    <a href="index.php?pages=checkout" class="btn btn--small-wide checkout">Proceed to checkout</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Function to remove item from cart using AJAX
async function removeFromCart(index) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('action', 'remove');
        formData.append('id', index);
        formData.append('token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
        
        const response = await fetch('pages/sections/add_to_cart.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            // Update cart count in header
            const cartCountElement = document.querySelector('#CartCount');
            if (cartCountElement) {
                cartCountElement.textContent = result.cartCount;
            }
            
            // Remove the item row from the cart table
            const itemRow = event.target.closest('tr');
            if (itemRow) {
                itemRow.remove();
            }
            
            // Reload the page to update totals or show empty cart message
            window.location.reload();
        } else {
            console.error('Failed to remove item from cart:', result.message);
            alert('Failed to remove item from cart: ' + result.message);
        }
    } catch (error) {
        console.error('Error removing item from cart:', error);
        alert('Error removing item from cart');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Quantity buttons functionality
    const qtyBtns = document.querySelectorAll('.qtyBtn');
    qtyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.qty');
            let value = parseInt(input.value);
            
            if (this.classList.contains('plus')) {
                value++;
            } else {
                value = Math.max(1, value - 1);
            }
            
            input.value = value;
        });
    });
});
</script>

