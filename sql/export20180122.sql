-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 22 Janvier 2018 à 08:34
-- Version du serveur: 5.5.57-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `pf_management`
--

-- --------------------------------------------------------

--
-- Structure de la table `assignment_participant_department`
--

CREATE TABLE IF NOT EXISTS `assignment_participant_department` (
  `PARTICIPANT_id_participant` int(11) unsigned NOT NULL COMMENT 'PK participant',
  `DEPARTMENT_id_department` int(11) unsigned NOT NULL COMMENT 'PK department',
  PRIMARY KEY (`PARTICIPANT_id_participant`,`DEPARTMENT_id_department`),
  KEY `ix_assignment_participant_department_A` (`PARTICIPANT_id_participant`),
  KEY `ix_assignment_participant_department_B` (`DEPARTMENT_id_department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `assignment_participant_department`
--

INSERT INTO `assignment_participant_department` (`PARTICIPANT_id_participant`, `DEPARTMENT_id_department`) VALUES
(1, 99),
(2, 44),
(3, 99),
(7, 99),
(8, 99),
(9, 99),
(10, 99),
(11, 99),
(12, 99),
(13, 99),
(14, 99),
(15, 99),
(16, 99),
(21, 99),
(22, 99),
(24, 99),
(25, 99),
(26, 99),
(27, 99),
(28, 99),
(29, 99),
(30, 99),
(31, 99),
(32, 99),
(33, 99),
(34, 99),
(35, 99),
(36, 99),
(37, 99),
(38, 99),
(41, 99),
(42, 99),
(43, 99),
(44, 99),
(45, 99),
(47, 99),
(48, 99),
(49, 99),
(50, 99),
(51, 99),
(52, 99),
(53, 99),
(54, 99),
(55, 99),
(56, 99),
(57, 99),
(59, 99);

-- --------------------------------------------------------

--
-- Structure de la table `assignment_participant_enterprise`
--

CREATE TABLE IF NOT EXISTS `assignment_participant_enterprise` (
  `PARTICIPANT_id_participant` int(11) unsigned NOT NULL COMMENT 'PK participant',
  `ENTERPRISE_id_enterprise` int(11) unsigned NOT NULL COMMENT 'PK enterprise',
  PRIMARY KEY (`PARTICIPANT_id_participant`,`ENTERPRISE_id_enterprise`),
  KEY `ix_assignment_participant_enterprise_A` (`PARTICIPANT_id_participant`),
  KEY `ix_assignment_participant_enterprise_B` (`ENTERPRISE_id_enterprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `assignment_participant_enterprise`
--

INSERT INTO `assignment_participant_enterprise` (`PARTICIPANT_id_participant`, `ENTERPRISE_id_enterprise`) VALUES
(1, 51),
(1, 52),
(1, 53),
(2, 1),
(3, 51),
(3, 52),
(7, 54),
(7, 60),
(8, 55),
(9, 56),
(10, 56),
(11, 57),
(12, 57),
(13, 57),
(14, 58),
(15, 59),
(15, 62),
(15, 68),
(16, 60),
(16, 67),
(21, 61),
(22, 61),
(24, 63),
(25, 64),
(26, 64),
(27, 64),
(28, 64),
(29, 65),
(30, 65),
(31, 66),
(32, 66),
(33, 66),
(34, 66),
(35, 66),
(36, 66),
(37, 66),
(38, 66),
(41, 69),
(42, 69),
(43, 70),
(43, 71),
(44, 70),
(44, 71),
(45, 70),
(45, 71),
(47, 72),
(48, 73),
(49, 74),
(49, 79),
(50, 75),
(51, 76),
(52, 76),
(53, 51),
(53, 52),
(54, 54),
(55, 58),
(56, 77),
(57, 77),
(59, 78);

-- --------------------------------------------------------

--
-- Structure de la table `assignment_sp_store`
--

CREATE TABLE IF NOT EXISTS `assignment_sp_store` (
  `PARTICIPANT_id_participant` int(11) unsigned NOT NULL COMMENT 'PK salesperson',
  `ENTERPRISE_id_enterprise` int(11) unsigned NOT NULL COMMENT 'PK store',
  `PURCHASING_FAIR_id_purchasing_fair` int(11) unsigned NOT NULL COMMENT 'PK purchasing_fair',
  PRIMARY KEY (`PARTICIPANT_id_participant`,`ENTERPRISE_id_enterprise`,`PURCHASING_FAIR_id_purchasing_fair`),
  KEY `ix_assign_sp_store_participant` (`PARTICIPANT_id_participant`),
  KEY `ix_assign_sp_store_enterprise` (`ENTERPRISE_id_enterprise`),
  KEY `ix_assign_sp_store_pf` (`PURCHASING_FAIR_id_purchasing_fair`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `assignment_sp_store`
--

INSERT INTO `assignment_sp_store` (`PARTICIPANT_id_participant`, `ENTERPRISE_id_enterprise`, `PURCHASING_FAIR_id_purchasing_fair`) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 6, 2),
(1, 8, 2),
(1, 9, 2),
(1, 13, 2),
(1, 14, 2),
(1, 15, 2),
(1, 21, 2),
(1, 22, 2),
(1, 26, 2),
(1, 27, 2),
(1, 33, 2),
(1, 34, 2),
(1, 35, 2),
(1, 36, 2),
(1, 37, 2),
(1, 38, 2),
(1, 40, 2),
(1, 46, 2),
(1, 47, 2),
(3, 5, 2),
(3, 16, 2),
(3, 17, 2),
(3, 19, 2),
(3, 20, 2),
(3, 25, 2),
(3, 43, 2),
(3, 45, 2),
(7, 6, 2),
(7, 7, 2),
(7, 8, 2),
(7, 9, 2),
(7, 10, 2),
(7, 11, 2),
(7, 12, 2),
(7, 13, 2),
(7, 14, 2),
(7, 15, 2),
(7, 16, 2),
(7, 17, 2),
(7, 18, 2),
(7, 19, 2),
(7, 20, 2),
(7, 21, 2),
(7, 22, 2),
(7, 23, 2),
(7, 24, 2),
(7, 25, 2),
(7, 26, 2),
(7, 27, 2),
(7, 28, 2),
(7, 29, 2),
(7, 30, 2),
(7, 31, 2),
(7, 33, 2),
(7, 34, 2),
(7, 35, 2),
(7, 36, 2),
(7, 37, 2),
(7, 38, 2),
(7, 39, 2),
(7, 40, 2),
(7, 41, 2),
(7, 42, 2),
(7, 43, 2),
(7, 44, 2),
(7, 45, 2),
(7, 46, 2),
(7, 47, 2),
(7, 48, 2),
(8, 1, 2),
(8, 2, 2),
(8, 3, 2),
(8, 4, 2),
(8, 5, 2),
(8, 6, 2),
(8, 7, 2),
(8, 8, 2),
(8, 9, 2),
(8, 10, 2),
(8, 11, 2),
(8, 12, 2),
(8, 13, 2),
(8, 14, 2),
(8, 15, 2),
(8, 16, 2),
(8, 17, 2),
(8, 18, 2),
(8, 19, 2),
(8, 20, 2),
(8, 21, 2),
(8, 22, 2),
(8, 23, 2),
(8, 24, 2),
(8, 25, 2),
(8, 26, 2),
(8, 27, 2),
(8, 28, 2),
(8, 29, 2),
(8, 30, 2),
(8, 31, 2),
(8, 32, 2),
(8, 33, 2),
(8, 34, 2),
(8, 35, 2),
(8, 36, 2),
(8, 37, 2),
(8, 38, 2),
(8, 39, 2),
(8, 40, 2),
(8, 41, 2),
(8, 42, 2),
(8, 43, 2),
(8, 44, 2),
(8, 45, 2),
(8, 46, 2),
(8, 47, 2),
(8, 48, 2),
(9, 1, 2),
(9, 4, 2),
(9, 7, 2),
(9, 11, 2),
(9, 12, 2),
(9, 13, 2),
(9, 14, 2),
(9, 15, 2),
(9, 18, 2),
(9, 21, 2),
(9, 24, 2),
(9, 26, 2),
(9, 27, 2),
(9, 28, 2),
(9, 29, 2),
(9, 31, 2),
(9, 33, 2),
(9, 34, 2),
(9, 36, 2),
(9, 37, 2),
(9, 40, 2),
(9, 41, 2),
(9, 42, 2),
(9, 44, 2),
(9, 46, 2),
(9, 47, 2),
(10, 2, 2),
(10, 30, 2),
(10, 32, 2),
(10, 48, 2),
(11, 3, 2),
(11, 7, 2),
(11, 12, 2),
(11, 14, 2),
(11, 16, 2),
(11, 17, 2),
(11, 18, 2),
(11, 19, 2),
(11, 20, 2),
(11, 21, 2),
(11, 23, 2),
(11, 25, 2),
(11, 32, 2),
(11, 33, 2),
(11, 34, 2),
(11, 43, 2),
(11, 45, 2),
(11, 47, 2),
(11, 48, 2),
(12, 2, 2),
(12, 4, 2),
(12, 6, 2),
(12, 8, 2),
(12, 9, 2),
(12, 10, 2),
(12, 11, 2),
(12, 13, 2),
(12, 15, 2),
(12, 24, 2),
(12, 26, 2),
(12, 27, 2),
(12, 28, 2),
(12, 29, 2),
(12, 31, 2),
(12, 37, 2),
(12, 38, 2),
(12, 39, 2),
(12, 40, 2),
(12, 41, 2),
(12, 42, 2),
(12, 44, 2),
(12, 46, 2),
(13, 1, 2),
(13, 5, 2),
(13, 22, 2),
(13, 30, 2),
(13, 35, 2),
(14, 1, 2),
(14, 2, 2),
(14, 3, 2),
(14, 4, 2),
(14, 5, 2),
(14, 6, 2),
(14, 7, 2),
(14, 8, 2),
(14, 9, 2),
(14, 10, 2),
(14, 11, 2),
(14, 12, 2),
(14, 14, 2),
(14, 15, 2),
(14, 16, 2),
(14, 17, 2),
(14, 18, 2),
(14, 19, 2),
(14, 20, 2),
(14, 21, 2),
(14, 22, 2),
(14, 23, 2),
(14, 25, 2),
(14, 26, 2),
(14, 27, 2),
(14, 29, 2),
(14, 30, 2),
(14, 32, 2),
(14, 33, 2),
(14, 34, 2),
(14, 35, 2),
(14, 36, 2),
(14, 38, 2),
(14, 39, 2),
(14, 40, 2),
(14, 43, 2),
(14, 45, 2),
(14, 46, 2),
(14, 47, 2),
(14, 48, 2),
(15, 1, 2),
(15, 2, 2),
(15, 3, 2),
(15, 4, 2),
(15, 5, 2),
(15, 6, 2),
(15, 7, 2),
(15, 8, 2),
(15, 9, 2),
(15, 10, 2),
(15, 11, 2),
(15, 12, 2),
(15, 13, 2),
(15, 14, 2),
(15, 15, 2),
(15, 16, 2),
(15, 17, 2),
(15, 18, 2),
(15, 19, 2),
(15, 20, 2),
(15, 21, 2),
(15, 22, 2),
(15, 23, 2),
(15, 24, 2),
(15, 25, 2),
(15, 26, 2),
(15, 27, 2),
(15, 28, 2),
(15, 29, 2),
(15, 30, 2),
(15, 31, 2),
(15, 32, 2),
(15, 33, 2),
(15, 34, 2),
(15, 35, 2),
(15, 36, 2),
(15, 37, 2),
(15, 38, 2),
(15, 39, 2),
(15, 40, 2),
(15, 41, 2),
(15, 42, 2),
(15, 43, 2),
(15, 44, 2),
(15, 45, 2),
(15, 46, 2),
(15, 47, 2),
(15, 48, 2),
(16, 1, 2),
(16, 2, 2),
(16, 3, 2),
(16, 4, 2),
(16, 6, 2),
(16, 7, 2),
(16, 8, 2),
(16, 9, 2),
(16, 10, 2),
(16, 13, 2),
(16, 14, 2),
(16, 15, 2),
(16, 18, 2),
(16, 22, 2),
(16, 24, 2),
(16, 26, 2),
(16, 27, 2),
(16, 28, 2),
(16, 29, 2),
(16, 30, 2),
(16, 31, 2),
(16, 33, 2),
(16, 35, 2),
(16, 36, 2),
(16, 37, 2),
(16, 38, 2),
(16, 40, 2),
(16, 41, 2),
(16, 42, 2),
(16, 44, 2),
(16, 46, 2),
(16, 47, 2),
(24, 1, 2),
(24, 2, 2),
(24, 3, 2),
(24, 4, 2),
(24, 5, 2),
(24, 6, 2),
(24, 7, 2),
(24, 8, 2),
(24, 9, 2),
(24, 10, 2),
(24, 11, 2),
(24, 12, 2),
(24, 13, 2),
(24, 14, 2),
(24, 15, 2),
(24, 16, 2),
(24, 17, 2),
(24, 18, 2),
(24, 19, 2),
(24, 20, 2),
(24, 21, 2),
(24, 22, 2),
(24, 23, 2),
(24, 24, 2),
(24, 25, 2),
(24, 26, 2),
(24, 27, 2),
(24, 28, 2),
(24, 29, 2),
(24, 30, 2),
(24, 31, 2),
(24, 33, 2),
(24, 34, 2),
(24, 35, 2),
(24, 36, 2),
(24, 37, 2),
(24, 38, 2),
(24, 39, 2),
(24, 40, 2),
(24, 41, 2),
(24, 42, 2),
(24, 43, 2),
(24, 44, 2),
(24, 45, 2),
(24, 46, 2),
(24, 47, 2),
(24, 48, 2),
(25, 4, 2),
(25, 5, 2),
(25, 8, 2),
(25, 10, 2),
(25, 15, 2),
(25, 26, 2),
(25, 27, 2),
(25, 29, 2),
(25, 30, 2),
(25, 35, 2),
(25, 38, 2),
(25, 46, 2),
(26, 3, 2),
(26, 16, 2),
(26, 17, 2),
(26, 19, 2),
(26, 20, 2),
(26, 23, 2),
(26, 25, 2),
(26, 39, 2),
(26, 43, 2),
(26, 45, 2),
(26, 48, 2),
(27, 7, 2),
(27, 13, 2),
(27, 28, 2),
(27, 31, 2),
(27, 36, 2),
(27, 41, 2),
(27, 42, 2),
(27, 44, 2),
(27, 47, 2),
(28, 1, 2),
(28, 2, 2),
(28, 6, 2),
(28, 9, 2),
(28, 11, 2),
(28, 12, 2),
(28, 14, 2),
(28, 18, 2),
(28, 21, 2),
(28, 22, 2),
(28, 24, 2),
(28, 32, 2),
(28, 33, 2),
(28, 34, 2),
(28, 37, 2),
(28, 40, 2),
(29, 1, 2),
(29, 2, 2),
(29, 4, 2),
(29, 5, 2),
(29, 6, 2),
(29, 7, 2),
(29, 8, 2),
(29, 9, 2),
(29, 10, 2),
(29, 11, 2),
(29, 12, 2),
(29, 13, 2),
(29, 14, 2),
(29, 16, 2),
(29, 19, 2),
(29, 20, 2),
(29, 21, 2),
(29, 23, 2),
(29, 24, 2),
(29, 25, 2),
(29, 26, 2),
(29, 27, 2),
(29, 28, 2),
(29, 29, 2),
(29, 30, 2),
(29, 31, 2),
(29, 32, 2),
(29, 33, 2),
(29, 34, 2),
(29, 35, 2),
(29, 37, 2),
(29, 38, 2),
(29, 39, 2),
(29, 41, 2),
(29, 43, 2),
(29, 44, 2),
(30, 3, 2),
(30, 15, 2),
(30, 17, 2),
(30, 18, 2),
(30, 22, 2),
(30, 36, 2),
(30, 40, 2),
(30, 42, 2),
(30, 45, 2),
(31, 1, 2),
(31, 2, 2),
(31, 4, 2),
(31, 6, 2),
(31, 8, 2),
(31, 9, 2),
(31, 10, 2),
(31, 11, 2),
(31, 12, 2),
(31, 13, 2),
(31, 14, 2),
(31, 15, 2),
(31, 18, 2),
(31, 21, 2),
(31, 22, 2),
(31, 24, 2),
(31, 26, 2),
(31, 27, 2),
(31, 28, 2),
(31, 29, 2),
(31, 30, 2),
(31, 31, 2),
(31, 33, 2),
(31, 34, 2),
(31, 35, 2),
(31, 36, 2),
(31, 37, 2),
(31, 38, 2),
(31, 40, 2),
(31, 41, 2),
(31, 42, 2),
(31, 44, 2),
(31, 46, 2),
(32, 1, 2),
(32, 2, 2),
(32, 4, 2),
(32, 6, 2),
(32, 8, 2),
(32, 9, 2),
(32, 10, 2),
(32, 11, 2),
(32, 12, 2),
(32, 13, 2),
(32, 14, 2),
(32, 15, 2),
(32, 18, 2),
(32, 21, 2),
(32, 22, 2),
(32, 24, 2),
(32, 26, 2),
(32, 27, 2),
(32, 28, 2),
(32, 29, 2),
(32, 30, 2),
(32, 31, 2),
(32, 33, 2),
(32, 34, 2),
(32, 35, 2),
(32, 36, 2),
(32, 37, 2),
(32, 38, 2),
(32, 40, 2),
(32, 41, 2),
(32, 42, 2),
(32, 44, 2),
(32, 46, 2),
(33, 7, 2),
(33, 47, 2),
(34, 7, 2),
(34, 47, 2),
(35, 3, 2),
(35, 5, 2),
(35, 16, 2),
(35, 20, 2),
(35, 32, 2),
(35, 39, 2),
(35, 45, 2),
(36, 3, 2),
(36, 5, 2),
(36, 16, 2),
(36, 20, 2),
(36, 32, 2),
(36, 39, 2),
(36, 45, 2),
(37, 17, 2),
(37, 19, 2),
(37, 23, 2),
(37, 25, 2),
(37, 43, 2),
(37, 48, 2),
(38, 17, 2),
(38, 19, 2),
(38, 23, 2),
(38, 25, 2),
(38, 43, 2),
(38, 48, 2),
(41, 5, 2),
(41, 7, 2),
(41, 11, 2),
(41, 12, 2),
(41, 13, 2),
(41, 14, 2),
(41, 18, 2),
(41, 21, 2),
(41, 24, 2),
(41, 28, 2),
(41, 31, 2),
(41, 32, 2),
(41, 33, 2),
(41, 34, 2),
(41, 36, 2),
(41, 37, 2),
(41, 41, 2),
(41, 42, 2),
(41, 44, 2),
(41, 47, 2),
(42, 1, 2),
(42, 2, 2),
(42, 3, 2),
(42, 4, 2),
(42, 6, 2),
(42, 8, 2),
(42, 9, 2),
(42, 10, 2),
(42, 15, 2),
(42, 16, 2),
(42, 17, 2),
(42, 19, 2),
(42, 20, 2),
(42, 22, 2),
(42, 23, 2),
(42, 25, 2),
(42, 26, 2),
(42, 27, 2),
(42, 29, 2),
(42, 30, 2),
(42, 35, 2),
(42, 38, 2),
(42, 39, 2),
(42, 40, 2),
(42, 43, 2),
(42, 45, 2),
(42, 46, 2),
(42, 48, 2),
(43, 7, 2),
(43, 13, 2),
(43, 24, 2),
(43, 28, 2),
(43, 31, 2),
(43, 36, 2),
(43, 41, 2),
(43, 42, 2),
(43, 44, 2),
(43, 47, 2),
(44, 1, 2),
(44, 2, 2),
(44, 3, 2),
(44, 4, 2),
(44, 8, 2),
(44, 9, 2),
(44, 10, 2),
(44, 15, 2),
(44, 16, 2),
(44, 17, 2),
(44, 19, 2),
(44, 20, 2),
(44, 22, 2),
(44, 23, 2),
(44, 25, 2),
(44, 26, 2),
(44, 27, 2),
(44, 29, 2),
(44, 30, 2),
(44, 35, 2),
(44, 37, 2),
(44, 38, 2),
(44, 39, 2),
(44, 40, 2),
(44, 43, 2),
(44, 45, 2),
(44, 46, 2),
(44, 48, 2),
(45, 5, 2),
(45, 6, 2),
(45, 11, 2),
(45, 12, 2),
(45, 14, 2),
(45, 18, 2),
(45, 21, 2),
(45, 32, 2),
(45, 33, 2),
(45, 34, 2),
(48, 1, 2),
(48, 2, 2),
(48, 3, 2),
(48, 4, 2),
(48, 5, 2),
(48, 6, 2),
(48, 7, 2),
(48, 8, 2),
(48, 9, 2),
(48, 10, 2),
(48, 11, 2),
(48, 12, 2),
(48, 13, 2),
(48, 14, 2),
(48, 15, 2),
(48, 16, 2),
(48, 17, 2),
(48, 18, 2),
(48, 19, 2),
(48, 20, 2),
(48, 21, 2),
(48, 22, 2),
(48, 23, 2),
(48, 24, 2),
(48, 25, 2),
(48, 26, 2),
(48, 27, 2),
(48, 28, 2),
(48, 29, 2),
(48, 30, 2),
(48, 31, 2),
(48, 32, 2),
(48, 33, 2),
(48, 34, 2),
(48, 35, 2),
(48, 36, 2),
(48, 37, 2),
(48, 38, 2),
(48, 39, 2),
(48, 40, 2),
(48, 41, 2),
(48, 42, 2),
(48, 43, 2),
(48, 44, 2),
(48, 45, 2),
(48, 46, 2),
(48, 47, 2),
(48, 48, 2),
(49, 1, 2),
(49, 2, 2),
(49, 3, 2),
(49, 4, 2),
(49, 5, 2),
(49, 6, 2),
(49, 7, 2),
(49, 8, 2),
(49, 9, 2),
(49, 10, 2),
(49, 11, 2),
(49, 12, 2),
(49, 13, 2),
(49, 14, 2),
(49, 15, 2),
(49, 16, 2),
(49, 17, 2),
(49, 18, 2),
(49, 19, 2),
(49, 20, 2),
(49, 21, 2),
(49, 22, 2),
(49, 23, 2),
(49, 24, 2),
(49, 25, 2),
(49, 26, 2),
(49, 27, 2),
(49, 28, 2),
(49, 29, 2),
(49, 30, 2),
(49, 31, 2),
(49, 32, 2),
(49, 33, 2),
(49, 34, 2),
(49, 35, 2),
(49, 36, 2),
(49, 37, 2),
(49, 38, 2),
(49, 39, 2),
(49, 40, 2),
(49, 41, 2),
(49, 42, 2),
(49, 43, 2),
(49, 44, 2),
(49, 45, 2),
(49, 46, 2),
(49, 47, 2),
(49, 48, 2),
(50, 1, 2),
(50, 2, 2),
(50, 3, 2),
(50, 4, 2),
(50, 5, 2),
(50, 6, 2),
(50, 7, 2),
(50, 8, 2),
(50, 9, 2),
(50, 10, 2),
(50, 11, 2),
(50, 12, 2),
(50, 13, 2),
(50, 14, 2),
(50, 15, 2),
(50, 16, 2),
(50, 17, 2),
(50, 18, 2),
(50, 19, 2),
(50, 20, 2),
(50, 21, 2),
(50, 22, 2),
(50, 23, 2),
(50, 24, 2),
(50, 25, 2),
(50, 26, 2),
(50, 27, 2),
(50, 28, 2),
(50, 29, 2),
(50, 30, 2),
(50, 31, 2),
(50, 32, 2),
(50, 33, 2),
(50, 34, 2),
(50, 35, 2),
(50, 36, 2),
(50, 37, 2),
(50, 38, 2),
(50, 39, 2),
(50, 40, 2),
(50, 41, 2),
(50, 42, 2),
(50, 43, 2),
(50, 44, 2),
(50, 45, 2),
(50, 46, 2),
(50, 47, 2),
(50, 48, 2),
(51, 1, 2),
(51, 2, 2),
(51, 3, 2),
(51, 4, 2),
(51, 6, 2),
(51, 7, 2),
(51, 8, 2),
(51, 10, 2),
(51, 11, 2),
(51, 12, 2),
(51, 13, 2),
(51, 14, 2),
(51, 15, 2),
(51, 16, 2),
(51, 17, 2),
(51, 18, 2),
(51, 19, 2),
(51, 21, 2),
(51, 22, 2),
(51, 23, 2),
(51, 25, 2),
(51, 26, 2),
(51, 27, 2),
(51, 28, 2),
(51, 29, 2),
(51, 31, 2),
(51, 33, 2),
(51, 34, 2),
(51, 35, 2),
(51, 36, 2),
(51, 37, 2),
(51, 38, 2),
(51, 39, 2),
(51, 40, 2),
(51, 41, 2),
(51, 42, 2),
(51, 43, 2),
(51, 44, 2),
(51, 45, 2),
(51, 46, 2),
(51, 47, 2),
(51, 48, 2),
(52, 5, 2),
(52, 9, 2),
(52, 20, 2),
(52, 24, 2),
(52, 30, 2),
(52, 32, 2),
(53, 24, 2),
(53, 28, 2),
(53, 41, 2),
(54, 5, 2),
(54, 32, 2),
(55, 13, 2),
(55, 24, 2),
(55, 28, 2),
(55, 31, 2),
(55, 37, 2),
(55, 41, 2),
(55, 42, 2),
(55, 44, 2),
(56, 16, 2),
(56, 17, 2),
(56, 19, 2),
(56, 20, 2),
(56, 23, 2),
(56, 32, 2),
(56, 39, 2),
(56, 43, 2),
(56, 48, 2),
(57, 1, 2),
(57, 2, 2),
(57, 3, 2),
(57, 4, 2),
(57, 5, 2),
(57, 6, 2),
(57, 7, 2),
(57, 8, 2),
(57, 9, 2),
(57, 10, 2),
(57, 11, 2),
(57, 12, 2),
(57, 13, 2),
(57, 14, 2),
(57, 15, 2),
(57, 18, 2),
(57, 21, 2),
(57, 22, 2),
(57, 24, 2),
(57, 25, 2),
(57, 26, 2),
(57, 27, 2),
(57, 28, 2),
(57, 29, 2),
(57, 30, 2),
(57, 31, 2),
(57, 33, 2),
(57, 34, 2),
(57, 35, 2),
(57, 36, 2),
(57, 37, 2),
(57, 38, 2),
(57, 40, 2),
(57, 41, 2),
(57, 42, 2),
(57, 44, 2),
(57, 45, 2),
(57, 46, 2),
(57, 47, 2),
(59, 1, 2),
(59, 2, 2),
(59, 3, 2),
(59, 4, 2),
(59, 5, 2),
(59, 6, 2),
(59, 7, 2),
(59, 8, 2),
(59, 9, 2),
(59, 10, 2),
(59, 11, 2),
(59, 12, 2),
(59, 13, 2),
(59, 14, 2),
(59, 15, 2),
(59, 16, 2),
(59, 17, 2),
(59, 18, 2),
(59, 19, 2),
(59, 20, 2),
(59, 21, 2),
(59, 22, 2),
(59, 23, 2),
(59, 24, 2),
(59, 25, 2),
(59, 26, 2),
(59, 27, 2),
(59, 28, 2),
(59, 29, 2),
(59, 30, 2),
(59, 31, 2),
(59, 32, 2),
(59, 33, 2),
(59, 34, 2),
(59, 35, 2),
(59, 36, 2),
(59, 37, 2),
(59, 38, 2),
(59, 39, 2),
(59, 40, 2),
(59, 41, 2),
(59, 42, 2),
(59, 43, 2),
(59, 44, 2),
(59, 45, 2),
(59, 46, 2),
(59, 47, 2),
(59, 48, 2);

-- --------------------------------------------------------

--
-- Structure de la table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `id_department` int(11) unsigned NOT NULL COMMENT 'PK',
  `name_department` varchar(255) NOT NULL COMMENT 'Nom',
  PRIMARY KEY (`id_department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `department`
--

INSERT INTO `department` (`id_department`, `name_department`) VALUES
(1, 'AIN'),
(2, 'AISNE'),
(3, 'ALLIER'),
(4, 'ALPES-DE-HAUTE-PROVENCE'),
(5, 'HAUTES-ALPES'),
(6, 'ALPES-MARITIMES'),
(7, 'ARDÈCHE'),
(8, 'ARDENNES'),
(9, 'ARIÈGE'),
(10, 'AUBE'),
(11, 'AUDE'),
(12, 'AVEYRON'),
(13, 'BOUCHES-DU-RHÔNE'),
(14, 'CALVADOS'),
(15, 'CANTAL'),
(16, 'CHARENTE'),
(17, 'CHARENTE-MARITIME'),
(18, 'CHER'),
(19, 'CORRÈZE'),
(20, 'CORSE'),
(21, 'CÔTE-D''OR'),
(22, 'CÔTES-D''ARMOR'),
(23, 'CREUSE'),
(24, 'DORDOGNE'),
(25, 'DOUBS'),
(26, 'DRÔME'),
(27, 'EURE'),
(28, 'EURE-ET-LOIR'),
(29, 'FINISTÈRE'),
(30, 'GARD'),
(31, 'HAUTE-GARONNE'),
(32, 'GERS'),
(33, 'GIRONDE'),
(34, 'HÉRAULT'),
(35, 'ILE-ET-VILAINE'),
(36, 'INDRE'),
(37, 'INDRE-ET-LOIRE'),
(38, 'ISÈRE'),
(39, 'JURA'),
(40, 'LANDES'),
(41, 'LOIR-ET-CHER'),
(42, 'LOIRE'),
(43, 'HAUTE-LOIRE'),
(44, 'LOIRE-ATLANTIQUE'),
(45, 'LOIRET'),
(46, 'LOT'),
(47, 'LOT-ET-GARONNE'),
(48, 'LOZÈRE'),
(49, 'MAINE-ET-LOIRE'),
(50, 'MANCHE'),
(51, 'MARNE'),
(52, 'HAUTE-MARNE'),
(53, 'MAYENNE'),
(54, 'MEURTHE-ET-MOSELLE'),
(55, 'MEUSE'),
(56, 'MORBIHAN'),
(57, 'MOSELLE'),
(58, 'NIÈVRE'),
(59, 'NORD'),
(60, 'OISE'),
(61, 'ORNE'),
(62, 'PAS-DE-CALAIS'),
(63, 'PUY-DE-DÔME'),
(64, 'PYRÉNÉES-ATLANTIQUES'),
(65, 'HAUTES-PYRÉNÉES'),
(66, 'PYRÉNÉES-ORIENTALES'),
(67, 'BAS-RHIN'),
(68, 'HAUT-RHIN'),
(69, 'RHÔNE'),
(70, 'HAUTE-SAÔNE'),
(71, 'SAÔNE-ET-LOIRE'),
(72, 'SARTHE'),
(73, 'SAVOIE'),
(74, 'HAUTE-SAVOIE'),
(75, 'PARIS'),
(76, 'SEINE-MARITIME'),
(77, 'SEINE-ET-MARNE'),
(78, 'YVELINES'),
(79, 'DEUX-SÈVRES'),
(80, 'SOMME'),
(81, 'TARN'),
(82, 'TARN-ET-GARONNE'),
(83, 'VAR'),
(84, 'VAUCLUSE'),
(85, 'VENDÉE'),
(86, 'VIENNE'),
(87, 'HAUTE-VIENNE'),
(88, 'VOSGES'),
(89, 'YONNE'),
(90, 'TERRITOIRE DE BELFORT'),
(91, 'ESSONNE'),
(92, 'HAUTS-DE-SEINE'),
(93, 'SEINE-SAINT-DENIS'),
(94, 'VAL-DE-MARNE'),
(95, 'VAL-D''OISE'),
(99, 'NC'),
(971, 'GUADELOUPE'),
(972, 'MARTINIQUE'),
(973, 'GUYANE'),
(974, 'RÉUNION'),
(976, 'MAYOTTE');

-- --------------------------------------------------------

--
-- Structure de la table `enterprise`
--

CREATE TABLE IF NOT EXISTS `enterprise` (
  `id_enterprise` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name_enterprise` varchar(50) NOT NULL COMMENT 'Nom de famille',
  `PROFILE_id_profile` int(11) unsigned NOT NULL COMMENT 'FK',
  `DEPARTMENT_id_department` int(11) unsigned NOT NULL COMMENT 'FK',
  `date_deletion_enterprise` datetime DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  PRIMARY KEY (`id_enterprise`),
  UNIQUE KEY `ux_name_enterprise` (`name_enterprise`),
  KEY `ix_enterprise_PROFILE_id_profile` (`PROFILE_id_profile`),
  KEY `ix_enterprise_DEPARTMENT_id_department` (`DEPARTMENT_id_department`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=80 ;

--
-- Contenu de la table `enterprise`
--

INSERT INTO `enterprise` (`id_enterprise`, `name_enterprise`, `PROFILE_id_profile`, `DEPARTMENT_id_department`, `date_deletion_enterprise`) VALUES
(1, 'ANCENIS', 2, 44, NULL),
(2, 'ANTHALDIS', 2, 44, NULL),
(3, 'ANTHIGO', 2, 35, NULL),
(4, 'ATLANTIS', 2, 44, NULL),
(5, 'AZEDIS', 2, 53, NULL),
(6, 'BLAINDIS', 2, 44, NULL),
(7, 'BOCAGE', 2, 14, NULL),
(8, 'BREVIDIS', 2, 44, NULL),
(9, 'BRIANDIS', 2, 44, NULL),
(10, 'BRIERE', 2, 44, NULL),
(11, 'BRISSAC', 2, 49, NULL),
(12, 'CDA49', 2, 49, NULL),
(13, 'CHANTONNAY', 2, 85, NULL),
(14, 'CHEMILLE', 2, 49, NULL),
(15, 'CLISSON', 2, 44, NULL),
(16, 'DACAR', 2, 35, NULL),
(17, 'DINANDIS', 2, 22, NULL),
(18, 'DYNALEC', 2, 49, NULL),
(19, 'ELAUDIS', 2, 56, NULL),
(20, 'FOUGERES', 2, 35, NULL),
(21, 'GOURONNIERES', 2, 49, NULL),
(22, 'GUERANDIS', 2, 44, NULL),
(23, 'JOSSELIN', 2, 56, NULL),
(24, 'HERBIDIS', 2, 85, NULL),
(25, 'KERMELEUC', 2, 35, NULL),
(26, 'LAURY-CHALONGES', 2, 44, NULL),
(27, 'NANTES-NORD', 2, 44, NULL),
(28, 'OUDAIRIDIS', 2, 85, NULL),
(29, 'PARIDIS', 2, 44, NULL),
(30, 'PAYS-DE-REDON', 2, 44, NULL),
(31, 'PSV', 2, 85, NULL),
(32, 'SABLE', 2, 72, NULL),
(33, 'SAUMUR', 2, 49, NULL),
(34, 'SEGRE', 2, 49, NULL),
(35, 'SODIJOUR', 2, 44, NULL),
(36, 'SODILONNE', 2, 85, NULL),
(37, 'SODINOVE', 2, 85, NULL),
(38, 'SODIPOR', 2, 44, NULL),
(39, 'SODIRENNES', 2, 35, NULL),
(40, 'SODIRETZ', 2, 44, NULL),
(41, 'SODIROCHE', 2, 85, NULL),
(42, 'SODIVARDIERE', 2, 85, NULL),
(43, 'SOLACDIS', 2, 56, NULL),
(44, 'ST-GILLES', 2, 85, NULL),
(45, 'ST-MALO', 2, 35, NULL),
(46, 'SUD-LOIRE', 2, 44, NULL),
(47, 'THOUARS', 2, 79, NULL),
(48, 'VADIS', 2, 56, NULL),
(49, '__ADMINISTRATEUR__', 3, 44, NULL),
(50, 'SCA OUEST', 4, 44, NULL),
(51, 'ABACA', 1, 99, NULL),
(52, 'ACTUELLE', 1, 99, NULL),
(53, 'BLISSED', 1, 99, NULL),
(54, 'COLINE KEAWA', 1, 99, NULL),
(55, 'FRANCE DENIM RMS 26', 1, 99, NULL),
(56, 'KIDILIZ ABSORBA - ALPHABET', 1, 99, NULL),
(57, 'ID''EST CDH', 1, 99, NULL),
(58, 'LC DISTRIBUTION', 1, 99, NULL),
(59, 'MAILLE DISTRIBUTION', 1, 99, NULL),
(60, 'MYTHEX', 1, 99, NULL),
(61, 'NORPROTEX', 1, 99, NULL),
(62, 'PICCO BELLO', 1, 99, NULL),
(63, 'RITCHIE', 1, 99, NULL),
(64, 'SALMON', 1, 99, NULL),
(65, 'STOOKER', 1, 99, NULL),
(66, 'TERRE DE MARINS', 1, 99, NULL),
(67, 'TREEKERS', 1, 99, NULL),
(68, 'UNIVERTEX', 1, 99, NULL),
(69, 'BESNARD', 1, 99, NULL),
(70, 'C.C.I.', 1, 99, NULL),
(71, 'COMFORT GROUP', 1, 99, NULL),
(72, 'DEFONSECA', 1, 99, NULL),
(73, 'DISTRIBEST', 1, 99, NULL),
(74, 'DRESCO', 1, 99, NULL),
(75, 'JP CHAUSSURES', 1, 99, NULL),
(76, 'ROYER', 1, 99, NULL),
(77, 'MADO MARCEL', 1, 99, NULL),
(78, 'SOCIETE ESPAGNOLE', 1, 99, NULL),
(79, 'ESPAGNOLS  - ROMAIN DE NARDO -', 1, 99, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id_log` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `ENTERPRISE_id_enterprise` int(11) unsigned NOT NULL COMMENT 'FK',
  `ip_address` varchar(50) NOT NULL COMMENT '@IP',
  `action_description` varchar(100) NOT NULL COMMENT 'Description',
  `action_datetime` datetime NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  PRIMARY KEY (`id_log`),
  KEY `ix_log_ENTERPRISE_id_enterprise` (`ENTERPRISE_id_enterprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

CREATE TABLE IF NOT EXISTS `participant` (
  `id_participant` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `civility_participant` varchar(8) NOT NULL COMMENT 'Civilité',
  `surname_participant` varchar(50) NOT NULL COMMENT 'Nom de famille',
  `name_participant` varchar(50) NOT NULL COMMENT 'Prénom',
  `email_participant` varchar(100) NOT NULL COMMENT 'E-mail',
  `date_deletion_participant` datetime DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  PRIMARY KEY (`id_participant`),
  UNIQUE KEY `ux_email_participant` (`email_participant`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

--
-- Contenu de la table `participant`
--

INSERT INTO `participant` (`id_participant`, `civility_participant`, `surname_participant`, `name_participant`, `email_participant`, `date_deletion_participant`) VALUES
(1, 'Monsieur', 'PAJOT', 'Fabrice', 'fabrice.pajot@gmail.com', NULL),
(2, 'Monsieur', 'ancenis', 'test', 'test@ancenis.fr', NULL),
(3, 'Madame', 'METAYER', 'MARYLINE', 'maryline.metayer@wanadoo.fr', NULL),
(7, 'Monsieur', 'JACQUOT ', 'David ', 'djacquot@wanadoo.fr', NULL),
(8, 'Monsieur', 'THINES', 'ROMUALD', 'romuald.rms26@gmail.com', NULL),
(9, 'Monsieur', 'ROQUET', 'Bruno', 'broquet@kidilizgroup.com', NULL),
(10, 'Monsieur', 'THESSON', 'Arnaud', 'athesson@kidilizgroup.com', NULL),
(11, 'Monsieur', 'HUBERT', 'Olivier', 'moihubert@wanadoo.fr', NULL),
(12, 'Monsieur', 'VIER', 'Laurent', 'laurentvier@orange.fr', NULL),
(13, 'Madame', 'RENARD', 'Isabelle', 'cmoi.renard@wanadoo.fr', NULL),
(14, 'Monsieur', 'GUERON', 'JEAN JACQUES', 'jjgueron@gmail.com', NULL),
(15, 'Monsieur', 'FEREZ', 'Louis-Michel', 'ferdis85@gmail.com', NULL),
(16, 'Monsieur', 'TIGER', 'Yves', 'yves.tiger@yahoo.fr', NULL),
(21, 'Monsieur', 'ZITOUN', 'PATRICK', 'pzitoun@norprotex.com', NULL),
(22, 'Monsieur', 'HAUG', 'Olivier', 'ohaug@norprotex.com', NULL),
(24, 'Monsieur', 'REVEAU', 'Philippe', 'reveau.philippe@orange.fr', NULL),
(25, 'Monsieur', 'MAURILLE', 'Jean-René', 'jeanrene.maurille@gsa.fr', NULL),
(26, 'Monsieur', 'DOUILLARD', 'Maxime', 'maxime.douillard@gsa.fr', NULL),
(27, 'Monsieur', 'ROBILLARD', 'Michel', 'michel.robillard@gsa.fr', NULL),
(28, 'Madame', 'POIRIER', 'Armelle', 'armelle.poirier@gsa.fr', NULL),
(29, 'Monsieur', 'BRICARD', 'Mickaël', 'mickael.bricard@wanadoo.fr', NULL),
(30, 'Madame', 'LE MOIGNE', 'Sophie', 'sophie-le-moigne@stookerbrands.fr', NULL),
(31, 'Madame', 'GUILLAMET', 'Céline', 'c.guillamet@terredemarins.com', NULL),
(32, 'Monsieur', 'GAULTIER', 'Karine', 'k.gaultier@terredemarins.com', NULL),
(33, 'Madame', 'BOTTA', 'Valérie', 'v.botta@terredemarins.com', NULL),
(34, 'Monsieur', 'BONJOUX', 'Anthony', 'a.bonjoux@terredemarins.com', NULL),
(35, 'Madame', 'MAUVIEL', 'Sabrina', 's.mauviel@terredemarins.com', NULL),
(36, 'Madame', 'BOUTROIS', 'Jennifer', 'j.boutrois@terredemarins.com', NULL),
(37, 'Madame', 'LATRUFFE', 'Caroline', 'c.latruffe@terredemarins.com', NULL),
(38, 'Monsieur', 'GASNIER', 'Thomas', 't.gasnier@terredemarins.com', NULL),
(41, 'Monsieur', 'CORNUE', 'JOHN', 'jcornue@besnard.fr', NULL),
(42, 'Monsieur', 'DUBOIS', 'Eric', 'edubois@besnard.fr', NULL),
(43, 'Monsieur', 'GATE', 'Jérémy', 'j.gate@ccishoes.com', NULL),
(44, 'Monsieur', 'JOLIVET', 'Patrice', 'p.jolivet@ccishoes.com', NULL),
(45, 'Monsieur', 'GERARD', 'Steven', 's.gerard@ccishoes.com', NULL),
(47, 'Madame', 'BOLLET HERAULT', 'Emmanuelle', 'e.bolletherault@luo-distribution.com', NULL),
(48, 'Monsieur', 'DUPE', 'Laurent', 'ldupe@distrib-est.fr', NULL),
(49, 'Monsieur', 'DE NARDO', 'Romain', 'romain@dresco.fr', NULL),
(50, 'Monsieur', 'MOREAU', 'Nicolas', 'n.moreau@jpchaussure.com', NULL),
(51, 'Monsieur', 'BARBIER', 'Jacky', 'jacky.barbier@grouperoyer.com', NULL),
(52, 'Monsieur', 'LECOEUR', 'Pascal', 'pascal.lecoeur@grouperoyer.com', NULL),
(53, 'Monsieur', 'MERCKENS', 'Philippe', 'philipe.merckens@gmail.com', NULL),
(54, 'Madame', 'JACQUOT', 'Sandrine', 'dsjacquot@wanadoo.fr', NULL),
(55, 'Monsieur', 'MARRETTA', 'Pierre', 'pmarretta@gmail.com', NULL),
(56, 'Madame', 'DUVIVIER', 'Marie-Laure', 'aduvivier@mado-group.com', NULL),
(57, 'Monsieur', 'BRETAUDEAU', 'Bernard', 'bernard.bretaudeau@gmail.com', NULL),
(59, 'Monsieur', 'FAYD''HERBE', 'Charles ', 'faydherbecharles@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `participation`
--

CREATE TABLE IF NOT EXISTS `participation` (
  `PARTICIPANT_id_participant` int(11) unsigned NOT NULL COMMENT 'PK participant',
  `PURCHASING_FAIR_id_purchasing_fair` int(11) unsigned NOT NULL COMMENT 'PK purchasing_fair',
  `password_participant` char(6) DEFAULT NULL COMMENT 'Mot de passe à six chiffres',
  `lunch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = NON / 1 = OUI',
  PRIMARY KEY (`PARTICIPANT_id_participant`,`PURCHASING_FAIR_id_purchasing_fair`),
  KEY `ix_participation_PARTICIPANT_id_participant` (`PARTICIPANT_id_participant`),
  KEY `ix_participation_PURCHASING_FAIR_id_purchasing_fair` (`PURCHASING_FAIR_id_purchasing_fair`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `participation`
--

INSERT INTO `participation` (`PARTICIPANT_id_participant`, `PURCHASING_FAIR_id_purchasing_fair`, `password_participant`, `lunch`) VALUES
(1, 2, '123456', 0),
(2, 1, '123456', 0),
(2, 2, '123456', 0),
(3, 2, '123456', 0),
(7, 2, '123456', 0),
(8, 2, '123456', 0),
(9, 2, '123456', 0),
(10, 2, '123456', 0),
(11, 2, '123456', 0),
(12, 2, '123456', 0),
(13, 2, '123456', 0),
(14, 2, '123456', 0),
(15, 2, '123456', 0),
(16, 2, '123456', 0),
(21, 2, '123456', 0),
(22, 2, '123456', 0),
(24, 2, '123456', 0),
(25, 2, '123456', 0),
(26, 2, '123456', 0),
(27, 2, '123456', 0),
(28, 2, '123456', 0),
(29, 2, '123456', 0),
(30, 2, '123456', 0),
(31, 2, '123456', 0),
(32, 2, '123456', 0),
(33, 2, '123456', 0),
(34, 2, '123456', 0),
(35, 2, '123456', 0),
(36, 2, '123456', 0),
(37, 2, '123456', 0),
(38, 2, '123456', 0),
(41, 2, '123456', 0),
(42, 2, '123456', 0),
(43, 2, '123456', 0),
(44, 2, '123456', 0),
(45, 2, '123456', 0),
(47, 2, '123456', 0),
(48, 2, '123456', 0),
(49, 2, '123456', 0),
(50, 2, '123456', 0),
(51, 2, '123456', 0),
(52, 2, '123456', 0),
(53, 2, '123456', 0),
(54, 2, '123456', 0),
(55, 2, '123456', 0),
(56, 2, '123456', 0),
(57, 2, '123456', 0),
(59, 2, '123456', 0);

-- --------------------------------------------------------

--
-- Structure de la table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `id_profile` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name_profile` varchar(50) NOT NULL COMMENT 'Nom du profil',
  `date_deletion_profile` datetime DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  PRIMARY KEY (`id_profile`),
  UNIQUE KEY `ux_name_profile` (`name_profile`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `profile`
--

INSERT INTO `profile` (`id_profile`, `name_profile`, `date_deletion_profile`) VALUES
(1, 'Fournisseur', NULL),
(2, 'Magasin', NULL),
(3, 'Service Informatique', NULL),
(4, 'Service Textile', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `purchasing_fair`
--

CREATE TABLE IF NOT EXISTS `purchasing_fair` (
  `id_purchasing_fair` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name_purchasing_fair` varchar(50) NOT NULL COMMENT 'Nom affiché',
  `hex_color` char(7) NOT NULL COMMENT 'Couleur de fond #123456',
  `start_datetime` datetime NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  `end_datetime` datetime NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  `lunch_break` time NOT NULL COMMENT 'HH:MM:SS',
  `TYPEOF_PF_id_typeof_pf` int(11) unsigned NOT NULL COMMENT 'FK',
  `date_deletion_purchasing_fair` datetime DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  PRIMARY KEY (`id_purchasing_fair`),
  UNIQUE KEY `ux_name_start_end_datetime` (`name_purchasing_fair`,`start_datetime`,`end_datetime`),
  KEY `ix_pf_TYPEOF_PF_id_typeof_pf` (`TYPEOF_PF_id_typeof_pf`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `purchasing_fair`
--

INSERT INTO `purchasing_fair` (`id_purchasing_fair`, `name_purchasing_fair`, `hex_color`, `start_datetime`, `end_datetime`, `lunch_break`, `TYPEOF_PF_id_typeof_pf`, `date_deletion_purchasing_fair`) VALUES
(1, 'unSalon', '#0b70b5', '2019-08-01 00:00:00', '2020-08-04 00:00:00', '20:00:00', 1, NULL),
(2, 'AH 18 SAISONNIER CHAUSSURE', '#e98a13', '2018-02-05 08:00:00', '2018-02-16 19:00:00', '12:00:00', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `qrcode_scan`
--

CREATE TABLE IF NOT EXISTS `qrcode_scan` (
  `content` varchar(250) NOT NULL COMMENT 'Contenu du QRCode',
  `scan_datetime` datetime NOT NULL COMMENT 'Date de scan QRCode'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `requirement`
--

CREATE TABLE IF NOT EXISTS `requirement` (
  `id_requirement` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `ENTERPRISE_STORE_id_enterprise` int(11) unsigned NOT NULL COMMENT 'FK',
  `ENTERPRISE_PROVIDER_id_enterprise` int(11) unsigned NOT NULL COMMENT 'FK',
  `PURCHASING_FAIR_id_purchasing_fair` int(11) unsigned NOT NULL COMMENT 'FK',
  `number_of_hours` tinyint(3) unsigned NOT NULL COMMENT 'Nb heures',
  PRIMARY KEY (`id_requirement`),
  UNIQUE KEY `ux_requirement` (`ENTERPRISE_STORE_id_enterprise`,`ENTERPRISE_PROVIDER_id_enterprise`,`PURCHASING_FAIR_id_purchasing_fair`),
  KEY `ix_requirement_ENTERPRISE_STORE_id_enterprise` (`ENTERPRISE_STORE_id_enterprise`),
  KEY `ix_requirement_ENTERPRISE_PROVIDER_id_enterprise` (`ENTERPRISE_PROVIDER_id_enterprise`),
  KEY `ix_requirement_PURCHASING_FAIR_id_purchasing_fair` (`PURCHASING_FAIR_id_purchasing_fair`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `requirement`
--

INSERT INTO `requirement` (`id_requirement`, `ENTERPRISE_STORE_id_enterprise`, `ENTERPRISE_PROVIDER_id_enterprise`, `PURCHASING_FAIR_id_purchasing_fair`, `number_of_hours`) VALUES
(1, 1, 51, 1, 5),
(5, 1, 53, 1, 1),
(6, 1, 51, 2, 5);

-- --------------------------------------------------------

--
-- Structure de la table `typeof_pf`
--

CREATE TABLE IF NOT EXISTS `typeof_pf` (
  `id_typeof_pf` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name_typeof_pf` varchar(50) NOT NULL COMMENT 'Nom du type de salon',
  PRIMARY KEY (`id_typeof_pf`),
  UNIQUE KEY `ux_name_typeof_pf` (`name_typeof_pf`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `typeof_pf`
--

INSERT INTO `typeof_pf` (`id_typeof_pf`, `name_typeof_pf`) VALUES
(2, 'Autre'),
(1, 'Textile');

-- --------------------------------------------------------

--
-- Structure de la table `unavailability`
--

CREATE TABLE IF NOT EXISTS `unavailability` (
  `id_unavailability` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `start_datetime` datetime NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  `end_datetime` datetime NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  `ENTERPRISE_id_enterprise` int(11) unsigned NOT NULL COMMENT 'FK',
  `PURCHASING_FAIR_id_purchasing_fair` int(11) unsigned NOT NULL COMMENT 'FK',
  `date_deletion_unavailability` datetime DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  PRIMARY KEY (`id_unavailability`),
  UNIQUE KEY `ux_enterprise_start_end_datetime` (`start_datetime`,`end_datetime`,`ENTERPRISE_id_enterprise`,`PURCHASING_FAIR_id_purchasing_fair`),
  KEY `ix_unavailability_ENTERPRISE_id_enterprise` (`ENTERPRISE_id_enterprise`),
  KEY `ix_unavailability_PURCHASING_FAIR_id_purchasing_fair` (`PURCHASING_FAIR_id_purchasing_fair`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `unavailability_sp`
--

CREATE TABLE IF NOT EXISTS `unavailability_sp` (
  `id_unavailability_sp` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `start_datetime` datetime NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  `end_datetime` datetime NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  `PARTICIPANT_id_participant` int(11) unsigned NOT NULL COMMENT 'PK',
  `PURCHASING_FAIR_id_purchasing_fair` int(11) unsigned NOT NULL COMMENT 'FK',
  `date_deletion_unavailability_sp` datetime DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
  PRIMARY KEY (`id_unavailability_sp`),
  UNIQUE KEY `ux_participant_start_end_datetime` (`start_datetime`,`end_datetime`,`PARTICIPANT_id_participant`,`PURCHASING_FAIR_id_purchasing_fair`),
  KEY `ix_unavailability_sp_PARTICIPANT_id_participant` (`PARTICIPANT_id_participant`),
  KEY `ix_unavailability_sp_PF_id_pf` (`PURCHASING_FAIR_id_purchasing_fair`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Contenu de la table `unavailability_sp`
--

INSERT INTO `unavailability_sp` (`id_unavailability_sp`, `start_datetime`, `end_datetime`, `PARTICIPANT_id_participant`, `PURCHASING_FAIR_id_purchasing_fair`, `date_deletion_unavailability_sp`) VALUES
(1, '2018-02-05 08:00:00', '2018-02-05 19:00:00', 41, 2, NULL),
(3, '2018-02-06 08:00:00', '2018-02-06 00:00:00', 41, 2, NULL),
(4, '2018-02-07 08:00:00', '2018-02-07 19:00:00', 41, 2, NULL),
(5, '2018-02-08 08:00:00', '2018-02-08 19:00:00', 41, 2, NULL),
(6, '2018-02-09 08:00:00', '2018-02-09 19:00:00', 41, 2, NULL),
(7, '2018-02-12 08:00:00', '2018-02-12 19:00:00', 42, 2, NULL),
(8, '2018-02-13 08:00:00', '2018-02-13 19:00:00', 42, 2, NULL),
(9, '2018-02-14 08:00:00', '2018-02-14 19:00:00', 42, 2, NULL),
(10, '2018-02-15 08:00:00', '2018-02-15 19:00:00', 42, 2, NULL),
(11, '2018-02-16 08:00:00', '2018-02-16 19:00:00', 42, 2, NULL),
(12, '2018-02-08 08:00:00', '2018-02-08 19:00:00', 50, 2, NULL),
(13, '2018-02-09 08:00:00', '2018-02-09 19:00:00', 50, 2, NULL),
(14, '2018-02-05 08:00:00', '2018-02-05 19:00:00', 3, 2, NULL),
(15, '2018-02-08 08:00:00', '2018-02-08 19:00:00', 3, 2, NULL),
(16, '2018-02-05 08:00:00', '2018-02-05 19:00:00', 57, 2, NULL),
(17, '2018-02-06 08:00:00', '2018-02-06 19:00:00', 57, 2, NULL),
(18, '2018-02-07 08:00:00', '2018-02-07 19:00:00', 57, 2, NULL),
(19, '2018-02-08 08:00:00', '2018-02-08 19:00:00', 57, 2, NULL),
(20, '2018-02-05 08:00:00', '2018-02-05 19:00:00', 37, 2, NULL),
(21, '2018-02-08 08:00:00', '2018-02-08 19:00:00', 37, 2, NULL),
(22, '2018-02-09 08:00:00', '2018-02-09 19:00:00', 37, 2, NULL),
(23, '2018-02-09 08:00:00', '2018-02-09 19:00:00', 3, 2, NULL),
(24, '2018-02-05 08:00:00', '2018-02-05 19:00:00', 25, 2, NULL),
(25, '2018-02-06 08:00:00', '2018-02-06 19:00:00', 25, 2, NULL),
(26, '2018-02-07 08:00:00', '2018-02-07 19:00:00', 25, 2, NULL),
(27, '2018-02-08 08:00:00', '2018-02-08 19:00:00', 25, 2, NULL),
(28, '2018-02-09 08:00:00', '2018-02-09 19:00:00', 25, 2, NULL),
(29, '2018-02-05 08:00:00', '2018-02-05 19:00:00', 13, 2, NULL),
(30, '2018-02-09 08:00:00', '2018-02-09 19:00:00', 13, 2, NULL),
(32, '2019-08-09 00:00:00', '2019-08-09 00:00:00', 1, 1, NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `assignment_participant_department`
--
ALTER TABLE `assignment_participant_department`
  ADD CONSTRAINT `fk_assignment_participant_department_A` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_assignment_participant_department_B` FOREIGN KEY (`DEPARTMENT_id_department`) REFERENCES `department` (`id_department`);

--
-- Contraintes pour la table `assignment_participant_enterprise`
--
ALTER TABLE `assignment_participant_enterprise`
  ADD CONSTRAINT `fk_assignment_participant_enterprise_A` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_assignment_participant_enterprise_B` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`);

--
-- Contraintes pour la table `assignment_sp_store`
--
ALTER TABLE `assignment_sp_store`
  ADD CONSTRAINT `fk_assignment_sp_store_participant` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_assignment_sp_store_enterprise` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_assignment_sp_store_pf` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

--
-- Contraintes pour la table `enterprise`
--
ALTER TABLE `enterprise`
  ADD CONSTRAINT `fk_enterprise_department` FOREIGN KEY (`DEPARTMENT_id_department`) REFERENCES `department` (`id_department`),
  ADD CONSTRAINT `fk_enterprise_profile` FOREIGN KEY (`PROFILE_id_profile`) REFERENCES `profile` (`id_profile`);

--
-- Contraintes pour la table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_enterprise` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`);

--
-- Contraintes pour la table `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `fk_participation_participant` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_participation_purchasing_fair` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

--
-- Contraintes pour la table `purchasing_fair`
--
ALTER TABLE `purchasing_fair`
  ADD CONSTRAINT `fk_purchasing_fair_typeof_pf` FOREIGN KEY (`TYPEOF_PF_id_typeof_pf`) REFERENCES `typeof_pf` (`id_typeof_pf`);

--
-- Contraintes pour la table `requirement`
--
ALTER TABLE `requirement`
  ADD CONSTRAINT `fk_requirement_enterprise_store` FOREIGN KEY (`ENTERPRISE_STORE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_requirement_enterprise_provider` FOREIGN KEY (`ENTERPRISE_PROVIDER_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_requirement_purchasing_fair` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

--
-- Contraintes pour la table `unavailability`
--
ALTER TABLE `unavailability`
  ADD CONSTRAINT `fk_unavailability_enterprise` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_unavailability_purchasing_fair` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

--
-- Contraintes pour la table `unavailability_sp`
--
ALTER TABLE `unavailability_sp`
  ADD CONSTRAINT `fk_unavailability_sp_participant` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_unavailability_sp_purchasing_fair` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
