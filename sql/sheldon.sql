-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 23. Mai 2022 um 15:28
-- Server Version: 5.6.13
-- PHP-Version: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `sheldon`
--
CREATE DATABASE IF NOT EXISTS `sheldon` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sheldon`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `asset`
--

CREATE TABLE IF NOT EXISTS `asset` (
  `asset_id` int(255) NOT NULL AUTO_INCREMENT,
  `asset_type_id` int(255) NOT NULL,
  `asset_vendor_id` int(255) NOT NULL,
  `asset_model_id` int(255) NOT NULL,
  `asset_building_id` int(255) NOT NULL DEFAULT '1',
  `asset_floor_id` int(255) NOT NULL DEFAULT '1',
  `asset_room_id` int(255) NOT NULL DEFAULT '1',
  `asset_serial` varchar(200) NOT NULL,
  `asset_cis` varchar(200) NOT NULL,
  `asset_description` varchar(200) NOT NULL DEFAULT '-',
  PRIMARY KEY (`asset_id`),
  UNIQUE KEY `asset_serial` (`asset_serial`),
  KEY `asset_type_id` (`asset_type_id`),
  KEY `asset_vendor_id` (`asset_vendor_id`),
  KEY `asset_model_id` (`asset_model_id`),
  KEY `asset_building_id` (`asset_building_id`),
  KEY `asset_floor_id` (`asset_floor_id`),
  KEY `asset_room_id` (`asset_room_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

--
-- Daten für Tabelle `asset`
--

INSERT INTO `asset` (`asset_id`, `asset_type_id`, `asset_vendor_id`, `asset_model_id`, `asset_building_id`, `asset_floor_id`, `asset_room_id`, `asset_serial`, `asset_cis`, `asset_description`) VALUES
(62, 15, 21, 24, 1, 1, 1, '2049LZ54GQ79', '[]', '-'),
(63, 15, 21, 24, 1, 1, 1, '2049LZ54GPJ9', '[]', '-'),
(64, 16, 20, 25, 1, 2, 2, '3CQ5293BHB', '[]', '-'),
(65, 16, 20, 25, 1, 1, 3, '3CQ5330J43', '[]', '-'),
(67, 14, 20, 23, 1, 1, 1, '5CG812560Z', '[]', '-'),
(68, 14, 20, 23, 1, 1, 1, '5CG81251PD', '[["31",["Z1340289","10100651","11549851","10312830"]],["28","P0120AYYNC"]]', '-');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `building`
--

CREATE TABLE IF NOT EXISTS `building` (
  `building_id` int(11) NOT NULL AUTO_INCREMENT,
  `building_name` varchar(200) NOT NULL,
  PRIMARY KEY (`building_id`),
  UNIQUE KEY `building_name` (`building_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `building`
--

INSERT INTO `building` (`building_id`, `building_name`) VALUES
(1, 'Geb. 31');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ci`
--

CREATE TABLE IF NOT EXISTS `ci` (
  `ci_id` int(255) NOT NULL AUTO_INCREMENT,
  `ci_name` varchar(200) NOT NULL,
  `ci_type` enum('string','select','url','list') NOT NULL,
  `ci_regex` varchar(200) NOT NULL,
  PRIMARY KEY (`ci_id`),
  UNIQUE KEY `ci_name` (`ci_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Daten für Tabelle `ci`
--

INSERT INTO `ci` (`ci_id`, `ci_name`, `ci_type`, `ci_regex`) VALUES
(28, 'Hostname', 'string', 'A-Z0-9'),
(29, 'MAC-Adresse', 'string', 'A-F0-9\\:'),
(31, 'Diskencrypt User', 'list', 'a-zA-Z0-9öäüÖÄÜß\\s\\-\\.\\,');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `floor`
--

CREATE TABLE IF NOT EXISTS `floor` (
  `floor_id` int(11) NOT NULL AUTO_INCREMENT,
  `floor_name` varchar(200) NOT NULL,
  PRIMARY KEY (`floor_id`),
  UNIQUE KEY `floor_name` (`floor_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `floor`
--

INSERT INTO `floor` (`floor_id`, `floor_name`) VALUES
(4, 'EG'),
(1, 'OG01'),
(2, 'OG02'),
(3, 'OG03');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lend`
--

CREATE TABLE IF NOT EXISTS `lend` (
  `lend_id` int(255) NOT NULL AUTO_INCREMENT,
  `lend_creator_id` int(255) NOT NULL,
  `lend_user_id` int(255) NOT NULL,
  `lend_assets` longtext NOT NULL,
  `lend_archived_assets` longtext NOT NULL,
  `lend_description` varchar(200) NOT NULL,
  `lend_start` date NOT NULL,
  `lend_end` date NOT NULL,
  `lend_last_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lend_archived` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`lend_id`),
  KEY `lend_creator_id` (`lend_creator_id`),
  KEY `lend_user_id` (`lend_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Daten für Tabelle `lend`
--

INSERT INTO `lend` (`lend_id`, `lend_creator_id`, `lend_user_id`, `lend_assets`, `lend_archived_assets`, `lend_description`, `lend_start`, `lend_end`, `lend_last_seen`, `lend_archived`) VALUES
(9, 11549851, 11506084, '["62"]', '[]', 'KIT Lehrgang', '2022-05-18', '2022-05-30', '2022-05-19 14:27:16', '0'),
(11, 11549851, 10096136, '["64"]', '[]', 'Austausch gegen Altgerät.', '2022-05-19', '2023-05-23', '2022-05-23 09:52:29', '0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model`
--

CREATE TABLE IF NOT EXISTS `model` (
  `model_id` int(11) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(200) NOT NULL,
  PRIMARY KEY (`model_id`),
  UNIQUE KEY `model_name` (`model_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Daten für Tabelle `model`
--

INSERT INTO `model` (`model_id`, `model_name`) VALUES
(24, 'C925e'),
(22, 'EliteBook 840 G7'),
(21, 'EliteBook 840 G8'),
(25, 'EliteDisplay E231e'),
(26, 'FZ-G1'),
(28, 'Genucard 3'),
(23, 'Probook 640 G2');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rank`
--

CREATE TABLE IF NOT EXISTS `rank` (
  `rank_id` int(255) NOT NULL AUTO_INCREMENT,
  `rank_name_long` varchar(200) NOT NULL,
  `rank_name_short` varchar(200) NOT NULL,
  PRIMARY KEY (`rank_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=72 ;

--
-- Daten für Tabelle `rank`
--

INSERT INTO `rank` (`rank_id`, `rank_name_long`, `rank_name_short`) VALUES
(1, 'Gefreiter', 'Gefr'),
(2, 'Obergefreiter', 'OGefr'),
(3, 'Hauptgefreiter', 'HptGefr'),
(4, 'Stabsgefreiter', 'StGefr'),
(5, 'Oberstabsgefreiter', 'OStGefr'),
(6, 'Korporal', 'Korporal'),
(7, 'Stabskorporal', 'StKorporal'),
(8, 'Maat', 'Maat'),
(9, 'Unteroffizier', 'Uffz'),
(10, 'Seekadett', 'SKad'),
(11, 'Fahnenjunker', 'Fhj'),
(12, 'Obermaat', 'OMaat'),
(13, 'Stabsunteroffizier', 'StUffz'),
(14, 'Bootsmann', 'Btsm'),
(15, 'Feldwebel', 'Fw'),
(16, 'Fähnrich zur See', 'Fähnr zS'),
(17, 'Fähnrich', 'Fähnr'),
(18, 'Oberbootsmann', 'OBtsm'),
(19, 'Oberfeldwebel', 'OFw'),
(20, 'Hauptbootsmann', 'HptBtsm'),
(21, 'Hauptfeldwebel', 'HptFw'),
(22, 'Oberfähnrich zur See', 'OFähnr zS'),
(23, 'Oberfähnrich', 'OFähnr'),
(24, 'Stabsbootsmann', 'StBtsm'),
(25, 'Stabsfeldwebel', 'StFw'),
(26, 'Oberstabsbootsmann', 'OStBtsm'),
(27, 'Oberstabsfeldwebel', 'OStFw'),
(28, 'Leutnant zur See', 'Lt zS'),
(29, 'Leutnant', 'Lt'),
(30, 'Oberleutnant zur See', 'OLt zS'),
(31, 'Oberleutnant', 'OLt'),
(32, 'Kapitänleutnant', 'KptLt'),
(33, 'Hauptmann', 'Hptm'),
(34, 'Stabskapitänleutnant', 'StKptLt'),
(35, 'Stabshauptmann', 'StHptm'),
(36, 'Korvettenkapitän', 'KKpt'),
(37, 'Major', 'Maj'),
(38, 'Fregattenkapitän', 'FKpt'),
(39, 'Oberstleutnant', 'Oberstlt'),
(40, 'Kapitän zur See', 'Kpt zS'),
(41, 'Oberst', 'Oberst'),
(42, 'Flottillenadmiral', 'FltlAdm'),
(43, 'Brigadegeneral', 'BrigGen'),
(44, 'Konteradmiral', 'KAdm'),
(45, 'Generalmajor', 'GenMaj'),
(46, 'Vizeadmiral', 'VAdm'),
(47, 'Generalleutnant', 'GenLt'),
(48, 'Admiral', 'Adm'),
(49, 'General', 'Gen'),
(50, 'Stabsarzt', 'StArzt'),
(51, 'Stabsapotheker', 'StAp'),
(52, 'Stabsveterinär', 'StVet'),
(53, 'Oberstabsarzt', 'OStArzt'),
(54, 'Oberstabsapotheker', 'OStAp'),
(55, 'Oberstabsveterinär', 'OStVet'),
(56, 'Flottillenarzt', 'FltlArzt'),
(57, 'Flottillenapotheker', 'FltlAp'),
(58, 'Oberfeldarzt', 'OFArzt'),
(59, 'Oberfeldapotheker', 'OFAp'),
(60, 'Oberfeldveterinär', 'OFVet'),
(61, 'Flottenarzt', 'FlArzt'),
(62, 'Oberstarzt', 'OberstArzt'),
(63, 'Oberstapotheker', 'OberstAp'),
(64, 'Oberstveterinär', 'OberstVet'),
(65, 'Admiralarzt', 'AdmArzt'),
(66, 'Generalarzt', 'GenArzt'),
(67, 'Generalapotheker', 'GenAp'),
(68, 'Admiralstabsarzt', 'AdmStArzt'),
(69, 'Generalstabsarzt', 'GenStArzt'),
(70, 'Admiraloberstabsarzt', 'AdmOStArzt'),
(71, 'Generaloberstabsarzt', 'GenOStArzt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_name` varchar(200) NOT NULL,
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_name` (`room_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `room`
--

INSERT INTO `room` (`room_id`, `room_name`) VALUES
(1, 'R. 1.33'),
(2, 'R. 2.39'),
(4, 'R. 2.51'),
(3, 'R. 3.11');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Daten für Tabelle `type`
--

INSERT INTO `type` (`type_id`, `type_name`) VALUES
(14, 'Laptop'),
(18, 'Mobile Security Device'),
(16, 'Monitor'),
(17, 'Tablet'),
(15, 'Webcam');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(8) NOT NULL,
  `user_rank_id` int(2) NOT NULL,
  `user_vname` varchar(200) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_building_id` int(255) NOT NULL DEFAULT '1',
  `user_floor_id` int(255) NOT NULL DEFAULT '1',
  `user_room_id` int(255) NOT NULL DEFAULT '1',
  `user_password` varchar(200) NOT NULL,
  `user_salt` varchar(10) NOT NULL,
  `user_active` enum('0','1') NOT NULL DEFAULT '0',
  `user_admin` enum('0','1') NOT NULL DEFAULT '0',
  `user_failed_login` enum('0','1','2') NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  KEY `user_building_id` (`user_building_id`),
  KEY `user_floor_id` (`user_floor_id`),
  KEY `user_room_id` (`user_room_id`),
  KEY `user_rank_id` (`user_rank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`user_id`, `user_rank_id`, `user_vname`, `user_name`, `user_email`, `user_building_id`, `user_floor_id`, `user_room_id`, `user_password`, `user_salt`, `user_active`, `user_admin`, `user_failed_login`) VALUES
(10096136, 34, 'Ralf', 'Gröbel', 'RalfGroebel@bundeswehr.org', 1, 2, 2, '5ebfd6ccd35767b28d927f95a0e16570e975587ecbe8d2d7ad1a493c2a4e5cd4', '1zsE%$ilJY', '0', '0', '0'),
(10097606, 32, 'Jörg', 'Kriegel', 'JoergKriegel@bundeswehr.org', 1, 1, 1, '540f1b9e7aa6127e57105cf6b5bd39b79863edae77df8e7953342d3312b7055a', '!wJTNQsj&&', '0', '0', '0'),
(10101969, 32, 'Lutz', 'Wundrack', 'LutzWundrack@bundeswehr.org', 1, 1, 1, '9d95f2ab95305cb7027d87322249c3bbe5105bfabca09d92fab2390e16574963', 'bBQ0o70wZe', '0', '0', '0'),
(10105851, 38, 'Thorsten', 'Klinger', 'ThorstenKlinger@bundeswehr.org', 1, 1, 1, 'aeb7542c85b80e74cb51a7b8406e80cdce554c78005bbabda79ae9ce243df798', '?Ba6dXc7XN', '0', '0', '0'),
(11506084, 20, 'Pascal', 'Schümann', 'PascalSchuemann@bundeswehr.org', 1, 1, 1, 'eec3077aa717e200254515158e30c8b0bc9a292452fc3a459125763826bbaa03', '0iytpEMcsv', '0', '0', '0'),
(11549851, 12, 'Alexander', 'Brosch', 'alexanderbrosch@bundeswehr.org', 1, 1, 1, '78be7d99d95e79e9807476f6964f97c8560ee703c9aff44d8d94f1f8e8b81b56', 'j2GVy94PPP', '1', '1', '0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vendor`
--

CREATE TABLE IF NOT EXISTS `vendor` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(200) NOT NULL,
  PRIMARY KEY (`vendor_id`),
  UNIQUE KEY `vendor_name` (`vendor_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Daten für Tabelle `vendor`
--

INSERT INTO `vendor` (`vendor_id`, `vendor_name`) VALUES
(23, 'Genua'),
(20, 'HP'),
(21, 'Logitech'),
(22, 'Panasonic');

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `asset`
--
ALTER TABLE `asset`
  ADD CONSTRAINT `asset_ibfk_1` FOREIGN KEY (`asset_type_id`) REFERENCES `type` (`type_id`),
  ADD CONSTRAINT `asset_ibfk_2` FOREIGN KEY (`asset_vendor_id`) REFERENCES `vendor` (`vendor_id`),
  ADD CONSTRAINT `asset_ibfk_3` FOREIGN KEY (`asset_model_id`) REFERENCES `model` (`model_id`),
  ADD CONSTRAINT `asset_ibfk_4` FOREIGN KEY (`asset_building_id`) REFERENCES `building` (`building_id`),
  ADD CONSTRAINT `asset_ibfk_5` FOREIGN KEY (`asset_floor_id`) REFERENCES `floor` (`floor_id`),
  ADD CONSTRAINT `asset_ibfk_6` FOREIGN KEY (`asset_room_id`) REFERENCES `room` (`room_id`);

--
-- Constraints der Tabelle `lend`
--
ALTER TABLE `lend`
  ADD CONSTRAINT `lend_ibfk_1` FOREIGN KEY (`lend_creator_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `lend_ibfk_2` FOREIGN KEY (`lend_user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_building_id`) REFERENCES `building` (`building_id`),
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`user_floor_id`) REFERENCES `floor` (`floor_id`),
  ADD CONSTRAINT `user_ibfk_3` FOREIGN KEY (`user_room_id`) REFERENCES `room` (`room_id`),
  ADD CONSTRAINT `user_ibfk_4` FOREIGN KEY (`user_rank_id`) REFERENCES `rank` (`rank_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
