  <?php 
    $title =  $_GET['category'] ?? 'All';
    $sub_title = $_GET['subcategory'] ?? '';  

  ?>
  <!--Collection Banner-->
      <div class="collection-header">
        <div class="collection-hero">
          <div class="collection-hero__image"><img class="blur-up lazyload" data-src="assets/images/cat-women1.jpg"
              src="assets/images/cat-women1.jpg" alt="Women" title="Women" /></div>
          <div class="collection-hero__title-wrapper">
            <h1 class="collection-hero__title page-width"> 
              <?php 
                if ($title != 'All') {
                  if ($sub_title) {
                    echo  ucfirst($title) ." / " . ucfirst($sub_title);
                  } else {
                    echo  ucfirst($title) . " - All Clothing";
                  }
                } else{
                  echo "All Clothing";
                }
              ?>
            </h1>
          </div>
        </div>
      </div>
      <!--End Collection Banner-->

      <div class="container">
        <div class="row">
          <!--Main Content-->
          <div class="col-12 col-sm-12 col-md-9 col-lg-9 main-col">
            <div class="productList">
              <!--Toolbar-->
              <button type="button" class="btn btn-filter d-block d-md-none d-lg-none"> Product Filters</button>
              <div class="toolbar">
                <div class="filters-toolbar-wrapper">
                  <div class="row">
                    <div class="col-4 col-md-4 col-lg-4 text-left">
                      <span class="filters-toolbar__product-count">
                        <?php 
                            echo 'Category: ' . ucfirst($title) . ($sub_title ? " / " . ucfirst($sub_title) : '');
                         ?>
                      </span>
                    </div>
                    <div
                      class="col-4 col-md-4 col-lg-4 text-center filters-toolbar__item filters-toolbar__item--count d-flex justify-content-center align-items-center">
                      <span class="filters-toolbar__product-count"></span>
                    </div>
                    <div class="col-4 col-md-4 col-lg-4 text-right">
                      <div class="filters-toolbar__item">
                        <label for="SortBy" class="hidden">Sort</label>
                        <select name="SortBy" id="SortBy" class="filters-toolbar__input filters-toolbar__input--sort">
                          <option value="title-ascending" selected="selected">Sort</option>
                          <option>Best Selling</option>
                          <option>Alphabetically, A-Z</option>
                          <option>Alphabetically, Z-A</option>
                          <option>Price, low to high</option>
                          <option>Price, high to low</option>
                          <option>Date, new to old</option>
                          <option>Date, old to new</option>
                        </select>
                        <input class="collection-header__default-sort" type="hidden" value="manual">
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <!--product-->
              <div class="grid-products grid--view-items product-load-more">
                <div class="row">
                <?php 
                  $category = $_GET['category'] ?? null;
                  $subcategory = $_GET['subcategory'] ?? null;

                  // If your URL gives string names like "men", map them to IDs
                  switch ($category) {
                    case 'men':
                      $category = 2;
                      break;
                    case 'women':
                      $category = 3;
                      break;
                    default:
                      // leave it null if not matched
                      $category = $category;
                  }
                  switch ($subcategory) {
                    case 't-shirts':
                      $subcategory = 5;
                      break;
                    case 'hoodie':
                      $subcategory = 6;
                      break;
                    case 'shirts':
                      $subcategory = 7;
                      break;
                    case 'jeans':
                      $subcategory = 8;
                      break;
                    case 'dresses':
                      $subcategory = 9;
                      break;
                    default:
                      // leave it null if not matched
                      $subcategory = $subcategory;
                  }

                  // Build base query
                  $sql = "SELECT * FROM products";
                  $conditions = [];
                  $params = [];
                  $types = "";

                  // Add category filter if exists
                  if ($category) {
                    $conditions[] = "category_id = ?";
                    $params[] = $category;
                    $types .= "i"; // integer
                  }

                  // Add subcategory filter if exists
                  if ($subcategory) {
                    $conditions[] = "sub_category_id = ?";
                    $params[] = $subcategory;
                    $types .= "i";
                  }

                  // Combine conditions
                  if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                  }

                  // Prepare and bind
                  $stmt = $conn->prepare($sql);

                  if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                  }

                  $stmt->execute();
                  $result = $stmt->get_result();
                  while ($row = $result->fetch_assoc()) {
                ?>

                  <div class="col-6 col-sm-6 col-md-4 col-lg-3 item">
                    <!-- start product image -->
                    <div class="product-image">
                      <?php 
                        $gallery_sql = "SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 2";
                        $gallery_stmt = $conn->prepare($gallery_sql);
                        $gallery_stmt->bind_param("i", $row['id']);
                        $gallery_stmt->execute();
                        $gallery_result = $gallery_stmt->get_result();
                        $gallery_images = $gallery_result->fetch_all(MYSQLI_ASSOC);
                        if (count($gallery_images)<2 ) {
                          $gallery_images[0]['image_path'] = $row['image']; // Ensure primary image is first 
                          $gallery_images[1]['image_path'] = 'assets/images/product-images/product-image1-1.jpg'; // Placeholder for hover image
                          
                        }
                        // echo $row['id'].'<br>';
                        // echo count($gallery_images);
                        // echo htmlspecialchars($gallery_images[1]['image_path']);
                        // echo $row['image'];
                      ?>
                      <!-- start product image -->
                      <a href="index.php?pages=product-detail&id=<?= $row['id'] ?>">
                        <!-- image -->
                        <img class="primary blur-up lazyload" data-src="<?= htmlspecialchars($gallery_images[0]['image_path']) ?>"
                          src="<?= htmlspecialchars($gallery_images[0]['image_path'])?>" alt="image" title="product">
                        <!-- End image -->
                        <!-- Hover image -->
                        <img class="hover blur-up lazyload" data-src="<?= htmlspecialchars($gallery_images[1]['image_path'])?>"
                          src="<?= htmlspecialchars($gallery_images[1]['image_path'])?>" alt="image" title="product">
                        <!-- End hover image -->
                        <!-- product label -->
                        <!-- <div class="product-labels rounded"><span class="lbl on-sale">-16%</span> <span
                            class="lbl pr-label1">new</span></div> -->
                        <!-- End product label -->
                      </a>
                     
                      <!-- end product image -->

                      <!-- Start product button -->
                      <form class="variants add" action="#" onclick="window.location.href='index.php?pages=cart'" method="post">
                        <button class="btn btn-addto-cart" type="button">Add To Cart</button>
                      </form>
                      <div class="button-set">
                        <a href="javascript:void(0)" title="Quick View" class="quick-view-popup quick-view"
                          data-toggle="modal" data-target="#content_quickview">
                          <i class="icon anm anm-search-plus-r"></i>
                        </a>
                        <div class="wishlist-btn">
                          <a class="wishlist add-to-wishlist" href="#" title="Add to Wishlist">
                            <i class="icon anm anm-heart-l"></i>
                          </a>
                        </div>
                        <div class="compare-btn">
                          <a class="compare add-to-compare" href="pages/compare.html" title="Add to Compare">
                            <i class="icon anm anm-random-r"></i>
                          </a>
                        </div>
                      </div>
                      <!-- end product button -->
                    </div>
                    <!-- end product image -->

                    <!--start product details -->
                    <div class="product-details text-center">
                      <!-- product name -->
                      <div class="product-name">
                        <a href="#"><?= $row['name'] ?></a>
                      </div>
                      <!-- End product name -->
                      <!-- product price -->
                      <div class="product-price">
                        <!-- <span class="old-price">$500.00</span> -->
                        <span class="price">$<?= $row['price']?> </span>
                      </div>
                      <!-- End product price -->

                      <div class="product-review">
                        <i class="font-13 fa fa-star"></i>
                        <i class="font-13 fa fa-star"></i>
                        <i class="font-13 fa fa-star"></i>
                        <i class="font-13 fa fa-star-o"></i>
                        <i class="font-13 fa fa-star-o"></i>
                      </div>
                    </div>
                    <!-- End product details -->
                  </div>
                <?php 
                  }
                  ?>
                </div>
              </div>
            </div>
            <div class="infinitpaginOuter">
              <div class="infinitpagin">
                <a href="pages/javascript:void(0)" class="btn loadMore">Load More</a>
              </div>
            </div>
          </div>
          <!--Sidebar-->
          <div class="col-12 col-sm-12 col-md-3 col-lg-3 sidebar filterbar">
            <div class="closeFilter d-block d-md-none d-lg-none"><i class="icon icon anm anm-times-l"></i></div>
            <div class="sidebar_tags">
              <!--Categories-->
              <div class="sidebar_widget categories filter-widget">
                <div class="widget-title">
                  <h2>Categories</h2>
                </div>
                <div class="widget-content">
                  <ul class="sidebar_categories">
                    <li class="lvl-1"><a href="index.php?pages=product" class="site-nav">All Clothing</a></li>
                    <li class="level1 sub-level"><a href="#;" class="site-nav">Men</a>
                      <ul class="sublinks">
                        <li class="level2"><a href="index.php?pages=product&category=men&subcategory=t-shirts" class="site-nav">T-shirts</a></li>
                        <li class="level2"><a href="index.php?pages=product&category=men" class="site-nav">View All Clothing</a></li>
                      </ul>
                    </li>
                    <li class="level1 sub-level"><a href="#;" class="site-nav">Women</a>
                      <ul class="sublinks">
                        <li class="level2"><a href="index.php?pages=product&category=women&subcategory=t-shirts" class="site-nav">T-shirts</a></li>
                        <li class="level2"><a href="index.php?pages=product&category=women&subcategory=hoodie" class="site-nav">Sweat-shirt & Hoodies</a></li>
                        <li class="level2"><a href="index.php?pages=product&category=women" class="site-nav">All</a></li>
                        <li class="level2"><a href="index.php?pages=product&category=women" class="site-nav">All</a></li>
                        <li class="level2"><a href="index.php?pages=product&category=women" class="site-nav">View All Clothing</a></li>
                      </ul>
                    </li>
                    <li class="lvl-1"><a href="#;" class="site-nav">Shoes</a></li>
                    <li class="lvl-1"><a href="#;" class="site-nav">Accessories</a></li>
                    <li class="lvl-1"><a href="#;" class="site-nav">Collections</a></li>
                    <li class="lvl-1"><a href="#;" class="site-nav">Sale</a></li>
                    <li class="lvl-1"><a href="#;" class="site-nav">Page</a></li>
                  </ul>
                </div>
              </div>
              <!--Categories-->
              <!--Price Filter-->
              <div class="sidebar_widget filterBox filter-widget">
                <div class="widget-title">
                  <h2>Price</h2>
                </div>
                <form action="#" method="post" class="price-filter">
                  <div id="slider-range"
                    class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                    <div class="ui-slider-range ui-widget-header ui-corner-all"></div>
                    <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                    <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <p class="no-margin"><input id="amount" type="text"></p>
                    </div>
                    <div class="col-6 text-right margin-25px-top">
                      <button class="btn btn-secondary btn--small">filter</button>
                    </div>
                  </div>
                </form>
              </div>
              <!--End Price Filter-->
              <!--Size Swatches-->
              <div class="sidebar_widget filterBox filter-widget size-swacthes">
                <div class="widget-title">
                  <h2>Size</h2>
                </div>
                <div class="filter-color swacth-list">
                  <ul>
                    <li><span class="swacth-btn checked">X</span></li>
                    <li><span class="swacth-btn">XL</span></li>
                    <li><span class="swacth-btn">M</span></li>
                    <li><span class="swacth-btn">L</span></li>
                    <li><span class="swacth-btn">S</span></li>
                    <li><span class="swacth-btn">XS</span></span></li>
                  </ul>
                </div>
              </div>
              <!--End Size Swatches-->
              <!--Color Swatches-->
              <div class="sidebar_widget filterBox filter-widget">
                <div class="widget-title">
                  <h2>Color</h2>
                </div>
                <div class="filter-color swacth-list clearfix">
                  <span class="swacth-btn black"></span>
                  <span class="swacth-btn white checked"></span>
                  <span class="swacth-btn red"></span>
                  <span class="swacth-btn blue"></span>
                  <span class="swacth-btn pink"></span>
                  <span class="swacth-btn gray"></span>
                  <span class="swacth-btn green"></span>
                  <span class="swacth-btn orange"></span>
                  <span class="swacth-btn yellow"></span>
                  <span class="swacth-btn blueviolet"></span>
                  <span class="swacth-btn brown"></span>
                  <span class="swacth-btn darkGoldenRod"></span>
                  <span class="swacth-btn darkGreen"></span>
                  <span class="swacth-btn darkRed"></span>
                  <span class="swacth-btn dimGrey"></span>
                  <span class="swacth-btn khaki"></span>
                </div>
              </div>
              <!--End Color Swatches-->
              <!--Brand-->
              <div class="sidebar_widget filterBox filter-widget">
                <div class="widget-title">
                  <h2>Brands</h2>
                </div>
                <ul>
                  <li>
                    <input type="checkbox" value="allen-vela" id="check1">
                    <label for="check1"><span><span></span></span>Allen Vela</label>
                  </li>
                  <li>
                    <input type="checkbox" value="oxymat" id="check3">
                    <label for="check3"><span><span></span></span>Oxymat</label>
                  </li>
                  <li>
                    <input type="checkbox" value="vanelas" id="check4">
                    <label for="check4"><span><span></span></span>Vanelas</label>
                  </li>
                  <li>
                    <input type="checkbox" value="pagini" id="check5">
                    <label for="check5"><span><span></span></span>Pagini</label>
                  </li>
                  <li>
                    <input type="checkbox" value="monark" id="check6">
                    <label for="check6"><span><span></span></span>Monark</label>
                  </li>
                </ul>
              </div>
              <!--End Brand-->
              
              <!--Banner-->
              <div class="sidebar_widget static-banner">
                <img src="assets/images/side-banner-2.jpg" alt="" />
              </div>
              <!--Banner-->
              <!--Information-->
              <div class="sidebar_widget">
                <div class="widget-title">
                  <h2>Information</h2>
                </div>
                <div class="widget-content">
                  <p>Use this text to share information about your brand with your customers. Describe a product, share
                    announcements, or welcome customers to your store.</p>
                </div>
              </div>
              <!--end Information-->
            </div>
          </div>
        </div>
      </div>


    </div>