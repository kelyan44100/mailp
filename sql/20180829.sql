-- Separate CDA49 & Gouronnières
UPDATE `enterprise` SET `date_deletion_enterprise` = NULL WHERE `enterprise`.`id_enterprise` = 12;
UPDATE `enterprise` SET `date_deletion_enterprise` = NULL WHERE `enterprise`.`id_enterprise` = 21;

-- Hide previous association
UPDATE  `enterprise` SET  `date_deletion_enterprise` =  '2018-08-29 09:25:00' WHERE  `enterprise`.`id_enterprise` =130;

