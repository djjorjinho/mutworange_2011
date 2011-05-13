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
  `dim_gender_id` ENUM('M','F','O') NOT NULL COMMENT '\'M\',\'F\'\nO is for tests' ,
  `description` VARCHAR(15) NULL COMMENT '\'Male\',\'Female\'' ,
  PRIMARY KEY (`dim_gender_id`) ,
  INDEX `idx_gender_description` (`description` ASC, `dim_gender_id` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_mobility`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_mobility` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_mobility` (
  `dim_mobility_id` CHAR(6) NOT NULL COMMENT '\'CAMPUS\'' ,
  `description` VARCHAR(20) NULL COMMENT '\'Campus\',\'Residential\'' ,
  PRIMARY KEY (`dim_mobility_id`) ,
  INDEX `idx_mobility_description` (`description` ASC, `dim_mobility_id` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_date`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_date` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_date` (
  `dim_date_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `year` SMALLINT UNSIGNED NULL ,
  `semester` ENUM('1','2') NULL ,
  PRIMARY KEY (`dim_date_id`) ,
  INDEX `idx_dim_date` (`dim_date_id` ASC, `year` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_lodging`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_lodging` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_lodging` (
  `dim_lodging_id` VARCHAR(20) NOT NULL ,
  `description` VARCHAR(20) NULL ,
  PRIMARY KEY (`dim_lodging_id`) ,
  INDEX `idx_lodging_description` (`description` ASC, `dim_lodging_id` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_institution`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_institution` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_institution` (
  `dim_institution_id` INT NOT NULL AUTO_INCREMENT ,
  `country_code` VARCHAR(10) NULL ,
  `institution_code` VARCHAR(10) NULL ,
  `description` VARCHAR(30) NULL ,
  PRIMARY KEY (`dim_institution_id`) ,
  INDEX `idx_institution` USING BTREE (`dim_institution_id` DESC, `country_code` DESC, `institution_code` DESC, `description` DESC) ,
  INDEX `idx_institution_country` (`country_code` ASC) ,
  INDEX `idx_institution_institution` (`institution_code` ASC, `description` ASC) )
ENGINE = MyISAM
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`dim_study`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`dim_study` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`dim_study` (
  `dim_study_id` INT NOT NULL AUTO_INCREMENT ,
  `area_code` VARCHAR(10) NULL ,
  `degree_code` VARCHAR(10) NULL ,
  `course_code` VARCHAR(10) NULL ,
  `description` VARCHAR(30) NULL ,
  PRIMARY KEY (`dim_study_id`) ,
  INDEX `idx_study` (`dim_study_id` ASC, `area_code` ASC, `degree_code` ASC, `course_code` ASC, `description` ASC) ,
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
  `dim_phase_id` VARCHAR(20) NOT NULL ,
  `description` VARCHAR(25) NULL ,
  PRIMARY KEY (`dim_phase_id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`fact_efficiency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`fact_efficiency` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`fact_efficiency` (
  `dim_date_id` BIGINT UNSIGNED NOT NULL ,
  `dim_institution_id` INT NOT NULL ,
  `dim_institution_host_id` INT NOT NULL ,
  `dim_phase_id` VARCHAR(20) NOT NULL ,
  `dim_mobility_id` CHAR(6) NOT NULL ,
  `dim_gender_id` ENUM('M','F','O') NOT NULL ,
  `avg_response_days` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `max_response_days` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `min_response_days` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `val_participants` INT UNSIGNED NULL DEFAULT 0 ,
  `perc_students` SMALLINT NULL DEFAULT 0 ,
  `lodging_available` INT UNSIGNED NULL DEFAULT 0 ,
  `perc_lodging` SMALLINT NULL DEFAULT 0 ,
  INDEX `fk_fact_efficiency_dim_enddate` (`dim_date_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_host_institution` (`dim_institution_host_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_home_institution` (`dim_institution_id` ASC) ,
  INDEX `idx_efficiency_home` USING BTREE (`dim_date_id` DESC, `dim_institution_id` DESC, `dim_phase_id` ASC, `dim_mobility_id` ASC, `dim_gender_id` ASC) ,
  INDEX `idx_efficiency_host` (`dim_date_id` ASC, `dim_institution_host_id` ASC, `dim_phase_id` ASC, `dim_mobility_id` ASC, `dim_gender_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_mobility1` (`dim_mobility_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_phase1` (`dim_phase_id` ASC) ,
  INDEX `fk_fact_efficiency_dim_gender1` (`dim_gender_id` ASC) ,
  CONSTRAINT `fk_fact_efficiency_dim_date`
    FOREIGN KEY (`dim_date_id` )
    REFERENCES `p8statsdw`.`dim_date` (`dim_date_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_institution1`
    FOREIGN KEY (`dim_institution_host_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`dim_institution_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_institution2`
    FOREIGN KEY (`dim_institution_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`dim_institution_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_mobility1`
    FOREIGN KEY (`dim_mobility_id` )
    REFERENCES `p8statsdw`.`dim_mobility` (`dim_mobility_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_phase1`
    FOREIGN KEY (`dim_phase_id` )
    REFERENCES `p8statsdw`.`dim_phase` (`dim_phase_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficiency_dim_gender1`
    FOREIGN KEY (`dim_gender_id` )
    REFERENCES `p8statsdw`.`dim_gender` (`dim_gender_id` )
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
  `dim_institution_id` INT NOT NULL ,
  `dim_institution_host_id` INT NOT NULL ,
  `dim_mobility_id` CHAR(6) NOT NULL ,
  `dim_study_id` INT NOT NULL ,
  `dim_lodging_id` VARCHAR(20) NOT NULL ,
  `dim_gender_id` ENUM('M','F','O') NOT NULL ,
  `total_applications` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `last_applications` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `avg_ects` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `max_ects` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  `min_ects` SMALLINT UNSIGNED NULL DEFAULT 0 ,
  INDEX `fk_fact_efficacy_dim_lodging` (`dim_lodging_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_mobility` (`dim_mobility_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_gender` (`dim_gender_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_date` (`dim_date_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_home_institution` (`dim_institution_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_host_institution` (`dim_institution_host_id` ASC) ,
  INDEX `idx_efficacy_home` USING BTREE (`dim_date_id` DESC, `dim_institution_id` DESC, `dim_mobility_id` DESC, `dim_study_id` ASC, `dim_lodging_id` ASC, `dim_gender_id` DESC) ,
  INDEX `idx_efficacy_host` USING BTREE (`dim_date_id` ASC, `dim_institution_host_id` ASC, `dim_mobility_id` ASC, `dim_study_id` ASC, `dim_lodging_id` ASC, `dim_gender_id` ASC) ,
  INDEX `fk_fact_efficacy_dim_study1` (`dim_study_id` ASC) ,
  CONSTRAINT `fk_fact_efficacy_dim_lodging10`
    FOREIGN KEY (`dim_lodging_id` )
    REFERENCES `p8statsdw`.`dim_lodging` (`dim_lodging_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_mobility10`
    FOREIGN KEY (`dim_mobility_id` )
    REFERENCES `p8statsdw`.`dim_mobility` (`dim_mobility_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_gender10`
    FOREIGN KEY (`dim_gender_id` )
    REFERENCES `p8statsdw`.`dim_gender` (`dim_gender_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_date10`
    FOREIGN KEY (`dim_date_id` )
    REFERENCES `p8statsdw`.`dim_date` (`dim_date_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_institution10`
    FOREIGN KEY (`dim_institution_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`dim_institution_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_institution20`
    FOREIGN KEY (`dim_institution_host_id` )
    REFERENCES `p8statsdw`.`dim_institution` (`dim_institution_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fact_efficacy_dim_study1`
    FOREIGN KEY (`dim_study_id` )
    REFERENCES `p8statsdw`.`dim_study` (`dim_study_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MRG_MyISAM
INSERT_METHOD = LAST
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `p8statsdw`.`slaves`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`slaves` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`slaves` (
  `slaves_id` CHAR(15) NOT NULL COMMENT 'ip address' ,
  `port` CHAR(7) NULL ,
  PRIMARY KEY (`slaves_id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`ods_efficiency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`ods_efficiency` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`ods_efficiency` (
  `ods_efficiency_id` BIGINT NOT NULL AUTO_INCREMENT ,
  `student_id` VARCHAR(45) NULL COMMENT 'erasmusStudent.studentid' ,
  `dim_phase_id` VARCHAR(20) NULL COMMENT 'forms.type' ,
  `institution_code` VARCHAR(20) NULL COMMENT 'erasmusStudent.homeInstitutionId' ,
  `institution_host_code` VARCHAR(20) NULL COMMENT 'erasmusStudent.hostInstitutionId' ,
  `country_code` CHAR(6) NULL ,
  `country_host_code` CHAR(6) NULL ,
  `year` SMALLINT UNSIGNED NULL ,
  `semester` ENUM('1','2') NULL ,
  `dim_mobility_id` CHAR(6) NULL COMMENT 'forms.content.traineeOrStudy' ,
  `create_date` DATETIME NULL ,
  `approve_date` DATETIME NULL ,
  `reject_date` DATETIME NULL ,
  `dim_gender_id` VARCHAR(45) NULL COMMENT 'user.sex' ,
  `lodging_available` TINYINT(1) UNSIGNED NULL DEFAULT 0 COMMENT 'erasmusStudent.lodgingId\n-> leasing.rentalid\n\nleasing.residentid -> residence.recidenceid\n\nresidence.available' ,
  PRIMARY KEY (`ods_efficiency_id`) ,
  UNIQUE INDEX `idx_student_phase` (`student_id` ASC, `dim_phase_id` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`meta_semester`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`meta_semester` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`meta_semester` (
  `meta_semester_id` CHAR(7) NOT NULL DEFAULT 'default' COMMENT 'countryid' ,
  `semester` ENUM('1','2') NOT NULL DEFAULT 1 ,
  `range` VARCHAR(45) NULL COMMENT 'regular expression defining numeric range' ,
  PRIMARY KEY (`meta_semester_id`, `semester`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`scenarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`scenarios` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`scenarios` (
  `scenarios_id` VARCHAR(45) NOT NULL ,
  `users_id` INT UNSIGNED NOT NULL ,
  `config` TEXT NULL ,
  PRIMARY KEY (`scenarios_id`, `users_id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `p8statsdw`.`meta_last_ods_table`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p8statsdw`.`meta_last_ods_table` ;

CREATE  TABLE IF NOT EXISTS `p8statsdw`.`meta_last_ods_table` (
  `meta_last_ods_table_id` VARCHAR(100) NOT NULL COMMENT 'table name' ,
  `last_id` BIGINT UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`meta_last_ods_table_id`) )
ENGINE = MyISAM;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `p8statsdw`.`meta_semester`
-- -----------------------------------------------------
START TRANSACTION;
USE `p8statsdw`;
INSERT INTO `p8statsdw`.`meta_semester` (`meta_semester_id`, `semester`, `range`) VALUES ('default', '1', '^(1[0-2])|([1-3]|[9])$');
INSERT INTO `p8statsdw`.`meta_semester` (`meta_semester_id`, `semester`, `range`) VALUES ('default', '2', '^[4-7]$');

COMMIT;

-- -----------------------------------------------------
-- Data for table `p8statsdw`.`meta_last_ods_table`
-- -----------------------------------------------------
START TRANSACTION;
USE `p8statsdw`;
INSERT INTO `p8statsdw`.`meta_last_ods_table` (`meta_last_ods_table_id`, `last_id`) VALUES ('ods_efficiency', 0);

COMMIT;
