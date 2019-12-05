-- GOURONNIERES + CDA ASSOCIATION
-- Disable these Stores (date_deletion IS NOT NULL)
UPDATE enterprise 
SET enterprise.date_deletion_enterprise = NOW() 
WHERE name_enterprise IN ("GOURONNIERES", "CDA49");

-- Creation of a new Store : GOURONNIERES-CDA49
INSERT INTO enterprise(
name_enterprise, 
password_enterprise, 
panel_enterprise, 
postal_address, 
postal_code, 
city, 
vat, 
TYPEOF_PROVIDER_id_typeof_provider, 
PROFILE_id_profile, 
DEPARTMENT_id_department, 
date_deletion_enterprise ) 
VALUES (
"GOURONNIERES+CDA49",
UNHEX(SHA1("12345678")),
"15.49",
NULL,
NULL,
NULL,
NULL,
NULL,
2,
49,
NULL);

-- New stores name for STORE_NAME NUMBER
UPDATE enterprise SET name_enterprise = 'LAURY-CHALONGES 1 - Damien' WHERE enterprise.id_enterprise = 26;
UPDATE enterprise SET name_enterprise = 'LAURY-CHALONGES 2 - Valérie' WHERE enterprise.id_enterprise = 115;
UPDATE enterprise SET name_enterprise = 'LAURY-CHALONGES 3 - Éric' WHERE enterprise.id_enterprise = 116;
UPDATE enterprise SET name_enterprise = 'SODIRENNES 1 - Armelle' WHERE enterprise.id_enterprise = 39;
UPDATE enterprise SET name_enterprise = 'SODIRENNES 2 - Odile' WHERE enterprise.id_enterprise = 117;
UPDATE enterprise SET name_enterprise = 'SODIRETZ 1 - Nathalie' WHERE enterprise.id_enterprise = 40;
UPDATE enterprise SET name_enterprise = 'SODIRETZ 2 - Sylvie' WHERE enterprise.id_enterprise = 119;
UPDATE enterprise SET name_enterprise = 'SODIRETZ 3 - Katerine' WHERE enterprise.id_enterprise = 120;

-- Table "store_workforce" (effectif entreprise)
CREATE TABLE IF NOT EXISTS store_workforce (
    ENTERPRISE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'PK enterprise',
    outer_clothing TINYINT(1) UNSIGNED NOT NULL COMMENT 'Vêtement de dessus',
    under_clothing TINYINT(1) UNSIGNED NOT NULL COMMENT 'Vêtement de dessous',
    shoes TINYINT(1) UNSIGNED NOT NULL COMMENT 'Chaussure',
    CONSTRAINT pk_store_workforce PRIMARY KEY (ENTERPRISE_id_enterprise)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Establishment of referential integrity (FK)
ALTER TABLE store_workforce
ADD CONSTRAINT fk_store_workforce_enterprise FOREIGN KEY (ENTERPRISE_id_enterprise) REFERENCES enterprise(id_enterprise);

-- INDEX ON FK IS OK DUE TO PK ON THEM

-- Contenu table store_workforce
INSERT INTO `store_workforce` (`ENTERPRISE_id_enterprise`, `outer_clothing`, `under_clothing`, `shoes`) VALUES
(1, 1, 2, 1),
(2, 2, 2, 2),
(3, 2, 2, 2),
(4, 0, 0, 0),
(5, 1, 1, 1),
(6, 1, 1, 1),
(7, 2, 2, 2),
(8, 0, 0, 0),
(9, 2, 2, 2),
(10, 2, 2, 2),
(11, 2, 2, 2),
(12, 0, 0, 0),
(13, 1, 1, 1),
(14, 2, 2, 2),
(15, 2, 2, 2),
(16, 2, 2, 2),
(17, 2, 2, 2),
(18, 1, 1, 1),
(19, 2, 2, 2),
(20, 2, 2, 2),
(21, 0, 0, 0),
(22, 2, 2, 2),
(23, 0, 0, 0),
(24, 2, 2, 2),
(25, 1, 1, 1),
(26, 0, 1, 0),
(27, 2, 2, 2),
(28, 0, 0, 0),
(29, 0, 0, 0),
(30, 1, 1, 1),
(31, 1, 1, 1),
(32, 2, 2, 2),
(33, 1, 1, 1),
(34, 2, 2, 2),
(35, 2, 2, 2),
(36, 2, 2, 2),
(37, 2, 2, 2),
(38, 0, 0, 0),
(39, 0, 1, 0),
(40, 1, 1, 0),
(41, 0, 0, 0),
(42, 1, 1, 1),
(43, 1, 1, 1),
(44, 1, 1, 1),
(45, 2, 2, 2),
(46, 1, 1, 1),
(47, 1, 1, 1),
(48, 2, 2, 2),
(115, 1, 0, 0),
(116, 1, 0, 2),
(117, 2, 1, 2),
(118, 0, 0, 0),
(119, 1, 1, 0),
(120, 0, 0, 1),
(131, 2, 2, 2),
(132, 2, 2, 2),
(133, 3, 2, 3);

DELETE FROM lunch;