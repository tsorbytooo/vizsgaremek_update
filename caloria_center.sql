-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Feb 13. 14:06
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
-- Tábla szerkezet ehhez a táblához `custom_recipes`
--

CREATE TABLE `custom_recipes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_calories` int(11) NOT NULL DEFAULT 0,
  `ingredients` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

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
-- Tábla szerkezet ehhez a táblához `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(2, 9, 5, '', '2026-02-13 12:21:25');

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
(71, 'Paprika (TV)', 20.00, 1.00, 3.00, 0.30, 1.20, 2.40, 0.002, 'g', 'local', '2026-02-11 08:42:08', NULL, NULL),
(72, 'Húsleves (gazdagon)', 45.00, 3.20, 4.10, 1.80, 0.50, 0.80, 1.200, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(73, 'Pacalpörkölt', 155.00, 14.50, 2.10, 9.80, 0.00, 0.50, 1.500, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(74, 'Rántott hús (sertés)', 285.00, 18.00, 15.00, 17.50, 1.20, 0.30, 1.100, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(75, 'Bableves füstölt hússal', 110.00, 7.50, 12.00, 4.50, 3.80, 1.10, 1.400, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(76, 'Töltött káposzta', 135.00, 6.80, 8.50, 8.20, 2.50, 1.20, 1.600, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(77, 'Gulyásleves', 85.00, 5.50, 7.20, 3.80, 1.40, 0.90, 1.300, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(78, 'Paprikás krumpli kolbásszal', 125.00, 4.20, 16.50, 5.80, 2.10, 1.00, 1.400, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(79, 'Sült krumpli (mirelit, sült)', 290.00, 3.40, 38.00, 14.00, 3.20, 0.50, 0.800, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(80, 'Pörkölt (sertés lapocka)', 185.00, 16.50, 2.50, 12.40, 0.00, 0.80, 1.400, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(81, 'Bolognai spagetti', 160.00, 7.80, 22.00, 5.20, 1.50, 2.40, 0.900, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(82, 'Milka Alpesi Tejcsokoládé', 530.00, 6.30, 58.50, 29.50, 1.80, 57.00, 0.370, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(83, 'Haribo Gumicukor (Goldbären)', 343.00, 6.90, 77.00, 0.50, 0.00, 46.00, 0.070, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(84, 'Kinder Tejszelet', 422.00, 7.90, 34.00, 27.90, 0.00, 29.50, 0.230, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(85, 'Kinder Bueno', 572.00, 8.60, 49.50, 37.30, 0.00, 41.20, 0.270, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(86, 'Lays Sós Chips', 536.00, 6.40, 52.00, 33.00, 4.30, 0.50, 1.600, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(87, 'Pringles (Hagymás-tejfölös)', 515.00, 4.20, 52.00, 32.00, 2.80, 2.50, 1.700, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(88, 'Balaton Szelet (ét)', 525.00, 5.40, 59.00, 28.50, 3.10, 42.00, 0.200, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(89, 'Oreo Keksz', 476.00, 4.80, 68.00, 20.00, 2.40, 38.00, 0.730, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(90, 'Twix', 495.00, 4.40, 65.00, 24.00, 0.00, 49.00, 0.440, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(91, 'Sport Szelet (ét)', 455.00, 3.90, 68.00, 18.50, 0.00, 62.00, 0.100, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(92, 'Uborka (kígyó)', 15.00, 0.60, 3.60, 0.10, 0.50, 1.70, 0.002, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(93, 'Sárgarépa (nyers)', 41.00, 0.90, 9.60, 0.20, 2.80, 4.70, 0.070, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(94, 'Karfiol', 25.00, 1.90, 5.00, 0.30, 2.00, 1.90, 0.030, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(95, 'Brokkoli', 34.00, 2.80, 6.60, 0.40, 2.60, 1.70, 0.033, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(96, 'Krumpli (főtt)', 77.00, 2.00, 17.50, 0.10, 2.20, 0.80, 0.010, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(97, 'Banán', 89.00, 1.10, 22.80, 0.30, 2.60, 12.20, 0.001, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(98, 'Körte', 57.00, 0.40, 15.00, 0.10, 3.10, 9.80, 0.001, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(99, 'Narancs', 47.00, 0.90, 11.80, 0.10, 2.40, 9.40, 0.001, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(100, 'Coca-Cola (Classic)', 42.00, 0.00, 10.60, 0.00, 0.00, 10.60, 0.000, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(101, 'Tej (2.8%)', 58.00, 3.20, 4.70, 2.80, 0.00, 4.70, 0.100, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(102, 'Sör (világos)', 43.00, 0.50, 3.60, 0.00, 0.00, 0.10, 0.010, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(103, 'Vaj', 717.00, 0.90, 0.10, 81.00, 0.00, 0.10, 0.011, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(104, 'Ketchup', 101.00, 1.30, 23.20, 0.10, 0.70, 23.10, 2.300, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(105, 'Mustár', 66.00, 4.40, 5.20, 4.00, 2.00, 0.90, 2.500, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(106, 'Majonéz', 680.00, 1.00, 3.00, 75.00, 0.00, 2.50, 1.500, 'g', 'local', '2026-02-13 12:22:42', NULL, NULL),
(107, 'Kakaós csiga', 345.00, 6.50, 48.00, 14.00, 2.10, 18.00, 0.600, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(108, 'Túrós táska', 310.00, 8.20, 42.00, 12.00, 1.50, 14.00, 0.500, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(109, 'Kifli (tejes)', 282.00, 8.50, 56.00, 2.50, 2.80, 4.00, 1.200, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(110, 'Zsemle', 275.00, 8.00, 57.00, 1.20, 3.00, 2.50, 1.300, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(111, 'Vajas croissant', 406.00, 8.20, 45.00, 21.00, 2.60, 11.00, 0.900, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(112, 'Gyros tál (hasábbal, öntettel)', 210.00, 12.00, 15.00, 11.00, 1.80, 1.50, 1.800, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(113, 'Pizza Margherita', 266.00, 11.00, 33.00, 10.00, 2.30, 3.50, 1.500, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(114, 'Pizza Hawaii', 245.00, 10.50, 31.00, 8.50, 2.00, 6.20, 1.400, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(115, 'Hot-dog', 247.00, 10.00, 18.00, 15.00, 0.80, 4.20, 2.100, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(116, 'Chicken Nuggets (6 db)', 297.00, 15.00, 18.00, 18.00, 1.00, 0.50, 1.200, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(117, 'Székelykáposzta', 105.00, 5.80, 6.20, 6.50, 2.80, 1.40, 1.500, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(118, 'Paradicsomos káposzta', 85.00, 1.50, 14.00, 2.80, 2.40, 9.50, 1.100, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(119, 'Zöldborsófőzelék', 115.00, 5.20, 16.00, 3.50, 4.80, 6.50, 1.200, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(120, 'Krumplifőzelék', 95.00, 2.80, 14.50, 3.20, 1.90, 1.20, 1.300, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(121, 'Aranygaluska vanília sodóval', 285.00, 6.40, 42.00, 10.50, 1.20, 22.00, 0.400, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(122, 'Mákos guba', 225.00, 7.50, 35.00, 6.80, 4.50, 18.00, 0.300, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(123, 'Pilóta keksz', 495.00, 5.80, 64.00, 24.00, 2.50, 32.00, 0.500, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(124, 'Negro (klasszikus)', 384.00, 0.00, 96.00, 0.00, 0.00, 74.00, 0.000, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(125, 'Dunakavics', 485.00, 9.50, 65.00, 21.00, 4.20, 58.00, 0.050, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(126, 'Francia drazsé', 455.00, 3.50, 78.00, 14.00, 1.80, 72.00, 0.100, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(127, 'Tuc keksz (sós)', 483.00, 8.10, 62.00, 22.00, 2.30, 7.10, 2.800, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(128, 'Mogyi Pörkölt napraforgómag', 622.00, 24.00, 13.00, 53.00, 8.50, 2.50, 1.500, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(129, 'Monster Energy (Original)', 47.00, 0.00, 12.00, 0.00, 0.00, 11.00, 0.190, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(130, 'Hell Energy Drink', 46.00, 0.00, 11.00, 0.00, 0.00, 11.00, 0.200, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(131, 'Sió Almalé 100%', 45.00, 0.10, 10.50, 0.10, 0.00, 10.20, 0.000, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(132, 'Egri Bikavér (vörösbor)', 83.00, 0.00, 2.50, 0.00, 0.00, 0.20, 0.000, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(133, 'Unicum', 305.00, 0.00, 15.00, 0.00, 0.00, 15.00, 0.000, 'g', 'local', '2026-02-13 12:25:34', NULL, NULL),
(134, 'Sertésborda bakonyi módra', 195.00, 16.20, 4.50, 12.80, 0.80, 1.20, 1.400, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(135, 'Brassói aprópecsenye', 245.00, 14.00, 18.50, 13.00, 2.10, 0.50, 1.600, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(136, 'Májgaluska leves', 65.00, 4.20, 6.80, 2.50, 0.40, 0.70, 1.300, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(137, 'Spenót főzelék főtt tojással', 115.00, 8.50, 7.20, 6.50, 3.20, 2.10, 1.200, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(138, 'Rántott sajt (tartár nélkül)', 320.00, 18.50, 16.00, 20.00, 0.50, 1.10, 1.800, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(139, 'Gombás rizottó', 145.00, 3.80, 26.00, 3.20, 1.20, 0.60, 0.900, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(140, 'Lazac filé (sült)', 208.00, 22.00, 0.00, 13.00, 0.00, 0.00, 0.150, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(141, 'Tonhal (konzerv, vizes)', 116.00, 26.00, 0.00, 1.00, 0.00, 0.00, 0.800, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(142, 'Füstölt makréla', 305.00, 18.50, 0.00, 25.00, 0.00, 0.00, 2.500, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(143, 'Sajtos pogácsa', 385.00, 10.20, 40.00, 20.00, 2.40, 2.80, 2.100, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(144, 'Hamburger (sima, gyorséttermi)', 255.00, 12.50, 28.00, 10.00, 1.50, 6.00, 1.300, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(145, 'Tortilla tekercs (üres)', 312.00, 8.50, 52.00, 7.50, 2.80, 2.10, 1.600, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(146, 'KitKat szelet', 518.00, 6.50, 58.50, 28.00, 2.00, 48.00, 0.150, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(147, 'Mars szelet', 448.00, 4.00, 69.00, 17.00, 1.20, 60.00, 0.400, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(148, 'Bounty (tejcsokis)', 487.00, 3.70, 59.00, 25.70, 1.60, 48.00, 0.250, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(149, 'Skittles', 404.00, 0.00, 91.00, 4.20, 0.00, 89.00, 0.020, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(150, 'Chio Hagymás-Tejfölös chips', 535.00, 6.00, 51.00, 33.00, 4.00, 3.20, 1.500, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(151, 'Sós pálcika (Ropi)', 380.00, 10.00, 75.00, 4.50, 3.50, 2.50, 4.500, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(152, 'Mogyoróvaj', 588.00, 25.00, 20.00, 50.00, 6.00, 9.00, 1.100, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(153, 'Gouda sajt', 356.00, 25.00, 2.20, 27.00, 0.00, 0.00, 1.900, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(154, 'Camembert sajt', 300.00, 19.80, 0.50, 24.00, 0.00, 0.50, 2.000, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(155, 'Zabpehely (száraz)', 389.00, 16.90, 66.00, 6.90, 10.60, 0.00, 0.010, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(156, 'Méz', 304.00, 0.30, 82.00, 0.00, 0.00, 82.00, 0.010, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(157, 'Sprite', 38.00, 0.00, 9.00, 0.00, 0.00, 9.00, 0.010, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(158, 'Fanta Narancs', 48.00, 0.00, 12.00, 0.00, 0.00, 12.00, 0.010, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(159, 'Cappuccino (cukor nélkül)', 35.00, 2.50, 4.50, 1.50, 0.00, 4.00, 0.100, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL),
(160, 'Pálinka (50%)', 245.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.000, 'g', 'local', '2026-02-13 12:58:10', NULL, NULL);

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
  `gender` enum('male','female') DEFAULT NULL,
  `theme` varchar(10) DEFAULT 'light'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `premium`, `height`, `weight`, `age`, `gender`, `theme`) VALUES
(6, 'Schnepp Ádám', 'adam@gmail.com', '$2y$10$xwfF0Fdt4mRSH8xZS3vuu.FhdGnRfVhDTa496douxc4lQcQ77qSLG', 1, 170, 95, 19, 'male', 'light'),
(9, 'csorba', 'admin@gmail.com', '$2y$10$pkOxgkEza6da7Gk6TW4vDemHqVeeSiiE3yIAGMIZklnrZ0fD5e/da', 0, 180, 75, 20, 'male', 'light');

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
(4, 6, 1, 110.00, '2026-01-28', '2026-01-28 11:23:19'),
(13, 6, 37, 263.00, '2026-01-30', '2026-01-30 13:51:36'),
(14, 6, 37, 263.00, '2026-02-04', '2026-02-04 09:04:59'),
(34, 9, 53, 153.00, '2026-02-11', '2026-02-11 14:07:58'),
(35, 9, 53, 150.00, '2026-02-11', '2026-02-11 14:09:03');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `water_log`
--

CREATE TABLE `water_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `log_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `water_log`
--

INSERT INTO `water_log` (`id`, `user_id`, `amount`, `log_date`) VALUES
(44, 9, 0.25, '2026-02-12'),
(45, 9, 0.25, '2026-02-12'),
(46, 9, 0.25, '2026-02-12');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `custom_recipes`
--
ALTER TABLE `custom_recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- A tábla indexei `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`food_id`);

--
-- A tábla indexei `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- A tábla indexei `water_log`
--
ALTER TABLE `water_log`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `custom_recipes`
--
ALTER TABLE `custom_recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT a táblához `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `user_food_log`
--
ALTER TABLE `user_food_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT a táblához `water_log`
--
ALTER TABLE `water_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `custom_recipes`
--
ALTER TABLE `custom_recipes`
  ADD CONSTRAINT `custom_recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
