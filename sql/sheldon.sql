-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 10. Mai 2022 um 14:46
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
  `asset_keywords` longtext NOT NULL,
  PRIMARY KEY (`asset_id`),
  UNIQUE KEY `asset_serial` (`asset_serial`),
  KEY `asset_type_id` (`asset_type_id`),
  KEY `asset_vendor_id` (`asset_vendor_id`),
  KEY `asset_model_id` (`asset_model_id`),
  KEY `asset_building_id` (`asset_building_id`),
  KEY `asset_floor_id` (`asset_floor_id`),
  KEY `asset_room_id` (`asset_room_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

--
-- Daten für Tabelle `asset`
--

INSERT INTO `asset` (`asset_id`, `asset_type_id`, `asset_vendor_id`, `asset_model_id`, `asset_building_id`, `asset_floor_id`, `asset_room_id`, `asset_serial`, `asset_cis`, `asset_keywords`) VALUES
(44, 14, 20, 22, 1, 1, 1, '5CG204326B', '[["28","DEUKEL1AMEF01I0"],["29","C0:18:03:CD:C3:53"]]', 'Laptop HP EliteBook 840 G7 5CG204326B'),
(45, 14, 20, 22, 1, 1, 1, '5CG2043PN3', '[]', 'Laptop HP EliteBook 840 G7 5CG2043PN3'),
(46, 14, 20, 22, 1, 1, 1, '5CG2043PB4', '[]', 'Laptop HP EliteBook 840 G7 5CG2043PB4'),
(47, 14, 20, 22, 1, 1, 1, '5CG2043PS8', '[]', 'Laptop HP EliteBook 840 G7 5CG2043PS8'),
(48, 14, 20, 22, 1, 1, 1, '5CG20435ZG', '[]', 'Laptop HP EliteBook 840 G7 5CG20435ZG'),
(49, 14, 20, 22, 1, 1, 1, '5CG2043QHY', '[]', 'Laptop HP EliteBook 840 G7 5CG2043QHY'),
(50, 14, 20, 21, 1, 1, 1, '5CG1383JNX', '[]', 'Laptop HP EliteBook 840 G8 5CG1383JNX'),
(51, 14, 20, 21, 1, 1, 1, '5CG1383HM7', '[["28","DEUKEL1AMEF01I3"],["29","E0:70:EA:DC:34:A5"],["30",["2x USB-A","2x USB-C","1x HDMI","1x Klinke 3.5mm","1x SC"]]]', 'Laptop HP EliteBook 840 G8 5CG1383HM7'),
(52, 14, 20, 21, 1, 1, 1, '5CG1383HM2', '[["28","DEUKEL1AMEF01I5"],["29","E0:70:EA:DC:33:76"],["30",["2x USB-A","2x USB-C","1x HDMI","1x Klinke 3.5mm","1x SC"]]]', 'Laptop HP EliteBook 840 G8 5CG1383HM2'),
(53, 14, 20, 21, 1, 1, 1, '5CG1380WV2', '[]', 'Laptop HP EliteBook 840 G8 5CG1380WV2'),
(54, 14, 20, 21, 1, 1, 1, '5CG1380WTJ', '[]', 'Laptop HP EliteBook 840 G8 5CG1380WTJ'),
(55, 14, 20, 21, 1, 1, 1, '5CG1380XHQ', '[]', 'Laptop HP EliteBook 840 G8 5CG1380XHQ'),
(56, 14, 20, 21, 1, 1, 1, '5CG1380WT4', '[]', 'Laptop HP EliteBook 840 G8 5CG1380WT4'),
(57, 14, 20, 21, 1, 1, 1, '5CG1383HLK', '[]', 'Laptop HP EliteBook 840 G8 5CG1383HLK'),
(58, 14, 20, 21, 1, 1, 1, '5CG1383HSF', '[]', 'Laptop HP EliteBook 840 G8 5CG1383HSF'),
(59, 14, 20, 21, 1, 1, 1, '5CG137C2GT', '[]', 'Laptop HP EliteBook 840 G8 5CG137C2GT'),
(60, 14, 20, 21, 1, 1, 1, '5CG1383HS7', '[]', 'Laptop HP EliteBook 840 G8 5CG1383HS7'),
(61, 14, 20, 21, 1, 1, 1, '5CG1383HM0', '[]', 'Laptop HP EliteBook 840 G8 5CG1383HM0');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Daten für Tabelle `ci`
--

INSERT INTO `ci` (`ci_id`, `ci_name`, `ci_type`, `ci_regex`) VALUES
(28, 'Hostname', 'string', 'A-Z0-9'),
(29, 'MAC-Adresse', 'string', 'A-Z0-9\\:'),
(30, 'Anschlüsse', 'list', '["USB","HDMI","VGA","DisplayPort"]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `floor`
--

CREATE TABLE IF NOT EXISTS `floor` (
  `floor_id` int(11) NOT NULL AUTO_INCREMENT,
  `floor_name` varchar(200) NOT NULL,
  PRIMARY KEY (`floor_id`),
  UNIQUE KEY `floor_name` (`floor_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `floor`
--

INSERT INTO `floor` (`floor_id`, `floor_name`) VALUES
(1, 'OG01');

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
  `lend_last_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lend_end` date NOT NULL,
  `lend_archived` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`lend_id`),
  KEY `lend_creator_id` (`lend_creator_id`),
  KEY `lend_user_id` (`lend_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `lend`
--

INSERT INTO `lend` (`lend_id`, `lend_creator_id`, `lend_user_id`, `lend_assets`, `lend_archived_assets`, `lend_description`, `lend_last_seen`, `lend_end`, `lend_archived`) VALUES
(8, 11549851, 11549851, '[]', '[["46","10.05.2022"],["44","10.05.2022"]]', '-', '2022-05-10 14:44:24', '2022-05-20', '1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model`
--

CREATE TABLE IF NOT EXISTS `model` (
  `model_id` int(11) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(200) NOT NULL,
  PRIMARY KEY (`model_id`),
  UNIQUE KEY `model_name` (`model_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Daten für Tabelle `model`
--

INSERT INTO `model` (`model_id`, `model_name`) VALUES
(22, 'EliteBook 840 G7'),
(21, 'EliteBook 840 G8'),
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `room`
--

INSERT INTO `room` (`room_id`, `room_name`) VALUES
(1, 'R. 1.33');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `type`
--

INSERT INTO `type` (`type_id`, `type_name`) VALUES
(14, 'Laptop');

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
  `user_keywords` longtext NOT NULL,
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

INSERT INTO `user` (`user_id`, `user_rank_id`, `user_vname`, `user_name`, `user_email`, `user_building_id`, `user_floor_id`, `user_room_id`, `user_password`, `user_salt`, `user_active`, `user_admin`, `user_failed_login`, `user_keywords`) VALUES
(11549851, 12, 'Alexander', 'Brosch', 'alexanderbrosch@bundeswehr.org', 1, 1, 1, '78be7d99d95e79e9807476f6964f97c8560ee703c9aff44d8d94f1f8e8b81b56', 'j2GVy94PPP', '1', '1', '0', '11549851 Obermaat Alexander Brosch alexanderbrosch@bundeswehr.org'),
(99999999, 48, 'EF1', 'Admin', 'einsfltl1itmanagement@bundeswehr.org', 1, 1, 1, '78be7d99d95e79e9807476f6964f97c8560ee703c9aff44d8d94f1f8e8b81b56', 'j2GVy94PPP', '1', '1', '0', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vendor`
--

CREATE TABLE IF NOT EXISTS `vendor` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(200) NOT NULL,
  PRIMARY KEY (`vendor_id`),
  UNIQUE KEY `vendor_name` (`vendor_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Daten für Tabelle `vendor`
--

INSERT INTO `vendor` (`vendor_id`, `vendor_name`) VALUES
(20, 'HP');

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
