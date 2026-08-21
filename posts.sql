-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Isäntä: 127.0.0.1:3306
-- Luontiaika: 21.08.2026 klo 09:53
-- Palvelimen versio: 8.4.7
-- PHP-versio 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Tietokanta: `pimeaverkko`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `author` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vedos taulusta `posts`
--

INSERT INTO `posts` (`id`, `group_id`, `author`, `content`, `created_at`) VALUES
(9, 2, 'Jarkko', 'lempi autoni on Toyota Yaris 1.0 litraisella koneella', '2026-08-21 09:34:24'),
(10, 2, 'Jarkko', 'mOPO ON PAREMPI KUIN AUTO', '2026-08-21 09:38:53'),
(7, 1, 'Jarkko', 'Onko fortnite maailamn suosituin peli, minun lempparini se ainakin on', '2026-08-21 09:32:59'),
(11, 9, 'Jarkko', 'MINÄ VIHAAN KOULUA TODELLA PALJON', '2026-08-21 09:39:37'),
(12, 10, '¨ÅP08967¨\'Ä\'\'¨\'', '\'¨\'0875Q£@€$€$', '2026-08-21 09:40:03');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
