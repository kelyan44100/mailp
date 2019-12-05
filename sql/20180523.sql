-- Ajout table lunch
CREATE TABLE IF NOT EXISTS lunch (
    ENTERPRISE_id_enterprise int(11) unsigned NOT NULL COMMENT 'PK enterprise',
    PURCHASING_FAIR_id_purchasing_fair int(11) unsigned NOT NULL COMMENT 'PK purchasing_fair',
    lunches_planned tinyint(3) unsigned NOT NULL COMMENT 'Nb repas prévus',
    lunches_canceled tinyint(3) unsigned NOT NULL COMMENT 'Nb repas annulés',
    CONSTRAINT pk_lunch PRIMARY KEY (ENTERPRISE_id_enterprise, PURCHASING_FAIR_id_purchasing_fair)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Establishment of referential integrity (FK)
ALTER TABLE lunch
ADD CONSTRAINT fk_lunch_enterprise FOREIGN KEY (ENTERPRISE_id_enterprise) REFERENCES enterprise(id_enterprise);

ALTER TABLE lunch
ADD CONSTRAINT fk_lunch_purchasing_fair FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

-- INDEX ON FK ARE OK DUE TO PK ON THEM