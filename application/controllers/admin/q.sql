ALTER TABLE `purchases` ADD `size` VARCHAR(100) NULL DEFAULT NULL AFTER `sku`;


ALTER TABLE `c_orders` CHANGE `production_deadline` `production_deadline` DATETIME NULL DEFAULT NULL;
