<?php 
    $selected_color = isset($_GET['color']) ? $_GET['color'] : '';
    $selected_size = isset($_GET['size']) ? $_GET['size'] : '';
    $pid = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    $pd_sql = "SELECT p.*, b.name AS brand_name, c.name AS category_name
              FROM products p
              LEFT JOIN brands b ON p.brand_id = b.id
              LEFT JOIN categories c ON p.category_id = c.id
              WHERE p.id = ?";   
    $pd_stmt = $conn->prepare($pd_sql);
    $pd_stmt->bind_param("i", $pid);
    $pd_stmt->execute();
    $pd_result = $pd_stmt->get_result();
    $product = $pd_result->fetch_assoc();

    $variants_sql = "SELECT color, price FROM product_variants WHERE product_id = ? ORDER BY color";
    $variants_stmt = $conn->prepare($variants_sql);
    $variants_stmt->bind_param("i", $pid);
    $variants_stmt->execute();
    $variants = $variants_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $selected_price = $product['price']; // Default price
    // if ($selected_color && $selected_size) {
    //     foreach ($variants as $variant) {
    //         if (strtolower($variant['color']) === strtolower($selected_color) && 
    //             strtolower($variant['size']) === strtolower($selected_size)) {
    //             $selected_price = $variant['price'];
    //             break;
    //         }
    //     }
    // }

    $gallery_sql = "SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC";
    $gallery_stmt = $conn->prepare($gallery_sql);
    $gallery_stmt->bind_param("i", $pid);
    $gallery_stmt->execute();
    $gallery_result = $gallery_stmt->get_result();
    $gallery_images = $gallery_result->fetch_all(MYSQLI_ASSOC);
?>

<script>
   function updateVariantSelection(color, size) {
    console.log('Updating variant selection - Color:', color, 'Size:', size);
    
    if (color) {
      document.getElementById('slVariant').innerText = color;
    }

    const sizeLabel = document.querySelector('.swatch-1 .header .slVariant');
    if (sizeLabel && size) {
      sizeLabel.innerText = size;
    }

    document.querySelectorAll('.swatch-0 .swatch-element').forEach(el => el.classList.remove('selected'));
    document.querySelectorAll('.swatch-1 .swatch-element').forEach(el => el.classList.remove('selected'));

    if (color) {
      const colorEl = document.querySelector(`.swatch-0 .swatch-element[data-value="${color}"]`);
      if (colorEl) colorEl.classList.add('selected');
    }
    if (size) {
      const sizeEl = document.querySelector(`.swatch-1 .swatch-element[data-value="${size}"]`);
      if (sizeEl) sizeEl.classList.add('selected');
    }

    // Update hidden form fields
    const selectedColorInput = document.getElementById('selectedColor');
    const selectedSizeInput = document.getElementById('selectedSize');
    if (selectedColorInput) {
        selectedColorInput.value = color || '';
        console.log('Updated hidden color field to:', selectedColorInput.value);
    }
    if (selectedSizeInput) {
        selectedSizeInput.value = size || '';
        console.log('Updated hidden size field to:', selectedSizeInput.value);
    }

    const params = new URLSearchParams(window.location.search);
    if (color) params.set("color", color);
    if (size) params.set("size", size);
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.replaceState({}, '', newUrl);
  

    event?.preventDefault();
  }
   window.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const color = params.get("color");
    const size = params.get("size");

    if (color) {
      const colorInput = document.querySelector(`input[name="option-0"][value="${color}"]`);
      if (colorInput) colorInput.checked = true;
    }

    if (size) {
      const sizeInput = document.querySelector(`input[name="option-1"][value="${size}"]`);
      if (sizeInput) sizeInput.checked = true;
    }

    if (color || size) {
      updateVariantSelection(color, size);
    }
  });
    
</script>

<!--MainContent-->
<div id="MainContent" class="main-content" role="main">
    <!--Breadcrumb-->
    <div class="bredcrumbWrap">
      <div class="container breadcrumbs" role="navigation" aria-label="breadcrumbs">
        <a href="index.php" title="Back to the home page">Home</a><span aria-hidden="true">›</span><span><?= $product['name'] ?></span>
      </div>
    </div>
    <!--End Breadcrumb-->
    <div id="ProductSection-product-template"
      class="product-template__container prstyle1 container product-right-sidebar">
      <!--product-single-->
      <div class="product-single">
        <div class="row">
          <div class="col-lg-9 col-md-9 col-sm-12 col-12">
            <div class="product-details-img">
              <div class="zoompro-wrap product-zoom-right pl-20">
                <div class="zoompro-span">
                  <img class="zoompro blur-up lazyload"
                    data-zoom-image="<?= $product['image']?>" alt=""
                    src="<?= $product['image']?>" />
                </div>
               
              </div>
              <div class="lightboximages">
                <?php while ($img = $gallery_result->fetch_assoc()): ?>
                  <a href="<?= htmlspecialchars($img['image_path']); ?>" data-size="1462x2048"></a>
                <?php endwhile; ?>
              </div>

              <div class="product-thumb">
                  <div id="gallery" class="product-dec-slider-2 product-tab-left">
                    <?php if ($gallery_result->num_rows > 0): 
                      $gallery_result->data_seek(0); 
                      while ($img = $gallery_result->fetch_assoc()): 
                      ?>
                        <a data-image="<?= htmlspecialchars($img['image_path']); ?>"
                          data-zoom-image="<?= htmlspecialchars($img['image_path']); ?>"
                          class="slick-slide"
                          aria-hidden="true" tabindex="-1">
                          <img class="blur-up lazyload"
                              src="<?= htmlspecialchars($img['image_path']); ?>"
                              alt="Product image" />
                        </a>
                      <?php endwhile; else: ?>
                        <a data-image="<?= $product['image'] ?>"
                          data-zoom-image="<?= $product['image'] ?>"
                          class="slick-slide"
                          aria-hidden="true" tabindex="-1">
                          <img class="blur-up lazyload"
                              src="<?= $product['image'] ?>"
                              alt="Product image" />
                        </a>
                    <?php endif; ?>
                  </div>
                </div>  

            </div>
            <div class="product-information">
              <div class="product-single__meta">
                <h1 class="product-single__title"><?= $product['name']?></h1>
                <div class="prInfoRow">
                  <div class="product-stock"> <span class="instock ">In Stock : <?= $product['stock_qty']?></span> <span
                      class="outstock hide">Unavailable</span> </div>
                  <div class="product-sku">SKU: <span class="variant-sku"><?= $product['sku']?></span></div>
                  <div class="product-review"><a class="reviewLink" href="#tab2"><i
                        class="font-13 fa fa-star"></i><i class="font-13 fa fa-star"></i><i
                        class="font-13 fa fa-star"></i><i class="font-13 fa fa-star-o"></i><i
                        class="font-13 fa fa-star-o"></i><span class="spr-badge-caption">6 reviews</span></a></div>
                </div>
                <p class="product-single__price product-single__price-product-template">
                  <span class="visually-hidden">Regular price</span>
                  <span class="product-price__price product-price__price-product-template">
                    <span id="ProductPrice-product-template"><span class="money">$<?= $selected_price ?></span></span>
                  </span>
                </p>
                <div class="product-single__description rte">
                  <p><?= $product['description']?></p>
                </div>
                <!-- FORM HERE BRO  -->
                <form method="POST" action="pages/sections/add_to_cart.php" id="cartForm"
                  accept-charset="UTF-8" class="product-form product-form-product-template hidedropdown"
                  enctype="multipart/form-data" onsubmit="return false;">
                  <input type="hidden" name="id" value="<?= $product['id'] ?>">
                  <input type="hidden" name="img" value="<?= $product['image'] ?>">
                  <input type="hidden" name="name" value="<?= $product['name'] ?>">
                  <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']) ?>">
                  <input type="hidden" name="token" value="<?= $_SESSION['csrf_token'] ?>">
                  <div class="swatch clearfix swatch-0 option1" data-option-index="0">
                    <div class="product-form__item">
                      <!-- Initial text for Color label based on PHP variable -->
                      <label class="header">Color: <span class="slVariant" id="slVariant">SELECT COLOR</span></label>
                      <?php 
                      $unique_colors = array_unique(array_column($variants, 'color'));
                      foreach($unique_colors as $color): 
                      ?>
                            <div data-value="<?php echo $color; ?>" class="swatch-element color available <?php echo ($selected_color === $color) ? 'selected' : ''; ?>">
                                <input class="swatchInput" id="swatch-0-<?php echo strtolower($color); ?>" 
                                      type="radio" name="option-0" value="<?php echo $color; ?>" <?php echo ($selected_color === $color) ? 'checked' : ''; ?>
                                      onchange="updateVariantSelection(this.value, '<?php echo $selected_size; ?>')">
                                <label class="swatchLbl color rectangle large" 
                                      for="swatch-0-<?php echo strtolower($color); ?>" 
                                      style="background-color:<?php echo $color; ?>;" 
                                      title="<?php echo $color; ?>"></label>
                            </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <div class="swatch clearfix swatch-1 option2" data-option-index="1">
                    <div class="product-form__item">
                      <!-- Initial text for Size label based on PHP variable -->
                      <label class="header">Size: <span class="slVariant"><?php echo $selected_size ? $selected_size . ' (Select Color)' : 'Select Size'; ?></span></label>
                      <div data-value="XS" class="swatch-element xs available <?php echo ($selected_size === 'XS') ? 'selected' : ''; ?>">
                        <input class="swatchInput" id="swatch-1-xs" type="radio" name="option-1" value="XS" <?php echo ($selected_size === 'XS') ? 'checked' : ''; ?>
                              onchange="updateVariantSelection('<?php echo $selected_color; ?>', this.value)">
                        <label class="swatchLbl large rectangle" for="swatch-1-xs" title="XS">XS</label>
                      </div>
                      <div data-value="S" class="swatch-element s available <?php echo ($selected_size === 'S') ? 'selected' : ''; ?>">
                        <input class="swatchInput" id="swatch-1-s" type="radio" name="option-1" value="S" <?php echo ($selected_size === 'S') ? 'checked' : ''; ?>
                              onchange="updateVariantSelection('<?php echo $selected_color; ?>', this.value)">
                        <label class="swatchLbl large rectangle" for="swatch-1-s" title="S">S</label>
                      </div>
                      <div data-value="M" class="swatch-element m available <?php echo ($selected_size === 'M') ? 'selected' : ''; ?>">
                        <input class="swatchInput" id="swatch-1-m" type="radio" name="option-1" value="M" <?php echo ($selected_size === 'M') ? 'checked' : ''; ?>
                              onchange="updateVariantSelection('<?php echo $selected_color; ?>', this.value)">
                        <label class="swatchLbl large rectangle" for="swatch-1-m" title="M">M</label>
                      </div>
                      <div data-value="L" class="swatch-element l available <?php echo ($selected_size === 'L') ? 'selected' : ''; ?>">
                        <input class="swatchInput" id="swatch-1-l" type="radio" name="option-1" value="L" <?php echo ($selected_size === 'L') ? 'checked' : ''; ?>
                              onchange="updateVariantSelection('<?php echo $selected_color; ?>', this.value)">
                        <label class="swatchLbl large rectangle" for="swatch-1-l" title="L">L</label>
                      </div>
                      <div data-value="XL" class="swatch-element xl available <?php echo ($selected_size === 'XL') ? 'selected' : ''; ?>">
                        <input class="swatchInput" id="swatch-1-xl" type="radio" name="option-1" value="XL" <?php echo ($selected_size === 'XL') ? 'checked' : ''; ?>
                              onchange="updateVariantSelection('<?php echo $selected_color; ?>', this.value)">
                        <label class="swatchLbl large rectangle" for="swatch-1-xl" title="XL">XL</label>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="color" id="selectedColor" value="<?= htmlspecialchars($selected_color) ?>">
                  <input type="hidden" name="size" id="selectedSize" value="<?= htmlspecialchars($selected_size) ?>">
                  
                  <p class="infolinks"><a href="#sizechart" class="sizelink btn"> Size Guide</a> </p>
                  <!-- Product Action -->
                  <div class="product-action clearfix">
                    <div class="product-form__item--quantity">
                      <!-- <div class="quantity-wrap">
                        <label for="qty">Qty</label>
                        <input type="number" id="qty" name="quantity" value="1" min="1" inputmode="numeric">
                      </div> -->
                      <div class="wrapQtyBtn">
                        <div class="qtyField">
                          <a class="qtyBtn minus" href="javascript:void(0);"><i class="fa anm anm-minus-r"
                              aria-hidden="true"></i></a>
                          <input type="text" id="qty" name="quantity" value="1" min="1" inputmode="numeric"
                            class="product-form__input qty">
                          <a class="qtyBtn plus" href="javascript:void(0);"><i class="fa anm anm-plus-r"
                              aria-hidden="true"></i></a>
                        </div>
                      </div>
                      
                    </div>
                    <div class="product-form__item--button">
                      <button type="submit" id="addToCartForm" name="add" class="btn product-form__cart-submit addItemBtn">
                        <span id="AddToCartText-product-template">Add to cart</span>
                      </button>
                      <button type="button" id="orderNowBtn" name="add" class="btn product-form__cart-submit">
                        <span id="AddToCartText-product-template">Order Now!</span>
                      </button>
                    </div>
                  </div>
                  <!-- End Product Action -->
                </form>
                <div class="product-info">
                  <p class="product-type"><span class="lbl">Product Type:</span> <a href="index.php?pages=product&category=women"
                      title="Women's">Women's</a></p>
                  <p class="product-cat"> <span class="lbl">Collections: </span><a href="#" title="">Women</a>
                  </p>
                </div>
              </div>
            </div>
            <!--Product Tabs-->
            <?php include 'includes/layout/body/product-detail-tap.php' ?>
            <!--End Product Tabs-->
          </div>
          <!--Product Sidebar-->
            <?php include 'includes/layout/body/product-detail-sidebar.php' ?>
          <!--Product Sidebar-->
        </div>
      </div>
      <!--End-product-single-->
    </div>
    <!--#ProductSection-product-template-->
  </div>
<script>
// Function to validate required selections
function validateSelections() {
    console.log('Validating selections...');
    const selectedColor = document.querySelector('input[name="option-0"]:checked');
    const selectedSize = document.querySelector('input[name="option-1"]:checked');
    
    console.log('Selected color:', selectedColor ? selectedColor.value : 'none');
    console.log('Selected size:', selectedSize ? selectedSize.value : 'none');
    
    if (!selectedColor) {
        alert("Please select a color");
        return false;
    }
    
    if (!selectedSize) {
        alert("Please select a size");
        return false;
    }
    
    // Update hidden fields with selected values
    const hiddenColor = document.getElementById('selectedColor');
    const hiddenSize = document.getElementById('selectedSize');
    
    if (hiddenColor) {
        hiddenColor.value = selectedColor.value;
        console.log('Set hidden color field to:', hiddenColor.value);
    }
    if (hiddenSize) {
        hiddenSize.value = selectedSize.value;
        console.log('Set hidden size field to:', hiddenSize.value);
    }
    
    console.log('Validation passed');
    return true;
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function() {
    // Auto-select options if only one is available
    const colorInputs = document.querySelectorAll('input[name="option-0"]');
    const sizeInputs = document.querySelectorAll('input[name="option-1"]');
    
    // Auto-select color if only one option
    if (colorInputs.length === 1) {
        colorInputs[0].checked = true;
        updateVariantSelection(colorInputs[0].value, document.querySelector('input[name="option-1"]:checked')?.value || '');
    }
    
    // Auto-select size if only one option
    if (sizeInputs.length === 1) {
        sizeInputs[0].checked = true;
        updateVariantSelection(document.querySelector('input[name="option-0"]:checked')?.value || '', sizeInputs[0].value);
    }

    // Add to cart button handler
    const cartForm = document.getElementById("cartForm");
    if (cartForm) {
        cartForm.addEventListener("submit", async function(e) {
            e.preventDefault(); // This is critical to prevent default form submission
            console.log('Form submission started');
            
            if (!validateSelections()) {
                console.log('Validation failed');
                return false;
            }
            
            // Ensure hidden fields are up to date before submission
            const selectedColor = document.querySelector('input[name="option-0"]:checked');
            const selectedSize = document.querySelector('input[name="option-1"]:checked');
            const hiddenColor = document.getElementById('selectedColor');
            const hiddenSize = document.getElementById('selectedSize');
            
            console.log('Before submission - Selected color:', selectedColor ? selectedColor.value : 'none');
            console.log('Before submission - Selected size:', selectedSize ? selectedSize.value : 'none');
            
            if (selectedColor && hiddenColor) {
                hiddenColor.value = selectedColor.value;
                console.log('Ensured hidden color field is:', hiddenColor.value);
            }
            if (selectedSize && hiddenSize) {
                hiddenSize.value = selectedSize.value;
                console.log('Ensured hidden size field is:', hiddenSize.value);
            }
            
            const formData = new FormData(this);
            
            // Log form data being sent
            console.log('Form data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            try {
                console.log('Adding item to cart...');
                const response = await fetch("pages/sections/add_to_cart.php", {
                    method: "POST",
                    body: formData,
                    credentials: "include"
                });

                console.log('Add to cart response:', response);
                const result = await response.json();
                console.log('Add to cart result:', result);
                if (result.status === "success") {
                    // Update cart count in header immediately
                    const cartCountElement = document.querySelector('#CartCount');
                    if (cartCountElement) {
                        const oldCount = cartCountElement.textContent;
                        cartCountElement.textContent = result.cartCount;
                        console.log('Updated cart count from', oldCount, 'to', result.cartCount);
                    }
                    
                    // Trigger cart update event to refresh mini cart
                    console.log('Dispatching cartUpdated event...');
                    document.dispatchEvent(new Event('cartUpdated'));
                    
                    // Provide visual feedback that item was added
                    const addToCartBtn = document.getElementById("addToCartForm");
                    if (addToCartBtn) {
                        const buttonText = addToCartBtn.querySelector('#AddToCartText-product-template') || 
                                          addToCartBtn.querySelector('span') || 
                                          addToCartBtn;
                        
                        const originalText = buttonText.textContent;
                        buttonText.textContent = "✓ Added to Cart";
                        addToCartBtn.style.backgroundColor = '#28a745'; // Green color
                        addToCartBtn.disabled = true;
                        
                        // Reset button after 3 seconds
                        setTimeout(() => {
                            buttonText.textContent = originalText;
                            addToCartBtn.style.backgroundColor = ''; // Reset to default
                            addToCartBtn.disabled = false;
                        }, 3000);
                    }
                    
                    // Show success message
                    alert("✅ Product added to cart!");
                } else {
                    // Show error message
                    alert("❌ Failed to add to cart: " + (result.message || ""));
                    
                    // Reset button if it was temporarily disabled
                    const addToCartBtn = document.getElementById("addToCartForm");
                    if (addToCartBtn) {
                        addToCartBtn.disabled = false;
                        addToCartBtn.style.backgroundColor = ''; // Reset to default
                    }
                }
            } catch(error) {
                console.error("Error adding to cart:", error);
                alert("❌ Error adding to cart: " + error.message);
                
                // Reset button if it was temporarily disabled
                const addToCartBtn = document.getElementById("addToCartForm");
                if (addToCartBtn) {
                    addToCartBtn.disabled = false;
                    addToCartBtn.style.backgroundColor = ''; // Reset to default
                }
            }
        });
    }

    // Order Now button handler
    const orderNowBtn = document.getElementById("orderNowBtn");
    if (orderNowBtn) {
        orderNowBtn.addEventListener("click", async function() {
            console.log('Order now button clicked');
            if (!validateSelections()) {
                console.log('Validation failed for order now');
                return false;
            }
            
            const form = document.getElementById("cartForm");
            const formData = new FormData(form);

            try {
                console.log('Adding item to cart for order now...');
                const response = await fetch("pages/sections/add_to_cart.php", {
                    method: "POST",
                    body: formData,
                    credentials: "include" 
                });
                
                console.log('Order now response:', response);

                // Redirect to cart page after adding
                console.log('Redirecting to cart page...');
                window.location.href = "index.php?pages=cart";
            } catch(error) {
                console.error("Error processing order:", error);
                alert("❌ Error processing order: " + error.message);
            }
        });
    }
});
</script>
<?php include 'includes/layout/body/product-detail-footer.php' ?>

<?php
// After successfully adding to cart:
?>
<script>
    document.dispatchEvent(new Event('cartUpdated'));
</script>
<?php