-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               10.4.14-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for publications
CREATE DATABASE IF NOT EXISTS `publications` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `publications`;

-- Dumping structure for table publications.editions
CREATE TABLE IF NOT EXISTS `editions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TitleOrPublicationID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Abbreviation` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `TitleOrPublicationID` (`TitleOrPublicationID`),
  CONSTRAINT `editions_ibfk_1` FOREIGN KEY (`TitleOrPublicationID`) REFERENCES `titlesandpublications` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table publications.editions: ~131 rows (approximately)
REPLACE INTO `editions` (`ID`, `TitleOrPublicationID`, `Name`, `Abbreviation`) VALUES
	(1, 1, 'E1', 'E1'),
	(2, 1, 'E2', 'E2'),
	(3, 1, 'MANDELA SUPP (01)', 'MS'),
	(4, 1, 'Dispatch Sport (01)', 'DS'),
	(5, 1, 'Property Dispatch (01)', 'PD'),
	(6, 1, 'Budget Supplement (01)', 'BS'),
	(7, 1, '01S105/03/15 AFRICA OPEN (01)', 'AO'),
	(8, 1, 'saturday dispatch (02)', 'SD'),
	(9, 1, 'Supplement (02)', 'S2'),
	(10, 1, 'Daily Dispatch (01)', 'DD'),
	(11, 1, 'Mdantsane Dispatch (01)', 'MD'),
	(12, 1, 'Daily Dispatch (02)', 'D2'),
	(13, 1, 'Wrap (01)', 'W1'),
	(14, 1, 'saturday dispatch (01)', 'S1'),
	(15, 1, 'Weekend Property (01)', 'WP'),
	(16, 1, 'Supplement (01)', 'S1'),
	(17, 1, 'Posters (01)', 'P1'),
	(18, 2, 'E1', 'E1'),
	(19, 2, 'E2', 'E2'),
	(20, 2, 'Wrap (01)', 'W1'),
	(21, 2, 'Mainbody (01)', 'M1'),
	(22, 2, 'Posters (01)', 'P1'),
	(23, 2, 'Weekender (01)', 'W1'),
	(24, 3, 'E1', 'E1'),
	(25, 3, 'E2', 'E2'),
	(26, 3, 'E3', 'E3'),
	(27, 3, 'Mainbody (01)', 'M1'),
	(28, 3, 'Racing (01)', 'R1'),
	(29, 3, '01bg2402 BUDGET SUPP (01)', 'BS'),
	(30, 3, '01sup1203 IRON MAN (01)', 'IM'),
	(31, 3, '01wwe0703 (01)', 'W1'),
	(32, 3, 'LaFemme (01)', 'LF'),
	(33, 3, '01ten1604 (01)', 'T1'),
	(34, 3, '01tens2704 tens 2003 (01)', 'T1'),
	(35, 3, '01sup2303 Splash (01)', 'S1'),
	(36, 3, 'Mainbody (03)', 'M3'),
	(37, 3, '01ten1704 1995 (01)', 'T1'),
	(38, 3, 'Wrap (01)', 'W1'),
	(39, 3, 'Surveys (02)', 'S2'),
	(40, 3, 'TGIF (01)', 'TG'),
	(41, 3, 'Surveys (01)', 'S1'),
	(42, 3, '01bee2510 (01)', 'B1'),
	(43, 3, 'POSTERS (01)', 'P1'),
	(44, 3, 'EP Herald 1847', 'E1'),
	(45, 3, 'Matricsupplment (01)', 'M1'),
	(46, 3, 'Mainbody (02)', 'M2'),
	(47, 3, 'Motoring (01)', 'M1'),
	(48, 3, 'pr011703 Iron Man (01)', 'IM'),
	(49, 3, '01Matric2912 Matric Results (01)', 'M1'),
	(50, 3, 'Shibobo (01)', 'S1'),
	(51, 3, 'None', 'N1'),
	(52, 4, 'E1', 'E1'),
	(53, 4, 'E2', 'E2'),
	(54, 4, 'E3', 'E3'),
	(55, 4, 'E4', 'E4'),
	(56, 4, 'Neighbourhood (01)', 'N1'),
	(57, 4, 'Wrap (01)', 'W1'),
	(58, 4, 'Mainbody (04)', 'M4'),
	(59, 4, 'Mainbody (02)', 'M2'),
	(60, 4, 'Auto Body (01)', 'A1'),
	(61, 4, 'Property (01)', 'P1'),
	(62, 4, 'Posters (01)', 'P1'),
	(63, 4, 'SwitchOn (01)', 'S1'),
	(64, 4, 'Surveys (01)', 'S1'),
	(65, 4, 'Shibobo (01)', 'S1'),
	(66, 4, 'Mainbody (03)', 'M3'),
	(67, 4, 'Shibobo (02)', 'S2'),
	(68, 4, 'Mainbody (01)', 'M1'),
	(69, 4, 'Leisure (01)', 'L1'),
	(70, 4, 'Business (01)', 'B1'),
	(71, 4, 'None', 'N1'),
	(72, 5, 'E1', 'E1'),
	(73, 5, 'Algoa Sun (01)', 'AS'),
	(74, 5, 'the representative (01)', 'TR'),
	(75, 5, 'our times (01)', 'OT'),
	(76, 5, 'Go!&Express (01)', 'GE'),
	(77, 5, 'Algoa Sun (04)', 'A4'),
	(78, 5, 'ilizwe (01)', 'IL'),
	(79, 5, 'Surveys (01)', 'S1'),
	(80, 5, 'the representative (02)', 'T2'),
	(81, 5, 'talktown (01)', 'TT'),
	(82, 5, 'Algoa Sun (02)', 'A2'),
	(83, 5, 'themercury (01)', 'TM'),
	(84, 5, 'Surveys (02)', 'S2'),
	(85, 5, 'Go!&Express (02)', 'G2'),
	(86, 6, 'E1', 'E1'),
	(87, 6, 'E2', 'E2'),
	(88, 6, 'E5', 'E5'),
	(89, 6, 'E3', 'E3'),
	(90, 6, 'E4', 'E4'),
	(91, 6, 'E6', 'E6'),
	(92, 6, 'E7', 'E7'),
	(93, 6, 'Sunday World (02)', 'S2'),
	(94, 6, 'Matric Results (01)', 'MR'),
	(95, 6, 'Matric Results (02)', 'M2'),
	(96, 6, 'Matric Results (05)', 'M5'),
	(97, 6, 'CSI Edition (01)', 'CE'),
	(98, 6, 'Sowetan (01)', 'SW'),
	(99, 6, 'Timeout (01)', 'TO'),
	(100, 6, 'Matric Results (07)', 'M7'),
	(101, 6, 'Matric Results (03)', 'M3'),
	(102, 6, 'Sowetan (02)', 'S2'),
	(103, 6, 'Matric Results (04)', 'M4'),
	(104, 6, 'Surveys (02)', 'S2'),
	(105, 6, 'Surveys (01)', 'S1'),
	(106, 6, 'Sowetan (03)', 'S3'),
	(107, 6, 'Matric Results (06)', 'M6'),
	(108, 6, 'SMag (01)', 'SM'),
	(109, 7, 'E1', 'E1'),
	(110, 7, 'E2', 'E2'),
	(111, 8, 'E1', 'E1'),
	(112, 9, 'E1', 'E1'),
	(113, 10, 'E1', 'E1'),
	(114, 10, 'Investors Monthly (01)', 'IM'),
	(115, 10, 'FM Stats (01)', 'FS'),
	(116, 10, 'AdFocus (01)', 'AF'),
	(117, 10, 'Investors Monthl (01)', 'IM'),
	(118, 10, 'The Little Black Book (01)', 'LB'),
	(119, 10, 'Campus (01)', 'CA'),
	(120, 10, 'Innovations (01)', 'IN'),
	(121, 10, 'Top Companies (01)', 'TC'),
	(122, 10, 'Surveys (default) (01)', 'SD'),
	(123, 10, 'Property Handbook (01)', 'PH'),
	(124, 10, 'Special Survey (01)', 'SS'),
	(125, 10, 'Posters (01)', 'PO'),
	(126, 10, 'Surveys (01)', 'S1'),
	(127, 10, 'CIO Africa (01)', 'CA'),
	(128, 10, 'Travel (01)', 'TR'),
	(129, 10, 'Financial Mail (01)', 'FM'),
	(130, 10, 'Future Company (01)', 'FC'),
	(131, 10, 'Budget (01)', 'BU');

-- Dumping structure for table publications.titlesandpublications
CREATE TABLE IF NOT EXISTS `titlesandpublications` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Abbreviation` varchar(10) NOT NULL,
  `Type` enum('Title','Publication') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table publications.titlesandpublications: ~28 rows (approximately)
REPLACE INTO `titlesandpublications` (`ID`, `Name`, `Abbreviation`, `Type`) VALUES
	(1, 'Globe', 'GL', 'Title'),
	(2, 'SD', 'SD', 'Title'),
	(3, 'HR', 'HR', 'Title'),
	(4, 'WP', 'WP', 'Title'),
	(5, 'CN', 'CN', 'Title'),
	(6, 'SO', 'SO', 'Title'),
	(7, 'BT', 'BT', 'Title'),
	(8, 'LS', 'LS', 'Title'),
	(9, 'OS', 'OS', 'Title'),
	(10, 'NB', 'NB', 'Title'),
	(11, 'FM', 'FM', 'Title'),
	(12, 'The Times', 'TT', 'Publication'),
	(13, 'Algoa', 'AL', 'Publication'),
	(14, 'Indabazethu', 'IN', 'Publication'),
	(15, 'Images', '', 'Publication'),
	(16, 'SA HomeOwner', 'SH', 'Publication'),
	(17, 'Avusa Media Supplements', 'AM', 'Publication'),
	(18, 'AvocadoAFR', 'AA', 'Publication'),
	(19, 'AvocadoENG', 'AE', 'Publication'),
	(20, 'Stuff', 'ST', 'Publication'),
	(21, 'Elle', 'EL', 'Publication'),
	(22, 'ElleDeco', 'ED', 'Publication'),
	(23, 'To_Delete_', 'TD_', 'Publication'),
	(24, 'Sunday World', 'SW', 'Publication'),
	(25, 'BIGnews', 'BN', 'Publication'),
	(26, 'promo', 'PR', 'Publication'),
	(27, 'System', 'SY', 'Publication'),
	(28, '<no publication<', 'NP', 'Publication');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
