CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `type` enum('percentage','amount') NOT NULL,
  `min_value` decimal(10,2) DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `products_product_fk_idx` (`product_id`),
  CONSTRAINT `products_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `stocks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_product_fk_idx` (`product_id`),
  CONSTRAINT `stocks_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `coupon_id` int DEFAULT NULL,
  `coupon_code` varchar(100) DEFAULT NULL,
  `coupon_value` decimal(10,2) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `zipcode` varchar(9) DEFAULT NULL,
  `address` text,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','completed') NOT NULL DEFAULT 'pending',
  `products` json DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_coupon_fk_idx` (`coupon_id`),
  CONSTRAINT `orders_coupon_fk` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO `products` (`id`, `product_id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Coca-Cola', 5.00, NOW(), NOW()),
(2, 1, 'Coca-Cola Zero', 5.50, NOW(), NOW()),
(3, 1, 'Coca-Cola Retornável', 5.00, NOW(), NOW()),
(4, NULL, 'Pepsi', 4.50, NOW(), NOW()),
(5, NULL, 'Fanta', 4.00, NOW(), NOW()),
(6, NULL, 'Sprite', 4.00, NOW(), NOW()),
(7, NULL, 'Guaraná', 4.50, NOW(), NOW()),
(8, NULL, 'Guaraná Antártica', 4.50, NOW(), NOW()),
(9, 8, 'Guaraná Antártica Lata', 4.50, NOW(), NOW()),
(10, 8, 'Guaraná Antártica Pet', 4.50, NOW(), NOW()),
(11, NULL, 'Água Mineral', 2.00, NOW(), NOW()),
(12, NULL, 'Suco de Laranja', 3.50, NOW(), NOW()),
(13, NULL, 'Suco de Uva', 3.50, NOW(), NOW()),
(14, NULL, 'Suco de Abacaxi', 3.50, NOW(), NOW()),
(15, NULL, 'Suco de Limão', 3.50, NOW(), NOW());
