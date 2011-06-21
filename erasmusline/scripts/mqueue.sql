DROP TABLE IF EXISTS `mqueue`;
CREATE TABLE IF NOT EXISTS `mqueue` (
  `mqueue_id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` datetime not null,
  `tag` varchar(100),
  `module` varchar(100),
  `method` varchar(200),
  `params` text,
  `finished` tinyint default 0,
  `finish_date` datetime,
  PRIMARY KEY (`mqueue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
