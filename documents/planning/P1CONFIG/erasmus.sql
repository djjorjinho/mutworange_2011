SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `mydb` ;
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
SHOW WARNINGS;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Country`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Country` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`Country` (
  `Code` CHAR(3) NOT NULL DEFAULT '' ,
  `Name` CHAR(52) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`Code`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`institutions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`institutions` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`institutions` (
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
    REFERENCES `mydb`.`Country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_Institutions_Country1` ON `mydb`.`institutions` (`instCountry` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`users` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`users` (
  `userId` VARCHAR(45) NOT NULL ,
  `familyName` VARCHAR(45) NOT NULL ,
  `firstName` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `birthDate` DATE NOT NULL ,
  `birthPlace` VARCHAR(45) NOT NULL ,
  `sex` TINYINT(1)  NOT NULL ,
  `tel` VARCHAR(45) NOT NULL ,
  `mobilePhone` VARCHAR(45) NULL ,
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
    REFERENCES `mydb`.`Country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Users_Institutions1`
    FOREIGN KEY (`institutionId` )
    REFERENCES `mydb`.`institutions` (`instId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_Users_Country1` ON `mydb`.`users` (`country` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_Users_Institutions1` ON `mydb`.`users` (`institutionId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`owner`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`owner` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`owner` (
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
    REFERENCES `mydb`.`Country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_owner_Country1` ON `mydb`.`owner` (`country` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`residence`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`residence` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`residence` (
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
    REFERENCES `mydb`.`owner` (`ownerId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_residence_Country1`
    FOREIGN KEY (`country` )
    REFERENCES `mydb`.`Country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_Residents_Owner1` ON `mydb`.`residence` (`ownerId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_residence_Country1` ON `mydb`.`residence` (`country` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`leasing`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`leasing` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`leasing` (
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
    REFERENCES `mydb`.`residence` (`residenceId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_Lodging_Residents1` ON `mydb`.`leasing` (`residentId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`Education`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Education` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`Education` (
  `educationId` INT NOT NULL AUTO_INCREMENT ,
  `educationName` VARCHAR(200) NULL ,
  PRIMARY KEY (`educationId`) )
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`educationPerInstitute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`educationPerInstitute` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`educationPerInstitute` (
  `educationPerInstId` INT NOT NULL ,
  `institutionId` INT NOT NULL ,
  `studyId` INT NOT NULL ,
  `Description` TEXT NULL ,
  PRIMARY KEY (`educationPerInstId`) ,
  CONSTRAINT `fk_Institutions_has_Study_Institutions1`
    FOREIGN KEY (`institutionId` )
    REFERENCES `mydb`.`institutions` (`instId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Institutions_has_Study_Study1`
    FOREIGN KEY (`studyId` )
    REFERENCES `mydb`.`Education` (`educationId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_Institutions_has_Study_Study1` ON `mydb`.`educationPerInstitute` (`studyId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_Institutions_has_Study_Institutions1` ON `mydb`.`educationPerInstitute` (`institutionId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`erasmusStudent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`erasmusStudent` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`erasmusStudent` (
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
    REFERENCES `mydb`.`users` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfo_Users1`
    FOREIGN KEY (`homeCoordinatorId` )
    REFERENCES `mydb`.`users` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfo_Users3`
    FOREIGN KEY (`hostCoordinatorId` )
    REFERENCES `mydb`.`users` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfo_Institutions1`
    FOREIGN KEY (`homeInstitutionId` )
    REFERENCES `mydb`.`institutions` (`instId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfo_Institutions2`
    FOREIGN KEY (`hostInstitutionId` )
    REFERENCES `mydb`.`institutions` (`instId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfoPerStudent_Institutions_has_Study1`
    FOREIGN KEY (`educationPerInstId` )
    REFERENCES `mydb`.`educationPerInstitute` (`educationPerInstId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ErasmusInfoPerStudent_Lodging1`
    FOREIGN KEY (`lodgingId` )
    REFERENCES `mydb`.`leasing` (`rentalId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Users2` ON `mydb`.`erasmusStudent` (`studentId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Users1` ON `mydb`.`erasmusStudent` (`homeCoordinatorId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Users3` ON `mydb`.`erasmusStudent` (`hostCoordinatorId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Institutions1` ON `mydb`.`erasmusStudent` (`homeInstitutionId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfo_Institutions2` ON `mydb`.`erasmusStudent` (`hostInstitutionId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfoPerStudent_Institutions_has_Study1` ON `mydb`.`erasmusStudent` (`educationPerInstId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusInfoPerStudent_Lodging1` ON `mydb`.`erasmusStudent` (`lodgingId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`coursesPerEducPerInst`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`coursesPerEducPerInst` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`coursesPerEducPerInst` (
  `courseId` INT NOT NULL AUTO_INCREMENT ,
  `courseName` VARCHAR(100) NOT NULL ,
  `ectsPoint` INT NOT NULL ,
  `courseDescription` TEXT NULL ,
  `educationPerInstId` INT NOT NULL ,
  PRIMARY KEY (`courseId`) ,
  CONSTRAINT `fk_Courses_educationPerInstitute1`
    FOREIGN KEY (`educationPerInstId` )
    REFERENCES `mydb`.`educationPerInstitute` (`educationPerInstId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_Courses_educationPerInstitute1` ON `mydb`.`coursesPerEducPerInst` (`educationPerInstId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`grades`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`grades` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`grades` (
  `gradesId` INT NOT NULL AUTO_INCREMENT ,
  `courseId` INT NOT NULL ,
  `studentId` VARCHAR(45) NOT NULL ,
  `grade` INT NULL ,
  PRIMARY KEY (`gradesId`) ,
  CONSTRAINT `fk_Grades_Institutions_has_Study_has_Courses1`
    FOREIGN KEY (`courseId` )
    REFERENCES `mydb`.`coursesPerEducPerInst` (`courseId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Grades_ErasmusInfoPerStudent1`
    FOREIGN KEY (`studentId` )
    REFERENCES `mydb`.`erasmusStudent` (`studentId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_Grades_Institutions_has_Study_has_Courses1` ON `mydb`.`grades` (`courseId` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_Grades_ErasmusInfoPerStudent1` ON `mydb`.`grades` (`studentId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`studentEvents`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`studentEvents` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`studentEvents` (
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
    REFERENCES `mydb`.`erasmusStudent` (`studentId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_ErasmusProgressPerStudent_ErasmusInfoPerStudent1` ON `mydb`.`studentEvents` (`erasmusStudentId` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`companies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`companies` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`companies` (
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
    REFERENCES `mydb`.`Country` (`Code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_Institutions_Country1` ON `mydb`.`companies` (`companyCountry` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mydb`.`forms`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`forms` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `mydb`.`forms` (
  `formId` INT NOT NULL ,
  `type` VARCHAR(45) NOT NULL ,
  `date` DATE NOT NULL ,
  `content` TEXT NOT NULL ,
  `studentId` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`formId`) ,
  CONSTRAINT `fk_Forms_ErasmusInfoPerStudent1`
    FOREIGN KEY (`studentId` )
    REFERENCES `mydb`.`erasmusStudent` (`studentId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_Forms_ErasmusInfoPerStudent1` ON `mydb`.`forms` (`studentId` ASC) ;

SHOW WARNINGS;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
