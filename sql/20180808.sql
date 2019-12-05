-- Table "present" (participants présents)
CREATE TABLE IF NOT EXISTS present (
    ENTERPRISE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'PK enterprise',
    PARTICIPANT_id_participant INT(11) UNSIGNED NOT NULL COMMENT 'PK participant',
    PURCHASING_FAIR_id_purchasing_fair INT(11) UNSIGNED NOT NULL COMMENT 'PK participant',
    present_details JSON NULL COMMENT 'Jours de présence',
    CONSTRAINT pk_present PRIMARY KEY (ENTERPRISE_id_enterprise, PARTICIPANT_id_participant, PURCHASING_FAIR_id_purchasing_fair)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Establishment of referential integrity (FK)
ALTER TABLE present
ADD CONSTRAINT fk_present_enterprise FOREIGN KEY (ENTERPRISE_id_enterprise) REFERENCES enterprise(id_enterprise);
ALTER TABLE present
ADD CONSTRAINT fk_present_participant FOREIGN KEY (PARTICIPANT_id_participant) REFERENCES participant(id_participant);
ALTER TABLE present
ADD CONSTRAINT fk_present_purchasing_fair FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

-- INDEX ON FK IS OK DUE TO PK ON THEM