-- Creating tables
CREATE TABLE agent (
id_agent INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
civility_agent VARCHAR(8) NOT NULL COMMENT 'Civilité',
surname_agent VARCHAR(50) NOT NULL COMMENT 'Nom de famille',
name_agent VARCHAR(50) NOT NULL COMMENT 'Prénom',
address_line_1_agent VARCHAR(100) NOT NULL COMMENT 'Ligne Adresse 1',
address_line_2_agent VARCHAR(100) NOT NULL COMMENT 'Ligne Adresse 2',
providers_agent TEXT NOT NULL COMMENT 'Fournisseurs associés',
date_deletion_agent DATETIME DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
CONSTRAINT pk_agent PRIMARY KEY (id_agent)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Establishment of constraints UNIQUE
ALTER TABLE agent
ADD CONSTRAINT ux_agent UNIQUE (civility_agent, surname_agent, name_agent, address_line_1_agent, address_line_2_agent);

-- Data
INSERT INTO `agent` (`id_agent`, `civility_agent`, `surname_agent`, `name_agent`, `address_line_1_agent`, `address_line_2_agent`, `providers_agent`, `date_deletion_agent`) 
VALUES (NULL, 'Monsieur', 'GUERON', '.', '22 RUE DU COTEAU', '44880 SAUTRON', '123|63', NULL), 
(NULL, 'Monsieur', 'FEREZ', 'LOUIS-MICHEL', '40 RUE DES RUISSEAUX', '49300 CHOLET', '68|62|59', NULL), 
(NULL, 'Monsieur', 'IQUEL', 'Pascal Beg Menez', '3 ROUTE DE LA PLAGE', '29940 LA FORÊT-FOUESNANT', '125', NULL);