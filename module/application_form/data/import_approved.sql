
CREATE TABLE approved_ids(
id INT NOT NULL AUTO_INCREMENT ,
PRIMARY KEY ( id )
);

CREATE TABLE approvedapplications(
id INT NOT NULL AUTO_INCREMENT ,
submitted int,
PRIMARY KEY ( id )
);

LOAD DATA LOCAL INFILE '/tmp/schapproved.csv' 
INTO TABLE approved_ids 
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n';

INSERT INTO approvedapplications
SELECT id, max(submitted)
FROM applicationform
WHERE school_id IN (
SELECT id
FROM approved_ids)
group by school_id;

UPDATE applicationform SET approved =1,approved_date = curdate() WHERE id IN (
SELECT id
FROM approvedapplications
);

DROP TABLE approved_ids;

DROP TABLE approvedapplications
