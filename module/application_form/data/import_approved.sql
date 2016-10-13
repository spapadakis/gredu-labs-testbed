
CREATE TABLE approved_ids(
id INT NOT NULL AUTO_INCREMENT ,
PRIMARY KEY ( id )
);

LOAD DATA LOCAL INFILE '/home/akatsi/schapproved.csv' 
INTO TABLE approved_ids 
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n';


UPDATE applicationform SET approved =1,approved_date = curdate() WHERE id IN (
SELECT id
FROM approved_ids
);

DROP TABLE approved_ids;
