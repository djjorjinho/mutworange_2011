-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 22 Jun 2011 om 16:56
-- Serverversie: 5.5.8
-- PHP-Versie: 5.3.5

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
('IRL', 'Ireland'),
('POR', 'Portugal');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Gegevens worden uitgevoerd voor tabel `coursespereducperinst`
--

INSERT INTO `coursespereducperinst` (`courseId`, `courseCode`, `courseName`, `ectsCredits`, `courseDescription`, `educationId`, `institutionId`) VALUES
(9, 'Java', 'Java Programming', '5', 'Java Programming', 13, 'info@kahosl.be'),
(10, 'Web', 'Web Design', '4', 'Web Design', 13, 'info@kahosl.be'),
(11, 'FRE', 'French', '4', 'French', 16, 'info@kaalst.be'),
(12, 'ENG', 'English', '3', 'English', 16, 'info@kaalst.be'),
(13, 'Cisco', 'Networking', '4', 'Networking', 15, 'info@kaalst.be'),
(14, 'Win', 'Windows', '5', 'Windows', 15, 'info@kaalst.be'),
(15, 'LIN', 'Linux', '4', 'Linux', 15, 'info@kaalst.be');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `education`
--

DROP TABLE IF EXISTS `education`;
CREATE TABLE IF NOT EXISTS `education` (
  `educationId` int(11) NOT NULL AUTO_INCREMENT,
  `educationName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`educationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Gegevens worden uitgevoerd voor tabel `education`
--

INSERT INTO `education` (`educationId`, `educationName`) VALUES
(12, 'Electronica'),
(13, 'ICT'),
(14, 'Mathemathics'),
(15, 'ICT'),
(16, 'French');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Gegevens worden uitgevoerd voor tabel `educationperinstitute`
--

INSERT INTO `educationperinstitute` (`educationPerInstId`, `studyId`, `Description`, `institutionId`) VALUES
(9, 12, 'Electronica', 'info@kahosl.be'),
(10, 13, 'ICT', 'info@kahosl.be'),
(11, 14, 'Math', 'info@kahosl.be'),
(12, 15, 'ICT', 'info@kaalst.be'),
(13, 16, 'French', 'info@kaalst.be');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `erasmuslevel`
--

DROP TABLE IF EXISTS `erasmuslevel`;
CREATE TABLE IF NOT EXISTS `erasmuslevel` (
  `levelId` int(11) NOT NULL AUTO_INCREMENT,
  `levelName` varchar(45) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `module` varchar(45) DEFAULT NULL,
  `view` varchar(45) DEFAULT NULL,
  `next` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`levelId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Gegevens worden uitgevoerd voor tabel `erasmuslevel`
--

INSERT INTO `erasmuslevel` (`levelId`, `levelName`, `deadline`, `module`, `view`, `next`) VALUES
(6, 'Precandidate', '2011-05-25', 'precandidate', 'precandidate', 'Student Application and Learning Agreement'),
(8, 'Student Application and Learning Agreement', '2011-06-10', 'lagreeform', 'applicform', 'Offical Erasmus Contract'),
(10, 'Accomodation Registration Form', '2011-06-22', 'acom_reg', 'acom_reg', 'Certificate Of Arrival'),
(11, 'Certificate Of Arrival', '2011-06-30', 'abroadstay', 'certarrival', 'abroadstay'),
(13, 'Change of Learning Agreement', NULL, 'learnagr_ch', 'learnagrch', 'abroadstay'),
(15, 'Extend Mobility Period', NULL, 'extend', 'extend', 'abroadstay'),
(16, 'Certificate Of Departure', NULL, 'abroad_stay', 'select', 'Evaluation Questionaire'),
(17, 'Evaluation Questionaire', NULL, 'teardown_finish', 'evaluation', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `erasmusstudent`
--

DROP TABLE IF EXISTS `erasmusstudent`;
CREATE TABLE IF NOT EXISTS `erasmusstudent` (
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
  `studentId` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`studentId`),
  UNIQUE KEY `users_email_UNIQUE` (`users_email`),
  KEY `fk_ErasmusInfoPerStudent_Institutions_has_Study1` (`educationPerInstId`),
  KEY `fk_erasmusstudent_users1` (`users_email`),
  KEY `fk_erasmusstudent_users2` (`hostCoordinatorId`),
  KEY `fk_erasmusstudent_users3` (`homeCoordinatorId`),
  KEY `fk_erasmusstudent_institutions1` (`homeInstitutionId`),
  KEY `fk_erasmusstudent_institutions2` (`hostInstitutionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Gegevens worden uitgevoerd voor tabel `erasmusstudent`
--

INSERT INTO `erasmusstudent` (`users_email`, `startDate`, `endDate`, `educationPerInstId`, `statusOfErasmus`, `traineeOrStudy`, `uploadedWhat`, `ectsCredits`, `mothertongue`, `beenAbroad`, `action`, `hostCoordinatorId`, `homeCoordinatorId`, `homeInstitutionId`, `hostInstitutionId`, `studentId`) VALUES
('nathanva89@hotmail.com', '2011-06-23', '2011-06-25', 9, 'Student Application and Learning Agreement', '1', 'Transcript.pdf,v0vPGKn82HaAD9ebimdnhPsoGLc1Ehbu.pdf,cr7wf0abcjEWeX34s63wRGabvuT22HVi.pdf', 5, 'Dutch', 'Yes', 22, 'luk.schoofs@kahosl.be', 'nathan.vanassche@kahosl.be', 'info@kahosl.be', 'info@kahosl.be', 5),
('stephane.polet@kahosl.be', '2011-06-23', '2011-06-25', 9, 'Student Application and Learning Agreement', '1', 'Transcript.pdf,,', 15, 'Dutch', 'Yes', 11, 'luk.schoofs@kahosl.be', 'nathan.vanassche@kahosl.be', 'info@kahosl.be', 'info@kaalst.be', 6);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `forms`
--

DROP TABLE IF EXISTS `forms`;
CREATE TABLE IF NOT EXISTS `forms` (
  `formId` varchar(32) NOT NULL,
  `type` varchar(45) NOT NULL,
  `date` date NOT NULL,
  `content` text NOT NULL,
  `erasmusLevelId` int(11) NOT NULL,
  `studentId` varchar(100) NOT NULL,
  `action` int(11) NOT NULL DEFAULT '2',
  `motivationHome` text,
  `motivationHost` text,
  PRIMARY KEY (`formId`),
  KEY `fk_forms_erasmusLevel1` (`erasmusLevelId`),
  KEY `fk_forms_erasmusstudent1` (`studentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `forms`
--

INSERT INTO `forms` (`formId`, `type`, `date`, `content`, `erasmusLevelId`, `studentId`, `action`, `motivationHome`, `motivationHost`) VALUES
('CzbULxEWJCa4qPTIVbNnLxW6yhyoI4W5', 'Learning Agreement', '2011-06-22', '{"courseCount":"-1","code0":"ENG","title0":"English","ects0":"3","signDate":"2011-06-23"}', 8, 'nathanva89@hotmail.com', 2, NULL, NULL),
('JKfuChId0I9mn3PxIKe8efPyACHnx4rD', 'Student Application Form', '2011-06-22', '{"acaYear":"2010-2011","study":"Electronica","sendInstName":"KAHO Sint-Lieven","sendInstAddress":"Gebroeders Desmetstraat - 9000 - Ghent - Belgium","sendDepCoorName":"Van Assche Nathan","sendDepCoorTel":"092524678","sendDepCoorMail":"nathan.vanassche@kahosl.be","sendInstCoorName":"Lopez Daniel","sendInstCoorTel":"34567890","sendInstCoorMail":"office@kahosl.be","fName":"Nathan","faName":"Van Assche","dateBirth":"1989-01-24","sex":"Male","nation":"Belgium","birthPlace":"Ghent","cAddress":"Grote Elsdries 9 - 9090 Melle","daateValid":"2011-07-05","cTel":"092527865","pAddress":"","pTel":"","mail":"nathanva89@hotmail.com","recInstitut":"KAHO Sint-Lieven","coountry":"Belgium","daateFrom":"2011-06-23","daateUntill":"2011-06-25","duration":"5","ectsPoints":"5","motivation":"Just wanted to go on Erasmus","motherTongue":"Dutch","instrLanguage":"Dutch","language0":"Frencht","studyThis0":"0","knowledgeThis0":"0","extraPrep0":"0","type0":"ICT","firm0":"Job","date0":"2011","country0":"Belgium","diplome":"ICT","yEducation":"3","whichInst":"KAHO","accepted":"1","coordinator":"Accepted","hostDepCoor":"luk.schoofs@kahosl.be","signDepSignDate":"2011-06-23","signInstSignDate":"2011-06-23"}', 8, 'nathanva89@hotmail.com', 1, NULL, 'Accepted'),
('Np4VEjE9cWzMtQDk7vnI5My04h0SDgO4', 'Learning Agreement', '2011-06-22', '{"courseCount":"","code0":"ENG","title0":"English","ects0":"3","signDate":"","acceptedHome":"1","signDepSignDateSend":"","signInstSignDateSend":"","acceptedHost":"1","signDepSignDateRec":"","signInstSignDateRec":"","coordinator":"Come on!"}', 8, 'stephane.polet@kahosl.be', 1, 'Come on!', 'Approved'),
('O6IXeSWeC27TNjTpPCeoqo2Wq92djDJm', 'Precandidate', '2011-06-22', '{"familyName":"Polet","firstName":"Stephane","email":"stephane.polet@kahosl.be","instName":"KAHO Sint-Lieven","streetNr":"Veldstraat","tel":"09258741","mobilePhone":"047896541","study":"ICT","choice1":"Portugal","choice2":"Spain","choice3":"Ireland","traineeOrStudy":"Study","cribb":"Yes","cribRent":"Yes","scolarship":"Yes","motivation":"Erasmus"}', 6, 'stephane.polet@kahosl.be', 1, 'Bob', NULL),
('TqvFUXnWWAHuUDu2dLMVpI0suJw4akrO', 'Student Application Form', '2011-06-22', '{"acaYear":"2010-2011","study":"Electronica","sendInstName":"KAHO Sint-Lieven","sendInstAddress":"Gebroeders Desmetstraat - 9000 - Ghent - Belgium","sendDepCoorName":"Van Assche Nathan","sendDepCoorTel":"092524678","sendDepCoorMail":"nathan.vanassche@kahosl.be","sendInstCoorName":"Lopez Daniel","sendInstCoorTel":"34567890","sendInstCoorMail":"office@kahosl.be","fName":"Stephane","faName":"Polet","dateBirth":"1989-01-24","sex":"Male","nation":"Belgium","birthPlace":"Ghent","cAddress":"Veldstraat - 9000 Ghent","daateValid":"2011-07-24","cTel":"09258741","pAddress":"","pTel":"","mail":"stephane.polet@kahosl.be","recInstitut":"KAHO Aalst","coountry":"Belgium","daateFrom":"2011-06-23","daateUntill":"2011-06-25","duration":"6","ectsPoints":"15","motivation":"Erasmus","motherTongue":"Dutch","instrLanguage":"Dutch","language0":"French","studyThis0":"0","knowledgeThis0":"0","extraPrep0":"0","type0":"ICT","firm0":"Job","date0":"2011","country0":"Belgium","diplome":"ICT","yEducation":"3","whichInst":"KAHO","accepted":"1","coordinator":"Bob","hostDepCoor":"luk.schoofs@kahosl.be","signDepSignDate":"2011-06-23","signInstSignDate":"2011-06-23"}', 8, 'stephane.polet@kahosl.be', 1, NULL, 'Bob'),
('zVmTi3GiduytVmoEghOz6N8Lg4ExpFQD', 'Precandidate', '2011-06-22', '{"familyName":"Van Assche","firstName":"Nathan","email":"nathanva89@hotmail.com","instName":"KAHO Aalst","streetNr":"Grote Elsdries 9","tel":"092527865","mobilePhone":"0473569854","study":"ICT","choice1":"Germany","choice2":"Spain","choice3":"Ireland","traineeOrStudy":"Study","cribb":"Yes","cribRent":"Yes","scolarship":"Yes","motivation":"Just want to go on Erasmus"}', 6, 'nathanva89@hotmail.com', 1, 'This student can go to Germany', NULL);

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
(12, NULL, NULL, NULL, 'stephane.polet@kahosl.be');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `homecoursestoerasmus`
--

DROP TABLE IF EXISTS `homecoursestoerasmus`;
CREATE TABLE IF NOT EXISTS `homecoursestoerasmus` (
  `courseId` int(11) NOT NULL,
  `erasmusId` int(11) NOT NULL,
  `isRequested` tinyint(1) NOT NULL DEFAULT '0',
  `homeanswer` int(11) DEFAULT NULL,
  `hostanswer` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `homecoursestoerasmus`
--

INSERT INTO `homecoursestoerasmus` (`courseId`, `erasmusId`, `isRequested`, `homeanswer`, `hostanswer`) VALUES
(6, 0, 0, NULL, NULL),
(8, 0, 0, NULL, NULL);

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
  `digital` tinyint(1) DEFAULT NULL,
  `iBan` varchar(25) DEFAULT NULL,
  `bic` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`instId`),
  UNIQUE KEY `instEmail_UNIQUE` (`instEmail`),
  KEY `fk_Institutions_Country1` (`instCountry`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Gegevens worden uitgevoerd voor tabel `institutions`
--

INSERT INTO `institutions` (`instId`, `instEmail`, `instName`, `instStreetNr`, `instCity`, `instPostalCode`, `instCountry`, `instTel`, `instFax`, `instDescription`, `instWebsite`, `traineeOrStudy`, `url`, `scale`, `digital`, `iBan`, `bic`) VALUES
(6, 'info@kahosl.be', 'KAHO Sint-Lieven', 'Gebroeders Desmetstraat', 'Ghent', '9000', 'BEL', '2243223', '23344323', 'Katholieke Hogeschool Sint-Lieven', 'www.kahosl.be', 1, 'stephanepolet.ikdoeict.be/MUTW', 20, 1, 'BE01-5578256-654525', 'BACBBEBB'),
(7, 'info@kaalst.be', 'KAHO Aalst', 'Aalstrstraat 27', 'Aalst', '9000', 'BEL', '2243223', '23344323', 'Katholieke Hogeschool Aalst', 'www.kahosl.be', 1, 'nathanvanassche.ikdoeict.be/MUTW', 20, 1, '747-65846514-08', 'BACBBEBB'),
(8, 'info@kul.pt', 'Isep Portugal', 'leuvense steenweg 99', 'Leuven', '3000', 'ESP', '2243223', '23344323', 'Portugal', 'www.kul.pt', 1, 'unknown', 20, 1, '0000000000', '0000000000');

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
  `institutionId` varchar(45) DEFAULT NULL,
  `country` char(3) NOT NULL,
  PRIMARY KEY (`ownerId`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_owner_Country1` (`country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `owner`
--

INSERT INTO `owner` (`ownerId`, `familyName`, `firstName`, `tel`, `email`, `streetNr`, `city`, `postalCode`, `mobilePhone`, `institutionId`, `country`) VALUES
(1, 'Van Assche', 'Stephane', '222222', 'nathan.vanassche@kahosl.be', 'Grote Elsdries 9', 'Ronse', '9000', '222222', 'info@kahosl.be', 'BEL'),
(2, 'Van Assche', 'Nathan', '0925893', 'nathanva89@hotmail.com', 'Grote Elsdries 9', 'Melle', '9090', '047656565', 'info@kaalst.be', 'BEL'),
(3, 'Polet', 'Stephane', '097892536', 'stephane.polet@kahosl.be', 'Korenmarkt 1', 'Ghent', '9000', '0473569741', 'info@kaalst.be', 'BEL'),
(4, 'Lopez', 'Daniel', '094546456', 'daniel.lopez@gmail.com', 'Rua De Boavista', 'Porto', '8200', '04756498542', 'info@kaalst.be', 'POR');

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
  `institutionId` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`residenceId`),
  KEY `fk_residence_Country1` (`country`),
  KEY `fk_residence_owner1` (`ownerId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `residence`
--

INSERT INTO `residence` (`residenceId`, `price`, `streetNr`, `city`, `postalCode`, `country`, `kitchen`, `bathroom`, `water`, `internet`, `beds`, `description`, `available`, `television`, `ownerId`, `elektricity`, `institutionId`) VALUES
(2, 400, 'Veldstraat', 'Gent', '9000', 'BEL', 1, 1, 1, 1, 4, 'Ghent', 1, 1, 'nathanva89@hotmail.com', 1, 'info@kaalst.be'),
(3, 400, 'Veldstraat 1', 'Ghent', '9000', 'BEL', 1, 1, 1, 1, 4, 'Veldstraat Ghent', 1, 1, 'nathanva89@hotmail.com', 1, 'info@kaalst.be'),
(4, 475, 'Korenmarkt 1', 'Ghent', '9000', 'BEL', 1, 1, 1, 1, 4, 'Korenmarkt', 1, 1, 'stephane.polet@kahosl.be', 1, 'info@kaalst.be'),
(5, 800, 'Rua De Boavista', 'Porto', '8200', 'POR', 1, 1, 1, 1, 6, 'Porto', 1, 1, 'daniel.lopez@gmail.com', 1, 'info@kaalst.be');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=190 ;

--
-- Gegevens worden uitgevoerd voor tabel `studentsevents`
--

INSERT INTO `studentsevents` (`eventId`, `reader`, `timestamp`, `motivation`, `readIt`, `action`, `erasmusLevelId`, `eventDescrip`, `studentId`) VALUES
(162, 'Student', '2011-06-22', '', 0, 2, 6, 'Filled in Precandidate.', 'nathanva89@hotmail.com'),
(163, 'Student', '2011-06-22', 'This student can go to Germany', 0, 1, 6, 'Precandidate approved', 'nathanva89@hotmail.com'),
(164, 'Student', '2011-06-22', '', 0, 30, 8, 'Filled in Student Application Form', 'nathanva89@hotmail.com'),
(165, 'Student', '2011-06-22', '', 0, 22, 8, 'Filled in Learning Agreement', 'nathanva89@hotmail.com'),
(166, 'Student', '2011-06-22', '', 0, 2, 6, 'Filled in Precandidate.', 'stephane.polet@kahosl.be'),
(167, 'Student', '2011-06-22', 'Bob', 0, 1, 6, 'Precandidate approved', 'stephane.polet@kahosl.be'),
(168, 'Student', '2011-06-22', '', 0, 30, 8, 'Filled in Student Application Form', 'stephane.polet@kahosl.be'),
(169, 'Student', '2011-06-22', '', 0, 22, 8, 'Filled in Learning Agreement', 'stephane.polet@kahosl.be'),
(187, 'Student', '2011-06-22', '', 0, 99, 8, 'Student Application sent to host institution.', 'stephane.polet@kahosl.be'),
(189, 'Student', '2011-06-22', 'Come on!', 0, 12, 8, 'Learning Agreement approved by home institute and sent to host.', 'stephane.polet@kahosl.be');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
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
  `origin` int(11) NOT NULL DEFAULT '0',
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_Users_Country1` (`country`),
  KEY `fk_users_institutions1` (`institutionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Gegevens worden uitgevoerd voor tabel `users`
--

INSERT INTO `users` (`email`, `familyName`, `firstName`, `password`, `birthDate`, `birthPlace`, `sex`, `tel`, `mobilePhone`, `fax`, `streetNr`, `city`, `postalCode`, `country`, `userLevel`, `isValidUser`, `verificationCode`, `institutionId`, `origin`, `userId`) VALUES
('office@kahosl.be', 'Lopez', 'Daniel', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-12-12', 'Ghent', 1, '34567890', '0478765465', NULL, 'Prinses Clementinalaan 140', 'Ronse', '9000', 'BEL', 'International Relations Office Staff', 2, 'GgcqGHFWcUJH79aF6N5W0FcxUhqLR8UX', 'info@kahosl.be', 0, 1),
('admin', 'admin', 'admin', '4c3b6c7517e9f780744f6582f2d36fb6', '2011-05-14', 'admin', 1, '222222', '222222', '12333333', 'Grote Elsdries 9', 'ghent', '9000', 'BEL', 'International Relations Office Staff', 2, 'sdsdf', 'info@kahosl.be', 0, 2),
('nathan.vanassche@kahosl.be', 'Van Assche', 'Nathan', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-12-12', 'Ghent', 0, '092524678', '04787865465', NULL, 'Grote Elsdries 9', 'Ronse', '9000', 'BEL', 'Erasmus Coordinator', 2, 'r7JhdeHb04jzvry5dayPS6QcTOaAExsi', 'info@kahosl.be', 0, 3),
('hostoffice@erasmus.com', 'Polet', 'Stephane', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-12-12', 'Ghent', 1, '09268546', '04746546', '12333333', 'Grote Elsdries 9', 'ghent', '9000', 'ESP', 'International Relations Office Staff', 2, 'GEPPXhzfCKe5BLWeaEuzkN1q957fXKKb', 'info@kaalst.be', 0, 4),
('nathanva89@hotmail.com', 'Van Assche', 'Nathan', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-01-24', 'Ghent', 1, '092527865', '0473569854', NULL, 'Grote Elsdries 9', 'Melle', '9090', 'BEL', 'Student', 2, 'cQbFj9eatvQQMgfKPSRRAs7uctorfsAo', 'info@kaalst.be', 0, 9),
('stephane.polet@kahosl.be', 'Polet', 'Stephane', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-01-24', 'Ghent', 1, '09258741', '047896541', NULL, 'Veldstraat', 'Ghent', '9000', 'BEL', 'Student', 2, 'V9B9OnJuPJqkTjXB32t1oBSjxD67eIQ4', 'info@kahosl.be', 0, 10),
('luk.schoofs@kahosl.be', 'Schoofs', 'Luc', '4c3b6c7517e9f780744f6582f2d36fb6', '2011-06-25', 'Ghent', 1, '091478596', '0478591464', NULL, 'Gebroeders Desmetstraat', 'Gent', '9000', 'BEL', 'Erasmus Coordinator', 2, 'sdfsdfsdf', 'info@kaalst.be', 1, 11);

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
