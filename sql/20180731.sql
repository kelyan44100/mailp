-- 31/07/2018 : Merger of two Stores : OUDAIRIDIS and SODIROCHE

-- Disable these Stores (date_deletion IS NOT NULL)
UPDATE enterprise 
SET enterprise.date_deletion_enterprise = NOW() 
WHERE name_enterprise IN ("OUDAIRIDIS", "SODIROCHE");

-- Creation of a new Store : SODIROCHE-OUDAIRIDIS
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
"OUDAIRIDIS+SODIROCHE",
UNHEX(SHA1("12345678")),
"14.85",
NULL,
NULL,
NULL,
NULL,
NULL,
2,
85,
NULL);

-- Details of lunches per day : new column added in table lunch.
ALTER TABLE lunch ADD lunches_details JSON NULL COMMENT 'Détails par jour des repas prévus' AFTER lunches_canceled;