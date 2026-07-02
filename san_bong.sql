-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 02, 2026 lúc 05:52 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `san_bong`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `football_field_id` bigint(20) UNSIGNED NOT NULL,
  `time_slot_id` bigint(20) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `slot_lock_key` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `football_field_id`, `time_slot_id`, `booking_date`, `total_price`, `status`, `note`, `slot_lock_key`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 3, '2026-07-03', 570000.00, 'confirmed', 'Dữ liệu mẫu phục vụ demo đồ án.', '1|2026-07-03|3', '2026-06-28 16:30:39', '2026-06-28 16:30:39'),
(2, 4, 2, 5, '2026-07-05', 600000.00, 'pending', 'Dữ liệu mẫu phục vụ demo đồ án.', '2|2026-07-05|5', '2026-06-28 16:30:39', '2026-06-28 16:30:39'),
(3, 5, 3, 4, '2026-06-23', 675000.00, 'completed', 'Dữ liệu mẫu phục vụ demo đồ án.', NULL, '2026-06-28 16:30:39', '2026-06-28 16:30:39'),
(4, 6, 4, 2, '2026-06-16', 750000.00, 'cancelled', 'Dữ liệu mẫu phục vụ demo đồ án.', NULL, '2026-06-28 16:30:39', '2026-06-28 16:30:39'),
(5, 2, 5, 7, '2026-07-08', 675000.00, 'confirmed', 'Dữ liệu mẫu phục vụ demo đồ án.', '5|2026-07-08|7', '2026-06-28 16:30:39', '2026-06-28 16:30:39'),
(6, 3, 2, 1, '2026-06-29', 600000.00, 'confirmed', NULL, '2|2026-06-29|1', '2026-06-28 16:45:22', '2026-06-28 16:46:12'),
(7, 2, 1, 8, '2026-06-30', 570000.00, 'confirmed', 'fdgdfgdfssgdfgfd', '1|2026-06-30|8', '2026-06-28 20:27:59', '2026-06-28 20:28:36'),
(8, 7, 7, 2, '2026-06-30', 600000.00, 'cancelled', 'hihi', NULL, '2026-06-28 20:41:59', '2026-06-28 20:42:34'),
(9, 7, 7, 2, '2026-06-30', 600000.00, 'pending', NULL, '7|2026-06-30|2', '2026-06-29 05:02:14', '2026-06-29 05:02:14'),
(10, 7, 2, 1, '2026-07-03', 600000.00, 'pending', NULL, '2|2026-07-03|1', '2026-07-02 10:56:17', '2026-07-02 10:56:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
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
-- Cấu trúc bảng cho bảng `field_images`
--

CREATE TABLE `field_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `football_field_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `field_images`
--

INSERT INTO `field_images` (`id`, `football_field_id`, `image_url`, `is_main`, `created_at`, `updated_at`) VALUES
(3, 2, '/images/field-2.svg', 0, '2026-06-28 16:30:38', '2026-06-29 04:56:17'),
(4, 2, '/images/field-detail.svg', 0, '2026-06-28 16:30:38', '2026-06-29 04:56:17'),
(5, 3, '/images/field-3.svg', 0, '2026-06-28 16:30:38', '2026-06-29 04:56:33'),
(6, 3, '/images/field-detail.svg', 0, '2026-06-28 16:30:38', '2026-06-29 04:56:33'),
(7, 4, '/images/field-4.svg', 1, '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(8, 4, '/images/field-detail.svg', 0, '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(9, 5, '/images/field-5.svg', 1, '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(10, 5, '/images/field-detail.svg', 0, '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(11, 6, '/images/field-6.svg', 1, '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(12, 6, '/images/field-detail.svg', 0, '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(14, 7, 'fields/0NIQwP1W461uLWVSZw8sEBWxWVKWD7UQmThzpvcW.png', 1, '2026-06-28 16:49:44', '2026-06-28 16:49:44'),
(15, 1, 'fields/VCRyAJLDQFe97BSHaAtYOkw60kP3Gbl9zr7Dmtj3.jpg', 1, '2026-06-29 04:55:58', '2026-06-29 04:56:01'),
(16, 2, 'fields/QM5WeGGxsaPGe124ZKAiPfypi1zcwHDg6KktiMS8.jpg', 1, '2026-06-29 04:56:13', '2026-06-29 04:56:17'),
(17, 3, 'fields/5bv7WfzlrsaMrMc8xNBUSmnjBSHoURRENYTUCMqw.jpg', 1, '2026-06-29 04:56:30', '2026-06-29 04:56:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `football_fields`
--

CREATE TABLE `football_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `price_per_hour` decimal(12,2) NOT NULL,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `football_fields`
--

INSERT INTO `football_fields` (`id`, `name`, `address`, `description`, `amenities`, `price_per_hour`, `open_time`, `close_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sân bóng Chu Văn An', 'Tây Hồ, Hà Nội', 'Sân bóng 7 người sử dụng mặt cỏ nhân tạo chất lượng cao, hệ thống chiếu sáng tốt và khu vực nghỉ ngơi thuận tiện. Phù hợp cho các trận giao hữu, luyện tập và giải đấu phong trào.', '[\"\\u0110\\u00e8n chi\\u1ebfu s\\u00e1ng\",\"B\\u00e3i \\u0111\\u1ed7 xe\",\"Ph\\u00f2ng thay \\u0111\\u1ed3\",\"N\\u01b0\\u1edbc u\\u1ed1ng\"]', 380000.00, '06:00:00', '23:00:00', 'active', '2026-06-28 16:30:38', '2026-06-29 04:56:01'),
(2, 'Sân Mini Bách Khoa', 'Hai Bà Trưng, Hà Nội', 'Sân bóng 7 người sử dụng mặt cỏ nhân tạo chất lượng cao, hệ thống chiếu sáng tốt và khu vực nghỉ ngơi thuận tiện. Phù hợp cho các trận giao hữu, luyện tập và giải đấu phong trào.', '[\"\\u0110\\u00e8n chi\\u1ebfu s\\u00e1ng\",\"B\\u00e3i \\u0111\\u1ed7 xe\",\"Ph\\u00f2ng thay \\u0111\\u1ed3\",\"N\\u01b0\\u1edbc u\\u1ed1ng\"]', 400000.00, '06:00:00', '23:00:00', 'active', '2026-06-28 16:30:38', '2026-06-29 04:56:17'),
(3, 'Sân vận động Mỹ Đình II', 'Nam Từ Liêm, Hà Nội', 'Sân bóng 7 người sử dụng mặt cỏ nhân tạo chất lượng cao, hệ thống chiếu sáng tốt và khu vực nghỉ ngơi thuận tiện. Phù hợp cho các trận giao hữu, luyện tập và giải đấu phong trào.', '[\"\\u0110\\u00e8n chi\\u1ebfu s\\u00e1ng\",\"B\\u00e3i \\u0111\\u1ed7 xe\",\"Ph\\u00f2ng thay \\u0111\\u1ed3\",\"N\\u01b0\\u1edbc u\\u1ed1ng\"]', 450000.00, '06:00:00', '23:00:00', 'active', '2026-06-28 16:30:38', '2026-06-29 04:56:33'),
(4, 'Sân bóng Thành Đô', 'Cầu Giấy, Hà Nội', 'Sân bóng 7 người sử dụng mặt cỏ nhân tạo chất lượng cao, hệ thống chiếu sáng tốt và khu vực nghỉ ngơi thuận tiện. Phù hợp cho các trận giao hữu, luyện tập và giải đấu phong trào.', '[\"\\u0110\\u00e8n chi\\u1ebfu s\\u00e1ng\",\"B\\u00e3i \\u0111\\u1ed7 xe\",\"Ph\\u00f2ng thay \\u0111\\u1ed3\",\"N\\u01b0\\u1edbc u\\u1ed1ng\"]', 500000.00, '06:00:00', '23:00:00', 'active', '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(5, 'Sân bóng Kỳ Hòa 2', 'Thanh Xuân, Hà Nội', 'Sân bóng 7 người sử dụng mặt cỏ nhân tạo chất lượng cao, hệ thống chiếu sáng tốt và khu vực nghỉ ngơi thuận tiện. Phù hợp cho các trận giao hữu, luyện tập và giải đấu phong trào.', '[\"\\u0110\\u00e8n chi\\u1ebfu s\\u00e1ng\",\"B\\u00e3i \\u0111\\u1ed7 xe\",\"Ph\\u00f2ng thay \\u0111\\u1ed3\",\"N\\u01b0\\u1edbc u\\u1ed1ng\"]', 450000.00, '06:00:00', '23:00:00', 'active', '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(6, 'Sport Plus Center', 'Hoàng Mai, Hà Nội', 'Sân bóng 7 người sử dụng mặt cỏ nhân tạo chất lượng cao, hệ thống chiếu sáng tốt và khu vực nghỉ ngơi thuận tiện. Phù hợp cho các trận giao hữu, luyện tập và giải đấu phong trào.', '[\"\\u0110\\u00e8n chi\\u1ebfu s\\u00e1ng\",\"B\\u00e3i \\u0111\\u1ed7 xe\",\"Ph\\u00f2ng thay \\u0111\\u1ed3\",\"N\\u01b0\\u1edbc u\\u1ed1ng\"]', 600000.00, '06:00:00', '23:00:00', 'active', '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(7, 'san dam hong', 'damhong2', 'dep', '[\"\\u0110\\u00e8n chi\\u1ebfu s\\u00e1ng\",\"B\\u00e3i \\u0111\\u1ed7 xe\",\"Ph\\u00f2ng thay \\u0111\\u1ed3\",\"N\\u01b0\\u1edbc u\\u1ed1ng\"]', 400000.00, '06:00:00', '23:00:00', 'active', '2026-06-28 16:48:32', '2026-06-28 16:49:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_01_000100_create_football_fields_table', 1),
(5, '2026_01_01_000200_create_field_images_table', 1),
(6, '2026_01_01_000300_create_time_slots_table', 1),
(7, '2026_01_01_000400_create_bookings_table', 1),
(8, '2026_01_01_000500_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('32b4507f-bfae-4570-b9e6-8d0d60ba7330', 'App\\Notifications\\BookingStatusChanged', 'App\\Models\\User', 2, '{\"title\":\"C\\u1eadp nh\\u1eadt \\u0111\\u01a1n \\u0111\\u1eb7t s\\u00e2n\",\"message\":\"\\u0110\\u01a1n \\u0111\\u1eb7t s\\u00e2n c\\u1ee7a b\\u1ea1n \\u0111\\u00e3 \\u0111\\u01b0\\u1ee3c x\\u00e1c nh\\u1eadn.\",\"booking_id\":7,\"status\":\"confirmed\",\"field_name\":\"S\\u00e2n b\\u00f3ng Chu V\\u0103n An\",\"booking_date\":\"30\\/06\\/2026\"}', NULL, '2026-06-28 20:28:36', '2026-06-28 20:28:36'),
('e1fe0d3f-fba4-4ee5-846a-948f89abdaff', 'App\\Notifications\\BookingStatusChanged', 'App\\Models\\User', 7, '{\"title\":\"C\\u1eadp nh\\u1eadt \\u0111\\u01a1n \\u0111\\u1eb7t s\\u00e2n\",\"message\":\"\\u0110\\u01a1n \\u0111\\u1eb7t s\\u00e2n c\\u1ee7a b\\u1ea1n \\u0111\\u00e3 b\\u1ecb h\\u1ee7y.\",\"booking_id\":8,\"status\":\"cancelled\",\"field_name\":\"san dam hong\",\"booking_date\":\"30\\/06\\/2026\"}', NULL, '2026-06-28 20:42:34', '2026-06-28 20:42:34'),
('f241f0cc-7342-4075-828a-ee3baf7e854e', 'App\\Notifications\\BookingStatusChanged', 'App\\Models\\User', 3, '{\"title\":\"C\\u1eadp nh\\u1eadt \\u0111\\u01a1n \\u0111\\u1eb7t s\\u00e2n\",\"message\":\"\\u0110\\u01a1n \\u0111\\u1eb7t s\\u00e2n c\\u1ee7a b\\u1ea1n \\u0111\\u00e3 \\u0111\\u01b0\\u1ee3c x\\u00e1c nh\\u1eadn.\",\"booking_id\":6,\"status\":\"confirmed\",\"field_name\":\"S\\u00e2n Mini B\\u00e1ch Khoa\",\"booking_date\":\"29\\/06\\/2026\"}', '2026-06-28 16:47:18', '2026-06-28 16:46:12', '2026-06-28 16:47:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('hA6mA9bagxCEmHps0pVCFjb2C0RymAgqEijezTTs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSngyamUwVmxHRWh1M0hRR2JQczZSV1JETkJsQTV2TnJxSFBxVnRHZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9fQ==', 1782989886);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `time_slots`
--

CREATE TABLE `time_slots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `time_slots`
--

INSERT INTO `time_slots` (`id`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`) VALUES
(1, '06:00:00', '07:30:00', 'active', '2026-06-28 16:30:37', '2026-06-28 16:30:37'),
(2, '08:00:00', '09:30:00', 'active', '2026-06-28 16:30:37', '2026-06-28 16:30:37'),
(3, '10:00:00', '11:30:00', 'active', '2026-06-28 16:30:37', '2026-06-28 16:30:37'),
(4, '14:00:00', '15:30:00', 'active', '2026-06-28 16:30:37', '2026-06-28 16:30:37'),
(5, '16:00:00', '17:30:00', 'active', '2026-06-28 16:30:37', '2026-06-28 16:30:37'),
(6, '17:30:00', '19:00:00', 'active', '2026-06-28 16:30:37', '2026-06-28 16:30:37'),
(7, '19:30:00', '21:00:00', 'active', '2026-06-28 16:30:37', '2026-06-28 16:30:37'),
(8, '21:00:00', '22:30:00', 'active', '2026-06-28 16:30:37', '2026-06-28 16:30:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'customer',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `address`, `avatar`, `role`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Quản trị viên PitchPerfect', 'admin@pitchperfect.vn', '0900000001', '2026-06-28 16:30:38', '$2y$12$xIDFwxLFxVKTfgaxiviqe.3Y17QApqC7Jjw0knYY4JqzQcU4fbuiy', 'Hà Nội', 'avatars/wsmjqet67KLEXlxaCe77tCCSTmUtQzQXdwFa8slD.jpg', 'admin', 'active', 'E6SObjAqeVR7mXBf87IdlCRDlqhZokSzwKYGax0DcWhsN2VHqhcMQLA5UOgp', '2026-06-28 16:30:38', '2026-06-29 05:21:13'),
(2, 'Nguyễn Văn An', 'an@example.com', '0901234567', '2026-06-28 16:30:38', '$2y$12$Aug04WLUHdIjYlXuHMMh0uM0B/mAfFZOboOMu0slF1rmttQqp1RZy', 'Hà Nội', NULL, 'customer', 'active', '16aeShVD83jaWkwk2psGGFonjt95fvfwkR4JmSnGUkyM2omkX80KAxaxXWMO', '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(3, 'Trần Minh Tuấn', 'tuan@example.com', '0901234568', '2026-06-28 16:30:38', '$2y$12$/jIKgo7LZBVqi2GpysPJ4OI0N8sUmFCVNYk4WBsnxRIdW3JHY.pSu', 'Hà Nội', NULL, 'customer', 'active', '3LUe2deFasS51VI0haqKn6wFbisLgBfCsSmreO9UMerzmMcbGznmHvWPlLxI', '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(4, 'Lê Quốc Huy', 'huy@example.com', '0901234569', '2026-06-28 16:30:38', '$2y$12$j9YFgG2wjjkM3cf8TGv98usAJBWIeAWYVMF/qGQyh3mtIDLkBtnZW', 'Hà Nội', NULL, 'customer', 'active', NULL, '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(5, 'Phạm Minh Anh', 'minhanh@example.com', '0901234570', '2026-06-28 16:30:38', '$2y$12$FJoP3ztQMgNflDDVtvujlObqiNYaGIJzsOMh0PuwuMm1MimQ2RnTu', 'Hà Nội', NULL, 'customer', 'active', NULL, '2026-06-28 16:30:38', '2026-06-28 16:30:38'),
(6, 'Đỗ Hoàng Nam', 'nam@example.com', '0901234571', '2026-06-28 16:30:39', '$2y$12$4imz6t/0.pkybTu3GcXovuMtm3AWj.iscRvutcBUWHgbbjvIrc40S', 'Hà Nội', NULL, 'customer', 'active', NULL, '2026-06-28 16:30:39', '2026-06-28 16:30:39'),
(7, 'Nguyễn Bảo Sơn', 'ab@gmail.com', '0846009262', NULL, '$2y$12$1ul6VW8uLfPYO96p81Hk6.RXG6cMTGJqxKIa2MzQ4/8wCEgRFQl6O', NULL, NULL, 'customer', 'active', NULL, '2026-06-28 20:39:38', '2026-06-28 20:40:05');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_slot_lock_key_unique` (`slot_lock_key`),
  ADD KEY `bookings_time_slot_id_foreign` (`time_slot_id`),
  ADD KEY `bookings_availability_index` (`football_field_id`,`booking_date`,`time_slot_id`),
  ADD KEY `bookings_user_history_index` (`user_id`,`status`,`booking_date`),
  ADD KEY `bookings_status_index` (`status`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `field_images`
--
ALTER TABLE `field_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_images_football_field_id_is_main_index` (`football_field_id`,`is_main`),
  ADD KEY `field_images_is_main_index` (`is_main`);

--
-- Chỉ mục cho bảng `football_fields`
--
ALTER TABLE `football_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `football_fields_name_status_index` (`name`,`status`),
  ADD KEY `football_fields_price_per_hour_status_index` (`price_per_hour`,`status`),
  ADD KEY `football_fields_status_index` (`status`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `time_slots_start_time_end_time_unique` (`start_time`,`end_time`),
  ADD KEY `time_slots_status_index` (`status`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD KEY `users_role_index` (`role`),
  ADD KEY `users_status_index` (`status`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `field_images`
--
ALTER TABLE `field_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `football_fields`
--
ALTER TABLE `football_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_football_field_id_foreign` FOREIGN KEY (`football_field_id`) REFERENCES `football_fields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_time_slot_id_foreign` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slots` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `field_images`
--
ALTER TABLE `field_images`
  ADD CONSTRAINT `field_images_football_field_id_foreign` FOREIGN KEY (`football_field_id`) REFERENCES `football_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
