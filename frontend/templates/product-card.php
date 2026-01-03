<?php
<div class="product-card" data-product-id="<?php echo $product['id']; ?>">
    <img class="product-image" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
    <h3 class="product-name"><?php echo $product['name']; ?></h3>
    <p class="product-price" data-price="<?php echo $product['price']; ?>">
        $<?php echo number_format($product['price'], 2); ?>
    </p>
    <button class="add-to-cart-btn">Add to Cart</button>
</div>