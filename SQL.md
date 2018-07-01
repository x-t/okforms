```

Table for Forms:

+--------------+---------------+------+-----+---------+-------+
| Field        | Type          | Null | Key | Default | Extra |
+--------------+---------------+------+-----+---------+-------+
| form_id      | varchar([1])  | NO   | PRI | NULL    |       |
| form_ip      | varchar([1])  | NO   |     | NULL    |       |
| form_type    | varchar([1])  | NO   |     | NULL    |       |
| form_made    | int(11)       | NO   |     | NULL    |       |
| form_expires | int(11)       | NO   |     | NULL    |       |
| form_q       | [0]           | YES  |     | NULL    |       |
| form_a       | [0]           | YES  |     | NULL    |       |
| form_pass    | varchar([1])  | NO   |     | NULL    |       |
| form_title   | varchar([1])  | NO   |     | NULL    |       |
| form_desc    | varchar([1])  | YES  |     | NULL    |       |
| form_sameip  | tinyint(1)    | NO   |     | NULL    |       |
+--------------+---------------+------+-----+---------+-------+

[0] This can be either JSON or any text type, such as mediumtext or varchar(N)
[1] These are the values you set from vars.php

---------- EXAMPLE ----------
+--------------+---------------+------+-----+---------+-------+
| Field        | Type          | Null | Key | Default | Extra |
+--------------+---------------+------+-----+---------+-------+
| form_id      | varchar(16)   | NO   | PRI | NULL    |       |
| form_ip      | varchar(64)   | NO   |     | NULL    |       |
| form_type    | varchar(10)   | NO   |     | NULL    |       |
| form_made    | int(11)       | NO   |     | NULL    |       |
| form_expires | int(11)       | NO   |     | NULL    |       |
| form_pass    | varchar(1024) | NO   |     | NULL    |       |
| form_title   | varchar(2048) | NO   |     | NULL    |       |
| form_desc    | varchar(4096) | YES  |     | NULL    |       |
| form_sameip  | tinyint(1)    | NO   |     | NULL    |       |
| form_q       | mediumtext    | YES  |     | NULL    |       |
| form_a       | mediumtext    | YES  |     | NULL    |       |
+--------------+---------------+------+-----+---------+-------+

CREATE TABLE `forms`.`form` ( `form_id` VARCHAR(16) NOT NULL , `form_ip` VARCHAR(64) NOT NULL , `form_type` VARCHAR(10) NOT NULL , `form_made` INT NOT NULL , `form_expires` INT NOT NULL , `form_pass` VARCHAR(1024) NOT NULL , `form_title` VARCHAR(2048) NOT NULL , `form_desc` VARCHAR(4096) NULL , `form_sameip` TINYINT NOT NULL , `form_q` MEDIUMTEXT NULL , `form_a` MEDIUMTEXT NULL, PRIMARY KEY (`form_id`) );

-----------------------------

Table for logs:

+-------+---------------+------+-----+---------+----------------+
| Field | Type          | Null | Key | Default | Extra          |
+-------+---------------+------+-----+---------+----------------+
| id    | int(11)       | NO   | PRI | NULL    | auto_increment |
| type  | varchar(1024) | NO   |     | NULL    |                |
| ip    | varchar(64)   | NO   |     | NULL    |                |
| descr | mediumtext    | YES  |     | NULL    |                |
| time  | int(11)       | NO   |     | NULL    |                |
+-------+---------------+------+-----+---------+----------------+

CREATE TABLE `forms`.`logger` ( `id` INT NOT NULL AUTO_INCREMENT , `type` VARCHAR(1024) NOT NULL , `ip` VARCHAR(64) NOT NULL , `descr` MEDIUMTEXT NULL , `time` INT NOT NULL , PRIMARY KEY (`id`));

Table for bans:

+------------+-------------+------+-----+---------+-------+
| Field      | Type        | Null | Key | Default | Extra |
+------------+-------------+------+-----+---------+-------+
| ban_ip     | varchar(64) | NO   | PRI | NULL    |       |
| ban_reason | mediumtext  | NO   |     | NULL    |       |
| ban_vote   | tinyint(1)  | YES  |     | NULL    |       |
| ban_create | tinyint(1)  | YES  |     | NULL    |       |
+------------+-------------+------+-----+---------+-------+

CREATE TABLE `forms`.`bans` ( `ban_ip` VARCHAR(64) NOT NULL , `ban_reason` MEDIUMTEXT NOT NULL , `ban_vote` TINYINT NULL , `ban_create` TINYINT NULL , PRIMARY KEY (`ban_ip`));

Table for reports:

+---------+-------------+------+-----+---------+----------------+
| Field   | Type        | Null | Key | Default | Extra          |
+---------+-------------+------+-----+---------+----------------+
| id      | int(11)     | NO   | PRI | NULL    | auto_increment |
| ip      | varchar(64) | NO   |     | NULL    |                |
| form_id | varchar(24) | NO   |     | NULL    |                |
| reason  | mediumtext  | NO   |     | NULL    |                |
+---------+-------------+------+-----+---------+----------------+

CREATE TABLE `forms`.`reports` ( `id` INT NOT NULL AUTO_INCREMENT , `ip` VARCHAR(64) NOT NULL , `form_id` VARCHAR(24) NOT NULL , `reason` MEDIUMTEXT NOT NULL , PRIMARY KEY (`id`));

```