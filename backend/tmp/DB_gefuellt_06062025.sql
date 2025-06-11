-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 09. Jun 2025 um 18:24
-- Server-Version: 10.11.5-MariaDB-1:10.11.5+maria~ubu1804
-- PHP-Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `gym`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `excluded_from_analysis` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `exercises`
--

INSERT INTO `exercises` (`id`, `user_id`, `name`, `category`, `description`, `created_at`, `excluded_from_analysis`) VALUES
(10, 5, 'Bizeps Curls am Kabelzug', 'Bizeps', '', '2025-05-19 11:57:33', 0),
(11, 5, 'Trizepsdrücken am Kabelzug', 'Trizeps', '', '2025-05-19 11:57:52', 0),
(12, 5, 'Hammercurls mit der KH', 'Brachialis', '', '2025-05-19 11:59:12', 0),
(13, 5, 'KH Bankdrücken', 'Brust', '', '2025-05-19 11:59:39', 0),
(14, 5, 'Fliegende am Kabelzug', 'Brust', '', '2025-05-19 11:59:58', 0),
(15, 5, 'Latzug', 'Rücken', '', '2025-05-19 12:00:18', 0),
(16, 5, 'Seitheben am Kabelzug', 'Schultern', '', '2025-05-22 16:25:34', 0),
(17, 5, 'Reverse Fly am Kabelzug', 'Hintere Schulter', '', '2025-05-22 16:53:09', 0),
(18, 5, 'Bizeps Curls hinten am Kabelzug', 'Bizeps', '', '2025-05-27 15:03:01', 0),
(19, 10, 'Test', 'test', '', '2025-05-28 18:45:24', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `training_entries`
--

CREATE TABLE `training_entries` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `sets` int(11) DEFAULT NULL,
  `reps` int(11) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `performed_at` datetime DEFAULT current_timestamp(),
  `excluded_from_analysis` tinyint(1) NOT NULL DEFAULT 0,
  `is_committed` tinyint(1) NOT NULL DEFAULT 1,
  `from_plan` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `training_entries`
--

INSERT INTO `training_entries` (`id`, `session_id`, `exercise_id`, `sets`, `reps`, `weight`, `notes`, `performed_at`, `excluded_from_analysis`, `is_committed`, `from_plan`, `deleted_at`) VALUES
(683, 114, 15, 1, 8, 75.00, '', '2025-05-22 16:32:51', 0, 1, 1, NULL),
(684, 114, 15, 2, 4, 75.00, '', '2025-05-22 16:32:51', 0, 1, 1, NULL),
(685, 114, 15, 3, 5, 70.00, '', '2025-05-22 16:32:51', 0, 1, 1, NULL),
(686, 114, 15, 4, 6, 65.00, '', '2025-05-22 16:32:51', 0, 1, 1, NULL),
(687, 114, 16, 1, 0, 0.00, '', '2025-05-22 16:32:51', 0, 1, 1, NULL),
(688, 114, 16, 2, 0, 0.00, '', '2025-05-22 16:32:51', 0, 1, 1, NULL),
(689, 114, 16, 3, 0, 0.00, '', '2025-05-22 16:32:51', 0, 1, 1, NULL),
(690, 115, 15, 1, 8, 75.00, '', '2025-05-22 16:46:01', 0, 1, 1, NULL),
(691, 115, 15, 2, 4, 75.00, '', '2025-05-22 16:46:01', 0, 1, 1, NULL),
(692, 115, 15, 3, 5, 70.00, '', '2025-05-22 16:46:01', 0, 1, 1, NULL),
(693, 115, 15, 4, 6, 65.00, '', '2025-05-22 16:46:01', 0, 1, 1, NULL),
(694, 115, 16, 1, 7, 30.00, '', '2025-05-22 16:46:01', 0, 1, 1, NULL),
(695, 115, 16, 2, 7, 25.00, '', '2025-05-22 16:46:01', 0, 1, 1, NULL),
(696, 115, 16, 3, 7, 25.00, '', '2025-05-22 16:46:01', 0, 1, 1, NULL),
(697, 116, 15, 1, 8, 75.00, '', '2025-05-22 16:46:02', 0, 1, 1, NULL),
(698, 116, 15, 2, 4, 75.00, '', '2025-05-22 16:46:02', 0, 1, 1, NULL),
(699, 116, 15, 3, 5, 70.00, '', '2025-05-22 16:46:02', 0, 1, 1, NULL),
(700, 116, 15, 4, 6, 65.00, '', '2025-05-22 16:46:02', 0, 1, 1, NULL),
(701, 116, 16, 1, 7, 30.00, '', '2025-05-22 16:46:02', 0, 1, 1, NULL),
(702, 116, 16, 2, 7, 25.00, '', '2025-05-22 16:46:02', 0, 1, 1, NULL),
(703, 116, 16, 3, 7, 25.00, '', '2025-05-22 16:46:02', 0, 1, 1, NULL),
(704, 117, 17, 1, 8, 25.00, '', '2025-05-22 17:00:44', 0, 1, 1, NULL),
(705, 117, 17, 2, 8, 25.00, '', '2025-05-22 17:00:44', 0, 1, 1, NULL),
(706, 117, 17, 3, 4, 25.00, '', '2025-05-22 17:00:44', 0, 1, 1, NULL),
(707, 118, 10, 1, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(708, 118, 10, 2, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(709, 118, 10, 3, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(710, 118, 10, 4, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(711, 118, 11, 1, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(712, 118, 11, 2, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(713, 118, 11, 3, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(714, 118, 12, 1, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(715, 118, 12, 2, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(716, 118, 12, 3, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(717, 118, 13, 1, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(718, 118, 13, 2, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(719, 118, 13, 3, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(720, 118, 14, 1, 11, 45.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(721, 118, 14, 2, 8, 45.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(722, 118, 14, 3, 7, 45.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(723, 118, 14, 4, 8, 40.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(724, 118, 15, 1, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(725, 118, 15, 2, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(726, 118, 15, 3, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(727, 118, 15, 4, 0, 0.00, '', '2025-05-27 14:49:27', 0, 1, 1, NULL),
(728, 119, 16, 1, 5, 30.00, 'r 5 l 6', '2025-05-27 14:58:36', 0, 1, 1, NULL),
(729, 119, 16, 2, 7, 25.00, 'r 6 l 8', '2025-05-27 14:58:36', 0, 1, 1, NULL),
(730, 119, 16, 3, 7, 25.00, '', '2025-05-27 14:58:36', 0, 1, 1, NULL),
(731, 119, 17, 1, 0, 0.00, '', '2025-05-27 14:58:36', 0, 1, 1, NULL),
(732, 119, 17, 2, 0, 0.00, '', '2025-05-27 14:58:36', 0, 1, 1, NULL),
(733, 119, 17, 3, 0, 0.00, '', '2025-05-27 14:58:36', 0, 1, 1, NULL),
(734, 120, 18, 1, 9, 45.00, '', '2025-05-27 15:12:58', 0, 1, 1, NULL),
(735, 120, 18, 2, 7, 45.00, '', '2025-05-27 15:12:58', 0, 1, 1, NULL),
(736, 120, 18, 3, 7, 45.00, '', '2025-05-27 15:12:58', 0, 1, 1, NULL),
(737, 121, 19, 1, 10, 10.00, '', '2025-05-28 18:45:59', 0, 1, 1, NULL),
(738, 121, 19, 2, 10, 10.00, '', '2025-05-28 18:45:59', 0, 1, 1, NULL),
(739, 121, 19, 3, 10, 10.00, '', '2025-05-28 18:45:59', 0, 1, 1, NULL),
(740, 121, 19, 4, 10, 10.00, '', '2025-05-28 18:45:59', 0, 1, 1, NULL),
(741, 122, 19, 1, 11, 11.00, '', '2025-05-28 18:46:15', 0, 1, 1, NULL),
(742, 122, 19, 2, 11, 11.00, '', '2025-05-28 18:46:15', 0, 1, 1, NULL),
(743, 122, 19, 4, 11, 11.00, '', '2025-05-28 18:46:15', 0, 1, 1, NULL),
(744, 123, 19, 1, 11, 11.00, '', '2025-05-28 18:46:34', 0, 1, 1, NULL),
(745, 123, 19, 2, 12, 12.00, '', '2025-05-28 18:46:34', 0, 1, 1, NULL),
(746, 123, 19, 3, 13, 13.00, '', '2025-05-28 18:46:34', 0, 1, 1, NULL),
(747, 124, 19, 1, 1, 1.00, '', '2025-05-28 18:47:02', 0, 1, 1, NULL),
(748, 124, 19, 2, 2, 2.00, '', '2025-05-28 18:47:02', 0, 1, 1, NULL),
(749, 124, 19, 3, 3, 3.00, '', '2025-05-28 18:47:02', 0, 1, 1, NULL),
(750, 124, 19, 4, 4, 4.00, '', '2025-05-28 18:47:02', 0, 1, 1, NULL),
(751, 125, 19, 1, 2, 2.00, '', '2025-05-28 18:47:23', 0, 1, 1, NULL),
(752, 125, 19, 2, 3, 3.00, '', '2025-05-28 18:47:23', 0, 1, 1, NULL),
(753, 127, 19, 1, 1, 1.00, '', '2025-05-28 18:47:58', 0, 1, 1, NULL),
(754, 127, 19, 2, 2, 2.00, '', '2025-05-28 18:47:58', 0, 1, 1, NULL),
(755, 127, 19, 3, 3, 3.00, '', '2025-05-28 18:47:58', 0, 1, 1, NULL),
(756, 128, 19, 1, 1, 1.00, '', '2025-05-28 18:48:06', 0, 1, 1, NULL),
(757, 128, 19, 2, 2, 2.00, '', '2025-05-28 18:48:06', 0, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `training_plans`
--

CREATE TABLE `training_plans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `training_plans`
--

INSERT INTO `training_plans` (`id`, `user_id`, `name`, `created_at`) VALUES
(7, 6, 'Po', '2025-05-18 18:32:31'),
(8, 5, 'Arme', '2025-05-19 11:56:43'),
(10, 5, 'Oberkörper', '2025-05-19 12:05:27'),
(11, 5, 'Schultern', '2025-05-22 16:25:04'),
(12, 10, 'Test', '2025-05-28 18:44:25'),
(13, 5, 'r', '2025-06-08 17:23:02');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `training_plan_exercises`
--

CREATE TABLE `training_plan_exercises` (
  `id` int(11) NOT NULL,
  `training_plan_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `exercise_order` int(11) DEFAULT NULL,
  `target_sets` int(11) DEFAULT NULL,
  `target_reps` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `training_plan_exercises`
--

INSERT INTO `training_plan_exercises` (`id`, `training_plan_id`, `exercise_id`, `exercise_order`, `target_sets`, `target_reps`) VALUES
(23, 10, 10, 2, 4, 10),
(24, 10, 11, 3, 3, 10),
(25, 10, 12, 5, 3, 10),
(26, 10, 13, 4, 3, 10),
(27, 10, 14, 1, 4, 10),
(28, 10, 15, 6, 4, 10),
(32, 11, 16, 1, 3, 10),
(33, 11, 17, 1, 3, 10),
(34, 8, 18, 1, 3, 10),
(35, 12, 19, 1, 3, 10);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `training_sessions`
--

CREATE TABLE `training_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `session_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `training_sessions`
--

INSERT INTO `training_sessions` (`id`, `user_id`, `plan_id`, `session_date`, `notes`, `created_at`) VALUES
(114, 5, 11, '2025-05-22', NULL, '2025-05-22 16:32:51'),
(115, 5, 11, '2025-05-22', NULL, '2025-05-22 16:46:01'),
(116, 5, 11, '2025-05-22', NULL, '2025-05-22 16:46:02'),
(117, 5, 11, '2025-05-22', NULL, '2025-05-22 17:00:44'),
(118, 5, 10, '2025-05-27', NULL, '2025-05-27 14:49:27'),
(119, 5, 11, '2025-05-27', NULL, '2025-05-27 14:58:36'),
(120, 5, 8, '2025-05-27', NULL, '2025-05-27 15:12:58'),
(121, 10, 12, '2025-05-28', NULL, '2025-05-28 18:45:59'),
(122, 10, 12, '2025-05-28', NULL, '2025-05-28 18:46:15'),
(123, 10, 12, '2025-05-28', NULL, '2025-05-28 18:46:34'),
(124, 10, 12, '2025-05-28', NULL, '2025-05-28 18:47:02'),
(125, 10, 12, '2025-05-28', NULL, '2025-05-28 18:47:23'),
(126, 10, 12, '2025-05-28', NULL, '2025-05-28 18:47:43'),
(127, 10, 12, '2025-05-28', NULL, '2025-05-28 18:47:58'),
(128, 10, 12, '2025-05-28', NULL, '2025-05-28 18:48:06');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`) VALUES
(5, 'lukas', 'lukas.holzmann@proton.me', '$2y$10$B3vfQrHTfxwLoz.3KEcx3OKnnjvYd.DlEQ.lWPbQWJL/u6g9MZ1ei', '2025-05-04 14:58:13'),
(6, 'Holzmasi', 'simone.holzmann70@gmail.com', '$2y$10$hOU/eQbOMTvcHZ.xusppLuHY2uvXXwsdirtfLBy7pRX/dxEisMLbm', '2025-05-18 18:31:22'),
(8, 'Paula', 'info@paula-holzmann.de', '$2y$10$W4UDQ2AbXl2kNC3O3YRMtOTaGMye.3bUleWwZLKUetjv0UznvNU.G', '2025-05-18 18:43:49'),
(9, 'mondreiter', 'info@achim-holzmann.de', '$2y$10$cQCzjdiTquCck4T4cLGhgOYVNc6CGIQyMi7/DtM5xvzXiMSda92Q2', '2025-05-18 18:44:05'),
(10, '01', '01@01.de', '$2y$10$lc9gWN5e9YgqJPXDi29zuejK8ykkhdnOzQqpTOfAsosPzrD.BDvXi', '2025-05-28 18:44:06');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `training_entries`
--
ALTER TABLE `training_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indizes für die Tabelle `training_plans`
--
ALTER TABLE `training_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `training_plan_exercises`
--
ALTER TABLE `training_plan_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_plan_id` (`training_plan_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indizes für die Tabelle `training_sessions`
--
ALTER TABLE `training_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT für Tabelle `training_entries`
--
ALTER TABLE `training_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=758;

--
-- AUTO_INCREMENT für Tabelle `training_plans`
--
ALTER TABLE `training_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT für Tabelle `training_plan_exercises`
--
ALTER TABLE `training_plan_exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT für Tabelle `training_sessions`
--
ALTER TABLE `training_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `exercises`
--
ALTER TABLE `exercises`
  ADD CONSTRAINT `exercises_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `training_entries`
--
ALTER TABLE `training_entries`
  ADD CONSTRAINT `training_entries_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `training_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_entries_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `training_plans`
--
ALTER TABLE `training_plans`
  ADD CONSTRAINT `training_plans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `training_plan_exercises`
--
ALTER TABLE `training_plan_exercises`
  ADD CONSTRAINT `training_plan_exercises_ibfk_1` FOREIGN KEY (`training_plan_id`) REFERENCES `training_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_plan_exercises_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `training_sessions`
--
ALTER TABLE `training_sessions`
  ADD CONSTRAINT `training_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_sessions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `training_plans` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
