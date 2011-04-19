SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `erasmusline` ;
CREATE SCHEMA IF NOT EXISTS `erasmusline` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
SHOW WARNINGS;
USE `erasmusline` ;
GRANT ALL PRIVILEGES  ON erasmusline.* 
TO 'erasmusline'@'%' IDENTIFIED BY 'orange' 
WITH GRANT OPTION;

-- -----------------------------------------------------
-- Table `erasmusline`.`country`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`country` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`country` (
  `Code` CHAR(3) NOT NULL DEFAULT '' ,
  `Name` CHAR(52) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`Code`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`institutions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`institutions` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`institutions` (
  `instId` INT NOT NULL AUTO_INCREMENT ,
  `instName` VARCHAR(200) NOT NULL ,
  `instStreetNr` VARCHAR(200) NOT NULL ,
  `instCity` VARCHAR(100) NOT NULL ,
  `instPostalCode` VARCHAR(45) NOT NULL ,
  `instCountry` CHAR(3) NOT NULL ,
  `instTel` VARCHAR(45) NOT NULL ,
  `instFax` VARCHAR(45) NULL ,
  `instEmail` VARCHAR(45) NULL ,
  `instDescription` TEXT NULL ,
  `instWebsite` VARCHAR(100) NULL ,
  `instPossibility` INT NOT NULL ,
  PRIMARY KEY (`instId`) ,
  CONSTRAINT `fk_Institutions_Country1`
    FOREIGN KEY (`instCountry` )
    REFERENCES `erasmusline`.`country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_Institutions_Country1` ON `erasmusline`.`institutions` (`instCountry` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`users` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`users` (
  `userId` VARCHAR(45) NOT NULL ,
  `familyName` VARCHAR(45) NOT NULL ,
  `firstName` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `birthDate` DATE NOT NULL ,
  `birthPlace` VARCHAR(45) NOT NULL ,
  `sex` TINYINT(1)  NOT NULL ,
  `tel` VARCHAR(45) NOT NULL ,
  `mobilePhone` VARCHAR(45) NULL ,
  `fax` VARCHAR(45) NULL ,
  `email` VARCHAR(45) NULL ,
  `streetNr` VARCHAR(45) NOT NULL ,
  `city` VARCHAR(45) NOT NULL ,
  `postalCode` VARCHAR(45) NOT NULL ,
  `country` CHAR(3) NOT NULL ,
  `userLevel` VARCHAR(45) NOT NULL ,
  `isValidUser` INT NOT NULL ,
  `verificationCode` VARCHAR(32) NULL ,
  `institutionId` INT NOT NULL ,
  PRIMARY KEY (`userId`) ,
  CONSTRAINT `fk_Users_Country1`
    FOREIGN KEY (`country` )
    REFERENCES `erasmusline`.`country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Users_Institutions1`
    FOREIGN KEY (`institutionId` )
    REFERENCES `erasmusline`.`institutions` (`instId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_Users_Country1` ON `erasmusline`.`users` (`country` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_Users_Institutions1` ON `erasmusline`.`users` (`institutionId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`owner`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`owner` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`owner` (
  `ownerId` INT NOT NULL AUTO_INCREMENT ,
  `familyName` VARCHAR(200) NULL ,
  `firstName` VARCHAR(200) NULL ,
  `tel` VARCHAR(45) NULL ,
  `email` VARCHAR(200) NULL ,
  `streetNr` VARCHAR(200) NULL ,
  `city` VARCHAR(200) NULL ,
  `postalCode` VARCHAR(45) NULL ,
  `mobilePhone` VARCHAR(45) NULL ,
  `country` CHAR(3) NOT NULL ,
  PRIMARY KEY (`ownerId`) ,
  CONSTRAINT `fk_owner_Country1`
    FOREIGN KEY (`country` )
    REFERENCES `erasmusline`.`country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_owner_Country1` ON `erasmusline`.`owner` (`country` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`residence`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`residence` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`residence` (
  `residenceId` INT NOT NULL AUTO_INCREMENT ,
  `price` INT NOT NULL ,
  `streetNr` VARCHAR(200) NOT NULL ,
  `city` VARCHAR(200) NOT NULL ,
  `postalCode` VARCHAR(45) NOT NULL ,
  `country` CHAR(3) NOT NULL ,
  `kitchen` TINYINT(1)  NOT NULL ,
  `bathroom` TINYINT(1)  NOT NULL ,
  `water/Electricity` INT NOT NULL ,
  `internet` INT NOT NULL ,
  `beds` INT NOT NULL ,
  `description` TEXT NULL ,
  `available` TINYINT(1)  NOT NULL ,
  `ownerId` INT NOT NULL ,
  `television` INT NOT NULL ,
  PRIMARY KEY (`residenceId`) ,
  CONSTRAINT `fk_Residents_Owner1`
    FOREIGN KEY (`ownerId` )
    REFERENCES `erasmusline`.`owner` (`ownerId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_residence_Country1`
    FOREIGN KEY (`country` )
    REFERENCES `erasmusline`.`country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_Residents_Owner1` ON `erasmusline`.`residence` (`ownerId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_residence_Country1` ON `erasmusline`.`residence` (`country` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`leasing`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`leasing` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`leasing` (
  `rentalId` INT NOT NULL AUTO_INCREMENT ,
  `outboundUser` INT NULL ,
  `Subleased` TINYINT(1)  NOT NULL ,
  `startDate` DATE NOT NULL ,
  `endDate` DATE NOT NULL ,
  `inboundUser` INT NULL ,
  `residentId` INT NOT NULL ,
  PRIMARY KEY (`rentalId`) ,
  CONSTRAINT `fk_Lodging_Residents1`
    FOREIGN KEY (`residentId` )
    REFERENCES `erasmusline`.`residence` (`residenceId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_Lodging_Residents1` ON `erasmusline`.`leasing` (`residentId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`education`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`education` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`education` (
  `educationId` INT NOT NULL AUTO_INCREMENT ,
  `educationName` VARCHAR(200) NULL ,
  PRIMARY KEY (`educationId`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`educationPerInstitute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`educationPerInstitute` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`educationPerInstitute` (
  `educationPerInstId` INT NOT NULL ,
  `institutionId` INT NOT NULL ,
  `studyId` INT NOT NULL ,
  `Description` TEXT NULL ,
  PRIMARY KEY (`educationPerInstId`) ,
  CONSTRAINT `fk_Institutions_has_Study_Institutions1`
    FOREIGN KEY (`institutionId` )
    REFERENCES `erasmusline`.`institutions` (`instId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Institutions_has_Study_Study1`
    FOREIGN KEY (`studyId` )
    REFERENCES `erasmusline`.`education` (`educationId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_Institutions_has_Study_Study1` ON `erasmusline`.`educationPerInstitute` (`studyId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_Institutions_has_Study_Institutions1` ON `erasmusline`.`educationPerInstitute` (`institutionId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`erasmusStudent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`erasmusStudent` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`erasmusStudent` (
  `studentId` VARCHAR(45) NOT NULL ,
  `homeCoordinatorId` INT NOT NULL ,
  `hostCoordinatorId` INT NOT NULL ,
  `homeInstitutionId` INT NOT NULL ,
  `hostInstitutionId` INT NOT NULL ,
  `startDate` DATE NOT NULL ,
  `endDate` DATE NOT NULL ,
  `educationPerInstId` INT NOT NULL ,
  `statusOfErasmus` VARCHAR(45) NOT NULL ,
  `lodgingId` INT NOT NULL ,
  `traineeOrStudy` INT NOT NULL ,
  `uploadedWhat` INT NULL ,
  `ectsCredits` INT NOT NULL ,
  `mothertongue` VARCHAR(45) NOT NULL ,
  `beenAbroad` TINYINT(1)  NULL ,
  PRIMARY KEY (`studentId`) ,
  CONSTRAINT `fk_ErasmusInfo_Users2`
    FOREIGN KEY (`studentId` )
    REFERENCES `erasmusline`.`users` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfo_Users1`
    FOREIGN KEY (`homeCoordinatorId` )
    REFERENCES `erasmusline`.`users` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfo_Users3`
    FOREIGN KEY (`hostCoordinatorId` )
    REFERENCES `erasmusline`.`users` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfo_Institutions1`
    FOREIGN KEY (`homeInstitutionId` )
    REFERENCES `erasmusline`.`institutions` (`instId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfo_Institutions2`
    FOREIGN KEY (`hostInstitutionId` )
    REFERENCES `erasmusline`.`institutions` (`instId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfoPerStudent_Institutions_has_Study1`
    FOREIGN KEY (`educationPerInstId` )
    REFERENCES `erasmusline`.`educationPerInstitute` (`educationPerInstId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfoPerStudent_Lodging1`
    FOREIGN KEY (`lodgingId` )
    REFERENCES `erasmusline`.`leasing` (`rentalId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Users2` ON `erasmusline`.`erasmusStudent` (`studentId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Users1` ON `erasmusline`.`erasmusStudent` (`homeCoordinatorId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Users3` ON `erasmusline`.`erasmusStudent` (`hostCoordinatorId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Institutions1` ON `erasmusline`.`erasmusStudent` (`homeInstitutionId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Institutions2` ON `erasmusline`.`erasmusStudent` (`hostInstitutionId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfoPerStudent_Institutions_has_Study1` ON `erasmusline`.`erasmusStudent` (`educationPerInstId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfoPerStudent_Lodging1` ON `erasmusline`.`erasmusStudent` (`lodgingId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`coursesPerEducPerInst`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`coursesPerEducPerInst` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`coursesPerEducPerInst` (
  `courseId` INT NOT NULL AUTO_INCREMENT ,
  `courseCode` VARCHAR(45) NOT NULL ,
  `courseName` VARCHAR(100) NOT NULL ,
  `ectsCredits` VARCHAR(45) NOT NULL ,
  `courseDescription` TEXT NULL ,
  `educationPerInstId` INT NOT NULL ,
  PRIMARY KEY (`courseId`) ,
  CONSTRAINT `fk_Courses_educationPerInstitute1`
    FOREIGN KEY (`educationPerInstId` )
    REFERENCES `erasmusline`.`educationPerInstitute` (`educationPerInstId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_Courses_educationPerInstitute1` ON `erasmusline`.`coursesPerEducPerInst` (`educationPerInstId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`grades`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`grades` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`grades` (
  `courseId` INT NOT NULL ,
  `studentId` VARCHAR(45) NOT NULL ,
  `localGrade` INT NULL ,
  `ectsGrade` VARCHAR(3) NULL ,
  `courseDuration` VARCHAR(45) NULL ,
  PRIMARY KEY (`courseId`, `studentId`) ,
  CONSTRAINT `fk_Grades_Institutions_has_Study_has_Courses1`
    FOREIGN KEY (`courseId` )
    REFERENCES `erasmusline`.`coursesPerEducPerInst` (`courseId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Grades_ErasmusInfoPerStudent1`
    FOREIGN KEY (`studentId` )
    REFERENCES `erasmusline`.`erasmusStudent` (`studentId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_Grades_Institutions_has_Study_has_Courses1` ON `erasmusline`.`grades` (`courseId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_Grades_ErasmusInfoPerStudent1` ON `erasmusline`.`grades` (`studentId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`studentEvents`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`studentEvents` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`studentEvents` (
  `eventId` INT NOT NULL AUTO_INCREMENT ,
  `event` TEXT NOT NULL ,
  `timestamp` DATE NOT NULL ,
  `motivation` TEXT NULL ,
  `erasmusLevel` ENUM('precandidate') NOT NULL ,
  `read_Notification` TINYINT(1)  NOT NULL ,
  `erasmusStudentId` VARCHAR(45) NOT NULL ,
  `action` TINYINT(1)  NOT NULL ,
  PRIMARY KEY (`eventId`) ,
  CONSTRAINT `fk_ErasmusProgressPerStudent_ErasmusInfoPerStudent1`
    FOREIGN KEY (`erasmusStudentId` )
    REFERENCES `erasmusline`.`erasmusStudent` (`studentId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusProgressPerStudent_ErasmusInfoPerStudent1` ON `erasmusline`.`studentEvents` (`erasmusStudentId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`companies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`companies` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`companies` (
  `companyId` INT NOT NULL AUTO_INCREMENT ,
  `companyName` VARCHAR(200) NOT NULL ,
  `companyStreetNr` VARCHAR(200) NOT NULL ,
  `companyCity` VARCHAR(100) NOT NULL ,
  `companyPostalCode` VARCHAR(45) NOT NULL ,
  `companyCountry` CHAR(3) NOT NULL ,
  `companyTel` VARCHAR(45) NOT NULL ,
  `companyFax` VARCHAR(45) NULL ,
  `companyEmail` VARCHAR(150) NULL ,
  `companyDescription` TEXT NULL ,
  `companyWebsite` VARCHAR(200) NULL ,
  PRIMARY KEY (`companyId`) ,
  CONSTRAINT `fk_Institutions_Country10`
    FOREIGN KEY (`companyCountry` )
    REFERENCES `erasmusline`.`country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_Institutions_Country1` ON `erasmusline`.`companies` (`companyCountry` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `erasmusline`.`forms`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `erasmusline`.`forms` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `erasmusline`.`forms` (
  `formId` INT NOT NULL ,
  `type` VARCHAR(45) NOT NULL ,
  `date` DATE NOT NULL ,
  `content` TEXT NOT NULL ,
  `studentId` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`formId`) ,
  CONSTRAINT `fk_Forms_ErasmusInfoPerStudent1`
    FOREIGN KEY (`studentId` )
    REFERENCES `erasmusline`.`erasmusStudent` (`studentId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SHOW WARNINGS;
CREATE INDEX `fk_Forms_ErasmusInfoPerStudent1` ON `erasmusline`.`forms` (`studentId` ASC) ;

SHOW WARNINGS;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
