<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartItems = $_SESSION['cart'] ?? [];
$cartCount = 0;
foreach ($cartItems as $item) {
    $cartCount += $item['quantity'];
}
?>

<div class="site-cart">
    <a href="#;" class="site-header__cart" title="Cart">
        <i class="icon anm anm-bag-l"></i>
        <span id="CartCount" class="site-header__cart-count"><?= $cartCount ?></span>
    </a>
    
    <div id="header-cart" class="block block-cart">
        <?php if (empty($cartItems)): ?>
            <div class="text-center p-3">
                <p>Your cart is empty</p>
                <a href="index.php?pages=product" class="btn btn-secondary btn--small">Shop Now</a>
            </div>
        <?php else: ?>
            <ul class="mini-products-list">
                <?php 
                $total = 0;
                foreach ($cartItems as $id => $item): 
                    $total += $item['price'] * $item['quantity'];
                ?>
                <li class="item" data-product-id="<?= $id ?>">
                    <a class="product-image" href="#">
                        <img src="<?= htmlspecialchars($item['img'] ?? '') ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>" />
                    </a>
                    <div class="product-details">
                        <a href="#" class="remove-item" data-id="<?= $id ?>">
                            <i class="anm anm-times-l" aria-hidden="true"></i>
                        </a>
                        <a class="pName" href="#"><?= htmlspecialchars($item['name']) ?></a>
                        <div class="variant-cart">
                            <?= htmlspecialchars($item['color'] ?? '') ?> 
                            <?= !empty($item['size']) ? '/ ' . htmlspecialchars($item['size']) : '' ?>
                        </div>
                        <div class="wrapQtyBtn">
                            <span class="item-quantity">Qty: <?= $item['quantity'] ?></span>
                        </div>
                        <div class="priceRow">
                            <span class="product-price">$<?= number_format($item['price'], 2) ?></span>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="total">
                <div class="total-in">
                    <span class="label">Cart Subtotal:</span>
                    <span class="product-price">$<?= number_format($total, 2) ?></span>
                </div>
                <div class="buttonSet text-center">
                    <a href="index.php?pages=cart" class="btn btn-secondary btn--small">View Cart</a>
                    <a href="index.php?pages=checkout" class="btn btn-secondary btn--small">Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>