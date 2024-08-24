-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 17, 2023 at 07:03 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `multirent_update`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_sections`
--

CREATE TABLE `about_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `button_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_sections`
--

INSERT INTO `about_sections` (`id`, `language_id`, `subtitle`, `title`, `text`, `button_name`, `button_url`, `created_at`, `updated_at`) VALUES
(1, 9, 'من نحن؟', 'لدينا خبرة صناعية تزيد عن 30 عامًا', 'و سأعرض مثال حي لهذا، من منا لم يتحمل جهد بدني شاق إلا من أجل الحصول على ميزة أو فائدة؟ ولكن من لديه الحق أن ينتقد شخص ما أراد أن يشعر بالسعادة التي لا تشوبها عواقب أليمة أو آخر أراد أن يتجنب الألم الذي ربما تنجم عنه بعض المتعة ؟\r\n\r\nعلي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', 'نحن نفعل', 'http://www.google.com', '2021-12-08 05:16:39', '2022-08-01 03:26:10'),
(2, 8, 'Who We Are?', 'We Have 30+ Years Industry Experience', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'We Do', 'http://www.google.com', '2022-03-06 00:25:12', '2022-10-04 05:16:07');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `role_id`, `first_name`, `last_name`, `image`, `username`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Leonard', 'Bourne', '622845a1841fb.png', 'admin', 'leonardbourne@example.com', '$2y$10$7rcuMv8LG9adF09JnRjt.O35YL/3dkFWA7EBhBT.LOZvS07OaeDFm', 1, NULL, '2022-08-02 02:48:17'),
(7, 14, 'John', 'Brown', '1628396045.png', 'john', 'johnbrown@example.com', '$2y$10$u.qEwF8xI2Uz6ZkuwCKIReA/kk9znTXZEzhfbf9pJs88eu.y/JhLS', 1, '2021-08-07 22:14:05', '2022-08-02 02:51:30');

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` bigint UNSIGNED NOT NULL,
  `ad_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resolution_type` smallint UNSIGNED NOT NULL COMMENT '1 => 300 x 250, 2 => 300 x 600, 3 => 728 x 90',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slot` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `ad_type`, `resolution_type`, `image`, `url`, `slot`, `views`, `created_at`, `updated_at`) VALUES
(7, 'banner', 3, '62e231ce31926.png', 'http://example.com/', NULL, 4, '2021-08-15 22:44:47', '2022-10-19 04:28:25'),
(8, 'banner', 2, '62e231b2e7386.png', 'http://example.com/', NULL, 0, '2021-08-15 22:45:21', '2022-07-28 00:50:26'),
(9, 'banner', 1, '62e23191d30d7.png', 'http://example.com/', NULL, 0, '2021-08-15 23:12:31', '2022-07-28 00:49:53'),
(10, 'banner', 1, '62e231831b43f.png', 'http://example.com/', NULL, 2, '2021-08-15 23:13:44', '2022-07-28 00:49:39'),
(11, 'banner', 2, '62e231750df75.png', 'http://example.com/', NULL, 2, '2021-08-15 23:15:14', '2022-07-28 00:49:25'),
(12, 'banner', 1, '62e231610cef0.png', 'http://example.com/', NULL, 1, '2021-08-15 23:16:41', '2022-08-02 03:16:37'),
(13, 'banner', 3, '62e23140498d8.png', 'http://example.com/', NULL, 1, '2021-08-17 04:52:09', '2022-08-22 06:01:38');

-- --------------------------------------------------------

--
-- Table structure for table `basic_settings`
--

CREATE TABLE `basic_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `uniqid` int UNSIGNED NOT NULL DEFAULT '12345',
  `favicon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `theme_version` smallint UNSIGNED NOT NULL,
  `base_currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `base_currency_symbol_position` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `base_currency_text` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `base_currency_text_position` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `base_currency_rate` decimal(8,2) DEFAULT NULL,
  `primary_color` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `secondary_color` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `breadcrumb_overlay_color` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `breadcrumb_overlay_opacity` decimal(4,2) DEFAULT NULL,
  `smtp_status` tinyint DEFAULT NULL,
  `smtp_host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smtp_port` int DEFAULT NULL,
  `encryption` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smtp_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smtp_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `from_mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `to_mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `breadcrumb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disqus_status` tinyint UNSIGNED DEFAULT NULL,
  `disqus_short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `google_recaptcha_status` tinyint DEFAULT NULL,
  `google_recaptcha_site_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `google_recaptcha_secret_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `whatsapp_status` tinyint UNSIGNED DEFAULT NULL,
  `whatsapp_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `whatsapp_header_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `whatsapp_popup_status` tinyint UNSIGNED DEFAULT NULL,
  `whatsapp_popup_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `maintenance_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `maintenance_status` tinyint DEFAULT NULL,
  `maintenance_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `bypass_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `footer_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `footer_background_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_theme_version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'light',
  `notification_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hero_section_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `about_section_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `counter_section_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `call_to_action_section_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `google_adsense_publisher_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `equipment_tax_amount` decimal(5,2) UNSIGNED DEFAULT NULL,
  `product_tax_amount` decimal(5,2) UNSIGNED DEFAULT NULL,
  `self_pickup_status` tinyint UNSIGNED DEFAULT NULL,
  `two_way_delivery_status` tinyint UNSIGNED DEFAULT NULL,
  `guest_checkout_status` tinyint UNSIGNED NOT NULL,
  `facebook_login_status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 -> enable, 0 -> disable',
  `facebook_app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `facebook_app_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `google_login_status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 -> enable, 0 -> disable',
  `google_client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `google_client_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tawkto_status` tinyint UNSIGNED NOT NULL COMMENT '1 -> enable, 0 -> disable',
  `tawkto_direct_chat_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vendor_admin_approval` int NOT NULL DEFAULT '0' COMMENT '1 active, 2 deactive',
  `vendor_email_verification` int NOT NULL DEFAULT '0' COMMENT '1 active, 2 deactive',
  `admin_approval_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basic_settings`
--

INSERT INTO `basic_settings` (`id`, `uniqid`, `favicon`, `logo`, `website_title`, `email_address`, `contact_number`, `address`, `theme_version`, `base_currency_symbol`, `base_currency_symbol_position`, `base_currency_text`, `base_currency_text_position`, `base_currency_rate`, `primary_color`, `secondary_color`, `breadcrumb_overlay_color`, `breadcrumb_overlay_opacity`, `smtp_status`, `smtp_host`, `smtp_port`, `encryption`, `smtp_username`, `smtp_password`, `from_mail`, `from_name`, `to_mail`, `breadcrumb`, `disqus_status`, `disqus_short_name`, `google_recaptcha_status`, `google_recaptcha_site_key`, `google_recaptcha_secret_key`, `whatsapp_status`, `whatsapp_number`, `whatsapp_header_title`, `whatsapp_popup_status`, `whatsapp_popup_message`, `maintenance_img`, `maintenance_status`, `maintenance_msg`, `bypass_token`, `footer_logo`, `footer_background_image`, `admin_theme_version`, `notification_image`, `hero_section_image`, `about_section_image`, `counter_section_image`, `call_to_action_section_image`, `google_adsense_publisher_id`, `equipment_tax_amount`, `product_tax_amount`, `self_pickup_status`, `two_way_delivery_status`, `guest_checkout_status`, `facebook_login_status`, `facebook_app_id`, `facebook_app_secret`, `google_login_status`, `google_client_id`, `google_client_secret`, `tawkto_status`, `tawkto_direct_chat_link`, `vendor_admin_approval`, `vendor_email_verification`, `admin_approval_notice`) VALUES
(2, 12345, '63b179e50da26.png', '63b166ec32c45.png', 'MultiRent', 'multirent@example.com', '+701 - 1111 - 2222 - 3333', '450 Young Road, New York, USA', 2, '$', 'left', 'USD', 'right', '1.00', '0C1239', 'FBA31C', '030103', '0.80', 1, 'smtp.gmail.com', 587, 'TLS', 'geniustest11@gmail.com', 'jvpdiafcjhrznkbm', 'geniustest11@gmail.com', 'MultiRent', NULL, '63b1770c9a255.png', 1, 'coursela', 0, '6LeJdwEbAAAAAMTYn_I2GN0iLSa25ja1GG30YZsZ', '6LeJdwEbAAAAAOG9wjJGyLk6BVh-X513O6GNtqLb', 1, '18046101470', 'Hi, there!', 1, 'If you have any issues, let us know.', '1632725312.png', 0, 'We are upgrading our site. We will come back soon. \r\nPlease stay with us.\r\nThank you.', 'fahad', '63b1670a68e5e.png', '61d28294c1b35.jpg', 'dark', '619b7d5e5e9df.png', '63b17947d2ed5.png', '63b178e72080d.png', '61cc4638ddd1e.jpg', '61cda1a624896.jpg', NULL, '10.00', '5.00', 1, 1, 1, 1, '997931780909470', '1e682e69883190ba4e0a1e2eda634c2e', 1, '820299140458-4dojqiuq0seg72417f31pb34jdku95e2.apps.googleusercontent.com', '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', 0, 'https://tawk.to/chat/6304bda537898912e964a4b5/1gb589l4i', 1, 0, 'Your account is deactivated or pending now please get in touch with admin.');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `image`, `serial_number`, `created_at`, `updated_at`) VALUES
(12, '62e4b94b7674d.png', 1, '2021-10-13 23:13:31', '2022-07-29 22:53:31'),
(16, '62e4bb1eac65b.png', 2, '2021-12-07 00:43:59', '2022-07-29 23:01:18'),
(17, '62e4bcca4a82a.png', 3, '2022-01-02 03:58:42', '2022-07-29 23:08:26'),
(18, '62e4bd6349dea.png', 4, '2022-07-27 03:02:14', '2022-07-29 23:10:59'),
(19, '62e4bee2dfd48.png', 5, '2022-07-29 23:17:22', '2022-07-29 23:17:22'),
(20, '62e4bff22e1e4.png', 6, '2022-07-29 23:21:54', '2022-07-29 23:21:54');

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL,
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `language_id`, `name`, `slug`, `status`, `serial_number`, `created_at`, `updated_at`) VALUES
(36, 8, 'Equipment', 'equipment', 1, 1, '2021-10-12 22:51:29', '2022-08-28 00:02:49'),
(38, 8, 'Construction', 'construction', 1, 2, '2021-10-12 22:51:52', '2022-08-28 00:02:53'),
(39, 9, 'معدات', 'معدات', 1, 1, '2021-10-12 22:58:54', '2022-08-28 00:03:02'),
(41, 9, 'بناء', 'بناء', 1, 2, '2021-10-12 22:59:19', '2022-08-28 00:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `blog_informations`
--

CREATE TABLE `blog_informations` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `blog_category_id` bigint UNSIGNED NOT NULL,
  `blog_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` blob NOT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_informations`
--

INSERT INTO `blog_informations` (`id`, `language_id`, `blog_category_id`, `blog_id`, `title`, `slug`, `author`, `content`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(19, 9, 41, 12, 'سلامة عمال البناء', 'سلامة-عمال-البناء', 'جين دو', 0x3c6469763ed98820d8b3d8a3d8b9d8b1d8b620d985d8abd8a7d98420d8add98a20d984d987d8b0d8a7d88c20d985d98620d985d986d8a720d984d98520d98ad8aad8add985d98420d8acd987d8af20d8a8d8afd986d98a20d8b4d8a7d98220d8a5d984d8a720d985d98620d8a3d8acd98420d8a7d984d8add8b5d988d98420d8b9d984d98920d985d98ad8b2d8a920d8a3d98820d981d8a7d8a6d8afd8a9d89f20d988d984d983d98620d985d98620d984d8afd98ad98720d8a7d984d8add98220d8a3d98620d98ad986d8aad982d8af20d8b4d8aed8b520d985d8a720d8a3d8b1d8a7d8af20d8a3d98620d98ad8b4d8b9d8b120d8a8d8a7d984d8b3d8b9d8a7d8afd8a920d8a7d984d8aad98a20d984d8a720d8aad8b4d988d8a8d987d8a720d8b9d988d8a7d982d8a820d8a3d984d98ad985d8a920d8a3d98820d8a2d8aed8b120d8a3d8b1d8a7d8af20d8a3d98620d98ad8aad8acd986d8a820d8a7d984d8a3d984d98520d8a7d984d8b0d98a20d8b1d8a8d985d8a720d8aad986d8acd98520d8b9d986d98720d8a8d8b9d8b620d8a7d984d985d8aad8b9d8a920d89fc2a03c2f6469763e3c6469763ed8b9d984d98a20d8a7d984d8acd8a7d986d8a820d8a7d984d8a2d8aed8b120d986d8b4d8acd8a820d988d986d8b3d8aad986d983d8b120d987d8a4d984d8a7d8a120d8a7d984d8b1d8acd8a7d98420d8a7d984d985d981d8aad988d986d988d98620d8a8d986d8b4d988d8a920d8a7d984d984d8add8b8d8a920d8a7d984d987d8a7d8a6d985d988d98620d981d98a20d8b1d8bad8a8d8a7d8aad987d98520d981d984d8a720d98ad8afd8b1d983d988d98620d985d8a720d98ad8b9d982d8a8d987d8a720d985d98620d8a7d984d8a3d984d98520d988d8a7d984d8a3d8b3d98a20d8a7d984d985d8add8aad985d88c20d988d8a7d984d984d988d98520d983d8b0d984d98320d98ad8b4d985d98420d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d8a3d8aed981d982d988d8a720d981d98a20d988d8a7d8acd8a8d8a7d8aad987d98520d986d8aad98ad8acd8a920d984d8b6d8b9d98120d8a5d8b1d8a7d8afd8aad987d98520d981d98ad8aad8b3d8a7d988d98a20d985d8b920d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d98ad8aad8acd986d8a8d988d98620d988d98ad986d8a3d988d98620d8b9d98620d8aad8add985d98420d8a7d984d983d8afd8ad20d988d8a7d984d8a3d984d985202e3c2f6469763e, NULL, NULL, '2021-10-13 23:13:31', '2022-07-27 01:47:19'),
(20, 8, 38, 12, 'Construction worker\'s safety', 'construction-worker\'s-safety', 'Jane Doe', 0x3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e4c6f72656d20697073756d20646f6c6f722073697420616d65742c20636f6e73656374657475722061646970697363696e6720656c69742c2073656420646f20656975736d6f642074656d706f7220696e6369646964756e74207574206c61626f726520657420646f6c6f7265206d61676e6120616c697175612e20557420656e696d206164206d696e696d2076656e69616d2c2071756973206e6f737472756420657865726369746174696f6e20756c6c616d636f206c61626f726973206e69736920757420616c697175697020657820656120636f6d6d6f646f20636f6e7365717561742e3c2f7370616e3e3c2f703e3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e44756973206175746520697275726520646f6c6f7220696e20726570726568656e646572697420696e20766f6c7570746174652076656c697420657373652063696c6c756d20646f6c6f726520657520667567696174206e756c6c612070617269617475722e204578636570746575722073696e74206f6363616563617420637570696461746174206e6f6e2070726f6964656e742c2073756e7420696e2063756c706120717569206f666669636961206465736572756e74206d6f6c6c697420616e696d20696420657374206c61626f72756d2e3c2f7370616e3e3c6272202f3e3c2f703e, NULL, NULL, '2021-10-13 23:13:31', '2022-08-02 01:00:07'),
(27, 9, 39, 16, 'أفضل تأجير المعدات لمشروعك الكبير', 'أفضل-تأجير-المعدات-لمشروعك-الكبير', 'فلان الفلاني', 0x3c703ed98820d8b3d8a3d8b9d8b1d8b620d985d8abd8a7d98420d8add98a20d984d987d8b0d8a7d88c20d985d98620d985d986d8a720d984d98520d98ad8aad8add985d98420d8acd987d8af20d8a8d8afd986d98a20d8b4d8a7d98220d8a5d984d8a720d985d98620d8a3d8acd98420d8a7d984d8add8b5d988d98420d8b9d984d98920d985d98ad8b2d8a920d8a3d98820d981d8a7d8a6d8afd8a9d89f20d988d984d983d98620d985d98620d984d8afd98ad98720d8a7d984d8add98220d8a3d98620d98ad986d8aad982d8af20d8b4d8aed8b520d985d8a720d8a3d8b1d8a7d8af20d8a3d98620d98ad8b4d8b9d8b120d8a8d8a7d984d8b3d8b9d8a7d8afd8a920d8a7d984d8aad98a20d984d8a720d8aad8b4d988d8a8d987d8a720d8b9d988d8a7d982d8a820d8a3d984d98ad985d8a920d8a3d98820d8a2d8aed8b120d8a3d8b1d8a7d8af20d8a3d98620d98ad8aad8acd986d8a820d8a7d984d8a3d984d98520d8a7d984d8b0d98a20d8b1d8a8d985d8a720d8aad986d8acd98520d8b9d986d98720d8a8d8b9d8b620d8a7d984d985d8aad8b9d8a920d89fc2a03c2f703e3c703ed8b9d984d98a20d8a7d984d8acd8a7d986d8a820d8a7d984d8a2d8aed8b120d986d8b4d8acd8a820d988d986d8b3d8aad986d983d8b120d987d8a4d984d8a7d8a120d8a7d984d8b1d8acd8a7d98420d8a7d984d985d981d8aad988d986d988d98620d8a8d986d8b4d988d8a920d8a7d984d984d8add8b8d8a920d8a7d984d987d8a7d8a6d985d988d98620d981d98a20d8b1d8bad8a8d8a7d8aad987d98520d981d984d8a720d98ad8afd8b1d983d988d98620d985d8a720d98ad8b9d982d8a8d987d8a720d985d98620d8a7d984d8a3d984d98520d988d8a7d984d8a3d8b3d98a20d8a7d984d985d8add8aad985d88c20d988d8a7d984d984d988d98520d983d8b0d984d98320d98ad8b4d985d98420d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d8a3d8aed981d982d988d8a720d981d98a20d988d8a7d8acd8a8d8a7d8aad987d98520d986d8aad98ad8acd8a920d984d8b6d8b9d98120d8a5d8b1d8a7d8afd8aad987d98520d981d98ad8aad8b3d8a7d988d98a20d985d8b920d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d98ad8aad8acd986d8a8d988d98620d988d98ad986d8a3d988d98620d8b9d98620d8aad8add985d98420d8a7d984d983d8afd8ad20d988d8a7d984d8a3d984d985202e3c2f703e, NULL, NULL, '2021-12-07 00:44:01', '2022-01-02 03:18:44'),
(28, 8, 36, 16, 'Best equipment rental for your big project', 'best-equipment-rental-for-your-big-project', 'John Doe', 0x3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e4c6f72656d20697073756d20646f6c6f722073697420616d65742c20636f6e73656374657475722061646970697363696e6720656c69742c2073656420646f20656975736d6f642074656d706f7220696e6369646964756e74207574206c61626f726520657420646f6c6f7265206d61676e6120616c697175612e20557420656e696d206164206d696e696d2076656e69616d2c2071756973206e6f737472756420657865726369746174696f6e20756c6c616d636f206c61626f726973206e69736920757420616c697175697020657820656120636f6d6d6f646f20636f6e7365717561742e3c2f7370616e3e3c2f703e3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e44756973206175746520697275726520646f6c6f7220696e20726570726568656e646572697420696e20766f6c7570746174652076656c697420657373652063696c6c756d20646f6c6f726520657520667567696174206e756c6c612070617269617475722e204578636570746575722073696e74206f6363616563617420637570696461746174206e6f6e2070726f6964656e742c2073756e7420696e2063756c706120717569206f666669636961206465736572756e74206d6f6c6c697420616e696d20696420657374206c61626f72756d2e3c2f7370616e3e3c2f703e, NULL, NULL, '2021-12-07 00:44:02', '2022-08-02 01:00:57'),
(29, 9, 41, 17, 'مكونات المولدات التي يجب أن تعرفها', 'مكونات-المولدات-التي-يجب-أن-تعرفها', 'ما فائدته', 0x3c6469763ed98820d8b3d8a3d8b9d8b1d8b620d985d8abd8a7d98420d8add98a20d984d987d8b0d8a7d88c20d985d98620d985d986d8a720d984d98520d98ad8aad8add985d98420d8acd987d8af20d8a8d8afd986d98a20d8b4d8a7d98220d8a5d984d8a720d985d98620d8a3d8acd98420d8a7d984d8add8b5d988d98420d8b9d984d98920d985d98ad8b2d8a920d8a3d98820d981d8a7d8a6d8afd8a9d89f20d988d984d983d98620d985d98620d984d8afd98ad98720d8a7d984d8add98220d8a3d98620d98ad986d8aad982d8af20d8b4d8aed8b520d985d8a720d8a3d8b1d8a7d8af20d8a3d98620d98ad8b4d8b9d8b120d8a8d8a7d984d8b3d8b9d8a7d8afd8a920d8a7d984d8aad98a20d984d8a720d8aad8b4d988d8a8d987d8a720d8b9d988d8a7d982d8a820d8a3d984d98ad985d8a920d8a3d98820d8a2d8aed8b120d8a3d8b1d8a7d8af20d8a3d98620d98ad8aad8acd986d8a820d8a7d984d8a3d984d98520d8a7d984d8b0d98a20d8b1d8a8d985d8a720d8aad986d8acd98520d8b9d986d98720d8a8d8b9d8b620d8a7d984d985d8aad8b9d8a920d89fc2a03c2f6469763e3c6469763ed8b9d984d98a20d8a7d984d8acd8a7d986d8a820d8a7d984d8a2d8aed8b120d986d8b4d8acd8a820d988d986d8b3d8aad986d983d8b120d987d8a4d984d8a7d8a120d8a7d984d8b1d8acd8a7d98420d8a7d984d985d981d8aad988d986d988d98620d8a8d986d8b4d988d8a920d8a7d984d984d8add8b8d8a920d8a7d984d987d8a7d8a6d985d988d98620d981d98a20d8b1d8bad8a8d8a7d8aad987d98520d981d984d8a720d98ad8afd8b1d983d988d98620d985d8a720d98ad8b9d982d8a8d987d8a720d985d98620d8a7d984d8a3d984d98520d988d8a7d984d8a3d8b3d98a20d8a7d984d985d8add8aad985d88c20d988d8a7d984d984d988d98520d983d8b0d984d98320d98ad8b4d985d98420d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d8a3d8aed981d982d988d8a720d981d98a20d988d8a7d8acd8a8d8a7d8aad987d98520d986d8aad98ad8acd8a920d984d8b6d8b9d98120d8a5d8b1d8a7d8afd8aad987d98520d981d98ad8aad8b3d8a7d988d98a20d985d8b920d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d98ad8aad8acd986d8a8d988d98620d988d98ad986d8a3d988d98620d8b9d98620d8aad8add985d98420d8a7d984d983d8afd8ad20d988d8a7d984d8a3d984d985202e3c2f6469763e, NULL, NULL, '2022-01-02 03:58:42', '2022-08-02 01:01:26'),
(30, 8, 38, 17, 'Generator Components Which You Should Know', 'generator-components-which-you-should-know', 'Drake Duncan', 0x3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e4c6f72656d20697073756d20646f6c6f722073697420616d65742c20636f6e73656374657475722061646970697363696e6720656c69742c2073656420646f20656975736d6f642074656d706f7220696e6369646964756e74207574206c61626f726520657420646f6c6f7265206d61676e6120616c697175612e20557420656e696d206164206d696e696d2076656e69616d2c2071756973206e6f737472756420657865726369746174696f6e20756c6c616d636f206c61626f726973206e69736920757420616c697175697020657820656120636f6d6d6f646f20636f6e7365717561742e3c2f7370616e3e3c2f703e3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e44756973206175746520697275726520646f6c6f7220696e20726570726568656e646572697420696e20766f6c7570746174652076656c697420657373652063696c6c756d20646f6c6f726520657520667567696174206e756c6c612070617269617475722e204578636570746575722073696e74206f6363616563617420637570696461746174206e6f6e2070726f6964656e742c2073756e7420696e2063756c706120717569206f666669636961206465736572756e74206d6f6c6c697420616e696d20696420657374206c61626f72756d2e3c2f7370616e3e3c2f703e, NULL, NULL, '2022-01-02 03:58:42', '2022-08-02 01:01:26'),
(31, 9, 41, 18, 'أفضل أعمال البناء مع أفضل مهندس', 'أفضل-أعمال-البناء-مع-أفضل-مهندس', 'كاثرين فوج', 0x3c703ed98820d8b3d8a3d8b9d8b1d8b620d985d8abd8a7d98420d8add98a20d984d987d8b0d8a7d88c20d985d98620d985d986d8a720d984d98520d98ad8aad8add985d98420d8acd987d8af20d8a8d8afd986d98a20d8b4d8a7d98220d8a5d984d8a720d985d98620d8a3d8acd98420d8a7d984d8add8b5d988d98420d8b9d984d98920d985d98ad8b2d8a920d8a3d98820d981d8a7d8a6d8afd8a9d89f20d988d984d983d98620d985d98620d984d8afd98ad98720d8a7d984d8add98220d8a3d98620d98ad986d8aad982d8af20d8b4d8aed8b520d985d8a720d8a3d8b1d8a7d8af20d8a3d98620d98ad8b4d8b9d8b120d8a8d8a7d984d8b3d8b9d8a7d8afd8a920d8a7d984d8aad98a20d984d8a720d8aad8b4d988d8a8d987d8a720d8b9d988d8a7d982d8a820d8a3d984d98ad985d8a920d8a3d98820d8a2d8aed8b120d8a3d8b1d8a7d8af20d8a3d98620d98ad8aad8acd986d8a820d8a7d984d8a3d984d98520d8a7d984d8b0d98a20d8b1d8a8d985d8a720d8aad986d8acd98520d8b9d986d98720d8a8d8b9d8b620d8a7d984d985d8aad8b9d8a920d89fc2a03c2f703e3c703ed8b9d984d98a20d8a7d984d8acd8a7d986d8a820d8a7d984d8a2d8aed8b120d986d8b4d8acd8a820d988d986d8b3d8aad986d983d8b120d987d8a4d984d8a7d8a120d8a7d984d8b1d8acd8a7d98420d8a7d984d985d981d8aad988d986d988d98620d8a8d986d8b4d988d8a920d8a7d984d984d8add8b8d8a920d8a7d984d987d8a7d8a6d985d988d98620d981d98a20d8b1d8bad8a8d8a7d8aad987d98520d981d984d8a720d98ad8afd8b1d983d988d98620d985d8a720d98ad8b9d982d8a8d987d8a720d985d98620d8a7d984d8a3d984d98520d988d8a7d984d8a3d8b3d98a20d8a7d984d985d8add8aad985d88c20d988d8a7d984d984d988d98520d983d8b0d984d98320d98ad8b4d985d98420d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d8a3d8aed981d982d988d8a720d981d98a20d988d8a7d8acd8a8d8a7d8aad987d98520d986d8aad98ad8acd8a920d984d8b6d8b9d98120d8a5d8b1d8a7d8afd8aad987d98520d981d98ad8aad8b3d8a7d988d98a20d985d8b920d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d98ad8aad8acd986d8a8d988d98620d988d98ad986d8a3d988d98620d8b9d98620d8aad8add985d98420d8a7d984d983d8afd8ad20d988d8a7d984d8a3d984d985202e3c2f703e, NULL, NULL, '2022-07-27 03:02:14', '2022-07-27 04:40:08'),
(32, 8, 38, 18, 'Best construction work with best engineer', 'best-construction-work-with-best-engineer', 'Katherine Fogg', 0x3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e4c6f72656d20697073756d20646f6c6f722073697420616d65742c20636f6e73656374657475722061646970697363696e6720656c69742c2073656420646f20656975736d6f642074656d706f7220696e6369646964756e74207574206c61626f726520657420646f6c6f7265206d61676e6120616c697175612e20557420656e696d206164206d696e696d2076656e69616d2c2071756973206e6f737472756420657865726369746174696f6e20756c6c616d636f206c61626f726973206e69736920757420616c697175697020657820656120636f6d6d6f646f20636f6e7365717561742e3c2f7370616e3e3c2f703e3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e44756973206175746520697275726520646f6c6f7220696e20726570726568656e646572697420696e20766f6c7570746174652076656c697420657373652063696c6c756d20646f6c6f726520657520667567696174206e756c6c612070617269617475722e204578636570746575722073696e74206f6363616563617420637570696461746174206e6f6e2070726f6964656e742c2073756e7420696e2063756c706120717569206f666669636961206465736572756e74206d6f6c6c697420616e696d20696420657374206c61626f72756d2e3c2f7370616e3e3c2f703e, NULL, NULL, '2022-07-27 03:02:14', '2022-08-02 01:02:01'),
(33, 9, 39, 19, 'استئجار معدات البناء لمشروعك الضخم', 'استئجار-معدات-البناء-لمشروعك-الضخم', 'فلان الفلاني', 0x3c703ed98820d8b3d8a3d8b9d8b1d8b620d985d8abd8a7d98420d8add98a20d984d987d8b0d8a7d88c20d985d98620d985d986d8a720d984d98520d98ad8aad8add985d98420d8acd987d8af20d8a8d8afd986d98a20d8b4d8a7d98220d8a5d984d8a720d985d98620d8a3d8acd98420d8a7d984d8add8b5d988d98420d8b9d984d98920d985d98ad8b2d8a920d8a3d98820d981d8a7d8a6d8afd8a9d89f20d988d984d983d98620d985d98620d984d8afd98ad98720d8a7d984d8add98220d8a3d98620d98ad986d8aad982d8af20d8b4d8aed8b520d985d8a720d8a3d8b1d8a7d8af20d8a3d98620d98ad8b4d8b9d8b120d8a8d8a7d984d8b3d8b9d8a7d8afd8a920d8a7d984d8aad98a20d984d8a720d8aad8b4d988d8a8d987d8a720d8b9d988d8a7d982d8a820d8a3d984d98ad985d8a920d8a3d98820d8a2d8aed8b120d8a3d8b1d8a7d8af20d8a3d98620d98ad8aad8acd986d8a820d8a7d984d8a3d984d98520d8a7d984d8b0d98a20d8b1d8a8d985d8a720d8aad986d8acd98520d8b9d986d98720d8a8d8b9d8b620d8a7d984d985d8aad8b9d8a920d89fc2a03c2f703e3c703ed8b9d984d98a20d8a7d984d8acd8a7d986d8a820d8a7d984d8a2d8aed8b120d986d8b4d8acd8a820d988d986d8b3d8aad986d983d8b120d987d8a4d984d8a7d8a120d8a7d984d8b1d8acd8a7d98420d8a7d984d985d981d8aad988d986d988d98620d8a8d986d8b4d988d8a920d8a7d984d984d8add8b8d8a920d8a7d984d987d8a7d8a6d985d988d98620d981d98a20d8b1d8bad8a8d8a7d8aad987d98520d981d984d8a720d98ad8afd8b1d983d988d98620d985d8a720d98ad8b9d982d8a8d987d8a720d985d98620d8a7d984d8a3d984d98520d988d8a7d984d8a3d8b3d98a20d8a7d984d985d8add8aad985d88c20d988d8a7d984d984d988d98520d983d8b0d984d98320d98ad8b4d985d98420d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d8a3d8aed981d982d988d8a720d981d98a20d988d8a7d8acd8a8d8a7d8aad987d98520d986d8aad98ad8acd8a920d984d8b6d8b9d98120d8a5d8b1d8a7d8afd8aad987d98520d981d98ad8aad8b3d8a7d988d98a20d985d8b920d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d98ad8aad8acd986d8a8d988d98620d988d98ad986d8a3d988d98620d8b9d98620d8aad8add985d98420d8a7d984d983d8afd8ad20d988d8a7d984d8a3d984d985202e3c2f703e, NULL, NULL, '2022-07-29 23:17:22', '2022-07-29 23:17:22'),
(34, 8, 36, 19, 'Rent construction equipment for your mega project', 'rent-construction-equipment-for-your-mega-project', 'John Doe', 0x3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e4c6f72656d20697073756d20646f6c6f722073697420616d65742c20636f6e73656374657475722061646970697363696e6720656c69742c2073656420646f20656975736d6f642074656d706f7220696e6369646964756e74207574206c61626f726520657420646f6c6f7265206d61676e6120616c697175612e20557420656e696d206164206d696e696d2076656e69616d2c2071756973206e6f737472756420657865726369746174696f6e20756c6c616d636f206c61626f726973206e69736920757420616c697175697020657820656120636f6d6d6f646f20636f6e7365717561742e3c2f7370616e3e3c2f703e3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e44756973206175746520697275726520646f6c6f7220696e20726570726568656e646572697420696e20766f6c7570746174652076656c697420657373652063696c6c756d20646f6c6f726520657520667567696174206e756c6c612070617269617475722e204578636570746575722073696e74206f6363616563617420637570696461746174206e6f6e2070726f6964656e742c2073756e7420696e2063756c706120717569206f666669636961206465736572756e74206d6f6c6c697420616e696d20696420657374206c61626f72756d2e3c2f7370616e3e3c2f703e, NULL, NULL, '2022-07-29 23:17:22', '2022-08-02 01:02:28'),
(35, 9, 41, 20, 'أفضل مخطط معماري من قبل أفضل مهندس', 'أفضل-مخطط-معماري-من-قبل-أفضل-مهندس', 'كاثرين فوج', 0x3c703ed98820d8b3d8a3d8b9d8b1d8b620d985d8abd8a7d98420d8add98a20d984d987d8b0d8a7d88c20d985d98620d985d986d8a720d984d98520d98ad8aad8add985d98420d8acd987d8af20d8a8d8afd986d98a20d8b4d8a7d98220d8a5d984d8a720d985d98620d8a3d8acd98420d8a7d984d8add8b5d988d98420d8b9d984d98920d985d98ad8b2d8a920d8a3d98820d981d8a7d8a6d8afd8a9d89f20d988d984d983d98620d985d98620d984d8afd98ad98720d8a7d984d8add98220d8a3d98620d98ad986d8aad982d8af20d8b4d8aed8b520d985d8a720d8a3d8b1d8a7d8af20d8a3d98620d98ad8b4d8b9d8b120d8a8d8a7d984d8b3d8b9d8a7d8afd8a920d8a7d984d8aad98a20d984d8a720d8aad8b4d988d8a8d987d8a720d8b9d988d8a7d982d8a820d8a3d984d98ad985d8a920d8a3d98820d8a2d8aed8b120d8a3d8b1d8a7d8af20d8a3d98620d98ad8aad8acd986d8a820d8a7d984d8a3d984d98520d8a7d984d8b0d98a20d8b1d8a8d985d8a720d8aad986d8acd98520d8b9d986d98720d8a8d8b9d8b620d8a7d984d985d8aad8b9d8a920d89fc2a03c2f703e3c703ed8b9d984d98a20d8a7d984d8acd8a7d986d8a820d8a7d984d8a2d8aed8b120d986d8b4d8acd8a820d988d986d8b3d8aad986d983d8b120d987d8a4d984d8a7d8a120d8a7d984d8b1d8acd8a7d98420d8a7d984d985d981d8aad988d986d988d98620d8a8d986d8b4d988d8a920d8a7d984d984d8add8b8d8a920d8a7d984d987d8a7d8a6d985d988d98620d981d98a20d8b1d8bad8a8d8a7d8aad987d98520d981d984d8a720d98ad8afd8b1d983d988d98620d985d8a720d98ad8b9d982d8a8d987d8a720d985d98620d8a7d984d8a3d984d98520d988d8a7d984d8a3d8b3d98a20d8a7d984d985d8add8aad985d88c20d988d8a7d984d984d988d98520d983d8b0d984d98320d98ad8b4d985d98420d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d8a3d8aed981d982d988d8a720d981d98a20d988d8a7d8acd8a8d8a7d8aad987d98520d986d8aad98ad8acd8a920d984d8b6d8b9d98120d8a5d8b1d8a7d8afd8aad987d98520d981d98ad8aad8b3d8a7d988d98a20d985d8b920d987d8a4d984d8a7d8a120d8a7d984d8b0d98ad98620d98ad8aad8acd986d8a8d988d98620d988d98ad986d8a3d988d98620d8b9d98620d8aad8add985d98420d8a7d984d983d8afd8ad20d988d8a7d984d8a3d984d985202e3c2f703e, NULL, NULL, '2022-07-29 23:21:54', '2022-07-29 23:21:54'),
(36, 8, 38, 20, 'Best architectural plan by the best engineer', 'best-architectural-plan-by-the-best-engineer', 'Katherine Fogg', 0x3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e4c6f72656d20697073756d20646f6c6f722073697420616d65742c20636f6e73656374657475722061646970697363696e6720656c69742c2073656420646f20656975736d6f642074656d706f7220696e6369646964756e74207574206c61626f726520657420646f6c6f7265206d61676e6120616c697175612e20557420656e696d206164206d696e696d2076656e69616d2c2071756973206e6f737472756420657865726369746174696f6e20756c6c616d636f206c61626f726973206e69736920757420616c697175697020657820656120636f6d6d6f646f20636f6e7365717561742e3c2f7370616e3e3c2f703e3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223e44756973206175746520697275726520646f6c6f7220696e20726570726568656e646572697420696e20766f6c7570746174652076656c697420657373652063696c6c756d20646f6c6f726520657520667567696174206e756c6c612070617269617475722e204578636570746575722073696e74206f6363616563617420637570696461746174206e6f6e2070726f6964656e742c2073756e7420696e2063756c706120717569206f666669636961206465736572756e74206d6f6c6c697420616e696d20696420657374206c61626f72756d2e3c2f7370616e3e3c2f703e, NULL, NULL, '2022-07-29 23:21:54', '2022-08-02 01:03:02');

-- --------------------------------------------------------

--
-- Table structure for table `blog_sections`
--

CREATE TABLE `blog_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_sections`
--

INSERT INTO `blog_sections` (`id`, `language_id`, `subtitle`, `title`, `created_at`, `updated_at`) VALUES
(3, 8, 'Blog', 'Our Latest Post', '2022-01-02 01:14:53', '2022-07-27 01:41:03'),
(4, 9, 'مقالات', 'أحدث مدونة', '2022-03-06 00:41:19', '2022-03-06 00:41:19');

-- --------------------------------------------------------

--
-- Table structure for table `call_to_action_sections`
--

CREATE TABLE `call_to_action_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `call_to_action_sections`
--

INSERT INTO `call_to_action_sections` (`id`, `language_id`, `subtitle`, `title`, `button_name`, `button_url`, `created_at`, `updated_at`) VALUES
(2, 8, 'We Are Serving Since 20 Years With Trust', 'Aenean ligula porttitor euonsequat vitae eleifend aenliquam lorem', 'Get A Quote', 'http://example.com/', '2021-12-31 22:29:16', '2022-07-27 01:01:38'),
(3, 9, 'نحن نخدم منذ 20 عامًا بثقة', 'نتيجة لظروف ما قد تكمن السعاده فيما نتحمله م', 'إقتبس', 'http://example.com/', '2022-03-06 00:38:38', '2022-08-01 05:48:16');

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint UNSIGNED NOT NULL,
  `equipment_commission` float(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `equipment_commission`, `created_at`, `updated_at`) VALUES
(1, 10.00, '2022-10-19 06:28:28', '2022-12-18 23:06:53');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` int DEFAULT NULL,
  `support_ticket_id` int DEFAULT NULL,
  `reply` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `vendor_id`, `support_ticket_id`, `reply`, `file`, `created_at`, `updated_at`) VALUES
(13, NULL, 10, '<p>rtjhth</p>', NULL, '2023-01-30 01:54:03', '2023-01-30 01:54:03'),
(14, NULL, 10, '<p>sdfbgfsdb</p>', NULL, '2023-01-30 01:54:06', '2023-01-30 01:54:06'),
(15, NULL, 10, '<p>gsfd</p>', NULL, '2023-01-30 01:54:08', '2023-01-30 01:54:08'),
(16, NULL, 10, '<p>sgdf</p>', NULL, '2023-01-30 01:54:11', '2023-01-30 01:54:11'),
(17, NULL, 10, '<p>dsfg</p>', NULL, '2023-01-30 01:54:13', '2023-01-30 01:54:13'),
(18, NULL, 10, '<p>dfsgdsgfs</p>', NULL, '2023-01-30 01:54:16', '2023-01-30 01:54:16'),
(19, NULL, 10, '<p>dsfgsg</p>', NULL, '2023-01-30 01:54:19', '2023-01-30 01:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `cookie_alerts`
--

CREATE TABLE `cookie_alerts` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `cookie_alert_status` tinyint UNSIGNED NOT NULL,
  `cookie_alert_btn_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_alert_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cookie_alerts`
--

INSERT INTO `cookie_alerts` (`id`, `language_id`, `cookie_alert_status`, `cookie_alert_btn_text`, `cookie_alert_text`, `created_at`, `updated_at`) VALUES
(1, 9, 1, 'أنا موافق', 'نحن نستخدم ملفات تعريف الارتباط لنمنحك أفضل تجربة عبر الإنترنت.\r\nمن خلال الاستمرار في تصفح الموقع ، فإنك توافق على استخدامنا لملفات تعريف الارتباط.', '2021-06-02 06:25:54', '2022-08-01 01:25:45'),
(2, 8, 1, 'I Agree', 'We use cookies to give you the best online experience.\r\nBy continuing to browse the site you are agreeing to our use of cookies.', '2021-08-29 04:20:43', '2022-10-04 05:29:07');

-- --------------------------------------------------------

--
-- Table structure for table `counter_informations`
--

CREATE TABLE `counter_informations` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `counter_informations`
--

INSERT INTO `counter_informations` (`id`, `language_id`, `icon`, `amount`, `title`, `created_at`, `updated_at`) VALUES
(11, 8, 'fas fa-truck-monster', 200, 'All Equipment', '2021-12-30 00:00:54', '2022-03-06 01:31:26'),
(12, 8, 'fas fa-map-marker-alt', 175, 'Coverage Area', '2021-12-30 00:01:22', '2021-12-30 00:01:22'),
(13, 8, 'fas fa-city', 596, 'Companies', '2021-12-30 00:02:07', '2021-12-30 00:05:09'),
(14, 8, 'fas fa-user-cog', 158, 'Workers', '2021-12-30 00:04:47', '2021-12-30 00:04:47'),
(18, 9, 'fas fa-truck-monster', 200, 'جميع المعدات', '2022-03-06 01:31:46', '2022-03-06 01:31:46'),
(19, 9, 'fas fa-map-marker-alt', 175, 'منطقة التغطية', '2022-03-06 01:32:19', '2022-03-06 01:32:19'),
(20, 9, 'fas fa-city', 596, 'شركات', '2022-03-06 01:32:55', '2022-03-06 01:32:55'),
(21, 9, 'fas fa-user-cog', 158, 'عمال', '2022-03-06 01:33:26', '2022-03-06 01:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `earnings`
--

CREATE TABLE `earnings` (
  `id` bigint UNSIGNED NOT NULL,
  `total_revenue` double(8,2) NOT NULL DEFAULT '0.00',
  `total_earning` double(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `earnings`
--

INSERT INTO `earnings` (`id`, `total_revenue`, `total_earning`, `created_at`, `updated_at`) VALUES
(1, 1627.00, 357.60, '2023-06-13 04:48:06', '2023-06-15 00:02:01');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `thumbnail_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slider_images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `min_booking_days` int UNSIGNED NOT NULL,
  `max_booking_days` int UNSIGNED NOT NULL,
  `offer` int UNSIGNED DEFAULT NULL,
  `per_day_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `per_week_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `per_month_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `lowest_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `is_featured` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `security_deposit_amount` double(8,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `vendor_id`, `thumbnail_image`, `slider_images`, `quantity`, `min_booking_days`, `max_booking_days`, `offer`, `per_day_price`, `per_week_price`, `per_month_price`, `lowest_price`, `is_featured`, `created_at`, `updated_at`, `security_deposit_amount`) VALUES
(24, 23, '63b15e92a70d4.png', '[\"63b15b7f6fefc.png\",\"63b15b7f74ad7.png\"]', 3, 1, 10, 3, '100.00', '300.00', '900.00', '100.00', 'yes', '2023-01-01 04:21:06', '2023-01-01 06:36:35', 0.00),
(25, 26, '63b162529f426.png', '[\"63b1609837376.png\",\"63b1609843041.png\",\"63b1609880c5c.png\"]', 3, 1, 30, 5, '200.00', '900.00', '2000.00', '200.00', 'no', '2023-01-01 04:37:06', '2023-01-01 23:01:27', 0.00),
(26, 23, '63b164d48f1f0.png', '[\"63b1635809075.png\",\"63b1635811954.png\",\"63b1635840846.png\"]', 4, 1, 10, 3, '500.00', '1500.00', '3000.00', '500.00', 'yes', '2023-01-01 04:47:48', '2023-01-01 06:36:29', 0.00),
(27, 25, '63b169a8c7d86.png', '[\"63b16889bed7e.png\",\"63b16889c0189.png\",\"63b1688a15c76.png\"]', 3, 1, 15, 10, '70.00', '210.00', '800.00', '70.00', 'no', '2023-01-01 05:08:24', '2023-01-01 23:01:04', 0.00),
(28, 25, '63b16b937024c.png', '[\"63b16a79673d8.png\",\"63b16a796b7f5.png\",\"63b16a79a0640.png\"]', 3, 2, 20, 2, '50.00', '250.00', '1000.00', '50.00', 'yes', '2023-01-01 05:16:35', '2023-01-01 23:00:39', 0.00),
(29, 23, '63b16df7ea12e.png', '[\"63b16ca1cd05f.png\",\"63b16ca1cf7a6.png\",\"63b16ca2262c2.png\"]', 3, 1, 10, 0, '30.00', '150.00', '500.00', '30.00', 'no', '2023-01-01 05:26:47', '2023-01-01 05:26:47', 0.00),
(30, 27, '63b17013bef62.png', '[\"63b16f2cc579e.png\",\"63b16f2cc98c1.png\",\"63b16f2cf3974.png\"]', 4, 1, 10, 10, '70.00', '210.00', '600.00', '70.00', 'yes', '2023-01-01 05:35:47', '2023-01-01 23:00:04', 0.00),
(31, 23, '63b1726db0361.png', '[\"63b171eaa72f4.png\",\"63b171eaa7302.png\",\"63b171ead899e.png\"]', 5, 1, 10, NULL, '70.00', '250.00', '699.00', '70.00', 'no', '2023-01-01 05:45:49', '2023-01-01 05:45:49', 0.00),
(32, 23, '63b1754c1a230.png', '[\"63b1733dc68e3.png\",\"63b1733de2a3d.png\",\"63b1733e05cb6.png\"]', 6, 1, 30, 15, '49.00', '259.00', '1099.00', '49.00', 'yes', '2023-01-01 05:58:04', '2023-06-13 00:03:58', NULL),
(33, 25, '63b261ab16b2e.png', '[\"63b2606bc805f.png\",\"63b2606bc8038.png\",\"63b2606c0b6de.png\"]', 5, 2, 10, 1, '100.00', '500.00', '1500.00', '100.00', 'no', '2023-01-01 22:46:35', '2023-01-01 22:46:35', 0.00),
(34, 26, '63b26477a7b67.png', '[\"63b262c833fcc.png\",\"63b262c8342d7.png\",\"63b262c875b92.png\"]', 2, 2, 10, 7, '90.00', '450.00', '900.00', '90.00', 'no', '2023-01-01 22:58:31', '2023-03-07 22:16:09', 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `equipment_bookings`
--

CREATE TABLE `equipment_bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `booking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `equipment_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `shipping_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(8,2) UNSIGNED DEFAULT NULL,
  `discount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `shipping_cost` decimal(8,2) UNSIGNED DEFAULT NULL,
  `tax` decimal(8,2) UNSIGNED DEFAULT NULL,
  `grand_total` decimal(8,2) UNSIGNED DEFAULT NULL,
  `comission` float(8,2) DEFAULT '0.00',
  `received_amount` float(8,2) DEFAULT '0.00',
  `currency_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text_position` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_status` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `security_deposit_amount` double(8,2) DEFAULT '0.00',
  `return_status` tinyint DEFAULT '0',
  `commission_percentage` double(8,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_categories`
--

CREATE TABLE `equipment_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL,
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_categories`
--

INSERT INTO `equipment_categories` (`id`, `language_id`, `name`, `slug`, `status`, `serial_number`, `created_at`, `updated_at`) VALUES
(44, 8, 'Excavator', 'excavator', 1, 1, '2022-01-25 22:03:22', '2022-08-27 23:07:15'),
(45, 8, 'Roller', 'roller', 1, 2, '2022-01-25 22:04:56', '2022-08-27 23:07:24'),
(47, 9, 'حفارة', 'حفارة', 1, 1, '2022-01-25 22:06:34', '2022-08-27 23:08:02'),
(48, 9, 'أسطوانة', 'أسطوانة', 1, 2, '2022-01-25 22:07:17', '2022-08-27 23:08:13'),
(52, 8, 'Mining Truck', 'mining-truck', 1, 3, '2022-01-29 23:57:35', '2022-08-27 23:07:32'),
(53, 9, 'شاحنة التعدين', 'شاحنة-التعدين', 1, 3, '2022-01-29 23:58:32', '2022-08-27 23:08:22'),
(54, 8, 'Wheel Loader', 'wheel-loader', 1, 4, '2022-01-31 05:27:24', '2022-08-27 23:07:36'),
(55, 9, 'رافعة شوكية', 'رافعة-شوكية', 1, 4, '2022-01-31 05:32:28', '2022-08-27 23:08:25'),
(56, 8, 'Mixer Truck', 'mixer-truck', 1, 5, '2022-07-31 23:38:02', '2022-08-27 23:07:46'),
(57, 8, 'Pile Driver', 'pile-driver', 1, 6, '2022-07-31 23:38:22', '2022-08-27 23:07:50'),
(58, 9, 'شاحنة خلاط', 'شاحنة-خلاط', 1, 5, '2022-07-31 23:39:05', '2022-08-27 23:08:27'),
(59, 9, 'سائق كوم', 'سائق-كوم', 1, 6, '2022-07-31 23:39:27', '2022-08-27 23:08:30');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_contents`
--

CREATE TABLE `equipment_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `equipment_category_id` bigint UNSIGNED NOT NULL,
  `equipment_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` blob NOT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_contents`
--

INSERT INTO `equipment_contents` (`id`, `language_id`, `equipment_category_id`, `equipment_id`, `title`, `slug`, `features`, `description`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(47, 8, 57, 24, 'HX26D PILE DRIVER FOR HIGHWAY GUARDRAIL CONSTRUCTION', 'hx26d-pile-driver-for-highway-guardrail-construction', 'Maximum pile height - 15\' (4.6 m) mast: 4.7 m\r\nMotor Power 60 kW\r\nEccentric Moment 0-36 kg*m\r\nFrequency 1100 r/min\r\nCentrifugal Force 0-480 kN', 0x3c703e45503830207265736f6e6174696f6e2d6672656520566962726f2068616d6d65722069732061206e657720736572696573206f662070726f6475637473206a6f696e746c7920646576656c6f706564206279206f757220636f6d70616e792c20746865204265696a696e6720636f6e737472756374696f6e206d656368616e697a6174696f6e20726573656172636820696e737469747574652c20616e64204a6170616e204a69616e2044616f20636f2e2c204c54442e2049742068617320776f6e20746865206669727374207072697a6520696e20746865206d696e6973747279206f6620636f6e737472756374696f6e20736369656e636520616e6420746563686e6f6c6f67792070726f677265737320616e64206973206c697374656420617320746865206d696e6973747279206f6620636f6e737472756374696f6e20736369656e636520616e6420746563686e6f6c6f677920616368696576656d656e74732070726f6d6f74696f6e20616e64207472616e73666f726d6174696f6e2067756964652070726f6a6563743b204578706f7274656420746f204a6170616e206973206b6e6f776e20617320746865202245706f63682070696c652068616d6d65722c2220746865204a6170616e65736520636f6e737472756374696f6e206d696e697374727920286e6f7720746865206d696e6973747279206f66206c616e6420616e64207472616e73706f72746174696f6e29206f6e2074686520736572696573206f66206d6f64656c7320666f7220746865207465737420616e64206576616c756174696f6e2c20616e64206973206c697374656420617320746865206e657720746563686e6f6c6f677920746f2070726f6d6f74652074686520757365206f662070726f64756374732e205468652062696767657374206368617261637465726973746963206f6620746865206d656368616e69736d206f66207468652045502073657269657320656c656374726963206472697665207669627261746f72792068616d6d65722069732074686520757365206f66206879647261756c696320636f6e74726f6c20656363656e7472696369747920636f6e76657273696f6e206465766963652c2077686963682063616e207265616c697a6520227a65726f222073746172742c20616e6420227a65726f222073746f7020616e642061646a7573742074686520656363656e74726963697479206d6f6d656e742066726f6d207a65726f20746f207468652064657369676e206d6178696d756d20647572696e6720746865206f7065726174696f6e2e20436f6d706172656420776974682074686520747261646974696f6e616c20766962726174696e672068616d6d65722c2069742073686f777320677265617420616476616e74616765732c20696e636c7564696e6720283129204974207265616c697a6564207468652073746172742d757020756e6465722074686520636f6e646974696f6e206f66206e6f20656363656e7472696369747920746f727175652c20616e6420736f6c766573207468652070726f626c656d206f66206c6172676520636170616369747920706f77657220737570706c79206e656564656420666f72207468652073746172742d7570206f6620656363656e7472696369747920746f72717565206f6620766962726174696f6e2068616d6d65722062656c7420776869636820697320776964656c7920757365642061742070726573656e743b20283229204974207265616c697a656420227a65726f2220737461727420616e6420227a65726f222073746f702c2077686963682063616e206f766572636f6d6520746865207265736f6e616e63652067656e657261746564207768656e207374617274696e6720616e642073746f7070696e67207769746820656363656e7472696320746f727175652c20616e642070726576656e7420746865206f6363757272656e6365206f66206e6f6973652067656e657261746564206279207265736f6e616e636520616e642064616d61676520746f206f746865722065717569706d656e742070617274733b202833292074686520656363656e7472696369747920746f727175652063616e2062652061646a757374656420636f6e76656e69656e746c7920616e6420667265656c7920647572696e6720746865206f7065726174696f6e206f6620746865206d616368696e6520746f20616461707420746f2074686520726571756972656d656e7473206f6620646966666572656e7420736f696c206c61796572732c20736f20617320746f20616368696576652074686520696465616c20737065656420616e6420656666696369656e6379206f662070696c652073696e6b696e672e3c6272202f3e3c2f703e, 'HX26D', 'EP80 resonation-free Vibro hammer is a new series of products jointly developed by our company, the Beijing construction mechanization research institute, and Japan Jian Dao co., LTD. It has won the first prize in the ministry of construction science and technology progress and is listed as the ministry of construction science and technology achievements promotion and transformation guide project; Exported to Japan is known as the \"Epoch pile hammer,\" the Japanese construction ministry (now the ministry of land and transportation) on the series of models for the test and evaluation, and is listed as the new technology to promote the use of products', '2023-01-01 04:21:07', '2023-01-01 04:21:07'),
(48, 9, 59, 24, 'HX26D سائق كومة لبناء حواجز الطرق السريعة', 'hx26d-سائق-كومة-لبناء-حواجز-الطرق-السريعة', '15 قدمًا (4.6 م): 4.7 م\r\nقوة المحرك 60 كيلو واط\r\nلحظة غير مركزية 0-36 كجم * م\r\nالتردد 1100 ص / دقيقة\r\nقوة الطرد المركزي 0-480 كيلو نيوتن', 0x3c703ed985d8b7d8b1d982d8a920d8a7d984d8a7d987d8aad8b2d8a7d8b220d8a7d984d8aed8a7d984d98ad8a920d985d98620d8a7d984d8b1d986d98ad986204550383020d987d98a20d8b3d984d8b3d984d8a920d8acd8afd98ad8afd8a920d985d98620d8a7d984d985d986d8aad8acd8a7d8aa20d8a7d984d8aad98a20d8aad98520d8aad8b7d988d98ad8b1d987d8a720d8a8d8b4d983d98420d985d8b4d8aad8b1d98320d985d98620d982d8a8d98420d8b4d8b1d983d8aad986d8a720d88c20d988d985d8b9d987d8af20d8a8d983d98ad98620d984d8a3d8a8d8add8a7d8ab20d985d98ad983d8a7d986d98ad983d8a720d8a7d984d8a8d986d8a7d8a120d88c20d988d8b4d8b1d983d8a9204a6170616e204a69616e2044616f20636f2ed88c204c54442e20d988d982d8af20d981d8a7d8b2d8aa20d8a8d8a7d984d8acd8a7d8a6d8b2d8a920d8a7d984d8a3d988d984d98920d981d98a20d988d8b2d8a7d8b1d8a920d8b9d984d988d98520d8a7d984d8a8d986d8a7d8a120d988d8a7d984d8aad982d8afd98520d8a7d984d8aad983d986d988d984d988d8acd98a20d988d8a3d8afd8b1d8acd8aa20d8b6d985d98620d985d8b4d8b1d988d8b920d8afd984d98ad98420d8aad8b9d8b2d98ad8b220d988d8aad8add988d98ad98420d8a5d986d8acd8a7d8b2d8a7d8aa20d8b9d984d988d98520d988d8aad983d986d988d984d988d8acd98ad8a720d8a7d984d8a8d986d8a7d8a120d89b20d98ad98fd8b9d8b1d98120d8a7d984d8aad8b5d8afd98ad8b120d8a5d984d98920d8a7d984d98ad8a7d8a8d8a7d98620d8a8d8a7d8b3d9852022d985d8b7d8b1d982d8a920d983d988d985d8a920d8a7d984d8b9d8b5d8b12220d88c20d988d8b2d8a7d8b1d8a920d8a7d984d8a8d986d8a7d8a120d8a7d984d98ad8a7d8a8d8a7d986d98ad8a92028d8a7d984d8a2d98620d988d8b2d8a7d8b1d8a920d8a7d984d8a3d8b1d8a7d8b6d98a20d988d8a7d984d986d982d9842920d8b9d984d98920d8b3d984d8b3d984d8a920d985d98620d8a7d984d986d985d8a7d8b0d8ac20d984d984d8a7d8aed8aad8a8d8a7d8b120d988d8a7d984d8aad982d98ad98ad98520d88c20d988d987d98a20d985d8afd8b1d8acd8a920d8b9d984d98920d8a3d986d987d8a720d8a7d984d8aad983d986d988d984d988d8acd98ad8a720d8a7d984d8acd8afd98ad8afd8a920d984d8aad8b9d8b2d98ad8b220d8a7d8b3d8aad8aed8afd8a7d98520d8a7d984d985d986d8aad8acd8a7d8aa202e20d8a5d98620d8a3d983d8a8d8b120d985d98ad8b2d8a920d984d8a2d984d98ad8a920d8a7d984d985d8b7d8b1d982d8a920d8a7d984d8a7d987d8aad8b2d8a7d8b2d98ad8a920d8b0d8a7d8aa20d8a7d984d985d8add8b1d98320d8a7d984d983d987d8b1d8a8d8a7d8a6d98a20d985d98620d8b3d984d8b3d984d8a920455020d987d98a20d8a7d8b3d8aad8aed8afd8a7d98520d8acd987d8a7d8b220d8aad8add988d98ad98420d8bad8b1d98ad8a820d8a7d984d8a3d8b7d988d8a7d8b120d984d984d8aad8add983d98520d8a7d984d987d98ad8afd8b1d988d984d98ad983d98a20d88c20d988d8a7d984d8b0d98a20d98ad985d983d98620d8a3d98620d98ad8add982d98220d8a8d8afd8a7d98ad8a92022d8b5d981d8b12220d9882022d8b5d981d8b12220d8aad988d982d98120d988d8b6d8a8d8b720d984d8add8b8d8a920d8a7d984d8a7d986d8add8b1d8a7d98120d985d98620d8a7d984d8b5d981d8b120d8a5d984d98920d8a3d982d8b5d98920d8add8af20d984d984d8aad8b5d985d98ad98520d8aed984d8a7d98420d8b9d985d984d98ad8a92e20d8a8d8a7d984d985d982d8a7d8b1d986d8a920d985d8b920d8a7d984d985d8b7d8b1d982d8a920d8a7d984d8a7d987d8aad8b2d8a7d8b2d98ad8a920d8a7d984d8aad982d984d98ad8afd98ad8a920d88c20d981d987d98a20d8aad98fd8b8d987d8b120d985d8b2d8a7d98ad8a720d983d8a8d98ad8b1d8a920d88c20d8a8d985d8a720d981d98a20d8b0d984d9832028312920d8a3d8afd8b1d983d8aa20d8a8d8afd8a120d8a7d984d8aad8b4d8bad98ad98420d981d98a20d8b8d98420d8add8a7d984d8a920d8b9d8afd98520d988d8acd988d8af20d8b9d8b2d98520d8afd988d8b1d8a7d98620d8bad8b1d98ad8a820d8a7d984d8a3d8b7d988d8a7d8b120d88c20d988d8add98420d985d8b4d983d984d8a920d985d8b5d8afd8b120d8a7d984d8b7d8a7d982d8a920d8b0d98a20d8a7d984d8b3d8b9d8a920d8a7d984d983d8a8d98ad8b1d8a920d8a7d984d984d8a7d8b2d985d8a920d984d8a8d8afd8a120d8b9d8b2d98520d8a7d984d8afd988d8b1d8a7d98620d8a7d984d984d8a7d985d8b1d983d8b2d98a20d984d984d8a7d987d8aad8b2d8a7d8b220d8add8b2d8a7d98520d8a7d984d985d8b7d8b1d982d8a920d8a7d984d8b0d98a20d98ad8b3d8aad8aed8afd98520d8b9d984d98920d986d8b7d8a7d98220d988d8a7d8b3d8b920d981d98a20d8a7d984d988d982d8aa20d8a7d984d8add8a7d8b6d8b120d89b2028322920d984d982d8af20d8a3d8afd8b1d983d8aa20d8a8d8afd8a7d98ad8a92022d8b5d981d8b12220d9882022d8b5d981d8b12220d8aad988d982d98120d88c20d988d8a7d984d8aad98a20d98ad985d983d98620d8a3d98620d8aad8aad8bad984d8a820d8b9d984d98920d8a7d984d8b1d986d98ad98620d8a7d984d985d8aad988d984d8af20d8b9d986d8af20d8a7d984d8a8d8afd8a120d988d8a7d984d8aad988d982d98120d8a8d8b9d8b2d98520d8afd988d8b1d8a7d98620d8bad8b1d98ad8a820d8a7d984d8a3d8b7d988d8a7d8b120d88c20d988d8aad985d986d8b920d8add8afd988d8ab20d8b6d988d8b6d8a7d8a120d986d8a7d8aad8acd8a920d8b9d98620d8a7d984d8b1d986d98ad98620d988d8a5d8aad984d8a7d98120d8a3d8acd8b2d8a7d8a120d8a7d984d985d8b9d8afd8a7d8aa20d8a7d984d8a3d8aed8b1d98920d89b2028332920d98ad985d983d98620d8aad8b9d8afd98ad98420d8b9d8b2d98520d8a7d984d8afd988d8b1d8a7d98620d8a7d984d984d8a7d985d8b1d983d8b2d98a20d8a8d8b3d987d988d984d8a920d988d8add8b1d98ad8a920d8a3d8abd986d8a7d8a120d8aad8b4d8bad98ad98420d8a7d984d8a2d984d8a920d984d984d8aad983d98ad98120d985d8b920d985d8aad8b7d984d8a8d8a7d8aa20d8b7d8a8d982d8a7d8aa20d8a7d984d8aad8b1d8a8d8a920d8a7d984d985d8aed8aad984d981d8a920d88c20d988d8b0d984d98320d984d8aad8add982d98ad98220d8a7d984d8b3d8b1d8b9d8a920d988d8a7d984d983d981d8a7d8a1d8a920d8a7d984d985d8abd8a7d984d98ad8a920d984d8bad8b1d98220d8a7d984d988d8a8d8b12e3c6272202f3e3c2f703e, 'HX26D', 'مطرقة الاهتزاز الخالية من الرنين EP80 هي سلسلة جديدة من المنتجات التي تم تطويرها بشكل مشترك من قبل شركتنا ، ومعهد بكين لأبحاث ميكانيكا البناء ، وشركة Japan Jian Dao co.، LTD. وقد فازت بالجائزة الأولى في وزارة علوم البناء والتقدم التكنولوجي وأدرجت ضمن مشروع دليل تعزيز وتحويل إنجازات علوم وتكنولوجيا البناء ؛ يُعرف التصدير إلى اليابان باسم \"مطرقة كومة العصر\" ، وزارة البناء اليابانية (الآن وزارة الأراضي والنقل) على سلسلة من النماذج للاختبار والتقييم ، وهي مدرجة على أنها التكنولوجيا الجديدة لتعزيز استخدام المنتجات', '2023-01-01 04:21:07', '2023-01-01 04:21:07'),
(49, 8, 44, 25, 'Dragline Excavator', 'dragline-excavator', 'ranging from 100m to 130m (325ft to 425ft)\r\nweight varying between 7,539t and 8,002t\r\ndigging depths are up to 64.5m and 76.7m respectively\r\nbucket capacity of 85m³ to 122m³', 0x3c703e49742069732061206c6f6e672d65737461626c6973686564206661637420746861742061207265616465722077696c6c206265206469737472616374656420627920746865207265616461626c6520636f6e74656e74206f6620612070616765207768656e206c6f6f6b696e6720617420697473206c61796f75742e3c2f703e3c703e2054686520706f696e74206f66207573696e67204c6f72656d20497073756d2069732074686174206974206861732061206d6f72652d6f722d6c657373206e6f726d616c20646973747269627574696f6e206f66206c6574746572732c206173206f70706f73656420746f207573696e672027436f6e74656e7420686572652c20636f6e74656e742068657265272c206d616b696e67206974206c6f6f6b206c696b65207265616461626c6520456e676c6973682e203c2f703e3c703e4d616e79206465736b746f70207075626c697368696e67207061636b6167657320616e6420776562207061676520656469746f7273206e6f7720757365204c6f72656d20497073756d2061732074686569722064656661756c74206d6f64656c20746578742c20616e6420612073656172636820666f7220276c6f72656d20497073756d2077696c6c20756e636f766572206d616e79207765627369746573207374696c6c20696e20746865697220696e66616e63792e20566172696f75732076657273696f6e7320686176652065766f6c766564206f766572207468652079656172732c20736f6d6574696d6573206279206163636964656e742c20736f6d6574696d6573206f6e20707572706f73652028696e6a65637465642068756d6f7220616e6420746865206c696b65292e3c2f703e3c703e72696174696f6e73206f66207061737361676573206f66204c6f72656d20497073756d20617661696c61626c652c2062757420746865206d616a6f72697479206861766520737566666572656420616c7465726174696f6e20696e20736f6d6520666f726d2c20627920696e6a65637465642068756d6f75722c206f722072616e646f6d6973656420776f72647320776869636820646f6e2774206c6f6f6b206576656e20736c696768746c792062656c69657661626c652e20496620796f752061726520676f696e6720746f2075736520612070617373616765206f66204c6f72656d20497073756d2c20796f75206e65656420746f20626520737572652074686572652069736e277420616e797468696e6720656d62617272617373696e672068696464656e20696e20746865206d6964646c65206f6620746578742e20416c6c20746865204c6f72656d2049703c6272202f3e3c2f703e, 'Dragline Excavator', 'It is a long-established fact that a reader will be distracted by the readable content of a page when looking at its layout.\r\n\r\nThe point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English.', '2023-01-01 04:37:06', '2023-01-01 04:37:06'),
(50, 9, 47, 25, 'حفارة دراجلاين', 'حفارة-دراجلاين', 'تتراوح من 100 متر إلى 130 مترًا (325 قدمًا إلى 425 قدمًا)\r\nيتراوح الوزن بين 7539 طنًا و 8002 طنًا\r\nيصل عمق الحفر إلى 64.5 م و 76.7 م على التوالي\r\nسعة دلو من 85 متر مكعب إلى 122 متر مكعب', 0x3c703ed987d986d8a7d98320d8add982d98ad982d8a920d985d8abd8a8d8aad8a920d985d986d8b020d8b2d985d98620d8b7d988d98ad98420d988d987d98a20d8a3d98620d8a7d984d985d8add8aad988d98920d8a7d984d985d982d8b1d988d8a120d984d8b5d981d8add8a920d985d8a720d8b3d98ad984d987d98a20d8a7d984d982d8a7d8b1d8a620d8b9d98620d8a7d984d8aad8b1d983d98ad8b220d8b9d984d98920d8a7d984d8b4d983d98420d8a7d984d8aed8a7d8b1d8acd98a20d984d984d986d8b520d8a3d98820d8b4d983d98420d8aad988d8b6d8b920d8a7d984d981d982d8b1d8a7d8aa20d981d98a20d8a7d984d8b5d981d8add8a920d8a7d984d8aad98a20d98ad982d8b1d8a3d987d8a72e20d988d984d8b0d984d98320d98ad8aad98520d8a7d8b3d8aad8aed8afd8a7d98520d8b7d8b1d98ad982d8a920d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d984d8a3d986d987d8a720d8aad8b9d8b7d98a20d8aad988d8b2d98ad8b9d8a7d98e20d8b7d8a8d98ad8b9d98ad8a7d98e202dd8a5d984d98920d8add8af20d985d8a72d20d984d984d8a3d8add8b1d98120d8b9d988d8b6d8a7d98b20d8b9d98620d8a7d8b3d8aad8aed8afd8a7d9852022d987d986d8a720d98ad988d8acd8af20d985d8add8aad988d98920d986d8b5d98ad88c20d987d986d8a720d98ad988d8acd8af20d985d8add8aad988d98920d986d8b5d98a2220d981d8aad8acd8b9d984d987d8a720d8aad8a8d8afd9882028d8a3d98a20d8a7d984d8a3d8add8b1d9812920d988d983d8a3d986d987d8a720d986d8b520d985d982d8b1d988d8a12e20d8a7d984d8b9d8afd98ad8af20d985d98620d8a8d8b1d8a7d985d8ad20d8a7d984d986d8b4d8b120d8a7d984d985d983d8aad8a8d98a20d988d8a8d8b1d8a7d985d8ad20d8aad8add8b1d98ad8b120d8b5d981d8add8a7d8aa20d8a7d984d988d98ad8a820d8aad8b3d8aad8aed8afd98520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8a8d8b4d983d98420d8a5d981d8aad8b1d8a7d8b6d98a20d983d986d985d988d8b0d8ac20d8b9d98620d8a7d984d986d8b5d88c20d988d8a5d8b0d8a720d982d985d8aa20d8a8d8a5d8afd8aed8a7d98420226c6f72656d20697073756d2220d981d98a20d8a3d98a20d985d8add8b1d98320d8a8d8add8ab20d8b3d8aad8b8d987d8b120d8a7d984d8b9d8afd98ad8af20d985d98620d8a7d984d985d988d8a7d982d8b920d8a7d984d8add8afd98ad8abd8a920d8a7d984d8b9d987d8af20d981d98a20d986d8aad8a7d8a6d8ac20d8a7d984d8a8d8add8ab2e20d8b9d984d98920d985d8afd98920d8a7d984d8b3d986d98ad98620d8b8d987d8b1d8aa20d986d8b3d8ae20d8acd8afd98ad8afd8a920d988d985d8aed8aad984d981d8a920d985d98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d985d88c20d8a3d8add98ad8a7d986d8a7d98b20d8b9d98620d8b7d8b1d98ad98220d8a7d984d8b5d8afd981d8a9d88c20d988d8a3d8add98ad8a7d986d8a7d98b20d8b9d98620d8b9d985d8af20d983d8a5d8afd8aed8a7d98420d8a8d8b9d8b620d8a7d984d8b9d8a8d8a7d8b1d8a7d8aa20d8a7d984d981d983d8a7d987d98ad8a920d8a5d984d98ad987d8a72ec2a028d8a8d985d8b9d986d98920d8a3d98620d8a7d984d8bad8a7d98ad8a920d987d98a20d8a7d984d8b4d983d98420d988d984d98ad8b320d8a7d984d985d8add8aad988d9892920d988d98ad98fd8b3d8aad8aed8afd98520d981d98a20d8b5d986d8a7d8b9d8a7d8aa20d8a7d984d985d8b7d8a7d8a8d8b920d988d8afd988d8b120d8a7d984d986d8b4d8b12e20d983d8a7d98620d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d988d984d8a7d98ad8b2d8a7d98420d8a7d984d985d8b9d98ad8a7d8b120d984d984d986d8b520d8a7d984d8b4d983d984d98a20d985d986d8b020d8a7d984d982d8b1d98620d8a7d984d8aed8a7d985d8b320d8b9d8b4d8b120d8b9d986d8afd985d8a720d982d8a7d985d8aa20d985d8b7d8a8d8b9d8a920d985d8acd987d988d984d8a920d8a8d8b1d8b520d985d8acd985d988d8b9d8a920d985d98620d8a7d984d8a3d8add8b1d98120d8a8d8b4d983d98420d8b9d8b4d988d8a7d8a6d98a20d8a3d8aed8b0d8aad987d8a720d985d98620d986d8b5d88c20d984d8aad983d988d991d98620d983d8aad98ad991d8a820d8a8d985d8abd8a7d8a8d8a920d8afd984d98ad98420d8a3d98820d985d8b1d8acd8b920d8b4d983d984d98a20d984d987d8b0d98720d8a7d984d8a3d8add8b1d9812e20d8aed985d8b3d8a920d982d8b1d988d98620d985d98620d8a7d984d8b2d985d98620d984d98520d8aad982d8b6d98a20d8b9d984d98920d987d8b0d8a720d8a7d984d986d8b53c6272202f3e3c2f703e, 'حفارة دراجلاين', 'ضع الفقرات في الصفحة التي يقرأها. ولذلك يتم استخدام طريقة لوريم إيبسوم لأنها تعطي توزيعاَ طبيعياَ -إلى حد ما- للأحرف عوضاً عن استخدام \"هنا يوجد محتوى نصي، هنا يوجد محتوى نصي\" فتجعلها تبدو (أي الأحرف) وكأنها نص مقروء. العديد من برامح النشر المكتبي وبرامح تحرير صفحات الويب تستخدم لوريم إيبسوم بشكل إفتراضي كنموذج عن النص، وإذا قمت بإدخال \"lorem ipsum\" في أي محرك بحث ستظهر العديد', '2023-01-01 04:37:06', '2023-01-01 04:37:06'),
(51, 8, 44, 26, 'Bulldozer D3', 'bulldozer-d3', 'Operating Weight 50,259 lbs\r\nBlade Capacity 14.8 yd3\r\nEngine Model	SAA6D114E-6\r\nRelief Valve Setting 4,050 psi\r\nRated Speed	1,950 rpm', 0x3c703e5365642075742070657273706963696174697320756e6465206f6d6e69732069737465206e61747573206572726f722073697420766f6c7570746174656d206163637573616e7469756d20646f6c6f72656d717565206c617564616e7469756d2c20746f74616d2072656d206170657269616d2c2065617175652069707361207175616520616220696c6c6f20696e76656e746f726520766572697461746973206574207175617369206172636869746563746f206265617461652076697461652064696374612073756e74206578706c696361626f2e204e656d6f20656e696d20697073616d20766f6c7570746174656d207175696120766f6c7570746173207369742061737065726e6174757220617574206f646974206175742066756769742c20736564207175696120636f6e73657175756e747572206d61676e6920646f6c6f72657320656f732071756920726174696f6e6520766f6c7570746174656d207365717569206e65736369756e742e204e6571756520706f72726f20717569737175616d206573742c2071756920646f6c6f72656d20697073756d207175696120646f6c6f722073697420616d65742c20636f6e73656374657475722c2061646970697363692076656c69742c207365642071756961206e6f6e206e756d7175616d2065697573206d6f64692074656d706f726120696e636964756e74207574206c61626f726520657420646f6c6f7265206d61676e616d20616c697175616d207175616572617420766f6c7570746174656d2e20557420656e696d206164206d696e696d612076656e69616d2c2071756973206e6f737472756d20657865726369746174696f6e656d20756c6c616d20636f72706f726973207375736369706974206c61626f72696f73616d2c206e69736920757420616c697175696420657820656120636f6d6d6f646920636f6e73657175617475723f205175697320617574656d2076656c2065756d206975726520726570726568656e64657269742071756920696e20656120766f6c7570746174652076656c69742065737365207175616d206e6968696c206d6f6c65737469616520636f6e73657175617475722c2076656c20696c6c756d2071756920646f6c6f72656d2065756d206675676961742071756f20766f6c7570746173206e756c6c612070617269617475723f3c2f703e3c703e61676e616d20616c697175616d207175616572617420766f6c7570746174656d2e20557420656e696d206164206d696e696d612076656e69616d2c2071756973206e6f737472756d20657865726369746174696f6e656d20756c6c616d20636f72706f726973207375736369706974206c61626f72696f73616d2c206e69736920757420616c697175696420657820656120636f6d6d6f646920636f6e73657175617475723f205175697320617574656d2076656c2065756d206975726520726570726568656e64657269742071756920696e20656120766f6c7570746174652076656c69742065737365207175616d206e6968696c206d6f6c65737469616520636f6e73657175617475722c2076656c20696c6c756d2071756920646f6c6f72656d2065756d206675676961742071756f20766f6c7570746173206e756c6c612070617269617475723f3c6272202f3e3c2f703e, 'Bulldozer D3', 'tecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut', '2023-01-01 04:47:48', '2023-01-01 04:47:48'),
(52, 9, 47, 26, 'جرافة د 3', 'جرافة-د-3', 'وزن التشغيل 50.259 رطل\r\nسعة الشفرة 14.8 ياردة 3\r\nموديل المحرك SAA6D114E-6\r\nضبط صمام التصريف 4050 رطل لكل بوصة مربعة\r\nالسرعة المقدرة 1،950 دورة في الدقيقة', 0x3c703ed987d986d8a7d984d98320d8a7d984d8b9d8afd98ad8af20d985d98620d8a7d984d8a3d986d988d8a7d8b920d8a7d984d985d8aad988d981d8b1d8a920d984d986d8b5d988d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d985d88c20d988d984d983d98620d8a7d984d8bad8a7d984d8a8d98ad8a920d8aad98520d8aad8b9d8afd98ad984d987d8a720d8a8d8b4d983d98420d985d8a720d8b9d8a8d8b120d8a5d8afd8aed8a7d98420d8a8d8b9d8b620d8a7d984d986d988d8a7d8afd8b120d8a3d98820d8a7d984d983d984d985d8a7d8aa20d8a7d984d8b9d8b4d988d8a7d8a6d98ad8a920d8a5d984d98920d8a7d984d986d8b52e20d8a5d98620d983d986d8aa20d8aad8b1d98ad8af20d8a3d98620d8aad8b3d8aad8aed8afd98520d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d985d8a7d88c20d8b9d984d98ad98320d8a3d98620d8aad8aad8add982d98220d8a3d988d984d8a7d98b20d8a3d98620d984d98ad8b320d987d986d8a7d98320d8a3d98a20d983d984d985d8a7d8aa20d8a3d98820d8b9d8a8d8a7d8b1d8a7d8aa20d985d8add8b1d8acd8a920d8a3d98820d8bad98ad8b120d984d8a7d8a6d982d8a920d985d8aed8a8d8a3d8a920d981d98a20d987d8b0d8a720d8a7d984d986d8b52e20d8a8d98ad986d985d8a720d8aad8b9d985d98420d8acd985d98ad8b920d985d988d984d991d8afd8a7d8aa20d986d8b5d988d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8b9d984d98920d8a7d984d8a5d986d8aad8b1d986d8aa20d8b9d984d98920d8a5d8b9d8a7d8afd8a920d8aad983d8b1d8a7d8b120d985d982d8a7d8b7d8b920d985d98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d986d981d8b3d98720d8b9d8afd8a920d985d8b1d8a7d8aa20d8a8d985d8a720d8aad8aad8b7d984d8a8d98720d8a7d984d8add8a7d8acd8a9d88c20d98ad982d988d98520d985d988d984d991d8afd986d8a720d987d8b0d8a720d8a8d8a7d8b3d8aad8aed8afd8a7d98520d983d984d985d8a7d8aa20d985d98620d982d8a7d985d988d8b320d98ad8add988d98a20d8b9d984d98920d8a3d983d8abd8b120d985d9862032303020d983d984d985d8a920d984d8a720d8aad98ad986d98ad8a9d88c20d985d8b6d8a7d98120d8a5d984d98ad987d8a720d985d8acd985d988d8b9d8a920d985d98620d8a7d984d8acd985d98420d8a7d984d986d985d988d8b0d8acd98ad8a9d88c20d984d8aad983d988d98ad98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8b0d98820d8b4d983d98420d985d986d8b7d982d98a20d982d8b1d98ad8a820d8a5d984d98920d8a7d984d986d8b520d8a7d984d8add982d98ad982d98a2e20d988d8a8d8a7d984d8aad8a7d984d98a20d98ad983d988d98620d8a7d984d986d8b520d8a7d984d986d8a7d8aad8ad20d8aed8a7d984d98a20d985d98620d8a7d984d8aad983d8b1d8a7d8b1d88c20d8a3d98820d8a3d98a20d983d984d985d8a7d8aa20d8a3d98820d8b9d8a8d8a7d8b1d8a7d8aa20d8bad98ad8b120d984d8a7d8a6d982d8a920d8a3d98820d985d8a720d8b4d8a7d8a8d9872e20d988d987d8b0d8a720d985d8a720d98ad8acd8b9d984d98720d8a3d988d98420d985d988d984d991d8af20d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8add982d98ad982d98a20d8b9d984d98920d8a7d984d8a5d986d8aad8b1d986d8aa2e3c2f703e3c703ed987d986d8a7d984d98320d8a7d984d8b9d8afd98ad8af20d985d98620d8a7d984d8a3d986d988d8a7d8b920d8a7d984d985d8aad988d981d8b1d8a920d984d986d8b5d988d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d985d88c20d988d984d983d98620d8a7d984d8bad8a7d984d8a8d98ad8a920d8aad98520d8aad8b9d8afd98ad984d987d8a720d8a8d8b4d983d98420d985d8a720d8b9d8a8d8b120d8a5d8afd8aed8a7d98420d8a8d8b9d8b620d8a7d984d986d988d8a7d8afd8b120d8a3d98820d8a7d984d983d984d985d8a7d8aa20d8a7d984d8b9d8b4d988d8a7d8a6d98ad8a920d8a5d984d98920d8a7d984d986d8b52e20d8a5d98620d983d986d8aa20d8aad8b1d98ad8af20d8a3d98620d8aad8b3d8aad8aed8afd98520d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d985d8a7d88c20d8b9d984d98ad98320d8a3d98620d8aad8aad8add982d98220d8a3d988d984d8a7d98b20d8a3d98620d984d98ad8b320d987d986d8a7d98320d8a3d98a20d983d984d985d8a7d8aa20d8a3d98820d8b9d8a8d8a7d8b1d8a7d8aa20d985d8add8b1d8acd8a920d8a3d98820d8bad98ad8b120d984d8a7d8a6d982d8a920d985d8aed8a8d8a3d8a920d981d98a20d987d8b0d8a720d8a7d984d986d8b52e20d8a8d98ad986d985d8a720d8aad8b9d985d98420d8acd985d98ad8b920d985d988d984d991d8afd8a7d8aa20d986d8b5d988d8b52e3c6272202f3e3c2f703e, 'جرافة د 3', 'ن الجمل النموذجية، لتكوين نص لوريم إيبسوم ذو شكل منطقي قريب إلى النص الحقيقي. وبالتالي يكون النص الناتح خالي من التكرار، أو أي كلمات أو عبارات غير لائقة أو ما شابه. وهذا ما يجعله أول مولّد نص لوريم إيبسوم حقيقي على الإنترنت.\r\n\r\nهنالك العديد من الأنواع المتوفرة لنصوص لوريم إيبسوم، ولكن الغالبية تم', '2023-01-01 04:47:48', '2023-01-01 04:47:48'),
(53, 8, 45, 27, 'Articulated combination 3.5 ton vibratory road roller', 'articulated-combination-3.5-ton-vibratory-road-roller', 'Model Number: ST3500\r\nType: Vibratory Roller\r\nPower:28.5kw\r\nGrade Ability:30%\r\nVibration frequency:60HZ', 0x3c703e5365642075742070657273706963696174697320756e6465206f6d6e69732069737465206e61747573206572726f722073697420766f6c7570746174656d206163637573616e7469756d20646f6c6f72656d717565206c617564616e7469756d2c20746f74616d2072656d206170657269616d2c2065617175652069707361207175616520616220696c6c6f20696e76656e746f726520766572697461746973206574207175617369206172636869746563746f206265617461652076697461652064696374612073756e74206578706c696361626f2e204e656d6f20656e696d20697073616d20766f6c7570746174656d207175696120766f6c7570746173207369742061737065726e6174757220617574206f646974206175742066756769742c20736564207175696120636f6e73657175756e747572206d61676e6920646f6c6f72657320656f732071756920726174696f6e6520766f6c7570746174656d207365717569206e65736369756e742e204e6571756520706f72726f20717569737175616d206573742c2071756920646f6c6f72656d20697073756d207175696120646f6c6f722073697420616d65742c20636f6e73656374657475722c2061646970697363692076656c69742c207365642071756961206e6f6e206e756d7175616d2065697573206d6f64692074656d706f726120696e636964756e74207574206c61626f726520657420646f6c6f7265206d61676e616d20616c697175616d207175616572617420766f6c7570746174656d2e20557420656e696d206164206d696e696d612076656e69616d2c2071756973206e6f737472756d20657865726369746174696f6e656d20756c6c616d20636f72706f726973207375736369706974206c61626f72696f73616d2c206e69736920757420616c697175696420657820656120636f6d6d6f646920636f6e73657175617475723f205175697320617574656d2076656c2065756d206975726520726570726568656e64657269742071756920696e20656120766f6c7570746174652076656c69742065737365207175616d206e6968696c206d6f6c65737469616520636f6e73657175617475722c2076656c20696c6c756d2071756920646f6c6f72656d2065756d206675676961742071756f20766f6c7570746173206e756c6c612070617269617475723f3c2f703e3c703e617420766f6c7570746174656d2e20557420656e696d206164206d696e696d612076656e69616d2c2071756973206e6f737472756d20657865726369746174696f6e656d20756c6c616d20636f72706f726973207375736369706974206c61626f72696f73616d2c206e69736920757420616c697175696420657820656120636f6d6d6f646920636f6e73657175617475723f205175697320617574656d2076656c2065756d206975726520726570726568656e64657269742071756920696e20656120766f6c7570746174652076656c69742065737365207175616d206e6968696c206d6f6c65737469616520636f6e73657175617475722c2076656c20696c6c756d2071756920646f6c6f72656d2065756d206675676961742071756f20766f6c7570746173206e756c6c612070617269617475723f3c2f703e, 'road roller', 'Articulated combination 3.5 ton vibratory road roller\r\nArticulated combination 3.5 ton vibratory road roller', '2023-01-01 05:08:24', '2023-01-01 05:08:24'),
(54, 9, 48, 27, 'تركيبة مفصلية مدحلة اهتزازية 3.5 طن', 'تركيبة-مفصلية-مدحلة-اهتزازية-3.5-طن', 'رقم الموديل: ST3500\r\nالنوع: أسطوانة اهتزازية\r\nالطاقة: 28.5 كيلو واط\r\nالقدرة على الصف: 30٪\r\nتردد الاهتزاز: 60 هرتز', 0x3c703ed987d986d8a7d984d98320d8a7d984d8b9d8afd98ad8af20d985d98620d8a7d984d8a3d986d988d8a7d8b920d8a7d984d985d8aad988d981d8b1d8a920d984d986d8b5d988d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d985d88c20d988d984d983d98620d8a7d984d8bad8a7d984d8a8d98ad8a920d8aad98520d8aad8b9d8afd98ad984d987d8a720d8a8d8b4d983d98420d985d8a720d8b9d8a8d8b120d8a5d8afd8aed8a7d98420d8a8d8b9d8b620d8a7d984d986d988d8a7d8afd8b120d8a3d98820d8a7d984d983d984d985d8a7d8aa20d8a7d984d8b9d8b4d988d8a7d8a6d98ad8a920d8a5d984d98920d8a7d984d986d8b52e20d8a5d98620d983d986d8aa20d8aad8b1d98ad8af20d8a3d98620d8aad8b3d8aad8aed8afd98520d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d985d8a7d88c20d8b9d984d98ad98320d8a3d98620d8aad8aad8add982d98220d8a3d988d984d8a7d98b20d8a3d98620d984d98ad8b320d987d986d8a7d98320d8a3d98a20d983d984d985d8a7d8aa20d8a3d98820d8b9d8a8d8a7d8b1d8a7d8aa20d985d8add8b1d8acd8a920d8a3d98820d8bad98ad8b120d984d8a7d8a6d982d8a920d985d8aed8a8d8a3d8a920d981d98a20d987d8b0d8a720d8a7d984d986d8b52e20d8a8d98ad986d985d8a720d8aad8b9d985d98420d8acd985d98ad8b920d985d988d984d991d8afd8a7d8aa20d986d8b5d988d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8b9d984d98920d8a7d984d8a5d986d8aad8b1d986d8aa20d8b9d984d98920d8a5d8b9d8a7d8afd8a920d8aad983d8b1d8a7d8b120d985d982d8a7d8b7d8b920d985d98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d986d981d8b3d98720d8b9d8afd8a920d985d8b1d8a7d8aa20d8a8d985d8a720d8aad8aad8b7d984d8a8d98720d8a7d984d8add8a7d8acd8a9d88c20d98ad982d988d98520d985d988d984d991d8afd986d8a720d987d8b0d8a720d8a8d8a7d8b3d8aad8aed8afd8a7d98520d983d984d985d8a7d8aa20d985d98620d982d8a7d985d988d8b320d98ad8add988d98a20d8b9d984d98920d8a3d983d8abd8b120d985d9862032303020d983d984d985d8a920d984d8a720d8aad98ad986d98ad8a9d88c20d985d8b6d8a7d98120d8a5d984d98ad987d8a720d985d8acd985d988d8b9d8a920d985d98620d8a7d984d8acd985d98420d8a7d984d986d985d988d8b0d8acd98ad8a9d88c20d984d8aad983d988d98ad98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8b0d98820d8b4d983d98420d985d986d8b7d982d98a20d982d8b1d98ad8a820d8a5d984d98920d8a7d984d986d8b520d8a7d984d8add982d98ad982d98a2e20d988d8a8d8a7d984d8aad8a7d984d98a20d98ad983d988d98620d8a7d984d986d8b520d8a7d984d986d8a7d8aad8ad20d8aed8a7d984d98a20d985d98620d8a7d984d8aad983d8b1d8a7d8b1d88c20d8a3d98820d8a3d98a20d983d984d985d8a7d8aa20d8a3d98820d8b9d8a8d8a7d8b1d8a7d8aa20d8bad98ad8b120d984d8a7d8a6d982d8a920d8a3d98820d985d8a720d8b4d8a7d8a8d9872e20d988d987d8b0d8a720d985d8a720d98ad8acd8b9d984d98720d8a3d988d98420d985d988d984d991d8af20d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8add982d98ad982d98a20d8b9d984d98920d8a7d984d8a5d986d8aad8b1d986d8aa2e3c2f703e3c703ed987d986d8a7d984d98320d8a7d984d8b9d8afd98ad8af20d985d98620d8a7d984d8a3d986d988d8a7d8b920d8a7d984d985d8aad988d981d8b1d8a920d984d986d8b5d988d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d985d88c20d988d984d983d98620d8a7d984d8bad8a7d984d8a8d98ad8a920d8aad98520d8aad8b9d8afd98ad984d987d8a720d8a8d8b4d983d98420d985d8a720d8b9d8a8d8b120d8a5d8afd8aed8a7d98420d8a8d8b9d8b620d8a7d984d986d988d8a7d8afd8b120d8a3d98820d8a7d984d983d984d985d8a7d8aa20d8a7d984d8b9d8b4d988d8a7d8a6d98ad8a920d8a5d984d98920d8a7d984d986d8b52e20d8a5d98620d983d986d8aa20d8aad8b1d98ad8af20d8a3d98620d8aad8b3d8aad8aed8afd98520d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d985d8a7d88c20d8b9d984d98ad98320d8a3d98620d8aad8aad8add982d98220d8a3d988d984d8a7d98b20d8a3d98620d984d98ad8b320d987d986d8a7d98320d8a3d98a20d983d984d985d8a7d8aa20d8a3d98820d8b9d8a8d8a7d8b1d8a7d8aa20d985d8add8b1d8acd8a920d8a3d98820d8bad98ad8b120d984d8a7d8a6d982d8a920d985d8aed8a8d8a3d8a920d981d98a20d987d8b0d8a720d8a7d984d986d8b52e20d8a8d98ad986d985d8a720d8aad8b9d985d98420d8acd985d98ad8b920d985d988d984d991d8afd8a7d8aa20d986d8b5d988d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8b9d984d98920d8a7d984d8a5d986d8aad8b1d986d8aa20d8b9d984d98920d8a5d8b9d8a7d8afd8a920d8aad983d8b1d8a7d8b120d985d982d8a7d8b7d8b920d985d98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d986d981d8b3d98720d8b9d8afd8a920d985d8b1d8a7d8aa20d8a8d985d8a720d8aad8aad8b7d984d8a8d98720d8a7d984d8add8a7d8acd8a9d88c20d98ad982d988d98520d985d988d984d991d8afd986d8a720d987d8b0d8a720d8a8d8a7d8b3d8aad8aed8afd8a7d98520d983d984d985d8a7d8aa20d985d98620d982d8a7d985d988d8b320d98ad8add988d98a20d8b9d984d98920d8a3d983d8abd8b120d985d9862032303020d983d984d985d8a920d984d8a720d8aad98ad986d98ad8a9d88c20d985d8b6d8a7d98120d8a5d984d98ad987d8a720d985d8acd985d988d8b9d8a920d985d98620d8a7d984d8acd985d98420d8a7d984d986d985d988d8b0d8acd98ad8a9d88c20d984d8aad983d988d98ad98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8b0d98820d8b4d983d98420d985d986d8b7d982d98a20d982d8b1d98ad8a820d8a5d984d98920d8a7d984d986d8b520d8a7d984d8add982d98ad982d98a2e20d988d8a8d8a7d984d8aad8a7d984d98a20d98ad983d988d98620d8a7d984d986d8b520d8a7d984d986d8a7d8aad8ad20d8aed8a7d984d98a20d985d98620d8a7d984d8aad983d8b1d8a7d8b1d88c20d8a3d98820d8a3d98a20d983d984d985d8a7d8aa20d8a3d98820d8b9d8a8d8a7d8b1d8a7d8aa20d8bad98ad8b120d984d8a7d8a6d982d8a920d8a3d98820d985d8a720d8b4d8a7d8a8d9872e20d988d987d8b0d8a720d985d8a720d98ad8acd8b9d984d98720d8a3d988d98420d985d988d984d991d8af20d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8add982d98ad982d98a20d8b9d984d98920d8a7d984d8a5d986d8aad8b1d986d8aa2e3c6272202f3e3c2f703e, 'تركيبة', 'تركيبة مفصلية مدحلة اهتزازية 3.5 طن\r\nتركيبة مفصلية مدحلة اهتزازية 3.5 طن', '2023-01-01 05:08:24', '2023-01-01 05:08:24'),
(55, 8, 52, 28, '822E Feller Buncher', '822e-feller-buncher', 'LENGTH less boom	4 930 mm\r\nWIDTH	3 390 mm\r\nHEIGHT	3 330 mm\r\nGROUND CLEARANCE	760 mm\r\nWEIGHT less attachment	28 350 kg\r\nTAIL SWING RADIUS	2 095 mm', 0x3c703e436f6e747261727920746f20706f70756c61722062656c6965662c204c6f72656d20497073756d206973206e6f742073696d706c792072616e646f6d20746578742e2049742068617320726f6f747320696e2061207069656365206f6620636c6173736963616c204c6174696e206c6974657261747572652066726f6d2034352042432c206d616b696e67206974206f7665722032303030207965617273206f6c642e2052696368617264204d63436c696e746f636b2c2061204c6174696e2070726f666573736f722061742048616d7064656e2d5379646e657920436f6c6c65676520696e2056697267696e69612c206c6f6f6b6564207570206f6e65206f6620746865206d6f7265206f627363757265204c6174696e20776f7264732c20636f6e73656374657475722c2066726f6d2061204c6f72656d20497073756d20706173736167652c20616e6420676f696e67207468726f75676820746865206369746573206f662074686520776f726420696e20636c6173736963616c206c6974657261747572652c20646973636f76657265642074686520756e646f75627461626c6520736f757263652e204c6f72656d20497073756d20636f6d65732066726f6d2073656374696f6e7320312e31302e333220616e6420312e31302e3333206f66202264652046696e6962757320426f6e6f72756d206574204d616c6f72756d2220285468652045787472656d6573206f6620476f6f6420616e64204576696c292062792043696365726f2c207772697474656e20696e2034352042432e3c2f703e3c703e205468697320626f6f6b2069732061207472656174697365206f6e20746865207468656f7279206f66206574686963732c207665727920706f70756c617220647572696e67207468652052656e61697373616e63652e20546865206669727374206c696e65206f66204c6f72656d20497073756d2c20224c6f72656d20697073756d20646f6c6f722073697420616d65742e2e222c20636f6d65732066726f6d2061206c696e6520696e2073656374696f6e20312e31302e33322ec2a020546865207374616e64617264206368756e6b206f66204c6f72656d20497073756d20757365642073696e63652074686520313530307320697320726570726f64756365642062656c6f7720666f722074686f736520696e74657265737465642e2053656374696f6e7320312e31302e333220616e6420312e31302e33332066726f6d202264652046696e6962757320426f6e6f72756d206574204d616c6f72756d222062792043696365726f2061726520616c736f20726570726f647563656420696e207468656972206578616374206f726967696e616c20666f726d2c206163636f6d70616e69656420627920456e676c6973682076657273696f6e732066726f6d207468652031393134207472616e736c6174696f6e20627920482e205261636b68616d2e3c6272202f3e3c2f703e, '822E Feller Buncher', '822E Feller Buncher822E Feller Bunc  \r\nher822E Feller Buncher822E Feller Buncher822E Feller Buncher', '2023-01-01 05:16:35', '2023-01-01 05:16:35'),
(56, 9, 55, 28, '822E فيلير بانشر', '822e-فيلير-بانشر', 'LENGTH أقل ذراع الرافعة 4930 مم\r\nالعرض 3390 مم\r\nالارتفاع 3330 ملم\r\nتطهير الأرض 760 ملم\r\nالوزن أقل من الملحق 28350 كجم\r\nتيل سوينغ راديوس 2095 ملم', 0x3c703ed987d986d8a7d98320d8add982d98ad982d8a920d985d8abd8a8d8aad8a920d985d986d8b020d8b2d985d98620d8b7d988d98ad98420d988d987d98a20d8a3d98620d8a7d984d985d8add8aad988d98920d8a7d984d985d982d8b1d988d8a120d984d8b5d981d8add8a920d985d8a720d8b3d98ad984d987d98a20d8a7d984d982d8a7d8b1d8a620d8b9d98620d8a7d984d8aad8b1d983d98ad8b220d8b9d984d98920d8a7d984d8b4d983d98420d8a7d984d8aed8a7d8b1d8acd98a20d984d984d986d8b520d8a3d98820d8b4d983d98420d8aad988d8b6d8b920d8a7d984d981d982d8b1d8a7d8aa20d981d98a20d8a7d984d8b5d981d8add8a920d8a7d984d8aad98a20d98ad982d8b1d8a3d987d8a72e20d988d984d8b0d984d98320d98ad8aad98520d8a7d8b3d8aad8aed8afd8a7d98520d8b7d8b1d98ad982d8a920d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d984d8a3d986d987d8a720d8aad8b9d8b7d98a20d8aad988d8b2d98ad8b9d8a7d98e20d8b7d8a8d98ad8b9d98ad8a7d98e202dd8a5d984d98920d8add8af20d985d8a72d20d984d984d8a3d8add8b1d98120d8b9d988d8b6d8a7d98b20d8b9d98620d8a7d8b3d8aad8aed8afd8a7d9852022d987d986d8a720d98ad988d8acd8af20d985d8add8aad988d98920d986d8b5d98ad88c20d987d986d8a720d98ad988d8acd8af20d985d8add8aad988d98920d986d8b5d98a2220d981d8aad8acd8b9d984d987d8a720d8aad8a8d8afd9882028d8a3d98a20d8a7d984d8a3d8add8b1d9812920d988d983d8a3d986d987d8a720d986d8b520d985d982d8b1d988d8a12e20d8a7d984d8b9d8afd98ad8af20d985d98620d8a8d8b1d8a7d985d8ad20d8a7d984d986d8b4d8b120d8a7d984d985d983d8aad8a8d98a20d988d8a8d8b1d8a7d985d8ad20d8aad8add8b1d98ad8b120d8b5d981d8add8a7d8aa20d8a7d984d988d98ad8a820d8aad8b3d8aad8aed8afd98520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8a8d8b4d983d98420d8a5d981d8aad8b1d8a7d8b6d98a20d983d986d985d988d8b0d8ac20d8b9d98620d8a7d984d986d8b5d88c20d988d8a5d8b0d8a720d982d985d8aa20d8a8d8a5d8afd8aed8a7d98420226c6f72656d20697073756d2220d981d98a20d8a3d98a20d985d8add8b1d98320d8a8d8add8ab20d8b3d8aad8b8d987d8b120d8a7d984d8b9d8afd98ad8af20d985d98620d8a7d984d985d988d8a7d982d8b920d8a7d984d8add8afd98ad8abd8a920d8a7d984d8b9d987d8af20d981d98a20d986d8aad8a7d8a6d8ac20d8a7d984d8a8d8add8ab2e20d8b9d984d98920d985d8afd98920d8a7d984d8b3d986d98ad98620d8b8d987d8b1d8aa20d986d8b3d8ae20d8acd8afd98ad8afd8a920d988d985d8aed8aad984d981d8a920d985d98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d985d88c20d8a3d8add98ad8a7d986d8a7d98b20d8b9d98620d8b7d8b1d98ad98220d8a7d984d8b5d8afd981d8a9d88c20d988d8a3d8add98ad8a7d986d8a7d98b20d8b9d98620d8b9d985d8af20d983d8a5d8afd8aed8a7d98420d8a8d8b9d8b620d8a7d984d8b9d8a8d8a7d8b1d8a7d8aa20d8a7d984d981d983d8a7d987d98ad8a920d8a5d984d98ad987d8a72e3c2f703e3c703ed987d986d8a7d98320d8add982d98ad982d8a920d985d8abd8a8d8aad8a920d985d986d8b020d8b2d985d98620d8b7d988d98ad98420d988d987d98a20d8a3d98620d8a7d984d985d8add8aad988d98920d8a7d984d985d982d8b1d988d8a120d984d8b5d981d8add8a920d985d8a720d8b3d98ad984d987d98a20d8a7d984d982d8a7d8b1d8a620d8b9d98620d8a7d984d8aad8b1d983d98ad8b220d8b9d984d98920d8a7d984d8b4d983d98420d8a7d984d8aed8a7d8b1d8acd98a20d984d984d986d8b520d8a3d98820d8b4d983d98420d8aad988d8b6d8b920d8a7d984d981d982d8b1d8a7d8aa20d981d98a20d8a7d984d8b5d981d8add8a920d8a7d984d8aad98a20d98ad982d8b1d8a3d987d8a72e20d988d984d8b0d984d98320d98ad8aad98520d8a7d8b3d8aad8aed8afd8a7d98520d8b7d8b1d98ad982d8a920d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d984d8a3d986d987d8a720d8aad8b9d8b7d98a20d8aad988d8b2d98ad8b9d8a7d98e20d8b7d8a8d98ad8b9d98ad8a7d98e202dd8a5d984d98920d8add8af20d985d8a72d20d984d984d8a3d8add8b1d98120d8b9d988d8b6d8a7d98b20d8b9d98620d8a7d8b3d8aad8aed8afd8a7d9852022d987d986d8a720d98ad988d8acd8af20d985d8add8aad988d98920d986d8b5d98ad88c20d987d986d8a720d98ad988d8acd8af20d985d8add8aad988d98920d986d8b5d98a2220d981d8aad8acd8b9d984d987d8a720d8aad8a8d8afd9882028d8a3d98a20d8a7d984d8a3d8add8b1d9812920d988d983d8a3d986d987d8a720d986d8b520d985d982d8b1d988d8a12e20d8a7d984d8b9d8afd98ad8af20d985d98620d8a8d8b1d8a7d985d8ad20d8a7d984d986d8b4d8b120d8a7d984d985d983d8aad8a8d98a20d988d8a8d8b1d8a7d985d8ad20d8aad8add8b1d98ad8b120d8b5d981d8add8a7d8aa20d8a7d984d988d98ad8a820d8aad8b3d8aad8aed8afd98520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8a8d8b4d983d98420d8a5d981d8aad8b1d8a7d8b6d98a20d983d986d985d988d8b0d8ac20d8b9d98620d8a7d984d986d8b5d88c20d988d8a5d8b0d8a720d982d985d8aa20d8a8d8a5d8afd8aed8a7d98420226c6f72656d20697073756d2220d981d98a20d8a3d98a20d985d8add8b1d98320d8a8d8add8ab20d8b3d8aad8b8d987d8b120d8a7d984d8b9d8afd98ad8af20d985d98620d8a7d984d985d988d8a7d982d8b920d8a7d984d8add8afd98ad8abd8a920d8a7d984d8b9d987d8af20d981d98a20d986d8aad8a7d8a6d8ac20d8a7d984d8a8d8add8ab2e20d8b9d984d98920d985d8afd98920d8a7d984d8b3d986d98ad98620d8b8d987d8b1d8aa20d986d8b3d8ae20d8acd8afd98ad8afd8a920d988d985d8aed8aad984d981d8a920d985d98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d985d88c20d8a3d8add98ad8a7d986d8a7d98b20d8b9d98620d8b7d8b1d98ad98220d8a7d984d8b5d8afd981d8a9d88c20d988d8a3d8add98ad8a7d986d8a7d98b20d8b9d98620d8b9d985d8af20d983d8a5d8afd8aed8a7d98420d8a8d8b9d8b620d8a7d984d8b9d8a8d8a7d8b1d8a7d8aa20d8a7d984d981d983d8a7d987d98ad8a920d8a5d984d98ad987d8a72e3c6272202f3e3c2f703e, '822E Feller Buncher', '822E فيلر بانشر 822E فيلر بونك\r\nher822E Feller Buncher822E Feller Buncher822E Feller Buncher', '2023-01-01 05:16:35', '2023-01-01 05:16:35'),
(57, 8, 56, 29, 'EICHER PRO 1055T TIPPER', 'eicher-pro-1055t-tipper', 'Engine  E483 4cyl 2V\r\nCylinder / Displacement	4 Cylinder/3298 cc\r\nMax Power	70 Kw(~95 HP) @ 3200 RPM\r\nMax Torque	285 Nm @ 1440 RPM\r\nGear Box	ET 35 S5, 5 Speed (5 forward, 1 reverse)', 0x3c703e436f6e747261727920746f20706f70756c61722062656c6965662c204c6f72656d20497073756d206973206e6f742073696d706c792072616e646f6d20746578742e2049742068617320726f6f747320696e2061207069656365206f6620636c6173736963616c204c6174696e206c6974657261747572652066726f6d2034352042432c206d616b696e67206974206f7665722032303030207965617273206f6c642e2052696368617264204d63436c696e746f636b2c2061204c6174696e2070726f666573736f722061742048616d7064656e2d5379646e657920436f6c6c65676520696e2056697267696e69612c206c6f6f6b6564207570206f6e65206f6620746865206d6f7265206f627363757265204c6174696e20776f7264732c20636f6e73656374657475722c2066726f6d2061204c6f72656d20497073756d20706173736167652c20616e6420676f696e67207468726f75676820746865206369746573206f662074686520776f726420696e20636c6173736963616c206c6974657261747572652c20646973636f76657265642074686520756e646f75627461626c6520736f757263652e204c6f72656d20497073756d20636f6d65732066726f6d2073656374696f6e7320312e31302e333220616e6420312e31302e3333206f66202264652046696e6962757320426f6e6f72756d206574204d616c6f72756d2220285468652045787472656d6573206f6620476f6f6420616e64204576696c292062792043696365726f2c207772697474656e20696e2034352042432e205468697320626f6f6b2069732061207472656174697365206f6e20746865207468656f7279206f66206574686963732c207665727920706f70756c617220647572696e67207468652052656e61697373616e63652e20546865206669727374206c696e65206f66204c6f72656d20497073756d2c20224c6f72656d20697073756d20646f6c6f722073697420616d65742e2e222c20636f6d65732066726f6d2061206c696e6520696e2073656374696f6e20312e31302e33322ec2a020546865207374616e64617264206368756e6b206f66204c6f72656d20497073756d20757365642073696e63652074686520313530307320697320726570726f64756365642062656c6f7720666f722074686f736520696e74657265737465642e2053656374696f6e7320312e31302e333220616e6420312e31302e33332066726f6d202264652046696e6962757320426f6e6f72756d206574204d616c6f72756d222062792043696365726f2061726520616c736f20726570726f647563656420696e207468656972206578616374206f726967696e616c20666f726d2c206163636f6d70616e69656420627920456e676c6973682076657273696f6e732066726f6d207468652031393134207472616e736c6174696f6e20627920482e205261636b68616d2e3c6272202f3e3c2f703e, 'EICHER PRO 1055T TIPPER', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, lo', '2023-01-01 05:26:47', '2023-01-01 05:26:47'),
(58, 9, 55, 29, 'إيشر برو 1055 تي قلابة', 'إيشر-برو-1055-تي-قلابة', 'المحرك E483 4cyl 2V\r\nالاسطوانة / الإزاحة 4 سلندر / 3298 سم مكعب\r\nأقصى قوة 70 كيلوواط (~ 95 حصان) @ 3200 دورة في الدقيقة\r\nأقصى عزم 285 نيوتن متر عند 1440 دورة في الدقيقة\r\nصندوق التروس ET 35 S5 ، 5 سرعات (5 أمامي ، 1 خلفي)', 0x3c703e3c7370616e207374796c653d22666f6e742d66616d696c793a274f70656e2053616e73272c20417269616c2c2073616e732d73657269663b746578742d616c69676e3a6a7573746966793b223ed987d986d8a7d98320d8add982d98ad982d8a920d985d8abd8a8d8aad8a920d985d986d8b020d8b2d985d98620d8b7d988d98ad98420d988d987d98a20d8a3d98620d8a7d984d985d8add8aad988d98920d8a7d984d985d982d8b1d988d8a120d984d8b5d981d8add8a920d985d8a720d8b3d98ad984d987d98a20d8a7d984d982d8a7d8b1d8a620d8b9d98620d8a7d984d8aad8b1d983d98ad8b220d8b9d984d98920d8a7d984d8b4d983d98420d8a7d984d8aed8a7d8b1d8acd98a20d984d984d986d8b520d8a3d98820d8b4d983d98420d8aad988d8b6d8b920d8a7d984d981d982d8b1d8a7d8aa20d981d98a20d8a7d984d8b5d981d8add8a920d8a7d984d8aad98a20d98ad982d8b1d8a3d987d8a72e20d988d984d8b0d984d98320d98ad8aad98520d8a7d8b3d8aad8aed8afd8a7d98520d8b7d8b1d98ad982d8a920d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d984d8a3d986d987d8a720d8aad8b9d8b7d98a20d8aad988d8b2d98ad8b9d8a7d98e20d8b7d8a8d98ad8b9d98ad8a7d98e202dd8a5d984d98920d8add8af20d985d8a72d20d984d984d8a3d8add8b1d98120d8b9d988d8b6d8a7d98b20d8b9d98620d8a7d8b3d8aad8aed8afd8a7d9852022d987d986d8a720d98ad988d8acd8af20d985d8add8aad988d98920d986d8b5d98ad88c20d987d986d8a720d98ad988d8acd8af20d985d8add8aad988d98920d986d8b5d98a2220d981d8aad8acd8b9d984d987d8a720d8aad8a8d8afd9882028d8a3d98a20d8a7d984d8a3d8add8b1d9812920d988d983d8a3d986d987d8a720d986d8b520d985d982d8b1d988d8a12e20d8a7d984d8b9d8afd98ad8af20d985d98620d8a8d8b1d8a7d985d8ad20d8a7d984d986d8b4d8b120d8a7d984d985d983d8aad8a8d98a20d988d8a8d8b1d8a7d985d8ad20d8aad8add8b1d98ad8b120d8b5d981d8add8a7d8aa20d8a7d984d988d98ad8a820d8aad8b3d8aad8aed8afd98520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d98520d8a8d8b4d983d98420d8a5d981d8aad8b1d8a7d8b6d98a20d983d986d985d988d8b0d8ac20d8b9d98620d8a7d984d986d8b5d88c20d988d8a5d8b0d8a720d982d985d8aa20d8a8d8a5d8afd8aed8a7d98420226c6f72656d20697073756d2220d981d98a20d8a3d98a20d985d8add8b1d98320d8a8d8add8ab20d8b3d8aad8b8d987d8b120d8a7d984d8b9d8afd98ad8af20d985d98620d8a7d984d985d988d8a7d982d8b920d8a7d984d8add8afd98ad8abd8a920d8a7d984d8b9d987d8af20d981d98a20d986d8aad8a7d8a6d8ac20d8a7d984d8a8d8add8ab2e20d8b9d984d98920d985d8afd98920d8a7d984d8b3d986d98ad98620d8b8d987d8b1d8aa20d986d8b3d8ae20d8acd8afd98ad8afd8a920d988d985d8aed8aad984d981d8a920d985d98620d986d8b520d984d988d8b1d98ad98520d8a5d98ad8a8d8b3d988d985d88c20d8a3d8add98ad8a7d986d8a7d98b20d8b9d98620d8b7d8b1d98ad98220d8a7d984d8b5d8afd981d8a9d88c20d988d8a3d8add98ad8a7d986d8a7d98b20d8b9d98620d8b9d985d8af20d983d8a5d8afd8aed8a7d98420d8a8d8b9d8b620d8a7d984d8b9d8a8d8a7d8b1d8a7d8aa20d8a7d984d981d983d8a7d987d98ad8a920d8a5d984d98ad987d8a72e3c2f7370616e3e3c6272202f3e3c2f703e, 'إيشر برو 1055 تي قلابة', 'خلافًا للاعتقاد الشائع ، فإن Lorem Ipsum ليس مجرد نص عشوائي. لها جذور في قطعة من الأدب اللاتيني الكلاسيكي من 45 قبل الميلاد ، مما يجعلها أكثر من 2000 عام. ريتشارد مكلينتوك ، أستاذ لاتيني في كلية هامبدن سيدني في فيرجينيا ، لو', '2023-01-01 05:26:48', '2023-01-01 05:26:48'),
(59, 8, 52, 30, 'Cat Diecast 24M Motor Grader 85264C', 'cat-diecast-24m-motor-grader-85264c', 'Core Classic Cat 24M Motor Grader\r\n33.02cm x 11.43cm x 9.53cm.\r\n Cat models have a range of over 160 \r\nWe only buy from the Authorised Caterpillar scale\r\ndistributor and we warrant every', 0x3c703e436f726520436c6173736963204361742032344d204d6f746f7220477261646572202d20313a3530207363616c6520686967686c792064657461696c65642064696563617374206d6f64656c207265706c69636120666561747572696e67207265616c6973746963207061696e7420616e6420646563616c732c2066756c6c792061646a75737461626c6520626c6164652c206172746963756c617465642066726f6e74206672616d652c206172746963756c617465642072656172206178656c732c206d6f766561626c65207269707065722c207374656572696e672066726f6e7420776865656c7320616e642061757468656e74696320726f6c6c696e6720776865656c7320616e64207275626265722074797265732e204974656d2064696d656e73696f6e73206172652033332e3032636d20782031312e3433636d207820392e3533636d2e20546869732069732061206272616e64206e6577206974656d20696e206f726967696e616c20756e6f70656e6564206361726420646973706c617920626f7820616e642069732067756172616e7465656420746f20626520616e206f6666696369616c20436174657270696c6c6172206c6963656e7365642070726f647563742e20426577617265206f6620636f756e7465726665697473207368697070696e672066726f6d204368696e612e204361746d6f64656c73206861766520612072616e6765206f66206f766572203136302063757272656e7420616e64206d6f7374206f662074686520726563656e746c7920646973636f6e74696e75656420436174657270696c6c6172207363616c65206d6f64656c7320696e2073746f636b2e205765206f6e6c79206275792066726f6d2074686520417574686f726973656420436174657270696c6c6172207363616c65206d6f64656c206469737472696275746f7220616e642077652077617272616e74206576657279206974656d20746f2062652067656e75696e65206f726967696e616c732c206272616e64206e657720616e6420696e207065726665637420636f6e646974696f6e2e20416c6c206f72646572732061726520736869707065642077697468696e20323420686f7572732066726f6d206f7572204175737472616c69616e2077617265686f7573652e204974656d732072656365697665642062726f6b656e206f722064616d6167656420616e64207265706f727465642077697468696e20372064617973206f66206172726976616c2077696c6c206265207265706c61636564206174206e6f206368617267652e204164756c7420636f6c6c65637461626c652064657369676e656420666f7220616765732031342b3c6272202f3e3c2f703e, 'Cat Diecast 24M Motor Grader 85264C', 'Core Classic Cat 24M Motor Grader - 1:50 scale highly detailed diecast model replica featuring realistic paint and decals, fully adjustable blade, articulated front frame, articulated rear ax', '2023-01-01 05:35:47', '2023-01-01 05:35:47');
INSERT INTO `equipment_contents` (`id`, `language_id`, `equipment_category_id`, `equipment_id`, `title`, `slug`, `features`, `description`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(60, 9, 48, 30, 'ممهدة القط دييكاست', 'ممهدة-القط-دييكاست', 'Core Classic Cat 24M Motor Grader\r\n33.02 سم × 11.43 سم × 9.53 سم.\r\n تتميز موديلات Cat بمدى يزيد عن 160\r\nنحن نشتري فقط من ميزان Caterpillar المعتمد\r\nالموزع ونحن نضمن كل', 0x3c703e436f726520436c6173736963204361742032344d204d6f746f7220477261646572202d20d986d985d988d8b0d8ac20d985d8b5d8a8d988d8a820d8a8d985d982d98ad8a7d8b320313a353020d985d981d8b5d98420d984d984d8bad8a7d98ad8a920d98ad8aad985d98ad8b220d8a8d8b7d984d8a7d8a120d988d8a7d982d8b9d98a20d988d8b4d8a7d8b1d8a7d8aa20d88c20d988d8b4d981d8b1d8a920d982d8a7d8a8d984d8a920d984d984d8aad8b9d8afd98ad98420d8a8d8a7d984d983d8a7d985d98420d88c20d988d8a5d8b7d8a7d8b120d8a3d985d8a7d985d98a20d985d981d8b5d984d98a20d88c20d988d985d8add8a7d988d8b120d8aed984d981d98ad8a920d985d981d8b5d984d98ad8a920d88c20d988d983d8b3d8a7d8b1d8a920d985d8aad8add8b1d983d8a920d88c20d988d8b9d8acd984d8a7d8aa20d8a3d985d8a7d985d98ad8a920d984d984d8aad988d8acd98ad98720d988d8b9d8acd984d8a7d8aa20d8afd988d8a7d8b1d8a920d8a3d8b5d984d98ad8a920d988d8a5d8b7d8a7d8b1d8a7d8aa20d985d8b7d8a7d8b7d98ad8a92e20d8a3d8a8d8b9d8a7d8af20d8a7d984d8b3d984d8b9d8a92033332e303220d8b3d98520c3972031312e343320d8b3d98520c39720392e353320d8b3d9852e20d987d8b0d8a720d8b9d986d8b5d8b120d8acd8afd98ad8af20d8aad985d8a7d985d98bd8a720d981d98a20d8b5d986d8afd988d98220d8b9d8b1d8b620d8a7d984d8a8d8b7d8a7d982d8a7d8aa20d8a7d984d8a3d8b5d984d98a20d8bad98ad8b120d8a7d984d985d981d8aad988d8ad20d988d985d8b6d985d988d986d98bd8a720d984d98ad983d988d98620d985d986d8aad8acd98bd8a720d8b1d8b3d985d98ad98bd8a720d985d8b1d8aed8b5d98bd8a720d985d98620436174657270696c6c61722e20d8a7d8add8b0d8b120d985d98620d8b4d8add98620d8a7d984d985d986d8aad8acd8a7d8aa20d8a7d984d985d8b2d98ad981d8a920d985d98620d8a7d984d8b5d98ad9862e20d8aad985d8aad984d983204361746d6f64656c7320d986d8b7d8a7d982d98bd8a720d98ad8b2d98ad8af20d8b9d9862031363020d8add8a7d984d98ad98bd8a720d988d985d8b9d8b8d98520d986d985d8a7d8b0d8ac20d985d982d98ad8a7d8b320436174657270696c6c617220d8a7d984d8aad98a20d8aad98520d8a5d98ad982d8a7d98120d8a5d986d8aad8a7d8acd987d8a720d985d8a4d8aed8b1d98bd8a720d981d98a20d8a7d984d985d8aed8b2d988d9862e20d986d8add98620d986d8b4d8aad8b1d98a20d981d982d8b720d985d98620d8a7d984d985d988d8b2d8b920d8a7d984d985d8b9d8aad985d8af20d984d8b7d8b1d8a7d8b2d8a7d8aa20d985d8b5d8bad8b120436174657270696c6c617220d988d986d8b6d985d98620d8a3d98620d98ad983d988d98620d983d98420d8b9d986d8b5d8b120d8a3d8b5d984d98ad98bd8a720d988d8acd8afd98ad8afd98bd8a720d988d981d98a20d8add8a7d984d8a920d985d985d8aad8a7d8b2d8a92e20d98ad8aad98520d8b4d8add98620d8acd985d98ad8b920d8a7d984d8b7d984d8a8d8a7d8aa20d981d98a20d8bad8b6d988d98620323420d8b3d8a7d8b9d8a920d985d98620d985d8b3d8aad988d8afd8b9d8a7d8aad986d8a720d8a7d984d8a3d8b3d8aad8b1d8a7d984d98ad8a92e20d8b3d98ad8aad98520d8a7d8b3d8aad8a8d8afd8a7d98420d8a7d984d8b9d986d8a7d8b5d8b120d8a7d984d8aad98a20d8aad98520d8a7d8b3d8aad984d8a7d985d987d8a720d985d983d8b3d988d8b1d8a920d8a3d98820d8aad8a7d984d981d8a920d988d8aad98520d8a7d984d8a5d8a8d984d8a7d8ba20d8b9d986d987d8a720d8aed984d8a7d984203720d8a3d98ad8a7d98520d985d98620d8a7d984d988d8b5d988d98420d8a8d8afd988d98620d8a3d98a20d8b1d8b3d988d9852e20d982d8a7d8a8d98420d984d984d8aad8add8b5d98ad98420d984d984d8a8d8a7d984d8bad98ad98620d985d8b5d985d98520d984d984d8a3d8b9d985d8a7d8b120d985d98620313420d8b9d8a7d985d98bd8a720d981d985d8a720d981d988d9823c6272202f3e3c2f703e, 'ممهدة القط دييكاست', 'Core Classic Cat 24M Motor Grader - نموذج مصبوب بمقياس 1:50 مفصل للغاية يتميز بطلاء واقعي وشارات ، وشفرة قابلة للتعديل بالكامل ، وإطار أمامي مفصلي ، وفأس خلفي مفصلي', '2023-01-01 05:35:47', '2023-01-01 05:35:47'),
(61, 8, 52, 31, 'Civil and commercial Pipelines Trenching Machine', 'civil-and-commercial-pipelines-trenching-machine', 'Type Of Construction civil and commercial\r\nwater supply, telecom, and electrical contractor\r\nAll locations as per requirement\r\n150mm to 450 mm\r\nup to 2000mm', 0x3c703e496e636f72706f726174656420696e207468652079656172203230303020696e204a6169707572202852616a61737468616e2c20496e646961292c20776520e2809c4a2e20422e20496e6475737472696573e2809d20617265206120536f6c652050726f70726965746f7273686970206669726d2c20656e676167656420696e204d616e75666163747572696e67206120776964652072616e6765206f66204261636b686f65204c6f616465722c20506f737420486f6c65204469676765722c204372616e65204261636b686f652c204865617679204561727468204d6f7665722c206574632e20416c736f2c2077652074726164696e67206f66204472696c6c696e67204d616368696e652c205368656172696e67204d616368696e6520616e64204c61746865204d616368696e652e2054686573652070726f64756374732061726520776964656c7920617070726563696174656420666f72207468656972206665617475726573206c696b65206c6f6e672073657276696365206c6966652c206869676820737472656e6774682c20616e642073747572647920636f6e737472756374696f6e2e20556e64657220746865206c656164657273686970206f6620e2809c4d722e204d756b657368204b756d61776174e2809d20284f776e6572292c2077652068617665206265656e2061626c6520746f206d656574207468652062756c6b20726571756972656d656e7473206f6620636c69656e747320696e20612074696d656c79206d616e6e65722e2041706172742066726f6d20746865736520776520616c736f2070726f76696465204d616368696e6573204d61696e74656e616e636520536572766963652e203c2f703e3c703e496e636f72706f726174656420696e207468652079656172203230303020696e204a6169707572202852616a61737468616e2c20496e646961292c20776520e2809c4a2e20422e20496e6475737472696573e2809d20617265206120536f6c652050726f70726965746f7273686970206669726d2c20656e676167656420696e204d616e75666163747572696e67206120776964652072616e6765206f66204261636b686f65204c6f616465722c20506f737420486f6c65204469676765722c204372616e65204261636b686f652c204865617679204561727468204d6f7665722c206574632e20416c736f2c2077652074726164696e67206f66204472696c6c696e67204d616368696e652c205368656172696e67204d616368696e6520616e64204c61746865204d616368696e652e2054686573652070726f64756374732061726520776964656c7920617070726563696174656420666f72207468656972206665617475726573206c696b65206c6f6e672073657276696365206c6966652c206869676820737472656e6774682c20616e642073747572647920636f6e737472756374696f6e2e20556e64657220746865206c656164657273686970206f6620e2809c4d722e204d756b657368204b756d61776174e2809d20284f776e6572292c2077652068617665206265656e2061626c6520746f206d656574207468652062756c6b20726571756972656d656e7473206f6620636c69656e747320696e20612074696d656c79206d616e6e65722e2041706172742066726f6d20746865736520776520616c736f2070726f76696465204d616368696e6573204d61696e74656e616e636520536572766963652e3c6272202f3e3c2f703e, 'Civil and commercial Pipelines Trenching Machine', 'Loader, Post Hole Digger, Crane Backhoe, Heavy Earth Mover, etc. Also, we trading of Drilling Machine, Shearing Machine and Lathe Machine. These products are widely appreciated for their features like long service life, high strength, and sturdy construction. Under the leadership of “Mr. Mukesh Kumawat” (Owner), we have been able to meet t', '2023-01-01 05:45:49', '2023-01-01 05:45:49'),
(62, 9, 48, 31, 'آلة حفر الخنادق المدنية والتجارية', 'آلة-حفر-الخنادق-المدنية-والتجارية', 'نوع البناء المدني والتجاري\r\nمقاول إمدادات المياه والاتصالات والكهرباء\r\nجميع المواقع حسب المتطلبات\r\n150 ملم إلى 450 ملم\r\nيصل إلى 2000 ملم', 0x3c703ed8aad8a3d8b3d8b3d8aa20d981d98a20d8b9d8a7d985203230303020d981d98a20d8acd8a7d98ad8a8d988d8b12028d8b1d8a7d8acd8b3d8aad8a7d98620d88c20d8a7d984d987d986d8af2920d88c20d986d8add98620e2809c4a2e20422e20496e647573747269657320e2809cd987d98a20d8b4d8b1d983d8a920d8b0d8a7d8aa20d985d984d983d98ad8a920d981d8b1d8afd98ad8a920d88c20d8aad8b9d985d98420d981d98a20d8aad8b5d986d98ad8b920d985d8acd985d988d8b9d8a920d988d8a7d8b3d8b9d8a920d985d98620d8a7d984d984d988d8a7d8afd8b120d8b0d8a7d8aa20d8a7d984d985d8add8b1d8a7d8ab20d8a7d984d8aed984d981d98a20d88c20d988d8add981d8a7d8b120d985d8a720d8a8d8b9d8af20d8a7d984d8abd982d988d8a820d88c20d988d8a7d984d985d8add8b1d8a7d8ab20d8a7d984d8aed984d981d98a20d984d984d8b1d8a7d981d8b9d8a920d88c20d988d985d8add8b1d98320d8a7d984d8aad8b1d8a8d8a920d8a7d984d8abd982d98ad984d8a920d88c20d988d985d8a720d8a5d984d98920d8b0d984d98320d8a3d98ad8b6d98bd8a720d88c20d988d986d8add98620d986d8aad8a7d8acd8b120d981d98a20d8a2d984d8a920d8a7d984d8add981d8b120d988d8a2d984d8a920d8a7d984d982d8b520d988d8a2d984d8a920d8a7d984d985d8aed8b1d8b7d8a92e20d98ad8aad98520d8aad982d8afd98ad8b120d987d8b0d98720d8a7d984d985d986d8aad8acd8a7d8aa20d8b9d984d98920d986d8b7d8a7d98220d988d8a7d8b3d8b920d984d985d98ad8b2d8a7d8aad987d8a720d985d8abd98420d8b9d985d8b120d8a7d984d8aed8afd985d8a920d8a7d984d8b7d988d98ad98420d988d8a7d984d982d988d8a920d8a7d984d8b9d8a7d984d98ad8a920d988d8a7d984d8a8d986d8a7d8a120d8a7d984d982d988d98a2e20d8aad8add8aa20d982d98ad8a7d8afd8a920e2809cd8a32e20d985d988d983d98ad8b420d983d988d985d8a7d988d8a7d8aa20e2809d28d8a7d984d985d8a7d984d9832920d88c20d8aad985d983d986d8a720d985d98620d8aad984d8a8d98ad8a920d985d8aad8b7d984d8a8d8a7d8aa20d8a7d984d8b9d985d984d8a7d8a120d981d98a20d8a7d984d988d982d8aa20d8a7d984d985d986d8a7d8b3d8a82e20d8a8d8b5d8b1d98120d8a7d984d986d8b8d8b120d8b9d98620d987d8b0d98720d88c20d981d8a5d986d986d8a720d986d982d8afd98520d8a3d98ad8b6d98bd8a720d8aed8afd985d8a920d8b5d98ad8a7d986d8a920d8a7d984d8a2d984d8a7d8aa2ec2a020d8aad8a3d8b3d8b3d8aa20d981d98a20d8b9d8a7d985203230303020d981d98a20d8acd8a7d98ad8a8d988d8b12028d8b1d8a7d8acd8b3d8aad8a7d98620d88c20d8a7d984d987d986d8af2920d88c20d986d8add98620e2809c4a2e20422e20496e647573747269657320e2809cd987d98a20d8b4d8b1d983d8a920d8b0d8a7d8aa20d985d984d983d98ad8a920d981d8b1d8afd98ad8a920d88c20d8aad8b9d985d98420d981d98a20d8aad8b5d986d98ad8b920d985d8acd985d988d8b9d8a920d988d8a7d8b3d8b9d8a920d985d98620d8a7d984d984d988d8a7d8afd8b120d8b0d8a7d8aa20d8a7d984d985d8add8b1d8a7d8ab20d8a7d984d8aed984d981d98a20d88c20d988d8add981d8a7d8b120d985d8a720d8a8d8b9d8af20d8a7d984d8abd982d988d8a820d88c20d988d8a7d984d985d8add8b1d8a7d8ab20d8a7d984d8aed984d981d98a20d984d984d8b1d8a7d981d8b9d8a920d88c20d988d985d8add8b1d98320d8a7d984d8aad8b1d8a8d8a920d8a7d984d8abd982d98ad984d8a920d88c20d988d985d8a720d8a5d984d98920d8b0d984d98320d8a3d98ad8b6d98bd8a720d88c20d988d986d8add98620d986d8aad8a7d8acd8b120d981d98a20d8a2d984d8a920d8a7d984d8add981d8b120d988d8a2d984d8a920d8a7d984d982d8b520d988d8a2d984d8a920d8a7d984d985d8aed8b1d8b7d8a92e20d98ad8aad98520d8aad982d8afd98ad8b120d987d8b0d98720d8a7d984d985d986d8aad8acd8a7d8aa20d8b9d984d98920d986d8b7d8a7d98220d988d8a7d8b3d8b920d984d985d98ad8b2d8a7d8aad987d8a720d985d8abd98420d8b9d985d8b120d8a7d984d8aed8afd985d8a920d8a7d984d8b7d988d98ad98420d988d8a7d984d982d988d8a920d8a7d984d8b9d8a7d984d98ad8a920d988d8a7d984d8a8d986d8a7d8a120d8a7d984d982d988d98a2e20d8aad8add8aa20d982d98ad8a7d8afd8a920e2809cd8a32e20d985d988d983d98ad8b420d983d988d985d8a7d988d8a7d8aa20e2809d28d8a7d984d985d8a7d984d9832920d88c20d8aad985d983d986d8a720d985d98620d8aad984d8a8d98ad8a920d985d8aad8b7d984d8a8d8a7d8aa20d8a7d984d8b9d985d984d8a7d8a120d981d98a20d8a7d984d988d982d8aa20d8a7d984d985d986d8a7d8b3d8a82e20d8a8d8b5d8b1d98120d8a7d984d986d8b8d8b120d8b9d98620d987d8b0d98720d88c20d981d8a5d986d986d8a720d986d982d8afd98520d8a3d98ad8b6d98bd8a720d8aed8afd985d8a920d8b5d98ad8a7d986d8a920d8a7d984d8a2d984d8a7d8aa2e3c2f703e3c703ed985d984d983d98ad8a920d981d8b1d8afd98ad8a920d88c20d8aad8b9d985d98420d981d98a20d8aad8b5d986d98ad8b920d985d8acd985d988d8b9d8a920d988d8a7d8b3d8b9d8a920d985d98620d8a7d984d984d988d8a7d8afd8b120d8b0d8a7d8aa20d8a7d984d985d8add8b1d8a7d8ab20d8a7d984d8aed984d981d98a20d88c20d988d8add981d8a7d8b120d985d8a720d8a8d8b9d8af20d8a7d984d8abd982d988d8a820d88c20d988d8a7d984d985d8add8b1d8a7d8ab20d8a7d984d8aed984d981d98a20d984d984d8b1d8a7d981d8b9d8a920d88c20d988d985d8add8b1d98320d8a7d984d8aad8b1d8a8d8a920d8a7d984d8abd982d98ad984d8a920d88c20d988d985d8a720d8a5d984d98920d8b0d984d98320d8a3d98ad8b6d98bd8a720d88c20d988d986d8add98620d986d8aad8a7d8acd8b120d981d98a20d8a2d984d8a920d8a7d984d8add981d8b120d988d8a2d984d8a920d8a7d984d982d8b520d988d8a2d984d8a920d8a7d984d985d8aed8b1d8b7d8a92e20d98ad8aad98520d8aad982d8afd98ad8b120d987d8b0d98720d8a7d984d985d986d8aad8acd8a7d8aa20d8b9d984d98920d986d8b7d8a7d98220d988d8a7d8b3d8b920d984d985d98ad8b2d8a7d8aad987d8a720d985d8abd98420d8b9d985d8b120d8a7d984d8aed8afd985d8a920d8a7d984d8b7d988d98ad98420d988d8a7d984d982d988d8a920d8a7d984d8b9d8a7d984d98ad8a920d988d8a7d984d8a8d986d8a7d8a120d8a7d984d982d988d98a2e20d8aad8add8aa20d982d98ad8a7d8afd8a920e2809cd8a32e20d985d988d983d98ad8b420d9833c6272202f3e3c2f703e, 'آلة حفر الخنادق المدنية والتجارية', 'ملكية فردية ، تعمل في تصنيع مجموعة واسعة من اللوادر ذات المحراث الخلفي ، وحفار ما بعد الثقوب ، والمحراث الخلفي للرافعة ، ومحرك التربة الثقيلة ، وما إلى ذلك أيضًا ، ونحن نتاجر في آلة الحفر وآلة القص وآلة المخرطة. يتم تقدير هذه المنتجات على نطاق واسع لميزاتها مثل عمر الخدمة الطويل والقوة العالية والبناء القوي. تحت قيادة “أ. موكيش ك', '2023-01-01 05:45:49', '2023-01-01 05:45:49'),
(63, 8, 54, 32, 'Large Wheel Loaders', 'large-wheel-loaders', 'The new wheel loader model is equipped\r\nhis is a new feature with three\r\nthe respective applications', 0x3c703e546865206e657720776865656c206c6f61646572206d6f64656c206973206571756970706564207769746820612032382e35206b696c6f77617474202f2033382e3820656e67696e65206173207374616e646172642e2054686520657868617573742061667465722d74726561746d656e742069732063617272696564206f757420627920444f4320616e64204450462e20536d6172742044726976696e672050524f20697320616c736f206f7074696f6e616c6c7920617661696c61626c6520666f722074686520616c6c2d776865656c207374656572206c6f616465722e20546869732069732061206e65772066656174757265207769746820746872656520646966666572656e74206f7065726174696e67206d6f646573207468617420737570706f727420616e642072656c6965766520746865206f70657261746f7220696e207468652072657370656374697665206170706c69636174696f6e73207768696c7374206265696e67206675656c2d656666696369656e742e20496e2074686520506f776572204d6f64652c2074686520636f6d706c65746520656e67696e65206f757470757420697320617661696c61626c652e20546869732070726f766964657320616e20696465616c20626173697320666f722066617374206c6f6164696e67206379636c657320616e6420616c736f20666f7220776f726b20696e20657863617661746564206d6174657269616c2e20496e20636f6d70617269736f6e20746f20506f776572204d6f64652c20746865206d616368696e6520647269766573207468652073616d652074726176656c20737065656420696e2045636f204d6f64652077697468207265647563656420656e67696e652073706565642e20546f2072656475636520746865206e6f697365206c6576656c20616e6420746f2073617665206675656c2c2074686973206f70657261746f72206d6f64652069732061626f766520616c6c207375697461626c6520666f7220737461636b696e6720776f726b20696e207768696368207468652066756c6c2073797374656d20706f776572206973206e6f742072657175697265643c2f703e3c703e3c6272202f3e3c2f703e3c703e546865206e657720776865656c206c6f61646572206d6f64656c206973206571756970706564207769746820612032382e35206b696c6f77617474202f2033382e3820656e67696e65206173207374616e646172642e2054686520657868617573742061667465722d74726561746d656e742069732063617272696564206f757420627920444f4320616e64204450462e20536d6172742044726976696e672050524f20697320616c736f206f7074696f6e616c6c7920617661696c61626c6520666f722074686520616c6c2d776865656c207374656572206c6f616465722e20546869732069732061206e65772066656174757265207769746820746872656520646966666572656e74206f7065726174696e67206d6f646573207468617420737570706f727420616e642072656c6965766520746865206f70657261746f7220696e207468652072657370656374697665206170706c69636174696f6e73207768696c7374206265696e67206675656c2d656666696369656e742e20496e2074686520506f776572204d6f64652c2074686520636f6d706c65746520656e67696e65206f757470757420697320617661696c61626c652e20546869732070726f766964657320616e20696465616c20626173697320666f722066617374206c6f6164696e67206379636c657320616e6420616c736f20666f7220776f726b20696e20657863617661746564206d6174657269616c2e20496e20636f6d70617269736f6e20746f20506f776572204d6f64652c20746865206d616368696e6520647269766573207468652073616d652074726176656c20737065656420696e2045636f204d6f64652077697468207265647563656420656e67696e652073706565642e20546f2072656475636520746865206e6f697365206c6576656c20616e6420746f2073617665206675656c2c2074686973206f70657261746f72206d6f64652069732061626f766520616c6c207375697461626c6520666f7220737461636b696e6720776f726b20696e207768696368207468652066756c6c2073797374656d20706f776572206973206e6f742072657175697265643c6272202f3e3c2f703e, 'Large Wheel Loaders', 'The new wheel loader model is equipped with a 28.5 kilowatt / 38.8 engine as standard. The exhaust after-treatment is carried out by DOC and PDF. Smart Driving PRO is also optional', '2023-01-01 05:58:04', '2023-01-01 05:58:04'),
(64, 9, 55, 32, 'اللوادر ذات العجلات الكبيرة', 'اللوادر-ذات-العجلات-الكبيرة', 'تم تجهيز نموذج الجرافة الجديدة\r\nله ميزة جديدة مع ثلاثة\r\nالتطبيقات المعنية', 0x3c703ed8aad98520d8aad8acd987d98ad8b220d986d985d988d8b0d8ac20d8a7d984d8acd8b1d8a7d981d8a920d8a7d984d8acd8afd98ad8afd8a920d8a8d985d8add8b1d9832032382e3520d983d98ad984d988d988d8a7d8aa202f2033382e3820d983d985d8b9d98ad8a7d8b12e20d98ad8aad98520d8aad986d981d98ad8b020d8a7d984d985d8b9d8a7d984d8acd8a920d8a7d984d984d8a7d8add982d8a920d984d984d8b9d8a7d8afd98520d8a8d988d8a7d8b3d8b7d8a920444f4320d988204450462e20d98ad8aad988d981d8b120536d6172742044726976696e672050524f20d8a3d98ad8b6d98bd8a720d8a7d8aed8aad98ad8a7d8b1d98ad98bd8a720d984d984d988d8afd8b120d985d8aad8b9d8afd8af20d8a7d984d8b9d8acd984d8a7d8aa2e20d987d8b0d98720d985d98ad8b2d8a920d8acd8afd98ad8afd8a920d985d8b920d8abd984d8a7d8abd8a920d8a3d988d8b6d8a7d8b920d8aad8b4d8bad98ad98420d985d8aed8aad984d981d8a920d8aad8afd8b9d98520d988d8aad8b1d98ad8ad20d8a7d984d985d8b4d8bad98420d981d98a20d8a7d984d8aad8b7d8a8d98ad982d8a7d8aa20d8a7d984d985d8b9d986d98ad8a920d985d8b920d8aad988d981d98ad8b120d8a7d8b3d8aad987d984d8a7d98320d8a7d984d988d982d988d8af2e20d981d98a20d988d8b6d8b920d8a7d984d8b7d8a7d982d8a920d88c20d98ad8aad988d981d8b120d8aed8b1d8ac20d8a7d984d985d8add8b1d98320d8a7d984d983d8a7d985d9842e20d98ad988d981d8b120d987d8b0d8a720d8a3d8b3d8a7d8b3d98bd8a720d985d8abd8a7d984d98ad98bd8a720d984d8afd988d8b1d8a7d8aa20d8a7d984d8aad8add985d98ad98420d8a7d984d8b3d8b1d98ad8b920d988d8a3d98ad8b6d98bd8a720d984d984d8b9d985d98420d981d98a20d8a7d984d985d988d8a7d8af20d8a7d984d985d8add981d988d8b1d8a92e20d8a8d8a7d984d985d982d8a7d8b1d986d8a920d985d8b920d988d8b6d8b920d8a7d984d8b7d8a7d982d8a920d88c20d8aad8b9d985d98420d8a7d984d985d8a7d983d98ad986d8a920d8a8d986d981d8b320d8b3d8b1d8b9d8a920d8a7d984d8b3d98ad8b120d981d98a20d8a7d984d988d8b6d8b920d8a7d984d8a7d982d8aad8b5d8a7d8afd98a20d985d8b920d8a7d986d8aed981d8a7d8b620d8b3d8b1d8b9d8a920d8a7d984d985d8add8b1d9832e20d984d8aad982d984d98ad98420d985d8b3d8aad988d98920d8a7d984d8b6d988d8b6d8a7d8a120d988d8aad988d981d98ad8b120d8a7d984d988d982d988d8af20d88c20d98ad8b9d8af20d988d8b6d8b920d8a7d984d985d8b4d8bad98420d987d8b0d8a720d985d986d8a7d8b3d8a8d98bd8a720d982d8a8d98420d983d98420d8b4d98ad8a120d984d8aad983d8afd98ad8b320d8a7d984d8a3d8b9d985d8a7d98420d8a7d984d8aad98a20d984d8a720d8aad8aad8b7d984d8a820d8b7d8a7d982d8a920d8a7d984d986d8b8d8a7d98520d8a7d984d983d8a7d985d984d8a9c2a020c2a020d8aad98520d8aad8acd987d98ad8b220d986d985d988d8b0d8ac20d8a7d984d8acd8b1d8a7d981d8a920d8a7d984d8acd8afd98ad8afd8a920d8a8d985d8add8b1d9832032382e3520d983d98ad984d988d988d8a7d8aa202f2033382e3820d983d985d8b9d98ad8a7d8b12e20d98ad8aad98520d8aad986d981d98ad8b020d8a7d984d985d8b9d8a7d984d8acd8a920d8a7d984d984d8a7d8add982d8a920d984d984d8b9d8a7d8afd98520d8a8d988d8a7d8b3d8b7d8a920444f4320d988204450462e20d98ad8aad988d981d8b120536d6172742044726976696e672050524f20d8a3d98ad8b6d98bd8a720d8a7d8aed8aad98ad8a7d8b1d98ad98bd8a720d984d984d988d8afd8b120d985d8aad8b9d8afd8af20d8a7d984d8b9d8acd984d8a7d8aa2e20d987d8b0d98720d985d98ad8b2d8a920d8acd8afd98ad8afd8a920d985d8b920d8abd984d8a7d8abd8a920d8a3d988d8b6d8a7d8b920d8aad8b4d8bad98ad98420d985d8aed8aad984d981d8a920d8aad8afd8b9d98520d988d8aad8b1d98ad8ad20d8a7d984d985d8b4d8bad98420d981d98a20d8a7d984d8aad8b7d8a8d98ad982d8a7d8aa20d8a7d984d985d8b9d986d98ad8a920d985d8b920d8aad988d981d98ad8b120d8a7d8b3d8aad987d984d8a7d98320d8a7d984d988d982d988d8af2e20d981d98a20d988d8b6d8b920d8a7d984d8b7d8a7d982d8a920d88c20d98ad8aad988d981d8b120d8aed8b1d8ac20d8a7d984d985d8add8b1d98320d8a7d984d983d8a7d985d9842e20d98ad988d981d8b120d987d8b0d8a720d8a3d8b3d8a7d8b3d98bd8a720d985d8abd8a7d984d98ad98bd8a720d984d8afd988d8b1d8a7d8aa20d8a7d984d8aad8add985d98ad98420d8a7d984d8b3d8b1d98ad8b920d988d8a3d98ad8b6d98bd8a720d984d984d8b9d985d98420d981d98a20d8a7d984d985d988d8a7d8af20d8a7d984d985d8add981d988d8b1d8a92e20d8a8d8a7d984d985d982d8a7d8b1d986d8a920d985d8b920d988d8b6d8b920d8a7d984d8b7d8a7d982d8a920d88c20d8aad8b9d985d98420d8a7d984d985d8a7d983d98ad986d8a920d8a8d986d981d8b320d8b3d8b1d8b9d8a920d8a7d984d8b3d98ad8b120d981d98a20d8a7d984d988d8b6d8b920d8a7d984d8a7d982d8aad8b5d8a7d8afd98a20d985d8b920d8a7d986d8aed981d8a7d8b620d8b3d8b1d8b9d8a920d8a7d984d985d8add8b1d9832e20d984d8aad982d984d98ad98420d985d8b3d8aad988d98920d8a7d984d8b6d988d8b6d8a7d8a120d988d8aad988d981d98ad8b120d8a7d984d988d982d988d8af20d88c20d98ad8b9d8af20d988d8b6d8b920d8a7d984d985d8b4d8bad98420d987d8b0d8a720d985d986d8a7d8b3d8a8d98bd8a720d982d8a8d98420d983d98420d8b4d98ad8a120d984d8aad983d8afd98ad8b320d8a7d984d8a3d8b9d985d8a7d98420d8a7d984d8aad98a20d984d8a720d8aad8aad8b7d984d8a820d8b7d8a7d982d8a920d8a7d984d986d8b8d8a7d98520d8a7d984d983d8a7d985d984d8a93c6272202f3e3c2f703e, 'اللوادر ذات العجلات الكبيرة', 'تم تجهيز نموذج الجرافة الجديدة بمحرك 28.5 كيلووات / 38.8 كمعيار. يتم تنفيذ المعالجة اللاحقة للعادم بواسطة DOC و DPF. Smart Driving PRO اختياري أيضًا', '2023-01-01 05:58:04', '2023-01-01 05:58:04'),
(65, 8, 45, 33, 'Kesar Road Paver Finisher Machine', 'kesar-road-paver-finisher-machine', 'Usage/Application Road Construction\r\nAutomation Grade	Semi-Automatic\r\nBrand/Make	Kesar\r\nCapacity	150 to 200 tons/hour\r\nPower Steering	Yes\r\nCondition	New', 0x3c703e274b45534152272050617665727320697320626173656420696e2050726573746f6e2077686572652077652068617665206f75722070726f64756374696f6e20666163746f72792c2073657276696365206465706172746d656e7420616e642073746f726573206465706172746d656e742e205765206861766520636f6e74696e756f75736c79206d616e75666163747572656420696e206f757220466163746f727920617420476f7a617269612c20446973743a204d656873616e612c2047756a617261742c20496e6469612053696e6365323030352e4f75722072616e6765206f66207275626265722074797265642050617665722046696e6973686572732070726f7669646520706176696e67207769647468732066726f6d20322e35206d6574657220746f20342e35206d657465727320616e64206f6e20637573746f6d657220726571756972656d656e747320757020746f2035206d65746572732077697468206465707468732066726f6d203130202d203230306d6d2e3c2f703e3c703e274b45534152272050617665727320697320626173656420696e2050726573746f6e2077686572652077652068617665206f75722070726f64756374696f6e20666163746f72792c2073657276696365206465706172746d656e7420616e642073746f726573206465706172746d656e742e205765206861766520636f6e74696e756f75736c79206d616e75666163747572656420696e206f757220466163746f727920617420476f7a617269612c20446973743a204d656873616e612c2047756a617261742c20496e6469612053696e6365323030352e4f75722072616e6765206f66207275626265722074797265642050617665722046696e6973686572732070726f7669646520706176696e67207769647468732066726f6d20322e35206d6574657220746f20342e35206d657465727320616e64206f6e20637573746f6d657220726571756972656d656e747320757020746f2035206d65746572732077697468206465707468732066726f6d203130202d203230306d6d2e3c2f703e3c703e274b45534152272050617665727320697320626173656420696e2050726573746f6e2077686572652077652068617665206f75722070726f64756374696f6e20666163746f72792c2073657276696365206465706172746d656e7420616e642073746f726573206465706172746d656e742e205765206861766520636f6e74696e756f75736c79206d616e75666163747572656420696e206f757220466163746f727920617420476f7a617269612c20446973743a204d656873616e612c2047756a617261742c20496e6469612053696e6365323030352e4f75722072616e6765206f66207275626265722074797265642050617665722046696e6973686572732070726f7669646520706176696e67207769647468732066726f6d20322e35206d6574657220746f20342e35206d657465727320616e64206f6e20637573746f6d657220726571756972656d656e747320757020746f2035206d65746572732077697468206465707468732066726f6d203130202d203230306d6d2e3c6272202f3e3c2f703e, 'Kesar Road Paver Finisher Machine', '\'KESAR\' Pavers is based in Preston where we have our production factory, service department and stores department. We have continuously manufactured in our Factory at Gozaria, Dist: Mehsana, Gujarat, India Since2005.Our r', '2023-01-01 22:46:35', '2023-01-01 22:46:35'),
(66, 9, 48, 33, 'آلة تشطيب رصف الطرق Kesar', 'آلة-تشطيب-رصف-الطرق-kesar', 'استخدام / تطبيق بناء الطرق\r\nدرجة الأتمتة شبه أوتوماتيكية\r\nالماركة / صنع قيصر\r\nالسعة من 150 إلى 200 طن / ساعة\r\nمقود مرن نعم\r\nحالة: جديدة', 0x3c703ed98ad982d8b920d985d982d8b120224b45534152222050617665727320d981d98a20d8a8d8b1d98ad8b3d8aad988d98620d8add98ad8ab20d984d8afd98ad986d8a720d985d8b5d986d8b920d8a5d986d8aad8a7d8ac20d988d982d8b3d98520d8aed8afd985d8a7d8aa20d988d982d8b3d98520d985d8aed8a7d8b2d9862e20d984d982d8af20d982d985d986d8a720d8a8d8a7d8b3d8aad985d8b1d8a7d8b120d8a8d8a7d984d8aad8b5d986d98ad8b920d981d98a20d985d8b5d986d8b9d986d8a720d981d98a20476f7a6172696120d88c20446973743a204d656873616e6120d88c2047756a6172617420d88c20496e64696120d985d986d8b020d8b9d8a7d98520323030352e20d8aad988d981d8b120d985d8acd985d988d8b9d8aad986d8a720d985d98620d8aad8b4d8b7d98ad8a8d8a7d8aa20d8a7d984d8b1d8b5d98120d8b0d8a7d8aa20d8a7d984d8a5d8b7d8a7d8b1d8a7d8aa20d8a7d984d985d8b7d8a7d8b7d98ad8a920d8b9d8b1d8b620d8b1d8b5d98120d985d98620322e3520d985d8aad8b120d8a5d984d98920342e3520d985d8aad8b120d988d8b9d984d98920d985d8aad8b7d984d8a8d8a7d8aa20d8a7d984d8b9d985d984d8a7d8a120d8add8aad989203520d8a3d985d8aad8a7d8b120d985d8b920d8a3d8b9d985d8a7d98220d985d986203130202d2032303020d985d984d9852ec2a020d98ad982d8b920d985d982d8b120224b45534152222050617665727320d981d98a20d8a8d8b1d98ad8b3d8aad988d98620d8add98ad8ab20d984d8afd98ad986d8a720d985d8b5d986d8b920d8a5d986d8aad8a7d8ac20d988d982d8b3d98520d8aed8afd985d8a7d8aa20d988d982d8b3d98520d985d8aed8a7d8b2d9862e20d984d982d8af20d982d985d986d8a720d8a8d8a7d8b3d8aad985d8b1d8a7d8b120d8a8d8a7d984d8aad8b5d986d98ad8b920d981d98a20d985d8b5d986d8b9d986d8a720d981d98a20476f7a6172696120d88c20446973743a204d656873616e6120d88c2047756a6172617420d88c20496e64696120d985d986d8b020d8b9d8a7d98520323030352e20d8aad988d981d8b120d985d8acd985d988d8b9d8aad986d8a720d985d98620d8aad8b4d8b7d98ad8a8d8a7d8aa20d8a7d984d8b1d8b5d98120d8b0d8a7d8aa20d8a7d984d8a5d8b7d8a7d8b1d8a7d8aa20d8a7d984d985d8b7d8a7d8b7d98ad8a920d8b9d8b1d8b620d8b1d8b5d98120d985d98620322e3520d985d8aad8b120d8a5d984d98920342e3520d985d8aad8b120d988d8b9d984d98920d985d8aad8b7d984d8a8d8a7d8aa20d8a7d984d8b9d985d984d8a7d8a120d8add8aad989203520d8a3d985d8aad8a7d8b120d985d8b920d8a3d8b9d985d8a7d98220d985d986203130202d2032303020d985d984d9852ec2a020d98ad982d8b920d985d982d8b120224b45534152222050617665727320d981d98a20d8a8d8b1d98ad8b3d8aad988d98620d8add98ad8ab20d984d8afd98ad986d8a720d985d8b5d986d8b920d8a5d986d8aad8a7d8ac20d988d982d8b3d98520d8aed8afd985d8a7d8aa20d988d982d8b3d98520d985d8aed8a7d8b2d9862e20d984d982d8af20d982d985d986d8a720d8a8d8a7d8b3d8aad985d8b1d8a7d8b120d8a8d8a7d984d8aad8b5d986d98ad8b920d981d98a20d985d8b5d986d8b9d986d8a720d981d98a20476f7a6172696120d88c20446973743a204d656873616e6120d88c2047756a6172617420d88c20496e64696120d985d986d8b020d8b9d8a7d98520323030352e20d8aad988d981d8b120d985d8acd985d988d8b9d8aad986d8a720d985d98620d8aad8b4d8b7d98ad8a8d8a7d8aa20d8a7d984d8b1d8b5d98120d8b0d8a7d8aa20d8a7d984d8a5d8b7d8a7d8b1d8a7d8aa20d8a7d984d985d8b7d8a7d8b7d98ad8a920d8b9d8b1d8b620d8b1d8b5d98120d985d98620322e3520d985d8aad8b120d8a5d984d98920342e3520d985d8aad8b120d988d8b9d984d98920d985d8aad8b7d984d8a8d8a7d8aa20d8a7d984d8b9d985d984d8a7d8a120d8add8aad989203520d8a3d985d8aad8a7d8b120d985d8b920d8a3d8b9d985d8a7d98220d985d986203130202d2032303020d985d984d9852e3c6272202f3e3c2f703e, 'Kesar Road Paver Finisher Machine', 'يقع مقر \"KESAR\" Pavers في بريستون حيث لدينا مصنع إنتاج وقسم خدمات وقسم مخازن. لقد صنعنا باستمرار في مصنعنا في Gozaria ، Dist: Mehsana ، Gujarat ، India منذ 2005.', '2023-01-01 22:46:35', '2023-01-01 22:46:35'),
(67, 8, 52, 34, 'Belaz 75710, Belarus', 'belaz-75710-belarus', 'the capacity of 496 tons\r\ntop speed of 64 km/h\r\nthe capacity to transport 496 tons of payload.\r\nThe power output of each engine is 2,300 horsepower\r\nthe latest model of the 797 dump', 0x3c703e576974682061207061796c6f6164206361706163697479206f662034393620746f6e732c2042656c617a2037353731302069732074686520776f726c642773206c617267657374206d696e696e672064756d7020747275636b2e2042656c61727573206f662042656c61727573206c61756e6368656420616e20756c7472612d68656176792064756d7020747275636b20696e204f63746f6265722032303133206174207468652072657175657374206f662061205275737369616e206d696e696e6720636f6d70616e792e205468652042656c617a20373537313020747275636b206973207363686564756c656420746f20676f206f6e2073616c6520696e20323031342e2054686520747275636b2069732032302e366d206c6f6e672c20382e32366d20686967682c20616e6420392e38376d20776964652e2054686520656d70747920776569676874206f66207468652076656869636c652069732033363020746f6e732e2042656c617a20373537313020686173206569676874204d696368656c696e206c6172676520747562656c65737320706e65756d6174696320746972657320616e642074776f2031362d63796c696e64657220747572626f636861726765642064696573656c20656e67696e65732e2054686520706f776572206f7574707574206f66206561636820656e67696e6520697320322c33303020686f727365706f7765722e205468652076656869636c65207573657320616e20656c656374726f6d656368616e6963616c207472616e736d697373696f6e2064726976656e20627920616c7465726e6174696e672063757272656e742e2054686520747275636b20686173206120746f70207370656564206f66203634206b6d2f682c20616e64206974206861732074686520636170616369747920746f207472616e73706f72742034393620746f6e73206f66207061796c6f61643c2f703e3c703e576974682061207061796c6f6164206361706163697479206f662034393620746f6e732c2042656c617a2037353731302069732074686520776f726c642773206c617267657374206d696e696e672064756d7020747275636b2e2042656c61727573206f662042656c61727573206c61756e6368656420616e20756c7472612d68656176792064756d7020747275636b20696e204f63746f6265722032303133206174207468652072657175657374206f662061205275737369616e206d696e696e6720636f6d70616e792e205468652042656c617a20373537313020747275636b206973207363686564756c656420746f20676f206f6e2073616c6520696e20323031342e2054686520747275636b2069732032302e366d206c6f6e672c20382e32366d20686967682c20616e6420392e38376d20776964652e2054686520656d70747920776569676874206f66207468652076656869636c652069732033363020746f6e732e2042656c617a20373537313020686173206569676874204d696368656c696e206c6172676520747562656c65737320706e65756d6174696320746972657320616e642074776f2031362d63796c696e64657220747572626f636861726765642064696573656c20656e67696e65732e2054686520706f776572206f7574707574206f66206561636820656e67696e6520697320322c33303020686f727365706f7765722e205468652076656869636c65207573657320616e20656c656374726f6d656368616e6963616c207472616e736d697373696f6e2064726976656e20627920616c7465726e6174696e672063757272656e742e2054686520747275636b20686173206120746f70207370656564206f66203634206b6d2f682c20616e64206974206861732074686520636170616369747920746f207472616e73706f72742034393620746f6e73206f66207061796c6f61643c6272202f3e3c2f703e, 'Belaz 75710, Belarus', 'With a payload capacity of 496 tons, Belaz 75710 is the world\'s largest mining dump truck. Belarus of Belarus launched an ultra-heavy dump truck in October 2013 at the request of a Russian mining company. The Belaz 75710 truck is scheduled to go on sale in 2014. The truck is 20.6m long, 8.26m high, and 9.87m wide. The empty weight of the vehicle is 360 tons', '2023-01-01 22:58:31', '2023-01-01 22:58:31'),
(68, 9, 48, 34, 'بيلاز 75710 ، بيلاروسيا', 'بيلاز-75710-،-بيلاروسيا', 'بسعة 496 طن\r\nالسرعة القصوى 64 كم / ساعة\r\nالقدرة على نقل 496 طن من الحمولة.\r\nيبلغ انتاج الطاقة لكل محرك 2300 حصان\r\nأحدث طراز من 797 تفريغ', 0x3c703ed8a8d8b3d8b9d8a920d8add985d988d984d8a92034393620d8b7d986d98bd8a720d88c20d8aad8b9d8af2042656c617a20373537313020d8a3d983d8a8d8b120d8b4d8a7d8add986d8a920d8aad8b9d8afd98ad98620d981d98a20d8a7d984d8b9d8a7d984d9852e20d8a3d8b7d984d982d8aa20d8a8d98ad984d8a7d8b1d988d8b3d98ad8a720d8a7d984d8a8d98ad984d8a7d8b1d988d8b3d98ad8a920d8b4d8a7d8add986d8a920d982d984d8a7d8a8d8a920d8abd982d98ad984d8a920d8acd8afd98bd8a720d981d98a20d8a3d983d8aad988d8a8d8b1203230313320d8a8d986d8a7d8a1d98b20d8b9d984d98920d8b7d984d8a820d8b4d8b1d983d8a920d8aad8b9d8afd98ad98620d8b1d988d8b3d98ad8a92e20d985d98620d8a7d984d985d982d8b1d8b120d8b7d8b1d8ad20d8b4d8a7d8add986d8a92042656c617a20373537313020d984d984d8a8d98ad8b920d981d98a20d8b9d8a7d98520323031342e20d98ad8a8d984d8ba20d8b7d988d98420d8a7d984d8b4d8a7d8add986d8a92032302e3620d985d8aad8b1d98bd8a720d988d8a7d8b1d8aad981d8a7d8b9d987d8a720382e323620d985d8aad8b1d98bd8a720d988d8b9d8b1d8b6d987d8a720392e383720d985d8aad8b1d98bd8a72e20d8a7d984d988d8b2d98620d8a7d984d981d8a7d8b1d8ba20d984d984d985d8b1d983d8a8d8a92033363020d8b7d9862e20d98ad8add8aad988d98a2042656c617a20373537313020d8b9d984d98920d8abd985d8a7d986d98ad8a920d8a5d8b7d8a7d8b1d8a7d8aa20d8aad8b9d985d98420d8a8d8a7d984d987d988d8a7d8a120d8a7d984d985d8b6d8bad988d8b720d983d8a8d98ad8b1d8a920d8a7d984d8add8acd98520d985d98620d8b7d8b1d8a7d8b220d985d98ad8b4d984d8a7d98620d988d8a7d8abd986d98ad98620d985d98620d985d8add8b1d983d8a7d8aa20d8a7d984d8afd98ad8b2d98420d8b0d8a7d8aa20d8a7d984d8b4d8add98620d8a7d984d8aad988d8b1d8a8d98ad986d98a20313620d8a3d8b3d8b7d988d8a7d986d8a92e20d98ad8a8d984d8ba20d8a7d986d8aad8a7d8ac20d8a7d984d8b7d8a7d982d8a920d984d983d98420d985d8add8b1d983203233303020d8add8b5d8a7d9862e20d8aad8b3d8aad8aed8afd98520d8a7d984d8b3d98ad8a7d8b1d8a920d986d8a7d982d98420d8add8b1d983d8a920d983d987d8b1d988d985d98ad983d8a7d986d98ad983d98a20d985d8afd981d988d8b9d98bd8a720d8a8d8a7d984d8aad98ad8a7d8b120d8a7d984d985d8aad8b1d8afd8af2e20d8aad8a8d984d8ba20d8b3d8b1d8b9d8a920d8a7d984d8b4d8a7d8add986d8a920d8a7d984d982d8b5d988d98920363420d983d985202f20d8b3d8a7d8b9d8a920d88c20d988d984d8afd98ad987d8a720d8a7d984d982d8afd8b1d8a920d8b9d984d98920d986d982d9842034393620d8b7d986d98bd8a720d985d98620d8a7d984d8add985d988d984d8a920d8a7d984d8b5d8a7d981d98ad8a9c2a020d8a8d8b3d8b9d8a920d8add985d988d984d8a92034393620d8b7d986d98bd8a720d88c20d8aad8b9d8af2042656c617a20373537313020d8a3d983d8a8d8b120d8b4d8a7d8add986d8a920d8aad8b9d8afd98ad98620d981d98a20d8a7d984d8b9d8a7d984d9852e20d8a3d8b7d984d982d8aa20d8a8d98ad984d8a7d8b1d988d8b3d98ad8a720d8a7d984d8a8d98ad984d8a7d8b1d988d8b3d98ad8a920d8b4d8a7d8add986d8a920d982d984d8a7d8a8d8a920d8abd982d98ad984d8a920d8acd8afd98bd8a720d981d98a20d8a3d983d8aad988d8a8d8b1203230313320d8a8d986d8a7d8a1d98b20d8b9d984d98920d8b7d984d8a820d8b4d8b1d983d8a920d8aad8b9d8afd98ad98620d8b1d988d8b3d98ad8a92e20d985d98620d8a7d984d985d982d8b1d8b120d8b7d8b1d8ad20d8b4d8a7d8add986d8a92042656c617a20373537313020d984d984d8a8d98ad8b920d981d98a20d8b9d8a7d98520323031342e20d98ad8a8d984d8ba20d8b7d988d98420d8a7d984d8b4d8a7d8add986d8a92032302e3620d985d8aad8b1d98bd8a720d988d8a7d8b1d8aad981d8a7d8b9d987d8a720382e323620d985d8aad8b1d98bd8a720d988d8b9d8b1d8b6d987d8a720392e383720d985d8aad8b1d98bd8a72e20d8a7d984d988d8b2d98620d8a7d984d981d8a7d8b1d8ba20d984d984d985d8b1d983d8a8d8a92033363020d8b7d9862e20d98ad8add8aad988d98a2042656c617a20373537313020d8b9d984d98920d8abd985d8a7d986d98ad8a920d8a5d8b7d8a7d8b1d8a7d8aa20d8aad8b9d985d98420d8a8d8a7d984d987d988d8a7d8a120d8a7d984d985d8b6d8bad988d8b720d983d8a8d98ad8b1d8a920d8a7d984d8add8acd98520d985d98620d8b7d8b1d8a7d8b220d985d98ad8b4d984d8a7d98620d988d8a7d8abd986d98ad98620d985d98620d985d8add8b1d983d8a7d8aa20d8a7d984d8afd98ad8b2d98420d8b0d8a7d8aa20d8a7d984d8b4d8add98620d8a7d984d8aad988d8b1d8a8d98ad986d98a20313620d8a3d8b3d8b7d988d8a7d986d8a92e20d98ad8a8d984d8ba20d8a7d986d8aad8a7d8ac20d8a7d984d8b7d8a7d982d8a920d984d983d98420d985d8add8b1d983203233303020d8add8b5d8a7d9862e20d8aad8b3d8aad8aed8afd98520d8a7d984d8b3d98ad8a7d8b1d8a920d986d8a7d982d98420d8add8b1d983d8a920d983d987d8b1d988d985d98ad983d8a7d986d98ad983d98a20d985d8afd981d988d8b9d98bd8a720d8a8d8a7d984d8aad98ad8a7d8b120d8a7d984d985d8aad8b1d8afd8af2e20d8aad8a8d984d8ba20d8b3d8b1d8b9d8a920d8a7d984d8b4d8a7d8add986d8a920d8a7d984d982d8b5d988d98920363420d983d985202f20d8b3d8a7d8b9d8a920d88c20d988d984d8afd98ad987d8a720d8a7d984d982d8afd8b1d8a920d8b9d984d98920d986d982d9842034393620d8b7d986d98bd8a720d985d98620d8a7d984d8add985d988d984d8a920d8a7d984d8b5d8a7d981d98ad8a93c6272202f3e3c2f703e, 'Belaz 75710, Belarus', 'بسعة حمولة 496 طنًا ، تعد Belaz 75710 أكبر شاحنة تعدين في العالم. أطلقت بيلاروسيا البيلاروسية شاحنة قلابة ثقيلة جدًا في أكتوبر 2013 بناءً على طلب شركة تعدين روسية. من المقرر طرح شاحنة Belaz 75710 للبيع في عام 2014. يبلغ طول الشاحنة 20.6 مترًا وارتفاعها 8.26 مترًا وعرضها 9.87 مترًا. الوزن الفارغ للمركبة 360 طن', '2023-01-01 22:58:31', '2023-01-01 22:58:31');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_coupons`
--

CREATE TABLE `equipment_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(8,2) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `equipments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_coupons`
--

INSERT INTO `equipment_coupons` (`id`, `name`, `code`, `type`, `value`, `start_date`, `end_date`, `equipments`, `created_at`, `updated_at`) VALUES
(16, 'fahad', 'fahad', 'fixed', '10.00', '2022-12-22', '2023-03-09', NULL, '2022-12-22 05:15:32', '2022-12-22 05:15:32');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_locations`
--

CREATE TABLE `equipment_locations` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint DEFAULT NULL,
  `equipment_id` bigint DEFAULT NULL,
  `location_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_locations`
--

INSERT INTO `equipment_locations` (`id`, `language_id`, `equipment_id`, `location_id`, `created_at`, `updated_at`) VALUES
(13, 8, 35, 25, '2023-06-06 04:37:32', '2023-06-06 04:37:32'),
(14, 8, 35, 24, '2023-06-06 04:37:32', '2023-06-06 04:37:32'),
(15, 9, 35, 27, '2023-06-06 04:37:32', '2023-06-06 04:37:32'),
(16, 9, 35, 26, '2023-06-06 04:37:32', '2023-06-06 04:37:32'),
(61, 8, 32, 25, '2023-06-13 00:03:58', '2023-06-13 00:03:58'),
(62, 8, 32, 24, '2023-06-13 00:03:58', '2023-06-13 00:03:58'),
(63, 9, 32, 27, '2023-06-13 00:03:58', '2023-06-13 00:03:58'),
(64, 9, 32, 26, '2023-06-13 00:03:58', '2023-06-13 00:03:58'),
(65, 8, 36, 10, '2023-06-13 03:31:56', '2023-06-13 03:31:56'),
(66, 8, 36, 9, '2023-06-13 03:31:56', '2023-06-13 03:31:56'),
(67, 8, 36, 8, '2023-06-13 03:31:56', '2023-06-13 03:31:56'),
(68, 9, 36, 13, '2023-06-13 03:31:56', '2023-06-13 03:31:56'),
(69, 9, 36, 12, '2023-06-13 03:31:56', '2023-06-13 03:31:56'),
(70, 9, 36, 11, '2023-06-13 03:31:56', '2023-06-13 03:31:56');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_reviews`
--

CREATE TABLE `equipment_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `equipment_id` bigint UNSIGNED NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rating` smallint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_reviews`
--

INSERT INTO `equipment_reviews` (`id`, `user_id`, `vendor_id`, `equipment_id`, `comment`, `rating`, `created_at`, `updated_at`) VALUES
(17, 23, 26, 34, 'Lorem Ipsum dummy text', 5, '2023-01-02 00:15:58', '2023-01-02 00:15:58'),
(18, 23, 25, 33, 'the industry\'s standard dummy', 4, '2023-01-02 00:17:06', '2023-01-02 00:17:06'),
(19, 23, 23, 32, 'l be distracted by the readable', 3, '2023-01-02 00:17:38', '2023-01-02 00:17:38'),
(20, 23, 23, 31, 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humor, or randomized', 5, '2023-01-02 00:18:17', '2023-01-02 00:18:17'),
(21, 15, 26, 34, 'looked up one of the more obscure Latin words,', 4, '2023-01-02 00:26:32', '2023-01-02 00:26:32'),
(22, 15, 25, 33, 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested', 3, '2023-01-02 00:28:07', '2023-01-02 00:28:07'),
(23, 15, 23, 32, 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form', 5, '2023-01-02 00:32:07', '2023-01-02 00:32:07');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_sections`
--

CREATE TABLE `equipment_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipment_sections`
--

INSERT INTO `equipment_sections` (`id`, `language_id`, `subtitle`, `title`, `text`, `created_at`, `updated_at`) VALUES
(2, 8, 'Awesome Equipment', 'Our Latest Equipment', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit.', '2021-12-30 02:58:22', '2022-07-27 00:36:07'),
(3, 9, 'معدات رهيبة', 'أحدث المعدات لدينا', 'نتيجة لظروف ما قد تكمن السعاده فيما نتحمله م', '2022-03-06 00:34:51', '2022-08-01 05:52:48');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `language_id`, `question`, `answer`, `serial_number`, `created_at`, `updated_at`) VALUES
(5, 8, 'How Donec Poseur Velit Fauci bus Auctor?', 'It is a long-established fact that reader and distracted this is readable content of a page when looking at its layout the point of using labored Ipsum is that it has a Morea normal the most finish distribution text of the printing and typesetting has been the industry.', 1, '2021-06-26 00:35:52', '2021-12-01 04:03:16'),
(6, 8, 'How Frequently Auctor Quam Vitae Fermtum?', 'It is a long established fact that reader and distracted this is readabale content of a page when looking at its layoi The point of usineg laiorem Ipsum is that it has a morea normal the most finish distribution text of the printing and typesetting has been the industry.', 2, '2021-06-26 00:38:14', '2021-06-26 00:38:14'),
(7, 8, 'How Frequently should I See A trainer?', 'It is a long established fact that reader and distracted this is readabale content of a page when looking at its layoi The point of usineg laiorem Ipsum is that it has a morea normal the most finish distribution text of the printing and typesetting has been the industry.', 3, '2021-06-26 00:39:02', '2021-06-26 00:39:02'),
(10, 9, 'في سحقت هيروشيما البريطاني يتم, غريمه باحتلال الأيديولوجية،', 'حشد الثقيل المنتصر ثم, أسر قررت تم. وغير تصفح الحزب واستمر, مشروط الساحلية هذا ان. أما معركة لبلجيكا، من, الألوف الثقيلة الإنجليزية أسر ٣٠. ٣٠ دار أمام أحدث, أما بحشد الهادي الدولارات ما, هو الحزب الصفحة محاولات قبل. وبحلول الخنادق الأوروبية، ان غير, وليرتفع برلين، انه, انتباه الوزراء البولندي تم تلك.', 1, '2021-08-29 06:57:35', '2021-08-29 06:57:35'),
(14, 9, 'نتيجة لظروف ما قد تكمن السعاده فيما نتحمله', 'هنالك العديد من الأنواع المتوفرة لنصوص لوريم إيبسوم، ولكن الغالبية تم تعديلها بشكل ما عبر إدخال بعض النوادر أو الكلمات العشوائية إلى النص. إن كنت تريد أن تستخدم نص لوريم إيبسوم ما، عليك أن تتحقق أولاً أن ليس هناك أي كلمات أو عبارات محرجة أو غير لائقة مخبأة في هذا النص. بينما تعمل جميع مولّدات نصوص لوريم إيبسوم على الإنترنت على إعادة تكرار مقاطع من نص لوريم إيبسوم نفسه عدة مرات بما تتطلبه الحاجة، يقوم مولّدنا هذا باستخدام كلمات من قاموس يحوي على أكثر من 200 كلمة لا تينية، مضاف إليها مجموعة من الجمل النموذجية، لتكوين نص لوريم إيبسوم ذو شكل منطقي قريب إلى النص الحقيقي. وبالتالي يكون النص الناتح خالي من التكرار، أو أي كلمات أو عبارات غير لائقة أو ما شابه. وهذا ما يجعله أول مولّد نص لوريم إيبسوم حقيقي على الإنترنت.', 2, '2021-12-01 04:04:11', '2021-12-01 04:04:11'),
(15, 9, 'للمصممين نص لوريم ايبسوم بالعربي عربي انجليزي', 'وعند موافقه العميل المبدئيه على التصميم يتم ازالة هذا النص من التصميم ويتم وضع النصوص النهائية المطلوبة للتصميم ويقول البعض ان وضع النصوص التجريبية بالتصميم قد تشغل المشاهد عن وضع الكثير من الملاحظات او الانتقادات للتصميم الاساسي.\r\n\r\nوخلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد. من كتاب \"حول أقاصي الخير والشر\"', 3, '2021-12-01 04:05:29', '2021-12-01 04:05:29');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `language_id`, `icon`, `title`, `text`, `created_at`, `updated_at`) VALUES
(4, 9, 'fas fa-hammer', 'خدمة الدقة', 'الجزء القياسي من لوريم إيبسوم المستخدم منذ القرن الخامس عشر مستنسخ أدناه للمهتمين.', '2021-12-09 06:49:52', '2022-08-01 03:34:41'),
(5, 9, 'fas fa-cannabis', 'السرعة والأمان', 'الجزء القياسي من لوريم إيبسوم المستخدم منذ القرن الخامس عشر مستنسخ أدناه للمهتمين.', '2021-12-09 06:51:31', '2022-08-01 03:35:04'),
(6, 9, 'far fa-money-bill-alt', 'التأثير الاقتصادي', 'الجزء القياسي من لوريم إيبسوم المستخدم منذ القرن الخامس عشر مستنسخ أدناه للمهتمين.', '2021-12-09 06:52:20', '2022-08-01 03:35:17'),
(7, 8, 'fas fa-hammer', 'Precision Service', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.', '2022-03-06 00:31:30', '2022-08-01 03:33:01'),
(8, 8, 'fas fa-cannabis', 'Fast & Safety', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.', '2022-03-06 00:32:18', '2022-08-01 03:33:21'),
(9, 8, 'far fa-money-bill-alt', 'Economically Effect', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.', '2022-03-06 00:33:11', '2022-08-01 03:33:40');

-- --------------------------------------------------------

--
-- Table structure for table `feature_sections`
--

CREATE TABLE `feature_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feature_sections`
--

INSERT INTO `feature_sections` (`id`, `language_id`, `subtitle`, `title`, `text`, `created_at`, `updated_at`) VALUES
(1, 9, 'ميزات رائعة', 'أحدث الميزات المثيرة لدينا', 'الجزء القياسي من لوريم إيبسوم المستخدم منذ القرن الخامس عشر مستنسخ أدناه للمهتمين.', '2021-12-09 05:11:21', '2022-08-01 03:34:15'),
(2, 8, 'Awesome Features', 'Our Latest & Exciting Features', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.', '2022-03-06 00:30:42', '2022-08-01 03:32:22');

-- --------------------------------------------------------

--
-- Table structure for table `footer_contents`
--

CREATE TABLE `footer_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `about_company` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `copyright_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_contents`
--

INSERT INTO `footer_contents` (`id`, `language_id`, `about_company`, `copyright_text`, `created_at`, `updated_at`) VALUES
(3, 8, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'Copyright © 2022,  All Rights Reserved.', '2022-01-02 23:26:08', '2022-03-03 03:38:20'),
(4, 9, 'هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها.', 'حقوق الطبع والنشر © 2022 ، جميع الحقوق محفوظة.', '2022-03-06 01:00:32', '2022-03-06 01:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` bigint UNSIGNED NOT NULL,
  `endpoint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` tinyint NOT NULL,
  `is_default` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `direction`, `is_default`, `created_at`, `updated_at`) VALUES
(8, 'English', 'en', 0, 1, '2021-05-31 05:58:22', '2022-08-01 00:57:24'),
(9, 'عربى', 'ar', 1, 0, '2021-05-31 05:59:16', '2022-08-10 05:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `charge` decimal(8,2) UNSIGNED DEFAULT '0.00',
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `language_id`, `vendor_id`, `name`, `charge`, `serial_number`, `created_at`, `updated_at`) VALUES
(8, 8, NULL, 'Washington, California', '100.00', 1, '2022-01-31 03:06:15', '2022-01-31 03:06:15'),
(9, 8, NULL, 'Washington, D.C.', '150.00', 2, '2022-01-31 03:06:56', '2022-01-31 03:06:56'),
(10, 8, NULL, 'Georgetown, California', '200.00', 3, '2022-01-31 03:07:46', '2022-01-31 03:07:46'),
(11, 9, NULL, 'واشنطن ، كاليفورنيا', '100.00', 1, '2022-03-06 00:48:03', '2022-03-06 00:48:03'),
(12, 9, NULL, 'واشنطن العاصمة.', '150.00', 2, '2022-03-06 00:48:41', '2022-03-06 00:48:41'),
(13, 9, NULL, 'جورج تاون ، كاليفورنيا', '200.00', 3, '2022-03-06 00:49:02', '2022-03-06 00:49:25'),
(19, 8, 7, 'Shah Amanat International Airport', '11.00', 1, '2022-10-19 04:15:19', '2022-10-19 04:15:19'),
(21, 8, 7, 'Perfect Money', '0.00', 1, '2022-10-30 00:33:19', '2022-10-30 00:33:19'),
(22, 8, 22, 'Hazrat Shahjalal International Airpor', '10.00', 1, '2022-12-14 03:46:19', '2022-12-14 03:46:19'),
(23, 8, 22, 'Shah Amanat International Airport', '20.00', 2, '2022-12-14 03:46:57', '2022-12-14 03:46:57'),
(24, 8, 23, 'Jefferson City', '3.00', 1, '2022-12-18 06:05:12', '2023-01-01 04:02:47'),
(25, 8, 23, 'Kansas City', '5.00', 2, '2023-01-01 04:03:11', '2023-01-01 04:03:11'),
(26, 9, 23, 'جيفرسون سيتي', '3.00', 1, '2023-01-01 04:03:40', '2023-01-01 04:03:40'),
(27, 9, 23, 'مدينة كانساس', '5.00', 2, '2023-01-01 04:03:57', '2023-01-01 23:10:14'),
(28, 8, 25, 'Alexander City', '10.00', 1, '2023-01-01 23:11:34', '2023-01-01 23:11:34'),
(29, 9, 25, 'مدينة الكسندر', '10.00', 1, '2023-01-01 23:11:52', '2023-01-01 23:11:52'),
(30, 8, 25, 'Anchorage', '5.00', 2, '2023-01-01 23:12:22', '2023-01-01 23:12:22'),
(31, 9, 25, 'مرسى', '5.00', 2, '2023-01-01 23:12:41', '2023-01-01 23:12:41'),
(32, 8, 26, 'Washington, California', '20.00', 1, '2023-01-01 23:14:00', '2023-01-01 23:14:00'),
(33, 9, 26, 'واشنطن ، كاليفورنيا', '20.00', 1, '2023-01-01 23:14:19', '2023-01-01 23:14:19'),
(34, 8, 26, 'Washington, Georgia', '10.00', 2, '2023-01-01 23:14:43', '2023-01-01 23:14:43'),
(35, 9, 26, 'واشنطن ، جورجيا', '10.00', 2, '2023-01-01 23:14:57', '2023-01-01 23:14:57'),
(36, 8, 27, 'Franklin, Alabama', '5.00', 1, '2023-01-01 23:15:41', '2023-01-01 23:15:41'),
(37, 8, 27, 'Franklin, Arkansas', '15.00', 2, '2023-01-01 23:16:00', '2023-01-01 23:16:00'),
(38, 9, 27, 'فرانكلين ، ألاباما', '15.00', 1, '2023-01-01 23:16:31', '2023-01-01 23:16:31'),
(39, 9, 27, 'فرانكلين ، أركنساس', '15.00', 2, '2023-01-01 23:16:53', '2023-01-01 23:16:53');

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` bigint NOT NULL,
  `mail_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mail_subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mail_body` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mail_templates`
--

INSERT INTO `mail_templates` (`id`, `mail_type`, `mail_subject`, `mail_body`) VALUES
(4, 'verify_email', 'Verify Your Email Address', 0x3c703e4869c2a07b757365726e616d657d2c3c2f703e3c703e5765206e65656420746f2076657269667920796f757220656d61696c2061646472657373206265666f726520796f752063616e2061636365737320796f75722064617368626f6172642e3c2f703e3c703e506c656173652076657269667920796f757220656d61696c2061646472657373206279207669736974696e6720746865206c696e6b2062656c6f773a203c6272202f3e7b766572696669636174696f6e5f6c696e6b7d2e3c2f703e3c703e5468616e6b20796f752e3c6272202f3e7b776562736974655f7469746c657d3c2f703e),
(5, 'reset_password', 'Recover Password of Your Account', 0x3c703e4869207b637573746f6d65725f6e616d657d2c3c2f703e3c703e576520686176652072656365697665642061207265717565737420746f20726573657420796f75722070617373776f72642e20496620796f7520646964206e6f74206d616b652074686520726571756573742c2069676e6f7265207468697320656d61696c2e204f74686572776973652c20796f752063616e20726573657420796f75722070617373776f7264207573696e67207468652062656c6f77206c696e6b2e3c2f703e3c703e7b70617373776f72645f72657365745f6c696e6b7d3c2f703e3c703e5468616e6b732c3c6272202f3e7b776562736974655f7469746c657d3c2f703e),
(9, 'product_order', 'Product Order Has Been Placed', 0x3c703e4869c2a07b637573746f6d65725f6e616d657d2c3c2f703e3c703e596f7572206f7264657220686173206265656e20706c61636564207375636365737366756c6c792e205765206861766520617474616368656420616e20696e766f69636520746f2074686973206d61696c2e3c6272202f3e4f72646572204e6f3a20237b6f726465725f6e756d6265727d2e20546865207472616e73616374696f6e2069642069733a207b7472616e73616374696f6e5f69647d2e3c2f703e3c703e7b6f726465725f6c696e6b7d3c6272202f3e3c2f703e3c703e4265737420726567617264732e3c6272202f3e7b776562736974655f7469746c657d3c2f703e),
(10, 'equipment_booking', 'Confirmation of Equipment Booking', 0x3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e4869207b637573746f6d65725f6e616d657d2c3c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e5468697320656d61696c20697320746f20636f6e6669726d20796f757220626f6f6b696e6720237b626f6f6b696e675f6e756d6265727d206f6e207b626f6f6b696e675f646174657d20666f72c2a0207b65717569706d656e745f6e616d657d2e2054686520626f6f6b696e672073746172742064617465207368616c6c206265206f6e207b73746172745f646174657d20616e642074686520626f6f6b696e6720656e642064617465207368616c6c206265206f6e207b656e645f646174657d2e20546865207472616e73616374696f6e206964206973207b7472616e73616374696f6e5f69647d2e3c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e7b626f6f6b696e675f6c696e6b7d3c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e7b76656e646f725f64657461696c735f6c696e6b7d3c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e5765206861766520616c736f20617474616368656420616e20696e766f69636520746f2074686973206d61696c2e20496620796f75206861766520616e7920696e717569726965732c20706c6561736520646f6e277420686573697461746520746f20636f6e746163742075732e3c6272202f3e3c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e4265737420526567617264732e3c6272202f3e7b776562736974655f7469746c657d3c2f703e),
(11, 'withdraw_approve', 'Confirmation of Withdraw Approve', 0x3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e4869207b76656e646f725f757365726e616d657d2c3c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e5468697320656d61696c20636f6e6669726d73207468617420796f7572207769746864726177616c2072657175657374c2a0207b77697468647261775f69647d20697320617070726f7665642ec2a03c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e596f75722063757272656e742062616c616e6365206973207b63757272656e745f62616c616e63657d2c20776974686472617720616d6f756e74207b77697468647261775f616d6f756e747d2c20636861726765203a207b6368617267657d2c70617961626c6520616d6f756e74207b70617961626c655f616d6f756e747d3c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e7769746864726177206d6574686f64203ac2a07b77697468647261775f6d6574686f647d2e20546865207472616e73616374696f6e206964206973207b7472616e73616374696f6e5f69647d2e3c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e3c6272202f3e3c2f703e3c70207374796c653d22666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313470783b6c696e652d6865696768743a312e38323b636f6c6f723a72676228302c302c30293b666f6e742d7374796c653a6e6f726d616c3b666f6e742d7765696768743a3430303b746578742d616c69676e3a6c6566743b223e4265737420526567617264732e3c6272202f3e7b776562736974655f7469746c657d3c2f703e),
(13, 'withdraw_rejected', 'Withdraw Request Rejected', 0x3c703e4869207b76656e646f725f757365726e616d657d2c3c2f703e3c703e5468697320656d61696c20636f6e6669726d73207468617420796f7572207769746864726177616c2072657175657374c2a0207b77697468647261775f69647d2069732072656a656374656420616e64207468652062616c616e636520616464656420746f20796f7572206163636f756e742ec2a03c2f703e3c703e596f75722063757272656e742062616c616e6365206973207b63757272656e745f62616c616e63657d3c2f703e3c703e3c6272202f3e3c2f703e3c703e4265737420526567617264732e3c6272202f3e7b776562736974655f7469746c657d3c2f703e),
(14, 'balance_add', 'Balance Add', 0x3c703e4869207b757365726e616d657d3c2f703e3c703e7b616d6f756e747d20616464656420746f20796f7572206163636f756e742e3c2f703e3c703e596f75722063757272656e742062616c616e6365206973207b63757272656e745f62616c616e63657d2ec2a03c2f703e3c703e546865207472616e73616374696f6e206964206973207b7472616e73616374696f6e5f69647d2e3c6272202f3e3c2f703e3c703e3c6272202f3e3c2f703e3c703e4265737420526567617264732e3c6272202f3e7b776562736974655f7469746c657d3c6272202f3e3c2f703e),
(15, 'balance_subtract', 'Balance Subtract', 0x3c703e4869207b757365726e616d657d3c2f703e3c703e7b616d6f756e747d2073756274726163742066726f6d20796f7572206163636f756e742e3c2f703e3c703e596f75722063757272656e742062616c616e6365206973207b63757272656e745f62616c616e63657d2e3c2f703e3c703e546865207472616e73616374696f6e206964206973207b7472616e73616374696f6e5f69647d2e3c6272202f3e3c2f703e3c703e3c6272202f3e3c2f703e3c703e4265737420526567617264732e3c6272202f3e7b776562736974655f7469746c657d3c2f703e),
(16, 'security_deposit_refund', 'Security Deposit Refund', 0x3c703e3c7370616e207374796c653d22636f6c6f723a7267622833342c33342c3334293b666f6e742d66616d696c793a417269616c2c2048656c7665746963612c2073616e732d73657269663b666f6e742d73697a653a736d616c6c3b223e4869207b757365726e616d657d2c3c2f7370616e3e3c6272202f3e3c6272202f3e3c7370616e207374796c653d22636f6c6f723a7267622833342c33342c3334293b666f6e742d66616d696c793a417269616c2c2048656c7665746963612c2073616e732d73657269663b666f6e742d73697a653a736d616c6c3b223e5468697320656d61696c20697320746f206e6f7469667920746865207365637572697479206465706f73697420726566756e6420737461747573206f6620796f75722065717569706d656e7420626f6f6b696e672e3c2f7370616e3e3c2f703e3c70207374796c653d22636f6c6f723a7267622833342c33342c3334293b666f6e742d66616d696c793a417269616c2c2048656c7665746963612c2073616e732d73657269663b666f6e742d73697a653a736d616c6c3b223e45717569706d656e74204e616d653a207b65717569706d656e745f6e616d657d3c2f703e3c70207374796c653d22636f6c6f723a7267622833342c33342c3334293b666f6e742d66616d696c793a417269616c2c2048656c7665746963612c2073616e732d73657269663b666f6e742d73697a653a736d616c6c3b223e426f6f6b696e672049643a207b626f6f6b696e675f6e756d6265727d3c2f703e3c70207374796c653d22636f6c6f723a7267622833342c33342c3334293b666f6e742d66616d696c793a417269616c2c2048656c7665746963612c2073616e732d73657269663b666f6e742d73697a653a736d616c6c3b223e5365637572697479204465706f73697420526566756e6420547970653a207b726566756e645f747970657d3c2f703e3c70207374796c653d22636f6c6f723a7267622833342c33342c3334293b666f6e742d66616d696c793a417269616c2c2048656c7665746963612c2073616e732d73657269663b666f6e742d73697a653a736d616c6c3b223e54686520726566756e6420616d6f756e742069733a207b616d6f756e747d2e3c2f703e3c70207374796c653d22636f6c6f723a7267622833342c33342c3334293b666f6e742d66616d696c793a417269616c2c2048656c7665746963612c2073616e732d73657269663b666f6e742d73697a653a736d616c6c3b223e41637475616c205365637572697479204465706f73697420416d6f756e74203a207b61637475616c5f73656375726974795f6465706f7369745f616d6f756e747d3c6272202f3e3c2f703e);

-- --------------------------------------------------------

--
-- Table structure for table `menu_builders`
--

CREATE TABLE `menu_builders` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `menus` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_builders`
--

INSERT INTO `menu_builders` (`id`, `language_id`, `menus`, `created_at`, `updated_at`) VALUES
(1, 9, '[{\"text\":\"الصفحة الرئيسية\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"ادوات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"equipment\"},{\"text\":\"الباعة\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"vendor\"},{\"text\":\"محل\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"custom\",\"children\":[{\"text\":\"منتجات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"products\"},{\"text\":\"عربة التسوق\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"cart\"}]},{\"text\":\"الصفحات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"custom\",\"children\":[{\"text\":\"معلومات عنا\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"معلومات-عنا\"},{\"text\":\"البنود و الظروف\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"البنود-و-الظروف\"}]},{\"text\":\"مقالات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"blog\"},{\"text\":\"التعليمات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"faq\"},{\"text\":\"اتصل\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"contact\"}]', '2021-11-18 04:50:31', '2022-12-22 03:38:06'),
(2, 8, '[{\"text\":\"Home\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"Equipment\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"equipment\"},{\"text\":\"Vendors\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"vendor\"},{\"text\":\"Shop\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"custom\",\"children\":[{\"text\":\"Products\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"products\"},{\"text\":\"Cart\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"cart\"}]},{\"text\":\"Pages\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"custom\",\"children\":[{\"text\":\"About Us\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"about-us\"},{\"text\":\"Terms & Conditions\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"terms-&-conditions\"}]},{\"text\":\"Blog\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"blog\"},{\"text\":\"FAQ\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"faq\"},{\"text\":\"Contact\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"contact\"}]', '2021-12-01 05:32:09', '2023-01-02 01:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2022_10_19_061720_create_commissions_table', 1),
(2, '2022_10_19_063819_create_earnings_table', 2),
(3, '2022_10_19_064056_create_transcations_table', 2),
(4, '2022_10_20_044719_create_withdraw_payment_methods_table', 3),
(5, '2022_10_20_044915_create_withdraw_method_inputs_table', 3),
(6, '2022_10_20_045157_create_withdraw_method_options_table', 3),
(7, '2022_10_20_045258_create_withdraws_table', 3),
(8, '2022_10_23_051050_create_vendor_reviews_table', 4),
(11, '2022_12_13_055404_create_vendor_infos_table', 5),
(12, '2023_03_08_040425_add_security_deposit_amount_column_to_equipments_table', 5),
(13, '2023_03_08_044930_add_column_to_equipment_bookings_table', 6),
(15, '2023_03_08_061957_create_security_deposit_refunds_table', 7),
(17, '2023_06_06_063356_create_equipment_locations_table', 8),
(18, '2023_06_13_063302_add_commission_percentage_column_to_equipment_bookings_table', 9),
(19, '2023_06_13_105405_add_security_deposit_column_to_transcations_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `offline_gateways`
--

CREATE TABLE `offline_gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 -> gateway is deactive, 1 -> gateway is active.',
  `has_attachment` tinyint(1) NOT NULL COMMENT '0 -> do not need attachment, 1 -> need attachment.',
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offline_gateways`
--

INSERT INTO `offline_gateways` (`id`, `name`, `short_description`, `instructions`, `status`, `has_attachment`, `serial_number`, `created_at`, `updated_at`) VALUES
(2, 'Citibank', 'A pioneer of both the credit card industry and automated teller machines, Citibank – formerly the City Bank of New York.', '<p><span style=\"color:rgb(51,51,51);font-family:\'proxima-nova\', sans-serif;font-size:16px;\">A pioneer of both the credit card industry and automated teller machines, </span><a href=\"https://smartasset.com/checking-account/Citibank-banking-review\">Citibank</a><span style=\"color:rgb(51,51,51);font-family:\'proxima-nova\', sans-serif;font-size:16px;\"> –\r\n formerly the City Bank of New York – was regarded as an East Coast \r\nequivalent to Wells Fargo during the 19th century.</span><br></p>', 1, 0, 1, '2021-07-16 22:41:59', '2022-01-23 00:11:01'),
(3, 'Bank of America', 'Bank of America has 4,265 branches in the country, only about 700 fewer than Chase. It started as a small institution serving immigrants in San Francisco.', '<p><span style=\"color:rgb(51,51,51);font-family:\'proxima-nova\', sans-serif;font-size:16px;\">With $1.8 trillion in consolidated assets, </span><a href=\"https://smartasset.com/checking-account/bank-of-america-review\">Bank of America</a><span style=\"color:rgb(51,51,51);font-family:\'proxima-nova\', sans-serif;font-size:16px;\"> is\r\n second on the list. Its headquarters in Charlotte, North Carolina, \r\nsinglehandedly makes that city one of the biggest financial centers in \r\nthe country.</span><br></p>', 1, 1, 2, '2021-07-16 22:43:19', '2021-07-16 22:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `online_gateways`
--

CREATE TABLE `online_gateways` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `information` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `online_gateways`
--

INSERT INTO `online_gateways` (`id`, `name`, `keyword`, `information`, `status`) VALUES
(1, 'PayPal', 'paypal', '{\"sandbox_status\":\"1\",\"client_id\":\"AVYKFEw63FtDt9aeYOe9biyifNI56s2Hc2F1Us11hWoY5GMuegipJRQBfWLiIKNbwQ5tmqKSrQTU3zB3\",\"client_secret\":\"EJY0qOKliVg7wKsR3uPN7lngr9rL1N7q4WV0FulT1h4Fw3_e5Itv1mxSdbtSUwAaQoXQFgq-RLlk_sQu\"}', 1),
(2, 'Instamojo', 'instamojo', '{\"sandbox_status\":\"1\",\"key\":\"test_172371aa837ae5cad6047dc3052\",\"token\":\"test_4ac5a785e25fc596b67dbc5c267\"}', 1),
(3, 'Paystack', 'paystack', '{\"key\":\"sk_test_4ac9f2c43514e3cc08ab68f922201549ebda1bfd\"}', 1),
(4, 'Flutterwave', 'flutterwave', '{\"public_key\":\"FLWPUBK_TEST-93972d50b7b24582a2050de2803799c0-X\",\"secret_key\":\"FLWSECK_TEST-3c9d39d4b16e9011bc4b9893f882f71e-X\"}', 1),
(5, 'Razorpay', 'razorpay', '{\"key\":\"rzp_test_fV9dM9URYbqjm7\",\"secret\":\"nickxZ1du2ojPYVVRTDif2Xr\"}', 1),
(6, 'MercadoPago', 'mercadopago', '{\"sandbox_status\":\"1\",\"token\":\"TEST-8543014337146975-070812-b128a1d63a4365e193bd3b3567780ffb-1148264951\"}', 1),
(7, 'Mollie', 'mollie', '{\"key\":\"test_83T8tFQAhjDsthGRK8mhVfnBt68wwB\"}', 1),
(8, 'Stripe', 'stripe', '{\"key\":\"pk_test_UnU1Coi1p5qFGwtpjZMRMgJM\",\"secret\":\"sk_test_QQcg3vGsKRPlW6T3dXcNJsor\"}', 1),
(9, 'Paytm', 'paytm', '{\"environment\":\"local\",\"merchant_key\":\"LhNGUUKE9xCQ9xY8\",\"merchant_mid\":\"tkogux49985047638244\",\"merchant_website\":\"WEBSTAGING\",\"industry_type\":\"Retail\"}', 1),
(20, 'Authorize.net', 'authorize.net', '{\"login_id\":\"3Ca5hYQ6h\",\"transaction_key\":\"8bt8Kr5gPZ3ZE23C\",\"public_key\":\"7m38JBnNjStNFq58BA6Wrr852ahtT533cGKavWwu6Fge28RDc5wC7wTL8Vsb35B3\",\"sandbox_check\":\"1\",\"text\":\"Pay via your Authorize.net account.\"}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `status` tinyint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `status`, `created_at`, `updated_at`) VALUES
(14, 1, '2021-10-18 02:33:45', '2021-10-18 02:33:45'),
(19, 1, '2022-07-30 05:33:25', '2022-07-30 05:33:25');

-- --------------------------------------------------------

--
-- Table structure for table `page_contents`
--

CREATE TABLE `page_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `page_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` blob NOT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_contents`
--

INSERT INTO `page_contents` (`id`, `language_id`, `page_id`, `title`, `slug`, `content`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(30, 9, 14, 'البنود و الظروف', 'البنود-و-الظروف', 0x3c70207374796c653d22746578742d616c69676e3a72696768743b223e3c666f6e7420636f6c6f723d2223363636363636223e3c7370616e207374796c653d22666f6e742d73697a653a313670783b223ed985d98620d986d8a7d8add98ad8a920d8a3d8aed8b1d98920d88c20d986d8b4d8acd8a820d8a8d8b3d8aed8b720d8b5d8a7d984d8ad20d988d986d983d8b1d98720d8a7d984d8b1d8acd8a7d98420d8a7d984d8b0d98ad98620d8aed8afd8b9d987d98520d8b3d8add8b120d985d8aad8b9d8a920d8a7d984d984d8add8b8d8a920d988d8a5d8add8a8d8a7d8b7d987d98520d88c20d988d8a3d8b9d985d8aad987d98520d8a7d984d8b1d8bad8a8d8a920d88c20d984d8afd8b1d8acd8a920d8a3d986d987d98520d984d8a720d98ad8b3d8aad8b7d98ad8b9d988d98620d8a7d984d8aad986d8a8d8a420d8a8d8a7d984d8a3d984d98520d988d8a7d984d985d8aad8a7d8b9d8a820d8a7d984d8aad98a20d8b3d8aad8aad8b1d8aad8a820d8b9d984d98920d8b0d984d98320d89b20d988d8a7d984d984d988d98520d8a7d984d985d8aad8b3d8a7d988d98a20d98ad982d8b920d8b9d984d98920d985d98620d98ad981d8b4d98420d981d98a20d8a3d8afd8a7d8a120d988d8a7d8acd8a8d98720d8a8d8b3d8a8d8a820d8b6d8b9d98120d8a7d984d8a5d8b1d8a7d8afd8a920d88c20d988d987d98820d986d981d8b320d8a7d984d982d988d98420d8a8d8a7d984d8a7d986d983d985d8a7d8b420d985d98620d8a7d984d983d8af20d988d8a7d984d8a3d984d9852e20d987d8b0d98720d8a7d984d8add8a7d984d8a7d8aa20d8a8d8b3d98ad8b7d8a920d984d984d8bad8a7d98ad8a920d988d8b3d987d984d8a920d8a7d984d8aad985d98ad98ad8b22e20d981d98a20d8b3d8a7d8b9d8a920d985d8acd8a7d986d98ad8a920d88c20d8b9d986d8afd985d8a720d8aad983d988d98620d982d8afd8b1d8aad986d8a720d8b9d984d98920d8a7d984d8a7d8aed8aad98ad8a7d8b120d8bad98ad8b120d985d982d98ad8afd8a920d988d8b9d986d8afd985d8a720d984d8a720d8b4d98ad8a120d98ad985d986d8b9d986d8a720d985d98620d8a7d984d982d98ad8a7d98520d8a8d985d8a720d986d981d8b6d984d98720d88c20d98ad8acd8a820d8a7d984d8aad8b1d8add98ad8a820d8a8d983d98420d985d8aad8b9d8a920d988d8aad8acd986d8a820d983d98420d8a3d984d9852e20d988d984d983d98620d981d98a20d8b8d8b1d988d98120d985d8b9d98ad986d8a920d988d8a8d8b3d8a8d8a820d8a7d8afd8b9d8a7d8a1d8a7d8aa20d8a7d984d988d8a7d8acd8a820d8a3d98820d8a7d984d8aad8b2d8a7d985d8a7d8aa20d8a7d984d8b9d985d98420d88c20d8b3d98ad8add8afd8ab20d981d98a20d983d8abd98ad8b120d985d98620d8a7d984d8a3d8add98ad8a7d98620d8a3d986d98720d98ad8acd8a820d986d8a8d8b020d8a7d984d985d984d8b0d8a7d8aa20d988d982d8a8d988d98420d8a7d984d8a5d8b2d8b9d8a7d8ac2e20d984d8b0d984d98320d98ad8aad985d8b3d98320d8a7d984d8b1d8acd98420d8a7d984d8add983d98ad98520d8afd8a7d8a6d985d98bd8a720d981d98a20d987d8b0d98720d8a7d984d8a3d985d988d8b120d8a8d985d8a8d8afd8a320d8a7d984d8a7d8aed8aad98ad8a7d8b120d987d8b0d8a73a20d981d987d98820d98ad8b1d981d8b620d8a7d984d985d984d8b0d8a7d8aa20d984d8aad8a3d985d98ad98620d985d984d8b0d8a7d8aa20d8a3d8b9d8b8d98520d8a3d8aed8b1d98920d88c20d988d8a5d984d8a720d981d8a5d986d98720d98ad8aad8add985d98420d8a7d984d8a2d984d8a7d98520d984d8aad8acd986d8a820d8a7d984d8a2d984d8a7d98520d8a7d984d8b3d98ad8a6d8a92e3c2f7370616e3e3c2f666f6e743e3c6272202f3e3c2f703e, NULL, NULL, '2021-10-18 02:33:45', '2022-03-06 05:12:50'),
(31, 8, 14, 'Terms & Conditions', 'terms-&-conditions', 0x3c68343e57656c636f6d6520746f2072656e746571756970213c2f68343e0d0a0d0a3c703e5468657365207465726d7320616e6420636f6e646974696f6e73206f75746c696e65207468652072756c657320616e6420726567756c6174696f6e7320666f722074686520757365206f662072656e746571756970277320576562736974652c206c6f63617465642061742068747470733a2f2f636f646563616e796f6e2e6b7265617469766465762e636f6d2f72656e7465717569702e3c2f703e0d0a0d0a3c703e427920616363657373696e672074686973207765627369746520776520617373756d6520796f7520616363657074207468657365207465726d7320616e6420636f6e646974696f6e732e20446f206e6f7420636f6e74696e756520746f207573652072656e74657175697020696620796f7520646f206e6f7420616772656520746f2074616b6520616c6c206f6620746865207465726d7320616e6420636f6e646974696f6e7320737461746564206f6e207468697320706167652e3c2f703e0d0a0d0a3c703e54686520666f6c6c6f77696e67207465726d696e6f6c6f6779206170706c69657320746f207468657365205465726d7320616e6420436f6e646974696f6e732c20507269766163792053746174656d656e7420616e6420446973636c61696d6572204e6f7469636520616e6420616c6c2041677265656d656e74733a2022436c69656e74222c2022596f752220616e642022596f7572222072656665727320746f20796f752c2074686520706572736f6e206c6f67206f6e2074686973207765627369746520616e6420636f6d706c69616e7420746f2074686520436f6d70616e79e2809973207465726d7320616e6420636f6e646974696f6e732e202254686520436f6d70616e79222c20224f757273656c766573222c20225765222c20224f75722220616e6420225573222c2072656665727320746f206f757220436f6d70616e792e20225061727479222c202250617274696573222c206f7220225573222c2072656665727320746f20626f74682074686520436c69656e7420616e64206f757273656c7665732e20416c6c207465726d7320726566657220746f20746865206f666665722c20616363657074616e636520616e6420636f6e73696465726174696f6e206f66207061796d656e74206e656365737361727920746f20756e64657274616b65207468652070726f63657373206f66206f757220617373697374616e636520746f2074686520436c69656e7420696e20746865206d6f737420617070726f707269617465206d616e6e657220666f7220746865206578707265737320707572706f7365206f66206d656574696e672074686520436c69656e74e2809973206e6565647320696e2072657370656374206f662070726f766973696f6e206f662074686520436f6d70616e79e2809973207374617465642073657276696365732c20696e206163636f7264616e6365207769746820616e64207375626a65637420746f2c207072657661696c696e67206c6177206f66204e65746865726c616e64732e20416e7920757365206f66207468652061626f7665207465726d696e6f6c6f6779206f72206f7468657220776f72647320696e207468652073696e67756c61722c20706c7572616c2c206361706974616c697a6174696f6e20616e642f6f722068652f736865206f7220746865792c206172652074616b656e20617320696e7465726368616e676561626c6520616e64207468657265666f726520617320726566657272696e6720746f2073616d652e3c2f703e0d0a0d0a3c68333e3c7374726f6e673e436f6f6b6965733c2f7374726f6e673e3c2f68333e0d0a0d0a3c703e576520656d706c6f792074686520757365206f6620636f6f6b6965732e20427920616363657373696e672072656e7465717569702c20796f752061677265656420746f2075736520636f6f6b69657320696e2061677265656d656e742077697468207468652072656e7465717569702773205072697661637920506f6c6963792e203c2f703e0d0a0d0a3c703e4d6f737420696e7465726163746976652077656273697465732075736520636f6f6b69657320746f206c6574207573207265747269657665207468652075736572e28099732064657461696c7320666f7220656163682076697369742e20436f6f6b696573206172652075736564206279206f7572207765627369746520746f20656e61626c65207468652066756e6374696f6e616c697479206f66206365727461696e20617265617320746f206d616b652069742065617369657220666f722070656f706c65207669736974696e67206f757220776562736974652e20536f6d65206f66206f757220616666696c696174652f6164766572746973696e6720706172746e657273206d617920616c736f2075736520636f6f6b6965732e3c2f703e0d0a0d0a3c68333e3c7374726f6e673e4c6963656e73653c2f7374726f6e673e3c2f68333e0d0a0d0a3c703e556e6c657373206f7468657277697365207374617465642c2072656e74657175697020616e642f6f7220697473206c6963656e736f7273206f776e2074686520696e74656c6c65637475616c2070726f70657274792072696768747320666f7220616c6c206d6174657269616c206f6e2072656e7465717569702e20416c6c20696e74656c6c65637475616c2070726f706572747920726967687473206172652072657365727665642e20596f75206d61792061636365737320746869732066726f6d2072656e74657175697020666f7220796f7572206f776e20706572736f6e616c20757365207375626a656374656420746f207265737472696374696f6e732073657420696e207468657365207465726d7320616e6420636f6e646974696f6e732e3c2f703e0d0a0d0a3c703e596f75206d757374206e6f743a3c2f703e0d0a3c756c3e0d0a202020203c6c693e52657075626c697368206d6174657269616c2066726f6d2072656e7465717569703c2f6c693e0d0a202020203c6c693e53656c6c2c2072656e74206f72207375622d6c6963656e7365206d6174657269616c2066726f6d2072656e7465717569703c2f6c693e0d0a202020203c6c693e526570726f647563652c206475706c6963617465206f7220636f7079206d6174657269616c2066726f6d2072656e7465717569703c2f6c693e0d0a202020203c6c693e52656469737472696275746520636f6e74656e742066726f6d2072656e7465717569703c2f6c693e0d0a3c2f756c3e0d0a0d0a3c703e546869732041677265656d656e74207368616c6c20626567696e206f6e20746865206461746520686572656f662e204f7572205465726d7320616e6420436f6e646974696f6e73207765726520637265617465642077697468207468652068656c70206f6620746865203c6120687265663d2268747470733a2f2f7777772e7465726d73616e64636f6e646974696f6e7367656e657261746f722e636f6d2f223e46726565205465726d7320616e6420436f6e646974696f6e732047656e657261746f723c2f613e2e3c2f703e0d0a0d0a3c703e5061727473206f6620746869732077656273697465206f6666657220616e206f70706f7274756e69747920666f7220757365727320746f20706f737420616e642065786368616e6765206f70696e696f6e7320616e6420696e666f726d6174696f6e20696e206365727461696e206172656173206f662074686520776562736974652e2072656e74657175697020646f6573206e6f742066696c7465722c20656469742c207075626c697368206f722072657669657720436f6d6d656e7473207072696f7220746f2074686569722070726573656e6365206f6e2074686520776562736974652e20436f6d6d656e747320646f206e6f74207265666c6563742074686520766965777320616e64206f70696e696f6e73206f662072656e7465717569702c697473206167656e747320616e642f6f7220616666696c69617465732e20436f6d6d656e7473207265666c6563742074686520766965777320616e64206f70696e696f6e73206f662074686520706572736f6e2077686f20706f737420746865697220766965777320616e64206f70696e696f6e732e20546f2074686520657874656e74207065726d6974746564206279206170706c696361626c65206c6177732c2072656e746571756970207368616c6c206e6f74206265206c6961626c6520666f722074686520436f6d6d656e7473206f7220666f7220616e79206c696162696c6974792c2064616d61676573206f7220657870656e7365732063617573656420616e642f6f72207375666665726564206173206120726573756c74206f6620616e7920757365206f6620616e642f6f7220706f7374696e67206f6620616e642f6f7220617070656172616e6365206f662074686520436f6d6d656e7473206f6e207468697320776562736974652e3c2f703e0d0a0d0a3c703e72656e7465717569702072657365727665732074686520726967687420746f206d6f6e69746f7220616c6c20436f6d6d656e747320616e6420746f2072656d6f766520616e7920436f6d6d656e74732077686963682063616e20626520636f6e7369646572656420696e617070726f7072696174652c206f6666656e73697665206f722063617573657320627265616368206f66207468657365205465726d7320616e6420436f6e646974696f6e732e3c2f703e0d0a0d0a3c703e596f752077617272616e7420616e6420726570726573656e7420746861743a3c2f703e0d0a0d0a3c756c3e0d0a202020203c6c693e596f752061726520656e7469746c656420746f20706f73742074686520436f6d6d656e7473206f6e206f7572207765627369746520616e64206861766520616c6c206e6563657373617279206c6963656e73657320616e6420636f6e73656e747320746f20646f20736f3b3c2f6c693e0d0a202020203c6c693e54686520436f6d6d656e747320646f206e6f7420696e7661646520616e7920696e74656c6c65637475616c2070726f70657274792072696768742c20696e636c7564696e6720776974686f7574206c696d69746174696f6e20636f707972696768742c20706174656e74206f722074726164656d61726b206f6620616e792074686972642070617274793b3c2f6c693e0d0a202020203c6c693e54686520436f6d6d656e747320646f206e6f7420636f6e7461696e20616e7920646566616d61746f72792c206c6962656c6f75732c206f6666656e736976652c20696e646563656e74206f72206f746865727769736520756e6c617766756c206d6174657269616c20776869636820697320616e20696e766173696f6e206f6620707269766163793c2f6c693e0d0a202020203c6c693e54686520436f6d6d656e74732077696c6c206e6f74206265207573656420746f20736f6c69636974206f722070726f6d6f746520627573696e657373206f7220637573746f6d206f722070726573656e7420636f6d6d65726369616c2061637469766974696573206f7220756e6c617766756c2061637469766974792e3c2f6c693e0d0a3c2f756c3e0d0a0d0a3c703e596f7520686572656279206772616e742072656e7465717569702061206e6f6e2d6578636c7573697665206c6963656e736520746f207573652c20726570726f647563652c206564697420616e6420617574686f72697a65206f746865727320746f207573652c20726570726f6475636520616e64206564697420616e79206f6620796f757220436f6d6d656e747320696e20616e7920616e6420616c6c20666f726d732c20666f726d617473206f72206d656469612e3c2f703e0d0a0d0a3c68333e3c7374726f6e673e48797065726c696e6b696e6720746f206f757220436f6e74656e743c2f7374726f6e673e3c2f68333e0d0a0d0a3c703e54686520666f6c6c6f77696e67206f7267616e697a6174696f6e73206d6179206c696e6b20746f206f7572205765627369746520776974686f7574207072696f72207772697474656e20617070726f76616c3a3c2f703e0d0a0d0a3c756c3e0d0a202020203c6c693e476f7665726e6d656e74206167656e636965733b3c2f6c693e0d0a202020203c6c693e53656172636820656e67696e65733b3c2f6c693e0d0a202020203c6c693e4e657773206f7267616e697a6174696f6e733b3c2f6c693e0d0a202020203c6c693e4f6e6c696e65206469726563746f7279206469737472696275746f7273206d6179206c696e6b20746f206f7572205765627369746520696e207468652073616d65206d616e6e657220617320746865792068797065726c696e6b20746f20746865205765627369746573206f66206f74686572206c697374656420627573696e65737365733b20616e643c2f6c693e0d0a202020203c6c693e53797374656d2077696465204163637265646974656420427573696e65737365732065786365707420736f6c69636974696e67206e6f6e2d70726f666974206f7267616e697a6174696f6e732c20636861726974792073686f7070696e67206d616c6c732c20616e6420636861726974792066756e6472616973696e672067726f757073207768696368206d6179206e6f742068797065726c696e6b20746f206f75722057656220736974652e3c2f6c693e0d0a3c2f756c3e0d0a0d0a3c703e5468657365206f7267616e697a6174696f6e73206d6179206c696e6b20746f206f757220686f6d6520706167652c20746f207075626c69636174696f6e73206f7220746f206f74686572205765627369746520696e666f726d6174696f6e20736f206c6f6e6720617320746865206c696e6b3a20286129206973206e6f7420696e20616e7920776179206465636570746976653b2028622920646f6573206e6f742066616c73656c7920696d706c792073706f6e736f72736869702c20656e646f7273656d656e74206f7220617070726f76616c206f6620746865206c696e6b696e6720706172747920616e64206974732070726f647563747320616e642f6f722073657276696365733b20616e642028632920666974732077697468696e2074686520636f6e74657874206f6620746865206c696e6b696e67207061727479e280997320736974652e3c2f703e0d0a0d0a3c703e5765206d617920636f6e736964657220616e6420617070726f7665206f74686572206c696e6b2072657175657374732066726f6d2074686520666f6c6c6f77696e67207479706573206f66206f7267616e697a6174696f6e733a3c2f703e0d0a0d0a3c756c3e0d0a202020203c6c693e636f6d6d6f6e6c792d6b6e6f776e20636f6e73756d657220616e642f6f7220627573696e65737320696e666f726d6174696f6e20736f75726365733b3c2f6c693e0d0a202020203c6c693e646f742e636f6d20636f6d6d756e6974792073697465733b3c2f6c693e0d0a202020203c6c693e6173736f63696174696f6e73206f72206f746865722067726f75707320726570726573656e74696e67206368617269746965733b3c2f6c693e0d0a202020203c6c693e6f6e6c696e65206469726563746f7279206469737472696275746f72733b3c2f6c693e0d0a202020203c6c693e696e7465726e657420706f7274616c733b3c2f6c693e0d0a202020203c6c693e6163636f756e74696e672c206c617720616e6420636f6e73756c74696e67206669726d733b20616e643c2f6c693e0d0a202020203c6c693e656475636174696f6e616c20696e737469747574696f6e7320616e64207472616465206173736f63696174696f6e732e3c2f6c693e0d0a3c2f756c3e0d0a0d0a3c703e57652077696c6c20617070726f7665206c696e6b2072657175657374732066726f6d207468657365206f7267616e697a6174696f6e732069662077652064656369646520746861743a2028612920746865206c696e6b20776f756c64206e6f74206d616b65207573206c6f6f6b20756e6661766f7261626c7920746f206f757273656c766573206f7220746f206f7572206163637265646974656420627573696e65737365733b2028622920746865206f7267616e697a6174696f6e20646f6573206e6f74206861766520616e79206e65676174697665207265636f72647320776974682075733b20286329207468652062656e6566697420746f2075732066726f6d20746865207669736962696c697479206f66207468652068797065726c696e6b20636f6d70656e73617465732074686520616273656e6365206f662072656e7465717569703b20616e642028642920746865206c696e6b20697320696e2074686520636f6e74657874206f662067656e6572616c207265736f7572636520696e666f726d6174696f6e2e3c2f703e0d0a0d0a3c703e5468657365206f7267616e697a6174696f6e73206d6179206c696e6b20746f206f757220686f6d65207061676520736f206c6f6e6720617320746865206c696e6b3a20286129206973206e6f7420696e20616e7920776179206465636570746976653b2028622920646f6573206e6f742066616c73656c7920696d706c792073706f6e736f72736869702c20656e646f7273656d656e74206f7220617070726f76616c206f6620746865206c696e6b696e6720706172747920616e64206974732070726f6475637473206f722073657276696365733b20616e642028632920666974732077697468696e2074686520636f6e74657874206f6620746865206c696e6b696e67207061727479e280997320736974652e3c2f703e0d0a0d0a3c703e496620796f7520617265206f6e65206f6620746865206f7267616e697a6174696f6e73206c697374656420696e2070617261677261706820322061626f766520616e642061726520696e746572657374656420696e206c696e6b696e6720746f206f757220776562736974652c20796f75206d75737420696e666f726d2075732062792073656e64696e6720616e20652d6d61696c20746f2072656e7465717569702e20506c6561736520696e636c75646520796f7572206e616d652c20796f7572206f7267616e697a6174696f6e206e616d652c20636f6e7461637420696e666f726d6174696f6e2061732077656c6c206173207468652055524c206f6620796f757220736974652c2061206c697374206f6620616e792055524c732066726f6d20776869636820796f7520696e74656e6420746f206c696e6b20746f206f757220576562736974652c20616e642061206c697374206f66207468652055524c73206f6e206f7572207369746520746f20776869636820796f7520776f756c64206c696b6520746f206c696e6b2e205761697420322d33207765656b7320666f72206120726573706f6e73652e3c2f703e0d0a0d0a3c703e417070726f766564206f7267616e697a6174696f6e73206d61792068797065726c696e6b20746f206f7572205765627369746520617320666f6c6c6f77733a3c2f703e0d0a0d0a3c756c3e0d0a202020203c6c693e427920757365206f66206f757220636f72706f72617465206e616d653b206f723c2f6c693e0d0a202020203c6c693e427920757365206f662074686520756e69666f726d207265736f75726365206c6f6361746f72206265696e67206c696e6b656420746f3b206f723c2f6c693e0d0a202020203c6c693e427920757365206f6620616e79206f74686572206465736372697074696f6e206f66206f75722057656273697465206265696e67206c696e6b656420746f2074686174206d616b65732073656e73652077697468696e2074686520636f6e7465787420616e6420666f726d6174206f6620636f6e74656e74206f6e20746865206c696e6b696e67207061727479e280997320736974652e3c2f6c693e0d0a3c2f756c3e0d0a0d0a3c703e4e6f20757365206f662072656e7465717569702773206c6f676f206f72206f7468657220617274776f726b2077696c6c20626520616c6c6f77656420666f72206c696e6b696e6720616273656e7420612074726164656d61726b206c6963656e73652061677265656d656e742e3c2f703e0d0a0d0a3c68333e3c7374726f6e673e694672616d65733c2f7374726f6e673e3c2f68333e0d0a0d0a3c703e576974686f7574207072696f7220617070726f76616c20616e64207772697474656e207065726d697373696f6e2c20796f75206d6179206e6f7420637265617465206672616d65732061726f756e64206f7572205765627061676573207468617420616c74657220696e20616e7920776179207468652076697375616c2070726573656e746174696f6e206f7220617070656172616e6365206f66206f757220576562736974652e3c2f703e0d0a0d0a3c68333e3c7374726f6e673e436f6e74656e74204c696162696c6974793c2f7374726f6e673e3c2f68333e0d0a0d0a3c703e5765207368616c6c206e6f7420626520686f6c6420726573706f6e7369626c6520666f7220616e7920636f6e74656e7420746861742061707065617273206f6e20796f757220576562736974652e20596f7520616772656520746f2070726f7465637420616e6420646566656e6420757320616761696e737420616c6c20636c61696d73207468617420697320726973696e67206f6e20796f757220576562736974652e204e6f206c696e6b2873292073686f756c6420617070656172206f6e20616e7920576562736974652074686174206d617920626520696e746572707265746564206173206c6962656c6f75732c206f627363656e65206f72206372696d696e616c2c206f7220776869636820696e6672696e6765732c206f74686572776973652076696f6c617465732c206f72206164766f63617465732074686520696e6672696e67656d656e74206f72206f746865722076696f6c6174696f6e206f662c20616e79207468697264207061727479207269676874732e3c2f703e0d0a0d0a3c68333e3c7374726f6e673e596f757220507269766163793c2f7374726f6e673e3c2f68333e0d0a0d0a3c703e506c656173652072656164205072697661637920506f6c6963793c2f703e0d0a0d0a3c68333e3c7374726f6e673e5265736572766174696f6e206f66205269676874733c2f7374726f6e673e3c2f68333e0d0a0d0a3c703e576520726573657276652074686520726967687420746f2072657175657374207468617420796f752072656d6f766520616c6c206c696e6b73206f7220616e7920706172746963756c6172206c696e6b20746f206f757220576562736974652e20596f7520617070726f766520746f20696d6d6564696174656c792072656d6f766520616c6c206c696e6b7320746f206f757220576562736974652075706f6e20726571756573742e20576520616c736f20726573657276652074686520726967687420746f20616d656e207468657365207465726d7320616e6420636f6e646974696f6e7320616e64206974e2809973206c696e6b696e6720706f6c69637920617420616e792074696d652e20427920636f6e74696e756f75736c79206c696e6b696e6720746f206f757220576562736974652c20796f7520616772656520746f20626520626f756e6420746f20616e6420666f6c6c6f77207468657365206c696e6b696e67207465726d7320616e6420636f6e646974696f6e732e3c2f703e0d0a0d0a3c68333e3c7374726f6e673e52656d6f76616c206f66206c696e6b732066726f6d206f757220776562736974653c2f7374726f6e673e3c2f68333e0d0a0d0a3c703e496620796f752066696e6420616e79206c696e6b206f6e206f757220576562736974652074686174206973206f6666656e7369766520666f7220616e7920726561736f6e2c20796f7520617265206672656520746f20636f6e7461637420616e6420696e666f726d20757320616e79206d6f6d656e742e2057652077696c6c20636f6e736964657220726571756573747320746f2072656d6f7665206c696e6b732062757420776520617265206e6f74206f626c69676174656420746f206f7220736f206f7220746f20726573706f6e6420746f20796f75206469726563746c792e3c2f703e0d0a0d0a3c703e576520646f206e6f7420656e7375726520746861742074686520696e666f726d6174696f6e206f6e2074686973207765627369746520697320636f72726563742c20776520646f206e6f742077617272616e742069747320636f6d706c6574656e657373206f722061636375726163793b206e6f7220646f2077652070726f6d69736520746f20656e7375726520746861742074686520776562736974652072656d61696e7320617661696c61626c65206f72207468617420746865206d6174657269616c206f6e207468652077656273697465206973206b65707420757020746f20646174652e3c2f703e0d0a0d0a3c68333e3c7374726f6e673e446973636c61696d65723c2f7374726f6e673e3c2f68333e0d0a0d0a3c703e546f20746865206d6178696d756d20657874656e74207065726d6974746564206279206170706c696361626c65206c61772c207765206578636c75646520616c6c20726570726573656e746174696f6e732c2077617272616e7469657320616e6420636f6e646974696f6e732072656c6174696e6720746f206f7572207765627369746520616e642074686520757365206f66207468697320776562736974652e204e6f7468696e6720696e207468697320646973636c61696d65722077696c6c3a3c2f703e0d0a0d0a3c756c3e0d0a202020203c6c693e6c696d6974206f72206578636c756465206f7572206f7220796f7572206c696162696c69747920666f72206465617468206f7220706572736f6e616c20696e6a7572793b3c2f6c693e0d0a202020203c6c693e6c696d6974206f72206578636c756465206f7572206f7220796f7572206c696162696c69747920666f72206672617564206f72206672617564756c656e74206d6973726570726573656e746174696f6e3b3c2f6c693e0d0a202020203c6c693e6c696d697420616e79206f66206f7572206f7220796f7572206c696162696c697469657320696e20616e79207761792074686174206973206e6f74207065726d697474656420756e646572206170706c696361626c65206c61773b206f723c2f6c693e0d0a202020203c6c693e6578636c75646520616e79206f66206f7572206f7220796f7572206c696162696c69746965732074686174206d6179206e6f74206265206578636c7564656420756e646572206170706c696361626c65206c61772e3c2f6c693e0d0a3c2f756c3e0d0a0d0a3c703e546865206c696d69746174696f6e7320616e642070726f6869626974696f6e73206f66206c696162696c6974792073657420696e20746869732053656374696f6e20616e6420656c7365776865726520696e207468697320646973636c61696d65723a2028612920617265207375626a65637420746f2074686520707265636564696e67207061726167726170683b20616e642028622920676f7665726e20616c6c206c696162696c69746965732061726973696e6720756e6465722074686520646973636c61696d65722c20696e636c7564696e67206c696162696c69746965732061726973696e6720696e20636f6e74726163742c20696e20746f727420616e6420666f7220627265616368206f66207374617475746f727920647574792e3c2f703e0d0a0d0a3c703e4173206c6f6e6720617320746865207765627369746520616e642074686520696e666f726d6174696f6e20616e64207365727669636573206f6e207468652077656273697465206172652070726f76696465642066726565206f66206368617267652c2077652077696c6c206e6f74206265206c6961626c6520666f7220616e79206c6f7373206f722064616d616765206f6620616e79206e61747572652e3c2f703e, NULL, NULL, '2021-10-18 02:33:45', '2022-10-20 04:41:25'),
(41, 9, 19, 'معلومات عنا', 'معلومات-عنا', 0x3c70207374796c653d22746578742d616c69676e3a72696768743b636f6c6f723a7267622836362c36362c3636293b666f6e742d66616d696c793a7461686f6d612c2073616e732d73657269663b223e3c7370616e207374796c653d22636f6c6f723a726762283130322c3130322c313032293b666f6e742d66616d696c793a4c61746f2c2073616e732d73657269663b666f6e742d73697a653a313670783b223ed985d98620d986d8a7d8add98ad8a920d8a3d8aed8b1d98920d88c20d986d8b4d8acd8a820d8a8d8b3d8aed8b720d8b5d8a7d984d8ad20d988d986d983d8b1d98720d8a7d984d8b1d8acd8a7d98420d8a7d984d8b0d98ad98620d8aed8afd8b9d987d98520d8b3d8add8b120d985d8aad8b9d8a920d8a7d984d984d8add8b8d8a920d988d8a5d8add8a8d8a7d8b7d987d98520d88c20d988d8a3d8b9d985d8aad987d98520d8a7d984d8b1d8bad8a8d8a920d88c20d984d8afd8b1d8acd8a920d8a3d986d987d98520d984d8a720d98ad8b3d8aad8b7d98ad8b9d988d98620d8a7d984d8aad986d8a8d8a420d8a8d8a7d984d8a3d984d98520d988d8a7d984d985d8aad8a7d8b9d8a820d8a7d984d8aad98a20d8b3d8aad8aad8b1d8aad8a820d8b9d984d98920d8b0d984d98320d89b20d988d8a7d984d984d988d98520d8a7d984d985d8aad8b3d8a7d988d98a20d98ad982d8b920d8b9d984d98920d985d98620d98ad981d8b4d98420d981d98a20d8a3d8afd8a7d8a120d988d8a7d8acd8a8d98720d8a8d8b3d8a8d8a820d8b6d8b9d98120d8a7d984d8a5d8b1d8a7d8afd8a920d88c20d988d987d98820d986d981d8b320d8a7d984d982d988d98420d8a8d8a7d984d8a7d986d983d985d8a7d8b420d985d98620d8a7d984d983d8af20d988d8a7d984d8a3d984d9852e20d987d8b0d98720d8a7d984d8add8a7d984d8a7d8aa20d8a8d8b3d98ad8b7d8a920d984d984d8bad8a7d98ad8a920d988d8b3d987d984d8a920d8a7d984d8aad985d98ad98ad8b22e20d981d98a20d8b3d8a7d8b9d8a920d985d8acd8a7d986d98ad8a920d88c20d8b9d986d8afd985d8a720d8aad983d988d98620d982d8afd8b1d8aad986d8a720d8b9d984d98920d8a7d984d8a7d8aed8aad98ad8a7d8b120d8bad98ad8b120d985d982d98ad8afd8a920d988d8b9d986d8afd985d8a720d984d8a720d8b4d98ad8a120d98ad985d986d8b9d986d8a720d985d98620d8a7d984d982d98ad8a7d98520d8a8d985d8a720d986d981d8b6d984d98720d88c20d98ad8acd8a820d8a7d984d8aad8b1d8add98ad8a820d8a8d983d98420d985d8aad8b9d8a920d988d8aad8acd986d8a820d983d98420d8a3d984d9852e20d988d984d983d98620d981d98a20d8b8d8b1d988d98120d985d8b9d98ad986d8a920d988d8a8d8b3d8a8d8a820d8a7d8afd8b9d8a7d8a1d8a7d8aa20d8a7d984d988d8a7d8acd8a820d8a3d98820d8a7d984d8aad8b2d8a7d985d8a7d8aa20d8a7d984d8b9d985d98420d88c20d8b3d98ad8add8afd8ab20d981d98a20d983d8abd98ad8b120d985d98620d8a7d984d8a3d8add98ad8a7d98620d8a3d986d98720d98ad8acd8a820d986d8a8d8b020d8a7d984d985d984d8b0d8a7d8aa20d988d982d8a8d988d98420d8a7d984d8a5d8b2d8b9d8a7d8ac2e20d984d8b0d984d98320d98ad8aad985d8b3d98320d8a7d984d8b1d8acd98420d8a7d984d8add983d98ad98520d8afd8a7d8a6d985d98bd8a720d981d98a20d987d8b0d98720d8a7d984d8a3d985d988d8b120d8a8d985d8a8d8afd8a320d8a7d984d8a7d8aed8aad98ad8a7d8b120d987d8b0d8a73a20d981d987d98820d98ad8b1d981d8b620d8a7d984d985d984d8b0d8a7d8aa20d984d8aad8a3d985d98ad98620d985d984d8b0d8a7d8aa20d8a3d8b9d8b8d98520d8a3d8aed8b1d98920d88c20d988d8a5d984d8a720d981d8a5d986d98720d98ad8aad8add985d98420d8a7d984d8a2d984d8a7d98520d984d8aad8acd986d8a820d8a7d984d8a2d984d8a7d98520d8a7d984d8b3d98ad8a6d8a92e3c2f7370616e3e3c6272202f3e3c2f703e, NULL, NULL, '2022-07-30 05:33:25', '2022-08-02 00:55:09'),
(42, 8, 19, 'About Us', 'about-us', 0x3c703e3c7370616e207374796c653d22636f6c6f723a726762283130322c3130322c313032293b666f6e742d66616d696c793a526f626f746f3b666f6e742d73697a653a313670783b223e4f6e20746865206f746865722068616e642c2077652064656e6f756e63652077697468207269676874656f757320696e6469676e6174696f6e20616e64206469736c696b65206d656e2077686f2061726520736f2062656775696c656420616e642064656d6f72616c697a65642062792074686520636861726d73206f6620706c656173757265206f6620746865206d6f6d656e742c20736f20626c696e646564206279206465736972652c207468617420746865792063616e6e6f7420666f726573656520746865207061696e20616e642074726f75626c6520746861742061726520626f756e6420746f20656e7375653b20616e6420657175616c20626c616d652062656c6f6e677320746f2074686f73652077686f206661696c20696e2074686569722064757479207468726f756768207765616b6e657373206f662077696c6c2c207768696368206973207468652073616d6520617320736179696e67207468726f75676820736872696e6b696e672066726f6d20746f696c20616e64207061696e2e2054686573652063617365732061726520706572666563746c792073696d706c6520616e64206561737920746f2064697374696e67756973682e20496e2061206672656520686f75722c207768656e206f757220706f776572206f662063686f69636520697320756e7472616d6d656c656420616e64207768656e206e6f7468696e672070726576656e7473206f7572206265696e672061626c6520746f20646f2077686174207765206c696b6520626573742c20657665727920706c65617375726520697320746f2062652077656c636f6d656420616e64206576657279207061696e2061766f696465642e2042757420696e206365727461696e2063697263756d7374616e63657320616e64206f77696e6720746f2074686520636c61696d73206f662064757479206f7220746865206f626c69676174696f6e73206f6620627573696e6573732069742077696c6c206672657175656e746c79206f63637572207468617420706c65617375726573206861766520746f206265207265707564696174656420616e6420616e6e6f79616e6365732061636365707465642e205468652077697365206d616e207468657265666f726520616c7761797320686f6c647320696e207468657365206d61747465727320746f2074686973207072696e6369706c65206f662073656c656374696f6e3a2068652072656a6563747320706c6561737572657320746f20736563757265206f74686572206772656174657220706c656173757265732c206f7220656c736520686520656e6475726573207061696e7320746f2061766f696420776f727365207061696e732e3c2f7370616e3e3c6272202f3e3c2f703e, NULL, NULL, '2022-07-30 05:33:25', '2022-08-01 04:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `page_headings`
--

CREATE TABLE `page_headings` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `blog_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_details_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `products_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_details_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `error_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `faq_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `forget_password_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_forget_password_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `signup_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_login_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_signup_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkout_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipment_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipment_details_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_headings`
--

INSERT INTO `page_headings` (`id`, `language_id`, `blog_page_title`, `blog_details_page_title`, `contact_page_title`, `products_page_title`, `product_details_page_title`, `error_page_title`, `faq_page_title`, `forget_password_page_title`, `vendor_forget_password_page_title`, `login_page_title`, `signup_page_title`, `vendor_login_page_title`, `vendor_signup_page_title`, `cart_page_title`, `checkout_page_title`, `equipment_page_title`, `equipment_details_page_title`, `vendor_page_title`, `created_at`, `updated_at`) VALUES
(4, 9, 'مقالات', 'تفاصيل المنشور', 'اتصال', 'منتجات', 'تفاصيل المنتج', '404', 'التعليمات', 'نسيت كلمة المرور', 'البائع نسيت كلمة المرور', 'تسجيل الدخول', 'اشتراك', 'تسجيل دخول البائع', 'تسجيل البائع', 'عربة التسوق', 'الدفع', 'معدات', 'تفاصيل المعدات', 'بائع', '2021-10-14 02:42:42', '2022-10-23 01:34:42'),
(8, 8, 'Blog', 'Post Details', 'Contact', 'Products', 'Product Details', '404', 'FAQ', 'Forget Password', 'Vendor Forget Password', 'Login', 'Signup', 'Vendor Login', 'Vendor Signup', 'Cart', 'Checkout', 'Equipment', 'Equipment Details', 'Vendors', '2022-01-10 05:21:48', '2022-10-22 04:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` smallint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `image`, `url`, `serial_number`, `created_at`, `updated_at`) VALUES
(8, '61d17f5e76212.png', 'http://example.com/', 1, '2022-01-02 04:33:02', '2022-07-06 23:24:01'),
(9, '61d17f68437f9.png', 'http://example.com/', 2, '2022-01-02 04:33:12', '2022-07-06 23:21:13'),
(10, '61d17f724771f.png', 'http://example.com/', 3, '2022-01-02 04:33:22', '2022-07-06 23:24:33'),
(11, '61d17f8069bc7.png', 'http://example.com/', 4, '2022-01-02 04:33:36', '2022-07-06 23:25:11'),
(12, '61d17f8ba9ef8.png', 'http://example.com/', 5, '2022-01-02 04:33:47', '2022-07-06 23:19:30'),
(17, '61d17f68437f9.png', 'http://example.com/', 6, '2022-01-02 04:33:12', '2022-07-06 23:21:13');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `popups`
--

CREATE TABLE `popups` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `type` smallint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `background_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_color_opacity` decimal(3,2) UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `button_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `delay` int UNSIGNED NOT NULL COMMENT 'value will be in milliseconds',
  `serial_number` mediumint UNSIGNED NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 => deactive, 1 => active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `popups`
--

INSERT INTO `popups` (`id`, `language_id`, `type`, `image`, `name`, `background_color`, `background_color_opacity`, `title`, `text`, `button_text`, `button_color`, `button_url`, `end_date`, `end_time`, `delay`, `serial_number`, `status`, `created_at`, `updated_at`) VALUES
(7, 8, 1, '63035116cdbb8.png', 'Super Sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1500, 1, 0, '2021-08-10 05:05:12', '2022-08-22 04:07:58'),
(8, 8, 2, '630353e579d10.png', 'Month End Sale', '000000', '0.70', 'ENJOY 10% OFF', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'Shop Now', '000000', 'http://example.com/', NULL, NULL, 2000, 2, 0, '2021-08-10 05:07:11', '2022-08-22 04:14:19'),
(10, 8, 3, '6303575ae73a2.png', 'Summer Sale', 'FF8C00', '0.85', 'Newsletter', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'Subscribe', 'FF8C00', NULL, NULL, NULL, 2000, 3, 0, '2021-08-11 05:42:11', '2022-08-22 04:17:27'),
(11, 8, 4, '630358d59cd94.png', 'Winter Offer', NULL, NULL, 'Get 10% off your first order', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt', 'Shop Now', 'FF8C00', 'http://example.com/', NULL, NULL, 1500, 4, 0, '2021-08-11 06:38:08', '2022-08-22 04:23:05'),
(12, 8, 5, '6303592c51003.png', 'Winter Sale', NULL, NULL, 'Get 10% off your first order', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt', 'Subscribe', 'F8960D', NULL, NULL, NULL, 2000, 5, 1, '2021-08-11 06:44:26', '2022-09-01 05:31:05'),
(13, 8, 6, '63035e25b0dce.png', 'New Arrivals Sale', NULL, NULL, 'Hurry, Sales Ends This Friday', 'This is your last chance to save 30%', 'Yes, I Want to Save 30%', 'F4A460', 'http://example.com/', '2024-07-25', '10:00:00', 2000, 6, 0, '2021-08-11 06:48:52', '2022-08-22 04:45:43'),
(14, 8, 7, '63035bac6f1e1.png', 'Flash Sale', 'CD853F', NULL, 'Hurry, Sale Ends This Friday', 'This is your last chance to save 30%', 'Yes, I Want to Save 30%', 'F4A460', 'http://example.com/', '2025-01-14', '12:00:00', 1500, 7, 0, '2021-08-11 07:15:16', '2022-10-20 05:58:49'),
(19, 9, 1, '61a6f917913f2.jpg', 'الجمعة السوداء', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1500, 1, 0, '2021-11-30 22:24:55', '2022-08-20 01:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `product_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slider_images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `input_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int UNSIGNED DEFAULT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_price` decimal(8,2) UNSIGNED NOT NULL,
  `previous_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `average_rating` decimal(4,2) UNSIGNED DEFAULT '0.00',
  `is_featured` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `vendor_id`, `product_type`, `featured_image`, `slider_images`, `status`, `input_type`, `file`, `link`, `stock`, `sku`, `current_price`, `previous_price`, `average_rating`, `is_featured`, `created_at`, `updated_at`) VALUES
(21, NULL, 'physical', '62e1174b98d08.png', '[\"62e117448087c.png\",\"62e11744865b8.png\",\"62e11744b2c86.png\",\"62e11744b30a5.png\"]', 'show', NULL, NULL, NULL, 12, 'DRI-RED-987', '75.00', '120.00', '0.00', 'yes', '2022-01-09 02:37:18', '2022-08-29 01:32:29'),
(24, NULL, 'physical', '62e117999c388.png', '[\"62e117a89a6d3.png\",\"62e117a89d0bd.png\",\"62e117a8c6695.png\",\"62e117a8c6bf0.png\"]', 'show', NULL, NULL, NULL, 20, 'SAW-ORG-789', '300.00', '350.00', '0.00', 'yes', '2022-01-10 02:34:41', '2022-08-21 06:41:57'),
(25, NULL, 'physical', '62e118a917c6f.png', '[\"62e1184ee7aa6.png\",\"62e1184eeb4ef.png\",\"62e1184f2b0cb.png\",\"62e1184f2b1f5.png\"]', 'show', NULL, NULL, NULL, 6, 'GEN-YEL-678', '190.00', NULL, '5.00', 'yes', '2022-01-10 02:46:55', '2022-09-01 08:34:48'),
(29, NULL, 'physical', '62e11b00a9484.png', '[\"62e11a8d9b428.png\",\"62e11a8da0be6.png\",\"62e11a8dc52ad.png\",\"62e11a8dc97eb.png\"]', 'show', NULL, NULL, NULL, 24, 'SAW-RED-678', '45.00', NULL, '0.00', 'yes', '2022-07-27 05:01:20', '2022-10-05 06:13:51'),
(30, NULL, 'physical', '62e122deadfd4.png', '[\"62e1226f75348.png\",\"62e1226f75345.png\",\"62e1226f9db5f.png\",\"62e1226fa1737.png\"]', 'show', NULL, NULL, NULL, 17, 'GRI-BLU-567', '89.00', '129.00', '3.00', 'yes', '2022-07-27 05:34:54', '2022-10-04 10:48:26'),
(31, NULL, 'physical', '62e4d8f9a5dbe.png', '[\"62e4d852dd5c8.png\",\"62e4d852dd5eb.png\",\"62e4d8530e69f.png\",\"62e4d85311dcb.png\"]', 'show', NULL, NULL, NULL, 26, 'BOX-BLA-456', '79.00', NULL, '0.00', 'no', '2022-07-30 01:08:41', '2022-12-22 04:57:21'),
(32, NULL, 'physical', '62e4d9f8766f7.png', '[\"62e4d98bd1320.png\",\"62e4d98bd3661.png\",\"62e4d98c04329.png\",\"62e4d98c078ea.png\"]', 'show', NULL, NULL, NULL, 8, 'PLU-RED-345', '15.00', '20.00', '0.00', 'no', '2022-07-30 01:12:56', '2022-10-05 06:13:51'),
(33, NULL, 'physical', '62e4dac2ade7a.png', '[\"62e4da4fa88d2.png\",\"62e4da4fa9386.png\",\"62e4da4fd463d.png\",\"62e4da4fd47e6.png\"]', 'show', NULL, NULL, NULL, 29, 'MUL-ORA-345', '25.00', '29.00', '0.00', 'no', '2022-07-30 01:16:18', '2022-10-09 01:23:33'),
(34, NULL, 'physical', '62e4dbc8b0922.png', '[\"62e4db5eb2bbb.png\",\"62e4db5eb403c.png\",\"62e4db5ed9444.png\",\"62e4db5edcd14.png\"]', 'show', NULL, NULL, NULL, 13, 'POL-YEL-234', '150.00', '165.00', '0.00', 'no', '2022-07-30 01:20:40', '2022-12-22 04:16:18'),
(35, NULL, 'physical', '62e4dd175f0d5.png', '[\"62e4dc6fdf7e8.png\",\"62e4dc6fe2363.png\",\"62e4dc7013ad0.png\",\"62e4dc701962b.png\"]', 'show', NULL, NULL, NULL, 18, 'GEN-RED-123', '250.00', NULL, '4.00', 'no', '2022-07-30 01:26:15', '2022-10-05 06:13:51'),
(40, NULL, 'digital', '63036c1d345c5.png', '[\"6303656c0f0e5.png\",\"6303656c16c32.png\",\"6303656c34881.png\",\"6303656c3c723.png\"]', 'show', 'link', NULL, 'http://www.example.com/', NULL, NULL, '10.00', NULL, '0.00', 'no', '2022-08-22 05:19:23', '2022-08-22 05:44:29'),
(41, NULL, 'digital', '630464edbee87.png', '[\"63036cc7147dd.png\",\"63036cc717322.png\",\"63036cc73a2ac.png\",\"63036cc73c1d1.png\"]', 'show', 'link', NULL, 'http://www.example.com/', NULL, NULL, '15.00', '23.00', '0.00', 'no', '2022-08-22 05:49:18', '2022-08-22 23:26:05'),
(42, 7, 'digital', '634fc21dad782.jpg', '[\"634fc16a6edb2.jpg\",\"634fc19198513.jpg\"]', 'show', 'link', NULL, 'https://www.youtube.com/watch?v=S9uiof1H_Kg', NULL, NULL, '100.00', '120.00', '5.00', 'no', '2022-10-19 03:23:41', '2022-12-11 01:13:08'),
(43, NULL, 'physical', '63f046c56074f.png', '[\"63f04694b42f0.png\",\"63f04694b42f1.png\",\"63f04694e511f.png\"]', 'show', NULL, NULL, NULL, 10, NULL, '100.00', '120.00', '0.00', 'no', '2023-02-17 21:32:21', '2023-02-17 21:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL,
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `language_id`, `name`, `slug`, `status`, `serial_number`, `created_at`, `updated_at`) VALUES
(27, 8, 'Cordless Tools', 'cordless-tools', 1, 1, '2022-01-05 23:32:06', '2022-08-27 23:40:53'),
(28, 8, 'Generator', 'generator', 1, 2, '2022-01-05 23:32:19', '2022-08-27 23:41:02'),
(30, 9, 'أدوات لاسلكية', 'أدوات-لاسلكية', 1, 1, '2022-01-05 23:34:56', '2022-08-27 23:41:28'),
(31, 9, 'مولد كهرباء', 'مولد-كهرباء', 1, 2, '2022-01-05 23:35:23', '2022-08-27 23:41:37'),
(34, 8, 'SAW', 'saw', 1, 3, '2022-01-10 01:24:32', '2022-08-27 23:41:05'),
(35, 9, 'منشار', 'منشار', 1, 3, '2022-01-10 01:25:26', '2022-08-27 23:41:40'),
(42, 8, 'Grinders & Polishers', 'grinders-&-polishers', 1, 4, '2022-07-27 05:32:22', '2022-08-27 23:41:07'),
(43, 9, 'المطاحن وأجهزة التلميع', 'المطاحن-وأجهزة-التلميع', 1, 4, '2022-07-27 05:32:43', '2022-08-27 23:41:44'),
(48, 8, 'Book', 'book', 1, 5, '2022-08-22 05:19:53', '2022-08-27 23:41:10'),
(49, 9, 'الكتاب', 'الكتاب', 1, 5, '2022-08-22 05:20:20', '2022-08-27 23:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `product_contents`
--

CREATE TABLE `product_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `product_category_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_contents`
--

INSERT INTO `product_contents` (`id`, `language_id`, `product_category_id`, `product_id`, `title`, `slug`, `summary`, `content`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(41, 9, 30, 21, 'آلة الحفر', 'آلة-الحفر', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<div style=\"text-align:justify;\"><font>لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</font></div>', NULL, NULL, '2022-01-09 02:37:18', '2022-08-01 04:34:23'),
(42, 8, 27, 21, 'Drill Machine', 'drill-machine', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-01-09 02:37:18', '2022-08-01 04:34:23'),
(45, 9, 35, 24, 'المنشار', 'المنشار', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br />', NULL, NULL, '2022-01-10 02:34:41', '2022-08-01 04:36:19'),
(46, 8, 34, 24, 'Chainsaw', 'chainsaw', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br />', NULL, NULL, '2022-01-10 02:34:41', '2022-08-01 04:36:19'),
(47, 9, 31, 25, 'مولد كهرباء', 'مولد-كهرباء', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<div style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.<br /></div>', NULL, NULL, '2022-01-10 02:46:56', '2022-08-01 04:36:55'),
(48, 8, 28, 25, 'Generator', 'generator', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br />', NULL, NULL, '2022-01-10 02:46:56', '2022-08-01 04:36:55'),
(55, 9, 35, 29, 'منشار دائري', 'منشار-دائري', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<p><span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br /></p>', NULL, NULL, '2022-07-27 05:01:20', '2022-08-01 04:37:48'),
(56, 8, 34, 29, 'Circular saw', 'circular-saw', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-07-27 05:01:20', '2022-08-01 04:37:48'),
(57, 9, 43, 30, 'آلة طحن', 'آلة-طحن', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<p><span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br /></p>', NULL, NULL, '2022-07-27 05:34:55', '2022-08-01 04:39:09'),
(58, 8, 42, 30, 'Grinding Machine', 'grinding-machine', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-07-27 05:34:55', '2022-08-01 04:39:09'),
(59, 9, 30, 31, 'كل ذلك في صندوق أدوات واحد', 'كل-ذلك-في-صندوق-أدوات-واحد', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<p><span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br /></p>', NULL, NULL, '2022-07-30 01:08:41', '2022-08-01 04:40:14'),
(60, 8, 27, 31, 'All in One Tool Box', 'all-in-one-tool-box', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-07-30 01:08:41', '2022-08-01 04:40:14'),
(61, 9, 30, 32, 'كاتنج بلس', 'كاتنج-بلس', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<p><span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br /></p>', NULL, NULL, '2022-07-30 01:12:56', '2022-08-01 04:41:20'),
(62, 8, 27, 32, 'Cutting Plus', 'cutting-plus', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-07-30 01:12:56', '2022-08-01 04:41:20'),
(63, 9, 30, 33, 'جهاز رقمي متعدد', 'جهاز-رقمي-متعدد', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<p><span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br /></p>', NULL, NULL, '2022-07-30 01:16:18', '2022-08-01 04:42:13'),
(64, 8, 27, 33, 'Digital Multimeter', 'digital-multimeter', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-07-30 01:16:18', '2022-08-01 04:42:13'),
(65, 9, 43, 34, 'غسالة بوش AQT 37-13 Plus 1700 وات للمنزل والسيارة', 'غسالة-بوش-aqt-37-13-plus-1700-وات-للمنزل-والسيارة', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<p><span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br /></p>', NULL, NULL, '2022-07-30 01:20:40', '2022-08-01 04:43:01'),
(66, 8, 42, 34, 'Bosch AQT 37-13 Plus 1700-Watt Home and Car Washer', 'bosch-aqt-37-13-plus-1700-watt-home-and-car-washer', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-07-30 01:20:40', '2022-08-01 04:43:01'),
(67, 9, 31, 35, 'ماكينة لحام SEALEY Professional Mighty MIG', 'ماكينة-لحام-sealey-professional-mighty-mig', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<p><span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br /></p>', NULL, NULL, '2022-07-30 01:26:15', '2022-08-01 04:43:54'),
(68, 8, 28, 35, 'SEALEY Professional Mighty MIG Welder', 'sealey-professional-mighty-mig-welder', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-07-30 01:26:15', '2022-08-01 04:43:54'),
(77, 8, 48, 40, 'Manual Guide', 'manual-guide', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-08-22 05:19:24', '2022-08-22 05:21:10'),
(78, 9, 49, 40, 'دليل اليدوي', 'دليل-اليدوي', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<p><span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br /></p>', NULL, NULL, '2022-08-22 05:19:24', '2022-08-22 05:21:10'),
(79, 8, 48, 41, 'User Manual Book', 'user-manual-book', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '<p><span style=\"font-family:\'Open Sans\', Arial, sans-serif;text-align:justify;\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br /></p>', NULL, NULL, '2022-08-22 05:49:18', '2022-08-22 05:49:18'),
(80, 9, 49, 41, 'كتاب دليل المستخدم', 'كتاب-دليل-المستخدم', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '<p><span style=\"text-align:justify;\">لكن لا بد أن أوضح لك أن كل هذه الأفكار المغلوطة حول استنكار  النشوة وتمجيد الألم نشأت بالفعل، وسأعرض لك التفاصيل لتكتشف حقيقة وأساس تلك السعادة البشرية، فلا أحد يرفض أو يكره أو يتجنب الشعور بالسعادة، ولكن بفضل هؤلاء الأشخاص الذين لا يدركون بأن السعادة لا بد أن نستشعرها بصورة أكثر عقلانية ومنطقية فيعرضهم هذا لمواجهة الظروف الأليمة، وأكرر بأنه لا يوجد من يرغب في الحب ونيل المنال ويتلذذ بالآلام، الألم هو الألم ولكن نتيجة لظروف ما قد تكمن السعاده فيما نتحمله من كد وأسي.</span><br /></p>', NULL, NULL, '2022-08-22 05:49:18', '2022-08-22 05:49:18'),
(81, 8, 27, 42, 'EOS R7 (Body)', 'eos-r7-(body)', 'EOS R7 (Body)EOS R7 (Body)EOS R7 (R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)', '<p>EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)<br /></p>', 'dsfs', 'dfsdfssdfsdfsdfsdf', '2022-10-19 03:23:42', '2022-10-19 03:23:42'),
(82, 9, 35, 42, 'EOS R7 (Body)', 'eos-r7-(body)', 'EOS R7 (Body)EOS R7 (Body)EOS R7 (R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)', '<p>EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)EOS R7 (Body)<br /></p>', 'dsfs', 'dfsdfssdfsdfsdfsdf', '2022-10-19 03:23:42', '2022-10-19 03:23:42'),
(83, 8, 48, 43, 'Book', 'book', 'orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '<p>orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br /></p>', 'fdsa', 'sdafasfaf', '2023-02-17 21:32:22', '2023-02-17 21:32:22'),
(84, 9, 35, 43, 'Book', 'book', 'orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '<p>orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br /></p>', 'fdsa', 'sdafasfaf', '2023-02-17 21:32:22', '2023-02-17 21:32:22');

-- --------------------------------------------------------

--
-- Table structure for table `product_coupons`
--

CREATE TABLE `product_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(8,2) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `minimum_spend` decimal(8,2) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_coupons`
--

INSERT INTO `product_coupons` (`id`, `name`, `code`, `type`, `value`, `start_date`, `end_date`, `minimum_spend`, `created_at`, `updated_at`) VALUES
(7, 'Sell30', 'Sell30', 'fixed', '30.00', '2022-07-01', '2022-08-31', NULL, '2022-01-05 22:25:33', '2022-08-27 06:04:39'),
(8, 'WinterSell', 'WinterSell', 'percentage', '25.00', '2022-07-19', '2022-10-20', '50.00', '2022-01-05 22:27:46', '2022-08-28 05:56:20'),
(9, 'FridaySell', 'FridaySell', 'fixed', '20.00', '2021-12-25', '2021-12-31', NULL, '2022-01-05 22:29:40', '2022-01-05 22:29:40');

-- --------------------------------------------------------

--
-- Table structure for table `product_orders`
--

CREATE TABLE `product_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_contact_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_contact_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(8,2) UNSIGNED NOT NULL,
  `discount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `product_shipping_charge_id` bigint UNSIGNED DEFAULT NULL,
  `shipping_cost` decimal(8,2) UNSIGNED DEFAULT NULL,
  `tax` decimal(8,2) UNSIGNED NOT NULL,
  `grand_total` decimal(8,2) UNSIGNED NOT NULL,
  `currency_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_text_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_orders`
--

INSERT INTO `product_orders` (`id`, `user_id`, `order_number`, `billing_first_name`, `billing_last_name`, `billing_email`, `billing_contact_number`, `billing_address`, `billing_city`, `billing_state`, `billing_country`, `shipping_first_name`, `shipping_last_name`, `shipping_email`, `shipping_contact_number`, `shipping_address`, `shipping_city`, `shipping_state`, `shipping_country`, `total`, `discount`, `product_shipping_charge_id`, `shipping_cost`, `tax`, `grand_total`, `currency_text`, `currency_text_position`, `currency_symbol`, `currency_symbol_position`, `payment_method`, `gateway_type`, `payment_status`, `order_status`, `attachment`, `invoice`, `created_at`, `updated_at`) VALUES
(199, 15, '63da4ea576921', 'Robert', 'Fonseca', 'robert@gmail.com', '715-396-9284', '1470 King St', 'Bellingham', 'Washington', 'United States', 'Robert', 'Fonseca', 'robert@gmail.com', '715-396-9284', '1470 King St', 'Bellingham', 'Washington', 'United States', '100.00', '0.00', NULL, NULL, '5.00', '105.00', 'USD', 'left', '$', 'left', 'Citibank', 'offline', 'pending', 'pending', NULL, NULL, '2023-02-01 05:36:05', '2023-02-01 05:36:05');

-- --------------------------------------------------------

--
-- Table structure for table `product_purchase_items`
--

CREATE TABLE `product_purchase_items` (
  `id` bigint UNSIGNED NOT NULL,
  `product_order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_purchase_items`
--

INSERT INTO `product_purchase_items` (`id`, `product_order_id`, `product_id`, `title`, `quantity`, `created_at`, `updated_at`) VALUES
(298, 199, 42, 'EOS R7 (Body)', 1, '2023-02-01 05:36:05', '2023-02-01 05:36:05');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rating` smallint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `user_id`, `product_id`, `comment`, `rating`, `created_at`, `updated_at`) VALUES
(9, 15, 25, '.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn.equipement-sidebar-info .booking-form .pricing-body .price-option span.span-btn', 5, '2022-07-28 04:02:57', '2022-07-28 04:02:57'),
(10, 15, 30, 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 3, '2022-07-30 05:48:05', '2022-07-30 05:48:05'),
(11, 15, 35, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 4, '2022-07-30 05:51:04', '2022-07-30 05:51:04');

-- --------------------------------------------------------

--
-- Table structure for table `product_sections`
--

CREATE TABLE `product_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sections`
--

INSERT INTO `product_sections` (`id`, `language_id`, `subtitle`, `title`, `text`, `created_at`, `updated_at`) VALUES
(2, 8, 'Featured Products', 'Best Quality Product', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit.', '2022-02-28 04:27:50', '2022-07-30 04:54:33'),
(3, 9, 'أفضل منتج عالي الجودة', 'منتجات مميزة', 'تبرع: إن كنت تستخدم هذا الموقع بشكل دائم وترغب في مساعدته، أرجو التبرع ولو بمبلغ بسيط للمساعدة في دفع أجور استضافة الموقع وأجور نقل البيانات. لا يوجد حد أدنى للتبرع، سنكون ممتنين لأي مبلغ', '2022-03-06 00:36:22', '2022-03-06 00:36:22');

-- --------------------------------------------------------

--
-- Table structure for table `product_shipping_charges`
--

CREATE TABLE `product_shipping_charges` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_charge` decimal(8,2) UNSIGNED NOT NULL,
  `serial_number` smallint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_shipping_charges`
--

INSERT INTO `product_shipping_charges` (`id`, `language_id`, `title`, `short_text`, `shipping_charge`, `serial_number`, `created_at`, `updated_at`) VALUES
(5, 8, 'Free Shipping', 'Shipment will be within 10-15 Days.', '0.00', 1, '2021-07-04 00:31:50', '2021-07-19 01:33:50'),
(6, 8, 'Standard Shipping', 'Shipment will be within 5-10 Day.', '5.00', 2, '2021-07-04 00:32:40', '2021-07-04 00:32:40'),
(7, 8, '2-Day Shipping', 'Shipment will be within 2 Days.', '10.00', 3, '2021-07-04 00:33:24', '2021-07-04 00:33:24'),
(8, 8, 'Same Day Shipping', 'Shipment will be within 1 Day.', '20.00', 4, '2021-07-04 00:34:09', '2021-07-04 00:34:09'),
(9, 9, 'الشحن مجانا', 'ستكون الشحنة في غضون 10-15 يومًا.', '0.00', 1, '2021-08-29 06:09:43', '2021-08-29 06:09:43'),
(10, 9, 'شحن قياسي', 'سيتم الشحن في غضون 5-10 يوم.', '5.00', 2, '2021-08-29 06:11:01', '2021-08-29 06:11:01'),
(11, 9, 'شحن لمدة يومين', 'ستكون الشحنة في غضون يومين.', '10.00', 3, '2021-08-29 06:12:14', '2021-08-29 06:12:14'),
(12, 9, 'نفس الشحن يوم', 'ستكون الشحنة في غضون يوم واحد.', '20.00', 4, '2021-08-29 06:13:30', '2021-08-29 06:13:30');

-- --------------------------------------------------------

--
-- Table structure for table `push_subscriptions`
--

CREATE TABLE `push_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `subscribable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscribable_id` bigint UNSIGNED NOT NULL,
  `endpoint` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_encoding` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quick_links`
--

CREATE TABLE `quick_links` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` smallint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quick_links`
--

INSERT INTO `quick_links` (`id`, `language_id`, `title`, `url`, `serial_number`, `created_at`, `updated_at`) VALUES
(3, 8, 'Privacy & Policy', 'http://example.com/', 1, '2021-06-22 22:52:38', '2021-12-01 04:14:35'),
(4, 8, 'About Me', 'http://example.com/', 2, '2021-06-22 22:53:09', '2021-12-01 04:14:55'),
(5, 8, 'Contact', 'http://example.com/', 3, '2021-06-22 22:53:27', '2021-12-01 04:15:26'),
(6, 9, 'سياسة خاصة', 'http://example.com/', 1, '2021-08-29 07:04:05', '2021-12-01 04:16:18'),
(7, 9, 'عني', 'http://example.com/', 2, '2021-08-29 07:06:32', '2021-12-01 04:16:34'),
(8, 9, 'اتصل', 'http://example.com/', 3, '2021-08-29 07:08:21', '2021-12-01 04:16:52');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(4, 'Admin', '[\"Language Management\",\"Basic Settings\",\"Home Page\",\"Instructors\",\"Course Management\",\"Course Enrolments\",\"FAQ Management\",\"Footer\"]', '2021-08-06 22:42:38', '2021-11-24 23:20:08'),
(6, 'Moderator', '[\"Basic Settings\",\"Home Page\",\"Course Enrolments\",\"Blog Management\",\"FAQ Management\",\"Advertisements\",\"Footer\"]', '2021-08-07 22:14:34', '2021-11-24 22:15:07'),
(14, 'Supervisor', 'null', '2021-11-24 22:48:53', '2022-02-26 05:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint UNSIGNED NOT NULL,
  `about_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `work_process_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `feature_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `counter_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `equipment_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `testimonial_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `product_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `call_to_action_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `blog_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `partner_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `subscribe_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `footer_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `about_section_status`, `work_process_section_status`, `feature_section_status`, `counter_section_status`, `equipment_section_status`, `testimonial_section_status`, `product_section_status`, `call_to_action_section_status`, `blog_section_status`, `partner_section_status`, `subscribe_section_status`, `footer_section_status`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, '2022-08-20 22:43:25');

-- --------------------------------------------------------

--
-- Table structure for table `security_deposit_refunds`
--

CREATE TABLE `security_deposit_refunds` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint DEFAULT NULL,
  `refund_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partial_amount` double(8,2) DEFAULT '0.00',
  `status` int DEFAULT '0' COMMENT '0=pending, 1=aggree, raise dispute',
  `refund_status` int DEFAULT '0' COMMENT '0=pending, 1=refunded',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `security_deposit_refunds`
--

INSERT INTO `security_deposit_refunds` (`id`, `booking_id`, `refund_type`, `partial_amount`, `status`, `refund_status`, `created_at`, `updated_at`) VALUES
(2, 24, 'partial', 11.00, 2, 0, '2023-06-15 00:02:38', '2023-06-15 01:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `seos`
--

CREATE TABLE `seos` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `meta_keyword_home` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_home` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword_equipment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_equipment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword_products` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_products` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword_cart` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_cart` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword_blog` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_blog` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword_faq` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_faq` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_contact` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword_login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_login` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword_signup` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_signup` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword_forget_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_forget_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords_vendor_login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_vendor_login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords_vendor_signup` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_vendor_signup` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords_vendor_forget_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_descriptions_vendor_forget_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords_vendor_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description_vendor_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seos`
--

INSERT INTO `seos` (`id`, `language_id`, `meta_keyword_home`, `meta_description_home`, `meta_keyword_equipment`, `meta_description_equipment`, `meta_keyword_products`, `meta_description_products`, `meta_keyword_cart`, `meta_description_cart`, `meta_keyword_blog`, `meta_description_blog`, `meta_keyword_faq`, `meta_description_faq`, `meta_keyword_contact`, `meta_description_contact`, `meta_keyword_login`, `meta_description_login`, `meta_keyword_signup`, `meta_description_signup`, `meta_keyword_forget_password`, `meta_description_forget_password`, `meta_keywords_vendor_login`, `meta_description_vendor_login`, `meta_keywords_vendor_signup`, `meta_description_vendor_signup`, `meta_keywords_vendor_forget_password`, `meta_descriptions_vendor_forget_password`, `meta_keywords_vendor_page`, `meta_description_vendor_page`, `created_at`, `updated_at`) VALUES
(2, 9, 'مسكن', 'وصف المنزل', 'معدات', 'وصف المعدات', 'منتجات', 'وصف المنتجات', 'عربة التسوق', 'وصف عربة التسوق', 'مقالات', 'وصف المدونة', 'التعليمات', 'التعليمات الوصف', 'اتصال', 'وصف جهة الاتصال', 'تسجيل الدخول', 'وصف تسجيل الدخول', 'اشتراك', 'وصف الاشتراك', 'هل نسيت كلمة السر', 'نسيت كلمة المرور الوصف', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-07-30 05:57:39', '2022-08-01 01:24:18'),
(4, 8, 'Home', 'Home Description', 'Equipment', 'Equipment Description', 'Products', 'Products Description', 'Cart', 'Cart Description', 'Blog', 'Blog Description', 'FAQ', 'FAQ Description', 'Contact', 'Contact Description', 'Login', 'Login Description', 'Signup', 'Signup Description', 'Forgot Password', 'Forgot Password Description', 'vendor login', 'vendor login page description', 'vendor signup', 'vendor signup page description', 'vendor_forget_password', 'vendor forget password', 'vendors', 'vendors', '2022-03-05 23:49:35', '2022-10-22 04:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `background_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `language_id`, `background_image`, `title`, `text`, `created_at`, `updated_at`) VALUES
(4, 9, '61d2debbeb6a3.jpg', 'أفضل معدات البناء', 'هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها.', '2022-01-03 05:32:11', '2022-08-01 03:16:15'),
(5, 9, '61d2ded1081e7.jpg', 'أفضل معدات البناء', 'هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها.', '2022-01-03 05:32:33', '2022-08-01 03:16:41'),
(7, 8, '63b1793774949.png', 'Best Construction Equipment', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', '2022-03-06 00:16:33', '2023-01-01 06:14:47'),
(8, 8, '63b1793e1f09d.png', 'Best Construction Equipment', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', '2022-03-06 00:17:21', '2023-01-01 06:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `social_medias`
--

CREATE TABLE `social_medias` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_medias`
--

INSERT INTO `social_medias` (`id`, `icon`, `url`, `serial_number`, `created_at`, `updated_at`) VALUES
(36, 'fab fa-facebook-f', 'http://example.com/', 1, '2021-11-20 03:01:42', '2021-11-20 03:01:42'),
(37, 'fab fa-twitter', 'http://example.com/', 3, '2021-11-20 03:03:22', '2021-11-20 03:03:22'),
(38, 'fab fa-linkedin-in', 'http://example.com/', 2, '2021-11-20 03:04:29', '2021-11-20 03:04:29');

-- --------------------------------------------------------

--
-- Table structure for table `static_sections`
--

CREATE TABLE `static_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `button_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `static_sections`
--

INSERT INTO `static_sections` (`id`, `language_id`, `title`, `text`, `button_name`, `button_url`, `created_at`, `updated_at`) VALUES
(3, 9, 'أفضل معدات البناء', 'هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها.', 'يكتشف', 'http://example.com/', '2022-02-28 00:24:35', '2022-08-01 03:22:23'),
(4, 8, 'Best Construction Equipment', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'Discover', 'http://example.com/', '2022-03-06 00:18:59', '2022-08-01 03:21:48');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email_id`, `created_at`, `updated_at`) VALUES
(5, 'fahadahmadshemul@gmail.com', '2023-01-22 03:19:16', '2023-01-22 03:19:16'),
(6, 'hassanfahad910@gmail.com', '2023-06-07 02:57:22', '2023-06-07 02:57:22');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` int NOT NULL,
  `admin_id` int DEFAULT NULL,
  `ticket_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1-pending, 2-open, 3-closed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_message` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `vendor_id`, `admin_id`, `ticket_number`, `email`, `subject`, `description`, `attachment`, `status`, `created_at`, `updated_at`, `last_message`) VALUES
(10, 23, 1, '63b2b7b85c63b', 'megasoft.envato@gmail.com', 'Payment Rejected', 'Lorem Ipsum dummy text', '63b2b7b85bb56.zip', 2, '2023-01-02 04:53:44', '2023-01-30 01:54:19', '2023-01-30 01:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_statuses`
--

CREATE TABLE `support_ticket_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `support_ticket_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_ticket_statuses`
--

INSERT INTO `support_ticket_statuses` (`id`, `support_ticket_status`, `created_at`, `updated_at`) VALUES
(1, 'active', '2022-06-25 03:52:18', '2022-11-01 05:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `language_id`, `image`, `name`, `occupation`, `comment`, `created_at`, `updated_at`) VALUES
(5, 9, '62e27b16b9aae.jpg', 'توماس زينكس', 'رجل اعمال', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '2022-01-01 23:18:57', '2022-08-01 03:52:43'),
(6, 9, '621c9a6474879.png', 'فلان الفلاني', 'باني', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '2022-01-01 23:19:58', '2022-08-01 03:53:34'),
(7, 9, '62246762d0cf6.png', 'اشلي هيرت', 'عامل', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '2022-01-01 23:20:11', '2022-08-01 03:53:55'),
(13, 8, '62e7a1af39822.jpg', 'Tomas Zinks', 'Businessman', 'Duis leo. Sed fringilla mauris sit amibh. Donec sodales sagittis magna. Sed consequat goos services.', '2022-03-06 01:45:48', '2022-08-01 03:49:35'),
(14, 8, '62249ae91459e.png', 'John Doe', 'Builder', 'Duis leo. Sed fringilla mauris sit amibh. Donec sodales sagittis magna. Sed consequat goos services.', '2022-03-06 01:46:33', '2022-08-01 03:50:04'),
(15, 8, '62249adfe34e9.png', 'Ashley Hurt', 'Worker', 'Duis leo. Sed fringilla mauris sit amibh. Donec sodales sagittis magna. Sed consequat goos services.', '2022-03-06 01:47:07', '2022-08-01 03:50:32'),
(16, 9, '62e27b0c3d26d.jpg', 'البظر المصب', 'عميل التأجير', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '2022-07-27 00:51:54', '2022-08-01 03:54:18'),
(17, 9, '62e27af68f5fc.jpg', 'دونالد جيمس', 'عميل التأجير', 'علي الجانب الآخر نشجب ونستنكر هؤلاء الرجال المفتونون بنشوة اللحظة الهائمون في رغباتهم فلا يدركون ما يعقبها من الألم والأسي المحتم، واللوم كذلك يشمل هؤلاء الذين أخفقوا في واجباتهم نتيجة لضعف إرادتهم فيتساوي مع هؤلاء الذين يتجنبون وينأون عن تحمل الكدح والألم .', '2022-07-27 00:52:18', '2022-08-01 03:54:40'),
(18, 8, '62e7a20e31e53.jpg', 'Mohona Clitar', 'Rental Customer', 'Duis leo. Sed fringilla mauris sit amibh. Donec sodales sagittis magna. Sed consequat goos services.', '2022-08-01 03:51:10', '2022-08-01 03:51:10'),
(19, 8, '62e7a22bd2e29.jpg', 'Donald James', 'Rental Customer', 'Duis leo. Sed fringilla mauris sit amibh. Donec sodales sagittis magna. Sed consequat goos services.', '2022-08-01 03:51:39', '2022-08-01 03:51:39');

-- --------------------------------------------------------

--
-- Table structure for table `testimonial_sections`
--

CREATE TABLE `testimonial_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonial_sections`
--

INSERT INTO `testimonial_sections` (`id`, `language_id`, `subtitle`, `title`, `created_at`, `updated_at`) VALUES
(2, 8, 'Client Feedback', 'What Client Say About Us', '2021-12-30 03:54:24', '2021-12-30 03:55:17'),
(3, 9, 'ملاحظات العملاء', 'ماذا يقول العميل عنا', '2022-03-06 00:35:29', '2022-03-06 00:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `transcations`
--

CREATE TABLE `transcations` (
  `id` bigint UNSIGNED NOT NULL,
  `transcation_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` bigint DEFAULT NULL,
  `transcation_type` int DEFAULT NULL COMMENT '1=Equipment Booking, 2=Withdraw, 3=  balance add, 4 = balance subtract 5 = product order',
  `user_id` bigint DEFAULT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `payment_status` int NOT NULL DEFAULT '0',
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grand_total` double(8,2) DEFAULT NULL,
  `shipping_charge` double(8,2) DEFAULT '0.00',
  `commission` double(8,2) DEFAULT '0.00',
  `tax` double(8,2) DEFAULT '0.00',
  `pre_balance` float(8,2) DEFAULT '0.00',
  `after_balance` float(8,2) DEFAULT '0.00',
  `gateway_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `security_deposit` double(8,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transcations`
--

INSERT INTO `transcations` (`id`, `transcation_id`, `booking_id`, `transcation_type`, `user_id`, `vendor_id`, `payment_status`, `payment_method`, `grand_total`, `shipping_charge`, `commission`, `tax`, `pre_balance`, `after_balance`, `gateway_type`, `currency_symbol`, `currency_symbol_position`, `created_at`, `updated_at`, `security_deposit`) VALUES
(1, '1686807534', 21, 1, 23, 23, 1, 'PayPal', 298.80, NULL, 33.20, 33.20, 0.00, 298.80, 'online', '$', 'left', '2023-06-14 23:38:54', '2023-06-14 23:38:54', 121.00),
(2, '1686807618', 22, 1, 23, NULL, 1, 'PayPal', 144.00, NULL, 144.00, 14.40, NULL, NULL, 'online', '$', 'left', '2023-06-14 23:40:18', '2023-06-14 23:40:18', 10.00),
(3, '1686808176', 23, 1, 23, 23, 1, 'Citibank', 298.80, NULL, 33.20, 33.20, 298.80, 597.60, 'offline', '$', 'left', '2023-06-14 23:49:36', '2023-06-14 23:49:36', 121.00),
(4, '1686808921', 24, 1, 23, 23, 1, 'Bank of America', 298.80, NULL, 33.20, 33.20, 597.60, 896.40, 'offline', '$', 'left', '2023-06-15 00:02:01', '2023-06-15 00:02:01', 121.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 -> banned or deactive, 1 -> active',
  `verification_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `image`, `username`, `email`, `email_verified_at`, `password`, `contact_number`, `address`, `city`, `state`, `country`, `status`, `verification_token`, `remember_token`, `provider`, `provider_id`, `created_at`, `updated_at`) VALUES
(15, 'Robert', 'Fonseca', '62e10a8cdf479.png', 'robert', 'robert@gmail.com', '2022-07-27 03:47:20', '$2y$10$Vety8F2h2O/XjKKCTLuwHua0MKTG78g4vcc1jaZmO8SHuqhrNM/jy', '715-396-9284', '1470 King St', 'Bellingham', 'Washington', 'United States', 1, '1bf935818b82cb473784a71309d75179', NULL, NULL, NULL, '2022-07-27 03:46:29', '2022-08-02 03:03:02'),
(22, 'Alisa', 'Meyer', '63b14e2771eab.png', 'lasukyryju', 'pacyvumek@mailinator.com', '2023-01-01 03:09:04', '$2y$10$wD3hcKOMBuJ3.sy.8W488OdzNjjDJ6Qd0DnV32YHQVDB4DWv6AHdC', '+917428730894', 'Alaska, United States of America', 'Juneau', 'Alaska', 'United States of America', 1, '0d7bd556e4378c7ae4b3d89c782fe333', NULL, NULL, NULL, '2023-01-01 03:08:51', '2023-01-01 03:11:03'),
(23, 'Example', 'User', '63b14ea6924bf.png', 'exampleuser01', 'fahadahmadshemul@gmail.com', '2023-01-01 03:12:06', '$2y$10$f.exAkhuq8MHA/rQMKTxN.aPgQ6F5ortdAEfrjwQLYonsUZcDlfFi', '+1-202-555-0181', 'California, United States of America', 'Los Angeles', 'California', 'United States of America', 1, 'a08ecc582ddb05e492ddf125ac32dd1a', NULL, NULL, NULL, '2023-01-01 03:11:53', '2023-01-01 23:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `amount` double(8,2) DEFAULT '0.00',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `facebook` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `self_pickup_status` tinyint NOT NULL DEFAULT '1',
  `two_way_delivery_status` tinyint NOT NULL DEFAULT '1',
  `avg_rating` float(8,2) NOT NULL DEFAULT '0.00',
  `show_email_addresss` tinyint NOT NULL DEFAULT '1',
  `show_phone_number` tinyint NOT NULL DEFAULT '1',
  `show_contact_form` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `photo`, `email`, `phone`, `username`, `password`, `status`, `amount`, `email_verified_at`, `facebook`, `twitter`, `linkedin`, `self_pickup_status`, `two_way_delivery_status`, `avg_rating`, `show_email_addresss`, `show_phone_number`, `show_contact_form`, `created_at`, `updated_at`) VALUES
(23, '63b13236dda38.png', 'megasoft.envato@gmail.com', '013921380912', 'jonedoe832', '$2y$10$mzthmsEv7Qy1gRMwof95T.1YweEw1Z6bipMgdjU0H2wD5qmX1lKBS', 1, 896.40, '2022-12-18 06:02:20', NULL, NULL, NULL, 1, 1, 4.33, 1, 1, 1, '2022-12-18 06:02:02', '2023-06-15 00:02:01'),
(25, '63b1347d85f0a.png', 'bazis@mailinator.com', '+1 (227) 377-8769', 'raysin0089', '$2y$10$5/H5PMZl4MqW1wo5XAodEuvvqMaTtqhhihcGfNzYh1SM5hUO.mm8.', 1, 0.00, '2023-01-01 01:56:29', NULL, NULL, NULL, 1, 1, 3.50, 1, 1, 1, '2023-01-01 01:21:33', '2023-01-02 00:28:07'),
(26, '63b13592b219f.png', 'giwama@mailinator.com', '+1 (993) 454-1213', 'jackaranda738', '$2y$10$2K2hf4xO.Bz.h/EMDEx1SODMLnNJe/RnSGXz.dJVNG0xWKJ2oorN6', 1, 0.00, '2023-01-01 01:56:27', NULL, NULL, NULL, 1, 1, 4.50, 1, 1, 1, '2023-01-01 01:26:10', '2023-06-05 23:25:43'),
(27, '63b1363ed7e50.png', 'tytehuhupa@mailinator.com', '+1 (245) 331-7182', 'ritabook638', '$2y$10$.lMjrQzLxfjyKnl7fajnY.pieCpKcsiAODYPtYcMYHJeNg4mXcY2G', 1, 0.00, '2023-01-01 01:56:26', NULL, NULL, NULL, 1, 1, 0.00, 1, 1, 1, '2023-01-01 01:29:02', '2023-01-01 03:47:14'),
(28, '63b137877105e.png', 'zihuqox@mailinator.com', '+1 (991) 542-2231', 'Anitaaath12', '$2y$10$09pf1qS6RXxq62VJoNBwkOkoWeyrEtz.EH3NLvF0ZeN1FQU.ctRoe', 1, 0.00, '2023-01-01 01:56:25', NULL, NULL, NULL, 1, 1, 0.00, 1, 1, 1, '2023-01-01 01:34:31', '2023-01-01 03:49:38'),
(29, '63b13a69dea16.png', 'vilolus@mailinator.com', '+1 (198) 403-9346', 'daveallippa', '$2y$10$hrITvxvyvVKh5P63FeEkce1M/PAkQrseQiqIJE04rz0SaRXDBsPOq', 1, 0.00, '2023-01-01 01:56:23', NULL, NULL, NULL, 1, 1, 0.00, 1, 1, 1, '2023-01-01 01:42:42', '2023-01-01 03:51:58'),
(30, '63b13c8e173f5.png', 'bisulagos@mailinator.com', '+1 (484) 508-9384', 'clydestale637', '$2y$10$vcgB484ITF3HtqIEd.b/vOvCGr/3QY62XqqYdktSnxteI5itT1usu', 1, 0.00, '2023-01-01 01:56:22', NULL, NULL, NULL, 1, 1, 0.00, 1, 1, 1, '2023-01-01 01:55:58', '2023-01-01 03:53:59'),
(31, '63b13d6578c37.png', 'mofimyvo@mailinator.com', '+1 (196) 803-6314', 'lauranorda21', '$2y$10$Wpod.sx9vurryluGQ95FBu6j9BAR3yfkH0pHkZz5fCKadG4xFZR1S', 1, 0.00, NULL, NULL, NULL, NULL, 1, 1, 0.00, 1, 1, 1, '2023-01-01 01:59:33', '2023-01-01 03:56:19'),
(32, '63b13e84c8fdc.png', 'vesyfawu@mailinator.com', '+1 (995) 235-1365', 'rosebush092', '$2y$10$7KuvC0iJv04cHa3yBW3.j.QGOeIUFHod5Bpqu42Enie0ihx8LWzhS', 1, 0.00, NULL, NULL, NULL, NULL, 1, 1, 0.00, 1, 1, 1, '2023-01-01 02:04:20', '2023-01-01 03:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_infos`
--

CREATE TABLE `vendor_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `language_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_infos`
--

INSERT INTO `vendor_infos` (`id`, `vendor_id`, `language_id`, `name`, `shop_name`, `country`, `city`, `state`, `zip_code`, `address`, `details`, `created_at`, `updated_at`) VALUES
(10, 23, 8, 'Jhone Doe', 'Business Equipment Rentals', 'United States of America', 'Trenton', 'New Jersey', '2131', 'New Jersey,  United States of America', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita incidunt quam cumque reiciendis autem. Nobis repellat officia, velit vel cupiditate ut quaerat fugiat, tempore consequatur reprehenderit quod, consectetur odit enim.', '2022-12-18 00:02:02', '2022-12-31 22:25:19'),
(11, 23, 9, 'فلان الفلاني', 'تأجير معدات الأعمال', 'الولايات المتحدة الأمريكية', 'ترينتون', 'نيو جيرسي', '2131', 'نيو جيرسي ، الولايات المتحدة الأمريكية', 'في الصفحة التي يقرأها. ولذلك يتم استخدام طريقة لوريم إيبسوم لأنها تعطي توزيعاَ طبيعياَ -إلى حد ما- للأحرف عوضاً عن استخدام \"هنا يوجد محتوى نصي، هنا يوجد محتوى نصي\" فتجعلها تبدو (أي الأحرف) وكأنها نص مقروء. العديد من برامح النشر المكتبي وبرامح تحرير صفحات الويب تستخدم لوريم إيبسوم بشكل إفتراضي كنموذج عن النص، وإذا قمت بإدخ', '2022-12-18 00:12:44', '2022-12-31 22:25:19'),
(14, 25, 8, 'Ray Sin', 'Copier Lease, Rental, Repair', 'United States', 'New York', NULL, '19387', 'New York, United States', 'Lorem Ipsum is dummied text of the printing and typesetting industry.\r\n Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.', '2022-12-31 19:21:33', '2022-12-31 21:45:15'),
(15, 25, 9, 'راي سين', 'إيجار آلة تصوير ، إيجار ، إصلاح', 'الولايات المتحدة', 'نيويورك', NULL, '19387', 'نيويورك ، الولايات المتحدة', 'طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز \r\nعلى الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. ولذلك ي', '2022-12-31 19:21:33', '2022-12-31 21:45:15'),
(16, 26, 8, 'Jack Aranda', 'CityWide  Solutions', 'United States', 'Los Angeles.', NULL, '76362', 'Los Angeles, United States', 'Lorem Ipsum is dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s', '2022-12-31 19:26:10', '2022-12-31 21:44:16'),
(17, 26, 9, 'جاك أراندا', 'حلول سيتي وايد', 'الولايات المتحدة', 'لوس أنجلوس', NULL, '76362', 'لوس أنجلوس ، الولايات المتحدة', 'طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على ا\r\nلشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. ولذلك ي', '2022-12-31 19:26:10', '2022-12-31 21:44:16'),
(18, 27, 8, 'Rita Book', 'EliteCrew', 'United States', 'Atlanta', 'North Carolina', '42907', 'Atlanta, United States', 'Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web', '2022-12-31 19:29:02', '2022-12-31 21:47:14'),
(19, 27, 9, 'كتاب ريتا', 'طاقم النخبة', 'الولايات المتحدة', 'أتلانتا', NULL, '42907', 'أتلانتا ، الولايات المتحدة', 'الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال\r\n المعيار للنص الشكلي منذ القرن الخامس عشر عندما قامت مطبعة مجهو', '2022-12-31 19:29:02', '2022-12-31 21:47:14'),
(20, 28, 8, 'Anita Bath', 'TopCrown', 'United States', 'New York City', NULL, '21737', 'New York City, United States', 'Lorem Ipsum is that it has a more-or-less normal distribution of letters, \r\nas opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web', '2022-12-31 19:34:31', '2022-12-31 21:49:38'),
(21, 28, 9, 'أنيتا باث', 'توب كرون', 'الولايات المتحدة', 'مدينة نيويورك', NULL, '21737', 'مدينة نيويورك ، الولايات المتحدة', 'ر إدخال بعض النوادر أو الكلمات العشوائية إلى النص. إن كنت تريد أن تستخدم نص لوريم إيبسوم ما، عليك أن تتحقق أولاً\r\n أن ليس هناك أي كلمات أو عبارات محرجة أو غير لائقة مخبأة في هذا النص. بينما تعمل جميع مولّدات نصوص لوري', '2022-12-31 19:34:31', '2022-12-31 21:49:38'),
(22, 29, 8, 'Dave Allippa', 'Soulberry Rental', 'United States of America', 'Charlotte', 'North Carolina', '58670', 'Charlotte, United States of America', 'Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here,\r\n content here\', making it look like readable English. Many desktop publishing packages and web', '2022-12-31 19:42:42', '2022-12-31 21:51:58'),
(23, 29, 9, 'ديف أليبا', 'تأجير سولبيري', 'الولايات المتحدة الأمريكية', 'شارلوت', 'شمال كارولينا', '58670', 'شارلوت ، الولايات المتحدة الأمريكية', 'عات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر عندما قامت مطبعة \r\nمجهولة برص مجموعة من الأحرف بشكل عشوائي أخذتها من نص، لتكوّن كتيّب بمثابة دليل أو مرجع شكلي ل', '2022-12-31 19:46:49', '2022-12-31 21:51:58'),
(24, 30, 8, 'Clyde Stale', 'MainSquare', 'United States of America', 'Richmond', NULL, '35686', 'Richmond, United States of America', 'There are many variations of passages of Lorem Ipsum available,\r\n but the majority have suffered alteration in some form, by injected humor, \r\nor randomized words which don\'t look even slightly believable.', '2022-12-31 19:55:58', '2022-12-31 21:53:59'),
(25, 30, 9, 'كلايد ستيل', 'الميدان الرئيسي', 'الولايات المتحدة الأمريكية', 'ريتشموند', NULL, '35686', 'ريتشموند ، الولايات المتحدة الأمريكية', 'ا بشكل ما عبر إدخال بعض النوادر أو الكلمات العشوائية إلى النص. إن كنت تريد أن تستخدم نص لوريم إيبسوم ما، \r\nعليك أن تتحقق أولاً أن ليس هناك أي كلمات أو عبارات محرجة أو غير لائقة مخبأة في هذا النص. بينما تعمل جميع مولّدات نصوص لوريم إيبسوم على الإن', '2022-12-31 19:55:58', '2022-12-31 21:53:59'),
(26, 31, 8, 'Laura Norda', 'Urban Fresh Rental', 'United States of America', 'Olympia', NULL, '90455', 'Olympia, United States of America', 'consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theo', '2022-12-31 19:59:33', '2022-12-31 21:56:19'),
(27, 31, 9, 'لورا نوردا', 'تأجير المناطق الحضرية الطازجة', 'الولايات المتحدة الأمريكية', 'أولمبيا', NULL, '90455', 'أولمبيا ، الولايات المتحدة الأمريكية', 'ز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. ولذلك يتم استخدام طريقة لوريم إيبسوم لأنها تعطي توزيعاَ طبيعياَ -إلى حد ما- للأحرف عوضاً عن استخدام \"هنا يوجد محتوى نصي، هنا يوجد محتوى نصي\" فتجعلها تبدو (أي الأحرف) وكأنها نص مقروء. العديد من برامح النشر المكتبي وبرامح تحرير صفحات الويب تستخدم', '2022-12-31 19:59:33', '2022-12-31 21:56:19'),
(28, 32, 8, 'Rose Bush', 'DreamFiesta', 'United States of America', 'Austin', NULL, '97344', 'Austin, United States of America', 'it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset', '2022-12-31 20:04:20', '2022-12-31 21:59:19'),
(29, 32, 9, 'روز بوش', 'دريمفيستا', 'الولايات المتحدة الأمريكية', 'أوستين', NULL, '97344', 'أوستن ، الولايات المتحدة الأمريكية', '(بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر عندما قامت مطبعة مجهولة برص مجموعة من الأحرف بشكل عشوائي أخذتها من نص، لتكوّن كتيّب بم', '2022-12-31 20:04:20', '2022-12-31 21:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_reviews`
--

CREATE TABLE `vendor_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `rating` double(8,2) DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_reviews`
--

INSERT INTO `vendor_reviews` (`id`, `user_id`, `vendor_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 16, 7, 3.00, 'fsdafaf', '2022-10-15 23:29:02', '2022-10-20 23:29:02'),
(2, 15, 7, 4.00, 'Drag, drop, and mix differentro parts to quickly build your landingp in a matter of minutes. Let us knowos your thoughts or', '2022-10-11 18:00:00', '2022-10-22 00:02:05');

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `withdraw_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_id` int DEFAULT NULL,
  `amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payable_amount` float(8,2) NOT NULL DEFAULT '0.00',
  `total_charge` float(8,2) NOT NULL DEFAULT '0.00',
  `additional_reference` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `feilds` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraws`
--

INSERT INTO `withdraws` (`id`, `vendor_id`, `withdraw_id`, `method_id`, `amount`, `payable_amount`, `total_charge`, `additional_reference`, `feilds`, `status`, `created_at`, `updated_at`) VALUES
(25, 23, '647f1b693b287', 2, '100', 85.50, 14.50, 'N/A', '{\"Account_No\":\"09812039821093\"}', 2, '2023-06-06 05:41:29', '2023-06-06 05:42:21'),
(26, 23, '6480244fe1f8c', 2, '100', 85.50, 14.50, 'N/A', '{\"Account_No\":\"09812039821093\"}', 2, '2023-06-07 00:31:43', '2023-06-07 00:33:29'),
(27, 23, '648024cd52930', 2, '100', 85.50, 14.50, 'N/A', '{\"Account_No\":\"09812039821093\"}', 1, '2023-06-07 00:33:49', '2023-06-07 00:34:22'),
(28, 23, '648024ff517ec', 2, '200', 175.50, 24.50, 'N/A', '{\"Account_No\":\"09812039821093\"}', 2, '2023-06-07 00:34:39', '2023-06-07 00:35:08'),
(29, 23, '64802534b96dc', 2, '50', 40.50, 9.50, 'N/A', '{\"Account_No\":\"09812039821093\"}', 0, '2023-06-07 00:35:32', '2023-06-07 00:35:32');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_method_inputs`
--

CREATE TABLE `withdraw_method_inputs` (
  `id` bigint UNSIGNED NOT NULL,
  `withdraw_payment_method_id` bigint DEFAULT NULL,
  `type` tinyint DEFAULT NULL COMMENT '1-text, 2-select, 3-checkbox, 4-textarea, 5-datepicker, 6-timepicker, 7-number',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required` tinyint NOT NULL DEFAULT '0' COMMENT '1-required, 0- optional',
  `order_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraw_method_inputs`
--

INSERT INTO `withdraw_method_inputs` (`id`, `withdraw_payment_method_id`, `type`, `label`, `name`, `placeholder`, `required`, `order_number`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Account No', 'Account_No', 'Enter Account Number', 1, 1, '2022-10-19 23:15:38', '2022-10-19 23:15:38'),
(2, 3, 1, 'Email', 'Email', 'Enter Perfect Money Email', 1, 1, '2022-10-31 23:48:02', '2022-10-31 23:48:02'),
(3, 4, 1, 'Email', 'Email', 'Enter Perfect Money Email', 1, 1, '2022-12-12 01:03:06', '2022-12-12 01:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_method_options`
--

CREATE TABLE `withdraw_method_options` (
  `id` bigint UNSIGNED NOT NULL,
  `withdraw_method_input_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_payment_methods`
--

CREATE TABLE `withdraw_payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `min_limit` double(8,2) DEFAULT NULL,
  `max_limit` double(8,2) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `fixed_charge` float(8,2) DEFAULT '0.00',
  `percentage_charge` float(8,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraw_payment_methods`
--

INSERT INTO `withdraw_payment_methods` (`id`, `min_limit`, `max_limit`, `name`, `status`, `fixed_charge`, `percentage_charge`, `created_at`, `updated_at`) VALUES
(2, 50.00, 1000.00, 'Bkash', 1, 5.00, 10.00, '2022-10-19 23:15:16', '2022-10-31 23:02:10'),
(4, 212.00, 2212.00, 'Perfect Money', 1, 21.00, 12.00, '2022-12-12 00:59:39', '2022-12-12 00:59:39');

-- --------------------------------------------------------

--
-- Table structure for table `work_processes`
--

CREATE TABLE `work_processes` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_processes`
--

INSERT INTO `work_processes` (`id`, `language_id`, `icon`, `serial_number`, `title`, `created_at`, `updated_at`) VALUES
(3, 9, 'fas fa-search', 1, 'ابحث عن معداتك', '2021-12-09 02:06:57', '2022-08-01 03:30:00'),
(4, 9, 'fas fa-qrcode', 2, 'قارن اختيارك', '2021-12-09 02:09:04', '2022-08-01 03:30:13'),
(5, 9, 'fas fa-truck', 3, 'حجز المعدات', '2021-12-09 02:12:00', '2022-08-01 03:30:25'),
(6, 9, 'fas fa-chart-line', 4, 'ابدأ مشروعك', '2021-12-09 02:14:21', '2022-08-01 03:30:39'),
(7, 8, 'fas fa-search', 1, 'Search Your Equipment', '2022-03-06 00:26:48', '2022-08-01 03:27:33'),
(8, 8, 'fas fa-qrcode', 2, 'Compare Your Selection', '2022-03-06 00:27:58', '2022-08-01 03:27:49'),
(9, 8, 'fas fa-truck', 3, 'Reserve The Equipment', '2022-03-06 00:28:30', '2022-08-01 03:28:05'),
(10, 8, 'fas fa-chart-line', 4, 'Get Start Your Project', '2022-03-06 00:29:11', '2022-08-01 03:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `work_process_sections`
--

CREATE TABLE `work_process_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_process_sections`
--

INSERT INTO `work_process_sections` (`id`, `language_id`, `subtitle`, `title`, `text`, `created_at`, `updated_at`) VALUES
(1, 9, 'آلية العمل', 'احصل على إيجاراتك في 4 خطوات سهلة', 'إنها طرية للغاية ولا تترك أي ألم. يحب أن يعتني بأسرته. سوف تؤتي هذه العملية ثمارها.', '2021-12-08 23:03:46', '2022-08-01 03:29:44'),
(2, 8, 'Work Process', 'Get Your Rentals in Easy 4 Steps', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', '2022-03-06 00:26:05', '2022-08-01 03:27:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_sections`
--
ALTER TABLE `about_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD KEY `admins_role_id_foreign` (`role_id`);

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basic_settings`
--
ALTER TABLE `basic_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_categories_language_id_foreign` (`language_id`);

--
-- Indexes for table `blog_informations`
--
ALTER TABLE `blog_informations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_informations_language_id_foreign` (`language_id`),
  ADD KEY `blog_informations_blog_category_id_foreign` (`blog_category_id`),
  ADD KEY `blog_informations_blog_id_foreign` (`blog_id`);

--
-- Indexes for table `blog_sections`
--
ALTER TABLE `blog_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `call_to_action_sections`
--
ALTER TABLE `call_to_action_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cookie_alerts`
--
ALTER TABLE `cookie_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cookie_alerts_language_id_foreign` (`language_id`);

--
-- Indexes for table `counter_informations`
--
ALTER TABLE `counter_informations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `earnings`
--
ALTER TABLE `earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment_bookings`
--
ALTER TABLE `equipment_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_bookings_equipment_id_foreign` (`equipment_id`),
  ADD KEY `equipment_bookings_user_id_foreign` (`user_id`);

--
-- Indexes for table `equipment_categories`
--
ALTER TABLE `equipment_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_categories_language_id_foreign` (`language_id`);

--
-- Indexes for table `equipment_contents`
--
ALTER TABLE `equipment_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment_coupons`
--
ALTER TABLE `equipment_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment_locations`
--
ALTER TABLE `equipment_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment_reviews`
--
ALTER TABLE `equipment_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_reviews_user_id_foreign` (`user_id`),
  ADD KEY `equipment_reviews_equipment_id_foreign` (`equipment_id`);

--
-- Indexes for table `equipment_sections`
--
ALTER TABLE `equipment_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_language_id_foreign` (`language_id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feature_sections`
--
ALTER TABLE `feature_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_contents`
--
ALTER TABLE `footer_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `footer_texts_language_id_foreign` (`language_id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_builders`
--
ALTER TABLE `menu_builders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offline_gateways`
--
ALTER TABLE `offline_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `online_gateways`
--
ALTER TABLE `online_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_contents`
--
ALTER TABLE `page_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_contents_language_id_foreign` (`language_id`),
  ADD KEY `page_contents_page_id_foreign` (`page_id`);

--
-- Indexes for table `page_headings`
--
ALTER TABLE `page_headings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_headings_language_id_foreign` (`language_id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `popups`
--
ALTER TABLE `popups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `popups_language_id_foreign` (`language_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_categories_language_id_foreign` (`language_id`);

--
-- Indexes for table `product_contents`
--
ALTER TABLE `product_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_contents_language_id_foreign` (`language_id`),
  ADD KEY `product_contents_product_category_id_foreign` (`product_category_id`),
  ADD KEY `product_contents_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_coupons`
--
ALTER TABLE `product_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_orders`
--
ALTER TABLE `product_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `product_purchase_items`
--
ALTER TABLE `product_purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_purchase_items_product_order_id_foreign` (`product_order_id`),
  ADD KEY `product_purchase_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_reviews_user_id_foreign` (`user_id`),
  ADD KEY `product_reviews_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_sections`
--
ALTER TABLE `product_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_shipping_charges`
--
ALTER TABLE `product_shipping_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_charges_language_id_foreign` (`language_id`);

--
-- Indexes for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `push_subscriptions_endpoint_unique` (`endpoint`),
  ADD KEY `push_subscriptions_subscribable_type_subscribable_id_index` (`subscribable_type`,`subscribable_id`);

--
-- Indexes for table `quick_links`
--
ALTER TABLE `quick_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quick_links_language_id_foreign` (`language_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security_deposit_refunds`
--
ALTER TABLE `security_deposit_refunds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seos`
--
ALTER TABLE `seos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seos_language_id_foreign` (`language_id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_medias`
--
ALTER TABLE `social_medias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `static_sections`
--
ALTER TABLE `static_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscribers_email_id_unique` (`email_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_ticket_statuses`
--
ALTER TABLE `support_ticket_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonial_sections`
--
ALTER TABLE `testimonial_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transcations`
--
ALTER TABLE `transcations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_infos`
--
ALTER TABLE `vendor_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_reviews`
--
ALTER TABLE `vendor_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_method_inputs`
--
ALTER TABLE `withdraw_method_inputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_method_options`
--
ALTER TABLE `withdraw_method_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_payment_methods`
--
ALTER TABLE `withdraw_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_processes`
--
ALTER TABLE `work_processes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_process_sections`
--
ALTER TABLE `work_process_sections`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_sections`
--
ALTER TABLE `about_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `basic_settings`
--
ALTER TABLE `basic_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `blog_informations`
--
ALTER TABLE `blog_informations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `blog_sections`
--
ALTER TABLE `blog_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `call_to_action_sections`
--
ALTER TABLE `call_to_action_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cookie_alerts`
--
ALTER TABLE `cookie_alerts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `counter_informations`
--
ALTER TABLE `counter_informations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `earnings`
--
ALTER TABLE `earnings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `equipment_bookings`
--
ALTER TABLE `equipment_bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `equipment_categories`
--
ALTER TABLE `equipment_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `equipment_contents`
--
ALTER TABLE `equipment_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `equipment_coupons`
--
ALTER TABLE `equipment_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `equipment_locations`
--
ALTER TABLE `equipment_locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `equipment_reviews`
--
ALTER TABLE `equipment_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `equipment_sections`
--
ALTER TABLE `equipment_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `feature_sections`
--
ALTER TABLE `feature_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `footer_contents`
--
ALTER TABLE `footer_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_builders`
--
ALTER TABLE `menu_builders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `offline_gateways`
--
ALTER TABLE `offline_gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `online_gateways`
--
ALTER TABLE `online_gateways`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `page_contents`
--
ALTER TABLE `page_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `page_headings`
--
ALTER TABLE `page_headings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `popups`
--
ALTER TABLE `popups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `product_contents`
--
ALTER TABLE `product_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `product_coupons`
--
ALTER TABLE `product_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_orders`
--
ALTER TABLE `product_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `product_purchase_items`
--
ALTER TABLE `product_purchase_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=299;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_sections`
--
ALTER TABLE `product_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_shipping_charges`
--
ALTER TABLE `product_shipping_charges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `quick_links`
--
ALTER TABLE `quick_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `security_deposit_refunds`
--
ALTER TABLE `security_deposit_refunds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `seos`
--
ALTER TABLE `seos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `social_medias`
--
ALTER TABLE `social_medias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `static_sections`
--
ALTER TABLE `static_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `support_ticket_statuses`
--
ALTER TABLE `support_ticket_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `testimonial_sections`
--
ALTER TABLE `testimonial_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transcations`
--
ALTER TABLE `transcations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `vendor_infos`
--
ALTER TABLE `vendor_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `vendor_reviews`
--
ALTER TABLE `vendor_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `withdraw_method_inputs`
--
ALTER TABLE `withdraw_method_inputs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `withdraw_method_options`
--
ALTER TABLE `withdraw_method_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_payment_methods`
--
ALTER TABLE `withdraw_payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `work_processes`
--
ALTER TABLE `work_processes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `work_process_sections`
--
ALTER TABLE `work_process_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD CONSTRAINT `blog_categories_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_informations`
--
ALTER TABLE `blog_informations`
  ADD CONSTRAINT `blog_informations_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_informations_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_informations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cookie_alerts`
--
ALTER TABLE `cookie_alerts`
  ADD CONSTRAINT `cookie_alerts_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment_bookings`
--
ALTER TABLE `equipment_bookings`
  ADD CONSTRAINT `equipment_bookings_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipment_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment_categories`
--
ALTER TABLE `equipment_categories`
  ADD CONSTRAINT `equipment_categories_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment_reviews`
--
ALTER TABLE `equipment_reviews`
  ADD CONSTRAINT `equipment_reviews_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipment_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `footer_contents`
--
ALTER TABLE `footer_contents`
  ADD CONSTRAINT `footer_texts_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `page_contents`
--
ALTER TABLE `page_contents`
  ADD CONSTRAINT `page_contents_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `page_contents_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `popups`
--
ALTER TABLE `popups`
  ADD CONSTRAINT `popups_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_contents`
--
ALTER TABLE `product_contents`
  ADD CONSTRAINT `product_contents_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_contents_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_contents_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_orders`
--
ALTER TABLE `product_orders`
  ADD CONSTRAINT `product_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_purchase_items`
--
ALTER TABLE `product_purchase_items`
  ADD CONSTRAINT `product_purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_purchase_items_product_order_id_foreign` FOREIGN KEY (`product_order_id`) REFERENCES `product_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_shipping_charges`
--
ALTER TABLE `product_shipping_charges`
  ADD CONSTRAINT `shipping_charges_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quick_links`
--
ALTER TABLE `quick_links`
  ADD CONSTRAINT `quick_links_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seos`
--
ALTER TABLE `seos`
  ADD CONSTRAINT `seos_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
