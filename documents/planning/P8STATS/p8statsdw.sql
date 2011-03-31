SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `p8statsdw` ;
CREATE SCHEMA IF NOT EXISTS `p8statsdw` DEFAULT CHARACTER SET latin1 ;
USE `p8statsdw` ;

-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_gender`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_gender` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_gender` (
  `code` ENUM('M','F','O') NOT NULL COMMENT '\'M\',\'F\'\nO is for tests' ,
  `description` VARCHAR(15) NULL COMMENT '\'Male\',\'Female\'' ,
  PRIMARY KEY (`code`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_mobility`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_mobility` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_mobility` (
  `code` CHAR(6) NOT NULL COMMENT '\'CAMPUS\'' ,
  `description` VARCHAR(20) NULL COMMENT '\'Campus\',\'Residential\'' ,
  PRIMARY KEY (`code`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_date`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_date` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_date` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `day` TINYINT UNSIGNED NULL ,
  `month` TINYINT UNSIGNED NULL ,
  `year` SMALLINT UNSIGNED NULL ,
  `semester` TINYINT UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `date_unq` (`month` DESC, `year` DESC, `id` DESC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_lodging`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_lodging` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_lodging` (
  `code` CHAR(6) NOT NULL ,
  `description` VARCHAR(20) NULL ,
  PRIMARY KEY (`code`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_institution`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_institution` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_institution` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `country_code` VARCHAR(10) NULL ,
  `city_code` VARCHAR(10) NULL ,
  `institution_code` VARCHAR(10) NULL ,
  `faculty_code` VARCHAR(10) NULL ,
  `department_code` VARCHAR(10) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_study`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_study` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_study` (
  `id` INT NOT NULL ,
  `area_code` VARCHAR(10) NULL ,
  `degree_code` VARCHAR(10) NULL ,
  `course_code` VARCHAR(10) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_state`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_state` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_state` (
  `code` CHAR(6) NOT NULL ,
  `description` VARCHAR(25) NULL ,
  PRIMARY KEY (`code`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`fact_efficiency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`fact_efficiency` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`fact_efficiency` (
  `dim_startdate_id` BIGINT UNSIGNED NOT NULL ,
  `dim_enddate_id` BIGINT UNSIGNED NOT NULL ,
  `dim_home_institution_id` INT NOT NULL ,
  `dim_host_institution_id` INT NOT NULL ,
  `dim_state_code` CHAR(6) NOT NULL ,
  `dim_gender_code` ENUM('M','F','O') NOT NULL ,
  `delay_hours` SMALLINT UNSIGNED NULL ,
  `elapsed_hours` SMALLINT UNSIGNED NULL ,
  `reply_hours` SMALLINT UNSIGNED NULL ,
  `gave_up` TINYINT UNSIGNED NULL ,
  `accomodation_available` TINYINT UNSIGNED NULL ,
  `accepted` TINYINT UNSIGNED NULL ,
  `denied` TINYINT UNSIGNED NULL ,
  INDEX `fk_fact_efficiency_dim_state1` (`dim_state_code` ASC) ,
  INDEX `fk_fact_efficiency_dim_gender1` (`dim_gender_code` ASC) ,
  INDEX `fk_fact_efficiency_dim_date2` (`dim_enddate_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_date1` (`dim_startdate_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_institution1` (`dim_host_institution_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_institution2` (`dim_home_institution_id` ASC) ,
  INDEX `idx_efficiency` USING BTREE (`dim_startdate_id` DESC, `dim_enddate_id` DESC, `dim_home_institution_id` DESC, `dim_host_institution_id` DESC, `dim_state_code` DESC, `dim_gender_code` DESC) ,
  CONSTRAINT `fk_fact_efficiency_dim_state1`
    FOREIGN KEY (`dim_state_code` )
    REFERENCES `p8statsdw`.`dim_state` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_gender1`
    FOREIGN KEY (`dim_gender_code` )
    REFERENCES `p8statsdw`.`dim_gender` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_date2`
    FOREIGN KEY (`dim_enddate_id` )
    REFERENCES `p8statsdw`.`dim_date` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_date1`
    FOREIGN KEY (`dim_startdate_id` )
    REFERENCES `p8statsdw`.`dim_date` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_institution1`
    FOREIGN KEY (`dim_host_institution_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_institution2`
    FOREIGN KEY (`dim_home_institution_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`fact_efficacy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`fact_efficacy` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`fact_efficacy` (
  `dim_date_id` BIGINT UNSIGNED NOT NULL ,
  `dim_home_institution_id` INT NOT NULL ,
  `dim_host_institution_id` INT NOT NULL ,
  `dim_mobility_code` CHAR(6) NOT NULL ,
  `dim_lodging_code` CHAR(6) NOT NULL ,
  `dim_gender_code` ENUM('M','F','O') NOT NULL ,
  `extension` TINYINT UNSIGNED NULL ,
  `resubmission` TINYINT UNSIGNED NULL ,
  `ects` TINYINT UNSIGNED NULL ,
  `student` TINYINT UNSIGNED NOT NULL DEFAULT 1 ,
  INDEX `fk_fact_efficacy_dim_lodging1` (`dim_lodging_code` ASC) ,
  INDEX `fk_fact_efficacy_dim_mobility1` (`dim_mobility_code` ASC) ,
  INDEX `fk_fact_efficacy_dim_gender1` (`dim_gender_code` ASC) ,
  INDEX `fk_fact_efficacy_dim_date1` (`dim_date_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_institution1` (`dim_home_institution_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_institution2` (`dim_host_institution_id` ASC) ,
  INDEX `idx_efficacy` USING BTREE (`dim_date_id` DESC, `dim_home_institution_id` DESC, `dim_host_institution_id` DESC, `dim_mobility_code` DESC, `dim_lodging_code` DESC, `dim_gender_code` DESC) ,
  CONSTRAINT `fk_fact_efficacy_dim_lodging1`
    FOREIGN KEY (`dim_lodging_code` )
    REFERENCES `p8statsdw`.`dim_lodging` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_mobility1`
    FOREIGN KEY (`dim_mobility_code` )
    REFERENCES `p8statsdw`.`dim_mobility` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_gender1`
    FOREIGN KEY (`dim_gender_code` )
    REFERENCES `p8statsdw`.`dim_gender` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_date1`
    FOREIGN KEY (`dim_date_id` )
    REFERENCES `p8statsdw`.`dim_date` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_institution1`
    FOREIGN KEY (`dim_home_institution_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_institution2`
    FOREIGN KEY (`dim_host_institution_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
