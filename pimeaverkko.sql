-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Isäntä: 127.0.0.1:3306
-- Luontiaika: 28.08.2026 klo 09:54
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
-- Rakenne taululle `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_User_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vedos taulusta `groups`
--

INSERT INTO `groups` (`id`, `user_id`, `name`, `description`) VALUES
(1, 1, 'Pelit', 'peleistä liittyviä keskusteluja'),
(2, 1, 'Autot', 'Autoista liittyviä keskusteluja'),
(9, 1, 'Koulu', 'kouluun liittyvää keskustelua'),
(10, 1, '\'', '\''),
(11, 1, 'auto', 'fgdfghs');

-- --------------------------------------------------------

--
-- Rakenne taululle `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  `author` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `POST_FK_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vedos taulusta `posts`
--

INSERT INTO `posts` (`id`, `group_id`, `user_id`, `author`, `content`, `created_at`) VALUES
(7, 1, 1, 'Jarkko', 'Onko fortnite maailamn suosituin peli, minun lempparini se ainakin on', '2026-08-21 09:32:59'),
(9, 2, 1, 'Jarkko', 'lempi autoni on Toyota Yaris 1.0 litraisella koneella', '2026-08-21 09:34:24'),
(10, 2, 1, 'Jarkko', 'mOPO ON PAREMPI KUIN AUTO', '2026-08-21 09:38:53'),
(11, 9, 1, 'Jarkko', 'MINÄ VIHAAN KOULUA TODELLA PALJON', '2026-08-21 09:39:37'),
(12, 10, 1, '¨ÅP08967¨\'Ä\'\'¨\'', '\'¨\'0875Q£@€$€$', '2026-08-21 09:40:03'),
(15, 1, 1, 'Jarkko H', 'moi', '2026-08-25 09:08:43');

-- --------------------------------------------------------

--
-- Rakenne taululle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vedos taulusta `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password_hash`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$riKqirBOd1Yr1mC7OcgYnu49VS04Kyqoq8EGhqjvuc8CW6KoKrns2'),
(2, 'admin', 'admin2@gmail.com', '$2y$10$BKdrN5G2M5EYrxYHPDa/muJzNDDnoI0JpkoTH9INn5435PK.P6RiW'),
(3, 'admin', 'admin3@gmail.com', '$2y$10$UsdzOGFJzGZCgX5jpwA.aePpzgLmUGq7en0bSI/MAgz51L1H5H10y'),
(5, 'Jarkko', 'jarkko@gmail.com', '$2y$10$YTr9IDRhnTdLYW.oYY2zZ.X9TUDMdDCQfsprJWuWCp.oYopJ11XZS');

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `FK_User_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Rajoitteet taululle `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `POST_FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
