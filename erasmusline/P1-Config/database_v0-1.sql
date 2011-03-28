SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Tabellenstruktur für Tabelle `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `code` char(3) NOT NULL DEFAULT '',
  `name` char(52) NOT NULL DEFAULT '',
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `country`
--

INSERT INTO `country` (`code`, `name`) VALUES
('AT', 'Austria'),
('BE', 'Belgium'),
('BG', 'Bulgaria'),
('CZ', 'Czech Republic'),
('CY', 'Cyprus'),
('DK', 'Denmark'),
('EE', 'Estonia'),
('DE', 'Germany'),
('ES', 'Spain'),
('FI', 'Finland'),
('FR', 'France'),
('GR', 'Greece'),
('HU', 'Hungary'),
('IS', 'Iceland'),
('IE', 'Ireland'),
('IT', 'Italy'),
('LV', 'Latvia'),
('LI', 'Liechtenstein'),
('LT', 'Lithuania'),
('LU', 'Luxembourg'),
('MT', 'Malta'),
('NL', 'Netherlands'),
('NO', 'Norway'),
('PL', 'Poland'),
('PT', 'Portugal'),
('RO', 'Romania'),
('SK', 'Slovak Republic'),
('SI', 'Slovenia'),
('SE', 'Sweden'),
('UK', 'United Kingdom'),
('TR', 'Turkey'),
('XX', 'Other');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `courses`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `erasmusinfoperstudent`
--

DROP TABLE IF EXISTS `erasmusinfoperstudent`;
CREATE TABLE IF NOT EXISTS `erasmusinfoperstudent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idstudent` int(11) NOT NULL,
  `idhomecoordinator` int(11) NOT NULL,
  `idhostcoordinator` int(11) NOT NULL,
  `idhomeinstitution` int(11) NOT NULL,
  `idhostinstitution` int(11) NOT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `iderasmusinfoperstud` int(11) NOT NULL,
  `id_institution_has_study` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ErasmusInfo_Users2` (`idstudent`),
  KEY `fk_ErasmusInfo_Users1` (`idhomecoordinator`),
  KEY `fk_ErasmusInfo_Users3` (`idhostcoordinator`),
  KEY `fk_ErasmusInfo_Institutions1` (`idhomeinstitution`),
  KEY `fk_ErasmusInfo_Institutions2` (`idhostinstitution`),
  KEY `fk_ErasmusInfoPerStudent_ErasmusProgressPerStudent1` (`iderasmusinfoperstud`),
  KEY `fk_ErasmusInfoPerStudent_Institutions_has_Study1` (`id_institution_has_study`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `erasmusinfoperstudent`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `erasmuslevel`
--

DROP TABLE IF EXISTS `erasmuslevel`;
CREATE TABLE IF NOT EXISTS `erasmuslevel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `erasmuslevel`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `erasmusprogressperstudent`
--

DROP TABLE IF EXISTS `erasmusprogressperstudent`;
CREATE TABLE IF NOT EXISTS `erasmusprogressperstudent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_stage1` date DEFAULT NULL,
  `date_stage2` date DEFAULT NULL,
  `date_stage3` date DEFAULT NULL,
  `date_stage4` date DEFAULT NULL,
  `status` enum('Approved','Pending','Disapproved') NOT NULL,
  `motivation` text,
  `idstatuslevel` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ErasmusProgress_ErasmusLevel1` (`idstatuslevel`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `erasmusprogressperstudent`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `grades`
--

DROP TABLE IF EXISTS `grades`;
CREATE TABLE IF NOT EXISTS `grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iderasmusinfo` int(11) NOT NULL,
  `grades` int(11) DEFAULT NULL,
  `id_instit_stud_cours` int(11) DEFAULT NULL,
  `institutions_has_study_has_courses_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ErasmusInfo_has_Institutions_has_Study_has_Courses_Erasmus1` (`iderasmusinfo`),
  KEY `fk_Grades_Institutions_has_Study_has_Courses1` (`institutions_has_study_has_courses_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `grades`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `institutions`
--

DROP TABLE IF EXISTS `institutions`;
CREATE TABLE IF NOT EXISTS `institutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  `street` varchar(90) DEFAULT NULL,
  `city` varchar(90) DEFAULT NULL,
  `phone` varchar(90) DEFAULT NULL,
  `fax` varchar(90) DEFAULT NULL,
  `email` varchar(90) DEFAULT NULL,
  `description` text,
  `countrycode` char(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Institutions_Country1` (`countrycode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `institutions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `institutions_has_study`
--

DROP TABLE IF EXISTS `institutions_has_study`;
CREATE TABLE IF NOT EXISTS `institutions_has_study` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idinstitutions` int(11) NOT NULL,
  `idstudy` int(11) NOT NULL,
  `description` text,
  `id_institution_has_study` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Institutions_has_Study_Institutions1` (`idinstitutions`),
  KEY `fk_Institutions_has_Study_Study1` (`idstudy`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `institutions_has_study`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `institutions_has_study_has_courses`
--

DROP TABLE IF EXISTS `institutions_has_study_has_courses`;
CREATE TABLE IF NOT EXISTS `institutions_has_study_has_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idinstitutions` int(11) NOT NULL,
  `idstudy` int(11) NOT NULL,
  `idcourses` int(11) NOT NULL,
  `ects_points` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `fk_Institutions_has_Study_has_Courses_Institutions_has_Study1` (`idinstitutions`,`idstudy`),
  KEY `fk_Institutions_has_Study_has_Courses_Courses1` (`idcourses`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `institutions_has_study_has_courses`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lodging`
--

DROP TABLE IF EXISTS `lodging`;
CREATE TABLE IF NOT EXISTS `lodging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduseroutbound` int(11) DEFAULT NULL,
  `subleased` tinyint(1) DEFAULT NULL,
  `idresident` int(11) DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `iduserinbound` int(11) DEFAULT NULL,
  `idresidents` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Lodging_Residents1` (`idresidents`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `lodging`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `owner`
--

DROP TABLE IF EXISTS `owner`;
CREATE TABLE IF NOT EXISTS `owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  `firstname` varchar(90) DEFAULT NULL,
  `telephone` varchar(90) DEFAULT NULL,
  `email` varchar(90) DEFAULT NULL,
  `street` varchar(90) DEFAULT NULL,
  `city` varchar(90) DEFAULT NULL,
  `postalCode` int(11) DEFAULT NULL,
  `country` varchar(90) DEFAULT NULL,
  `gsm` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `owner`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `residents`
--

DROP TABLE IF EXISTS `residents`;
CREATE TABLE IF NOT EXISTS `residents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` int(11) DEFAULT NULL,
  `street` varchar(90) DEFAULT NULL,
  `city` varchar(90) DEFAULT NULL,
  `postal Code` varchar(90) DEFAULT NULL,
  `country` varchar(90) DEFAULT NULL,
  `kitchen` tinyint(1) DEFAULT NULL,
  `bathroom` tinyint(1) DEFAULT NULL,
  `water_electricity` tinyint(1) DEFAULT NULL,
  `internet` tinyint(1) DEFAULT NULL,
  `beds` int(11) DEFAULT NULL,
  `description` text,
  `free` tinyint(1) DEFAULT NULL,
  `idowner` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Residents_Owner1` (`idowner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `residents`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `study`
--

DROP TABLE IF EXISTS `study`;
CREATE TABLE IF NOT EXISTS `study` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  `description` varchar(90) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL,
  `type` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `study`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userlevel`
--

DROP TABLE IF EXISTS `userlevel`;
CREATE TABLE IF NOT EXISTS `userlevel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `userlevel`
--

INSERT INTO `userlevel` (`id`, `name`, `description`) VALUES
(1, 'Student', NULL),
(2, 'Teaching Staff', NULL),
(3, 'Erasmus Coordinator', NULL),
(4, 'Higher Education Institution (HEI)', NULL),
(5, 'Industrial Institution (II)', NULL),
(6, 'International Relations Office Staff', NULL),
(7, 'Super Administrator', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  `firstname` varchar(90) DEFAULT NULL,
  `password` varchar(90) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birthplace` varchar(90) DEFAULT NULL,
  `sex` tinyint(1) DEFAULT NULL,
  `phone` varchar(90) DEFAULT NULL,
  `gsm` varchar(90) DEFAULT NULL,
  `email` varchar(90) DEFAULT NULL,
  `street` varchar(90) DEFAULT NULL,
  `city` varchar(90) DEFAULT NULL,
  `postalcode` varchar(90) DEFAULT NULL,
  `iduserlevel` int(11) NOT NULL,
  `idlodging` int(11) DEFAULT NULL,
  `code` char(3) NOT NULL,
  `isvaliduser` tinyint(1) DEFAULT NULL,
  `verificationcode` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Users_Userlevel1` (`iduserlevel`),
  KEY `fk_Users_Lodging1` (`idlodging`),
  KEY `fk_Users_Country1` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `name`, `firstname`, `password`, `birthdate`, `birthplace`, `sex`, `phone`, `gsm`, `email`, `street`, `city`, `postalcode`, `iduserlevel`, `idlodging`, `code`, `isvaliduser`, `verificationcode`) VALUES
(1, 'Admin', 'Admin', '21232f297a57a5a743894a0e4a801fc3', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, NULL, NULL, 7, NULL, '', NULL, NULL);
