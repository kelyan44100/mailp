ALTER TABLE participant DROP INDEX ux_email_participant;
ALTER TABLE participant
ADD CONSTRAINT ux_email_participant UNIQUE (civility_participant, surname_participant, name_participant, email_participant);