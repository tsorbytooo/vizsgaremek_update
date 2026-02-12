-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Feb 12. 09:33
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `caloria_center`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `food_id`) VALUES
(1, 6, 37);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `foods`
--

CREATE TABLE `foods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `calories_100g` decimal(6,2) NOT NULL,
  `protein_100g` decimal(5,2) DEFAULT NULL,
  `carbs_100g` decimal(5,2) DEFAULT NULL,
  `fat_100g` decimal(5,2) DEFAULT NULL,
  `fiber_100g` decimal(5,2) DEFAULT NULL,
  `sugar_100g` decimal(5,2) DEFAULT NULL,
  `salt_100g` decimal(5,3) DEFAULT NULL,
  `unit` enum('g','ml') DEFAULT 'g',
  `source` enum('local','api') DEFAULT 'local',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `foods`
--

INSERT INTO `foods` (`id`, `name`, `calories_100g`, `protein_100g`, `carbs_100g`, `fat_100g`, `fiber_100g`, `sugar_100g`, `salt_100g`, `unit`, `source`, `created_at`, `image`, `created_by`) VALUES
(1, 'Csirkemell', 165.00, 31.00, 0.00, 3.60, 0.00, 0.00, 0.090, 'g', 'local', '2026-01-22 12:36:11', NULL, NULL),
(2, 'Rizs (főtt)', 130.00, 2.70, 28.00, 0.30, 0.40, 0.10, 0.010, 'g', 'local', '2026-01-22 12:36:11', NULL, NULL),
(3, 'Tojás', 155.00, 13.00, 1.10, 11.00, 0.00, 1.10, 0.120, 'g', 'local', '2026-01-22 12:36:11', NULL, NULL),
(4, 'Alma', 52.00, 0.30, 14.00, 0.20, 2.40, 10.00, 0.000, 'g', 'local', '2026-01-22 12:36:11', NULL, NULL),
(5, 'Maxi Cheese Burger', 256.00, 13.00, 20.00, 13.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:26:59', NULL, NULL),
(6, 'Amora Sauce pour Cheeseburger Flacon Souple 250ml', 411.00, 0.90, 7.70, 43.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:26:59', NULL, NULL),
(7, 'Original burger cheese', 265.00, 13.00, 21.00, 14.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:26:59', NULL, NULL),
(8, '', 252.00, 13.00, 26.00, 10.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:26:59', NULL, NULL),
(9, 'BURGER LE CHAROLAIS BOEUF EMMENTAL', 301.00, 13.00, 28.00, 15.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:26:59', NULL, NULL),
(10, '2 Steaks végétaux et gourmands', 229.00, 13.00, 3.80, 17.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:33', NULL, NULL),
(11, 'Steaks de pois', 284.00, 21.00, 12.00, 16.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:33', NULL, NULL),
(12, 'Steak végétal', 221.00, 17.00, 13.00, 10.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:33', NULL, NULL),
(13, 'Burgers natures', 207.00, 19.00, 4.20, 12.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:33', NULL, NULL),
(14, 'Steak haché Charal authentique', 170.00, 20.00, 0.00, 10.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:33', NULL, NULL),
(15, 'Yogurt Bnine BANANA', 88.10, 3.90, 14.30, 1.70, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:57', NULL, NULL),
(16, '5 Banana Lunchbox Loaves', 317.00, 8.33, 56.70, 4.67, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:57', NULL, NULL),
(17, 'Huel Ready-To-Drink - Banana', 80.00, 4.00, 6.70, 3.80, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:57', NULL, NULL),
(18, 'Strawberries & Bananas', 48.00, 0.53, 12.10, 0.05, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:57', NULL, NULL),
(19, 'fruit smoothie STRAWBERRIES, BANANAS & APPLES', 48.00, 0.53, 10.70, 0.05, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:27:57', NULL, NULL),
(20, 'Túró Rudi', 331.40, 5.90, 39.20, 15.70, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:34:21', NULL, NULL),
(21, 'Túró Rudi', 295.00, 13.00, 26.00, 19.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:34:21', NULL, NULL),
(22, 'Óriás Pöttyös Túró Rudi', 339.00, 11.00, 39.00, 15.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:34:21', NULL, NULL),
(23, 'Mizo túró rudi', 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:34:21', NULL, NULL),
(24, 'Túró rudi trikolor', 352.00, 8.80, 39.00, 18.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:34:21', NULL, NULL),
(25, 'Földimogyoró - Pörkölt, sózott', 598.00, 24.00, 12.00, 49.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:44:22', NULL, NULL),
(26, 'Mexicorn - Chili', 435.00, 7.60, 69.00, 13.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:44:22', NULL, NULL),
(27, 'Földimogyoró tésztabundában - Chili ízű', 497.00, 14.00, 39.00, 31.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:44:22', NULL, NULL),
(28, 'pörkölt kukorica chilis', 434.00, 7.60, 72.00, 12.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:44:22', NULL, NULL),
(29, 'Földimogyoró, extrán pörkölt', 601.00, 24.00, 13.00, 49.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:44:22', NULL, NULL),
(30, 'Mogyi - Pörkölt, sózott kesudió', 599.00, 19.00, 21.00, 48.00, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:44:22', NULL, NULL),
(31, 'Pörkölt ízesítő', 89.00, 3.50, 10.50, 2.40, NULL, NULL, NULL, 'g', 'api', '2026-01-28 11:44:22', NULL, NULL),
(32, 'Almond Drink', 14.00, 0.50, NULL, NULL, NULL, NULL, NULL, 'g', 'local', '2026-01-28 13:31:23', NULL, NULL),
(33, 'Görög joghurt', 122.00, 4.64, NULL, NULL, NULL, NULL, NULL, 'g', 'local', '2026-01-28 13:31:23', NULL, NULL),
(34, 'Snickers', 481.00, 8.60, NULL, NULL, NULL, NULL, NULL, 'g', 'local', '2026-01-28 13:31:23', NULL, NULL),
(35, 'Coco Délicieuse et Tropicale', 20.00, 0.10, NULL, NULL, NULL, NULL, NULL, 'g', 'local', '2026-01-28 13:31:23', NULL, NULL),
(36, 'Alpro Not Milk', 59.00, 0.70, NULL, NULL, NULL, NULL, NULL, 'g', 'local', '2026-01-28 13:31:23', NULL, NULL),
(37, 'Tripla Cheesy', 257.00, 16.00, 13.00, 15.00, NULL, NULL, NULL, 'g', '', '2026-01-30 11:32:22', NULL, NULL),
(38, 'muzli', 265.00, NULL, NULL, NULL, NULL, NULL, NULL, 'g', 'local', '2026-02-04 13:16:39', '1770210999_ronaldo.gif', 6),
(39, 'husi', 67.00, NULL, NULL, NULL, NULL, NULL, NULL, 'g', 'local', '2026-02-06 11:36:25', '1770377785_fugelaci.jpg', 8),
(41, 'siu', 67.00, NULL, NULL, NULL, NULL, NULL, NULL, 'g', 'local', '2026-02-06 11:44:46', '1770378286_ronaldo.jpg', 8),
(42, 'Garnélarák (főtt)', 99.00, 24.00, 0.20, 0.30, 0.00, 0.00, 1.100, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(43, 'Tőkehal filé', 82.00, 18.00, 0.00, 0.70, 0.00, 0.00, 0.130, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(44, 'Hekk filé', 89.00, 17.20, 0.00, 2.20, 0.00, 0.00, 0.150, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(45, 'Padlizsán', 25.00, 1.00, 6.00, 0.20, 3.00, 3.50, 0.002, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(46, 'Sütőtök', 26.00, 1.00, 6.50, 0.10, 0.50, 2.80, 0.001, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(47, 'Kelbimbó', 43.00, 3.40, 9.00, 0.30, 3.80, 2.20, 0.025, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(48, 'Spárga', 20.00, 2.20, 3.90, 0.10, 2.10, 1.90, 0.002, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(49, 'Zellergumó', 42.00, 1.50, 9.00, 0.30, 1.80, 1.80, 0.100, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(50, 'Retek', 16.00, 0.70, 3.40, 0.10, 1.60, 1.90, 0.039, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(51, 'Kínai kel', 13.00, 1.50, 2.20, 0.20, 1.00, 1.20, 0.065, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(52, 'Málna', 52.00, 1.20, 11.90, 0.60, 6.50, 4.40, 0.001, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(53, 'Eper', 32.00, 0.70, 7.70, 0.30, 2.00, 4.90, 0.001, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(54, 'Ananász', 50.00, 0.50, 13.10, 0.10, 1.40, 9.80, 0.001, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(55, 'Kivi', 61.00, 1.10, 15.00, 0.50, 3.00, 9.00, 0.003, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(56, 'Sárgadinnye', 34.00, 0.80, 8.20, 0.20, 0.90, 7.90, 0.010, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(57, 'Vörösbab (konzerv)', 127.00, 8.70, 22.80, 0.50, 7.40, 0.30, 0.300, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(58, 'Zöldborsó', 81.00, 5.40, 14.50, 0.40, 5.10, 5.70, 0.005, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(59, 'Tökmag (natúr)', 559.00, 30.00, 10.70, 49.00, 6.00, 1.40, 0.018, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(60, 'Szezámmag', 573.00, 17.70, 23.40, 49.70, 11.80, 0.30, 0.011, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(61, 'Tojásfehérje', 52.00, 11.00, 0.70, 0.20, 0.00, 0.70, 0.160, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(62, 'Tofu', 76.00, 8.00, 1.90, 4.80, 0.30, 0.70, 0.007, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(63, 'Hajdina (főtt)', 92.00, 3.40, 20.00, 0.60, 2.70, 0.00, 0.001, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(64, 'Bulgur (főtt)', 83.00, 3.00, 18.60, 0.20, 4.50, 0.10, 0.005, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(65, 'Csirkemáj (sült)', 167.00, 24.50, 0.90, 6.50, 0.00, 0.00, 0.200, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(66, 'Cottage Cheese (light)', 80.00, 12.00, 3.00, 2.00, 0.00, 3.00, 0.700, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(67, 'Kefir', 43.00, 3.50, 4.80, 1.00, 0.00, 4.80, 0.110, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(68, 'Hummusz', 166.00, 7.90, 14.30, 9.60, 6.00, 0.30, 0.950, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(69, 'Gomba (csiperke)', 22.00, 3.10, 3.30, 0.30, 1.00, 2.00, 0.005, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(70, 'Paradicsom', 18.00, 0.90, 3.90, 0.20, 1.20, 2.60, 0.005, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(71, 'Paprika (TV)', 20.00, 1.00, 3.00, 0.30, 1.20, 2.40, 0.002, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `category` enum('Support','Feedback','Bug') DEFAULT 'Support',
  `status` enum('Open','Closed') DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `premium` tinyint(1) NOT NULL,
  `height` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `premium`, `height`, `weight`, `age`, `gender`) VALUES
(1, 'Remek Elek', 'remekelek@gmail.com', 'remekelek123', 0, NULL, NULL, NULL, NULL),
(2, 'Pahotsa Henrik', 'henrikabest@gmail.com', 'henrikhenrik123', 1, NULL, NULL, NULL, NULL),
(3, 'nigger', 'nyomorekvagyok@gmail.com', 'csorba', 0, NULL, NULL, NULL, NULL),
(4, 'nigger', 'nyomorekvagyok@gmail.com', 'csorba', 0, NULL, NULL, NULL, NULL),
(6, 'Schnepp Ádám', 'adam@gmail.com', '$2y$10$xwfF0Fdt4mRSH8xZS3vuu.FhdGnRfVhDTa496douxc4lQcQ77qSLG', 1, 170, 95, 19, 'male'),
(7, 'Szőke Császár Bálint', 'szokebalintdeazeredeti@gmail.com', '$2y$10$smFWpselm2qSQ.3lJqj6lODwutnZnscaOQq1cnEUjDoKkDfBoVPe.', 0, 120, 140, 12, 'female'),
(8, 'nigger', 'nigger@gmail.com', '$2y$10$mxUtxDgmsP2eSH28zszhseoqXhcph6qyphWld3czU5AgvGIWC4Jd6', 1, 200, 67, 35, 'male'),
(9, 'csorba', 'admin@gmail.com', '$2y$10$pkOxgkEza6da7Gk6TW4vDemHqVeeSiiE3yIAGMIZklnrZ0fD5e/da', 1, 180, 75, 20, 'male'),
(10, 'wawa', 'nadszintia@gmail.com', '$2y$10$KLGjhqIstHphvuHqCVkjbubXsJrCvEJtOsBgu1cBVV9kGg/gzBoJS', 1, 180, 65, 21, 'female');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `user_food_log`
--

CREATE TABLE `user_food_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `quantity` decimal(6,2) NOT NULL,
  `log_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `user_food_log`
--

INSERT INTO `user_food_log` (`id`, `user_id`, `food_id`, `quantity`, `log_date`, `created_at`) VALUES
(1, 1, 1, 200.00, '2026-01-22', '2026-01-22 12:48:18'),
(2, 1, 2, 50.00, '2026-01-22', '2026-01-22 12:48:18'),
(3, 2, 4, 250.00, '2026-01-22', '2026-01-22 12:48:18'),
(4, 6, 1, 110.00, '2026-01-28', '2026-01-28 11:23:19'),
(7, 7, 16, 100.00, '2026-01-28', '2026-01-28 13:30:30'),
(8, 7, 6, 100.00, '2026-01-28', '2026-01-28 13:30:49'),
(9, 7, 11, 500.00, '2026-01-28', '2026-01-28 13:31:11'),
(10, 7, 34, 500.00, '2026-01-28', '2026-01-28 13:31:41'),
(12, 7, 37, 263.00, '2026-01-30', '2026-01-30 12:17:55'),
(13, 6, 37, 263.00, '2026-01-30', '2026-01-30 13:51:36'),
(14, 6, 37, 263.00, '2026-02-04', '2026-02-04 09:04:59'),
(15, 6, 38, 89.00, '2026-02-04', '2026-02-04 13:16:39'),
(21, 8, 20, 100.00, '2026-02-11', '2026-02-11 08:34:58');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`food_id`);

--
-- A tábla indexei `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `user_food_log`
--
ALTER TABLE `user_food_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `food_id` (`food_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT a táblához `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `user_food_log`
--
ALTER TABLE `user_food_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `user_food_log`
--
ALTER TABLE `user_food_log`
  ADD CONSTRAINT `user_food_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_food_log_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
