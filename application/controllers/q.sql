ALTER TABLE `c_orders` ADD `whg` DECIMAL(20,2) NULL DEFAULT '0' AFTER `login_junk_for`, ADD `print_face_price` DECIMAL(20,2) NULL DEFAULT '0' AFTER `whg`, ADD `print_face_title` DECIMAL(20,2) NULL DEFAULT '0' AFTER `print_face_price`, ADD `table_json` LONGTEXT NULL DEFAULT NULL AFTER `print_face_title`;


ALTER TABLE `c_orders` ADD `final_farm` LONGTEXT NULL DEFAULT NULL AFTER `table_json`;


ALTER TABLE `c_orders` CHANGE `final_farm` `final_form` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;


ALTER TABLE `c_orders` ADD `stamps_cost` DECIMAL(20,2) NULL DEFAULT '0' AFTER `logo_cost`;


ALTER TABLE `c_orders` CHANGE `whg` `whg` VARCHAR(100) NULL DEFAULT NULL, CHANGE `print_face_title` `print_face_title` VARCHAR(100) NULL DEFAULT NULL;
