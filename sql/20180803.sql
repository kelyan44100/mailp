-- DEBUG LOF FILE AFTER PLANNING GENERATION
-- ROOR W/ BRIERE (STORE) & TERRE DE MARINS (PROVIDER)

-- SELECT * FROM requirement WHERE number_of_hours LIKE "%30"

UPDATE requirement SET number_of_hours = REPLACE(number_of_hours, 30, 50) WHERE number_of_hours LIKE "%30"; 
-- 3 ROWS AFFECTED

-- BREVIDIS + SODIPOR ASSOCIATION
-- Disable these Stores (date_deletion IS NOT NULL)
UPDATE enterprise 
SET enterprise.date_deletion_enterprise = NOW() 
WHERE name_enterprise IN ("BREVIDIS", "SODIPOR");

-- Creation of a new Store : BREVIDIS-SODIPOR
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
"BREVIDIS+SODIPOR",
UNHEX(SHA1("12345678")),
"26.44",
NULL,
NULL,
NULL,
NULL,
NULL,
2,
44,
NULL);

-- Agents
DELETE FROM agent;

INSERT INTO `agent` (`id_agent`, `civility_agent`, `surname_agent`, `name_agent`, `address_line_1_agent`, `address_line_2_agent`, `providers_agent`, `date_deletion_agent`) VALUES
(1, 'Monsieur', 'GUERON', 'Jean-Jacques', '22 RUE DU COTEAU', '44880 SAUTRON', '123', NULL),
(2, 'Monsieur', 'FERREZ', 'Louis-Michel', '40 RUE DES RUISSEAUX', '49300 CHOLET', '59|62|68', NULL),
(3, 'Monsieur', 'IQUEL', 'Beg Menez', '3 ROUTE DE LA PLAGE', '29940 LA FORÊT-FOUESNANT', '125', NULL),
(4, 'Monsieur', "FAYD'HERBE", '.', '46 RUE DU PLUMAIL', '79460 MAGNE', '127', NULL),
(5, 'Monsieur', 'CCI', '.', '2-4 RUE BERNARD PALISSY - ZA CHASSEREAU', '85604 SAINT-GEORGES DE MONTAIGU CEDEX', '71', NULL);