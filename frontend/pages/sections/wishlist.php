<?php
session_start();

// Include session manager for authentication functions
require_once '../../includes/session-manager.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Store the intended page in session
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Set error message and redirect to login
    $_SESSION['error_message'] = 'Please login to view your wishlist.';
    header('Location: ../../frontend/index.php?pages=login');
    exit;
}

// Get current user information
$current_user = getCurrentUser();

// In a real implementation, you would fetch wishlist items from database
// For now, we'll create sample data
$wishlist_items = [
    [
        'id' => 1,
        'name' => 'Regular T-Shirts With Printed',
        'price' => 29.99,
        'regular_price' => 39.99,
        'image' => 'assets/images/product-images/women/t-shirts/AFTERNOON4975.jpg',
        'sizes' => ['S', 'M', 'L', 'XL'],
        'colors' => ['Black', 'White', 'Red']
    ],
    [
        'id' => 2,
        'name' => 'Relaxed Fit T-Shirt',
        'price' => 19.99,
        'regular_price' => 24.99,
        'image' => 'assets/images/product-images/women/t-shirts/ZANDO03.06.20252336.jpg',
        'sizes' => ['S', 'M', 'L'],
        'colors' => ['Black', 'White', 'Blue']
    ]
];
?>

<!--Page Title-->
<div class="page section-header text-center">
  <div class="page-title">
    <div class="wrapper">
      <h1 class="page-width">My Wishlist</h1>
    </div>
  </div>
</div>
<!--End Page Title-->

<div class="container">
  <div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col">
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
        <div class="row mb-4">
          <div class="col-md-6">
            <h2>My Wishlist</h2>
          </div>
          <div class="col-md-6 text-md-right">
            <a href="index.php?pages=account" class="btn btn-secondary">Back to Account</a>
          </div>
        </div>
        
        <?php if (empty($wishlist_items)): ?>
          <div class="alert alert-info">
            <p>Your wishlist is empty.</p>
            <a href="index.php?pages=product" class="btn btn-primary">Start Shopping</a>
          </div>
        <?php else: ?>
          <div class="row">
            <?php foreach ($wishlist_items as $item): ?>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card product-card">
                <div class="product-image position-relative">
                  <a href="index.php?pages=product-detail&id=<?= $item['id'] ?>">
                    <img class="card-img-top" src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                  </a>
                  <div class="product-overlay d-flex align-items-center justify-content-center">
                    <div class="product-buttons">
                      <a href="index.php?pages=cart&action=add&id=<?= $item['id'] ?>" class="btn btn-primary btn-sm">Add to Cart</a>
                      <a href="index.php?pages=wishlist-remove&id=<?= $item['id'] ?>" class="btn btn-danger btn-sm ml-2" onclick="return confirm('Are you sure you want to remove this item from your wishlist?');">Remove</a>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="index.php?pages=product-detail&id=<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                  </h5>
                  <div class="product-price">
                    <span class="money">$<?= number_format($item['price'], 2) ?></span>
                    <?php if ($item['price'] < $item['regular_price']): ?>
                      <span class="old-price text-muted mr-1">
                        <s>$<?= number_format($item['regular_price'], 2) ?></s>
                      </span>
                    <?php endif; ?>
                  </div>
                  <div class="product-meta mt-2">
                    <div class="sizes">
                      <small>Sizes: <?= implode(', ', $item['sizes']) ?></small>
                    </div>
                    <div class="colors mt-1">
                      <small>Colors: <?= implode(', ', $item['colors']) ?></small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<style>
.product-card {
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.product-image {
    overflow: hidden;
    position: relative;
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.product-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.product-price .money {
    font-weight: bold;
    color: #000;
}

.product-price .old-price s {
    color: #999;
}

.card-title a {
    text-decoration: none;
    color: inherit;
}

.card-title a:hover {
    color: #007bff;
}
</style>