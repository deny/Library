CREATE TABLE `friend` (
	`f_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`f_name` VARCHAR(80),
	`f_surname` VARCHAR(80),
	PRIMARY KEY(`f_id`)
) ENGINE=InnoDB;
CREATE TABLE `friend_c_details` (
	`fcd_id` INT(10) UNSIGNED,
	`fcd_email` VARCHAR(50),
	`fcd_phone` VARCHAR(12),
	`fcd_city` VARCHAR(80),
	`fcd_address` VARCHAR(120),
	PRIMARY KEY(`fcd_id`)
) ENGINE=InnoDB;
CREATE TABLE `group` (
	`g_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`g_name` VARCHAR(80),
	PRIMARY KEY(`g_id`)
) ENGINE=InnoDB;

CREATE TABLE `group_j_peoples` (
	`g_id` INT(10) UNSIGNED,
	`gjp_id` INT(10) UNSIGNED,
	UNIQUE KEY `g_id` (`g_id`,`gjp_id`),
	KEY `gjp_id` (`gjp_id`)
) ENGINE=InnoDB;

ALTER TABLE `friend_c_details`
	ADD CONSTRAINT `friend_c_details_ibfk_fcd_id` FOREIGN KEY (`fcd_id`) REFERENCES `friend` (`f_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `group_j_peoples`
	ADD CONSTRAINT `group_j_peoples_ibfk_g_id` FOREIGN KEY (`g_id`) REFERENCES `group` (`g_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `group_j_peoples_ibfk_gjp_id` FOREIGN KEY (`gjp_id`) REFERENCES `friend` (`f_id`) ON DELETE CASCADE ON UPDATE CASCADE;
