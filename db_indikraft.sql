/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : db_indikraft

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-08-28 08:07:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for addresses
-- ----------------------------
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `address_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` int(11) NOT NULL,
  `postal_code` int(6) NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`address_id`),
  KEY `address_user_id_foreign` (`user_id`),
  CONSTRAINT `address_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of addresses
-- ----------------------------
INSERT INTO `addresses` VALUES ('4', '12', 'Izira', 'Milas', 'Kp. Cibacang', '24', '40553', '087822667801', '2017-08-07 03:47:13', '2017-08-25 01:54:32');
INSERT INTO `addresses` VALUES ('6', '14', 'Salim', 'Admin1', 'Bandung', '24', '40553', '87822667801', '2017-08-07 04:22:34', '2017-08-24 03:48:06');
INSERT INTO `addresses` VALUES ('9', '19', null, null, 'Bandung', '24', '40553', '87822667801', '2017-08-08 09:46:44', '2017-08-08 09:46:44');
INSERT INTO `addresses` VALUES ('10', '12', 'Izira', 'Milas', 'Bandung', '177', '40553', '87822667801', '2017-08-09 06:17:20', '2017-08-09 06:17:20');
INSERT INTO `addresses` VALUES ('11', '12', 'sadasd', 'asdasd', 'asd', '311', '32134', '312', '2017-08-18 08:01:51', '2017-08-18 08:01:51');

-- ----------------------------
-- Table structure for carts
-- ----------------------------
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `product_option_id` int(10) unsigned DEFAULT NULL,
  `transaction_id` int(10) unsigned DEFAULT NULL,
  `price` int(11) NOT NULL,
  `amount` int(4) NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `carts_buyer_id_foreign` (`buyer_id`),
  KEY `product_option_id` (`product_option_id`),
  KEY `carts_product_id_foreign` (`product_id`),
  CONSTRAINT `carts_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`product_option_id`) REFERENCES `products_options` (`product_option_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of carts
-- ----------------------------
INSERT INTO `carts` VALUES ('27', '12', '7', null, '10', '55000', '1', '55001', '1', '2017-08-21 08:53:46', '2017-08-21 09:05:27');
INSERT INTO `carts` VALUES ('29', '12', '7', null, '12', '55000', '1', '55000', '1', '2017-08-23 04:00:39', '2017-08-23 04:07:18');
INSERT INTO `carts` VALUES ('30', '12', '7', null, '13', '55000', '1', '55000', '1', '2017-08-23 04:35:12', '2017-08-23 04:35:31');
INSERT INTO `carts` VALUES ('31', '12', '7', null, '14', '55000', '1', '55000', '1', '2017-08-23 04:36:37', '2017-08-23 04:36:54');
INSERT INTO `carts` VALUES ('32', '12', '7', null, '15', '55000', '1', '55000', '1', '2017-08-23 04:37:34', '2017-08-23 04:37:57');
INSERT INTO `carts` VALUES ('33', '12', '7', null, '16', '55000', '1', '55000', '1', '2017-08-23 04:39:34', '2017-08-23 04:39:55');
INSERT INTO `carts` VALUES ('34', '12', '7', null, '17', '55000', '1', '55000', '1', '2017-08-23 04:40:29', '2017-08-23 04:40:45');
INSERT INTO `carts` VALUES ('35', '12', '7', null, '18', '55000', '1', '55000', '1', '2017-08-23 04:41:16', '2017-08-23 04:41:35');
INSERT INTO `carts` VALUES ('36', '12', '7', null, '19', '55000', '1', '55000', '1', '2017-08-23 04:43:15', '2017-08-23 04:43:32');
INSERT INTO `carts` VALUES ('37', '12', '7', null, '20', '55000', '1', '55000', '1', '2017-08-23 04:54:51', '2017-08-23 04:55:10');
INSERT INTO `carts` VALUES ('38', '12', '7', null, '21', '55000', '1', '55000', '1', '2017-08-23 04:56:00', '2017-08-23 04:56:17');
INSERT INTO `carts` VALUES ('39', '12', '7', null, '22', '55000', '1', '55000', '1', '2017-08-23 06:12:08', '2017-08-23 06:12:26');
INSERT INTO `carts` VALUES ('40', '12', '7', null, '23', '55000', '1', '55000', '1', '2017-08-23 06:14:48', '2017-08-23 06:15:05');
INSERT INTO `carts` VALUES ('41', '12', '7', null, '24', '55000', '1', '55000', '1', '2017-08-23 06:25:18', '2017-08-23 06:25:41');
INSERT INTO `carts` VALUES ('43', '12', '8', null, '24', '3000', '3', '6000', '1', '2017-08-24 08:54:04', '2017-08-24 09:00:14');
INSERT INTO `carts` VALUES ('44', '12', '9', null, '24', '55000', '1', '55000', '1', '2017-08-23 06:25:18', '2017-08-23 06:25:41');
INSERT INTO `carts` VALUES ('45', '12', '7', null, '25', '55000', '2', '110000', '1', '2017-08-27 06:48:20', '2017-08-27 07:05:24');
INSERT INTO `carts` VALUES ('46', '12', '8', null, '25', '3000', '1', '3000', '1', '2017-08-27 06:48:32', '2017-08-27 07:05:24');
INSERT INTO `carts` VALUES ('47', '12', '7', null, '26', '55000', '1', '55000', '1', '2017-08-27 14:10:25', '2017-08-27 14:10:51');
INSERT INTO `carts` VALUES ('49', '12', '11', null, '27', '200000', '1', '200000', '1', '2017-08-27 14:11:54', '2017-08-27 14:15:47');

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `categories_category_name_unique` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('1', 'Baju', '2017-08-07 11:36:15', '2017-08-07 11:36:18');
INSERT INTO `categories` VALUES ('2', 'Celana', '2017-08-07 11:36:15', '2017-08-07 11:36:18');

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_tail` int(11) DEFAULT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comments_post_id_foreign` (`post_id`),
  CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of comments
-- ----------------------------
INSERT INTO `comments` VALUES ('1', null, '4', 'Salim Arizi', 'salim9asmp1pdl@gmail.com', 'Komentarinya menurut saya komentar itu adalah komentar', '2017-08-10 07:54:51', '2017-08-10 07:54:51');
INSERT INTO `comments` VALUES ('2', null, '4', 'Ar', 'sad@arms.com', 'arew', '2017-08-10 08:00:59', '2017-08-10 08:00:59');
INSERT INTO `comments` VALUES ('3', null, '6', 'Salim Arizi', 'salim9asmp1pdl@gmail.com', 'Ini komentar dari Salim Arizi', '2017-08-13 11:23:19', '2017-08-13 11:23:19');
INSERT INTO `comments` VALUES ('4', null, '6', 'Salim Arizi', 'salim9asmp1pdl@gmail.com', 'asd', '2017-08-13 11:40:06', '2017-08-13 11:40:06');
INSERT INTO `comments` VALUES ('5', null, '4', 'Izira Milas', 'abc@gmail.com', 'Ini komentar dari mobile', '2017-08-18 03:23:48', '2017-08-18 03:23:48');
INSERT INTO `comments` VALUES ('6', null, '4', 'Izira Milas', 'abc@gmail.com', 'Ini komentar dari mobile', '2017-08-24 09:40:11', '2017-08-24 09:40:11');
INSERT INTO `comments` VALUES ('9', null, '9', 'Salim Arizi', 'salim9asmp1pdl@gmail.com', 'cek notifikasi', '2017-08-26 12:33:45', '2017-08-26 12:33:45');
INSERT INTO `comments` VALUES ('10', null, '5', 'Izira Milas', 'salim9asmp1pdl@gmail.com', 'sad', '2017-08-27 14:08:53', '2017-08-27 14:08:53');

-- ----------------------------
-- Table structure for emails
-- ----------------------------
DROP TABLE IF EXISTS `emails`;
CREATE TABLE `emails` (
  `email_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of emails
-- ----------------------------
INSERT INTO `emails` VALUES ('1', 'Salim Arizi', 'salim9asmp1pdl@gmail.com', '2017-08-18 08:18:59', '2017-08-18 08:18:59');
INSERT INTO `emails` VALUES ('2', 'Arizi', 'salimarizi07@gmail.com', '2017-08-18 09:58:21', '2017-08-18 09:58:21');
INSERT INTO `emails` VALUES ('3', 'Arizi', 'salimarizi07@gmail.com', '2017-08-24 09:55:15', '2017-08-24 09:55:15');

-- ----------------------------
-- Table structure for faqs
-- ----------------------------
DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
  `faq_id` int(4) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of faqs
-- ----------------------------
INSERT INTO `faqs` VALUES ('2', 'Pertanyaan1', 'Jawaban', '2017-08-18 09:59:03', '2017-08-18 02:59:03');

-- ----------------------------
-- Table structure for group_options
-- ----------------------------
DROP TABLE IF EXISTS `group_options`;
CREATE TABLE `group_options` (
  `group_option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_option_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`group_option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of group_options
-- ----------------------------

-- ----------------------------
-- Table structure for images
-- ----------------------------
DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_category_id` int(10) NOT NULL,
  `image_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`image_id`),
  KEY `image_category_id` (`image_category_id`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`image_category_id`) REFERENCES `image_categories` (`image_category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of images
-- ----------------------------
INSERT INTO `images` VALUES ('3', '11', '1.png', '5c20dcbcfbab07ab6c2df7e27444d5ac2afca5691502492334.png', 'asd', '2017-08-11 22:58:54', '2017-08-11 22:58:54');
INSERT INTO `images` VALUES ('4', '12', 'Bukti daftar.png', '36462f1bcff3efefa692139179aaa46bdc84ba8f1502495402.png', 'Ini adalah gambar ketika kita', '2017-08-11 23:50:02', '2017-08-11 23:50:02');
INSERT INTO `images` VALUES ('5', '12', 'Larry\'s_Gym_22.jpg', '7031dd65d02ce1e0d90ad0eabd2ed4f1ea55aa901502495402.jpg', 'Ini adalah gambar ketika kita', '2017-08-11 23:50:02', '2017-08-11 23:50:02');
INSERT INTO `images` VALUES ('6', '13', 'Chrome.png', '983c24918351ada724a5bc56db33ef8a7d78c0351502624550.png', 'Ini adalah gambar ketika ada kategori baru', '2017-08-13 11:42:30', '2017-08-13 11:42:30');
INSERT INTO `images` VALUES ('7', '13', '5.png', 'ee673444daa2c4c150863fb4fe2e59385df853241502624550.png', 'Ini adalah gambar ketika ada kategori baru', '2017-08-13 11:42:30', '2017-08-13 11:42:30');
INSERT INTO `images` VALUES ('8', '13', 'Chrome.png', '983c24918351ada724a5bc56db33ef8a7d78c0351502624589.png', 'asdadasdasdasd\r\nasdasdasdasd', '2017-08-13 11:43:09', '2017-08-13 11:43:09');
INSERT INTO `images` VALUES ('9', '13', 'Bukti daftar.png', '36462f1bcff3efefa692139179aaa46bdc84ba8f1502624589.png', 'asdadasdasdasd\r\nasdasdasdasd', '2017-08-13 11:43:09', '2017-08-13 11:43:09');
INSERT INTO `images` VALUES ('10', '13', 'diagram konteks hubin.jpg', '33a9fea9ddde1f2fe1db14ae92e37e7f82a9f3ad1502624647.jpg', 'asdasdasdasd', '2017-08-13 11:44:07', '2017-08-13 11:44:07');
INSERT INTO `images` VALUES ('11', '11', '1.png', '5c20dcbcfbab07ab6c2df7e27444d5ac2afca5691502624670.png', 'asdasdsadasd', '2017-08-13 11:44:30', '2017-08-13 11:44:30');
INSERT INTO `images` VALUES ('12', '11', 'Bukti daftar.png', '36462f1bcff3efefa692139179aaa46bdc84ba8f1502624671.png', 'asdasdsadasd', '2017-08-13 11:44:31', '2017-08-13 11:44:31');
INSERT INTO `images` VALUES ('13', '12', '1.png', '5c20dcbcfbab07ab6c2df7e27444d5ac2afca5691502676431.png', 'sadasdsadasdadd\r\nasdasdasdasd', '2017-08-14 02:07:11', '2017-08-14 02:07:11');
INSERT INTO `images` VALUES ('14', '12', 'Untitled.png', 'b5703ac068802fd4c4ca6b8e87b4fc9f76c0c3ba1502676432.png', 'sadasdsadasdadd\r\nasdasdasdasd', '2017-08-14 02:07:12', '2017-08-14 02:07:12');
INSERT INTO `images` VALUES ('15', '12', 'ps.png', 'a8a0a5d7957fd8b5f551b31b49ebec6f85767db51502676432.png', 'sadasdsadasdadd\r\nasdasdasdasd', '2017-08-14 02:07:12', '2017-08-14 02:07:12');
INSERT INTO `images` VALUES ('16', '12', 'Larry\'s_Gym_19.jpg', 'f3559cc2746b67f10174950efb80539f26d5b8511502676433.jpg', 'sadasdsadasdadd\r\nasdasdasdasd', '2017-08-14 02:07:13', '2017-08-14 02:07:13');
INSERT INTO `images` VALUES ('17', '12', 'Larry\'s_Gym_22.jpg', '7031dd65d02ce1e0d90ad0eabd2ed4f1ea55aa901502676433.jpg', 'sadasdsadasdadd\r\nasdasdasdasd', '2017-08-14 02:07:13', '2017-08-14 02:07:13');
INSERT INTO `images` VALUES ('18', '12', 'sp.png', '63b4afda6b56e99aa1b241b07d2ad3261d349cf31502676434.png', 'sadasdsadasdadd\r\nasdasdasdasd', '2017-08-14 02:07:14', '2017-08-14 02:07:14');
INSERT INTO `images` VALUES ('20', '14', 'Larry\'s_Gym_22.jpg', '7031dd65d02ce1e0d90ad0eabd2ed4f1ea55aa901503022611.jpg', 'Ini adalah foto baru di kategori baru', '2017-08-18 02:16:51', '2017-08-18 02:16:51');

-- ----------------------------
-- Table structure for image_categories
-- ----------------------------
DROP TABLE IF EXISTS `image_categories`;
CREATE TABLE `image_categories` (
  `image_category_id` int(10) NOT NULL AUTO_INCREMENT,
  `image_category_name` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of image_categories
-- ----------------------------
INSERT INTO `image_categories` VALUES ('11', 'Kejuaraan', '2017-08-11 04:15:00', '2017-08-11 04:15:00');
INSERT INTO `image_categories` VALUES ('12', 'Syukuran', '2017-08-11 04:19:29', '2017-08-11 04:19:29');
INSERT INTO `image_categories` VALUES ('13', 'Kategori Baru', '2017-08-13 11:41:55', '2017-08-13 11:41:55');
INSERT INTO `image_categories` VALUES ('14', 'Kategori Baru', '2017-08-18 02:12:37', '2017-08-18 02:12:37');

-- ----------------------------
-- Table structure for informations
-- ----------------------------
DROP TABLE IF EXISTS `informations`;
CREATE TABLE `informations` (
  `information_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`information_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of informations
-- ----------------------------
INSERT INTO `informations` VALUES ('1', 'Tentang Kami', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse.</p>', null, '2017-08-14 03:44:56');
INSERT INTO `informations` VALUES ('2', 'Kontak Kami', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non, amet assumenda ad maiores vero voluptate. Placeat veritatis, repellat aliquam voluptas natus impedit, dignissimos aliquid minima at voluptatum ad esse.</p>', null, '2017-08-14 03:52:35');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('49', '2014_09_12_021823_roles', '1');
INSERT INTO `migrations` VALUES ('50', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('51', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('52', '2017_08_04_024655_categories', '1');
INSERT INTO `migrations` VALUES ('53', '2017_08_04_025350_products', '1');
INSERT INTO `migrations` VALUES ('54', '2017_08_04_025620_product_images', '1');
INSERT INTO `migrations` VALUES ('55', '2017_08_04_025649_ratings', '1');
INSERT INTO `migrations` VALUES ('56', '2017_08_04_025737_group_option', '1');
INSERT INTO `migrations` VALUES ('57', '2017_08_04_025839_option', '1');
INSERT INTO `migrations` VALUES ('58', '2017_08_04_025905_product_option', '1');
INSERT INTO `migrations` VALUES ('59', '2017_08_04_025930_transactions', '1');
INSERT INTO `migrations` VALUES ('60', '2017_08_04_030041_carts', '1');
INSERT INTO `migrations` VALUES ('61', '2017_08_04_030123_address', '1');
INSERT INTO `migrations` VALUES ('62', '2017_08_04_030146_shipping_address', '1');
INSERT INTO `migrations` VALUES ('63', '2017_08_04_030216_profile', '1');
INSERT INTO `migrations` VALUES ('64', '2017_08_04_030248_requests', '1');
INSERT INTO `migrations` VALUES ('65', '2017_08_04_030316_product_image_requests', '1');
INSERT INTO `migrations` VALUES ('66', '2017_08_04_030421_posts', '1');
INSERT INTO `migrations` VALUES ('67', '2017_08_04_030442_comments', '1');
INSERT INTO `migrations` VALUES ('68', '2017_08_04_030531_image', '1');
INSERT INTO `migrations` VALUES ('69', '2017_08_04_030617_emails', '1');
INSERT INTO `migrations` VALUES ('70', '2017_08_04_030646_video', '1');
INSERT INTO `migrations` VALUES ('71', '2017_08_04_030710_information', '1');
INSERT INTO `migrations` VALUES ('72', '2017_08_22_061055_create_notifications_table', '2');

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` int(10) unsigned NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_id_notifiable_type_index` (`notifiable_id`,`notifiable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of notifications
-- ----------------------------
INSERT INTO `notifications` VALUES ('5371cd92-8838-45c0-92c1-c3e0e395de88', 'App\\Notifications\\NewOrder', '14', 'App\\User', '{\"order_id\":\"1220170823062540\"}', '2017-08-26 12:53:41', '2017-08-23 06:25:51', '2017-08-26 12:53:41');
INSERT INTO `notifications` VALUES ('710df95e-717e-491a-a7ac-ad0c37ec164c', 'App\\Notifications\\NewOrder', '14', 'App\\User', '{\"order_id\":\"1220170827070524\"}', null, '2017-08-27 07:05:46', '2017-08-27 07:05:46');
INSERT INTO `notifications` VALUES ('aab33a42-be2a-48bf-ba2a-5a4694773263', 'App\\Notifications\\NewOrder', '14', 'App\\User', '{\"order_id\":\"1220170827021051\"}', null, '2017-08-27 14:11:01', '2017-08-27 14:11:01');
INSERT INTO `notifications` VALUES ('aece12be-9039-44af-bc5d-7ed0d9e376d4', 'App\\Notifications\\NewOrder', '14', 'App\\User', '{\"order_id\":\"1220170827021547\"}', null, '2017-08-27 14:15:57', '2017-08-27 14:15:57');
INSERT INTO `notifications` VALUES ('e44adac7-f803-47c6-9cba-ed474de131de', 'App\\Notifications\\CommentArticle', '14', 'App\\User', '{\"comment_id\":10,\"post_id\":\"5\",\"name\":\"Izira Milas\"}', null, '2017-08-27 14:08:53', '2017-08-27 14:08:53');
INSERT INTO `notifications` VALUES ('fde4b0eb-0932-4028-ba1f-4ff1f4283eff', 'App\\Notifications\\CommentArticle', '14', 'App\\User', '{\"comment_id\":9,\"post_id\":\"9\",\"name\":\"Salim Arizi\"}', null, '2017-08-26 12:33:45', '2017-08-26 12:33:45');

-- ----------------------------
-- Table structure for options
-- ----------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_option_id` int(10) unsigned NOT NULL,
  `option_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`option_id`),
  KEY `options_group_option_id_foreign` (`group_option_id`),
  CONSTRAINT `options_group_option_id_foreign` FOREIGN KEY (`group_option_id`) REFERENCES `group_options` (`group_option_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of options
-- ----------------------------

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------
INSERT INTO `password_resets` VALUES ('salim9asmp1pdl@gmail.com', 'rqeibrNOJQZycjPjCnqGgXYNCnOOHPapFw4Nbcn3t51qTnxXlY4VWY0Pddkb84S4', '2017-08-28 08:00:01');

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `writer_id` int(10) unsigned NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_id`),
  KEY `posts_writer_id_foreign` (`writer_id`),
  CONSTRAINT `posts_writer_id_foreign` FOREIGN KEY (`writer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of posts
-- ----------------------------
INSERT INTO `posts` VALUES ('4', '14', 'Judulnya', 'asdsadsad', '1502264405.png', '2017-08-09 07:40:05', '2017-08-09 07:40:05');
INSERT INTO `posts` VALUES ('5', '14', 'Judulnya2', '<p>sadadwr3243</p>', '1502269736.png', '2017-08-09 09:08:56', '2017-08-09 09:08:56');
INSERT INTO `posts` VALUES ('6', '14', 'asdajkdsj', '<p>sadjlaksd</p>\r\n\r\n<p>sajdklasjd</p>\r\n\r\n<p>asdjlkasd</p>', '1502346450.png', '2017-08-10 06:27:30', '2017-08-10 06:27:30');
INSERT INTO `posts` VALUES ('7', '14', 'judul artikel ini adalah', '<p>sfdasdkjaksdja</p>\r\n\r\n<p><img alt=\"\" src=\"http://localhost:8000/photos//1.png\" style=\"height:768px; width:1366px\" /></p>\r\n\r\n<p>sadasdsadasd</p>', '1502350285.png', '2017-08-10 07:31:25', '2017-08-10 07:31:25');
INSERT INTO `posts` VALUES ('8', '14', 'Ini artikel dari Android', 'Artikel ini dikirim oleh mobile apps milik indikraf', '1503022003.png', '2017-08-18 02:06:42', '2017-08-18 02:06:43');
INSERT INTO `posts` VALUES ('9', '14', 'asjlkdjalskjd', '<p>asdjklasdlkasjd</p>\r\n\r\n<p><img alt=\"\" src=\"http://localhost:8000/photos//Bukti daftar.png\" style=\"height:768px; width:1366px\" />asldkjk;asldkasl;dk</p>', '1503288110.png', '2017-08-21 04:01:50', '2017-08-21 04:01:50');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `store_id` int(10) NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` double(10,0) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `stock` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `products_seller_id_foreign` (`seller_id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE,
  CONSTRAINT `products_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('7', '14', '1', '1', 'Baju Bagus', 'Baju ini baju bagus', '1000', '55000', '84', '2017-08-07 06:52:50', '2017-08-27 14:10:51');
INSERT INTO `products` VALUES ('8', '14', '1', '1', 'Baju Biasa', 'Baju ini bukan baju biasa', '1000', '3000', '99', '2017-08-08 03:14:19', '2017-08-27 07:05:24');
INSERT INTO `products` VALUES ('9', '14', '1', '2', 'Baju aja', 'ini adalah baju', '2000', '100000', '200', '2017-08-16 02:01:27', '2017-08-21 05:51:25');
INSERT INTO `products` VALUES ('11', '14', '2', '1', 'Celana', 'Ini adalah celana biasa luar', '1000', '200000', '9', '2017-08-18 01:55:29', '2017-08-27 14:15:47');

-- ----------------------------
-- Table structure for products_options
-- ----------------------------
DROP TABLE IF EXISTS `products_options`;
CREATE TABLE `products_options` (
  `product_option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_option_id` int(10) unsigned NOT NULL,
  `option_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `additional_price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_option_id`),
  KEY `products_options_group_option_id_foreign` (`group_option_id`),
  KEY `products_options_option_id_foreign` (`option_id`),
  KEY `products_options_product_id_foreign` (`product_id`),
  CONSTRAINT `products_options_group_option_id_foreign` FOREIGN KEY (`group_option_id`) REFERENCES `group_options` (`group_option_id`) ON DELETE CASCADE,
  CONSTRAINT `products_options_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `options` (`option_id`) ON DELETE CASCADE,
  CONSTRAINT `products_options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of products_options
-- ----------------------------

-- ----------------------------
-- Table structure for product_images
-- ----------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `product_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `product_image_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_image_id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of product_images
-- ----------------------------
INSERT INTO `product_images` VALUES ('10', '7', '1502088770.jpg', '2017-08-07 06:52:50', '2017-08-07 06:52:50');
INSERT INTO `product_images` VALUES ('11', '7', '7031dd65d02ce1e0d90ad0eabd2ed4f1ea55aa901502088852.jpg', '2017-08-07 06:54:12', '2017-08-07 06:54:12');
INSERT INTO `product_images` VALUES ('12', '8', '1502162059.png', '2017-08-08 03:14:19', '2017-08-08 03:14:19');
INSERT INTO `product_images` VALUES ('13', '8', 'd72f157396c5b67c7502e554bdf687066cd3c83b1502162071.png', '2017-08-08 03:14:31', '2017-08-08 03:14:31');
INSERT INTO `product_images` VALUES ('14', '7', '36462f1bcff3efefa692139179aaa46bdc84ba8f1502423726.png', '2017-08-11 03:55:26', '2017-08-11 03:55:26');
INSERT INTO `product_images` VALUES ('15', '7', '5c20dcbcfbab07ab6c2df7e27444d5ac2afca5691502423726.png', '2017-08-11 03:55:26', '2017-08-11 03:55:26');
INSERT INTO `product_images` VALUES ('16', '9', '1502848887.jpg', '2017-08-16 02:01:27', '2017-08-16 02:01:27');
INSERT INTO `product_images` VALUES ('18', '11', '1503021329.png', '2017-08-18 01:55:29', '2017-08-18 01:55:29');
INSERT INTO `product_images` VALUES ('19', '11', '5c20dcbcfbab07ab6c2df7e27444d5ac2afca5691503021339.png', '2017-08-18 01:55:39', '2017-08-18 01:55:39');
INSERT INTO `product_images` VALUES ('20', '11', '7031dd65d02ce1e0d90ad0eabd2ed4f1ea55aa901503021375.jpg', '2017-08-18 01:56:15', '2017-08-18 01:56:15');

-- ----------------------------
-- Table structure for profiles
-- ----------------------------
DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `profile_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`profile_id`),
  KEY `profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of profiles
-- ----------------------------
INSERT INTO `profiles` VALUES ('7', '12', 'Izira', 'Milas', 'Laki-laki', '12_Izira.jpg', '2017-08-04 08:03:05', '2017-08-26 23:37:10');
INSERT INTO `profiles` VALUES ('9', '14', 'Salim', 'Admin1', 'Laki-laki', '14_Salim.png', '2017-08-07 04:22:34', '2017-08-24 04:01:03');
INSERT INTO `profiles` VALUES ('15', '19', 'Salim', 'Arizi', 'Laki-laki', null, '2017-08-08 09:46:44', '2017-08-08 09:46:44');
INSERT INTO `profiles` VALUES ('16', '20', 'salim', 'arizi', null, null, '2017-08-09 01:54:43', '2017-08-09 01:54:43');
INSERT INTO `profiles` VALUES ('17', '21', 'salim', 'arizi', null, null, '2017-08-24 06:44:38', '2017-08-24 06:44:38');

-- ----------------------------
-- Table structure for ratings
-- ----------------------------
DROP TABLE IF EXISTS `ratings`;
CREATE TABLE `ratings` (
  `rating_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `rating` int(2) NOT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rating_id`),
  KEY `ratings_user_id_foreign` (`user_id`),
  KEY `ratings_product_id_foreign` (`product_id`),
  CONSTRAINT `ratings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of ratings
-- ----------------------------
INSERT INTO `ratings` VALUES ('3', '7', '12', '3', 'sajdlkasjdlkjasldjlasjdlkjalskdjlkasjdlkjaslkdjlaksjdlksajlkdjaskasdasd', '2017-08-16 07:00:31', '2017-08-16 07:00:31');
INSERT INTO `ratings` VALUES ('7', '8', '12', '5', 'ini adalah komentar/review untuk produk ini', '2017-08-25 02:24:31', '2017-08-25 02:24:31');
INSERT INTO `ratings` VALUES ('9', '9', '12', '5', 'ini adalah komentar/review untuk produk ini', '2017-08-25 02:24:31', '2017-08-25 02:24:31');

-- ----------------------------
-- Table structure for requests
-- ----------------------------
DROP TABLE IF EXISTS `requests`;
CREATE TABLE `requests` (
  `request_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('Website','Instagram','Facebook','Image') COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of requests
-- ----------------------------
INSERT INTO `requests` VALUES ('1', 'salim9asmp1pdl@gmail.com', '87822667801', 'Website', 'www.toko-saya.com', '2017-08-21 02:17:40', '2017-08-21 02:33:14', null);
INSERT INTO `requests` VALUES ('2', 'salim9asmp1pdl@gmail.com', '87822667801', 'Image', '/uploads/gambar_request_produk/1503281950.png', '2017-08-21 02:19:10', '2017-08-21 02:19:10', null);
INSERT INTO `requests` VALUES ('3', 'sad@gmail.com', '087', 'Image', '/uploads/gambar_request_produk/1503566006.png', '2017-08-24 09:13:26', '2017-08-24 09:13:26', null);
INSERT INTO `requests` VALUES ('4', 'sad@gmail.com', '087', 'Website', 'www.ada.com', '2017-08-24 09:20:50', '2017-08-24 09:20:50', null);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `roles_role_name_unique` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'Admin', '2017-08-04 15:07:11', '2017-08-04 15:07:15');
INSERT INTO `roles` VALUES ('2', 'Member', '2017-08-04 15:07:45', '2017-08-04 15:07:48');

-- ----------------------------
-- Table structure for shipping_addresses
-- ----------------------------
DROP TABLE IF EXISTS `shipping_addresses`;
CREATE TABLE `shipping_addresses` (
  `shipping_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`shipping_id`),
  KEY `shipping_address_address_id_foreign` (`address_id`),
  CONSTRAINT `shipping_address_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of shipping_addresses
-- ----------------------------
INSERT INTO `shipping_addresses` VALUES ('6', '1220170821090527', '4', '2017-08-21 09:05:27', '2017-08-21 09:05:27');
INSERT INTO `shipping_addresses` VALUES ('8', '1220170823040718', '11', '2017-08-23 04:07:18', '2017-08-23 04:07:18');
INSERT INTO `shipping_addresses` VALUES ('9', '1220170823043531', '4', '2017-08-23 04:35:32', '2017-08-23 04:35:32');
INSERT INTO `shipping_addresses` VALUES ('10', '1220170823043653', '4', '2017-08-23 04:36:54', '2017-08-23 04:36:54');
INSERT INTO `shipping_addresses` VALUES ('11', '1220170823043757', '4', '2017-08-23 04:37:57', '2017-08-23 04:37:57');
INSERT INTO `shipping_addresses` VALUES ('12', '1220170823043955', '4', '2017-08-23 04:39:56', '2017-08-23 04:39:56');
INSERT INTO `shipping_addresses` VALUES ('13', '1220170823044045', '4', '2017-08-23 04:40:46', '2017-08-23 04:40:46');
INSERT INTO `shipping_addresses` VALUES ('14', '1220170823044134', '4', '2017-08-23 04:41:35', '2017-08-23 04:41:35');
INSERT INTO `shipping_addresses` VALUES ('15', '1220170823044332', '4', '2017-08-23 04:43:32', '2017-08-23 04:43:32');
INSERT INTO `shipping_addresses` VALUES ('16', '1220170823045509', '4', '2017-08-23 04:55:10', '2017-08-23 04:55:10');
INSERT INTO `shipping_addresses` VALUES ('17', '1220170823045617', '4', '2017-08-23 04:56:18', '2017-08-23 04:56:18');
INSERT INTO `shipping_addresses` VALUES ('18', '1220170823061226', '4', '2017-08-23 06:12:27', '2017-08-23 06:12:27');
INSERT INTO `shipping_addresses` VALUES ('19', '1220170823061505', '4', '2017-08-23 06:15:05', '2017-08-23 06:15:05');
INSERT INTO `shipping_addresses` VALUES ('20', '1220170823062540', '4', '2017-08-23 06:25:41', '2017-08-23 06:25:41');
INSERT INTO `shipping_addresses` VALUES ('21', '1220170827070524', '4', '2017-08-27 07:05:24', '2017-08-27 07:05:24');
INSERT INTO `shipping_addresses` VALUES ('22', '1220170827021051', '4', '2017-08-27 14:10:51', '2017-08-27 14:10:51');
INSERT INTO `shipping_addresses` VALUES ('23', '1220170827021547', '11', '2017-08-27 14:15:47', '2017-08-27 14:15:47');

-- ----------------------------
-- Table structure for stores
-- ----------------------------
DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `store_id` int(10) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(50) NOT NULL,
  `store_address` text NOT NULL,
  `store_city` int(11) NOT NULL,
  `store_postal_code` varchar(6) NOT NULL,
  `store_email` varchar(100) NOT NULL,
  `store_phone` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of stores
-- ----------------------------
INSERT INTO `stores` VALUES ('1', 'Indikraf', 'Turangga, Buah Batu.', '23', '40264', 'indikraf@gmail.com', '0123456789', '2017-08-20 08:14:27', '2017-08-20 08:14:27');
INSERT INTO `stores` VALUES ('2', 'Toko Salim', 'Puring', '177', '54311', 'salimarizi07@gmail.com', '087822667801', '2017-08-21 05:51:02', '2017-08-21 05:51:02');

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `transaction_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_id` int(10) unsigned NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courier` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courier_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_price` decimal(10,0) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `transactions_buyer_id_foreign` (`buyer_id`),
  CONSTRAINT `transactions_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of transactions
-- ----------------------------
INSERT INTO `transactions` VALUES ('10', '1220170821090527', '12', '1', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '7000', 'success', '2017-08-21 09:05:27', '2017-08-21 09:05:27');
INSERT INTO `transactions` VALUES ('12', '1220170823040718', '12', '1', 'COD', 'JNE', 'Ongkos Kirim Ekonomis (5-7 har', '47000', 'success', '2017-08-23 04:07:18', '2017-08-23 04:07:18');
INSERT INTO `transactions` VALUES ('13', '1220170823043531', '12', '1', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '7000', 'success', '2017-08-23 04:35:31', '2017-08-23 04:35:31');
INSERT INTO `transactions` VALUES ('14', '1220170823043653', '12', '1', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '7000', 'success', '2017-08-23 04:36:53', '2017-08-23 04:36:53');
INSERT INTO `transactions` VALUES ('15', '1220170823043757', '12', '1', 'COD', 'POS', 'Surat Kilat Khusus (2 hari)', '9000', 'success', '2017-08-23 04:37:57', '2017-08-23 04:37:57');
INSERT INTO `transactions` VALUES ('16', '1220170823043955', '12', '1', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '7000', 'success', '2017-08-23 04:39:55', '2017-08-23 04:39:55');
INSERT INTO `transactions` VALUES ('17', '1220170823044045', '12', '1', 'COD', 'JNE', 'JNE City Courier (2-3 hari)', '6000', 'success', '2017-08-23 04:40:45', '2017-08-23 04:40:45');
INSERT INTO `transactions` VALUES ('18', '1220170823044134', '12', '1', 'COD', 'JNE', 'JNE City Courier (2-3 hari)', '6000', 'success', '2017-08-23 04:41:34', '2017-08-23 04:41:34');
INSERT INTO `transactions` VALUES ('19', '1220170823044332', '12', '1', 'COD', 'JNE', 'JNE City Courier (2-3 hari)', '6000', 'success', '2017-08-23 04:43:32', '2017-08-23 04:43:32');
INSERT INTO `transactions` VALUES ('20', '1220170823045509', '12', '1', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '7000', 'success', '2017-08-23 04:55:09', '2017-08-23 04:55:09');
INSERT INTO `transactions` VALUES ('21', '1220170823045617', '12', '1', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '7000', 'success', '2017-08-23 04:56:17', '2017-08-23 04:56:17');
INSERT INTO `transactions` VALUES ('22', '1220170823061226', '12', '1', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '7000', 'success', '2017-08-23 06:12:26', '2017-08-23 06:12:26');
INSERT INTO `transactions` VALUES ('23', '1220170823061505', '12', '1', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '7000', 'success', '2017-08-23 06:15:05', '2017-08-23 06:15:05');
INSERT INTO `transactions` VALUES ('24', '1220170823062540', '12', '1', 'COD', 'JNE', 'JNE City Courier (2-3 hari)', '6000', 'success', '2017-08-23 06:25:40', '2017-08-23 06:25:40');
INSERT INTO `transactions` VALUES ('25', '1220170827070524', '12', '3', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '14000', 'success', '2017-08-27 07:05:24', '2017-08-27 07:05:24');
INSERT INTO `transactions` VALUES ('26', '1220170827021051', '12', '1', 'COD', 'JNE', 'JNE City Courier (1-2 hari)', '7000', 'success', '2017-08-27 14:10:51', '2017-08-27 14:10:51');
INSERT INTO `transactions` VALUES ('27', '1220170827021547', '12', '1', 'COD', 'JNE', 'Ongkos Kirim Ekonomis (5-7 har', '47000', 'success', '2017-08-27 14:15:47', '2017-08-27 14:15:47');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `verification_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('12', '2', 'salim9asmp1pdl@gmail.com', '$2y$10$6hfVl5qNNlfZqKiHgnfuBekoz08zLxfpmGsDhG5XfrEfUzI3wmL1.', '1', null, 'vG7V2C4486ErQNPHGGl1AI7TcoEwxHlfsQvIlbCtpLXIUmN2FEbxFcN4krqp', '2017-08-04 08:03:05', '2017-08-26 23:37:10');
INSERT INTO `users` VALUES ('14', '1', 'salimarizi07@gmail.com', '$2y$10$kXhYlVznEtCAJXWvRI5UMuuT0u0KLHNtl0z98mnxAVAIjJzEU5Kl2', '1', null, 'wON2jf9koYcoRs07rwyfnQcVBRqjKzYhImypeDw2h87nkHOXaE8jREkYxIl0', '2017-08-07 04:22:34', '2017-08-07 04:24:13');
INSERT INTO `users` VALUES ('19', '2', 'salim9asmp1pdl1@gmail.com', '$2y$10$11ri.NV9UHMrOLGCIT.IiufisxsQFdc9DLKFmhaBiU2xXXgh8VWPy', '0', 'U4dbLuEr8WJSDYJILll7scSmkdM0DsrZcdW3IfeF', null, '2017-08-08 09:46:44', '2017-08-08 09:46:44');
INSERT INTO `users` VALUES ('20', '2', 'salim9asmp1pdl2@gmail.com', '$2y$10$jAcNSDr/9Mt3vRjhVgMMzOGKiA2Yb23Panv73moMrmP7Xw37UcFEi', '0', 'enhVtcyITHZWhkqH4DveASyWah8G7npKXR02gsP6', null, '2017-08-09 01:54:43', '2017-08-09 01:54:43');
INSERT INTO `users` VALUES ('21', '2', 'salim9asmp11pdl@gmail.com', '$2y$10$ngxXCMIEnqEhpJWJMB7OHeK2NMWME14Kq8WyKuxhgr7iYdpdHD7UK', '0', 'G7ygpsIMfYwAnTImwBRbuTmJV5z3RrFO4Qo1AN0h', null, '2017-08-24 06:44:38', '2017-08-24 06:44:38');

-- ----------------------------
-- Table structure for videos
-- ----------------------------
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `video_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `video_url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of videos
-- ----------------------------
INSERT INTO `videos` VALUES ('11', 'https://www.youtube.com/watch?v=DTdV35XOr6A', '2017-08-14 02:51:45', '2017-08-14 02:51:45');
INSERT INTO `videos` VALUES ('12', 'https://www.youtube.com/watch?v=xZ3E4z3PALM', '2017-08-14 02:51:45', '2017-08-14 02:51:45');
INSERT INTO `videos` VALUES ('13', 'https://www.youtube.com/watch?v=GAubLJry07I', '2017-08-14 02:51:45', '2017-08-14 02:51:45');
INSERT INTO `videos` VALUES ('15', 'https://www.youtube.com/watch?v=DTdV35XOr6A', '2017-08-14 02:51:45', '2017-08-14 02:51:45');
