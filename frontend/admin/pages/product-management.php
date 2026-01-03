
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            margin-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .search-bar input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .filter-controls {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .filter-controls select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .products-table th,
        .products-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .products-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        .products-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-draft {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-discontinued {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .stock-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .stock-in {
            background-color: #d4edda;
            color: #155724;
        }
        
        .stock-out {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .actions-cell {
            display: flex;
            gap: 8px;
        }
        
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-edit {
            background-color: #007bff;
            color: white;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .pagination button {
            padding: 8px 16px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .pagination button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 2% auto;
            padding: 0;
            border-radius: 10px;
            width: 90%;
            max-width: 1000px;
            max-height: 95vh;
            position: relative;
            overflow: hidden;
        }
        
        .modal-content small {
            width: 90%;
            max-width: 600px;
            max-height: 95vh;
        }
        
        .close {
            position: absolute;
            right: 15px;
            top: 15px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            z-index: 1001;
            cursor: pointer;
        }
        
        .close:hover {
            color: #000;
        }
        
        #productForm {
            width: 100%;
            height: 85vh;
            border: none;
        }
        
        .no-products {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>

    <!-- Header -->
    <div class="header">
        <h1>Product Management</h1>
        <div class="header-actions">
            <span style="color: white; margin-right: 15px;">Welcome, <?php echo $_SESSION['admin_username'] ?? 'Admin'; ?>!</span>
            <a href="../index.php?pages=logout" style="color: white; text-decoration: none; margin-right: 15px;">Logout</a>
            <button class="btn btn-primary" onclick="showAddProduct()" style="background-color: #28a745; color: white;">Add New Product</button>
            <button class="btn btn-secondary" onclick="exportProducts()" style="background-color: #6c757d; color: white;">Export</button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="filters-section">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search products..." oninput="filterProducts()">
        </div>
        <div class="filter-controls">
            <select id="categoryFilter" onchange="filterProducts()">
                <option value="">All Categories</option>
                <option value="mens">Men's Clothing</option>
                <option value="womens">Women's Clothing</option>
                <option value="kids">Kids Clothing</option>
                <option value="accessories">Accessories</option>
            </select>
            <select id="brandFilter" onchange="filterProducts()">
                <option value="">All Brands</option>
                <option value="Apple">Apple</option>
                <option value="Samsung">Samsung</option>
                <option value="Nike">Nike</option>
                <option value="Adidas">Adidas</option>
            </select>
            <select id="statusFilter" onchange="filterProducts()">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="draft">Draft</option>
                <option value="discontinued">Discontinued</option>
            </select>
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-container">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>SKU</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productsTableBody">
                <!-- Products will be loaded here -->
                <tr>
                    <td colspan="10" class="no-products">
                        Loading products...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <button id="prevBtn" onclick="changePage(-1)">Previous</button>
        <span id="pageInfo">Page 1 of 1</span>
        <button id="nextBtn" onclick="changePage(1)">Next</button>
    </div>

    <!-- Modal for Add/Edit Product -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <iframe id="productForm" src="" frameborder="0"></iframe>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content small">
            <div style="padding: 20px;">
                <h3 style="margin-top: 0;">Confirm Action</h3>
                <p id="confirmMessage"></p>
                <div class="modal-actions" style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
                    <button class="btn btn-secondary" onclick="closeConfirmModal()" style="background-color: #6c757d; color: white;">Cancel</button>
                    <button class="btn btn-danger" id="confirmBtn" style="background-color: #dc3545; color: white;">Confirm</button>
                </div>
            </div>
        </div>
    </div>

