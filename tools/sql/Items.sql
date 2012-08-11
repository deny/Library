CREATE TABLE `item` (
	`i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`i_name` VARCHAR(128),
	`i_category` INT(10) UNSIGNED,
	`i_description` TEXT,
	`i_status` ENUM("free","borrowed"),
	PRIMARY KEY(`i_id`)
) ENGINE=InnoDB;
CREATE TABLE `category` (
	`c_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`c_name` VARCHAR(128),
	PRIMARY KEY(`c_id`)
) ENGINE=InnoDB;
CREATE TABLE `borrow` (
	`b_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`b_item` INT(10) UNSIGNED,
	`b_friend` INT(10) UNSIGNED,
	`b_start` INT(11),
	`b_end` INT(11),
	PRIMARY KEY(`b_id`)
) ENGINE=InnoDB;


ALTER TABLE `item`
	ADD CONSTRAINT `item_ibfk_i_category` FOREIGN KEY (`i_category`) REFERENCES `category` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `borrow`
	ADD CONSTRAINT `borrow_ibfk_b_item` FOREIGN KEY (`b_item`) REFERENCES `item` (`i_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `borrow_ibfk_b_friend` FOREIGN KEY (`b_friend`) REFERENCES `friend` (`f_id`) ON DELETE CASCADE ON UPDATE CASCADE;
