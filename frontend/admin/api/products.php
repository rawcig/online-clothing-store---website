<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

$productId = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {
    case 'GET':
        if ($productId) {
            getProduct($productId);
        } else {
            getProducts();
        }
        break;
    case 'POST':
        createProduct();
        break;
    case 'PUT':
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input && isset($input['id'])) {
            updateProduct($input['id']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Product ID is required in request body']);
        }
        break;
    case 'DELETE':
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input && isset($input['id'])) {
            deleteProduct($input['id']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Product ID is required in request body']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function getProducts() {
    global $pdo;
    
    try {
        $whereClauses = [];
        $params = [];
        
        if (!empty($_GET['category'])) {
            $whereClauses[] = "p.category_id = :category";
            $params[':category'] = $_GET['category'];
        }
        
        if (!empty($_GET['brand'])) {
            $whereClauses[] = "p.brand_id = :brand";
            $params[':brand'] = $_GET['brand'];
        }
        
        if (!empty($_GET['status'])) {
            $statusMap = [
                'active' => 1,
                'inactive' => 0,
                'draft' => 0, 
                'discontinued' => 0 
            ];
            if (isset($statusMap[$_GET['status']])) {
                $whereClauses[] = "p.is_active = :status";
                $params[':status'] = $statusMap[$_GET['status']];
            }
        }
        
        if (!empty($_GET['search'])) {
            $searchTerm = '%' . $_GET['search'] . '%';
            $whereClauses[] = "(p.name LIKE :search OR p.description LIKE :search OR p.sku LIKE :search)";
            $params[':search'] = $searchTerm;
        }
        
        $whereSql = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
        
        // Build query with proper joins to get category and brand names
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       b.name as brand_name,
                       p.image as primary_image
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                $whereSql
                ORDER BY p.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();
        
        // Format the products to match the frontend structure
        foreach ($products as &$product) {
            // Map category name to category slug for frontend
            $categoryMap = [
                'Clothing' => 'clothing',
                'Men' => 'mens',
                'Women' => 'womens',
                'Kids' => 'kids',
                'Accessories' => 'accessories'
            ];
            
            $product['category'] = $categoryMap[$product['category_name']] ?? 'clothing';
            $product['brand'] = $product['brand_name'];
            
            // Format price values
            $product['basePrice'] = (float)$product['price'];
            $product['finalPrice'] = $product['sale_price'] ? (float)$product['sale_price'] : (float)$product['price'];
            $product['discount'] = $product['sale_price'] ? 
                round(((float)$product['price'] - (float)$product['sale_price']) / (float)$product['price'] * 100) : 0;
                
            // Map active status to product status
            $product['productStatus'] = $product['is_active'] ? 'active' : 'inactive';
            
            // Map stock status based on stock quantity
            if ($product['stock_qty'] > 0) {
                $product['stockStatus'] = 'in-stock';
            } else {
                $product['stockStatus'] = 'unavailable';
            }
            
            // Get product images from product_images table
            $imageSql = "SELECT image_path FROM product_images WHERE product_id = :product_id ORDER BY sort_order, id";
            $imageStmt = $pdo->prepare($imageSql);
            $imageStmt->execute([':product_id' => $product['id']]);
            $imageResults = $imageStmt->fetchAll(PDO::FETCH_COLUMN);
            
            // If no images in product_images, use the primary image from products table
            $product['images'] = !empty($imageResults) ? $imageResults : [$product['primary_image']];
            
            // Get product specifications from product_variants
            $specSql = "SELECT size, color FROM product_variants WHERE product_id = :product_id LIMIT 5";
            $specStmt = $pdo->prepare($specSql);
            $specStmt->execute([':product_id' => $product['id']]);
            $specResults = $specStmt->fetchAll();
            
            $product['specifications'] = [];
            foreach ($specResults as $spec) {
                if (!empty($spec['size'])) {
                    $product['specifications'][] = ['label' => 'Size', 'property' => $spec['size']];
                }
                if (!empty($spec['color'])) {
                    $product['specifications'][] = ['label' => 'Color', 'property' => $spec['color']];
                }
            }
            
            // Initialize tags array
            $product['tags'] = [];
            
            // Format dates
            $product['createdAt'] = date('Y-m-d', strtotime($product['created_at']));
            $product['updatedAt'] = date('Y-m-d', strtotime($product['updated_at']));
        }
        
        echo json_encode($products);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Get specific product by ID
function getProduct($id) {
    global $pdo;
    
    try {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       b.name as brand_name,
                       p.image as primary_image
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch();
        
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }
        
        // Map category name to category slug for frontend
        $categoryMap = [
            'Clothing' => 'clothing',
            'Men' => 'mens',
            'Women' => 'womens',
            'Kids' => 'kids',
            'Accessories' => 'accessories'
        ];
        
        $product['category'] = $categoryMap[$product['category_name']] ?? 'clothing';
        $product['brand'] = $product['brand_name'];
        
        // Format price values
        $product['basePrice'] = (float)$product['price'];
        $product['finalPrice'] = $product['sale_price'] ? (float)$product['sale_price'] : (float)$product['price'];
        $product['discount'] = $product['sale_price'] ? 
            round(((float)$product['price'] - (float)$product['sale_price']) / (float)$product['price'] * 100) : 0;
            
        // Map active status to product status
        $product['productStatus'] = $product['is_active'] ? 'active' : 'inactive';
        
        // Map stock status based on stock quantity
        if ($product['stock_qty'] > 0) {
            $product['stockStatus'] = 'in-stock';
        } else {
            $product['stockStatus'] = 'unavailable';
        }
        
        // Get product images from product_images table
        $imageSql = "SELECT image_path FROM product_images WHERE product_id = :product_id ORDER BY sort_order, id";
        $imageStmt = $pdo->prepare($imageSql);
        $imageStmt->execute([':product_id' => $product['id']]);
        $imageResults = $imageStmt->fetchAll(PDO::FETCH_COLUMN);
        
        // If no images in product_images, use the primary image from products table
        $product['images'] = !empty($imageResults) ? $imageResults : [$product['primary_image']];
        
        // Get product specifications from product_variants
        $specSql = "SELECT size, color FROM product_variants WHERE product_id = :product_id";
        $specStmt = $pdo->prepare($specSql);
        $specStmt->execute([':product_id' => $product['id']]);
        $specResults = $specStmt->fetchAll();
        
        $product['specifications'] = [];
        foreach ($specResults as $spec) {
            if (!empty($spec['size'])) {
                $product['specifications'][] = ['label' => 'Size', 'property' => $spec['size']];
            }
            if (!empty($spec['color'])) {
                $product['specifications'][] = ['label' => 'Color', 'property' => $spec['color']];
            }
        }
        
        // Initialize tags array
        $product['tags'] = [];
        
        // Format dates
        $product['createdAt'] = date('Y-m-d', strtotime($product['created_at']));
        $product['updatedAt'] = date('Y-m-d', strtotime($product['updated_at']));
        
        echo json_encode($product);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Handle POST requests for specific product by ID
if ($method == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input && isset($input['action']) && $input['action'] === 'get' && isset($input['id'])) {
        getProduct($input['id']);
        exit();
    }
    
    // If it's not a get request, treat as create (for compatibility with existing frontend)
    createProduct();
}

// Create new product
function createProduct() {
    global $pdo;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        return;
    }
    
    try {
        // Get category ID from category name
        $catSql = "SELECT id FROM categories WHERE name LIKE :category_name OR :category_name LIKE name";
        $catStmt = $pdo->prepare($catSql);
        
        // Map category slug to actual category name
        $categoryNames = [
            'mens' => 'Men',
            'womens' => 'Women', 
            'kids' => 'Kids',
            'accessories' => 'Accessories'
        ];
        
        $categoryName = $categoryNames[$input['category']] ?? 'Clothing';
        $catStmt->execute([':category_name' => '%' . $categoryName . '%']);
        $category = $catStmt->fetch();
        $categoryId = $category ? $category['id'] : 1; // default to first category
        
        // Get brand ID from brand name
        $brandSql = "SELECT id FROM brands WHERE name = :brand_name";
        $brandStmt = $pdo->prepare($brandSql);
        $brandStmt->execute([':brand_name' => $input['brand']]);
        $brand = $brandStmt->fetch();
        $brandId = $brand ? $brand['id'] : 1; // default to first brand
        
        // Prepare SQL query
        $sql = "INSERT INTO products (name, description, price, sale_price, sku, stock_qty, category_id, brand_id, image, is_active, created_at, updated_at) 
                VALUES (:name, :description, :price, :sale_price, :sku, :stock_qty, :category_id, :brand_id, :image, :is_active, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        
        // Calculate sale price from base price and discount
        $salePrice = isset($input['discount']) && $input['discount'] > 0 ? 
            $input['basePrice'] * (1 - $input['discount'] / 100) : 
            null;
        
        $params = [
            ':name' => $input['name'],
            ':description' => $input['description'],
            ':price' => $input['basePrice'],
            ':sale_price' => $salePrice,
            ':sku' => $input['sku'],
            ':stock_qty' => $input['stockStatus'] === 'in-stock' ? 10 : 0, // default stock level
            ':category_id' => $categoryId,
            ':brand_id' => $brandId,
            ':image' => isset($input['images'][0]) ? $input['images'][0] : null,
            ':is_active' => $input['productStatus'] === 'active' ? 1 : 0
        ];
        
        $stmt->execute($params);
        $newProductId = $pdo->lastInsertId();
        
        // Insert product images if any
        if (!empty($input['images'])) {
            foreach ($input['images'] as $index => $imagePath) {
                $imgSql = "INSERT INTO product_images (product_id, image_path, sort_order, is_primary) 
                          VALUES (:product_id, :image_path, :sort_order, :is_primary)";
                $imgStmt = $pdo->prepare($imgSql);
                $imgStmt->execute([
                    ':product_id' => $newProductId,
                    ':image_path' => $imagePath,
                    ':sort_order' => $index,
                    ':is_primary' => $index === 0 ? 1 : 0
                ]);
            }
        }
        
        // Insert product variants if any specifications exist
        if (!empty($input['specifications'])) {
            foreach ($input['specifications'] as $spec) {
                if ($spec['label'] === 'Size' || $spec['label'] === 'Color') {
                    // Try to determine size and color from specifications
                    $size = $spec['label'] === 'Size' ? $spec['property'] : null;
                    $color = $spec['label'] === 'Color' ? $spec['property'] : null;
                    
                    $varSql = "INSERT INTO product_variants (product_id, size, color, price, created_at, updated_at) 
                              VALUES (:product_id, :size, :color, :price, NOW(), NOW())";
                    $varStmt = $pdo->prepare($varSql);
                    $varStmt->execute([
                        ':product_id' => $newProductId,
                        ':size' => $size,
                        ':color' => $color,
                        ':price' => $input['basePrice']
                    ]);
                }
            }
        }
        
        // Get the newly created product
        getProduct($newProductId);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Update existing product
function updateProduct($id) {
    global $pdo;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        return;
    }
    
    try {
        // Get category ID from category name
        $catSql = "SELECT id FROM categories WHERE name LIKE :category_name OR :category_name LIKE name";
        $catStmt = $pdo->prepare($catSql);
        
        // Map category slug to actual category name
        $categoryNames = [
            'mens' => 'Men',
            'womens' => 'Women', 
            'kids' => 'Kids',
            'accessories' => 'Accessories'
        ];
        
        $categoryName = $categoryNames[$input['category']] ?? 'Clothing';
        $catStmt->execute([':category_name' => '%' . $categoryName . '%']);
        $category = $catStmt->fetch();
        $categoryId = $category ? $category['id'] : 1; // default to first category
        
        // Get brand ID from brand name
        $brandSql = "SELECT id FROM brands WHERE name = :brand_name";
        $brandStmt = $pdo->prepare($brandSql);
        $brandStmt->execute([':brand_name' => $input['brand']]);
        $brand = $brandStmt->fetch();
        $brandId = $brand ? $brand['id'] : 1; // default to first brand
        
        // Calculate sale price from base price and discount
        $salePrice = isset($input['discount']) && $input['discount'] > 0 ? 
            $input['basePrice'] * (1 - $input['discount'] / 100) : 
            null;
        
        // Prepare SQL query
        $sql = "UPDATE products 
                SET name = :name, 
                    description = :description, 
                    price = :price, 
                    sale_price = :sale_price, 
                    sku = :sku, 
                    stock_qty = :stock_qty, 
                    category_id = :category_id, 
                    brand_id = :brand_id, 
                    image = :image, 
                    is_active = :is_active, 
                    updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        $params = [
            ':id' => $id,
            ':name' => $input['name'],
            ':description' => $input['description'],
            ':price' => $input['basePrice'],
            ':sale_price' => $salePrice,
            ':sku' => $input['sku'],
            ':stock_qty' => $input['stockStatus'] === 'in-stock' ? 10 : 0, // default stock level
            ':category_id' => $categoryId,
            ':brand_id' => $brandId,
            ':image' => isset($input['images'][0]) ? $input['images'][0] : null,
            ':is_active' => $input['productStatus'] === 'active' ? 1 : 0
        ];
        
        $stmt->execute($params);
        
        // Delete existing product images
        $delImgSql = "DELETE FROM product_images WHERE product_id = :product_id";
        $delImgStmt = $pdo->prepare($delImgSql);
        $delImgStmt->execute([':product_id' => $id]);
        
        // Insert updated product images
        if (!empty($input['images'])) {
            foreach ($input['images'] as $index => $imagePath) {
                $imgSql = "INSERT INTO product_images (product_id, image_path, sort_order, is_primary) 
                          VALUES (:product_id, :image_path, :sort_order, :is_primary)";
                $imgStmt = $pdo->prepare($imgSql);
                $imgStmt->execute([
                    ':product_id' => $id,
                    ':image_path' => $imagePath,
                    ':sort_order' => $index,
                    ':is_primary' => $index === 0 ? 1 : 0
                ]);
            }
        }
        
        // Delete existing product variants
        $delVarSql = "DELETE FROM product_variants WHERE product_id = :product_id";
        $delVarStmt = $pdo->prepare($delVarSql);
        $delVarStmt->execute([':product_id' => $id]);
        
        // Insert updated product variants if any specifications exist
        if (!empty($input['specifications'])) {
            foreach ($input['specifications'] as $spec) {
                if ($spec['label'] === 'Size' || $spec['label'] === 'Color') {
                    // Try to determine size and color from specifications
                    $size = $spec['label'] === 'Size' ? $spec['property'] : null;
                    $color = $spec['label'] === 'Color' ? $spec['property'] : null;
                    
                    $varSql = "INSERT INTO product_variants (product_id, size, color, price, created_at, updated_at) 
                              VALUES (:product_id, :size, :color, :price, NOW(), NOW())";
                    $varStmt = $pdo->prepare($varSql);
                    $varStmt->execute([
                        ':product_id' => $id,
                        ':size' => $size,
                        ':color' => $color,
                        ':price' => $input['basePrice']
                    ]);
                }
            }
        }
        
        // Get the updated product
        getProduct($id);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Delete product
function deleteProduct($id) {
    global $pdo;
    
    try {
        // Delete related records first (due to foreign key constraints)
        $delVariantsSql = "DELETE FROM product_variants WHERE product_id = :id";
        $delVariantsStmt = $pdo->prepare($delVariantsSql);
        $delVariantsStmt->execute([':id' => $id]);
        
        $delImagesSql = "DELETE FROM product_images WHERE product_id = :id";
        $delImagesStmt = $pdo->prepare($delImagesSql);
        $delImagesStmt->execute([':id' => $id]);
        
        // Now delete the product
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([':id' => $id]);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete product']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>