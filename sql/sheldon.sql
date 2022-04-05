-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 05. Apr 2022 um 15:08
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Daten für Tabelle `asset`
--

INSERT INTO `asset` (`asset_id`, `asset_type_id`, `asset_vendor_id`, `asset_model_id`, `asset_building_id`, `asset_floor_id`, `asset_room_id`, `asset_serial`, `asset_cis`, `asset_keywords`) VALUES
(3, 4, 1, 8, 2, 3, 3, '5CG812560Z', '[["4","P0120A6BXC"],["7","B4:B6:86:24:6D:A0"]]', 'Laptop HP Probook 640G2 5CG812560Z'),
(4, 4, 1, 8, 2, 3, 3, '5CG8125KY7', '[["4","P0120A6BXC"],["7","B4:B6:86:24:6D:A0"],["16","https:\\/\\/support.hp.com\\/de-de\\/document\\/c04940851"]]', 'Laptop HP Probook 640G2 5CG8125KY7'),
(5, 4, 1, 8, 1, 1, 1, '5CG8125KLP', '[["4","P0120AWS2C"],["7","B4:B6:86:24:B3:16"]]', 'Laptop HP Probook 640G2 5CG8125KLP'),
(7, 4, 1, 8, 2, 3, 3, '5CG8125PLW', '[["4","P0120AXS9C"],["7","B4:B6:86:24:8E:4C"],["16","https:\\/\\/support.hp.com\\/de-de\\/document\\/c04940851"]]', 'Laptop HP Probook 640G2 5CG8125PLW'),
(11, 4, 1, 8, 2, 3, 3, '5CG8125KW8', '[["4","P0120AWRSC"],["7","B4:B6:86:24:CD:FD"],["15","http:\\/\\/localhost\\/view.php?category=asset&id=32&tab=general"]]', 'Laptop HP Probook 640G2 5CG8125KW8'),
(12, 4, 1, 8, 1, 1, 1, '5CG8125NLC', '[["4","P0120B5Q8C"],["7","B4:B6:86:24:3F:15"],["15","http:\\/\\/localhost\\/view.php?category=asset&id=31&tab=general"]]', 'Laptop HP Probook 640G2 5CG8125NLC'),
(13, 4, 1, 8, 1, 1, 1, '5CG81251PD', '[["4","P0120AYYNC"],["7","B4:B6:86:24:0A:6D"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG81251PD'),
(14, 4, 1, 8, 1, 1, 1, '5CG8125S2F', '[["4","P0120AWS8C"],["7","B4:B6:86:25:50:70"],["8","registriert"]]', 'Laptop HP Probook 640G2 5CG8125S2F'),
(15, 4, 1, 8, 1, 1, 1, '5CG812565Y', '[["4","P0120A70EC"],["7","B4:B6:86:24:EB:03"],["8","registriert"]]', 'Laptop HP Probook 640G2 5CG812565Y'),
(16, 4, 1, 8, 1, 1, 1, '5CG8125ND9', '[["4","P0120AWSEC"],["7","B4:B6:86:24:EF:C3"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG8125ND9'),
(17, 4, 1, 8, 1, 1, 1, '5CG8125NK6', '[["4","P0120AWPHC"],["7","B4:B6:86:24:0F:60"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG8125NK6'),
(18, 4, 1, 8, 1, 1, 1, '5CG8125NKD', '[["4","P0120BG38C"],["7","B4:B6:86:24:5F:D3"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG8125NKD'),
(19, 4, 1, 8, 1, 1, 1, '5CG8125S06', '[["4","P0120A69TC"],["7","B4:B6:86:24:BE:07"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG8125S06'),
(20, 4, 1, 8, 1, 1, 1, '5CG8125LFJ', '[["4","P0120B5Q1C"],["7","B4:B6:86:24:CE:F3"],["15","http:\\/\\/localhost\\/view.php?category=asset&id=30&tab=general"]]', 'Laptop HP Probook 640G2 5CG8125LFJ'),
(21, 4, 1, 12, 1, 1, 1, '5CG9173XNC', '[["4","P01208GFXC"],["7","9C:7B:EF:75:98:02"]]', 'Laptop HP EliteBook 840G5 5CG9173XNC'),
(22, 4, 1, 8, 1, 1, 1, '5CG8125NBZ', '[["4","P0120AWRWC"],["7","B4:B6:86:24:FE:19"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG8125NBZ'),
(23, 4, 1, 8, 1, 1, 1, '5CG8125MDC', '[["4","P0120AYPWC"],["7","B4:B6:86:25:11:EE"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG8125MDC'),
(24, 4, 1, 8, 1, 1, 1, '5CG8125GLZ', '[["4","P0120AWV9C"],["7","B4:B6:86:25:21:7B"],["15","http:\\/\\/localhost\\/view.php?category=asset&id=33&tab=general"]]', 'Laptop HP Probook 640G2 5CG8125GLZ'),
(25, 4, 1, 9, 1, 1, 1, '5CG0218NLP', '[["4","P0120BZKHC"],["7","B0:5C:DA:EE:EC:74"],["8","registriert"]]', 'Laptop HP EliteBook 840G6 5CG0218NLP'),
(26, 4, 1, 8, 1, 1, 1, '5CG8125KVG', '[["4","P0120A67PC"],["7","B4:B6:86:24:7D:69"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG8125KVG'),
(27, 4, 1, 8, 1, 1, 1, '5CG81259M9', '[["4","P0120AWRCC"],["7","B4:B6:86:25:70:AB"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG81259M9'),
(28, 4, 1, 8, 1, 1, 1, '5CG8125N13', '[["4","P0120A69VC"],["7","B4:B6:86:24:CF:9D"],["8","nicht registriert"]]', 'Laptop HP Probook 640G2 5CG8125N13'),
(29, 4, 1, 9, 1, 1, 1, '5CG017D9DT', '[["4","P0120BSKWC"],["7","B0:5C:DA:A9:0D:42"],["8","nicht registriert"]]', 'Laptop HP EliteBook 840G6 5CG017D9DT'),
(30, 11, 18, 14, 1, 1, 1, '180485018591', '[["16","https:\\/\\/www.genua.de\\/it-sicherheitsloesungen\\/personal-security-device-genucard"]]', 'Mobile Security Device Genua Genucard 3 180485018591'),
(31, 11, 18, 14, 1, 1, 1, '180485018612', '[["16","https:\\/\\/www.genua.de\\/it-sicherheitsloesungen\\/personal-security-device-genucard"]]', 'Mobile Security Device Genua Genucard 3 180485018612'),
(32, 11, 18, 14, 1, 1, 1, '180485018625', '[["16","https:\\/\\/www.genua.de\\/it-sicherheitsloesungen\\/personal-security-device-genucard"]]', 'Mobile Security Device Genua Genucard 3 180485018625'),
(33, 11, 18, 14, 1, 1, 1, '180485015149', '[["16","https:\\/\\/www.genua.de\\/it-sicherheitsloesungen\\/personal-security-device-genucard"]]', 'Mobile Security Device Genua Genucard 3 180485015149'),
(34, 12, 1, 15, 3, 2, 4, 'CZK8170CL4', '[]', 'Monitor HP L2245W CZK8170CL4'),
(35, 3, 19, 10, 1, 1, 1, 'Q72W947AAAAAC0139', '[["18","1"],["17","4200"]]', 'Beamer Optoma EH-416 Q72W947AAAAAC0139');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `building`
--

CREATE TABLE IF NOT EXISTS `building` (
  `building_id` int(11) NOT NULL AUTO_INCREMENT,
  `building_name` varchar(200) NOT NULL,
  PRIMARY KEY (`building_id`),
  UNIQUE KEY `building_name` (`building_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `building`
--

INSERT INTO `building` (`building_id`, `building_name`) VALUES
(1, 'Eingang'),
(4, 'Geb. 15'),
(3, 'Geb. 17'),
(5, 'Geb. 22'),
(2, 'Geb. 31');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ci`
--

CREATE TABLE IF NOT EXISTS `ci` (
  `ci_id` int(255) NOT NULL AUTO_INCREMENT,
  `ci_name` varchar(200) NOT NULL,
  `ci_type` enum('string','select','url') NOT NULL,
  `ci_regex` varchar(200) NOT NULL,
  PRIMARY KEY (`ci_id`),
  UNIQUE KEY `ci_name` (`ci_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Daten für Tabelle `ci`
--

INSERT INTO `ci` (`ci_id`, `ci_name`, `ci_type`, `ci_regex`) VALUES
(4, 'Hostname', 'string', 'A-Z0-9'),
(7, 'MAC Adresse', 'string', 'A-Z0-9\\:'),
(8, 'IT-Poolverwalter', 'select', '["registriert","nicht registriert"]'),
(15, 'Genucard-URL', 'url', 'a-zA-Z0-9\\?\\&\\=\\.\\:\\/\\_'),
(16, 'Homepage', 'url', 'a-zA-Z0-9\\?\\&\\=\\.\\:\\/\\_\\-'),
(17, 'Lumen', 'string', '0-9'),
(18, 'Auflösung', 'select', '["720p","1080p"]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `floor`
--

CREATE TABLE IF NOT EXISTS `floor` (
  `floor_id` int(11) NOT NULL AUTO_INCREMENT,
  `floor_name` varchar(200) NOT NULL,
  PRIMARY KEY (`floor_id`),
  UNIQUE KEY `floor_name` (`floor_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `floor`
--

INSERT INTO `floor` (`floor_id`, `floor_name`) VALUES
(2, 'EG'),
(1, 'Eingang'),
(3, 'OG 01'),
(4, 'OG 02'),
(5, 'OG 03');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lend`
--

CREATE TABLE IF NOT EXISTS `lend` (
  `lend_id` int(255) NOT NULL AUTO_INCREMENT,
  `lend_document_nr` int(255) NOT NULL,
  `lend_creator_id` int(255) NOT NULL,
  `lend_user_id` int(255) NOT NULL,
  `lend_assets` longtext NOT NULL,
  `lend_start` date NOT NULL,
  `lend_end` date NOT NULL,
  `lend_archived` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`lend_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `lend`
--

INSERT INTO `lend` (`lend_id`, `lend_document_nr`, `lend_creator_id`, `lend_user_id`, `lend_assets`, `lend_start`, `lend_end`, `lend_archived`) VALUES
(2, 1649169741, 11549851, 10103631, '["25","29"]', '2022-04-05', '2022-04-30', '0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model`
--

CREATE TABLE IF NOT EXISTS `model` (
  `model_id` int(11) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(200) NOT NULL,
  PRIMARY KEY (`model_id`),
  UNIQUE KEY `model_name` (`model_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Daten für Tabelle `model`
--

INSERT INTO `model` (`model_id`, `model_name`) VALUES
(7, 'A 415'),
(5, 'B525'),
(10, 'EH-416'),
(12, 'EliteBook 840G5'),
(9, 'EliteBook 840G6'),
(1, 'EliteDisplay E231'),
(2, 'EliteDisplay E232'),
(13, 'FZ-55'),
(14, 'Genucard 3'),
(15, 'L2245W'),
(8, 'ProBook 640G2'),
(11, 'XJ-A252');

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
(1, 'Eingang'),
(3, 'R. 1.33'),
(4, 'R. 18');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `type`
--

INSERT INTO `type` (`type_id`, `type_name`) VALUES
(3, 'Beamer'),
(7, 'Drucker'),
(4, 'Laptop'),
(11, 'Mobile Security Device'),
(12, 'Monitor'),
(10, 'Smartphone'),
(9, 'Tablet'),
(2, 'Webcam'),
(5, 'Wlan-Repeater');

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

INSERT INTO `user` (`user_id`, `user_rank_id`, `user_vname`, `user_name`, `user_email`, `user_building_id`, `user_floor_id`, `user_room_id`, `user_password`, `user_salt`, `user_active`, `user_admin`, `user_keywords`) VALUES
(10103631, 26, 'Oliver', 'Diester', 'oliverdiester@bundeswehr.org', 1, 1, 1, 'e82ee6a97cf70d44602d52717bc85387fc02df0ef062f3aab43ac53905d8bf3f', 'K92GfL%Eau', '0', '0', '10103631 Oberstabsbootsmann Oliver Diester oliverdiester@bundeswehr.org'),
(11549851, 12, 'Alexander', 'Brosch', 'alexanderbrosch@bundeswehr.org', 2, 3, 3, '78be7d99d95e79e9807476f6964f97c8560ee703c9aff44d8d94f1f8e8b81b56', 'j2GVy94PPP', '1', '1', '11549851 Obermaat Alexander Brosch alexanderbrosch@bundeswehr.org'),
(11761012, 12, 'Marcin', 'Jurek', 'marcinjurek@bundeswehr.org', 1, 1, 1, 'cbae6a38acaaa78ec88d6a62a864cab361efe9381405961e28495c54b66fc587', 'LbDJSBAIpH', '0', '0', '11761012 Obermaat Marcin Jurek marcinjurek@bundeswehr.org'),
(11837260, 24, 'Arne', 'Roemer', 'arne1roemer@bundeswehr.org', 1, 1, 1, 'd175051a5031cd32593c4144e6158cc41cd75c56bb08ab435ec3cda996a81dfc', 'N6wZEYEI6Q', '0', '0', '11837260 Stabsbootsmann Arne Roemer arne1roemer@bundeswehr.org');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vendor`
--

CREATE TABLE IF NOT EXISTS `vendor` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(200) NOT NULL,
  PRIMARY KEY (`vendor_id`),
  UNIQUE KEY `vendor_name` (`vendor_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Daten für Tabelle `vendor`
--

INSERT INTO `vendor` (`vendor_id`, `vendor_name`) VALUES
(18, 'Genua'),
(16, 'Gigaset'),
(1, 'HP'),
(2, 'Logitech'),
(19, 'Optoma');

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
