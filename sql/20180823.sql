-- Update for 20180809.sql
-- TEXT: 65,535 characters -> 64 KB
-- LONGTEXT: 4,294,967,295 characters -> 4 GB
ALTER TABLE `present` CHANGE `present_details` `present_details` LONGTEXT NULL DEFAULT NULL COMMENT 'Jours de présence';
ALTER TABLE `lunch` CHANGE `lunches_details` `lunches_details` LONGTEXT NULL DEFAULT NULL COMMENT 'Détails par jour des repas prévus';