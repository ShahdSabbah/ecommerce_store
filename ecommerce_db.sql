-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2026 at 04:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'عطور', 'Reiciendis provident aliquam omnis aut odio.', '2026-08-27 06:44:18', '2026-08-27 06:44:18'),
(2, 'ملابس', 'Mollitia aut et qui praesentium.', '2026-08-27 06:44:18', '2026-08-27 06:44:18'),
(3, 'إكسسوارات', 'Tempora ab voluptatem quis vitae.', '2026-08-27 06:44:18', '2026-08-27 06:44:18'),
(4, 'إلكترونيات', 'Quisquam quia est doloremque iste.', '2026-08-27 06:44:18', '2026-08-27 06:44:18'),
(5, 'أثاث', 'Veritatis et et eaque voluptatem.', '2026-08-27 06:44:18', '2026-08-27 06:44:18'),
(6, 'أثاث', 'Et quisquam ea non nobis.', '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(7, 'ملابس', 'Omnis sed ducimus aut et quae qui qui.', '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(8, 'أحذية', 'Nesciunt aperiam ut sit error explicabo.', '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(9, 'إلكترونيات', 'Provident iusto unde sit et in facere.', '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(10, 'إكسسوارات', 'Voluptates esse totam vero sed harum enim ab quo.', '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(11, 'إلكترونيات', 'Et dolores saepe facere eum accusantium aliquid.', '2026-08-27 13:04:47', '2026-08-27 13:04:47'),
(12, 'ملابس', 'Dolor aut voluptatem nam animi laboriosam maiores voluptatem culpa.', '2026-08-27 13:04:47', '2026-08-27 13:04:47'),
(13, 'أحذية', 'Aut consequatur quibusdam voluptas expedita voluptatem ipsa.', '2026-08-27 13:04:47', '2026-08-27 13:04:47'),
(14, 'إكسسوارات', 'Sed ipsa voluptatibus ut dolor.', '2026-08-27 13:04:47', '2026-08-27 13:04:47'),
(15, 'عطور', 'Enim quasi dicta iste.', '2026-08-27 13:04:47', '2026-08-27 13:04:47'),
(16, 'ملابس', 'Hic dolor temporibus voluptatibus quo quae.', '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(17, 'إكسسوارات', 'Quia ut totam fugiat aperiam nam fugit.', '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(18, 'إلكترونيات', 'Sunt consequatur aut voluptatem enim.', '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(19, 'أثاث', 'Vero porro tenetur nesciunt quia nobis maiores.', '2026-08-27 13:13:27', '2026-08-27 13:13:27');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_08_23_083441_create_categories_table', 2),
(6, '2026_08_23_083518_create_products_table', 2),
(10, '2026_08_23_083940_create_orders_table', 3),
(11, '2026_08_23_083941_create_order_items_table', 3),
(12, '2026_08_23_083942_create_payments_table', 3),
(13, '2026_08_29_073154_add_role_to_users_table', 4),
(14, '2026_09_09_145627_add_is_super_admin_to_users_table', 5),
(15, '2026_09_10_103516_create_private_documents_table', 6),
(16, '2026_09_11_200717_create_permission_tables', 7),
(17, '2026_09_13_124104_create_carts_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `address`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Buck Dietrich', 'bkreiger@example.com', '+1-917-457-3659', '83460 Rohan Stravenue\nNew Shanieshire, CA 14066', 454.46, 'pending', '2026-08-27 13:13:27', '2026-08-27 13:21:17'),
(2, 'Isidro Hauck', 'streich.bertha@example.org', '857.379.5154', '457 Cassie Track\nEast Lysanneton, CA 56200-5488', 662.84, 'pending', '2026-08-27 13:13:27', '2026-08-28 05:18:19'),
(3, 'Mr. Thomas Ward Jr.', 'romaguera.terrence@example.org', '+1-870-929-2905', '72100 Stanton Crossroad Suite 259\nEast Lenny, OR 91995', 1914.74, 'processing', '2026-08-27 13:13:27', '2026-08-27 13:21:39'),
(4, 'Tierra Wilkinson', 'julianne31@example.com', '747.448.4179', '21266 Dayton Underpass Apt. 057\nLindfurt, TX 75608-3219', 899.16, 'pending', '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(5, 'Bessie Funk', 'adams.cielo@example.org', '1-332-984-4383', '966 Boyer Hollow Suite 176\nEldaton, PA 01944', 2231.36, 'cancelled', '2026-08-27 13:13:27', '2026-08-27 13:13:28'),
(6, 'Declan Hudson', 'bolson@example.com', '425.467.6677', '55871 Wilton Ville\nCorneliusfort, AR 16883-5247', 663.14, 'completed', '2026-08-27 13:13:27', '2026-09-05 04:35:43'),
(7, 'Mrs. Fleta Ziemann Jr.', 'ogoldner@example.com', '+1 (240) 647-0188', '47405 Jenifer Rest Suite 500\nBeaulahville, OR 14143', 1624.24, 'completed', '2026-08-27 13:13:27', '2026-08-27 13:13:28'),
(8, 'Myles Denesik', 'dave20@example.net', '+1.463.936.9565', '22742 Ayla View Suite 962\nNew Mathew, IN 51478-4216', 224.79, 'cancelled', '2026-08-27 13:13:27', '2026-08-27 13:13:28'),
(9, 'Leslie Bechtelar', 'onie.toy@example.com', '+1-602-928-0855', '5748 Summer Coves\nEast Emelia, MD 80130-5202', 944.56, 'cancelled', '2026-08-27 13:13:27', '2026-08-27 13:13:28'),
(10, 'Rocio Olson', 'dkautzer@example.com', '(847) 564-6056', '4090 Brown Expressway\nNorth Susan, IN 73605-0015', 68.37, 'processing', '2026-08-27 13:13:27', '2026-08-27 13:13:28'),
(11, 'ahmed', 'ahmed12@gmail.com', '0591234567', 'غزة - الشارع العام - بالقرب من الجامعة.', 356.47, 'pending', '2026-09-05 10:10:53', '2026-09-05 10:10:53'),
(12, 'shahdsabbah', 'shahedsabbah@gmail.com', '0591234567', 'dsdfhi', 745.81, 'pending', '2026-09-09 11:08:52', '2026-09-09 11:08:52'),
(13, 'shahdsabbah', 'shahedsabbah@gmail.com', '0599000000', 'Nablus, Main Street', 1921.68, 'pending', '2026-09-14 06:30:30', '2026-09-14 06:30:30'),
(14, 'shahdsabbah', 'shahedsabbah@gmail.com', '0599123456', 'Gaza, Palestine', 544.90, 'pending', '2026-09-15 12:25:28', '2026-09-15 12:25:28');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 52, 1, 64.13, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(2, 1, 59, 1, 231.24, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(3, 1, 63, 3, 53.03, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(4, 2, 13, 4, 165.71, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(5, 3, 77, 4, 82.62, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(6, 3, 70, 2, 221.19, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(7, 3, 67, 4, 285.47, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(8, 4, 7, 4, 224.79, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(9, 5, 48, 4, 81.26, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(10, 5, 19, 2, 116.37, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(11, 5, 68, 3, 293.42, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(12, 5, 50, 4, 198.33, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(13, 6, 31, 3, 122.64, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(14, 6, 27, 1, 144.38, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(15, 6, 78, 2, 75.42, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(16, 7, 23, 2, 169.74, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(17, 7, 48, 3, 81.26, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(18, 7, 5, 1, 254.32, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(19, 7, 14, 3, 262.22, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(20, 8, 7, 1, 224.79, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(21, 9, 42, 3, 254.16, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(22, 9, 37, 2, 91.04, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(23, 10, 4, 1, 68.37, '2026-08-27 13:13:28', '2026-08-27 13:13:28'),
(24, 11, 63, 2, 53.03, '2026-09-05 10:10:53', '2026-09-05 10:10:53'),
(25, 11, 62, 1, 250.41, '2026-09-05 10:10:53', '2026-09-05 10:10:53'),
(26, 12, 62, 1, 250.41, '2026-09-09 11:08:52', '2026-09-09 11:08:52'),
(27, 12, 66, 1, 201.98, '2026-09-09 11:08:52', '2026-09-09 11:08:52'),
(28, 12, 68, 1, 293.42, '2026-09-09 11:08:52', '2026-09-09 11:08:52'),
(29, 13, 3, 6, 193.12, '2026-09-14 06:30:30', '2026-09-14 06:30:30'),
(30, 13, 5, 3, 254.32, '2026-09-14 06:30:30', '2026-09-14 06:30:30'),
(31, 14, 2, 2, 272.45, '2026-09-15 12:25:28', '2026-09-15 12:25:28');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash_on_delivery',
  `status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage-products', 'web', '2026-09-11 17:07:50', '2026-09-11 17:07:50'),
(2, 'delete-products', 'web', '2026-09-11 17:07:50', '2026-09-11 17:07:50'),
(3, 'view-orders', 'web', '2026-09-11 17:07:50', '2026-09-11 17:07:50'),
(4, 'manage-users', 'web', '2026-09-11 17:07:50', '2026-09-11 17:07:50');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '2b4498d9dfd79d11587cf9f25f62a383d1473e096856e6d8c59dadef46054c69', '[\"*\"]', '2026-09-12 09:13:47', NULL, '2026-09-12 09:11:53', '2026-09-12 09:13:47'),
(2, 'App\\Models\\User', 1, 'auth_token', '4ffc1c8bdbaf36be1cefab2625b0799d8d1cf33c03a2aedd20c142a45df548fc', '[\"*\"]', NULL, NULL, '2026-09-12 10:05:58', '2026-09-12 10:05:58'),
(3, 'App\\Models\\User', 1, 'auth_token', 'bda217efcba7e9d81642bc87cdef2ae5bf7a0108e6b1095496fa21be0b3994f4', '[\"*\"]', NULL, NULL, '2026-09-12 10:06:08', '2026-09-12 10:06:08'),
(4, 'App\\Models\\User', 4, 'auth_token', '86d03d3c66fa432caca0aa06e41cbf85bd91c3c1b08d93a6d47465088383448e', '[\"*\"]', NULL, NULL, '2026-09-13 08:30:59', '2026-09-13 08:30:59'),
(6, 'App\\Models\\User', 1, 'auth_token', 'f1be7d1b39a3bc0fecc7e843806cf0396d1b11e6ff8552e797dbf5f9da5c4b55', '[\"*\"]', '2026-09-13 10:54:44', NULL, '2026-09-13 10:10:06', '2026-09-13 10:54:44'),
(7, 'App\\Models\\User', 1, 'auth_token', '29aca059cc2f8b4bb9411bcbe8d891b52c3335e2e0f80a7a3a051c7060b765ef', '[\"*\"]', '2026-09-14 06:08:36', NULL, '2026-09-13 10:57:24', '2026-09-14 06:08:36'),
(8, 'App\\Models\\User', 1, 'auth_token', '3f0205b4c145f5059dbdb5ae228d6652f14839f37a27f85d795e3ecbf0c680ca', '[\"*\"]', '2026-09-14 06:09:26', NULL, '2026-09-13 11:03:37', '2026-09-14 06:09:26'),
(9, 'App\\Models\\User', 1, 'auth_token', '49da66e9b5ff6b6e1e9b4113561131dfe5a63385fb50e12acc0dbfde4dc28202', '[\"*\"]', '2026-09-14 06:15:35', NULL, '2026-09-14 06:06:25', '2026-09-14 06:15:35'),
(10, 'App\\Models\\User', 1, 'auth_token', '090951606bef7ff874c2c499dd1145173bbd0018fcd5058aa93e346bf5c39ebd', '[\"*\"]', '2026-09-14 06:52:08', NULL, '2026-09-14 06:16:02', '2026-09-14 06:52:08'),
(11, 'App\\Models\\User', 5, 'auth_token', '55b4bd832b49d945cf47a201e91d08b5e85aac671cdcd641d89ec9df1eaed5a9', '[\"*\"]', NULL, NULL, '2026-09-15 12:58:26', '2026-09-15 12:58:26'),
(12, 'App\\Models\\User', 5, 'auth_token', '7f77bcf277d535c9766264235b6bb572082298c32abda8f4509fd7119f5e197c', '[\"*\"]', NULL, NULL, '2026-09-15 12:58:48', '2026-09-15 12:58:48'),
(13, 'App\\Models\\User', 5, 'auth_token', '2d7997d6dec8fc1e3ee5cc235c0186300a17cbf57ff14c0a5a61418ccb5d93d3', '[\"*\"]', NULL, NULL, '2026-09-15 13:01:29', '2026-09-15 13:01:29'),
(14, 'App\\Models\\User', 5, 'auth_token', 'd50e84696baa2efcf9bdc59cc6c96dc9b0e9dcd62a7749f3e49ecbb18b3e5b1e', '[\"*\"]', NULL, NULL, '2026-09-15 13:03:47', '2026-09-15 13:03:47'),
(16, 'App\\Models\\User', 5, 'auth_token', 'be351fd3e176bd4052138a00766195412fb9514ab3dac53f6354ad9ba79a38f0', '[\"*\"]', NULL, NULL, '2026-09-17 09:31:49', '2026-09-17 09:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `private_documents`
--

CREATE TABLE `private_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'user_document',
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `private_documents`
--

INSERT INTO `private_documents` (`id`, `user_id`, `title`, `type`, `file_path`, `file_name`, `file_size`, `mime_type`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'فاتورة توريد407', 'user_document', 'documents/QykOtog8dAnAK8K8SxnQXR0w69nf8xibZs3tFXCQ.webp', 'img1.webp', 7676, 'image/webp', NULL, '2026-09-10 11:01:00', '2026-09-10 11:01:00'),
(2, 1, 'هوية', 'user_document', 'documents/SkZMCvTbrEdBKUwU1UwwLc5Q3NKSMmas0H8i6O9d.png', '1.png', 30014, 'image/png', NULL, '2026-09-17 10:34:05', '2026-09-17 10:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `stock`, `image`, `created_at`, `updated_at`) VALUES
(1, 5, 'temporibus Lite', 'Reprehenderit labore sit hic voluptas quod aut autem.', 21.71, 35, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(2, 2, 'commodi Max', 'Molestiae accusantium velit nihil expedita eum consequuntur aliquid.', 272.45, 23, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(3, 4, 'quis Max', 'Id autem soluta quaerat minus ut tempore.', 193.12, 6, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(4, 1, 'vel Max', 'Vel qui ad qui consequatur et est.', 68.37, 36, NULL, '2026-08-27 06:44:19', '2026-08-27 06:46:13'),
(5, 5, 'sed Pro', 'Architecto et et tenetur animi quod quis.', 254.32, 16, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(6, 4, 'et Max', 'Aspernatur et voluptates nobis laborum excepturi.', 206.06, 6, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(7, 2, 'et Lite', 'Praesentium repellat quia consequuntur necessitatibus corporis et.', 224.79, 5, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(8, 2, 'porro Lite', 'Ab iure sed nulla aut expedita quia aut.', 135.82, 26, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(9, 2, 'sequi Lite', 'Est aut totam laborum dolor doloribus.', 60.87, 36, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(10, 3, 'harum Pro', 'Repudiandae ut quo ex dolore.', 100.81, 8, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(11, 4, 'aliquid Max', 'Architecto neque id omnis accusamus optio omnis veniam.', 193.74, 44, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(12, 4, 'quos Max', 'Voluptatibus ullam accusantium aperiam sed voluptas necessitatibus recusandae.', 28.72, 39, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(13, 3, 'et Max', 'Harum qui nesciunt vitae enim.', 165.71, 42, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(14, 5, 'atque Lite', 'Sunt et rerum velit optio aliquam ut.', 262.22, 26, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(15, 3, 'maxime Max', 'Ut non laboriosam cumque.', 97.04, 35, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(16, 1, 'commodi Lite', 'Tempora quod perferendis officia reprehenderit.', 39.27, 25, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(17, 2, 'enim Pro', 'Recusandae autem maiores minima magni omnis qui.', 24.01, 5, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(18, 2, 'quam Pro', 'Cum dolor vero molestiae repellat.', 100.64, 42, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(19, 4, 'omnis Lite', 'Adipisci quisquam neque earum.', 116.37, 30, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(20, 2, 'nam Lite', 'Aut quis maiores consectetur magnam ad commodi ut.', 265.38, 15, NULL, '2026-08-27 06:44:19', '2026-08-27 06:44:19'),
(21, 6, 'quasi Max', 'Velit rerum culpa cumque ad aperiam consequatur.', 274.90, 46, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(22, 1, 'culpa Lite', 'Voluptatum officiis dolores facilis molestiae.', 195.67, 25, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(23, 7, 'error Max', 'Quos quibusdam perspiciatis accusamus voluptates quia.', 169.74, 5, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(24, 3, 'nemo Lite', 'Voluptatem hic eveniet voluptatibus unde aut cum.', 247.16, 6, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(25, 1, 'adipisci Pro', 'Molestiae consequatur cupiditate totam molestiae voluptate nulla.', 270.15, 46, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(26, 4, 'labore Max', 'Aliquam excepturi et perferendis quo ut.', 33.52, 27, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(27, 5, 'ut Max', 'Modi sit accusamus placeat et.', 144.38, 37, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(28, 6, 'qui Max', 'Tempore laboriosam autem debitis magni deleniti quia qui ipsam.', 44.01, 18, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(29, 3, 'quidem Pro', 'Aliquid omnis esse aperiam velit in.', 178.23, 10, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(30, 6, 'quia Pro', 'Aliquam quae quis quas temporibus.', 275.65, 48, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(31, 1, 'dolore Lite', 'Dolores rem molestiae consectetur dolor magnam reiciendis odit repellat.', 122.64, 14, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(32, 2, 'in Pro', 'Et fuga eum quam aut.', 221.62, 42, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(33, 3, 'repudiandae Max', 'Mollitia dolor quaerat cum dolor sequi harum et.', 83.69, 46, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(34, 5, 'consequatur Lite', 'Facilis et nam atque rerum.', 185.28, 37, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(35, 9, 'cupiditate Pro', 'Necessitatibus deleniti officia labore molestiae.', 177.73, 37, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(36, 10, 'maiores Lite', 'Qui et voluptas consequatur error eos.', 263.04, 13, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(37, 2, 'eos Max', 'In sit est at nostrum dolores dolore consequatur.', 91.04, 50, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(38, 2, 'sint Pro', 'Dolore rem deleniti doloribus laborum nulla quo saepe.', 220.20, 30, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(39, 1, 'molestias Max', 'Sed rerum labore molestiae ducimus et.', 190.57, 40, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(40, 8, 'qui Lite', 'Tempora aperiam nobis nesciunt est nobis.', 193.56, 12, NULL, '2026-08-27 12:44:04', '2026-08-27 12:44:04'),
(41, 4, 'velit Pro', 'Tempora qui quos quod deleniti odit necessitatibus.', 25.41, 41, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(42, 12, 'eligendi Pro', 'Provident aut dolores saepe.', 254.16, 34, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(43, 15, 'architecto Max', 'Aperiam optio in excepturi quo qui.', 158.35, 7, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(44, 15, 'aut Pro', 'Ut enim et qui et dolore voluptatem.', 241.70, 8, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(45, 1, 'nisi Max', 'Saepe voluptas accusantium et ducimus deserunt ut.', 66.65, 16, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(46, 15, 'exercitationem Pro', 'A aut expedita velit aut.', 107.32, 32, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(47, 12, 'voluptatibus Lite', 'Libero qui consequatur aut ea cum qui.', 251.93, 14, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(48, 12, 'dolorem Pro', 'Laboriosam tenetur quis similique at repellendus blanditiis et.', 81.26, 37, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(49, 1, 'aliquid Lite', 'Omnis perferendis sed at consequatur nostrum laborum.', 97.02, 24, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(50, 12, 'ut Max', 'Non eaque ad sed.', 198.33, 43, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(51, 4, 'quia Lite', 'Nesciunt sunt suscipit et harum aperiam.', 88.11, 40, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(52, 11, 'ut Pro', 'Non repellat sit corrupti qui voluptatem omnis consequuntur.', 64.13, 42, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(53, 6, 'iure Lite', 'Nemo voluptas et ullam nihil voluptatem voluptate suscipit.', 46.57, 35, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(54, 11, 'vel Pro', 'Laborum modi tenetur quas velit ex omnis.', 174.15, 23, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(55, 7, 'doloribus Lite', 'Similique cumque deleniti facilis quo officia cumque nihil.', 265.39, 47, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(56, 6, 'doloribus Pro', 'Fugiat doloribus quisquam saepe aliquam qui.', 260.37, 37, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(57, 15, 'deserunt Lite', 'Voluptatibus exercitationem voluptatem dolore delectus qui autem quod sequi.', 30.01, 33, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(58, 14, 'adipisci Lite', 'Velit facere rerum culpa.', 162.73, 30, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(59, 3, 'doloribus Lite', 'Nihil eum exercitationem quia quis.', 231.24, 46, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(60, 3, 'nobis Max', 'Eligendi quo et rem quae.', 180.27, 14, NULL, '2026-08-27 13:04:48', '2026-08-27 13:04:48'),
(61, 1, 'dolores Max', 'Optio deleniti sint velit incidunt labore.', 71.04, 43, 'products/MrhV8ZnRe0AlagvB1IePEcSu2aVTl3azDlaTsJxf.jpg', '2026-08-27 13:13:27', '2026-09-05 04:24:56'),
(62, 15, 'quas Max', 'Voluptas aut rerum id esse facere nobis eos sunt.', 250.41, 24, 'products/Wbl3qJitqUSb2CVVOXQZB3AEhVBN2kjhARlOYtDp.jpg', '2026-08-27 13:13:27', '2026-09-05 04:25:16'),
(63, 2, 'sint Lite', 'Consequatur vel qui vitae corrupti nostrum aut voluptatem eius.', 53.03, 35, 'products/jF4wEXtObzyPxgeBqWrgE80nFLDtMcqKKIcUfhu7.jpg', '2026-08-27 13:13:27', '2026-09-05 04:32:28'),
(64, 14, 'corporis Lite', 'Blanditiis ipsum excepturi sed voluptatibus et sint omnis nemo.', 98.60, 48, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(66, 15, 'ullam Max', 'Facilis et et et similique labore earum.', 201.98, 5, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(67, 14, 'animi Pro', 'Vel qui temporibus ut nihil.', 285.47, 42, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(68, 4, 'itaque Pro', 'Animi vel non a.', 293.42, 5, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(70, 4, 'veniam Pro', 'Minima aut odit aut quasi.', 221.19, 7, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(71, 2, 'qui Pro', 'A nostrum ratione labore.', 174.55, 9, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(72, 1, 'recusandae Max', 'Qui autem ipsum dolorem eos.', 88.70, 30, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(73, 9, 'velit Max', 'Natus et corporis alias.', 203.50, 45, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(74, 7, 'facilis Pro', 'Non dignissimos quis facilis ducimus repudiandae.', 252.19, 47, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(75, 16, 'nihil Max', 'Et dolorem molestiae ex.', 242.72, 36, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(76, 7, 'id Lite', 'Sequi aut veritatis recusandae possimus.', 279.98, 30, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(77, 3, 'ut Pro', 'Molestiae ea consectetur suscipit.', 82.62, 44, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(78, 1, 'sed Lite', 'Odio voluptatem neque molestiae qui ut error mollitia.', 75.42, 5, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(79, 18, 'qui Pro', 'Doloremque blanditiis veritatis et sit et cum.', 224.77, 32, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(80, 3, 'molestiae Lite', 'Ab asperiores omnis dignissimos fugiat pariatur similique.', 260.73, 8, NULL, '2026-08-27 13:13:27', '2026-08-27 13:13:27'),
(81, 4, 'USB Keyboard', NULL, 55.00, 15, 'products/otxGk1iy0PpyIVjsCSUgScN57h6V3X8BlzsLDYsh.png', '2026-08-27 13:33:17', '2026-08-27 13:33:17');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2026-09-11 17:07:50', '2026-09-11 17:07:50'),
(2, 'admin', 'web', '2026-09-11 17:07:50', '2026-09-11 17:07:50'),
(3, 'customer', 'web', '2026-09-11 17:07:50', '2026-09-11 17:07:50');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 1),
(3, 2),
(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `is_super_admin`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'shahdsabbah', 'shahedsabbah@gmail.com', 'admin', 1, NULL, '$2y$12$9Ou9FiPRVKCqwqvf5Pw8zOEZvzoUs2/f.tFcDcBKPnxvD32W71C1u', 'kjdRNHGvQJ2AcWRYsn6lbBwicautfnMe6VROEaaJr1P2gLY7EfiIOL8lzbVL', '2026-08-23 06:34:41', '2026-09-09 11:56:41'),
(2, 'ahmed', 'ahmed12@gmail.com', 'customer', 0, NULL, '$2y$12$qRLjfJdooKkcUf76RqDotOFwNJCK3YSQOWiO/fvdGPWi2lNVwvxEa', NULL, '2026-08-29 06:07:18', '2026-08-29 06:07:18'),
(3, 'khaled12', 'khaled12@gmail.com', 'admin', 0, NULL, '$2y$12$WRmHYQiwOQCVvhhbW1cOteEt.INdq03LLkj96.CeSD04aGjhNfPV.', NULL, '2026-09-10 11:04:15', '2026-09-10 11:04:15'),
(4, 'ali', 'ali12@gmail.com', 'customer', 0, NULL, '$2y$12$.6GWnDVi5uZisEnYPKIqCeyj.pB0RIFIGwJhfszPNxVCAYIQB.496', NULL, '2026-09-13 08:30:58', '2026-09-13 08:30:58'),
(5, 'Ahmad Hassan', 'ahmad@example.com', 'customer', 0, NULL, '$2y$12$HsbH87kdLI.KcDHGnFemDeDD.jyMKLcgBdBcAk9dGhlK.A4oeSEpC', NULL, '2026-09-15 12:58:26', '2026-09-15 12:58:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `private_documents`
--
ALTER TABLE `private_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `private_documents_user_id_foreign` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `private_documents`
--
ALTER TABLE `private_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `private_documents`
--
ALTER TABLE `private_documents`
  ADD CONSTRAINT `private_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
