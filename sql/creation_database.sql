-- Creating the database
CREATE DATABASE IF NOT EXISTS pf_management
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

-- Database affected by the queries
USE pf_management;

-- Creating tables
CREATE TABLE participant (
    id_participant INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    civility_participant VARCHAR(8) NOT NULL COMMENT 'Civilité',
    surname_participant VARCHAR(50) NOT NULL COMMENT 'Nom de famille',
    name_participant VARCHAR(50) NOT NULL COMMENT 'Prénom',
    email_participant VARCHAR(100) NOT NULL COMMENT 'E-mail',
    date_deletion_participant DATETIME DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    CONSTRAINT pk_participant PRIMARY KEY (id_participant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE enterprise (
    id_enterprise INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    name_enterprise VARCHAR(50) NOT NULL COMMENT 'Nom de famille',
    PROFILE_id_profile INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    DEPARTMENT_id_department INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    date_deletion_enterprise DATETIME NULL DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    CONSTRAINT pk_enterprise PRIMARY KEY (id_enterprise)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE profile (
    id_profile INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    name_profile VARCHAR(50) NOT NULL COMMENT 'Nom du profil',
    date_deletion_profile DATETIME NULL DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    CONSTRAINT pk_profile PRIMARY KEY (id_profile)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Unavailability of Stores (Enterprise) => groupe of Participants, global
CREATE TABLE unavailability (
    id_unavailability INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    start_datetime DATETIME NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    end_datetime DATETIME NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    ENTERPRISE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    PURCHASING_FAIR_id_purchasing_fair INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    date_deletion_unavailability DATETIME NULL DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    CONSTRAINT pk_unavailability PRIMARY KEY (id_unavailability)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Unavailability of Salespersons (Participant) => individual, != unavailabilities of stores
CREATE TABLE unavailability_sp (
    id_unavailability_sp INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    start_datetime DATETIME NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    end_datetime DATETIME NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    PARTICIPANT_id_participant INT(11) UNSIGNED NOT NULL COMMENT 'PK',
    PURCHASING_FAIR_id_purchasing_fair INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    date_deletion_unavailability_sp DATETIME NULL DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    CONSTRAINT pk_unavailability_sp PRIMARY KEY (id_unavailability_sp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE purchasing_fair (
    id_purchasing_fair INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    name_purchasing_fair VARCHAR(50) NOT NULL COMMENT 'Nom affiché',
    hex_color CHAR(7) NOT NULL COMMENT 'Couleur de fond #123456',
    start_datetime DATETIME NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    end_datetime DATETIME NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    lunch_break TIME NOT NULL COMMENT 'HH:MM:SS',
    TYPEOF_PF_id_typeof_pf INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    registration_closing_date DATETIME NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    date_deletion_purchasing_fair DATETIME NULL DEFAULT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    CONSTRAINT pk_purchasing_fair PRIMARY KEY (id_purchasing_fair)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE log (
    id_log INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    ENTERPRISE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    ip_address VARCHAR(50) NOT NULL COMMENT '@IP',
    action_description VARCHAR(100) NOT NULL COMMENT 'Description',
    action_datetime DATETIME NOT NULL COMMENT 'AAAA-MM-JJ HH:MM:SS',
    CONSTRAINT pk_log PRIMARY KEY (id_log)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE requirement (
    id_requirement INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    ENTERPRISE_STORE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    ENTERPRISE_PROVIDER_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    PURCHASING_FAIR_id_purchasing_fair INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    number_of_hours TINYINT UNSIGNED NOT NULL COMMENT 'Nb heures',
    CONSTRAINT pk_requirement PRIMARY KEY (id_requirement)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE department (
    id_department INT(11) UNSIGNED NOT NULL COMMENT 'PK',
    name_department VARCHAR(255) NOT NULL COMMENT 'Nom',
    CONSTRAINT pk_department PRIMARY KEY (id_department)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE assignment_participant_enterprise (
    PARTICIPANT_id_participant INT(11) UNSIGNED NOT NULL COMMENT 'PK participant',
    ENTERPRISE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'PK enterprise',
    CONSTRAINT pk_assignment_participant_enterprise PRIMARY KEY (PARTICIPANT_id_participant, ENTERPRISE_id_enterprise)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE assignment_sp_store (
    PARTICIPANT_id_participant INT(11) UNSIGNED NOT NULL COMMENT 'PK salesperson',
    ENTERPRISE_STORE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'PK store',
    ENTERPRISE_PROVIDER_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'PK provider',
    PURCHASING_FAIR_id_purchasing_fair INT(11) UNSIGNED NOT NULL COMMENT 'PK purchasing_fair',
    CONSTRAINT pk_assignment_sp_store PRIMARY KEY (PARTICIPANT_id_participant, ENTERPRISE_STORE_id_enterprise, ENTERPRISE_PROVIDER_id_enterprise, PURCHASING_FAIR_id_purchasing_fair)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE assignment_participant_department (
    PARTICIPANT_id_participant INT(11) UNSIGNED NOT NULL COMMENT 'PK participant',
    DEPARTMENT_id_department INT(11) UNSIGNED NOT NULL COMMENT 'PK department',
    CONSTRAINT pk_assignment_participant_department PRIMARY KEY (PARTICIPANT_id_participant, DEPARTMENT_id_department)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE participation (
    PARTICIPANT_id_participant INT(11) UNSIGNED NOT NULL COMMENT 'PK participant',
    PURCHASING_FAIR_id_purchasing_fair INT(11) UNSIGNED NOT NULL COMMENT 'PK purchasing_fair',
    password_participant CHAR(6) NULL DEFAULT NULL COMMENT 'Mot de passe à six chiffres',
    lunch TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = NON / 1 = OUI',
    CONSTRAINT pk_participation PRIMARY KEY (PARTICIPANT_id_participant, PURCHASING_FAIR_id_purchasing_fair)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE typeof_pf (
    id_typeof_pf INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    name_typeof_pf VARCHAR(50) NOT NULL COMMENT 'Nom du type de salon',
    CONSTRAINT pk_typeof_pf PRIMARY KEY (id_typeof_pf)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE qrcode_scan (
    content VARCHAR(250) NOT NULL COMMENT 'Contenu du QRCode',
    scan_datetime DATETIME NOT NULL COMMENT 'Date de scan QRCode'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Establishment of referential integrity (FK)

ALTER TABLE purchasing_fair
ADD CONSTRAINT fk_purchasing_fair_typeof_pf FOREIGN KEY (TYPEOF_PF_id_typeof_pf) REFERENCES typeof_pf(id_typeof_pf);

ALTER TABLE enterprise
ADD CONSTRAINT fk_enterprise_department FOREIGN KEY (DEPARTMENT_id_department) REFERENCES department(id_department),
ADD CONSTRAINT fk_enterprise_profile  FOREIGN KEY (PROFILE_id_profile) REFERENCES profile(id_profile);

ALTER TABLE unavailability
ADD CONSTRAINT fk_unavailability_enterprise  FOREIGN KEY (ENTERPRISE_id_enterprise) REFERENCES enterprise(id_enterprise),
ADD CONSTRAINT fk_unavailability_purchasing_fair  FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

ALTER TABLE unavailability_sp
ADD CONSTRAINT fk_unavailability_sp_participant  FOREIGN KEY (PARTICIPANT_id_participant) REFERENCES participant(id_participant),
ADD CONSTRAINT fk_unavailability_sp_purchasing_fair  FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

ALTER TABLE log
ADD CONSTRAINT fk_log_enterprise  FOREIGN KEY (ENTERPRISE_id_enterprise) REFERENCES enterprise(id_enterprise);

ALTER TABLE requirement
ADD CONSTRAINT fk_requirement_enterprise_store  FOREIGN KEY (ENTERPRISE_STORE_id_enterprise) REFERENCES enterprise(id_enterprise),
ADD CONSTRAINT fk_requirement_enterprise_provider  FOREIGN KEY (ENTERPRISE_PROVIDER_id_enterprise) REFERENCES enterprise(id_enterprise),
ADD CONSTRAINT fk_requirement_purchasing_fair  FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

ALTER TABLE assignment_participant_enterprise
ADD CONSTRAINT fk_assignment_participant_enterprise_A FOREIGN KEY (PARTICIPANT_id_participant) REFERENCES participant(id_participant),
ADD CONSTRAINT fk_assignment_participant_enterprise_B FOREIGN KEY (ENTERPRISE_id_enterprise) REFERENCES enterprise(id_enterprise);

ALTER TABLE assignment_participant_department
ADD CONSTRAINT fk_assignment_participant_department_A FOREIGN KEY (PARTICIPANT_id_participant) REFERENCES participant(id_participant),
ADD CONSTRAINT fk_assignment_participant_department_B FOREIGN KEY (DEPARTMENT_id_department) REFERENCES department(id_department);

ALTER TABLE participation
ADD CONSTRAINT fk_participation_participant FOREIGN KEY (PARTICIPANT_id_participant) REFERENCES participant(id_participant),
ADD CONSTRAINT fk_participation_purchasing_fair FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

ALTER TABLE assignment_sp_store
ADD CONSTRAINT fk_assignment_sp_store_participant FOREIGN KEY (PARTICIPANT_id_participant) REFERENCES participant(id_participant),
ADD CONSTRAINT fk_ass_enterprise_store  FOREIGN KEY (ENTERPRISE_STORE_id_enterprise) REFERENCES enterprise(id_enterprise),
ADD CONSTRAINT fk_ass_enterprise_provider  FOREIGN KEY (ENTERPRISE_PROVIDER_id_enterprise) REFERENCES enterprise(id_enterprise),
ADD CONSTRAINT fk_assignment_sp_store_pf FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

-- Establishment of constraints UNIQUE
ALTER TABLE participant
ADD CONSTRAINT ux_email_participant UNIQUE (email_participant);

ALTER TABLE enterprise
ADD CONSTRAINT ux_name_enterprise UNIQUE (name_enterprise);

ALTER TABLE profile
ADD CONSTRAINT ux_name_profile UNIQUE (name_profile);

ALTER TABLE purchasing_fair
ADD CONSTRAINT ux_name_start_end_datetime UNIQUE (name_purchasing_fair, start_datetime, end_datetime);

ALTER TABLE unavailability
ADD CONSTRAINT ux_enterprise_start_end_datetime UNIQUE (start_datetime, end_datetime, ENTERPRISE_id_enterprise, PURCHASING_FAIR_id_purchasing_fair);

ALTER TABLE unavailability_sp
ADD CONSTRAINT ux_participant_start_end_datetime UNIQUE (start_datetime, end_datetime, PARTICIPANT_id_participant, PURCHASING_FAIR_id_purchasing_fair);

ALTER TABLE requirement
ADD CONSTRAINT ux_requirement UNIQUE (ENTERPRISE_STORE_id_enterprise, ENTERPRISE_PROVIDER_id_enterprise, PURCHASING_FAIR_id_purchasing_fair);

ALTER TABLE typeof_pf
ADD CONSTRAINT ux_name_typeof_pf UNIQUE (name_typeof_pf);

-- Creating indexes on foreign keys
-- CREATE INDEX ix_participant_ENTERPRISE_id_enterprise ON participant (ENTERPRISE_id_enterprise);
CREATE INDEX ix_enterprise_PROFILE_id_profile ON enterprise (PROFILE_id_profile);
CREATE INDEX ix_enterprise_DEPARTMENT_id_department ON enterprise (DEPARTMENT_id_department);
CREATE INDEX ix_unavailability_ENTERPRISE_id_enterprise ON unavailability (ENTERPRISE_id_enterprise);
CREATE INDEX ix_unavailability_PURCHASING_FAIR_id_purchasing_fair ON unavailability (PURCHASING_FAIR_id_purchasing_fair);
CREATE INDEX ix_unavailability_sp_PARTICIPANT_id_participant ON unavailability_sp (PARTICIPANT_id_participant);
CREATE INDEX ix_unavailability_sp_PF_id_pf ON unavailability_sp (PURCHASING_FAIR_id_purchasing_fair);
CREATE INDEX ix_log_ENTERPRISE_id_enterprise ON log (ENTERPRISE_id_enterprise);
CREATE INDEX ix_requirement_ENTERPRISE_STORE_id_enterprise ON requirement (ENTERPRISE_STORE_id_enterprise);
CREATE INDEX ix_requirement_ENTERPRISE_PROVIDER_id_enterprise ON requirement (ENTERPRISE_PROVIDER_id_enterprise);
CREATE INDEX ix_requirement_PURCHASING_FAIR_id_purchasing_fair ON requirement (PURCHASING_FAIR_id_purchasing_fair);
CREATE INDEX ix_assignment_participant_enterprise_A ON assignment_participant_enterprise (PARTICIPANT_id_participant);
CREATE INDEX ix_assignment_participant_enterprise_B ON assignment_participant_enterprise (ENTERPRISE_id_enterprise);
CREATE INDEX ix_assignment_participant_department_A ON assignment_participant_department (PARTICIPANT_id_participant);
CREATE INDEX ix_assignment_participant_department_B ON assignment_participant_department (DEPARTMENT_id_department);
CREATE INDEX ix_participation_PARTICIPANT_id_participant ON participation (PARTICIPANT_id_participant);
CREATE INDEX ix_participation_PURCHASING_FAIR_id_purchasing_fair ON participation (PURCHASING_FAIR_id_purchasing_fair);
CREATE INDEX ix_pf_TYPEOF_PF_id_typeof_pf ON purchasing_fair(TYPEOF_PF_id_typeof_pf);
CREATE INDEX ix_assign_sp_store_participant ON assignment_sp_store(PARTICIPANT_id_participant);
CREATE INDEX ix_ass_enterprise_store ON assignment_sp_store(ENTERPRISE_STORE_id_enterprise);
CREATE INDEX ix_ass_enterprise_provider ON assignment_sp_store(ENTERPRISE_PROVIDER_id_enterprise);
CREATE INDEX ix_assign_sp_store_pf ON assignment_sp_store(PURCHASING_FAIR_id_purchasing_fair);