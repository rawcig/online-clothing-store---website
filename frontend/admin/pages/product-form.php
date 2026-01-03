
<?php 
session_start();
// Check if user is authenticated
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_GET['id']) ? 'Edit Product' : 'Add Product'; ?></title>
    <style>
        /* Define basic styles here to match the admin panel */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f7;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 {
            color: #1d1d1f;
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: 600;
        }
        
        h2 {
            color: #1d1d1f;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e0e0e0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #1d1d1f;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d1d6;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #0071e3;
            box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.1);
        }
        
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 8px;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: #0071e3;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .btn-secondary {
            background-color: #86868b;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #6e6e73;
        }
        
        .btn-add {
            background-color: #34c759;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-add:hover {
            background-color: #28a745;
        }
        
        .btn-remove, .tag-remove {
            background-color: #ff3b30;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .form-actions {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 20px 0;
            margin-top: 20px;
            border-top: 1px solid #d2d2d7;
            display: flex;
            justify-content: flex-end;
            z-index: 10;
        }
        
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 20px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .image-upload-area {
            border: 2px dashed #d1d1d6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s;
            margin-bottom: 20px;
        }
        
        .image-upload-area:hover {
            border-color: #0071e3;
        }
        
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        
        .image-item {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .image-item img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            display: block;
        }
        
        .image-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0,0,0,0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .spec-input-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .spec-input-row input {
            width: 100%;
        }
        
        .spec-list {
            margin-top: 15px;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #d2d2d7;
            border-radius: 8px;
            margin-bottom: 8px;
            background: #f9f9f9;
        }
        
        .spec-content {
            flex: 1;
        }
        
        .spec-label {
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        
        .tag {
            display: inline-flex;
            align-items: center;
            background: #d8e2dc;
            padding: 6px 10px;
            border-radius: 16px;
            font-size: 14px;
        }
        
        .tag-remove {
            margin-left: 8px;
            padding: 0 4px;
            font-size: 16px;
        }
        
        .price-row {
            display: flex;
            gap: 10px;
        }
        
        .price-row input {
            flex: 1;
        }
        
        .price-row select {
            width: 100px;
        }
        
        .final-price-display {
            background: #f0f0f0;
            padding: 12px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
        
        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #0071e3;
            color: white;
            text-align: center;
            line-height: 16px;
            font-size: 10px;
            cursor: help;
        }
        
        @media (max-width: 768px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .spec-input-row {
                grid-template-columns: 1fr;
            }
            
            .price-row {
                flex-direction: column;
            }
            
            .price-row select {
                width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 id="formTitle"><?php echo isset($_GET['id']) ? 'Edit Product' : 'Add Product'; ?></h1>

        <!-- Main Grid -->
        <div class="main-grid">
            <!-- Left Column -->
            <div>
                <!-- Basic Information -->
                <div class="card">
                    <h2>Basic information</h2>
                    <div class="form-group">
                        <label>Product ID:</label>
                        <input type="text" class="form-control" id="productId" readonly style="background: #f9f9f9; cursor: not-allowed;" placeholder="Auto-generated">
                        <small style="color: #666; font-size: 12px;">Automatically generated unique identifier</small>
                    </div>
                    <div class="form-group">
                        <label>Product name: <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="productName" placeholder="Enter product name">
                    </div>
                    <div class="form-group">
                        <label>Brand: <span style="color: red;">*</span></label>
                        <select class="form-control" id="brand">
                            <option value="">Select Brand</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Stock Keeping Unit (SKU): <span style="color: red;">*</span> <span class="info-icon" title="A unique identifier for inventory tracking, usually alphanumeric code like ABC-12345-S-RED">?</span></label>
                        <input type="text" class="form-control" id="sku" placeholder="Enter SKU (e.g., NKE-TS001-M-BLK)" maxlength="50">
                        <small style="color: #666; font-size: 12px;">Unique code for inventory management (e.g., BRAND-PRODUCT-SIZE-COLOR)</small>
                    </div>
                </div>

                <!-- Add Images -->
                <div class="card">
                    <h2>Add images</h2>
                    <div class="image-upload-area" onclick="document.getElementById('fileInput').click()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24" style="margin-bottom: 10px;">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p style="color: #666; margin-bottom: 8px;">Drag your image here or click to browse</p>
                        <p style="color: #999; font-size: 14px;">Supports JPG, PNG, GIF up to 5MB</p>
                    </div>
                    <input type="file" id="fileInput" multiple accept="image/*" onchange="handleImageUpload(event)" style="display: none;">
                    <div id="imageGrid" class="image-grid"></div>
                </div>

                <!-- Details & Specifications -->
                <div class="card">
                    <h2>Details & Specifications</h2>
                    <div class="form-group">
                        <label>Product description: <span style="color: red;">*</span></label>
                        <textarea class="form-control" id="description" placeholder="Detailed product description" rows="4"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Size Range:</label>
                            <select class="form-control" id="sizeRange">
                                <option value="">Select Size Range</option>
                                <option value="XS">XS - Extra Small</option>
                                <option value="S">S - Small</option>
                                <option value="M">M - Medium</option>
                                <option value="L">L - Large</option>
                                <option value="XL">XL - Extra Large</option>
                                <option value="XXL">XXL - Double Extra Large</option>
                                <option value="XXXL">XXXL - Triple Extra Large</option>
                                <option value="XS-XL">XS to XL</option>
                                <option value="S-XXL">S to XXL</option>
                                <option value="M-XXXL">M to XXXL</option>
                                <option value="One Size">One Size</option>
                                <option value="Custom">Custom Sizes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Product Status: <span style="color: red;">*</span></label>
                            <select class="form-control" id="productStatus">
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                                <option value="discontinued">Discontinued</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Specifications Section -->
                    <div class="specifications-section" style="margin-top: 24px;">
                        <h3 style="font-size: 16px; margin-bottom: 16px; color: #1d1d1f;">Product Specifications</h3>
                        <div class="spec-input-row">
                            <input type="text" class="form-control" id="specLabel" placeholder="Specification name (e.g., Material, Weight)">
                            <input type="text" class="form-control" id="specProperty" placeholder="Specification value (e.g., 100% Cotton, 250g)">
                            <button class="btn-add" onclick="addSpecification()">Add</button>
                        </div>
                        <div id="specList" class="spec-list"></div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Type -->
                <div class="card">
                    <h2>Type</h2>
                    <div class="form-group">
                        <label>Select category: <span style="color: red;">*</span></label>
                        <select class="form-control" id="category">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select sub-category:</label>
                        <select class="form-control" id="subCategory">
                            <option value="">Select</option>
                            <option value="shirts">Shirts</option>
                            <option value="pants">Pants</option>
                            <option value="dresses">Dresses</option>
                            <option value="jackets">Jackets</option>
                            <option value="shoes">Shoes</option>
                        </select>
                    </div>
                </div>

                <!-- Tags -->
                <div class="card">
                    <h2>Tags</h2>
                    <div class="form-group">
                        <label>Add a keyword:</label>
                        <div class="form-row" style="align-items: center;">
                            <input type="text" class="form-control" id="tagInput" placeholder="Enter tag and press Enter or click Add">
                            <button class="btn-add" onclick="addTag()">Add</button>
                        </div>
                        <div id="tagsContainer" class="tags-container"></div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="card">
                    <h2>Pricing</h2>
                    <div class="form-group">
                        <label>Base Price: <span style="color: red;">*</span> <span class="info-icon" title="Original price before any discounts">?</span></label>
                        <div class="price-row">
                            <input type="number" class="form-control" id="basePrice" placeholder="0.00" step="0.01" min="0" oninput="calculateFinalPrice()">
                            <select class="form-control currency-select" id="currency">
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="GBP">GBP (£)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Discount in percentage:</label>
                        <input type="number" class="form-control" id="discount" placeholder="0" min="0" max="100" oninput="calculateFinalPrice()">%
                    </div>
                    <div class="form-group">
                        <label>Final price: <span class="info-icon" title="Price after applying discount">?</span></label>
                        <div class="final-price-display" id="finalPrice">0.00</div>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="card">
                    <h2>Stock status</h2>
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="stock" value="in-stock" checked>
                            <span>In stock</span>
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="stock" value="unavailable">
                            <span>Unavailable</span>
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="stock" value="announced">
                            <span>To be announced</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button class="btn btn-secondary" onclick="handleCancel()">Cancel</button>
            <button class="btn btn-primary" onclick="handleSubmit()" id="submitBtn"><?php echo isset($_GET['id']) ? 'Update Product' : 'Add Product'; ?></button>
        </div>
    </div>
</body>

<script>
// Product Form JavaScript
class ProductForm {
    constructor() {
        this.productData = {
            images: [],
            specifications: [],
            tags: []
        };
        this.isEditMode = false;
        this.editingProductId = null;
        this.init();
    }

    async init() {
        await this.loadCategoriesAndBrands();
        this.checkEditMode();
        this.bindEvents();
        this.calculateFinalPrice();
        // Generate Product ID for new products
        if (!this.isEditMode) {
            this.generateProductId();
        }
    }

    // Generate unique Product ID
    generateProductId() {
        const timestamp = Date.now().toString();
        const random = Math.random().toString(36).substr(2, 3).toUpperCase();
        const productId = `PRD${timestamp.slice(-6)}${random}`;
        document.getElementById('productId').value = productId;
    }

    async loadCategoriesAndBrands() {
        try {
            // Load categories
            const categoriesResponse = await fetch('../api/data.php?endpoint=categories');
            const categories = await categoriesResponse.json();
            
            const categorySelect = document.getElementById('category');
            categorySelect.innerHTML = '<option value="">Select</option>';
            categories.forEach(cat => {
                // Map database category names to frontend slugs
                const categoryMap = {
                    'Clothing': 'clothing',
                    'Men': 'mens',
                    'Women': 'womens',
                    'Kids': 'kids',
                    'Accessories': 'accessories'
                };
                
                const value = categoryMap[cat.name] || cat.name.toLowerCase();
                const option = document.createElement('option');
                option.value = value;
                option.textContent = cat.name;
                categorySelect.appendChild(option);
            });
            
            // Load brands
            const brandsResponse = await fetch('../api/data.php?endpoint=brands');
            const brands = await brandsResponse.json();
            
            const brandSelect = document.getElementById('brand');
            brandSelect.innerHTML = '<option value="">Select Brand</option>';
            brands.forEach(brand => {
                const option = document.createElement('option');
                option.value = brand.name;
                option.textContent = brand.name;
                brandSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading categories and brands:', error);
        }
    }

    checkEditMode() {
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id');
        
        if (productId) {
            this.isEditMode = true;
            this.editingProductId = productId;
            document.getElementById('formTitle').textContent = 'Edit Product';
            document.getElementById('submitBtn').textContent = 'Update Product';
            this.loadProductData(productId);
        }
    }

    bindEvents() {
        // Allow Enter key to add tags and specs
        document.getElementById('tagInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.addTag();
            }
        });

        document.getElementById('specProperty').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.addSpecification();
            }
        });

        // Form validation
        const requiredFields = ['productName', 'brand', 'sku', 'description', 'category', 'basePrice'];
        requiredFields.forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('input', this.validateForm.bind(this));
        });
    }

    async loadProductData(productId) {
        try {
            const response = await fetch(`../api/products.php?id=${productId}`);
            const product = await response.json();
            
            if (product) {
                this.populateForm(product);
            }
        } catch (error) {
            console.error('Error loading product data:', error);
            alert('Error loading product data');
        }
    }

    populateForm(product) {
        // Basic information
        document.getElementById('productId').value = product.id || '';
        document.getElementById('productName').value = product.name || '';
        document.getElementById('brand').value = product.brand || '';
        document.getElementById('sku').value = product.sku || '';
        document.getElementById('description').value = product.description || '';
        
        // Categories
        document.getElementById('category').value = product.category || '';
        // We'll need to handle subcategory separately if needed
        
        // Details
        document.getElementById('sizeRange').value = product.sizeRange || '';
        document.getElementById('productStatus').value = product.productStatus || 'active';
        
        // Pricing
        document.getElementById('basePrice').value = product.basePrice || '';
        document.getElementById('currency').value = product.currency || 'USD';
        document.getElementById('discount').value = product.discount || 0;
        
        // Stock status
        if (product.stockStatus) {
            const stockRadio = document.querySelector(`input[name="stock"][value="${product.stockStatus}"]`);
            if (stockRadio) stockRadio.checked = true;
        }
        
        // Images
        if (product.images && product.images.length > 0) {
            this.productData.images = [...product.images];
            this.renderImages();
        }
        
        // Specifications
        if (product.specifications && product.specifications.length > 0) {
            this.productData.specifications = [...product.specifications];
            this.renderSpecifications();
        }
        
        // Tags - for now, use empty array since our schema doesn't include tags
        this.productData.tags = product.tags || [];
        this.renderTags();
        
        // Calculate final price
        this.calculateFinalPrice();
    }

    // Handle image upload
    async handleImageUpload(event) {
        const files = event.target.files;
        
        for (let file of files) {
            // Show loading state
            const imageGrid = document.getElementById('imageGrid');
            const loadingElement = document.createElement('div');
            loadingElement.className = 'image-item';
            loadingElement.innerHTML = '<div>Uploading...</div>';
            imageGrid.appendChild(loadingElement);
            
            try {
                const formData = new FormData();
                formData.append('image', file);
                
                const response = await fetch('../api/upload.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Remove the loading element and add the uploaded image
                    imageGrid.removeChild(loadingElement);
                    this.productData.images.push(result.imagePath);
                    this.renderImages();
                } else {
                    throw new Error(result.error || 'Upload failed');
                }
            } catch (error) {
                console.error('Image upload error:', error);
                alert('Error uploading image: ' + error.message);
                // Remove the loading element if upload failed
                if (imageGrid.contains(loadingElement)) {
                    imageGrid.removeChild(loadingElement);
                }
            }
        }
    }

    // Remove image
    removeImage(index) {
        this.productData.images.splice(index, 1);
        this.renderImages();
    }

    // Render images
    renderImages() {
        const imageGrid = document.getElementById('imageGrid');
        imageGrid.innerHTML = '';
        
        this.productData.images.forEach((img, index) => {
            const imageItem = document.createElement('div');
            imageItem.className = 'image-item';
            imageItem.innerHTML = `
                <img src="${img}" alt="Product" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
                <button class="btn" style="background: #ff3b30; color: white; border: none; border-radius: 4px; padding: 2px 6px; cursor: pointer;" onclick="productForm.removeImage(${index})">X</button>
            `;
            imageGrid.appendChild(imageItem);
        });
    }

    // Add specification
    addSpecification() {
        const label = document.getElementById('specLabel').value.trim();
        const property = document.getElementById('specProperty').value.trim();
        
        if (label && property) {
            this.productData.specifications.push({ label, property });
            this.renderSpecifications();
            document.getElementById('specLabel').value = '';
            document.getElementById('specProperty').value = '';
        }
    }

    // Remove specification
    removeSpecification(index) {
        this.productData.specifications.splice(index, 1);
        this.renderSpecifications();
    }

    // Render specifications
    renderSpecifications() {
        const specList = document.getElementById('specList');
        specList.innerHTML = '';
        
        this.productData.specifications.forEach((spec, index) => {
            const specItem = document.createElement('div');
            specItem.className = 'spec-item';
            specItem.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; border: 1px solid #d2d2d7; border-radius: 4px; margin-bottom: 8px;">
                    <div style="flex: 1;">
                        <strong>${spec.label}:</strong> ${spec.property}
                    </div>
                    <button class="btn" style="background: #ff3b30; color: white; border: none; border-radius: 4px; padding: 4px 8px; cursor: pointer;" onclick="productForm.removeSpecification(${index})">Remove</button>
                </div>
            `;
            specList.appendChild(specItem);
        });
    }

    // Add tag
    addTag() {
        const tagInput = document.getElementById('tagInput');
        const tag = tagInput.value.trim();
        
        if (tag && !this.productData.tags.includes(tag)) {
            this.productData.tags.push(tag);
            this.renderTags();
            tagInput.value = '';
        }
    }

    // Remove tag
    removeTag(index) {
        this.productData.tags.splice(index, 1);
        this.renderTags();
    }

    // Render tags
    renderTags() {
        const tagsContainer = document.getElementById('tagsContainer');
        tagsContainer.innerHTML = '';
        
        this.productData.tags.forEach((tag, index) => {
            const tagElement = document.createElement('div');
            tagElement.className = 'tag';
            tagElement.style = 'display: inline-block; background: #e0e0e0; padding: 4px 8px; margin: 4px; border-radius: 12px;';
            tagElement.innerHTML = `
                ${tag}
                <button class="tag-remove" style="background: none; border: none; color: #ff3b30; cursor: pointer; margin-left: 5px;" onclick="productForm.removeTag(${index})">×</button>
            `;
            tagsContainer.appendChild(tagElement);
        });
    }

    // Calculate final price
    calculateFinalPrice() {
        const basePrice = parseFloat(document.getElementById('basePrice').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const finalPrice = basePrice - (basePrice * discount / 100);
        
        document.getElementById('finalPrice').textContent = finalPrice.toFixed(2);
    }

    // Validate form
    validateForm() {
        const requiredFields = {
            'productName': 'Product name is required',
            'brand': 'Brand is required',
            'sku': 'SKU is required',
            'description': 'Product description is required',
            'category': 'Category is required',
            'basePrice': 'Base price is required'
        };

        let isValid = true;
        const errors = [];

        for (const [fieldId, errorMessage] of Object.entries(requiredFields)) {
            const field = document.getElementById(fieldId);
            const value = field.value.trim();
            
            // Remove previous error styling
            field.style.borderColor = '';
            
            if (!value) {
                field.style.borderColor = '#ff3b30';
                errors.push(errorMessage);
                isValid = false;
            }
        }

        // Validate SKU format (basic validation)
        const sku = document.getElementById('sku').value.trim();
        if (sku && !/^[A-Z0-9\\-_]{3,50}$/i.test(sku)) {
            document.getElementById('sku').style.borderColor = '#ff3b30';
            errors.push('SKU must be 3-50 characters and contain only letters, numbers, hyphens, and underscores');
            isValid = false;
        }

        // Validate base price is a positive number
        const basePrice = parseFloat(document.getElementById('basePrice').value);
        if (isNaN(basePrice) || basePrice <= 0) {
            document.getElementById('basePrice').style.borderColor = '#ff3b30';
            if (!errors.includes('Base price must be a positive number')) {
                errors.push('Base price must be a positive number');
            }
            isValid = false;
        }

        return isValid;
    }

    // Collect all form data
    collectFormData() {
        return {
            id: document.getElementById('productId').value.trim(),
            name: document.getElementById('productName').value.trim(),
            brand: document.getElementById('brand').value,
            sku: document.getElementById('sku').value.trim(),
            description: document.getElementById('description').value.trim(),
            category: document.getElementById('category').value,
            subCategory: document.getElementById('subCategory').value,
            tags: this.productData.tags,
            basePrice: parseFloat(document.getElementById('basePrice').value),
            currency: document.getElementById('currency').value,
            discount: parseFloat(document.getElementById('discount').value) || 0,
            finalPrice: parseFloat(document.getElementById('finalPrice').textContent),
            sizeRange: document.getElementById('sizeRange').value,
            productStatus: document.getElementById('productStatus').value || 'active',
            stockStatus: document.querySelector('input[name="stock"]:checked').value,
            specifications: this.productData.specifications,
            images: this.productData.images
        };
    }

    // Handle form submission
    async handleSubmit() {
        if (!this.validateForm()) {
            alert('Please fill in all required fields correctly.');
            return;
        }

        const formData = this.collectFormData();
        
        try {
            let response;
            if (this.isEditMode) {
                // Update existing product
                response = await fetch('../api/products.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({id: this.editingProductId, ...formData})
                });
                
                if (response.ok) {
                    alert('Product updated successfully!');
                    // Close the modal by calling parent function if available
                    if (window.parent && window.parent.closeModal) {
                        window.parent.closeModal();
                    } else {
                        // If iframe is loaded directly, just reload parent
                        window.close();
                    }
                } else {
                    throw new Error('Failed to update product');
                }
            } else {
                // Create new product
                response = await fetch('../api/products.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                if (response.ok) {
                    alert('Product added successfully!');
                    // Close the modal by calling parent function if available
                    if (window.parent && window.parent.closeModal) {
                        window.parent.closeModal();
                    } else {
                        // If iframe is loaded directly, just reload parent
                        window.close();
                    }
                } else {
                    throw new Error('Failed to add product');
                }
            }
            
        } catch (error) {
            console.error('Error saving product:', error);
            alert('Error saving product. Please try again.');
        }
    }

    // Handle cancel
    handleCancel() {
        if (this.hasUnsavedChanges()) {
            if (confirm('You have unsaved changes. Are you sure you want to cancel?')) {
                // Close the modal by calling parent function if available
                if (window.parent && window.parent.closeModal) {
                    window.parent.closeModal();
                } else {
                    window.close();
                }
            }
        } else {
            // Close the modal by calling parent function if available
            if (window.parent && window.parent.closeModal) {
                window.parent.closeModal();
            } else {
                window.close();
            }
        }
    }

    // Check for unsaved changes
    hasUnsavedChanges() {
        const currentData = this.collectFormData();
        
        // Simple check - in a real app, you'd compare with original data
        return currentData.name || currentData.description || 
               this.productData.images.length > 0 || 
               this.productData.specifications.length > 0 || 
               this.productData.tags.length > 0;
    }
}

// Global instance
let productForm;

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    productForm = new ProductForm();
});

// Global functions for HTML onclick handlers
function handleImageUpload(event) {
    productForm.handleImageUpload(event);
}

function addSpecification() {
    productForm.addSpecification();
}

function addTag() {
    productForm.addTag();
}

function calculateFinalPrice() {
    productForm.calculateFinalPrice();
}

function handleSubmit() {
    productForm.handleSubmit();
}

function handleCancel() {
    productForm.handleCancel();
}
</script>
</body>
</html>
