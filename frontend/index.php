<?php 
  include_once 'includes/session-manager.php';
  include_once 'includes/config/database.php';
?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>G-8 Closet</title>
  <meta name="description" content="description">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php require_once 'includes/layout/head/head.php' ?>
</head>

<?php 
  
  $page = isset($_GET['pages']) ? $_GET['pages'] : 'main';
  switch ($page) {
    case 'index': //the old body class name, in case we changee
      $cn  = 'template-index belle template-index-belle';
      break;
    case 'product':
      $cn  = 'template-collection belle';
      break;
    case 'product-detail':
      $cn  = 'template-product template-product-right-thumb belle';
      break;
    case 'blog':
      $cn  = 'blog-page';
      break;
    case 'cart':
      $cn = 'page-template belle';
      break;
    case 'checkout':
      $cn = 'page-template belle';
      break;
    case 'account':
      $cn = 'page-template belle';
      break;
    case 'account-edit':
      $cn = 'page-template belle';
      break;
    case 'dashboard':
      $cn = 'page-template belle';
      break;
    case 'auth-test':
      $cn = 'page-template belle';
      break;
    case 'order-history':
      $cn = 'page-template belle';
      break;
    case 'order-detail':
      $cn = 'page-template belle';
      break;
    case 'address-book':
      $cn = 'page-template belle';
      break;
    case 'wishlist':
      $cn = 'page-template belle';
      break;
    case 'account':
      $cn = 'page-template belle';
      break;
    case 'order-confirmation':
      $cn = 'page-template belle';
      break;
    case '404':
      $cn = 'page-template lookbook-template error-page belle';
      break;
    case 'login':
      $cn = 'page-template belle';
      break;
    case 'register':
      $cn = 'page-template belle';
      break;
    default:
      $cn = 'template-index home11-grid';
  }
  $class_name = $cn;
?>
<body class="<?= $class_name?>">
  <!-- <div id="pre-loader">
    <img src="assets/images/loader.gif" alt="Loading..." />
  </div> -->
  <div class="pageWrapper">
    <!--Search Form Drawer-->
    <?php require 'includes/layout/header/search.php' ?>
    <!--End Search Form Drawer-->
    <!--Top Header-->
    <?php require 'includes/layout/header/topheader.php' ?>
    <!--End Top Header-->
    <!--Header-->
    <?php require 'includes/layout/header/header.php' ?>
    <!--End Header-->

    <!--Body Content-->
    <div id="page-content">
      <?php 
        
        $pages = isset($_GET['pages']) ? $_GET['pages'] : 'main';
        switch ($pages) {
          case 'main':
            require 'includes/layout/body/main2.php';
            break;
          case 'product':
            require 'pages/products/product-list.php';
            break;
          case 'product-detail':
            require 'pages/products/product-detail.php';
            break;
          case '404':
            require 'pages/sections/404.php';
            break;
          case 'cart':
            require 'pages/sections/cart.php';
            break;
          case 'checkout':
            require 'pages/sections/checkout.php';
            break;
          case 'order-confirmation':
            require 'pages/sections/order-confirmation.php';
            break;
          case 'account':
            require 'pages/sections/account.php';
            break;
          case 'minicart':
            require 'pages/minicart.php';
            break;
          case 'login':
            require 'auth/login.php';
            break;
          case 'register':
            require 'auth/register.php';
            break;
          case 'account':
            require 'pages/sections/account.php';
            break;
          case 'account-edit':
            require 'pages/sections/account-edit.php';
            break;
          case 'dashboard':
            require 'pages/sections/dashboard.php';
            break;
          case 'auth-test':
            require 'pages/sections/auth-test.php';
            break;
          case 'order-history':
            require 'pages/sections/order-history.php';
            break;
          case 'order-detail':
            require 'pages/sections/order-detail.php';
            break;
          case 'address-book':
            require 'pages/sections/address-book.php';
            break;
          case 'wishlist':
            require 'pages/sections/wishlist.php';
            break;
          case 'account':
            require 'pages/sections/account.php';
            break;
          default:
            require 'includes/layout/body/main.php';
        }
        

      
      ?>
    </div>
    <!--End Body Content-->

    <!--Footer-->
    <?php require 'includes/layout/footer/footer.php' ?>
    <!--Scoll Top-->
    <span id="site-scroll"><i class="icon anm anm-angle-up-r"></i></span>

    <!--Quick View popup-->
    <?php require 'includes/layout/footer/quick-view-popup.php' ?>

    <!-- Newsletter Popup -->
    <?php require 'includes/layout/footer/newletter-popup.php' ?>

    <!-- Including Jquery -->
    <!-- Including Javascript -->
    <?php require_once 'includes/layout/script.php' ?>
    <!--For Newsletter Popup-->
    <?php require 'includes/layout/footer/popup.php' ?>
    <!--End For Newsletter Popup-->
  </div>
</body>


</html>