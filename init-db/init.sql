CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `type` enum('percentage','amount') NOT NULL,
  `min_value` decimal(10,2) DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `products_product_fk_idx` (`product_id`),
  CONSTRAINT `products_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stocks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_product_fk_idx` (`product_id`),
  CONSTRAINT `stocks_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_value` decimal(10,2) DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','completed') NOT NULL DEFAULT 'pending',
  `products` json DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `product_id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Coca-Cola 1L', 5.00, NOW(), NOW()),
(2, 1, 'Coca-Cola Zero 1L', 5.50, NOW(), NOW()),
(3, 1, 'Coca-Cola Pet', 2.50, NOW(), NOW()),
(4, NULL, 'Pepsi', 4.50, NOW(), NOW()),
(5, NULL, 'Fanta', 4.00, NOW(), NOW()),
(6, NULL, 'Sprite', 4.00, NOW(), NOW()),
(7, NULL, 'Suco de Fruta', 3.50, NOW(), NOW()),
(8, 7, 'Suco de Manga', 3.50, NOW(), NOW());
(9, 7, 'Suco de Laranja', 3.50, NOW(), NOW()),
(10, 7, 'Suco de Uva', 3.50, NOW(), NOW()),
(11, 7, 'Suco de Abacaxi', 3.50, NOW(), NOW()),

INSERT INTO `stocks` (`id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 10, 100, NOW(), NOW()),
(2, 5, 100, NOW(), NOW()),
(3, 15, 100, NOW(), NOW()),
(4, 14, 100, NOW(), NOW()),
(5, 5, 100, NOW(), NOW()),
(6, 21, 100, NOW(), NOW()),
(7, 11, 100, NOW(), NOW()),
(8, 31, 100, NOW(), NOW());
(9, 12, 100, NOW(), NOW()),
(10, 13, 100, NOW(), NOW()),
(11, 10, 100, NOW(), NOW());

INSERT INTO `coupons` (`id`, `code`, `discount`, `type`, `min_value`, `valid_until`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '10OFF', 10.00, 'percentage', NULL, '2024-12-31 23:59:59', 1, NOW(), NOW()),
(2, '5OFF', 5.00, 'amount', NULL, '2024-12-31 23:59:59', 1, NOW(), NOW()),
(3, 'FREESHIP', 0.00, 'amount', 50.00, '2024-12-31 23:59:59', 1, NOW(), NOW());