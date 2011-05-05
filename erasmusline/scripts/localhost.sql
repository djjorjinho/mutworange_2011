-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 05 Mei 2011 om 17:50
-- Serverversie: 5.1.53
-- PHP-Versie: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `erasmusline`
--
DROP DATABASE `erasmusline`;
CREATE DATABASE `erasmusline` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `erasmusline`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `companyId` int(11) NOT NULL AUTO_INCREMENT,
  `companyName` varchar(200) NOT NULL,
  `companyStreetNr` varchar(200) NOT NULL,
  `companyCity` varchar(100) NOT NULL,
  `companyPostalCode` varchar(45) NOT NULL,
  `companyCountry` char(3) NOT NULL,
  `companyTel` varchar(45) NOT NULL,
  `companyFax` varchar(45) DEFAULT NULL,
  `companyEmail` varchar(150) DEFAULT NULL,
  `companyDescription` text,
  `companyWebsite` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`companyId`),
  KEY `fk_Institutions_Country1` (`companyCountry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Gegevens worden uitgevoerd voor tabel `companies`
--


-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `Code` char(3) NOT NULL DEFAULT '',
  `Name` char(52) NOT NULL DEFAULT '',
  PRIMARY KEY (`Code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `country`
--

INSERT INTO `country` (`Code`, `Name`) VALUES
('BEL', 'Belgium'),
('DEU', 'Germany'),
('ESP', 'Spain');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `coursespereducperinst`
--

DROP TABLE IF EXISTS `coursespereducperinst`;
CREATE TABLE IF NOT EXISTS `coursespereducperinst` (
  `courseId` int(11) NOT NULL AUTO_INCREMENT,
  `courseCode` varchar(45) NOT NULL,
  `courseName` varchar(100) NOT NULL,
  `ectsCredits` varchar(45) NOT NULL,
  `courseDescription` text,
  `educationId` int(11) NOT NULL,
  `instId` int(11) NOT NULL,
  PRIMARY KEY (`courseId`),
  KEY `fk_coursespereducperinst_education1` (`educationId`),
  KEY `fk_coursespereducperinst_institutions1` (`instId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `coursespereducperinst`
--

INSERT INTO `coursespereducperinst` (`courseId`, `courseCode`, `courseName`, `ectsCredits`, `courseDescription`, `educationId`, `instId`) VALUES
(3, 'ICT', 'ICT', '5', NULL, 3, 4),
(4, 'ELEK', 'Elektronica', '2', NULL, 3, 5);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `education`
--

DROP TABLE IF EXISTS `education`;
CREATE TABLE IF NOT EXISTS `education` (
  `educationId` int(11) NOT NULL AUTO_INCREMENT,
  `educationName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`educationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `education`
--

INSERT INTO `education` (`educationId`, `educationName`) VALUES
(3, 'ICT'),
(4, 'Elektronics'),
(5, 'Engels');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `educationperinstitute`
--

DROP TABLE IF EXISTS `educationperinstitute`;
CREATE TABLE IF NOT EXISTS `educationperinstitute` (
  `educationPerInstId` int(11) NOT NULL AUTO_INCREMENT,
  `institutionId` int(11) NOT NULL,
  `studyId` int(11) NOT NULL,
  `Description` text,
  PRIMARY KEY (`educationPerInstId`),
  KEY `fk_Institutions_has_Study_Study1` (`studyId`),
  KEY `fk_Institutions_has_Study_Institutions1` (`institutionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `educationperinstitute`
--

INSERT INTO `educationperinstitute` (`educationPerInstId`, `institutionId`, `studyId`, `Description`) VALUES
(3, 4, 3, 'fala'),
(4, 5, 3, NULL),
(5, 5, 4, 'qdsfqdf');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `erasmuslevel`
--

DROP TABLE IF EXISTS `erasmuslevel`;
CREATE TABLE IF NOT EXISTS `erasmuslevel` (
  `levelId` int(11) NOT NULL AUTO_INCREMENT,
  `levelName` varchar(45) DEFAULT NULL,
  `levelDescrip` text,
  `module` varchar(45) DEFAULT NULL,
  `view` varchar(45) DEFAULT NULL,
  `next` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`levelId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Gegevens worden uitgevoerd voor tabel `erasmuslevel`
--

INSERT INTO `erasmuslevel` (`levelId`, `levelName`, `levelDescrip`, `module`, `view`, `next`) VALUES
(6, 'Precandidate', NULL, 'precandidate', 'precandidate', 'Student Application Form'),
(7, 'Student Application Form', NULL, 'lagreeform', 'applicform', 'Learning Agreement'),
(8, 'Learning Agreement', NULL, 'lagreeform', 'lagreement', 'Offical Erasmus Contract'),
(9, 'Offical Erasmus Contract', NULL, 'preleave', 'contract', 'Accomodation Registration Form'),
(10, 'Accomodation Registration Form', NULL, 'accomodation', 'accomodation', 'Certificate Of Arrival'),
(11, 'Certificate Of Arrival', NULL, 'abroadstay', 'certarrival', 'Change to Learning Agreement');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `erasmusstudent`
--

DROP TABLE IF EXISTS `erasmusstudent`;
CREATE TABLE IF NOT EXISTS `erasmusstudent` (
  `studentId` int(11) NOT NULL,
  `homeCoordinatorId` int(11) DEFAULT NULL,
  `hostCoordinatorId` int(11) DEFAULT NULL,
  `homeInstitutionId` int(11) DEFAULT NULL,
  `hostInstitutionId` int(11) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `educationPerInstId` int(11) DEFAULT NULL,
  `statusOfErasmus` varchar(45) DEFAULT NULL,
  `traineeOrStudy` varchar(3) DEFAULT NULL,
  `uploadedWhat` varchar(200) DEFAULT NULL,
  `ectsCredits` int(11) DEFAULT NULL,
  `mothertongue` varchar(45) DEFAULT NULL,
  `beenAbroad` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`studentId`),
  KEY `fk_ErasmusInfo_Users2` (`studentId`),
  KEY `fk_ErasmusInfo_Users1` (`homeCoordinatorId`),
  KEY `fk_ErasmusInfo_Users3` (`hostCoordinatorId`),
  KEY `fk_ErasmusInfo_Institutions1` (`homeInstitutionId`),
  KEY `fk_ErasmusInfo_Institutions2` (`hostInstitutionId`),
  KEY `fk_ErasmusInfoPerStudent_Institutions_has_Study1` (`educationPerInstId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `erasmusstudent`
--

INSERT INTO `erasmusstudent` (`studentId`, `homeCoordinatorId`, `hostCoordinatorId`, `homeInstitutionId`, `hostInstitutionId`, `startDate`, `endDate`, `educationPerInstId`, `statusOfErasmus`, `traineeOrStudy`, `uploadedWhat`, `ectsCredits`, `mothertongue`, `beenAbroad`) VALUES
(9, NULL, NULL, 5, NULL, NULL, NULL, 5, 'Precandidate', NULL, ',,', NULL, NULL, NULL),
(11, NULL, NULL, 5, NULL, NULL, NULL, 5, 'Precandidate', NULL, '(1) Multicasting Overzicht.pdf,,', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `forms`
--

DROP TABLE IF EXISTS `forms`;
CREATE TABLE IF NOT EXISTS `forms` (
  `formId` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  `date` date NOT NULL,
  `content` text NOT NULL,
  `studentId` int(11) NOT NULL,
  `erasmusLevelId` int(11) NOT NULL,
  PRIMARY KEY (`formId`),
  KEY `fk_Forms_ErasmusInfoPerStudent1` (`studentId`),
  KEY `fk_forms_erasmusLevel1` (`erasmusLevelId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Gegevens worden uitgevoerd voor tabel `forms`
--

INSERT INTO `forms` (`formId`, `type`, `date`, `content`, `studentId`, `erasmusLevelId`) VALUES
(18, 'precandidate', '2011-05-05', '{"familyName":"Van Assche","firstName":"Stephane","email":"nathan.vanassche@kahosl.be","instName":"KAHO Sint-Lieven","streetNr":"Grote Elsdries 9","tel":"222222","mobilePhone":"222222","study":"Elektronics","choice1":"Germany","choice2":"Spain","choice3":"Belgium","motivation":"sdfsqdf","traineeOrStudy":"Study","cribb":"No","cribRent":"Yes","scolarship":"Yes"}', 9, 6),
(19, 'precandidate', '2011-05-05', '{"familyName":"Moens","firstName":"Jonas","email":"nathan.vanassche@kahosl.be","instName":"KAHO Sint-Lieven","streetNr":"Grote Elsdries 9","tel":"34567890","mobilePhone":"34567890","study":"Elektronics","choice1":"Germany","choice2":"Germany","choice3":"Belgium","motivation":"kqsdjflkqjsdlfk","traineeOrStudy":"Study","cribb":"No","cribRent":"No","scolarship":"No"}', 11, 6);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `grades`
--

DROP TABLE IF EXISTS `grades`;
CREATE TABLE IF NOT EXISTS `grades` (
  `courseId` int(11) NOT NULL,
  `studentId` int(11) NOT NULL,
  `localGrade` int(11) DEFAULT NULL,
  `ectsGrade` varchar(3) DEFAULT NULL,
  `courseDuration` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`courseId`,`studentId`),
  KEY `fk_Grades_Institutions_has_Study_has_Courses1` (`courseId`),
  KEY `fk_Grades_ErasmusInfoPerStudent1` (`studentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `grades`
--


-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `institutions`
--

DROP TABLE IF EXISTS `institutions`;
CREATE TABLE IF NOT EXISTS `institutions` (
  `instId` int(11) NOT NULL AUTO_INCREMENT,
  `instName` varchar(200) NOT NULL,
  `instStreetNr` varchar(200) NOT NULL,
  `instCity` varchar(100) NOT NULL,
  `instPostalCode` varchar(45) NOT NULL,
  `instCountry` char(3) NOT NULL,
  `instTel` varchar(45) NOT NULL,
  `instFax` varchar(45) DEFAULT NULL,
  `instEmail` varchar(45) DEFAULT NULL,
  `instDescription` text,
  `instWebsite` varchar(100) DEFAULT NULL,
  `traineeOrStudy` int(11) NOT NULL,
  `url` varchar(250) NOT NULL,
  PRIMARY KEY (`instId`),
  KEY `fk_Institutions_Country1` (`instCountry`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `institutions`
--

INSERT INTO `institutions` (`instId`, `instName`, `instStreetNr`, `instCity`, `instPostalCode`, `instCountry`, `instTel`, `instFax`, `instEmail`, `instDescription`, `instWebsite`, `traineeOrStudy`, `url`) VALUES
(4, 'KAHO Aalst', 'Gebroeders Desmetstraat', 'Ghent', '9000', 'DEU', '00000000', '00000000', 'info@kahosl.be', 'lorem ipsum', 'www.kahosl.be', 1, ''),
(5, 'KAHO Sint-Lieven', 'Gebroeders Desmetstraat', 'Aalst', '9000', 'DEU', '0000000', '0000000', 'info@kahosl.be', NULL, 'www.kahosl.be', 1, '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `leasing`
--

DROP TABLE IF EXISTS `leasing`;
CREATE TABLE IF NOT EXISTS `leasing` (
  `rentalId` int(11) NOT NULL AUTO_INCREMENT,
  `outboundUser` int(11) DEFAULT NULL,
  `Subleased` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `inboundUser` int(11) DEFAULT NULL,
  `residentId` int(11) NOT NULL,
  `studentId` int(11) NOT NULL,
  PRIMARY KEY (`rentalId`),
  KEY `fk_Lodging_Residents1` (`residentId`),
  KEY `fk_leasing_erasmusStudent1` (`studentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Gegevens worden uitgevoerd voor tabel `leasing`
--


-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `owner`
--

DROP TABLE IF EXISTS `owner`;
CREATE TABLE IF NOT EXISTS `owner` (
  `ownerId` int(11) NOT NULL AUTO_INCREMENT,
  `familyName` varchar(200) DEFAULT NULL,
  `firstName` varchar(200) DEFAULT NULL,
  `tel` varchar(45) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `streetNr` varchar(200) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `postalCode` varchar(45) DEFAULT NULL,
  `mobilePhone` varchar(45) DEFAULT NULL,
  `country` char(3) NOT NULL,
  PRIMARY KEY (`ownerId`),
  KEY `fk_owner_Country1` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Gegevens worden uitgevoerd voor tabel `owner`
--


-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `residence`
--

DROP TABLE IF EXISTS `residence`;
CREATE TABLE IF NOT EXISTS `residence` (
  `residenceId` int(11) NOT NULL AUTO_INCREMENT,
  `price` int(11) NOT NULL,
  `streetNr` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `postalCode` varchar(45) NOT NULL,
  `country` char(3) NOT NULL,
  `kitchen` tinyint(1) NOT NULL,
  `bathroom` tinyint(1) NOT NULL,
  `water/Electricity` int(11) NOT NULL,
  `internet` int(11) NOT NULL,
  `beds` int(11) NOT NULL,
  `description` text,
  `available` tinyint(1) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `television` int(11) NOT NULL,
  PRIMARY KEY (`residenceId`),
  KEY `fk_Residents_Owner1` (`ownerId`),
  KEY `fk_residence_Country1` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Gegevens worden uitgevoerd voor tabel `residence`
--


-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `studentsevents`
--

DROP TABLE IF EXISTS `studentsevents`;
CREATE TABLE IF NOT EXISTS `studentsevents` (
  `eventId` int(11) NOT NULL AUTO_INCREMENT,
  `event` text NOT NULL,
  `timestamp` date NOT NULL,
  `motivation` text,
  `read_Notification` tinyint(1) NOT NULL,
  `erasmusStudentId` int(11) NOT NULL,
  `action` tinyint(1) NOT NULL,
  `erasmusLevelId` int(11) NOT NULL,
  PRIMARY KEY (`eventId`),
  KEY `fk_ErasmusProgressPerStudent_ErasmusInfoPerStudent1` (`erasmusStudentId`),
  KEY `fk_studentEvents_erasmusLevel1` (`erasmusLevelId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Gegevens worden uitgevoerd voor tabel `studentsevents`
--

INSERT INTO `studentsevents` (`eventId`, `event`, `timestamp`, `motivation`, `read_Notification`, `erasmusStudentId`, `action`, `erasmusLevelId`) VALUES
(10, 'sdfsdf', '2011-05-05', '', 0, 9, 0, 6),
(11, 'sdfsdf', '2011-05-05', '', 0, 11, 0, 6);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `familyName` varchar(45) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `birthDate` date NOT NULL,
  `birthPlace` varchar(45) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `tel` varchar(45) NOT NULL,
  `mobilePhone` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `streetNr` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `postalCode` varchar(45) NOT NULL,
  `country` char(3) NOT NULL,
  `userLevel` enum('Student','Teaching Staff','Erasmus Coordinator','Higher Education Institution','Industrial Institution','International Relations Office Staff') NOT NULL,
  `isValidUser` int(11) NOT NULL,
  `verificationCode` varchar(32) DEFAULT NULL,
  `institutionId` int(11) NOT NULL,
  PRIMARY KEY (`userId`),
  KEY `fk_Users_Country1` (`country`),
  KEY `fk_Users_Institutions1` (`institutionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Gegevens worden uitgevoerd voor tabel `users`
--

INSERT INTO `users` (`userId`, `familyName`, `firstName`, `password`, `birthDate`, `birthPlace`, `sex`, `tel`, `mobilePhone`, `fax`, `email`, `streetNr`, `city`, `postalCode`, `country`, `userLevel`, `isValidUser`, `verificationCode`, `institutionId`) VALUES
(9, 'Van Assche', 'Stephane', 'Azerty123', '1989-12-12', 'Ghent', 1, '222222', '222222', NULL, 'bob.vanassche@kahosl.be', 'Grote Elsdries 9', 'Ronse', '9000', 'BEL', 'Student', 1, 'tabxvxj1XkuFE7uyuLczBcqcQxGHvLuJ', 5),
(10, 'admin', 'admin', 'admin', '1989-12-12', 'admin', 1, '1233456', '222222', NULL, 'admin', 'Grote Elsdries 9', 'ghent', '9000', 'ESP', 'Industrial Institution', 2, 'd1TMtPNuwaRXtI77V8Je8gJUjrRABSmc', 5),
(11, 'Moens', 'Jonas', 'Azerty123', '1989-01-24', 'Ghent', 1, '34567890', '34567890', NULL, 'nathan.vanassche@kahosl.be', 'Grote Elsdries 9', 'Ghent', '9000', 'BEL', 'Student', 1, '7i3IhgVFX5jxsjaKH6Hgb0NhRVt22FuF', 5),
(12, 'Schoofs', 'Luc', 'schoofs', '2011-05-01', 'Ghent', 1, '12345678', '123456789', '123456789', 'nathan.vanassche@kahosl.be', 'klsqjdf', 'lkqjsdf', '9000', 'BEL', 'International Relations Office Staff', 5, '123456789', 5);

--
-- Beperkingen voor gedumpte tabellen
--

--
-- Beperkingen voor tabel `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `fk_Institutions_Country10` FOREIGN KEY (`companyCountry`) REFERENCES `country` (`Code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `coursespereducperinst`
--
ALTER TABLE `coursespereducperinst`
  ADD CONSTRAINT `fk_coursespereducperinst_education1` FOREIGN KEY (`educationId`) REFERENCES `education` (`educationId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_coursespereducperinst_institutions1` FOREIGN KEY (`instId`) REFERENCES `institutions` (`instId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `educationperinstitute`
--
ALTER TABLE `educationperinstitute`
  ADD CONSTRAINT `fk_Institutions_has_Study_Institutions1` FOREIGN KEY (`institutionId`) REFERENCES `institutions` (`instId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Institutions_has_Study_Study1` FOREIGN KEY (`studyId`) REFERENCES `education` (`educationId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `erasmusstudent`
--
ALTER TABLE `erasmusstudent`
  ADD CONSTRAINT `fk_ErasmusInfoPerStudent_Institutions_has_Study1` FOREIGN KEY (`educationPerInstId`) REFERENCES `educationperinstitute` (`educationPerInstId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ErasmusInfo_Institutions1` FOREIGN KEY (`homeInstitutionId`) REFERENCES `institutions` (`instId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ErasmusInfo_Institutions2` FOREIGN KEY (`hostInstitutionId`) REFERENCES `institutions` (`instId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ErasmusInfo_Users1` FOREIGN KEY (`homeCoordinatorId`) REFERENCES `users` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ErasmusInfo_Users2` FOREIGN KEY (`studentId`) REFERENCES `users` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ErasmusInfo_Users3` FOREIGN KEY (`hostCoordinatorId`) REFERENCES `users` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `forms`
--
ALTER TABLE `forms`
  ADD CONSTRAINT `fk_Forms_ErasmusInfoPerStudent1` FOREIGN KEY (`studentId`) REFERENCES `erasmusstudent` (`studentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_forms_erasmusLevel1` FOREIGN KEY (`erasmusLevelId`) REFERENCES `erasmuslevel` (`levelId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_Grades_ErasmusInfoPerStudent1` FOREIGN KEY (`studentId`) REFERENCES `erasmusstudent` (`studentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Grades_Institutions_has_Study_has_Courses1` FOREIGN KEY (`courseId`) REFERENCES `coursespereducperinst` (`courseId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `institutions`
--
ALTER TABLE `institutions`
  ADD CONSTRAINT `fk_Institutions_Country1` FOREIGN KEY (`instCountry`) REFERENCES `country` (`Code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `leasing`
--
ALTER TABLE `leasing`
  ADD CONSTRAINT `fk_leasing_erasmusStudent1` FOREIGN KEY (`studentId`) REFERENCES `erasmusstudent` (`studentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Lodging_Residents1` FOREIGN KEY (`residentId`) REFERENCES `residence` (`residenceId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `owner`
--
ALTER TABLE `owner`
  ADD CONSTRAINT `fk_owner_Country1` FOREIGN KEY (`country`) REFERENCES `country` (`Code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `residence`
--
ALTER TABLE `residence`
  ADD CONSTRAINT `fk_residence_Country1` FOREIGN KEY (`country`) REFERENCES `country` (`Code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Residents_Owner1` FOREIGN KEY (`ownerId`) REFERENCES `owner` (`ownerId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `studentsevents`
--
ALTER TABLE `studentsevents`
  ADD CONSTRAINT `fk_ErasmusProgressPerStudent_ErasmusInfoPerStudent1` FOREIGN KEY (`erasmusStudentId`) REFERENCES `erasmusstudent` (`studentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_studentEvents_erasmusLevel1` FOREIGN KEY (`erasmusLevelId`) REFERENCES `erasmuslevel` (`levelId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_Users_Country1` FOREIGN KEY (`country`) REFERENCES `country` (`Code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Users_Institutions1` FOREIGN KEY (`institutionId`) REFERENCES `institutions` (`instId`) ON DELETE NO ACTION ON UPDATE NO ACTION;
