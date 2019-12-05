-- About encrypting the user password :
-- A SHA1() hash length is 160 bits or 40 hexadecimal characters.
-- The solution is to store the raw hash directly in binary form.
-- The type to use is no longer CHAR, but BINARY which stores binary strings. 
-- The length in bytes must be specified, so BINARY(20) for SHA1 (160 bits / 8 bits = 20 octets ; 1 octet = 8 bits). 
-- There is no longer any need to worry about the character set.
-- The space used is 20 bytes for SHA1 hash, which is 2 times less than the string version. 
-- The disadvantage is that it is necessary to think of using UNHEX or HEX as soon as one wants the hash in a printable form.
-- This is rarely the case.
-- Src : https://www.cloudconnected.fr/2009/04/12/quel-est-le-meilleur-type-pour-stocker-un-mot-de-passe-dans-une-table-mysql/

ALTER TABLE enterprise
ADD password_enterprise BINARY(20) NULL DEFAULT NULL COMMENT 'Mot de passe binaire' AFTER name_enterprise;

-- New table enterprise_contact
-- Creating table
CREATE TABLE enterprise_contact (
    id_enterprise_contact INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    civility_enterprise_contact VARCHAR(8) NOT NULL COMMENT 'Civilité',
    surname_enterprise_contact VARCHAR(50) NOT NULL COMMENT 'Nom de famille',
    name_enterprise_contact VARCHAR(50) NOT NULL COMMENT 'Prénom',
    email_enterprise_contact VARCHAR(100) NOT NULL COMMENT 'E-mail',
    registration_date DATETIME DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    ENTERPRISE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    CONSTRAINT pk_enterprise_contact PRIMARY KEY (id_enterprise_contact)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Establishment of referential integrity (FK)
ALTER TABLE enterprise_contact
ADD CONSTRAINT fk_ec_enterprise FOREIGN KEY (ENTERPRISE_id_enterprise) REFERENCES enterprise(id_enterprise);

-- Establishment of constraint UNIQUE
ALTER TABLE enterprise_contact
ADD CONSTRAINT ux_ec UNIQUE (ENTERPRISE_id_enterprise);

-- Creating indexes on foreign keys
CREATE INDEX ix_ec_ENTERPRISE_id_enterprise ON enterprise_contact (ENTERPRISE_id_enterprise);

-- if I wanted to set up an inheritance
-- Creating other tables (children)
-- The primary key of the child table is also a foreign key that references the primary key of the parent table !
-- The pk is automatically INDEX & UNIQUE (== NOT NULL)
-- SEE Eni resources -> 8_uml -> 'cours' page 107 in Adobe reader.

-- ProviderContact
-- CREATE TABLE provider_contact (
--     id_enterprise_contact INT(11) UNSIGNED NOT NULL COMMENT 'PK+FK',
--     CONSTRAINT pk_pc_enterprise_contact PRIMARY KEY (id_enterprise_contact)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ALTER TABLE provider_contact
-- ADD CONSTRAINT fk_pc_ec FOREIGN KEY (id_enterprise_contact) REFERENCES enterprise_contact(id_enterprise_contact) ON DELETE CASCADE;

-- StoreContact
-- CREATE TABLE store_contact (
--     id_enterprise_contact INT(11) UNSIGNED NOT NULL COMMENT 'PK+FK',
--     CONSTRAINT pk_sc_enterprise_contact PRIMARY KEY (id_enterprise_contact)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ALTER TABLE store_contact
-- ADD CONSTRAINT fk_sc_ec FOREIGN KEY (id_enterprise_contact) REFERENCES enterprise_contact(id_enterprise_contact) ON DELETE CASCADE;