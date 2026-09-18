-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Isäntä: 127.0.0.1:3306
-- Luontiaika: 18.09.2026 klo 08:54
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
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_User_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vedos taulusta `groups`
--

INSERT INTO `groups` (`id`, `user_id`, `name`, `description`) VALUES
(14, 7, 'Pelit', 'Peleihin liittyvää keskustelua'),
(16, 7, 'Autot', 'Autoista liittyvää keskustelua'),
(17, 8, 'Koirat', 'koiria');

-- --------------------------------------------------------

--
-- Rakenne taululle `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  `author` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `POST_FK_user_id` (`user_id`),
  KEY `POST_FK_group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vedos taulusta `posts`
--

INSERT INTO `posts` (`id`, `group_id`, `user_id`, `author`, `content`, `created_at`) VALUES
(20, 14, 7, 'Kaapo', 'Fortnite on huono peli', '2026-09-04 07:34:16'),
(21, 16, 7, 'Kaapo', 'Jarkon lempiauto on Toyota Yaris 1.0', '2026-09-04 07:35:57'),
(22, 16, 7, 'Kaapo', 'Toyota corolla 1.4', '2026-09-04 07:36:59'),
(23, 14, 8, 'Jarkko2', 'jarkko2', '2026-09-04 09:46:25'),
(24, 14, 1, 'admin', 'Clash Royale on hyvä peli nykyään', '2026-09-10 09:24:18'),
(25, 14, 1, 'admin', 'Brawl Stars on huono peli nykyään', '2026-09-10 09:24:27'),
(26, 16, 1, 'admin', 'Nissan primera on hyvä auto', '2026-09-10 09:24:53'),
(27, 16, 1, 'admin', 'Jarkko tykkää nissan suprasta', '2026-09-10 09:25:07'),
(28, 17, 1, 'admin', 'pieni koira', '2026-09-10 09:25:19'),
(29, 17, 1, 'admin', 'iso koira', '2026-09-10 09:25:24'),
(30, 17, 1, 'admin', 'medium koira', '2026-09-10 09:25:29'),
(31, 17, 1, 'admin', 'kissa ei ole koira', '2026-09-10 09:25:35');

-- --------------------------------------------------------

--
-- Rakenne taululle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `groups_created` int NOT NULL DEFAULT 0,
  `max_groups` int NOT NULL DEFAULT 2,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vedos taulusta `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password_hash`) VALUES
(1, 'admin1235', 'admin@gmail.com', '$2y$10$riKqirBOd1Yr1mC7OcgYnu49VS04Kyqoq8EGhqjvuc8CW6KoKrns2'),
(2, 'Admin2', 'admin2@gmail.com', '$2y$10$BKdrN5G2M5EYrxYHPDa/muJzNDDnoI0JpkoTH9INn5435PK.P6RiW'),
(3, 'admin', 'admin3@gmail.com', '$2y$10$UsdzOGFJzGZCgX5jpwA.aePpzgLmUGq7en0bSI/MAgz51L1H5H10y'),
(5, 'Jarkko', 'jarkko@gmail.com', '$2y$10$YTr9IDRhnTdLYW.oYY2zZ.X9TUDMdDCQfsprJWuWCp.oYopJ11XZS'),
(6, 'testi', 'testi@gmail.com', '$2y$10$oPiXrOOCmEiRiHMZo0iZDuh2rWQPXmajxNFdKsPLwBcFPVL8jKgZ6'),
(7, 'Kaapo', 'kaapo@gmail.com', '$2y$10$MhLzDhYQESsnyL7.xp0.Cuq3u0sVDPZbIUODlx/M/kHh3L7TajcN2'),
(8, 'Jarkko2', 'jarkko2@gmail.com', '$2y$10$3ofJXotjTHxgysph..gj6uOobiqgLtMX.GGoBL9qZQsimd.1Cb.l6'),
(9, '2', 'kaapo2@gmail.com', '$2y$10$z6.FpcgvgS6EkmCmrxt4meeJinrdKz1o5UDPRNfdb4WeN0NsRlaYe'),
(10, 'Kaapo', 'kaapo.kikkelijarvi2@samiedu365.fi', '$2y$10$yMWKpeka2.oaNSBeaCcB4e2Ab0kmQcc1TvfzdV3FctrMIKxOWfHpm');

-- Alustetaan olemassa olevien ryhmien määrät user-tauluun.
UPDATE `user` AS u
SET groups_created = (
  SELECT COUNT(*) FROM `groups` AS g WHERE g.user_id = u.id
);

-- Näkymä näyttää käyttäjän luomien ryhmien määrän ja kokonaisrajan.
DROP VIEW IF EXISTS `user_group_stats`;
CREATE VIEW `user_group_stats` AS
SELECT
  u.id AS user_id,
  u.name,
  u.email,
  u.groups_created,
  u.max_groups,
  COUNT(g.id) AS groups_in_groups_table
FROM `user` AS u
LEFT JOIN `groups` AS g ON g.user_id = u.id
GROUP BY u.id, u.name, u.email, u.groups_created, u.max_groups;

-- Käyttäjän ryhmäraja tulee käyttäjätaulusta, ei sovelluksen kovakoodatusta arvosta.
DROP TRIGGER IF EXISTS `limit_user_groups`;
DROP TRIGGER IF EXISTS `increment_user_group_count`;
DROP TRIGGER IF EXISTS `decrement_user_group_count`;
DELIMITER $$
CREATE TRIGGER `limit_user_groups`
BEFORE INSERT ON `groups`
FOR EACH ROW
BEGIN
  DECLARE group_count INT;
  DECLARE group_limit INT;

  SELECT max_groups INTO group_limit
  FROM `user`
  WHERE id = NEW.user_id;

  SELECT COUNT(*) INTO group_count
  FROM `groups`
  WHERE user_id = NEW.user_id;

  IF group_count >= group_limit THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Kayttajan ryhmien enimmäismaara on saavutettu';
  END IF;
END$$

CREATE TRIGGER `increment_user_group_count`
AFTER INSERT ON `groups`
FOR EACH ROW
BEGIN
  UPDATE `user`
  SET groups_created = groups_created + 1
  WHERE id = NEW.user_id;
END$$

CREATE TRIGGER `decrement_user_group_count`
AFTER DELETE ON `groups`
FOR EACH ROW
BEGIN
  UPDATE `user`
  SET groups_created = GREATEST(groups_created - 1, 0)
  WHERE id = OLD.user_id;
END$$
DELIMITER ;

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
  ADD CONSTRAINT `POST_FK_group_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `POST_FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
