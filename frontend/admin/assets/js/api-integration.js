// API Configuration and Integration Layer
// This file provides the interface between the frontend and backend API

class EcommerceAPI {
    constructor() {
        // Configure your API base URL here
        this.baseURL = '../api/products.php'; // Updated to connect to PHP backend
        this.apiKey = null; // Set if you need API key authentication
        this.authToken = null; // Set for JWT or bearer token authentication
    }

    // Set authentication token
    setAuthToken(token) {
        this.authToken = token;
    }

    // Set API key
    setApiKey(key) {
        this.apiKey = key;
    }

    // Generic API request method
    async makeRequest(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        
        const config = {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        };

        // Add authentication headers
        if (this.authToken) {
            config.headers['Authorization'] = `Bearer ${this.authToken}`;
        }
        
        if (this.apiKey) {
            config.headers['X-API-Key'] = this.apiKey;
        }

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API request failed:', error);
            throw error;
        }
    }

    // Product API methods
    async getProducts(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = `/products${queryString ? `?${queryString}` : ''}`;
        return this.makeRequest(endpoint);
    }

    async getProduct(id) {
        return this.makeRequest(`/products/${id}`);
    }

    async createProduct(productData) {
        return this.makeRequest('/products', {
            method: 'POST',
            body: JSON.stringify(productData)
        });
    }

    async updateProduct(id, productData) {
        return this.makeRequest(`/products/${id}`, {
            method: 'PUT',
            body: JSON.stringify(productData)
        });
    }

    async deleteProduct(id) {
        return this.makeRequest(`/products/${id}`, {
            method: 'DELETE'
        });
    }

    // Image upload method
    async uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);

        return this.makeRequest('/upload/image', {
            method: 'POST',
            headers: {
                // Don't set Content-Type for FormData, let browser set it
            },
            body: formData
        });
    }

    // Category API methods
    async getCategories() {
        return this.makeRequest('/categories');
    }

    async createCategory(categoryData) {
        return this.makeRequest('/categories', {
            method: 'POST',
            body: JSON.stringify(categoryData)
        });
    }

    // Brand API methods
    async getBrands() {
        return this.makeRequest('/brands');
    }

    async createBrand(brandData) {
        return this.makeRequest('/brands', {
            method: 'POST',
            body: JSON.stringify(brandData)
        });
    }

    // Analytics methods
    async getProductAnalytics(productId) {
        return this.makeRequest(`/analytics/products/${productId}`);
    }

    async getDashboardStats() {
        return this.makeRequest('/analytics/dashboard');
    }
}

// Database Integration Examples
// These are examples of how to structure your backend API

/*
// Example Express.js routes for Node.js backend

const express = require('express');
const multer = require('multer');
const path = require('path');
const router = express.Router();

// Configure multer for image uploads
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, 'uploads/images/');
    },
    filename: (req, file, cb) => {
        const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
        cb(null, 'product-' + uniqueSuffix + path.extname(file.originalname));
    }
});

const upload = multer({ 
    storage: storage,
    limits: { fileSize: 5 * 1024 * 1024 }, // 5MB limit
    fileFilter: (req, file, cb) => {
        if (file.mimetype.startsWith('image/')) {
            cb(null, true);
        } else {
            cb(new Error('Only image files are allowed'));
        }
    }
});

// Product routes
router.get('/products', async (req, res) => {
    try {
        const { page = 1, limit = 10, category, brand, status, search } = req.query;
        
        // Build query conditions
        let whereConditions = {};
        
        if (category) whereConditions.category = category;
        if (brand) whereConditions.brand = brand;
        if (status) whereConditions.productStatus = status;
        if (search) {
            whereConditions[Op.or] = [
                { name: { [Op.iLike]: `%${search}%` } },
                { description: { [Op.iLike]: `%${search}%` } },
                { id: { [Op.iLike]: `%${search}%` } }
            ];
        }

        const products = await Product.findAndCountAll({
            where: whereConditions,
            limit: parseInt(limit),
            offset: (parseInt(page) - 1) * parseInt(limit),
            order: [['updatedAt', 'DESC']]
        });

        res.json({
            products: products.rows,
            total: products.count,
            page: parseInt(page),
            totalPages: Math.ceil(products.count / parseInt(limit))
        });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

router.get('/products/:id', async (req, res) => {
    try {
        const product = await Product.findByPk(req.params.id);
        if (!product) {
            return res.status(404).json({ error: 'Product not found' });
        }
        res.json(product);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

router.post('/products', async (req, res) => {
    try {
        const product = await Product.create(req.body);
        res.status(201).json(product);
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});

router.put('/products/:id', async (req, res) => {
    try {
        const [updated] = await Product.update(req.body, {
            where: { id: req.params.id }
        });
        
        if (updated) {
            const product = await Product.findByPk(req.params.id);
            res.json(product);
        } else {
            res.status(404).json({ error: 'Product not found' });
        }
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});

router.delete('/products/:id', async (req, res) => {
    try {
        const deleted = await Product.destroy({
            where: { id: req.params.id }
        });
        
        if (deleted) {
            res.json({ message: 'Product deleted successfully' });
        } else {
            res.status(404).json({ error: 'Product not found' });
        }
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Image upload route
router.post('/upload/image', upload.single('image'), (req, res) => {
    if (!req.file) {
        return res.status(400).json({ error: 'No image file provided' });
    }
    
    const imageUrl = `/uploads/images/${req.file.filename}`;
    res.json({ imageUrl });
});

module.exports = router;
*/

/*
// Example Sequelize model for PostgreSQL/MySQL

const { DataTypes } = require('sequelize');

const Product = sequelize.define('Product', {
    id: {
        type: DataTypes.STRING,
        primaryKey: true,
        defaultValue: () => 'PRD' + Date.now().toString().slice(-6) + Math.random().toString(36).substr(2, 3).toUpperCase()
    },
    name: {
        type: DataTypes.STRING,
        allowNull: false,
        validate: {
            notEmpty: true,
            len: [1, 255]
        }
    },
    brand: {
        type: DataTypes.STRING,
        allowNull: false
    },
    identificationNo: {
        type: DataTypes.STRING,
        unique: true
    },
    description: {
        type: DataTypes.TEXT,
        allowNull: false
    },
    category: {
        type: DataTypes.STRING,
        allowNull: false
    },
    subCategory: {
        type: DataTypes.STRING
    },
    basePrice: {
        type: DataTypes.DECIMAL(10, 2),
        allowNull: false,
        validate: {
            min: 0
        }
    },
    currency: {
        type: DataTypes.STRING,
        defaultValue: 'USD'
    },
    discount: {
        type: DataTypes.INTEGER,
        defaultValue: 0,
        validate: {
            min: 0,
            max: 100
        }
    },
    finalPrice: {
        type: DataTypes.DECIMAL(10, 2),
        allowNull: false
    },
    sizeRange: {
        type: DataTypes.STRING
    },
    productStatus: {
        type: DataTypes.ENUM('active', 'inactive', 'draft', 'discontinued'),
        defaultValue: 'active'
    },
    stockStatus: {
        type: DataTypes.ENUM('in-stock', 'unavailable', 'announced'),
        defaultValue: 'in-stock'
    },
    images: {
        type: DataTypes.JSON,
        defaultValue: []
    },
    specifications: {
        type: DataTypes.JSON,
        defaultValue: []
    },
    tags: {
        type: DataTypes.JSON,
        defaultValue: []
    }
}, {
    timestamps: true,
    indexes: [
        { fields: ['category'] },
        { fields: ['brand'] },
        { fields: ['productStatus'] },
        { fields: ['name'] }
    ]
});

module.exports = Product;
*/

/*
// Example MongoDB schema with Mongoose

const mongoose = require('mongoose');

const productSchema = new mongoose.Schema({
    id: {
        type: String,
        required: true,
        unique: true,
        default: () => 'PRD' + Date.now().toString().slice(-6) + Math.random().toString(36).substr(2, 3).toUpperCase()
    },
    name: {
        type: String,
        required: true,
        trim: true,
        maxlength: 255
    },
    brand: {
        type: String,
        required: true
    },
    identificationNo: {
        type: String,
        unique: true,
        sparse: true
    },
    description: {
        type: String,
        required: true
    },
    category: {
        type: String,
        required: true,
        enum: ['mens', 'womens', 'kids', 'accessories']
    },
    subCategory: String,
    basePrice: {
        type: Number,
        required: true,
        min: 0
    },
    currency: {
        type: String,
        default: 'USD'
    },
    discount: {
        type: Number,
        default: 0,
        min: 0,
        max: 100
    },
    finalPrice: {
        type: Number,
        required: true
    },
    sizeRange: String,
    productStatus: {
        type: String,
        enum: ['active', 'inactive', 'draft', 'discontinued'],
        default: 'active'
    },
    stockStatus: {
        type: String,
        enum: ['in-stock', 'unavailable', 'announced'],
        default: 'in-stock'
    },
    images: [String],
    specifications: [{
        label: String,
        property: String
    }],
    tags: [String]
}, {
    timestamps: true
});

// Indexes for better query performance
productSchema.index({ category: 1 });
productSchema.index({ brand: 1 });
productSchema.index({ productStatus: 1 });
productSchema.index({ name: 'text', description: 'text' });

module.exports = mongoose.model('Product', productSchema);
*/

// Initialize API instance
const ecommerceAPI = new EcommerceAPI();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { EcommerceAPI, ecommerceAPI };
} else {
    window.EcommerceAPI = EcommerceAPI;
    window.ecommerceAPI = ecommerceAPI;
}
