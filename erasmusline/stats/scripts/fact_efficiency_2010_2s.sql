/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Version : 50144
 Source Host           : localhost
 Source Database       : p8statsdw

 Target Server Version : 50144
 File Encoding         : utf-8

 Date: 06/06/2011 17:21:14 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `fact_efficiency_2010_2s`
-- ----------------------------
DROP TABLE IF EXISTS `fact_efficiency_2010_2s`;
CREATE TABLE `fact_efficiency_2010_2s` (
  `dim_date_id` bigint(20) unsigned NOT NULL,
  `dim_institution_id` int(11) NOT NULL,
  `dim_institution_host_id` int(11) NOT NULL,
  `dim_phase_id` varchar(20) NOT NULL,
  `dim_mobility_id` char(6) NOT NULL,
  `dim_gender_id` enum('M','F','O') NOT NULL,
  `avg_response_days` smallint(5) unsigned DEFAULT '0',
  `max_response_days` smallint(5) unsigned DEFAULT '0',
  `min_response_days` smallint(5) unsigned DEFAULT '0',
  `val_participants` int(10) unsigned DEFAULT '0',
  `perc_students` smallint(6) DEFAULT '0',
  `lodging_available` int(10) unsigned DEFAULT '0',
  `perc_lodging` smallint(6) DEFAULT '0',
  `response_days` smallint(5) unsigned DEFAULT NULL,
  `student_lodging` tinyint(3) unsigned DEFAULT NULL,
  `rejected` tinyint(3) unsigned DEFAULT NULL,
  `student` tinyint(3) unsigned DEFAULT NULL default '0',
  KEY `fk_fact_efficiency_dim_enddate` (`dim_date_id`),
  KEY `fk_fact_efficiency_dim_host_institution` (`dim_institution_host_id`),
  KEY `fk_fact_efficiency_dim_home_institution` (`dim_institution_id`),
  KEY `idx_efficiency_home` (`dim_date_id`,`dim_institution_id`,`dim_phase_id`,`dim_mobility_id`,`dim_gender_id`) USING BTREE,
  KEY `idx_efficiency_host` (`dim_date_id`,`dim_institution_host_id`,`dim_phase_id`,`dim_mobility_id`,`dim_gender_id`),
  KEY `fk_fact_efficiency_dim_mobility1` (`dim_mobility_id`),
  KEY `fk_fact_efficiency_dim_phase1` (`dim_phase_id`),
  KEY `fk_fact_efficiency_dim_gender1` (`dim_gender_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `fact_efficiency_2010_2s`
-- ----------------------------
BEGIN;
INSERT INTO `fact_efficiency_2010_2s` VALUES
('4', '1', '2', 'precandidate', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','1'),
('4', '1', '2', 'applicform', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '2', 'lagreement', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '2', 'contract', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '2', 'accomodation', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '2', 'certarrival', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '4', 'precandidate', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','1'),
('4', '1', '4', 'applicform', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '4', 'lagreement', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '4', 'contract', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '4', 'accomodation', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '4', 'certarrival', 'study', 'M', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '3', 'precandidate', 'both', 'F', '45', '17', '0', '5', '0', '25', '0', '2', '1', '0','1'),
('4', '1', '3', 'applicform', 'both', 'F', '45', '17', '0', '5', '0', '25', '0', '2', '1', '0','0'),
('4', '1', '3', 'lagreement', 'both', 'F', '45', '17', '0', '5', '0', '25', '0', '2', '1', '0','0'),
('4', '1', '3', 'contract', 'both', 'F', '45', '17', '0', '5', '0', '25', '0', '2', '1', '1','0'),
('4', '1', '4', 'precandidate', 'study', 'F', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','1'),
('4', '1', '4', 'applicform', 'study', 'F', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '4', 'lagreement', 'study', 'F', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '4', 'contract', 'study', 'F', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '4', 'accomodation', 'study', 'F', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '4', 'certarrival', 'study', 'F', '45', '17', '0', '5', '0', '25', '0', '10', '1', '0','0'),
('4', '1', '3', 'precandidate', 'both', 'F', '45', '17', '0', '5', '0', '25', '0', '7', '1', '0','1'),
('4', '1', '3', 'applicform', 'both', 'F', '45', '17', '0', '5', '0', '25', '0', '12', '1', '0','0'),
('4', '1', '3', 'lagreement', 'both', 'F', '45', '17', '0', '5', '0', '25', '0', '17', '1', '1','0');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
