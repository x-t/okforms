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
| form_style   | mediumtext    | YES  |     | NULL    |       |
+--------------+---------------+------+-----+---------+-------+
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

Table for bans:

+------------+-------------+------+-----+---------+-------+
| Field      | Type        | Null | Key | Default | Extra |
+------------+-------------+------+-----+---------+-------+
| ban_ip     | varchar(64) | NO   | PRI | NULL    |       |
| ban_reason | mediumtext  | NO   |     | NULL    |       |
| ban_vote   | tinyint(1)  | YES  |     | NULL    |       |
| ban_create | tinyint(1)  | YES  |     | NULL    |       |
+------------+-------------+------+-----+---------+-------+

Table for reports:

+---------+-------------+------+-----+---------+----------------+
| Field   | Type        | Null | Key | Default | Extra          |
+---------+-------------+------+-----+---------+----------------+
| id      | int(11)     | NO   | PRI | NULL    | auto_increment |
| ip      | varchar(64) | NO   |     | NULL    |                |
| form_id | varchar(24) | NO   |     | NULL    |                |
| reason  | mediumtext  | NO   |     | NULL    |                |
+---------+-------------+------+-----+---------+----------------+

```