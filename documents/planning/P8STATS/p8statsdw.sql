SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `p8statsdw` ;
CREATE SCHEMA IF NOT EXISTS `p8statsdw` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `p8statsdw` ;

-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_gender`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_gender` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_gender` (
  `code` ENUM('M','F','O') NOT NULL COMMENT '\'M\',\'F\'\nO is for tests' ,
  `description` VARCHAR(15) NULL COMMENT '\'Male\',\'Female\'' ,
  PRIMARY KEY (`code`) ,
  INDEX `idx_gender_description` (`description` ASC, `code` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_mobility`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_mobility` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_mobility` (
  `code` CHAR(6) NOT NULL COMMENT '\'CAMPUS\'' ,
  `description` VARCHAR(20) NULL COMMENT '\'Campus\',\'Residential\'' ,
  PRIMARY KEY (`code`) ,
  INDEX `idx_mobility_description` (`description` ASC, `code` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_date`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_date` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_date` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `year` SMALLINT UNSIGNED NULL ,
  `semester` ENUM('1','2') NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_dim_date` (`id` ASC, `year` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_lodging`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_lodging` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_lodging` (
  `code` CHAR(6) NOT NULL ,
  `description` VARCHAR(20) NULL ,
  PRIMARY KEY (`code`) ,
  INDEX `idx_lodging_description` (`description` ASC, `code` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


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
  PRIMARY KEY (`id`) ,
  INDEX `idx_institution` USING BTREE (`id` ASC, `country_code` DESC, `city_code` DESC, `institution_code` DESC, `faculty_code` DESC, `department_code` DESC) ,
  INDEX `idx_institution_country` (`country_code` ASC) ,
  INDEX `idx_institution_city` (`city_code` ASC) ,
  INDEX `idx_institution_institution` (`institution_code` ASC) ,
  INDEX `idx_institution_faculty` (`faculty_code` ASC) ,
  INDEX `idx_institution_department` (`department_code` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_study`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_study` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_study` (
  `id` INT NOT NULL ,
  `area_code` VARCHAR(10) NULL ,
  `degree_code` VARCHAR(10) NULL ,
  `course_code` VARCHAR(10) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_study` (`id` ASC, `area_code` ASC, `degree_code` ASC, `course_code` ASC) ,
  INDEX `idx_study_area` (`area_code` ASC) ,
  INDEX `idx_study_degree` (`degree_code` ASC) ,
  INDEX `idx_study_course` (`course_code` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_phase`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_phase` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_phase` (
  `code` CHAR(6) NOT NULL ,
  `description` VARCHAR(25) NULL ,
  PRIMARY KEY (`code`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`fact_efficiency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`fact_efficiency` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`fact_efficiency` (
  `dim_date_id` BIGINT UNSIGNED NOT NULL ,
  `dim_home_institution_id` INT NOT NULL ,
  `dim_host_institution_id` INT NOT NULL ,
  `dim_phase_code` CHAR(6) NOT NULL ,
  `dim_mobility_code` CHAR(6) NOT NULL ,
  `dim_gender_code` ENUM('M','F','O') NOT NULL ,
  `avg_response_days` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `max_response_days` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `min_response_days` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `val_participants` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `perc_students` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  INDEX `fk_fact_efficiency_dim_enddate` (`dim_date_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_host_institution` (`dim_host_institution_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_home_institution` (`dim_home_institution_id` ASC) ,
  INDEX `idx_efficiency_home` USING BTREE (`dim_date_id` DESC, `dim_home_institution_id` DESC, `dim_phase_code` ASC, `dim_mobility_code` ASC, `dim_gender_code` ASC) ,
  INDEX `idx_efficiency_host` (`dim_date_id` ASC, `dim_host_institution_id` ASC, `dim_phase_code` ASC, `dim_mobility_code` ASC, `dim_gender_code` ASC) ,
  INDEX `fk_fact_efficiency_dim_mobility1` (`dim_mobility_code` ASC) ,
  INDEX `fk_fact_efficiency_dim_phase1` (`dim_phase_code` ASC) ,
  INDEX `fk_fact_efficiency_dim_gender1` (`dim_gender_code` ASC) ,
  CONSTRAINT `fk_fact_efficiency_dim_date`
    FOREIGN KEY (`dim_date_id` )
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
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_mobility1`
    FOREIGN KEY (`dim_mobility_code` )
    REFERENCES `p8statsdw`.`dim_mobility` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_phase1`
    FOREIGN KEY (`dim_phase_code` )
    REFERENCES `p8statsdw`.`dim_phase` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_gender1`
    FOREIGN KEY (`dim_gender_code` )
    REFERENCES `p8statsdw`.`dim_gender` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MRG_MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`fact_efficacy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`fact_efficacy` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`fact_efficacy` (
  `dim_date_id` BIGINT UNSIGNED NOT NULL ,
  `dim_home_institution_id` INT NOT NULL ,
  `dim_host_institution_id` INT NOT NULL ,
  `dim_mobility_code` CHAR(6) NOT NULL ,
  `dim_study_id` INT NOT NULL ,
  `dim_lodging_code` CHAR(6) NOT NULL ,
  `dim_gender_code` ENUM('M','F','O') NOT NULL ,
  `total_applications` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `last_applications` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `avg_ects` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `max_ects` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `min_ects` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  INDEX `fk_fact_efficacy_dim_lodging` (`dim_lodging_code` ASC) ,
  INDEX `fk_fact_efficacy_dim_mobility` (`dim_mobility_code` ASC) ,
  INDEX `fk_fact_efficacy_dim_gender` (`dim_gender_code` ASC) ,
  INDEX `fk_fact_efficacy_dim_date` (`dim_date_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_home_institution` (`dim_home_institution_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_host_institution` (`dim_host_institution_id` ASC) ,
  INDEX `idx_efficacy_home` USING BTREE (`dim_date_id` DESC, `dim_home_institution_id` DESC, `dim_mobility_code` DESC, `dim_study_id` ASC, `dim_lodging_code` ASC, `dim_gender_code` DESC) ,
  INDEX `idx_efficacy_host` USING BTREE (`dim_date_id` ASC, `dim_host_institution_id` ASC, `dim_mobility_code` ASC, `dim_study_id` ASC, `dim_lodging_code` ASC, `dim_gender_code` ASC) ,
  INDEX `fk_fact_efficacy_dim_study1` (`dim_study_id` ASC) ,
  CONSTRAINT `fk_fact_efficacy_dim_lodging10`
    FOREIGN KEY (`dim_lodging_code` )
    REFERENCES `p8statsdw`.`dim_lodging` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_mobility10`
    FOREIGN KEY (`dim_mobility_code` )
    REFERENCES `p8statsdw`.`dim_mobility` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_gender10`
    FOREIGN KEY (`dim_gender_code` )
    REFERENCES `p8statsdw`.`dim_gender` (`code` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_date10`
    FOREIGN KEY (`dim_date_id` )
    REFERENCES `p8statsdw`.`dim_date` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_institution10`
    FOREIGN KEY (`dim_home_institution_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_institution20`
    FOREIGN KEY (`dim_host_institution_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_study1`
    FOREIGN KEY (`dim_study_id` )
    REFERENCES `p8statsdw`.`dim_study` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MRG_MyISAM
INSERT_METHOD = LAST
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_semester`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_semester` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_semester` (
  `code` ENUM('1','2') NOT NULL ,
  `description` VARCHAR(15) NULL ,
  PRIMARY KEY (`code`) )
ENGINE = MyISAM;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
