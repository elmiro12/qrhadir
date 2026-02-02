-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.43 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.14.0.7165
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table absensiqr.admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.admins: ~1 rows (approximately)
DELETE FROM `admins`;
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
	(1, 'Administrator', 'admin@qrhadir.my.id', '$2y$12$Cn/Ihls5eGx6T16DTNrIo.e/kRo2e26Wa8AF57fXsYhd5XbCFNq9i', '2026-01-16 06:23:34', '2026-01-16 06:25:06');

-- Dumping structure for table absensiqr.attendance_qr_tokens
DROP TABLE IF EXISTS `attendance_qr_tokens`;
CREATE TABLE IF NOT EXISTS `attendance_qr_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_participant_id` bigint unsigned NOT NULL,
  `token` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_qr_tokens_token_unique` (`token`),
  KEY `attendance_qr_tokens_event_participant_id_foreign` (`event_participant_id`),
  CONSTRAINT `attendance_qr_tokens_event_participant_id_foreign` FOREIGN KEY (`event_participant_id`) REFERENCES `event_participants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.attendance_qr_tokens: ~3 rows (approximately)
DELETE FROM `attendance_qr_tokens`;
INSERT INTO `attendance_qr_tokens` (`id`, `event_participant_id`, `token`, `expired_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 'a63ddc4e-ebbb-4d86-b6e7-361b443a6c3c', '2026-01-18 06:25:06', '2026-01-16 06:24:58', '2026-01-16 06:25:06'),
	(2, 2, '9fdd25aa-ba87-46b0-997a-625433a5beba', '2026-01-18 06:25:06', '2026-01-16 06:24:58', '2026-01-16 06:25:06'),
	(3, 3, 'b87374b1-7d1f-4af8-8d02-f94c222c7fad', '2026-01-18 06:25:06', '2026-01-16 06:24:58', '2026-01-16 06:25:06');

-- Dumping structure for table absensiqr.attendances
DROP TABLE IF EXISTS `attendances`;
CREATE TABLE IF NOT EXISTS `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_participant_id` bigint unsigned NOT NULL,
  `attendance_date` date NOT NULL,
  `checkin_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_event_participant_id_attendance_date_unique` (`event_participant_id`,`attendance_date`),
  CONSTRAINT `attendances_event_participant_id_foreign` FOREIGN KEY (`event_participant_id`) REFERENCES `event_participants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.attendances: ~0 rows (approximately)
DELETE FROM `attendances`;

-- Dumping structure for table absensiqr.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table absensiqr.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table absensiqr.event_participants
DROP TABLE IF EXISTS `event_participants`;
CREATE TABLE IF NOT EXISTS `event_participants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `participant_id` bigint unsigned NOT NULL,
  `registered_via` enum('self','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'self',
  `registered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_participants_event_id_participant_id_unique` (`event_id`,`participant_id`),
  KEY `event_participants_participant_id_foreign` (`participant_id`),
  CONSTRAINT `event_participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_participants_participant_id_foreign` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.event_participants: ~3 rows (approximately)
DELETE FROM `event_participants`;
INSERT INTO `event_participants` (`id`, `event_id`, `participant_id`, `registered_via`, `registered_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'self', '2026-01-16 06:24:58', '2026-01-16 06:24:58', '2026-01-16 06:24:58'),
	(2, 1, 2, 'self', '2026-01-16 06:24:58', '2026-01-16 06:24:58', '2026-01-16 06:24:58'),
	(3, 1, 3, 'self', '2026-01-16 06:24:58', '2026-01-16 06:24:58', '2026-01-16 06:24:58');

-- Dumping structure for table absensiqr.events
DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','active','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.events: ~1 rows (approximately)
DELETE FROM `events`;
INSERT INTO `events` (`id`, `name`, `slug`, `start_date`, `end_date`, `location`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Demo Event Presensi QR', 'demo-event-presensi', '2026-01-16 06:25:06', '2026-01-18 06:25:06', 'Gedung Serbaguna Digital', 'active', '2026-01-16 06:24:58', '2026-01-16 06:25:06');

-- Dumping structure for table absensiqr.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table absensiqr.job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table absensiqr.jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table absensiqr.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.migrations: ~10 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_01_14_043004_create_admins_table', 1),
	(5, '2026_01_14_043109_create_events_table', 1),
	(6, '2026_01_14_043138_create_participants_table', 1),
	(7, '2026_01_14_043219_create_event_participants_table', 1),
	(8, '2026_01_14_043249_create_attendance_qr_tokens_table', 1),
	(9, '2026_01_14_043316_create_attendances_table', 1),
	(10, '2026_01_14_045654_create_settings_table', 1);

-- Dumping structure for table absensiqr.participants
DROP TABLE IF EXISTS `participants`;
CREATE TABLE IF NOT EXISTS `participants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `participants_email_unique` (`email`),
  UNIQUE KEY `participants_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.participants: ~3 rows (approximately)
DELETE FROM `participants`;
INSERT INTO `participants` (`id`, `name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
	(1, 'Budi Santoso', 'budi@example.test', '081234567891', '2026-01-16 06:24:58', '2026-01-16 06:24:58'),
	(2, 'Ani Wijaya', 'ani@example.test', '081234567892', '2026-01-16 06:24:58', '2026-01-16 06:24:58'),
	(3, 'Citra Lestari', 'citra@example.test', '081234567893', '2026-01-16 06:24:58', '2026-01-16 06:24:58');

-- Dumping structure for table absensiqr.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table absensiqr.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.sessions: ~0 rows (approximately)
DELETE FROM `sessions`;

-- Dumping structure for table absensiqr.settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.settings: ~8 rows (approximately)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'app_name', 'QR-Hadir', '2026-01-16 06:23:34', '2026-01-16 06:23:34'),
	(2, 'app_logo', 'logo.png', '2026-01-16 06:23:34', '2026-01-16 06:23:34'),
	(3, 'app_favicon', 'favicon.ico', '2026-01-16 06:23:34', '2026-01-16 06:23:34'),
	(4, 'app_description', 'Sistem Presensi Event Berbasis QR Code Terintegrasi', '2026-01-16 06:23:34', '2026-01-16 06:23:34'),
	(5, 'footer_text', '© 2026 QR-Hadir', '2026-01-16 06:23:34', '2026-01-16 06:23:34'),
	(6, 'timezone', 'Asia/Jayapura', '2026-01-16 06:23:34', '2026-01-16 06:23:34'),
	(7, 'contact_email', 'support@qrhadir.my.id', '2026-01-16 06:23:34', '2026-01-16 06:23:34'),
	(8, 'contact_whatsapp', '6281234567890', '2026-01-16 06:23:34', '2026-01-16 06:23:34');

-- Dumping structure for table absensiqr.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensiqr.users: ~0 rows (approximately)
DELETE FROM `users`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
