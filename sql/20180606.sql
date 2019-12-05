ALTER TABLE `enterprise` 
ADD `postal_address` VARCHAR(200) NULL DEFAULT NULL AFTER `panel_enterprise`, 
ADD `postal_code` VARCHAR(5) NULL DEFAULT NULL AFTER `postal_address`, 
ADD `city` VARCHAR(100) NULL DEFAULT NULL AFTER `postal_code`, 
ADD `vat` VARCHAR(30) NULL DEFAULT NULL AFTER `city`;