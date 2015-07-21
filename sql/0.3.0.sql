CREATE TABLE `language` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(500) NULL DEFAULT NULL,
	`origin_name` VARCHAR(500) NULL DEFAULT NULL,
	`code` VARCHAR(50) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
AUTO_INCREMENT=2
;

INSERT INTO `language` (`id`, `name`, `origin_name`, `code`) VALUES (1, 'English', 'English', 'en');
INSERT INTO `language` (`id`, `name`, `origin_name`, `code`) VALUES (2, 'Japanese', '日本語', 'jp');
INSERT INTO `language` (`id`, `name`, `origin_name`, `code`) VALUES (3, 'German', 'Deutsch', 'de_DE');
INSERT INTO `language` (`id`, `name`, `origin_name`, `code`) VALUES (4, 'French (France)', 'Français', 'fr_FR');
INSERT INTO `language` (`id`, `name`, `origin_name`, `code`) VALUES (5, 'French (Canada)', 'Français du Canada', 'fr_CA');
INSERT INTO `language` (`id`, `name`, `origin_name`, `code`) VALUES (6, 'Italian', 'Italiano', 'it_IT');
INSERT INTO `language` (`id`, `name`, `origin_name`, `code`) VALUES (7, 'Indonesian', 'Bahasa Indonesia', 'id_ID');
INSERT INTO `language` (`id`, `name`, `origin_name`, `code`) VALUES (8, 'Filipino', 'Filipino', 'fil');


ALTER TABLE `page`
ADD COLUMN `language_id` INT(11) NULL DEFAULT '1' AFTER `template_id`,
ADD CONSTRAINT `FK_page_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);


ALTER TABLE `page`
ADD COLUMN `sort` INT(11) NULL AFTER `language_id`;
