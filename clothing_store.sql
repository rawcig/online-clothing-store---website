/*
 Navicat Premium Data Transfer

 Source Server         : online_clothing_store
 Source Server Type    : MySQL
 Source Server Version : 90100
 Source Host           : localhost:3306
 Source Schema         : clothing_store

 Target Server Type    : MySQL
 Target Server Version : 90100
 File Encoding         : 65001

 Date: 03/01/2026 11:26:04
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for brands
-- ----------------------------
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of brands
-- ----------------------------
INSERT INTO `brands` VALUES (1, 'G8 CLOSET', NULL, '2025-10-05 15:52:46');
INSERT INTO `brands` VALUES (2, 'Nike', NULL, '2025-10-05 15:52:46');
INSERT INTO `brands` VALUES (3, 'Adidas', NULL, '2025-10-05 15:52:46');
INSERT INTO `brands` VALUES (4, 'Zara', NULL, '2025-10-05 15:52:46');
INSERT INTO `brands` VALUES (5, 'H&M', NULL, '2025-10-05 15:52:46');
INSERT INTO `brands` VALUES (6, 'Uniqlo', NULL, '2025-10-05 15:52:46');

-- ----------------------------
-- Table structure for cart
-- ----------------------------
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `product_variant_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT 1,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_user_cart_item`(`user_id` ASC, `product_variant_id` ASC) USING BTREE,
  UNIQUE INDEX `unique_session_cart_item`(`session_id` ASC, `product_variant_id` ASC) USING BTREE,
  INDEX `product_variant_id`(`product_variant_id` ASC) USING BTREE,
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cart
-- ----------------------------

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `parent_id` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parent_id`(`parent_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (1, 'Clothing', 'All clothing items', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (2, 'Men', 'Men\'s clothing', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (3, 'Women', 'Women\'s clothing', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (4, 'Kids', 'Children\'s clothing', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (5, 'T-Shirts', 'T-Shirts for all genders', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (6, 'Hoodies', 'Hoodies and Sweatshirts', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (7, 'Shirts', 'Dress shirts and casual shirts', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (8, 'Jeans', 'Jeans and denim wear', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (9, 'Dresses', 'Women\'s dresses', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (10, 'Shoes', 'Footwear for all', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `categories` VALUES (11, 'Accessories', 'Fashion accessories', NULL, '2025-10-05 15:52:46', '2025-10-05 15:52:46');

-- ----------------------------
-- Table structure for order_items
-- ----------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_variant_id` int NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `product_price` decimal(10, 2) NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10, 2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`order_id` ASC) USING BTREE,
  INDEX `product_variant_id`(`product_variant_id` ASC) USING BTREE,
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of order_items
-- ----------------------------
INSERT INTO `order_items` VALUES (1, 1, 1, 'Regular T-Shirts With Printed', 14.00, 1, 14.59, '2025-10-12 19:15:48');
INSERT INTO `order_items` VALUES (2, 2, 9, 'Regular Hoodie', 20.00, 1, 20.00, '2025-10-12 21:25:14');
INSERT INTO `order_items` VALUES (3, 2, 9, 'Regular Hoodie', 20.00, 1, 20.00, '2025-10-12 21:25:14');
INSERT INTO `order_items` VALUES (4, 3, 9, 'Regular Hoodie', 20.00, 1, 20.00, '2025-10-12 21:27:21');
INSERT INTO `order_items` VALUES (5, 3, 9, 'Regular Hoodie', 20.00, 1, 20.00, '2025-10-12 21:27:21');
INSERT INTO `order_items` VALUES (6, 4, 3, 'Loose Fitted T-Shirts With Printed', 12.00, 1, 12.95, '2025-10-12 21:37:11');
INSERT INTO `order_items` VALUES (7, 4, 8, 'Men Regular T-Shirts', 19.00, 1, 19.00, '2025-10-12 21:37:11');
INSERT INTO `order_items` VALUES (8, 5, 2, 'Relaxed Fit T-Shirt', 11.00, 1, 11.95, '2025-10-12 22:10:17');
INSERT INTO `order_items` VALUES (9, 5, 8, 'Men Regular T-Shirts', 19.00, 1, 19.00, '2025-10-12 22:10:17');
INSERT INTO `order_items` VALUES (10, 5, 4, 'Regular Sweatshirts', 13.00, 3, 41.85, '2025-10-12 22:10:17');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pending',
  `subtotal` decimal(10, 2) NOT NULL,
  `tax_amount` decimal(10, 2) NULL DEFAULT 0.00,
  `shipping_amount` decimal(10, 2) NULL DEFAULT 0.00,
  `discount_amount` decimal(10, 2) NULL DEFAULT 0.00,
  `total_amount` decimal(10, 2) NOT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'USD',
  `billing_address_id` int NULL DEFAULT NULL,
  `shipping_address_id` int NULL DEFAULT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_number`(`order_number` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `billing_address_id`(`billing_address_id` ASC) USING BTREE,
  INDEX `shipping_address_id`(`shipping_address_id` ASC) USING BTREE,
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`billing_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`shipping_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (1, 2, 'ORD-20251012-68EB9BF405986', 'pending', 14.59, 0.00, 0.00, 0.00, 16.59, 'USD', 1, NULL, 'cod', 'pending', '', '2025-10-12 19:15:48', '2025-10-12 19:15:48');
INSERT INTO `orders` VALUES (2, 4, 'ORD-20251012-68EBBA4A9CD15', 'pending', 40.00, 0.00, 0.00, 0.00, 42.00, 'USD', 2, NULL, 'cod', 'pending', '', '2025-10-12 21:25:14', '2025-10-12 21:25:14');
INSERT INTO `orders` VALUES (3, 4, 'ORD-20251012-68EBBAC9A17E1', 'pending', 40.00, 0.00, 0.00, 0.00, 42.00, 'USD', 3, NULL, 'cod', 'pending', '', '2025-10-12 21:27:21', '2025-10-12 21:27:21');
INSERT INTO `orders` VALUES (4, 4, 'ORD-20251012-68EBBD17EE967', 'pending', 31.95, 0.00, 0.00, 0.00, 33.95, 'USD', 4, NULL, 'cod', 'pending', '', '2025-10-12 21:37:11', '2025-10-12 21:37:11');
INSERT INTO `orders` VALUES (5, 5, 'ORD-20251012-68EBC4D98B22C', 'pending', 72.80, 0.00, 0.00, 0.00, 74.80, 'USD', 5, NULL, 'cod', 'pending', '', '2025-10-12 22:10:17', '2025-10-12 22:10:17');

-- ----------------------------
-- Table structure for payment_transactions
-- ----------------------------
DROP TABLE IF EXISTS `payment_transactions`;
CREATE TABLE `payment_transactions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `payment_gateway` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10, 2) NOT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'USD',
  `status` enum('pending','completed','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pending',
  `response_data` json NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `transaction_id`(`transaction_id` ASC) USING BTREE,
  INDEX `order_id`(`order_id` ASC) USING BTREE,
  CONSTRAINT `payment_transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for product_images
-- ----------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alt_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_primary` tinyint(1) NULL DEFAULT 0,
  `sort_order` int NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product_images
-- ----------------------------
INSERT INTO `product_images` VALUES (1, 1, 'assets/images/product-images/women/t-shirts/AFTERNOON4970.jpg', NULL, 2, 1, '2025-10-10 13:53:08');
INSERT INTO `product_images` VALUES (2, 1, 'assets/images/product-images/women/t-shirts/AFTERNOON4975.jpg', NULL, 1, 2, '2025-10-10 13:54:00');
INSERT INTO `product_images` VALUES (3, 1, 'assets/images/product-images/women/t-shirts/AFTERNOON4962.jpg', NULL, 0, 0, '2025-10-10 22:25:38');
INSERT INTO `product_images` VALUES (4, 1, 'assets/images/product-images/women/t-shirts/AFTERNOON4963.jpg', NULL, 0, 0, '2025-10-10 22:28:01');
INSERT INTO `product_images` VALUES (5, 1, 'assets/images/product-images/women/t-shirts/AFTERNOON4964.jpg', NULL, 0, 0, '2025-10-10 22:28:30');
INSERT INTO `product_images` VALUES (6, 2, 'assets/images/product-images/women/t-shirts/ZANDO03.06.20252336.jpg', NULL, 2, 0, '2025-10-10 23:52:54');
INSERT INTO `product_images` VALUES (7, 2, 'assets/images/product-images/women/t-shirts/ZANDO03.06.20252337.jpg', NULL, 1, 0, '2025-10-10 23:55:48');
INSERT INTO `product_images` VALUES (8, 2, 'assets/images/product-images/women/t-shirts/ZANDO03.06.20252329.jpg', NULL, 0, 0, '2025-10-10 23:56:12');
INSERT INTO `product_images` VALUES (9, 2, 'assets/images/product-images/women/t-shirts/ZANDO03.06.20252328.jpg', NULL, 0, 0, '2025-10-10 23:56:43');
INSERT INTO `product_images` VALUES (10, 3, 'assets/images/product-images/women/t-shirts/ZANDO160620256591.jpg', NULL, 2, 1, '2025-10-12 20:07:29');
INSERT INTO `product_images` VALUES (11, 3, 'assets/images/product-images/women/t-shirts/ZANDO160620256597.jpg', NULL, 1, 2, '2025-10-12 20:07:54');
INSERT INTO `product_images` VALUES (12, 3, 'assets/images/product-images/women/t-shirts/ZANDO160620256586.jpg', NULL, 0, 0, '2025-10-12 20:08:18');
INSERT INTO `product_images` VALUES (13, 3, 'assets/images/product-images/women/t-shirts/ZANDO160620256585.jpg', NULL, 0, 0, '2025-10-12 20:08:33');
INSERT INTO `product_images` VALUES (14, 4, 'assets/images/product-images/women/hoodies/women_sw1_1.jpg', NULL, 2, 1, '2025-10-12 20:10:14');
INSERT INTO `product_images` VALUES (15, 4, 'assets/images/product-images/women/hoodies/women_sw1_2.jpg', NULL, 1, 2, '2025-10-12 20:10:32');
INSERT INTO `product_images` VALUES (16, 4, 'assets/images/product-images/women/hoodies/women_sw1_3.jpg', NULL, 0, 0, '2025-10-12 20:10:46');
INSERT INTO `product_images` VALUES (17, 4, 'assets/images/product-images/women/hoodies/women_sw1_4.jpg', NULL, 0, 0, '2025-10-12 20:11:09');
INSERT INTO `product_images` VALUES (18, 5, 'assets/images/product-images/women/shirts/ZANDO03.06.20251989.jpg', NULL, 2, 1, '2025-10-12 20:13:42');
INSERT INTO `product_images` VALUES (19, 5, 'assets/images/product-images/women/shirts/ZANDO03.06.20251996.jpg', NULL, 1, 2, '2025-10-12 20:14:22');
INSERT INTO `product_images` VALUES (20, 5, 'assets/images/product-images/women/shirts/ZANDO03.06.20251982.jpg', NULL, 0, 0, '2025-10-12 20:14:34');
INSERT INTO `product_images` VALUES (21, 6, 'assets/images/product-images/men/t-shirts/men_tsh1_1.jpg', NULL, 2, 1, '2025-10-12 20:21:38');
INSERT INTO `product_images` VALUES (22, 6, 'assets/images/product-images/men/t-shirts/men_tsh1_2.jpg', NULL, 1, 1, '2025-10-12 20:21:49');
INSERT INTO `product_images` VALUES (23, 6, 'assets/images/product-images/men/t-shirts/men_tsh1_3.jpg', NULL, 0, 0, '2025-10-12 20:22:13');
INSERT INTO `product_images` VALUES (24, 6, 'assets/images/product-images/men/t-shirts/men_tsh1_4.jpg', NULL, 0, 0, '2025-10-12 20:22:22');
INSERT INTO `product_images` VALUES (25, 6, 'assets/images/product-images/men/t-shirts/men_tsh1_5.jpg', NULL, 0, 0, '2025-10-12 20:22:31');
INSERT INTO `product_images` VALUES (26, 10, 'assets/images/product-images/men/jeans/ZANDO2705202527596.jpg', NULL, 2, 1, '2025-10-12 20:25:06');
INSERT INTO `product_images` VALUES (27, 10, 'assets/images/product-images/men/jeans/ZANDO2705202527594.jpg', NULL, 1, 2, '2025-10-12 20:25:41');
INSERT INTO `product_images` VALUES (28, 10, 'assets/images/product-images/men/jeans/ZANDO2705202527598.jpg', NULL, 0, 0, '2025-10-12 20:25:47');
INSERT INTO `product_images` VALUES (29, 10, 'assets/images/product-images/men/jeans/ZANDO2705202527600.jpg', NULL, 0, 0, '2025-10-12 20:26:06');
INSERT INTO `product_images` VALUES (31, 9, 'assets/images/product-images/men/hoodies/SR__1254.jpg', NULL, 2, 1, '2025-10-12 20:28:13');
INSERT INTO `product_images` VALUES (32, 9, 'assets/images/product-images/men/hoodies/SR__1255.jpg', NULL, 1, 2, '2025-10-12 20:28:48');
INSERT INTO `product_images` VALUES (33, 9, 'assets/images/product-images/men/hoodies/SR__1256.jpg', NULL, 0, 0, '2025-10-12 20:29:09');
INSERT INTO `product_images` VALUES (34, 9, 'assets/images/product-images/men/hoodies/SR__1263.jpg', NULL, 0, 0, '2025-10-12 20:29:21');
INSERT INTO `product_images` VALUES (35, 11, 'assets/images/product-images/women/dress/dress3_1.jpg', NULL, 2, 1, '2025-10-12 20:37:28');
INSERT INTO `product_images` VALUES (36, 11, 'assets/images/product-images/women/dress/dress3_2.jpg', NULL, 1, 2, '2025-10-12 20:37:35');
INSERT INTO `product_images` VALUES (37, 11, 'assets/images/product-images/women/dress/dress3_3.jpg', NULL, 0, 0, '2025-10-12 20:37:54');
INSERT INTO `product_images` VALUES (38, 11, 'assets/images/product-images/women/dress/dress3_4.jpg', NULL, 0, 0, '2025-10-12 20:38:01');
INSERT INTO `product_images` VALUES (39, 11, 'assets/images/product-images/women/dress/dress3_5.jpg', NULL, 0, 0, '2025-10-12 20:38:09');
INSERT INTO `product_images` VALUES (40, 12, 'assets/images/product-images/women/jeans/zando-women-jeans (1).jpg', NULL, 2, 1, '2025-10-12 20:44:37');
INSERT INTO `product_images` VALUES (41, 12, 'assets/images/product-images/women/jeans/zando-women-jeans (2).jpg', NULL, 0, 2, '2025-10-12 20:44:51');
INSERT INTO `product_images` VALUES (42, 12, 'assets/images/product-images/women/jeans/zando-women-jeans (3).jpg', NULL, 0, 0, '2025-10-12 20:45:05');
INSERT INTO `product_images` VALUES (43, 12, 'assets/images/product-images/women/jeans/zando-women-jeans (4).jpg', NULL, 1, 0, '2025-10-12 20:45:13');
INSERT INTO `product_images` VALUES (44, 12, 'assets/images/product-images/women/jeans/zando-women-jeans (5).jpg', NULL, 0, 0, '2025-10-12 20:45:18');
INSERT INTO `product_images` VALUES (45, 8, 'assets/images/product-images/men/t-shirts/men_tsh3_1.jpg', NULL, 2, 1, '2025-10-12 20:56:26');
INSERT INTO `product_images` VALUES (46, 8, 'assets/images/product-images/men/t-shirts/men_tsh3_2.jpg', NULL, 1, 2, '2025-10-12 21:01:19');
INSERT INTO `product_images` VALUES (47, 8, 'assets/images/product-images/men/t-shirts/men_tsh3_3.jpg', NULL, 0, 0, '2025-10-12 21:01:27');
INSERT INTO `product_images` VALUES (48, 8, 'assets/images/product-images/men/t-shirts/men_tsh3_4.jpg', NULL, 0, 0, '2025-10-12 21:01:40');
INSERT INTO `product_images` VALUES (49, 8, 'assets/images/product-images/men/t-shirts/men_tsh3_5.jpg', NULL, 0, 0, '2025-10-12 21:01:50');

-- ----------------------------
-- Table structure for product_variants
-- ----------------------------
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `size` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `price` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_variant`(`product_id` ASC, `size` ASC, `color` ASC) USING BTREE,
  CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product_variants
-- ----------------------------
INSERT INTO `product_variants` VALUES (1, 1, 'S', 'Black', 14.59, '2025-10-05 15:52:46', '2025-10-10 15:19:23');
INSERT INTO `product_variants` VALUES (2, 1, 'M', 'Black', 14.59, '2025-10-05 15:52:46', '2025-10-10 15:19:28');
INSERT INTO `product_variants` VALUES (3, 1, 'L', 'Black', 14.59, '2025-10-05 15:52:46', '2025-10-10 15:19:29');
INSERT INTO `product_variants` VALUES (4, 1, 'XL', 'Black', 14.59, '2025-10-05 15:52:46', '2025-10-10 15:19:31');
INSERT INTO `product_variants` VALUES (5, 2, 'S', 'Black', 11.95, '2025-10-05 15:52:46', '2025-10-10 22:52:25');
INSERT INTO `product_variants` VALUES (6, 2, 'M', 'Black', 11.95, '2025-10-05 15:52:46', '2025-10-10 22:52:37');
INSERT INTO `product_variants` VALUES (7, 2, 'L', 'Black', 11.95, '2025-10-05 15:52:46', '2025-10-10 22:52:40');
INSERT INTO `product_variants` VALUES (8, 3, 'S', 'White', 12.95, '2025-10-05 15:52:46', '2025-10-12 20:08:58');
INSERT INTO `product_variants` VALUES (9, 3, 'M', 'White', 12.95, '2025-10-05 15:52:46', '2025-10-12 20:09:01');
INSERT INTO `product_variants` VALUES (10, 3, 'L', 'White', 12.95, '2025-10-05 15:52:46', '2025-10-12 20:09:03');
INSERT INTO `product_variants` VALUES (11, 6, 'S', 'Milk', 14.59, '2025-10-05 15:52:46', '2025-10-12 20:23:09');
INSERT INTO `product_variants` VALUES (12, 6, 'M', 'Milk', 14.59, '2025-10-05 15:52:46', '2025-10-12 20:23:21');
INSERT INTO `product_variants` VALUES (13, 6, 'L', 'Milk', 14.59, '2025-10-05 15:52:46', '2025-10-12 20:23:22');
INSERT INTO `product_variants` VALUES (14, 6, 'XL', 'Milk', 14.59, '2025-10-05 15:52:46', '2025-10-12 20:23:23');
INSERT INTO `product_variants` VALUES (15, 4, 'Free-size', 'White', NULL, '2025-10-12 20:11:31', '2025-10-12 20:11:31');
INSERT INTO `product_variants` VALUES (16, 5, NULL, 'White', NULL, '2025-10-12 20:12:39', '2025-10-12 20:12:39');
INSERT INTO `product_variants` VALUES (17, 10, NULL, 'Black', NULL, '2025-10-12 20:24:36', '2025-10-12 20:24:36');
INSERT INTO `product_variants` VALUES (18, 11, NULL, 'Black', NULL, '2025-10-12 20:36:59', '2025-10-12 20:36:59');
INSERT INTO `product_variants` VALUES (19, 12, NULL, 'Grey', NULL, '2025-10-12 20:46:05', '2025-10-12 20:46:08');
INSERT INTO `product_variants` VALUES (20, 8, NULL, 'Black', NULL, '2025-10-12 20:49:03', '2025-10-12 20:49:03');
INSERT INTO `product_variants` VALUES (21, 9, NULL, 'Brown', NULL, '2025-10-12 20:49:25', '2025-10-12 20:50:43');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `price` decimal(10, 2) NOT NULL,
  `sale_price` decimal(10, 2) NULL DEFAULT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `stock_qty` int NULL DEFAULT 0,
  `category_id` int NULL DEFAULT NULL,
  `sub_category_id` int NULL DEFAULT NULL,
  `brand_id` int NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `is_featured` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `sku`(`sku` ASC) USING BTREE,
  INDEX `sub_category_id`(`sub_category_id` ASC) USING BTREE,
  INDEX `category_id`(`category_id` ASC) USING BTREE,
  INDEX `brand_id`(`brand_id` ASC) USING BTREE,
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `products_ibfk_3` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 'Regular T-Shirts With Printed', 'Comfortable cotton t-shirt with printed design', 14.59, 10.00, 'TS001-BLK', 10, 3, 5, 1, 'assets/images/product-images/women/t-shirts/AFTERNOON4975.jpg', 1, 1, '2025-10-05 15:52:46', '2025-10-10 22:27:11');
INSERT INTO `products` VALUES (2, 'Relaxed Fit T-Shirt', 'Relaxed fit cotton t-shirt for everyday wear', 11.95, NULL, 'TS002-BLK', 20, 3, 5, 1, 'assets/images/product-images/women/t-shirts/ZANDO03.06.20252336.jpg', 1, 0, '2025-10-05 15:52:46', '2025-10-12 17:15:14');
INSERT INTO `products` VALUES (3, 'Loose Fitted T-Shirts With Printed', 'Loose fitted t-shirt with printed design', 12.95, NULL, 'TS003-WH', 44, 3, 5, 1, 'assets/images/product-images/women/t-shirts/ZANDO160620256591.jpg', 1, 0, '2025-10-05 15:52:46', '2025-10-12 17:15:26');
INSERT INTO `products` VALUES (4, 'Regular Sweatshirts', 'Warm sweatshirts for casual wear', 13.95, NULL, 'SW001-WH', 22, 3, 6, 1, 'assets/images/product-images/women/hoodies/women_sw1_1.jpg', 1, 0, '2025-10-05 15:52:46', '2025-10-12 17:15:40');
INSERT INTO `products` VALUES (5, 'Regular Fit Ribbon Shirt', 'Elegant ribbon shirt for formal wear', 15.59, NULL, 'SH001-WH', 33, 3, 7, 1, 'assets/images/product-images/women/shirts/ZANDO03.06.20251989.jpg', 1, 0, '2025-10-05 15:52:46', '2025-10-12 17:15:45');
INSERT INTO `products` VALUES (6, 'Men Regular T-Shirts With Printed', 'Men\'s comfortable cotton t-shirt with printed design', 14.59, NULL, 'TS001-MILK', 23, 2, 5, 1, 'assets/images/product-images/men/t-shirts/men_tsh1_1.jpg', 1, 1, '2025-10-05 15:52:46', '2025-10-12 17:16:00');
INSERT INTO `products` VALUES (8, 'Men Regular T-Shirts', 'Men\'s regular fit t-shirt', 19.00, NULL, 'TS004-BLK', 14, 2, 5, 1, 'assets/images/product-images/men/t-shirts/men_tsh3_1.jpg', 1, 0, '2025-10-05 15:52:46', '2025-10-12 17:16:25');
INSERT INTO `products` VALUES (9, 'Regular Hoodie', 'Men\'s hoodie', 20.00, NULL, 'SR__1254', 20, 2, 6, 1, 'assets/images/product-images/men/hoodies/SR__1254.jpg', 1, 0, '2025-10-12 20:01:54', '2025-10-12 20:04:20');
INSERT INTO `products` VALUES (10, 'Men\'s jeans', 'Men\'s jeans', 24.00, NULL, 'ZANDO2705202527594', 15, 2, 8, 1, 'assets/images/product-images/men/jeans/ZANDO2705202527596.jpg', 1, 0, '2025-10-12 20:05:37', '2025-10-12 20:06:01');
INSERT INTO `products` VALUES (11, 'Women\'s Dress', 'Normal short Dress, casual', 15.00, NULL, 'DRS001-XS', 19, 3, 9, 5, 'assets/images/product-images/women/dress/dress3_1.jpg', 1, 0, '2025-10-12 20:35:10', '2025-10-12 20:36:25');
INSERT INTO `products` VALUES (12, 'Denim Jean', 'Women\'s wide leg denim jean', 24.00, NULL, 'DENI_J001', 20, 3, 8, 6, 'assets/images/product-images/women/jeans/zando-women-jeans (1).jpg', 1, 0, '2025-10-12 20:42:35', '2025-10-12 20:44:09');

-- ----------------------------
-- Table structure for reviews
-- ----------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NULL DEFAULT NULL,
  `user_id` int NULL DEFAULT NULL,
  `rating` int NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_verified_purchase` tinyint(1) NULL DEFAULT 0,
  `is_approved` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `reviews_chk_1` CHECK ((`rating` >= 1) and (`rating` <= 5))
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of reviews
-- ----------------------------
INSERT INTO `reviews` VALUES (1, 1, NULL, 4, NULL, NULL, 0, 0, '2025-10-06 16:19:35', '2025-10-06 16:19:35');

-- ----------------------------
-- Table structure for sub_categories
-- ----------------------------
DROP TABLE IF EXISTS `sub_categories`;
CREATE TABLE `sub_categories`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sub_categories
-- ----------------------------
INSERT INTO `sub_categories` VALUES (1, 'All', 'All clothing items', '2025-10-05 15:52:46', '2025-10-07 08:05:23');
INSERT INTO `sub_categories` VALUES (2, 'Men', 'Men\'s clothing', '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `sub_categories` VALUES (3, 'Women', 'Women\'s clothing', '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `sub_categories` VALUES (4, 'Kids', 'Children\'s clothing', '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `sub_categories` VALUES (5, 'T-Shirts', 'T-Shirts for all genders', '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `sub_categories` VALUES (6, 'Hoodies', 'Hoodies and Sweatshirts', '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `sub_categories` VALUES (7, 'Shirts', 'Dress shirts and casual shirts', '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `sub_categories` VALUES (8, 'Jeans', 'Jeans and denim wear', '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `sub_categories` VALUES (9, 'Dresses', 'Women\'s dresses', '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `sub_categories` VALUES (10, 'Shoes', 'Footwear for all', '2025-10-05 15:52:46', '2025-10-05 15:52:46');
INSERT INTO `sub_categories` VALUES (11, 'Accessories', 'Fashion accessories', '2025-10-05 15:52:46', '2025-10-05 15:52:46');

-- ----------------------------
-- Table structure for user_addresses
-- ----------------------------
DROP TABLE IF EXISTS `user_addresses`;
CREATE TABLE `user_addresses`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `address_type` enum('billing','shipping','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'both',
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `company` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `address_line1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address_line2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_default` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_addresses
-- ----------------------------
INSERT INTO `user_addresses` VALUES (1, 2, 'both', NULL, NULL, NULL, 'ada', NULL, '', '', '', '', 0, '2025-10-12 19:15:48', '2025-10-12 19:15:48');
INSERT INTO `user_addresses` VALUES (2, 4, 'both', NULL, NULL, NULL, 'phnom penh', NULL, '', '', '', '', 0, '2025-10-12 21:25:14', '2025-10-12 21:25:14');
INSERT INTO `user_addresses` VALUES (3, 4, 'both', NULL, NULL, NULL, 'r', NULL, '', '', '', '', 0, '2025-10-12 21:27:21', '2025-10-12 21:27:21');
INSERT INTO `user_addresses` VALUES (4, 4, 'both', NULL, NULL, NULL, 'phnom penh', NULL, '', '', '', '', 0, '2025-10-12 21:37:11', '2025-10-12 21:37:11');
INSERT INTO `user_addresses` VALUES (5, 5, 'both', NULL, NULL, NULL, 'phnom penh', NULL, '', '', '', '', 0, '2025-10-12 22:10:17', '2025-10-12 22:10:17');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date_of_birth` date NULL DEFAULT NULL,
  `gender` enum('male','female','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `is_verified` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'rawh', 'r@gmail.com', '123123', NULL, NULL, NULL, 1, 1, '2025-10-11 00:43:58', '2025-10-12 16:49:00');
INSERT INTO `users` VALUES (2, 'r', 'r2@gmail.com', '123123', NULL, NULL, NULL, 1, 0, '2025-10-12 16:57:56', '2025-10-12 16:57:56');
INSERT INTO `users` VALUES (3, 'test', 'test@demo.com', '123123', NULL, NULL, NULL, 1, 0, '2025-10-12 20:52:08', '2025-10-12 20:52:08');
INSERT INTO `users` VALUES (4, 'sokdara', 'sokdara@gmail.com', '123123', NULL, NULL, NULL, 1, 0, '2025-10-12 21:22:26', '2025-10-12 21:22:26');
INSERT INTO `users` VALUES (5, 'monyroth', 'roth@gmail.com', '123123', NULL, NULL, NULL, 1, 0, '2025-10-12 22:06:50', '2025-10-12 22:06:50');

-- ----------------------------
-- Table structure for wishlist
-- ----------------------------
DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_user_product`(`user_id` ASC, `product_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of wishlist
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
