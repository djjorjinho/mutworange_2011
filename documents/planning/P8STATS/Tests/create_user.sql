create user 'erasmusline'@'localhost' identified by 'orange';

GRANT SELECT , 
INSERT ,

UPDATE ,
DELETE ,
CREATE ,
DROP ,
INDEX ,
ALTER ,
CREATE TEMPORARY TABLES ,
LOCK TABLES ON  `p8statsdw` . * TO  'erasmusline'@'localhost' 
WITH GRANT OPTION ;

