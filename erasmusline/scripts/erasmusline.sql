-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 13 Mei 2011 om 21:44
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
GRANT ALL PRIVILEGES  ON erasmusline.* TO 'erasmusline'@'%' IDENTIFIED BY 'orange' WITH GRANT OPTION;

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
  `companyEmail` varchar(100) DEFAULT NULL,
  `companyDescription` text,
  `companyWebsite` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`companyId`),
  UNIQUE KEY `companyEmail_UNIQUE` (`companyEmail`),
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
('ESP', 'Spain'),
('IRL', 'Ireland');

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
  `institutionId` varchar(100) NOT NULL,
  PRIMARY KEY (`courseId`),
  KEY `fk_coursespereducperinst_education1` (`educationId`),
  KEY `fk_coursespereducperinst_institutions1` (`institutionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Gegevens worden uitgevoerd voor tabel `coursespereducperinst`
--

INSERT INTO `coursespereducperinst` (`courseId`, `courseCode`, `courseName`, `ectsCredits`, `courseDescription`, `educationId`, `institutionId`) VALUES
(5, 'ICT', 'ICT', '5', 'dsf', 10, 'info@kahosl.be'),
(6, 'ELEK', 'Elektronica', '2', 'sdfds', 9, 'info@kahosl.be'),
(7, 'FRA', 'French', '3', 'sdfd', 8, 'info@kaalst.be'),
(8, 'MATH', 'Maths', '2', 'sdf', 9, 'info@kahosl.be');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `education`
--

DROP TABLE IF EXISTS `education`;
CREATE TABLE IF NOT EXISTS `education` (
  `educationId` int(11) NOT NULL AUTO_INCREMENT,
  `educationName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`educationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Gegevens worden uitgevoerd voor tabel `education`
--

INSERT INTO `education` (`educationId`, `educationName`) VALUES
(8, 'ICT'),
(9, 'Elektronics'),
(10, 'French'),
(11, 'Engels');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `educationperinstitute`
--

DROP TABLE IF EXISTS `educationperinstitute`;
CREATE TABLE IF NOT EXISTS `educationperinstitute` (
  `educationPerInstId` int(11) NOT NULL AUTO_INCREMENT,
  `studyId` int(11) NOT NULL,
  `Description` text,
  `institutionId` varchar(100) NOT NULL,
  PRIMARY KEY (`educationPerInstId`),
  KEY `fk_Institutions_has_Study_Study1` (`studyId`),
  KEY `fk_educationperinstitute_institutions1` (`institutionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Gegevens worden uitgevoerd voor tabel `educationperinstitute`
--

INSERT INTO `educationperinstitute` (`educationPerInstId`, `studyId`, `Description`, `institutionId`) VALUES
(6, 10, 'sdfsdf', 'info@kaalst.be'),
(8, 9, NULL, 'info@kahosl.be');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Gegevens worden uitgevoerd voor tabel `erasmuslevel`
--

INSERT INTO `erasmuslevel` (`levelId`, `levelName`, `levelDescrip`, `module`, `view`, `next`) VALUES
(6, 'Precandidate', NULL, 'precandidate', 'precandidate', 'Student Application and Learning Agreement'),
(8, 'Student Application and Learning Agreement', NULL, 'lagreeform', 'applicform', 'Offical Erasmus Contract'),
(9, 'Offical Erasmus Contract', NULL, 'preleave', 'contract', 'Accomodation Registration Form'),
(10, 'Accomodation Registration Form', NULL, 'accomodation', 'accomodation', 'Certificate Of Arrival'),
(11, 'Certificate Of Arrival', NULL, 'abroadstay', 'certarrival', 'Change to Learning Agreement'),
(12, 'Redo Student Application Form', NULL, 'lagreeform', 'applicform', NULL),
(13, 'Redo Learning Agreement', NULL, 'lagreeform', 'lagreement', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `erasmusstudent`
--

DROP TABLE IF EXISTS `erasmusstudent`;
CREATE TABLE IF NOT EXISTS `erasmusstudent` (
  `studentId` int(11) NOT NULL AUTO_INCREMENT,
  `users_email` varchar(100) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `educationPerInstId` int(11) DEFAULT NULL,
  `statusOfErasmus` varchar(45) DEFAULT NULL,
  `traineeOrStudy` varchar(3) DEFAULT NULL,
  `uploadedWhat` varchar(200) DEFAULT NULL,
  `ectsCredits` int(11) DEFAULT NULL,
  `mothertongue` varchar(45) DEFAULT NULL,
  `beenAbroad` varchar(3) DEFAULT NULL,
  `action` tinyint(4) DEFAULT NULL,
  `hostCoordinatorId` varchar(100) DEFAULT NULL,
  `homeCoordinatorId` varchar(100) DEFAULT NULL,
  `homeInstitutionId` varchar(100) DEFAULT NULL,
  `hostInstitutionId` varchar(100) DEFAULT NULL,
  UNIQUE KEY `users_email_UNIQUE` (`users_email`),
  KEY `fk_ErasmusInfo_Users2` (`studentId`),
  KEY `fk_ErasmusInfoPerStudent_Institutions_has_Study1` (`educationPerInstId`),
  KEY `fk_erasmusstudent_users1` (`users_email`),
  KEY `fk_erasmusstudent_users2` (`hostCoordinatorId`),
  KEY `fk_erasmusstudent_users3` (`homeCoordinatorId`),
  KEY `fk_erasmusstudent_institutions1` (`homeInstitutionId`),
  KEY `fk_erasmusstudent_institutions2` (`hostInstitutionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Gegevens worden uitgevoerd voor tabel `erasmusstudent`
--

INSERT INTO `erasmusstudent` (`studentId`, `users_email`, `startDate`, `endDate`, `educationPerInstId`, `statusOfErasmus`, `traineeOrStudy`, `uploadedWhat`, `ectsCredits`, `mothertongue`, `beenAbroad`, `action`, `hostCoordinatorId`, `homeCoordinatorId`, `homeInstitutionId`, `hostInstitutionId`) VALUES
(1, NULL, NULL, NULL, 8, 'Precandidate', NULL, ',,', NULL, NULL, NULL, 2, NULL, NULL, 'info@kahosl.be', NULL),
(2, NULL, NULL, NULL, 8, 'Precandidate', NULL, ',,', NULL, NULL, NULL, 2, NULL, NULL, 'info@kahosl.be', NULL),
(3, 'stephane.polet@kahosl.be', '2011-05-14', '2011-05-15', 8, 'Student Application and Learning Agreement', '1', ',,', 5, 'sdfsdf', 'No', 0, 'nathan.vanassche@kahosl.be', 'nathan.vanassche@kahosl.be', 'info@kahosl.be', 'info@kahosl.be');

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
  `erasmusLevelId` int(11) NOT NULL,
  `studentId` varchar(100) NOT NULL,
  PRIMARY KEY (`formId`),
  KEY `fk_forms_erasmusLevel1` (`erasmusLevelId`),
  KEY `fk_forms_erasmusstudent1` (`studentId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Gegevens worden uitgevoerd voor tabel `forms`
--

INSERT INTO `forms` (`formId`, `type`, `date`, `content`, `erasmusLevelId`, `studentId`) VALUES
(30, 'Precandidate', '2011-05-13', '{"familyName":"Van Assche","firstName":"Stephane","email":"stephane.polet@kahosl.be","instName":"KAHO Sint-Lieven","streetNr":"Grote Elsdries 9","tel":"222222","mobilePhone":"222222","study":"Elektronics","choice1":"Germany","choice2":"Spain","choice3":"Belgium","traineeOrStudy":"Study","cribb":"Yes","cribRent":"Yes","scolarship":"Yes","motivation":"sdqfsd"}', 6, 'stephane.polet@kahosl.be'),
(32, 'Student Application Form', '2011-05-13', '{"acaYear":"2010-2011","study":"Elektronics","sendInstName":"KAHO Sint-Lieven","sendInstAddress":"Gebroeders Desmetstraat - 9000 - Ghent - Belgium","sendDepCoorName":"Ann Mary","sendDepCoorTel":"222222","sendDepCoorMail":"nathan.vanassche@kahosl.be","sendInstCoorName":"admin admin","sendInstCoorTel":"222222","sendInstCoorMail":"admin","fName":"Stephane","faName":"Van Assche","dateBirth":"1989-12-12","sex":"Male","nation":"Belgium","birthPlace":"Ghent","cAddress":"Grote Elsdries 9 - 9000 Ronse","daateValid":"2012-11-12","cTel":"222222","pAddress":"sdfs","pTel":"234543","mail":"stephane.polet@kahosl.be","recInstitut":"KAHO Sint-Lieven","coountry":"Belgium","daateFrom":"2011-05-14","daateUntill":"2011-05-15","duration":"2","ectsPoints":"5","motivation":"sdfsdf","motherTongue":"sdfsdf","instrLanguage":"sdfsdf","languageCount":"2","language0":"sdfsd","studyThis0":"0","knowledgeThis0":"0","extraPrep0":"0","language1":"sdfsdf","studyThis1":"1","knowledgeThis1":"1","extraPrep1":"1","language2":"sdfsdf","studyThis2":"1","knowledgeThis2":"0","extraPrep2":"0","workCount":"2","type0":"sfsdf","firm0":"sdf","date0":"sfsd","country0":"sdf","type1":"sdfsd","firm1":"sdf","date1":"sdfsq","country1":"sdfs","type2":"df","firm2":"sdf","date2":"sdf","country2":"sdfq","diplome":"dsf","yEducation":"2","abroad":"No","whichInst":"sdfsdf"}', 8, 'stephane.polet@kahosl.be'),
(34, 'Learning Agreement', '2011-05-13', '{"courseCount":"{$courseCount}","code0":"ELEK","title0":"Elektronica","ects0":"2","sign":"sdfsdf","signDate":"2011-05-20"}', 8, 'stephane.polet@kahosl.be');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `grades`
--

DROP TABLE IF EXISTS `grades`;
CREATE TABLE IF NOT EXISTS `grades` (
  `courseId` int(11) NOT NULL,
  `localGrade` int(11) DEFAULT NULL,
  `ectsGrade` varchar(3) DEFAULT NULL,
  `courseDuration` varchar(45) DEFAULT NULL,
  `studentId` varchar(100) NOT NULL,
  PRIMARY KEY (`courseId`,`studentId`),
  KEY `fk_Grades_Institutions_has_Study_has_Courses1` (`courseId`),
  KEY `fk_grades_erasmusstudent1` (`studentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `grades`
--

INSERT INTO `grades` (`courseId`, `localGrade`, `ectsGrade`, `courseDuration`, `studentId`) VALUES
(6, NULL, NULL, NULL, 'stephane.polet@kahosl.be');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `institutions`
--

DROP TABLE IF EXISTS `institutions`;
CREATE TABLE IF NOT EXISTS `institutions` (
  `instId` int(11) NOT NULL AUTO_INCREMENT,
  `instEmail` varchar(100) DEFAULT NULL,
  `instName` varchar(200) NOT NULL,
  `instStreetNr` varchar(200) NOT NULL,
  `instCity` varchar(100) NOT NULL,
  `instPostalCode` varchar(45) NOT NULL,
  `instCountry` char(3) NOT NULL,
  `instTel` varchar(45) NOT NULL,
  `instFax` varchar(45) DEFAULT NULL,
  `instDescription` text,
  `instWebsite` varchar(100) DEFAULT NULL,
  `traineeOrStudy` int(11) NOT NULL,
  `url` varchar(250) NOT NULL,
  `scale` int(11) NOT NULL,
  PRIMARY KEY (`instId`),
  UNIQUE KEY `instEmail_UNIQUE` (`instEmail`),
  KEY `fk_Institutions_Country1` (`instCountry`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Gegevens worden uitgevoerd voor tabel `institutions`
--

INSERT INTO `institutions` (`instId`, `instEmail`, `instName`, `instStreetNr`, `instCity`, `instPostalCode`, `instCountry`, `instTel`, `instFax`, `instDescription`, `instWebsite`, `traineeOrStudy`, `url`, `scale`) VALUES
(6, 'info@kahosl.be', 'KAHO Sint-Lieven', 'Gebroeders Desmetstraat', 'Ghent', '9000', 'BEL', '2243223', '23344323', 'sdfs', 'www.kahosl.be', 1, 'sdf', 20),
(7, 'info@kaalst.be', 'KAHO Aalst', 'Aalstrstraat 27', 'Aalst', '9000', 'BEL', '2243223', '23344323', 'sdfsdf', 'www.kahosl.be', 1, 'sdf', 20);

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
  `studentId` varchar(100) NOT NULL,
  PRIMARY KEY (`rentalId`),
  KEY `fk_Lodging_Residents1` (`residentId`),
  KEY `fk_leasing_erasmusstudent1` (`studentId`)
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
  `email` varchar(100) DEFAULT NULL,
  `streetNr` varchar(200) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `postalCode` varchar(45) DEFAULT NULL,
  `mobilePhone` varchar(45) DEFAULT NULL,
  `country` char(3) NOT NULL,
  PRIMARY KEY (`ownerId`),
  UNIQUE KEY `email_UNIQUE` (`email`),
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
  `water` int(11) NOT NULL,
  `internet` int(11) NOT NULL,
  `beds` int(11) NOT NULL,
  `description` text,
  `available` tinyint(1) NOT NULL,
  `television` int(11) NOT NULL,
  `ownerId` varchar(100) NOT NULL,
  `elektricity` int(11) NOT NULL,
  PRIMARY KEY (`residenceId`),
  KEY `fk_residence_Country1` (`country`),
  KEY `fk_residence_owner1` (`ownerId`)
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
  `reader` text NOT NULL,
  `timestamp` date NOT NULL,
  `motivation` text,
  `readIt` tinyint(1) NOT NULL,
  `action` tinyint(1) NOT NULL,
  `erasmusLevelId` int(11) NOT NULL,
  `eventDescrip` text NOT NULL,
  `studentId` varchar(100) NOT NULL,
  PRIMARY KEY (`eventId`),
  KEY `fk_studentEvents_erasmusLevel1` (`erasmusLevelId`),
  KEY `fk_studentsevents_erasmusstudent1` (`studentId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Gegevens worden uitgevoerd voor tabel `studentsevents`
--

INSERT INTO `studentsevents` (`eventId`, `reader`, `timestamp`, `motivation`, `readIt`, `action`, `erasmusLevelId`, `eventDescrip`, `studentId`) VALUES
(37, 'Student', '2011-05-13', '', 1, 2, 6, 'Precandidate ingevuld.', 'stephane.polet@kahosl.be'),
(41, 'Student', '2011-05-13', 'approved my man', 1, 1, 6, 'Precandidate approved', 'stephane.polet@kahosl.be'),
(43, 'Student', '2011-05-13', '', 1, 30, 8, 'Filled in Student Application Form', 'stephane.polet@kahosl.be'),
(45, 'Student', '2011-05-13', '', 1, 22, 8, 'Filled in Learning Agreement', 'stephane.polet@kahosl.be'),
(52, 'Student', '2011-05-13', 'sdsdqf', 1, 0, 8, 'Learning Angreement is denied.', 'stephane.polet@kahosl.be');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `familyName` varchar(45) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `birthDate` date NOT NULL,
  `birthPlace` varchar(45) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `tel` varchar(45) NOT NULL,
  `mobilePhone` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `streetNr` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `postalCode` varchar(45) NOT NULL,
  `country` char(3) NOT NULL,
  `userLevel` enum('Student','Teaching Staff','Erasmus Coordinator','Higher Education Institution','Industrial Institution','International Relations Office Staff') NOT NULL,
  `isValidUser` int(11) NOT NULL,
  `verificationCode` varchar(32) DEFAULT NULL,
  `institutionId` varchar(100) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_Users_Country1` (`country`),
  KEY `fk_users_institutions1` (`institutionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Gegevens worden uitgevoerd voor tabel `users`
--

INSERT INTO `users` (`userId`, `email`, `familyName`, `firstName`, `password`, `birthDate`, `birthPlace`, `sex`, `tel`, `mobilePhone`, `fax`, `streetNr`, `city`, `postalCode`, `country`, `userLevel`, `isValidUser`, `verificationCode`, `institutionId`) VALUES
(19, 'stephane.polet@kahosl.be', 'Van Assche', 'Stephane', 'Azerty123', '1989-12-12', 'Ghent', 1, '222222', '222222', NULL, 'Grote Elsdries 9', 'Ronse', '9000', 'BEL', 'Student', 2, '0r5GaEuK7JirtswJCWQmjUeIO3PXfJaQ', 'info@kahosl.be'),
(20, 'admin', 'admin', 'admin', 'Azerty123', '2011-05-14', 'admin', 1, '222222', '222222', '12333333', 'Grote Elsdries 9', 'ghent', '9000', 'BEL', 'International Relations Office Staff', 2, 'sdsdf', 'info@kahosl.be'),
(21, 'nathan.vanassche@kahosl.be', 'Ann', 'Mary', 'Azerty123', '1989-12-12', 'Ghent', 0, '222222', '222222', NULL, 'Grote Elsdries 9', 'Ronse', '9000', 'BEL', 'Erasmus Coordinator', 2, 'r7JhdeHb04jzvry5dayPS6QcTOaAExsi', 'info@kahosl.be');

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
  ADD CONSTRAINT `fk_coursespereducperinst_institutions1` FOREIGN KEY (`institutionId`) REFERENCES `institutions` (`instEmail`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `educationperinstitute`
--
ALTER TABLE `educationperinstitute`
  ADD CONSTRAINT `fk_educationperinstitute_institutions1` FOREIGN KEY (`institutionId`) REFERENCES `institutions` (`instEmail`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Institutions_has_Study_Study1` FOREIGN KEY (`studyId`) REFERENCES `education` (`educationId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `erasmusstudent`
--
ALTER TABLE `erasmusstudent`
  ADD CONSTRAINT `fk_ErasmusInfoPerStudent_Institutions_has_Study1` FOREIGN KEY (`educationPerInstId`) REFERENCES `educationperinstitute` (`educationPerInstId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_erasmusstudent_institutions1` FOREIGN KEY (`homeInstitutionId`) REFERENCES `institutions` (`instEmail`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_erasmusstudent_institutions2` FOREIGN KEY (`hostInstitutionId`) REFERENCES `institutions` (`instEmail`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_erasmusstudent_users1` FOREIGN KEY (`users_email`) REFERENCES `users` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_erasmusstudent_users2` FOREIGN KEY (`hostCoordinatorId`) REFERENCES `users` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_erasmusstudent_users3` FOREIGN KEY (`homeCoordinatorId`) REFERENCES `users` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `forms`
--
ALTER TABLE `forms`
  ADD CONSTRAINT `fk_forms_erasmusLevel1` FOREIGN KEY (`erasmusLevelId`) REFERENCES `erasmuslevel` (`levelId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_forms_erasmusstudent1` FOREIGN KEY (`studentId`) REFERENCES `erasmusstudent` (`users_email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_grades_erasmusstudent1` FOREIGN KEY (`studentId`) REFERENCES `erasmusstudent` (`users_email`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
  ADD CONSTRAINT `fk_leasing_erasmusstudent1` FOREIGN KEY (`studentId`) REFERENCES `erasmusstudent` (`users_email`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
  ADD CONSTRAINT `fk_residence_owner1` FOREIGN KEY (`ownerId`) REFERENCES `owner` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `studentsevents`
--
ALTER TABLE `studentsevents`
  ADD CONSTRAINT `fk_studentEvents_erasmusLevel1` FOREIGN KEY (`erasmusLevelId`) REFERENCES `erasmuslevel` (`levelId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_studentsevents_erasmusstudent1` FOREIGN KEY (`studentId`) REFERENCES `erasmusstudent` (`users_email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_Users_Country1` FOREIGN KEY (`country`) REFERENCES `country` (`Code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_institutions1` FOREIGN KEY (`institutionId`) REFERENCES `institutions` (`instEmail`) ON DELETE NO ACTION ON UPDATE NO ACTION;
