// Product Management System
class ProductManager {
    constructor() {
        this.products = [];
        this.currentPage = 1;
        this.itemsPerPage = 10;
        this.currentFilter = {};
        this.init();
    }

    async init() {
        await this.loadProducts();
        this.renderProducts();
        this.updatePagination();
    }

    // Load products from API
    async loadProducts() {
        try {
            const params = new URLSearchParams();
            
            // Add filter parameters if they exist
            if (this.currentFilter.search) params.append('search', this.currentFilter.search);
            if (this.currentFilter.category) params.append('category', this.currentFilter.category);
            if (this.currentFilter.brand) params.append('brand', this.currentFilter.brand);
            if (this.currentFilter.status) params.append('status', this.currentFilter.status);
            
            const response = await fetch(`../api/products.php?${params}`);
            this.products = await response.json();
            
            // Update pagination
            this.updatePagination();
        } catch (error) {
            console.error('Error loading products:', error);
            alert('Error loading products');
            this.products = [];
        }
    }

    // Add new product
    async addProduct(productData) {
        try {
            const response = await fetch('../api/products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productData)
            });
            
            if (!response.ok) {
                throw new Error('Failed to add product');
            }
            
            const newProduct = await response.json();
            this.products.unshift(newProduct);
            this.renderProducts();
            this.updatePagination();
            return newProduct;
        } catch (error) {
            console.error('Error adding product:', error);
            alert('Error adding product');
            return null;
        }
    }

    // Update existing product
    async updateProduct(productId, productData) {
        try {
            const response = await fetch(`../api/products.php`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({id: productId, ...productData})
            });
            
            if (!response.ok) {
                throw new Error('Failed to update product');
            }
            
            const updatedProduct = await response.json();
            const index = this.products.findIndex(p => parseInt(p.id) == parseInt(productId));
            if (index !== -1) {
                this.products[index] = updatedProduct;
            }
            this.renderProducts();
            return updatedProduct;
        } catch (error) {
            console.error('Error updating product:', error);
            alert('Error updating product');
            return null;
        }
    }

    // Delete product
    async deleteProduct(productId) {
        if (!confirm('Are you sure you want to delete this product?')) {
            return;
        }
        
        try {
            const response = await fetch(`../api/products.php`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({id: productId})
            });
            
            if (!response.ok) {
                throw new Error('Failed to delete product');
            }
            
            const result = await response.json();
            if (result.success) {
                this.products = this.products.filter(p => parseInt(p.id) != parseInt(productId));
                this.renderProducts();
                this.updatePagination();
            } else {
                throw new Error(result.message || 'Failed to delete product');
            }
        } catch (error) {
            console.error('Error deleting product:', error);
            alert('Error deleting product');
        }
    }

    // Get product by ID
    async getProduct(productId) {
        try {
            const response = await fetch(`../api/products.php?id=${productId}`);
            return await response.json();
        } catch (error) {
            console.error('Error getting product:', error);
            return null;
        }
    }

    // Filter products
    filterProducts() {
        const searchTerm = document.getElementById('searchInput').value;
        const categoryFilter = document.getElementById('categoryFilter').value;
        const brandFilter = document.getElementById('brandFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;

        this.currentFilter = {
            search: searchTerm,
            category: categoryFilter,
            brand: brandFilter,
            status: statusFilter
        };

        this.currentPage = 1;
        this.loadProducts(); // Reload products from API with filters
    }

    // Get filtered products (now just returns the loaded products since filtering is done on server)
    getFilteredProducts() {
        return this.products;
    }

    // Render products table
    renderProducts() {
        const tbody = document.getElementById('productsTableBody');
        const filteredProducts = this.getFilteredProducts();
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        const pageProducts = filteredProducts.slice(startIndex, endIndex);

        tbody.innerHTML = '';

        if (pageProducts.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" style="text-align: center; padding: 40px; color: #666;">
                        No products found
                    </td>
                </tr>
            `;
            return;
        }

        pageProducts.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="product-id">${product.id}</div>
                </td>
                <td>
                    <div style="font-family: monospace; font-size: 12px; color: #666;">${product.sku || 'N/A'}</div>
                </td>
                <td>
                    <img src="${product.images[0] || 'https://via.placeholder.com/60x60'}" 
                         alt="${product.name}" class="product-image">
                </td>
                <td>
                    <div class="product-name">${product.name}</div>
                </td>
                <td>${product.brand}</td>
                <td>${this.getCategoryDisplayName(product.category)}</td>
                <td>$${product.finalPrice}</td>
                <td>
                    <span class="status-badge status-${product.productStatus}">
                        ${this.capitalizeFirst(product.productStatus)}
                    </span>
                </td>
                <td>
                    <span class="stock-status stock-${product.stockStatus === 'in-stock' ? 'in' : 'out'}">
                        ${this.getStockDisplayName(product.stockStatus)}
                    </span>
                </td>
                <td>
                    <div class="actions-cell">
                        <button class="btn btn-edit" onclick="editProduct('${product.id}')">Edit</button>
                        <button class="btn btn-delete" onclick="confirmDelete('${product.id}')">Delete</button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Update pagination
    updatePagination() {
        const filteredProducts = this.getFilteredProducts();
        const totalPages = Math.ceil(filteredProducts.length / this.itemsPerPage);
        
        document.getElementById('pageInfo').textContent = `Page ${this.currentPage} of ${totalPages}`;
        document.getElementById('prevBtn').disabled = this.currentPage === 1;
        document.getElementById('nextBtn').disabled = this.currentPage === totalPages || totalPages === 0;
    }

    // Change page
    changePage(direction) {
        const filteredProducts = this.getFilteredProducts();
        const totalPages = Math.ceil(filteredProducts.length / this.itemsPerPage);
        
        if (direction === -1 && this.currentPage > 1) {
            this.currentPage--;
        } else if (direction === 1 && this.currentPage < totalPages) {
            this.currentPage++;
        }
        
        this.renderProducts();
        this.updatePagination();
    }

    // Helper functions
    getCategoryDisplayName(category) {
        const categories = {
            'mens': "Men's Clothing",
            'womens': "Women's Clothing",
            'kids': "Kids Clothing",
            'accessories': "Accessories"
        };
        return categories[category] || category;
    }

    getStockDisplayName(status) {
        const statuses = {
            'in-stock': 'In Stock',
            'unavailable': 'Out of Stock',
            'announced': 'Coming Soon'
        };
        return statuses[status] || status;
    }

    capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Export products to CSV
    exportProducts() {
        const filteredProducts = this.getFilteredProducts();
        let csv = 'Product ID,SKU,Name,Brand,Category,Price,Status,Stock,Created Date\n';
        
        filteredProducts.forEach(product => {
            csv += `"${product.id}","${product.sku || 'N/A'}","${product.name}","${product.brand}","${this.getCategoryDisplayName(product.category)}","$${product.finalPrice}","${this.capitalizeFirst(product.productStatus)}","${this.getStockDisplayName(product.stockStatus)}","${product.createdAt}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `products_${new Date().toISOString().split('T')[0]}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
    }
}

// Global variables and functions
let productManager;
let currentEditingProductId = null;

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    productManager = new ProductManager();
});

// Global functions for UI interactions
function filterProducts() {
    productManager.filterProducts();
}

function changePage(direction) {
    productManager.changePage(direction);
}

function showAddProduct() {
    currentEditingProductId = null;
    const modal = document.getElementById('productModal');
    const iframe = document.getElementById('productForm');
    // iframe.src = '../../pages/product-form.php';
    modal.style.display = 'block';
}

function editProduct(productId) {
    currentEditingProductId = productId;
    const modal = document.getElementById('productModal');
    const iframe = document.getElementById('productForm');
    iframe.src = `../../pages/product-form.php?id=${productId}`;
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
    // Refresh the products list in case changes were made
    productManager.renderProducts();
}

function confirmDelete(productId) {
    const product = productManager.getProduct(productId);
    document.getElementById('confirmMessage').textContent = 
        `Are you sure you want to delete "${product.name}"? This action cannot be undone.`;
    
    const confirmBtn = document.getElementById('confirmBtn');
    confirmBtn.onclick = () => {
        productManager.deleteProduct(productId);
        closeConfirmModal();
    };
    
    document.getElementById('confirmModal').style.display = 'block';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
}

function exportProducts() {
    productManager.exportProducts();
}

// Close modals when clicking outside
window.onclick = function(event) {
    const productModal = document.getElementById('productModal');
    const confirmModal = document.getElementById('confirmModal');
    
    if (event.target === productModal) {
        closeModal();
    }
    if (event.target === confirmModal) {
        closeConfirmModal();
    }
}

// API functions for integration with backend
const ProductAPI = {
    async getProducts(params = {}) {
        const urlParams = new URLSearchParams(params);
        const response = await fetch(`../api/products.php?${urlParams}`);
        return response.json();
    },

    async getProduct(id) {
        const response = await fetch(`../api/products.php?id=${id}`);
        return response.json();
    },

    async createProduct(productData) {
        const response = await fetch('../api/products.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(productData)
        });
        return response.json();
    },

    async updateProduct(id, productData) {
        const response = await fetch('../api/products.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({id: id, ...productData})
        });
        return response.json();
    },

    async deleteProduct(id) {
        const response = await fetch('../api/products.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({id: id})
        });
        return response.json();
    }
};

// Make ProductAPI available globally for integration
window.ProductAPI = ProductAPI;
