<?php
include_once __DIR__ . '/../../session-manager.php';

// Get cart count (sum of quantities)
$cartCount = getCartItemCount();
$cartItems = $_SESSION['cart'] ?? [];
?>
<?php 
  $pages = isset($_GET['pages']) ? $_GET['pages'] : 'main';
  switch ($pages) {
    case 'main':
      $hc  = 'header-wrap classicHeader animated d-flex';
      break;
    case 'product':
      $hc  = 'header-wrap animated d-flex';
      break;
    case 'product-detail':
      $hc  = 'header-wrap animated d-flex';
      break;
    case 'cart':
      $hc  = 'header-wrap animated d-flex';
      break; 
    case 'checkout':
      $hc  = 'header-wrap animated d-flex';
      break;
    case 'login':
      $hc  = 'header-wrap animated d-flex';
      break;
    case 'register':
      $hc  = 'header-wrap animated d-flex';
      break;
    default:
      $hc = 'header-wrap classicHeader animated d-flex';
    }
    $headd_class = $hc;
?>
<div class="<?= $headd_class; ?>">
      <div class="container-fluid">
        <div class="row align-items-center">
          <!--Desktop Logo-->
          <div class="logo col-md-2 col-lg-2 d-none d-lg-block">
            <a href="index.php">
              <img src="assets/images/logo.png" alt="Belle Multipurpose Html Template"
                title="Belle Multipurpose Html Template" />
            </a>
          </div>
          <!--End Desktop Logo-->
          <div class="col-2 col-sm-3 col-md-3 col-lg-8">
            <div class="d-block d-lg-none">
              <button type="button" class="btn--link site-header__menu js-mobile-nav-toggle mobile-nav--open">
                <i class="icon anm anm-times-l"></i>
                <i class="anm anm-bars-r"></i>
              </button>
            </div>
            <!--Desktop Menu-->
            <nav class="grid__item" id="AccessibleNav"><!-- for mobile -->
              <ul id="siteNav" class="site-nav medium center hidearrow">
                <li class="lvl1 parent megamenu"><a href="index.php">Home <i class="anm anm-angle-down-l"></i></a>
                  <!-- <div class="megamenu style1">
                    <ul class="grid mmWrapper">
                      <li class="grid__item large-up--one-whole">
                        <ul class="grid">
                          <li class="grid__item lvl-1 col-md-3 col-lg-3"><a href="#" class="site-nav lvl-1">Home Group
                              1</a>
                            <ul class="subLinks">
                              <li class="lvl-2"><a href="index.php" class="site-nav lvl-2">Home 1 - Classic</a></li>
                              <li class="lvl-2"><a href="pages/home2-default.html" class="site-nav lvl-2">Home 2 - Default</a>
                              </li>
                              <li class="lvl-2"><a href="pages/home15-funiture.html" class="site-nav lvl-2">Home 15 -
                                  Furniture <span class="lbl nm_label1">New</span></a></li>
                              <li class="lvl-2"><a href="pages/home3-boxed.html" class="site-nav lvl-2">Home 3 - Boxed</a>
                              </li>
                              <li class="lvl-2"><a href="pages/home4-fullwidth.html" class="site-nav lvl-2">Home 4 -
                                  Fullwidth</a></li>
                              <li class="lvl-2"><a href="pages/home5-cosmetic.html" class="site-nav lvl-2">Home 5 -
                                  Cosmetic</a></li>
                              <li class="lvl-2"><a href="pages/home6-modern.html" class="site-nav lvl-2">Home 6 - Modern</a>
                              </li>
                              <li class="lvl-2"><a href="pages/home7-shoes.html" class="site-nav lvl-2">Home 7 - Shoes</a>
                              </li>
                            </ul>
                          </li>
                          <li class="grid__item lvl-1 col-md-3 col-lg-3"><a href="#" class="site-nav lvl-1">Home Group
                              2</a>
                            <ul class="subLinks">
                              <li class="lvl-2"><a href="pages/home8-jewellery.html" class="site-nav lvl-2">Home 8 -
                                  Jewellery</a></li>
                              </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </div> -->
                </li>
                <li class="lvl1 parent megamenu"><a href="index.php?pages=product&category=women">Women<i class="anm anm-angle-down-l"></i></a>
                  <div class="megamenu style4">
                    <ul class="grid grid--uniform mmWrapper">
                      <li class="grid__item lvl-1 col-md-3 col-lg-3"><a href="#" class="site-nav lvl-1">New In</a>
                        <ul class="subLinks">
                          <li class="lvl-2"><a href="index.php?pages=product&category=women" class="site-nav lvl-2">All</a>
                          </li>
                          <li class="lvl-2"><a href="index.php?pages=product&category=women&subcategory=t-shirts" class="site-nav lvl-2">T-shirt <span
                                class="lbl nm_label1">New</span></a>
                          </li>
                          <li class="lvl-2"><a href="index.php?pages=product&category=women&subcategory=shirts" class="site-nav lvl-2">Shirt</a>
                          </li>
                          <li class="lvl-2"><a href="index.php?pages=product&category=women&subcategory=hoodie" class="site-nav lvl-2">Sweater</a>
                          </li>
                          <li class="lvl-2"><a href="index.php?pages=product&category=women&subcategory=dress" class="site-nav lvl-2">Dress</a>
                          </li>
                          <li class="lvl-2"><a href="index.php?pages=product&category=women&subcategory=jeans" class="site-nav lvl-2">Jeans</a>
                          </li>
                          
                        </ul>
                      </li>
                      <li class="grid__item lvl-1 col-md-3 col-lg-3"><a href="#" class="site-nav lvl-1">Clothing</a>
                        <ul class="subLinks">
                          <li class="lvl-2"><a href="index.php?pages=product&category=women" class="site-nav lvl-2">All</a></li>
                          
                          <li class="lvl-2"><a href="index.php?pages=product&category=women&subcategory=t-shirts" class="site-nav lvl-2">T-shirt </a></li>
                          <li class="lvl-2"><a href="index.php?pages=product&category=women&subcategory=hoodie" class="site-nav lvl-2">Sweater</a>
                          </li>
                          <li class="lvl-2"><a href="index.php?pages=product&category=women&subcategory=dress" class="site-nav lvl-2">Dress</a>
                          </li>
                        </ul>
                      </li>
                      <li class="grid__item lvl-1 col-md-6 col-lg-6">
                        <a href="#"><img src="assets/images/megamenu-bg1.jpg" alt="" title="" /></a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li class="lvl1 parent megamenu"><a href="index.php?pages=product">MEN <i class="anm anm-angle-down-l"></i></a>
                  <div class="megamenu style2">
                    <ul class="grid mmWrapper">
                      <li class="grid__item one-whole">
                        <ul class="grid">
                          <li class="grid__item lvl-1 col-md-3 col-lg-3"><a href="#" class="site-nav lvl-1">NEW IN</a>
                            <ul class="subLinks">
                              <li class="lvl-2"><a href="index.php?pages=product&category=men&subcategory=t-shirts" class="site-nav lvl-2">T-shirts</a></li>
                              <li class="lvl-2"><a href="index.php?pages=product&category=men&subcategory=shirts" class="site-nav lvl-2">Shirts</a></li>
                              <li class="lvl-2"><a href="index.php?pages=product&category=men&subcategory=hoodie" class="site-nav lvl-2">Hoodies & sweatshirts</a></li>
                              <li class="lvl-2"><a href="index.php?pages=product&category=men&subcategory=jeans" class="site-nav lvl-2">Jeans</a></li>
                              
                            </ul>
                          </li>
                          <li class="grid__item lvl-1 col-md-3 col-lg-3"><a href="#" class="site-nav lvl-1">Clothing</a>
                            <ul class="subLinks">
                              <li class="lvl-2"><a href="index.php?pages=product&category=men" class="site-nav lvl-2">All</a></li>
                              <li class="lvl-2"><a href="index.php?pages=product&category=men&subcategory=t-shirts" class="site-nav lvl-2">T-shirts</a></li>
                              <li class="lvl-2"><a href="index.php?pages=product&category=men&subcategory=shirts" class="site-nav lvl-2">Shirts</a></li>
                              <li class="lvl-2"><a href="index.php?pages=product&category=men&subcategory=hoodie" class="site-nav lvl-2">Hoodies & sweatshirts</a></li>
                              <li class="lvl-2"><a href="index.php?pages=product&category=men&subcategory=jeans" class="site-nav lvl-2">Jeans</a></li>

                            </ul>
                          </li>
                            <li class="grid__item lvl-1 col-md-6 col-lg-6">
                            <a href="#"><img src="assets/images/megamenu-bg1.jpg" alt="" title="" /></a>
                          </li>
                        </ul>
                      </li>
                      <li class="grid__item large-up--one-whole imageCol"><a href="#"><img
                            src="assets/images/megamenu-bg2.jpg" alt=""></a></li>
                    </ul>
                  </div>
                </li>
                
               
              </ul>
            </nav>
            <!--End Desktop Menu-->
          </div>
          <!--Mobile Logo-->
          <div class="col-6 col-sm-6 col-md-6 col-lg-2 d-block d-lg-none mobile-logo">
            <div class="logo">
              <a href="index.php">
                <img src="assets/images/logo.png" alt="Belle Multipurpose Html Template"
                  title="Belle Multipurpose Html Template" />
              </a>
            </div>
          </div>
          <!--Mobile Logo-->
          <div class="col-4 col-sm-3 col-md-3 col-lg-2">
                <div class="site-cart">
                <a href="#;" class="site-header__cart" title="Cart">
                    <i class="icon anm anm-bag-l"></i>
                    <span id="CartCount" class="site-header__cart-count" data-cart-render="item_count">
                        <?= $cartCount ?>
                    </span>
                </a>
                
                <!--Minicart Popup-->
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
                            <li class="item">
                                <a class="product-image" href="index.php?pages=product-detail&id=<?= $id ?>">
                                    <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
                                </a>
                                <div class="product-details">
                                    <a href="javascript:void(0);" class="remove" 
                                       onclick="removeFromHeaderCart(<?= $id ?>)" 
                                       title="Remove this item">
                                        <i class="anm anm-times-l" aria-hidden="true"></i>
                                    </a>
                                    <a class="pName" href="index.php?pages=product-detail&id=<?= $id ?>">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </a>
                                    <div class="variant-cart">
                                        <?php if (!empty($item['color']) || !empty($item['size'])): ?>
                                            <?= htmlspecialchars($item['color']) ?> / <?= htmlspecialchars($item['size']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="wrapQtyBtn">
                                        <div class="qtyField">
                                            <span class="label">Qty: <?= $item['quantity'] ?></span>
                                        </div>
                                    </div>
                                    <div class="priceRow">
                                        <div class="product-price">
                                            <span class="money">$<?= number_format($item['price'], 2) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="total">
                            <div class="total-in">
                                <span class="label">Cart Subtotal:</span>
                                <span class="product-price">
                                    <span class="money">$<?= number_format($total, 2) ?></span>
                                </span>
                            </div>
                            <div class="buttonSet text-center">
                                <a href="index.php?pages=cart" class="btn btn-secondary btn--small">View Cart</a>
                                <a href="index.php?pages=checkout" class="btn btn-secondary btn--small">Checkout</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <!--EndMinicart Popup-->
            </div>
            <div class="site-header__search">
              <button type="button" class="search-trigger"><i class="icon anm anm-search-l"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>

<script>
// Function to remove item from header cart using AJAX
async function removeFromHeaderCart(index) {
    if (!confirm('Remove this item from cart?')) {
        return;
    }
    
    try {
        // Make an AJAX request to update the cart
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
            // Update the cart count in header
            const cartCountElement = document.querySelector('#CartCount');
            if (cartCountElement) {
                cartCountElement.textContent = result.cartCount;
            }
            
            // Trigger cart update event to refresh the mini cart
            document.dispatchEvent(new Event('cartUpdated'));
            
            // Remove the item from the DOM immediately
            const itemElement = event.target.closest('.item');
            if (itemElement) {
                itemElement.remove();
                
                // If cart becomes empty, show the empty message
                const miniCart = document.querySelector('#header-cart');
                const itemsList = miniCart.querySelector('.mini-products-list');
                if (!itemsList.querySelector('.item')) {
                    miniCart.innerHTML = '<div class="text-center p-3"><p>Your cart is empty</p><a href="index.php?pages=product" class="btn btn-secondary btn--small">Shop Now</a></div>';
                }
            }
            
            console.log('Item removed from cart');
        } else {
            console.error('Failed to remove item from cart:', result.message);
            alert('Failed to remove item from cart: ' + result.message);
        }
    } catch (error) {
        console.error('Error removing item from cart:', error);
        alert('Error removing item from cart');
    }
}

// Listen for cart update events
document.addEventListener('cartUpdated', function() {
    // This will be triggered when cart is updated from anywhere
    // You can add any additional logic here if needed
    console.log('Cart updated event received');
});
</script>
