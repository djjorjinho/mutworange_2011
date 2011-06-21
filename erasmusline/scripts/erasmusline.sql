-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 14 Jun 2011 om 16:24
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
/* use create_user.sql with root user please. only needs to be run once every new mysql installation*/
/* GRANT ALL PRIVILEGES  ON erasmusline.* TO 'erasmusline'@'%' IDENTIFIED BY 'orange' WITH GRANT OPTION;*/

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

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
  `deadline` date DEFAULT NULL,
  `module` varchar(45) DEFAULT NULL,
  `view` varchar(45) DEFAULT NULL,
  `next` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`levelId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `erasmuslevel`
--

INSERT INTO `erasmuslevel` (`levelId`, `levelName`, `deadline`, `module`, `view`, `next`) VALUES
(6, 'Precandidate', '2011-05-25', 'precandidate', 'precandidate', 'Student Application and Learning Agreement'),
(8, 'Student Application and Learning Agreement', '2011-06-10', 'lagreeform', 'applicform', 'Offical Erasmus Contract'),
(9, 'Offical Erasmus Contract', '2011-06-20', 'preleave', 'contract', 'Accomodation Registration Form'),
(10, 'Accomodation Registration Form', '2011-06-22', 'accomodation', 'accomodation', 'Certificate Of Arrival'),
(11, 'Certificate Of Arrival', '2011-06-30', 'abroadstay', 'certarrival', 'Change to Learning Agreement'),
(12, 'Redo Student Application Form', NULL, 'lagreeform', 'applicform', NULL),
(13, 'Redo Learning Agreement', NULL, 'lagreeform', 'lagreement', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `erasmusstudent`
--

INSERT INTO `erasmusstudent` (`users_email`, `startDate`, `endDate`, `educationPerInstId`, `statusOfErasmus`, `traineeOrStudy`, `uploadedWhat`, `ectsCredits`, `mothertongue`, `beenAbroad`, `action`, `hostCoordinatorId`, `homeCoordinatorId`, `homeInstitutionId`, `hostInstitutionId`) VALUES
('stephane.polet@kahosl.be', '2011-06-17', '2011-06-18', 8, 'Student Application and Learning Agreement', '1', '1)swethchl.pdf,,', 5, 'sdfsq', 'Yes', 22, NULL, 'nathan.vanassche@kahosl.be', 'info@kahosl.be', 'info@kaalst.be'),
('sportlife52@hotmail.com', '2011-06-12', '2011-06-14', 8, 'Student Application and Learning Agreement', '1', ',,', 2, 'sdfsdf', 'No', 11, NULL, 'nathan.vanassche@kahosl.be', 'info@kahosl.be', 'info@kaalst.be'),
('nathanva89@hotmail.com', '2011-06-14', '2011-06-16', 8, 'Student Application and Learning Agreement', '1', ',Integratie Oefening.pdf,', 5, 'sdfsdf', 'Yes', 11, NULL, 'nathan.vanassche@kahosl.be', 'info@kahosl.be', 'info@kaalst.be'),
('testing@gmail.com', '2011-06-16', '2011-06-29', 8, 'Student Application and Learning Agreement', '1', ',,', 3, 'qsdf', 'Yes', 22, NULL, 'nathan.vanassche@kahosl.be', 'info@kahosl.be', 'info@kaalst.be');

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
('58', 'Precandidate', '2011-06-08', '{"familyName":"Polet","firstName":"Stephane","email":"stephane.polet@kahosl.be","instName":"KAHO Sint-Lieven","streetNr":"Grote Elsdries 9","tel":"222222","mobilePhone":"222222","study":"Elektronics","choice1":"Spain","choice2":"Ireland","choice3":"Germany","traineeOrStudy":"Study","cribb":"Yes","cribRent":"Yes","scolarship":"Yes","motivation":"tof tof !!!"}', 6, 'stephane.polet@kahosl.be', 1, NULL, NULL),
('64', 'Student Application Form', '2011-06-08', '{"acaYear":"2010-2011","study":"Elektronics","sendInstName":"KAHO Sint-Lieven","sendInstAddress":"Gebroeders Desmetstraat - 9000 - Ghent - Belgium","sendDepCoorName":"Ann Mary","sendDepCoorTel":"222222","sendDepCoorMail":"nathan.vanassche@kahosl.be","sendInstCoorName":"plim plom","sendInstCoorTel":"34567890","sendInstCoorMail":"office@blabla.be","fName":"Stephane","faName":"Polet","dateBirth":"1989-12-12","sex":"Male","nation":"Belgium","birthPlace":"Ghent","cAddress":"Grote Elsdries 9 - 9000 Ronse","daateValid":"2012-11-12","cTel":"222222","pAddress":"sdfs","pTel":"234543","mail":"stephane.polet@kahosl.be","recInstitut":"KAHO Aalst","coountry":"Belgium","daateFrom":"2011-06-17","daateUntill":"2011-06-18","duration":"2","ectsPoints":"5","motivation":"qsdfsd","motherTongue":"sdfsq","instrLanguage":"dsfqsdf","languageCount":"0","language0":"sqdf","studyThis0":"0","knowledgeThis0":"0","extraPrep0":"0","workCount":"0","type0":"sd","firm0":"df","date0":"sdf","country0":"df","diplome":"sdfqsd","yEducation":"2","abroad":"Yes","whichInst":"sdqfqsdf"}', 8, 'stephane.polet@kahosl.be', 2, NULL, NULL),
('67', 'Learning Agreement', '2011-06-08', '{"courseCount":"1","code0":"ELEK","title0":"Elektronica","ects0":"2","code1":"ICT","title1":"ICT","ects1":"3","signDate":"2011-06-23"}', 8, 'stephane.polet@kahosl.be', 2, NULL, NULL),
('cr7wf0abcjEWeX34s63wRGabvuT22HVi', 'Learning Agreement', '2011-06-13', '{"courseCount":"","code0":"ICT","title0":"ICT","ects0":"5","signDate":"2011-06-14"}', 8, 'nathanva89@hotmail.com', 1, 'Because it''s so cool!', 'Approved!'),
('DOIOMp3j3EfUUDw3h3LBeTKDhcmb6np0', 'Student Application Form', '2011-06-11', '{"acaYear":"2010-2011","study":"Elektronics","sendInstName":"KAHO Sint-Lieven","sendInstAddress":"Gebroeders Desmetstraat - 9000 - Ghent - Belgium","sendDepCoorName":"Ann Mary","sendDepCoorTel":"222222","sendDepCoorMail":"nathan.vanassche@kahosl.be","sendInstCoorName":"plim plom","sendInstCoorTel":"34567890","sendInstCoorMail":"office@blabla.be","fName":"Anjo","faName":"Polanski","dateBirth":"1989-12-12","sex":"Male","nation":"Belgium","birthPlace":"Ghent","cAddress":"Grote Elsdries 9 - 9000 Ronse","daateValid":"2012-11-12","cTel":"34567890","pAddress":"","pTel":"","mail":"sportlife52@hotmail.com","recInstitut":"KAHO Aalst","coountry":"Belgium","daateFrom":"2011-06-12","daateUntill":"2011-06-14","duration":"2","ectsPoints":"2","motivation":"because it''s awesome!","motherTongue":"sdfsdf","instrLanguage":"dsfqsdf","languageCount":"","language0":"sdfsd","studyThis0":"0","knowledgeThis0":"0","extraPrep0":"0","workCount":"","type0":"sd","firm0":"sdf","date0":"sdf","country0":"sdf","diplome":"dsf","yEducation":"2","abroad":"No","whichInst":"sdfsdf"}', 8, 'sportlife52@hotmail.com', 1, NULL, 'Jaddadde, what an application!!!'),
('ELMbRqNqDmI8Hx2rKtackk4ML2jnm9sX', 'Precandidate', '2011-06-11', '{"familyName":"Polanski","firstName":"Anjo","email":"sportlife52@hotmail.com","instName":"KAHO Sint-Lieven","streetNr":"Grote Elsdries 9","tel":"34567890","mobilePhone":"34567890","study":"Elektronics","choice1":"Belgium","choice2":"Germany","choice3":"Spain","traineeOrStudy":"Study","cribb":"Yes","cribRent":"Yes","scolarship":"No","motivation":"flipfop"}', 6, 'sportlife52@hotmail.com', 1, 'Because you''re so awesome!!!', NULL),
('J6DykQuOUnNGQ94yRU0zA6xUifb25QIy', 'Student Application Form', '2011-06-12', '{"acaYear":"2010-2011","study":"Elektronics","sendInstName":"KAHO Sint-Lieven","sendInstAddress":"Gebroeders Desmetstraat - 9000 - Ghent - Belgium","sendDepCoorName":"Ann Mary","sendDepCoorTel":"222222","sendDepCoorMail":"nathan.vanassche@kahosl.be","sendInstCoorName":"plim plom","sendInstCoorTel":"34567890","sendInstCoorMail":"office@blabla.be","fName":"Aniona","faName":"Balcooni","dateBirth":"1989-12-12","sex":"Male","nation":"Belgium","birthPlace":"Ghent","cAddress":"Grote Elsdries 9 - 9000 Ronse","daateValid":"2012-11-12","cTel":"222222","pAddress":"","pTel":"","mail":"nathanva89@hotmail.com","recInstitut":"KAHO Aalst","coountry":"Belgium","daateFrom":"2011-06-13","daateUntill":"2011-06-14","duration":"2","ectsPoints":"5","motivation":"I would love to go on Erasmus!","motherTongue":"sdfsdf","instrLanguage":"dsfqsdf","languageCount":"0","language0":"sdfsd","studyThis0":"1","knowledgeThis0":"1","extraPrep0":"0","workCount":"","type0":"kjkm","firm0":"kjhk","date0":"kjhlj","country0":"kjhk","diplome":"dsf","yEducation":"2","abroad":"No","whichInst":"sdfsdf"}', 8, 'nathanva89@hotmail.com', 0, NULL, 'Sorry fellah, not good enough!'),
('R9KoKheordt7ASnQB4CvvJtDErR7KATH', 'Precandidate', '2011-06-14', '{"familyName":"Testing","firstName":"Test","email":"testing@gmail.com","instName":"KAHO Sint-Lieven","streetNr":"DFGHJKL","tel":"3456890","mobilePhone":"34567890","study":"Elektronics","choice1":"Germany","choice2":"Spain","choice3":"Ireland","traineeOrStudy":"Study","cribb":"No","cribRent":"No","scolarship":"No","motivation":"testing"}', 6, 'testing@gmail.com', 1, 'qsdfqsdf', NULL),
('v0vPGKn82HaAD9ebimdnhPsoGLc1Ehbu', 'Student Application Form', '2011-06-13', '{"acaYear":"2010-2011","study":"Elektronics","sendInstName":"KAHO Sint-Lieven","sendInstAddress":"Gebroeders Desmetstraat - 9000 - Ghent - Belgium","sendDepCoorName":"Ann Mary","sendDepCoorTel":"222222","sendDepCoorMail":"nathan.vanassche@kahosl.be","sendInstCoorName":"plim plom","sendInstCoorTel":"34567890","sendInstCoorMail":"office@blabla.be","fName":"Aniona","faName":"Balcooni","dateBirth":"1989-12-12","sex":"Male","nation":"Belgium","birthPlace":"Ghent","cAddress":"Grote Elsdries 9 - 9000 Ronse","daateValid":"2012-11-12","cTel":"222222","pAddress":"","pTel":"","mail":"nathanva89@hotmail.com","recInstitut":"KAHO Aalst","coountry":"Belgium","daateFrom":"2011-06-14","daateUntill":"2011-06-16","duration":"2","ectsPoints":"5","motivation":"The same as before.","motherTongue":"sdfsdf","instrLanguage":"dsfqsdf","languageCount":"","language0":"lkjlklk","studyThis0":"0","knowledgeThis0":"0","extraPrep0":"1","workCount":"","type0":"xfgvf","firm0":"ljkjk","date0":"oljk","country0":"okl","diplome":"dsf","yEducation":"2","abroad":"Yes","whichInst":"sdfsdf"}', 8, 'nathanva89@hotmail.com', 1, NULL, 'because you tried again :)'),
('VtfqS1uXM2f4xcQaRU20EowAwrwNtjVT', 'Precandidate', '2011-06-12', '{"familyName":"Balcooni","firstName":"Aniona","email":"nathanva89@hotmail.com","instName":"KAHO Sint-Lieven","streetNr":"Grote Elsdries 9","tel":"222222","mobilePhone":"222222","study":"Elektronics","choice1":"Belgium","choice2":"Germany","choice3":"Spain","traineeOrStudy":"Internship","cribb":"Yes","cribRent":"Yes","scolarship":"No","motivation":"Because I think it''s fun!"}', 6, 'nathanva89@hotmail.com', 1, 'We approve!', NULL),
('w2rMwfMPNXNtjTSGUVjpmPK2QS1waQje', 'Learning Agreement', '2011-06-14', '{"courseCount":"","code0":"ICT","title0":"ICT","ects0":"3","signDate":"2011-06-29"}', 8, 'testing@gmail.com', 2, NULL, NULL),
('xPTwJpuPI2Vz5ci49M63vd20Fb9HaB1M', 'Learning Agreement', '2011-06-12', '{"courseCount":"","code0":"ICT","title0":"ICT","ects0":"5","signDate":"2011-06-13"}', 8, 'nathanva89@hotmail.com', 0, 'not good enough!', NULL),
('XQLscMCofj5HkGosPRDk7t9rNNhntLe4', 'Student Application Form', '2011-06-14', '{"acaYear":"2010-2011","study":"Elektronics","sendInstName":"KAHO Sint-Lieven","sendInstAddress":"Gebroeders Desmetstraat - 9000 - Ghent - Belgium","sendDepCoorName":"Ann Mary","sendDepCoorTel":"222222","sendDepCoorMail":"nathan.vanassche@kahosl.be","sendInstCoorName":"plim plom","sendInstCoorTel":"34567890","sendInstCoorMail":"office@blabla.be","fName":"Test","faName":"Testing","dateBirth":"1989-01-24","sex":"Male","nation":"Belgium","birthPlace":"Ghent","cAddress":"DFGHJKL - 9090 DFGHJKL","daateValid":"2012-11-12","cTel":"3456890","pAddress":"","pTel":"456789","mail":"testing@gmail.com","recInstitut":"KAHO Aalst","coountry":"Belgium","daateFrom":"2011-06-16","daateUntill":"2011-06-29","duration":"3","ectsPoints":"3","motivation":"qsdfsqdf","motherTongue":"qsdf","instrLanguage":"sqdf","languageCount":"","language0":"qsdf","studyThis0":"0","knowledgeThis0":"0","extraPrep0":"0","workCount":"","type0":"sdf","firm0":"dfs","date0":"fsdf","country0":"sdf","diplome":"dfsd","yEducation":"3","abroad":"Yes","whichInst":"kaho"}', 8, 'testing@gmail.com', 2, NULL, NULL),
('yzGUUuaa51Hd1A32tfpeAiD3HR06jH1n', 'Learning Agreement', '2011-06-11', '{"courseCount":"","code0":"ELEK","title0":"Elektronica","ects0":"2","signDate":"2011-06-12"}', 8, 'sportlife52@hotmail.com', 1, 'We think it''s cool!', 'jeeha, accepted');

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
(5, NULL, NULL, NULL, 'nathanva89@hotmail.com'),
(5, NULL, NULL, NULL, 'stephane.polet@kahosl.be'),
(5, NULL, NULL, NULL, 'testing@gmail.com'),
(6, NULL, NULL, NULL, 'sportlife52@hotmail.com'),
(6, NULL, NULL, NULL, 'stephane.polet@kahosl.be');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `homecoursestoerasmus`
--

DROP TABLE IF EXISTS `homecoursestoerasmus`;
CREATE TABLE IF NOT EXISTS `homecoursestoerasmus` (
  `courseId` int(11) NOT NULL,
  `erasmusId` int(11) NOT NULL,
  `isRequested` tinyint(1) NOT NULL,
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
(6, 'info@kahosl.be', 'KAHO Sint-Lieven', 'Gebroeders Desmetstraat', 'Ghent', '9000', 'BEL', '2243223', '23344323', 'sdfs', 'www.kahosl.be', 1, 'stephanepolet.ikdoeict.be/MUTW', 20, NULL, NULL, NULL),
(7, 'info@kaalst.be', 'KAHO Aalst', 'Aalstrstraat 27', 'Aalst', '9000', 'BEL', '2243223', '23344323', 'sdfsdf', 'www.kahosl.be', 1, 'stephanepolet.ikdoeict.be/MUTW', 20, NULL, NULL, NULL),
(8, 'info@kul.pt', 'Katholieke Universiteit Leuven', 'leuvense steenweg 99', 'Leuven', '3000', 'ESP', '2243223', '23344323', 'An aweomse school!', 'www.kul.pt', 1, 'unknown', 20, 1, '0000000000', '0000000000');

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
  `institutionId` varchar(45) DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=162 ;

--
-- Gegevens worden uitgevoerd voor tabel `studentsevents`
--

INSERT INTO `studentsevents` (`eventId`, `reader`, `timestamp`, `motivation`, `readIt`, `action`, `erasmusLevelId`, `eventDescrip`, `studentId`) VALUES
(87, 'Student', '2011-06-08', '', 1, 2, 6, 'Precandidate ingevuld.', 'stephane.polet@kahosl.be'),
(88, 'Student', '2011-06-08', 'approved my fiend!', 1, 1, 6, 'Precandidate approved', 'stephane.polet@kahosl.be'),
(94, 'Student', '2011-06-08', '', 1, 30, 8, 'Filled in Student Application Form', 'stephane.polet@kahosl.be'),
(97, 'Student', '2011-06-08', '', 1, 22, 8, 'Filled in Learning Agreement', 'stephane.polet@kahosl.be'),
(120, 'Student', '2011-06-11', '', 1, 2, 6, 'Precandidate ingevuld.', 'sportlife52@hotmail.com'),
(121, 'Student', '2011-06-11', 'approved my love', 1, 1, 6, 'Precandidate approved', 'sportlife52@hotmail.com'),
(122, 'Student', '2011-06-11', '', 1, 30, 8, 'Filled in Student Application Form', 'sportlife52@hotmail.com'),
(123, 'Student', '2011-06-11', '', 1, 22, 8, 'Filled in Learning Agreement', 'sportlife52@hotmail.com'),
(125, 'Student', '2011-06-11', '', 1, 99, 8, 'Student Application sent to host institution.', 'sportlife52@hotmail.com'),
(147, 'Student', '2011-06-11', '', 1, 99, 8, 'Learning Agreement sent to host institution.', 'sportlife52@hotmail.com'),
(149, 'Student', '2011-06-12', '', 1, 2, 6, 'Precandidate ingevuld.', 'nathanva89@hotmail.com'),
(150, 'Student', '2011-06-12', 'We approve!', 1, 1, 6, 'Precandidate approved', 'nathanva89@hotmail.com'),
(151, 'Student', '2011-06-12', '', 1, 30, 8, 'Filled in Student Application Form', 'nathanva89@hotmail.com'),
(152, 'Student', '2011-06-12', '', 1, 22, 8, 'Filled in Learning Agreement', 'nathanva89@hotmail.com'),
(153, 'Student', '2011-06-12', 'not good enough!', 1, 20, 8, 'Learning Angreement is denied by home.', 'nathanva89@hotmail.com'),
(154, 'Student', '2011-06-12', '', 1, 99, 8, 'Student Application sent to host institution.', 'nathanva89@hotmail.com'),
(155, 'Student', '2011-06-13', '', 1, 22, 8, 'Filled in Learning Agreement', 'nathanva89@hotmail.com'),
(156, 'Student', '2011-06-13', 'Because it''s so cool!', 1, 22, 8, 'Learnign Agreement approved by home institute and sent to host.', 'nathanva89@hotmail.com'),
(157, 'Student', '2011-06-13', '', 1, 30, 8, 'Filled in Student Application Form', 'nathanva89@hotmail.com'),
(158, 'Student', '2011-06-14', '', 1, 2, 6, 'Filled in Precandidate.', 'testing@gmail.com'),
(159, 'Student', '2011-06-14', 'qsdfqsdf', 0, 1, 6, 'Precandidate approved', 'testing@gmail.com'),
(160, 'Student', '2011-06-14', '', 0, 30, 8, 'Filled in Student Application Form', 'testing@gmail.com'),
(161, 'Student', '2011-06-14', '', 0, 22, 8, 'Filled in Learning Agreement', 'testing@gmail.com');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Gegevens worden uitgevoerd voor tabel `users`
--

INSERT INTO `users` (`email`, `familyName`, `firstName`, `password`, `birthDate`, `birthPlace`, `sex`, `tel`, `mobilePhone`, `fax`, `streetNr`, `city`, `postalCode`, `country`, `userLevel`, `isValidUser`, `verificationCode`, `institutionId`, `origin`, `userId`) VALUES
('office@blabla.be', 'plim', 'plom', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-12-12', 'Ghent', 1, '34567890', '222222', NULL, 'Prinses Clementinalaan 140', 'Ronse', '9000', 'BEL', 'International Relations Office Staff', 2, 'GgcqGHFWcUJH79aF6N5W0FcxUhqLR8UX', 'info@kahosl.be', 0, 1),
('admin', 'admin', 'admin', '4c3b6c7517e9f780744f6582f2d36fb6', '2011-05-14', 'admin', 1, '222222', '222222', '12333333', 'Grote Elsdries 9', 'ghent', '9000', 'BEL', 'International Relations Office Staff', 2, 'sdsdf', 'info@kahosl.be', 0, 2),
('nathan.vanassche@kahosl.be', 'Ann', 'Mary', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-12-12', 'Ghent', 0, '222222', '222222', NULL, 'Grote Elsdries 9', 'Ronse', '9000', 'BEL', 'Erasmus Coordinator', 2, 'r7JhdeHb04jzvry5dayPS6QcTOaAExsi', 'info@kahosl.be', 0, 3),
('hostoffice@erasmus.com', 'nathan', 'POLET', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-12-12', 'Ghent', 1, '222222', '222222', '12333333', 'Grote Elsdries 9', 'ghent', '9000', 'ESP', 'International Relations Office Staff', 2, 'GEPPXhzfCKe5BLWeaEuzkN1q957fXKKb', 'info@kul.pt', 0, 4),
('stephane.polet@kahosl.be', 'Polet', 'Stephane', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-12-12', 'Ghent', 1, '222222', '222222', NULL, 'Grote Elsdries 9', 'Ronse', '9000', 'BEL', 'Student', 2, 'Gv5PpIx1zQcScL3b0njSza1IS5bahxc5', 'info@kahosl.be', 0, 5),
('sportlife52@hotmail.com', 'Polanski', 'Anjo', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-12-12', 'Ghent', 1, '34567890', '34567890', NULL, 'Grote Elsdries 9', 'Ronse', '9000', 'BEL', 'Student', 2, 's05sNkggXIgsSHjowDn9piShvHWTKArI', 'info@kahosl.be', 0, 6),
('nathanva89@hotmail.com', 'Balcooni', 'Aniona', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-12-12', 'Ghent', 1, '222222', '222222', NULL, 'plomstraat 28', 'Ronse', '9000', 'BEL', 'Student', 2, 'kiJpqnAeX32E2kxB3NsXTCpUmTkiLSvW', 'info@kahosl.be', 0, 7),
('testing@gmail.com', 'Testing', 'Test', '4c3b6c7517e9f780744f6582f2d36fb6', '1989-01-24', 'Ghent', 1, '3456890', '34567890', NULL, 'DFGHJKL', 'DFGHJKL', '9090', 'BEL', 'Student', 2, 'po5Po7juK68TKEVRKwPV14fKLqwRbw4v', 'info@kahosl.be', 0, 8);

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
