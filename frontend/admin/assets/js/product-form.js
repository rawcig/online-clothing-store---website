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
            this.loadProductData(productId);
            document.getElementById('formTitle').textContent = 'Edit Product';
            document.getElementById('submitBtn').textContent = 'Update Product';
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
            // Get product data from parent window's ProductAPI
            const product = await parent.ProductAPI.getProduct(productId);
            
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
        document.getElementById('subCategory').value = product.subCategory || '';
        
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
        
        // Tags
        if (product.tags && product.tags.length > 0) {
            this.productData.tags = [...product.tags];
            this.renderTags();
        }
        
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
                <img src="${img}" alt="Product">
                <button class="image-remove" onclick="productForm.removeImage(${index})">×</button>
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
                <div class="spec-content">
                    <div class="spec-label">${spec.label}</div>
                    <div class="spec-property">${spec.property}</div>
                </div>
                <button class="btn-remove" onclick="productForm.removeSpecification(${index})">Remove</button>
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
            tagElement.innerHTML = `
                ${tag}
                <button class="tag-remove" onclick="productForm.removeTag(${index})">×</button>
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
        if (sku && !/^[A-Z0-9\-_]{3,50}$/i.test(sku)) {
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
            let result;
            if (this.isEditMode) {
                result = await parent.ProductAPI.updateProduct(this.editingProductId, formData);
                if (result) {
                    alert('Product updated successfully!');
                } else {
                    throw new Error('Failed to update product');
                }
            } else {
                result = await parent.ProductAPI.createProduct(formData);
                alert('Product added successfully!');
            }
            
            // Close the modal by calling parent function
            parent.closeModal();
            
        } catch (error) {
            console.error('Error saving product:', error);
            alert('Error saving product. Please try again.');
        }
    }

    // Handle cancel
    handleCancel() {
        if (this.hasUnsavedChanges()) {
            if (confirm('You have unsaved changes. Are you sure you want to cancel?')) {
                parent.closeModal();
            }
        } else {
            parent.closeModal();
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
