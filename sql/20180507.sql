-- New table provider_present
-- Creating table
CREATE TABLE provider_present (
    PROVIDER_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'PK/FK',
    PURCHASING_FAIR_id_purchasing_fair INT(11) UNSIGNED NOT NULL COMMENT 'PK/FK',
    CONSTRAINT pk_provider_present PRIMARY KEY (PROVIDER_id_enterprise, PURCHASING_FAIR_id_purchasing_fair)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Establishment of referential integrity (FK)
ALTER TABLE provider_present
ADD CONSTRAINT fk_pp_ent FOREIGN KEY (PROVIDER_id_enterprise) REFERENCES enterprise(id_enterprise);

ALTER TABLE provider_present
ADD CONSTRAINT fk_pp_pf FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

-- For primary keys the index is created automatically
-- There is no index here on foreign keys because the primary key is composed of two foreign keys

CREATE TABLE IF NOT EXISTS typeof_provider (
  id_typeof_provider int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  name_typeof_provider varchar(50) NOT NULL COMMENT 'Nom du type de Fournisseur',
  PRIMARY KEY (id_typeof_provider),
  UNIQUE KEY ux_name_typeof_provider (name_typeof_provider)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Typeof inserts
INSERT INTO typeof_provider (id_typeof_provider, name_typeof_provider) VALUES (NULL, 'Chaussure');
INSERT INTO typeof_provider (id_typeof_provider, name_typeof_provider) VALUES (NULL, 'Textile');

-- Enterprise table update
ALTER TABLE enterprise ADD TYPEOF_PROVIDER_id_typeof_provider INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'FK' AFTER panel_enterprise;

-- Establishment of referential integrity (FK)
ALTER TABLE enterprise
ADD CONSTRAINT fk_ent_typeof_pr FOREIGN KEY (TYPEOF_PROVIDER_id_typeof_provider) REFERENCES typeof_provider(id_typeof_provider);

-- Creating indexes on foreign key
CREATE INDEX ix_ent_typeof_pr ON enterprise (TYPEOF_PROVIDER_id_typeof_provider);

-- Drop Unique Constraint
ALTER TABLE enterprise
DROP INDEX ux_name_enterprise;

-- Add new UNIQUE Constraint
ALTER TABLE enterprise
ADD CONSTRAINT ux_name_ent_typeof_pr UNIQUE (name_enterprise, TYPEOF_PROVIDER_id_typeof_provider);

-- Update typeof providers for tests
UPDATE enterprise 
SET TYPEOF_PROVIDER_id_typeof_provider = RAND()+1 
WHERE PROFILE_id_profile = 1;

-- New table special_guest
-- Creating table
CREATE TABLE special_guest (
    id_special_guest INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    ENTERPRISE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    PURCHASING_FAIR_id_purchasing_fair INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    civility_special_guest VARCHAR(8) NOT NULL COMMENT 'Civilité',
    surname_special_guest VARCHAR(50) NOT NULL COMMENT 'Nom de famille',
    name_special_guest VARCHAR(50) NOT NULL COMMENT 'Prénom',
    days TEXT NOT NULL COMMENT 'Jours',
    CONSTRAINT pk_special_guest PRIMARY KEY (id_special_guest)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Establishment of referential integrity (FK)
ALTER TABLE special_guest
ADD CONSTRAINT fk_sp_guest_ent FOREIGN KEY (ENTERPRISE_id_enterprise) REFERENCES enterprise(id_enterprise);

ALTER TABLE special_guest
ADD CONSTRAINT fk_sp_guest_pf FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

-- Creating indexes on foreign key
CREATE INDEX ix_sp_guest_ent ON special_guest (ENTERPRISE_id_enterprise);
CREATE INDEX ix_sp_guest_pf ON special_guest (PURCHASING_FAIR_id_purchasing_fair);

-- Unique constraint to prevent duplicates
ALTER TABLE special_guest
ADD CONSTRAINT ux_ent_pf_surname_name UNIQUE (ENTERPRISE_id_enterprise, PURCHASING_FAIR_id_purchasing_fair, surname_special_guest, name_special_guest);