-- First Love Church Management System Database Setup
-- Run this in phpMyAdmin to create all tables

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create fellowships table first
CREATE TABLE `fellowships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `meeting_day` tinyint(4) DEFAULT NULL COMMENT '1=Monday, 2=Tuesday, etc.',
  `meeting_time` time DEFAULT NULL,
  `leader_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pastor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create users table
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `role` enum('admin','pastor','leader','treasurer','member') NOT NULL DEFAULT 'member',
  `fellowship_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create attendances table
CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fellowship_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `attendance_count` int(11) NOT NULL DEFAULT 0,
  `bible_study_topic` varchar(191) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `recorded_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create offerings table
CREATE TABLE `offerings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fellowship_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `offering_date` date NOT NULL,
  `payment_method` enum('cash','mobile_money','bank_transfer','cheque') NOT NULL DEFAULT 'cash',
  `transaction_reference` varchar(191) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `confirmed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create announcements table
CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `content` text NOT NULL,
  `target_audience` enum('all','members','leaders','pastors','treasurers','admins') NOT NULL DEFAULT 'all',
  `priority` enum('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create announcement_reads table
CREATE TABLE `announcement_reads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `announcement_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create password_reset_tokens table
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add primary keys
ALTER TABLE `fellowships` ADD PRIMARY KEY (`id`);
ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `users_email_unique` (`email`);
ALTER TABLE `attendances` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `attendances_fellowship_id_attendance_date_unique` (`fellowship_id`,`attendance_date`);
ALTER TABLE `offerings` ADD PRIMARY KEY (`id`);
ALTER TABLE `announcements` ADD PRIMARY KEY (`id`);
ALTER TABLE `announcement_reads` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `announcement_reads_announcement_id_user_id_unique` (`announcement_id`,`user_id`);
ALTER TABLE `password_reset_tokens` ADD PRIMARY KEY (`email`);

-- Add auto increment
ALTER TABLE `fellowships` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `attendances` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `offerings` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `announcements` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `announcement_reads` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Add foreign key constraints
ALTER TABLE `fellowships` 
  ADD KEY `fellowships_leader_id_index` (`leader_id`),
  ADD KEY `fellowships_pastor_id_index` (`pastor_id`);

ALTER TABLE `users` 
  ADD KEY `users_role_is_active_index` (`role`,`is_active`),
  ADD KEY `users_fellowship_id_index` (`fellowship_id`);

ALTER TABLE `attendances` 
  ADD KEY `attendances_fellowship_id_attendance_date_index` (`fellowship_id`,`attendance_date`),
  ADD KEY `attendances_attendance_date_index` (`attendance_date`),
  ADD KEY `attendances_recorded_by_index` (`recorded_by`);

ALTER TABLE `offerings` 
  ADD KEY `offerings_fellowship_id_offering_date_index` (`fellowship_id`,`offering_date`),
  ADD KEY `offerings_status_offering_date_index` (`status`,`offering_date`),
  ADD KEY `offerings_submitted_by_index` (`submitted_by`),
  ADD KEY `offerings_confirmed_by_index` (`confirmed_by`);

-- Insert sample data
-- Admin user
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('System Administrator', 'admin@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-123456', 'admin', 1, NOW(), NOW());

-- Pastor
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('Pastor John Mwale', 'pastor@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-234567', 'pastor', 1, NOW(), NOW());

-- Treasurer  
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('Mary Banda', 'treasurer@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-345678', 'treasurer', 1, NOW(), NOW());

-- Sample fellowships
INSERT INTO `fellowships` (`name`, `description`, `location`, `meeting_day`, `meeting_time`, `pastor_id`, `is_active`, `created_at`, `updated_at`) VALUES
('UNILUS Fellowship', 'Fellowship for University of Lusaka students and staff', 'UNILUS Campus, Room 101', 3, '18:00:00', 2, 1, NOW(), NOW()),
('CBU Fellowship', 'Fellowship for Copperbelt University students', 'CBU Campus, Block A', 5, '17:30:00', 2, 1, NOW(), NOW()),
('Youth Fellowship', 'Fellowship for young professionals and youth', 'Foxdale Community Center', 6, '16:00:00', 2, 1, NOW(), NOW()),
('Women\'s Fellowship', 'Fellowship for women in the community', 'Church Main Hall', 2, '14:00:00', 2, 1, NOW(), NOW());

-- Fellowship leaders
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `fellowship_id`, `is_active`, `created_at`, `updated_at`) VALUES
('James Phiri', 'james@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-456789', 'leader', 1, 1, NOW(), NOW()),
('Grace Mulenga', 'grace@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-567890', 'leader', 2, 1, NOW(), NOW()),
('David Chanda', 'david@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-678901', 'leader', 3, 1, NOW(), NOW()),
('Ruth Kapata', 'ruth@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-789012', 'leader', 4, 1, NOW(), NOW());

-- Update fellowships with leaders
UPDATE `fellowships` SET `leader_id` = 4 WHERE `id` = 1;
UPDATE `fellowships` SET `leader_id` = 5 WHERE `id` = 2;
UPDATE `fellowships` SET `leader_id` = 6 WHERE `id` = 3;
UPDATE `fellowships` SET `leader_id` = 7 WHERE `id` = 4;

-- Sample members
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `fellowship_id`, `is_active`, `created_at`, `updated_at`) VALUES
('Peter Mwanza', 'peter@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-888001', 'member', 1, 1, NOW(), NOW()),
('Sarah Tembo', 'sarah@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-888002', 'member', 1, 1, NOW(), NOW()),
('Michael Banda', 'michael@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-888003', 'member', 2, 1, NOW(), NOW()),
('Esther Nkandu', 'esther@firstlove.church', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+260-971-888004', 'member', 2, 1, NOW(), NOW());

-- Sample announcements
INSERT INTO `announcements` (`title`, `content`, `target_audience`, `priority`, `is_active`, `published_at`, `created_by`, `created_at`, `updated_at`) VALUES
('Welcome to First Love Church CMS', 'We are excited to launch our new Church Management System! This platform will help us better manage our fellowships, track attendance, and handle offerings more efficiently.', 'all', 'high', 1, NOW(), 1, NOW(), NOW()),
('Monthly Fellowship Leaders Meeting', 'All fellowship leaders are invited to attend the monthly meeting this Saturday at 10:00 AM in the main church hall. We will discuss upcoming events and ministry plans.', 'leaders', 'normal', 1, NOW(), 2, NOW(), NOW());

COMMIT;

-- Note: All passwords are 'password' (hashed)
-- Login with: admin@firstlove.church / password 