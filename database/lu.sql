-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2026 at 02:39 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mtvsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `category_name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'OSTALO'),
(2, 'NASILJE'),
(3, 'KORUPCIJA'),
(4, 'PODRSKA');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_date` date DEFAULT NULL,
  `location_id` mediumint(5) UNSIGNED DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` smallint(5) UNSIGNED DEFAULT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `valetudinarian_id` bigint(20) UNSIGNED NOT NULL,
  `image_name` varchar(90) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images_events`
--

CREATE TABLE `images_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `image_name` varchar(90) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images_guess`
--

CREATE TABLE `images_guess` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `valetudinarian_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image_name` varchar(90) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE `parties` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`id`, `name`, `abbreviation`) VALUES
(1, 'Srpska napredna stranka', 'SNS'),
(2, 'Lojalista SNS', 'SNS'),
(3, 'Aktivista SNS', 'SNS'),
(4, 'Simpatizer SNS', 'SNS'),
(5, 'Ćaci', 'CACI'),
(6, 'Batinaši', 'BAT'),
(7, 'Policija', 'MUP'),
(8, 'Specijalna antiteroristička jedinica', 'SAJ'),
(9, 'Bezbednosno-informativna agencija', 'BIA'),
(10, 'Vojska', 'VS'),
(11, 'Žandarmerija', 'MUP'),
(12, 'Komunalna Policija', 'MUP'),
(13, 'EU Ćaci', 'EUC'),
(101, 'Socijalisticka partija Srbije', 'SPS'),
(102, 'Partija ujedinjenih penzionera', 'PUPS'),
(103, 'Zdrava Srbija', 'ZS'),
(104, 'Srpska narodna partija', 'SNP'),
(105, 'Pokret socijalista', 'PS'),
(106, 'Srpski pokret obnove', 'SPO'),
(107, 'Ujedinjena seljačka stranka', 'USS'),
(108, 'Srpska radikalna stranka', 'SRS'),
(109, 'Jedinstvena Srbija', 'JS'),
(110, 'Zeleni Srbije', 'ZS'),
(111, 'Nova Demokratska stranka Srbije', 'NDSS'),
(112, 'Pokret obnove Kraljevine Srbije', 'POKS'),
(113, 'Srpska stranka Zavetnici', 'ZAV'),
(114, 'Srpski pokret Dveri', 'DVER'),
(115, 'Mi – Glas iz naroda', 'MI'),
(116, 'Demokratska stranka', 'DS'),
(117, 'Partija slobodnih gradjana', 'PSG'),
(118, 'Stranka slobode i pravde', 'SSP'),
(119, 'Narodni pokret Srbije', 'NPS'),
(120, 'Zeleno-levi front', 'ZLF'),
(121, 'Ekološki ustanak – Ćuta', 'CUTA'),
(122, 'Srbija centar', 'SRCE'),
(123, 'Novo lice Srbije', 'NLS'),
(124, 'Dosta je bilo', 'DJB'),
(125, 'Socijaldemokratska stranka', 'SDS'),
(126, 'Narodna stranka', 'NS'),
(127, 'Levijatan', 'LE');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `valetudinarians`
--

CREATE TABLE `valetudinarians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sobriquet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_id` mediumint(5) UNSIGNED DEFAULT NULL,
  `party_id` smallint(5) UNSIGNED DEFAULT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(2) UNSIGNED DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vale_events`
--

CREATE TABLE `vale_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `valetudinarian_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vale_posting`
--

CREATE TABLE `vale_posting` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_id` mediumint(5) UNSIGNED DEFAULT NULL,
  `party_id` tinyint(2) UNSIGNED DEFAULT NULL,
  `confirmed` tinyint(2) UNSIGNED NOT NULL,
  `status` smallint(5) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`category_name`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_location_id_foreign` (`location_id`),
  ADD KEY `events_owner_id_foreign` (`owner_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images_events`
--
ALTER TABLE `images_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images_guess`
--
ALTER TABLE `images_guess`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parties`
--
ALTER TABLE `parties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parties_name_unique` (`name`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `valetudinarians`
--
ALTER TABLE `valetudinarians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `valetudinarians_party_id_foreign` (`party_id`),
  ADD KEY `valetudinarians_location_id_foreign` (`location_id`),
  ADD KEY `valetudinarians_owner_id_foreign` (`owner_id`);

--
-- Indexes for table `vale_events`
--
ALTER TABLE `vale_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vale_events_event_id_foreign` (`event_id`),
  ADD KEY `vale_events_valetudinarian_id_foreign` (`valetudinarian_id`);

--
-- Indexes for table `vale_posting`
--
ALTER TABLE `vale_posting`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images_events`
--
ALTER TABLE `images_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images_guess`
--
ALTER TABLE `images_guess`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `valetudinarians`
--
ALTER TABLE `valetudinarians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vale_events`
--
ALTER TABLE `vale_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `valetudinarians`
--
ALTER TABLE `valetudinarians`
  ADD CONSTRAINT `valetudinarians_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `valetudinarians_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vale_events`
--
ALTER TABLE `vale_events`
  ADD CONSTRAINT `vale_events_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vale_events_valetudinarian_id_foreign` FOREIGN KEY (`valetudinarian_id`) REFERENCES `valetudinarians` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
