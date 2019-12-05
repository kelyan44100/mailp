-- Suppression table qrcode_scan orpheline existante pour en créer une nouvelle

DELETE FROM qrcode_scan;
DROP TABLE qrcode_scan;
CREATE TABLE qrcode_scan (
    id_qrcode_scan INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    PURCHASING_FAIR_id_purchasing_fair INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    ENTERPRISE_id_enterprise INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    PARTICIPANT_id_participant INT(11) UNSIGNED NOT NULL COMMENT 'FK',
    scan_datetime DATETIME NOT NULL COMMENT 'Datetime de scan',
    CONSTRAINT pk_qrcode_scan PRIMARY KEY (id_qrcode_scan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- FK
ALTER TABLE qrcode_scan
ADD CONSTRAINT fk_qrcode_scan_pf FOREIGN KEY (PURCHASING_FAIR_id_purchasing_fair) REFERENCES purchasing_fair(id_purchasing_fair);

ALTER TABLE qrcode_scan
ADD CONSTRAINT fk_qrcode_scan_enterprise FOREIGN KEY (ENTERPRISE_id_enterprise) REFERENCES enterprise(id_enterprise);

ALTER TABLE qrcode_scan
ADD CONSTRAINT fk_qrcode_scan_participant FOREIGN KEY (PARTICIPANT_id_participant) REFERENCES participant(id_participant);

-- INDEXES ON FK
CREATE INDEX ix_qrcode_scan_pf ON qrcode_scan(PURCHASING_FAIR_id_purchasing_fair);
CREATE INDEX ix_qrcode_scan_ent ON qrcode_scan(ENTERPRISE_id_enterprise);
CREATE INDEX ix_qrcode_scan_part ON qrcode_scan(PARTICIPANT_id_participant);


