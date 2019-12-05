-- Add panel (panonceau) code stores
ALTER TABLE enterprise
ADD panel_enterprise CHAR(5) NULL DEFAULT NULL COMMENT 'Code panonceau' AFTER password_enterprise;

-- Ajout codes panonceaux à partir fichier excel foireux doc/magasins_codes_panonceaux.xlsx
UPDATE enterprise SET panel_enterprise = "15.44" WHERE id_enterprise = 1;
UPDATE enterprise SET panel_enterprise = "39.44" WHERE id_enterprise = 2;
UPDATE enterprise SET panel_enterprise = "19.35" WHERE id_enterprise = 3;
UPDATE enterprise SET panel_enterprise = "22.44" WHERE id_enterprise = 4;
UPDATE enterprise SET panel_enterprise = "04.53" WHERE id_enterprise = 5;
UPDATE enterprise SET panel_enterprise = "38.44" WHERE id_enterprise = 6;
UPDATE enterprise SET panel_enterprise = "09.79" WHERE id_enterprise = 7;
UPDATE enterprise SET panel_enterprise = "26.44" WHERE id_enterprise = 8;
UPDATE enterprise SET panel_enterprise = "32.44" WHERE id_enterprise = 9;
UPDATE enterprise SET panel_enterprise = "30.44" WHERE id_enterprise = 10;
UPDATE enterprise SET panel_enterprise = "14.49" WHERE id_enterprise = 11;
UPDATE enterprise SET panel_enterprise = "15.49" WHERE id_enterprise = 12;
UPDATE enterprise SET panel_enterprise = "20.85" WHERE id_enterprise = 13;
UPDATE enterprise SET panel_enterprise = "13.49" WHERE id_enterprise = 14;
UPDATE enterprise SET panel_enterprise = "29.44" WHERE id_enterprise = 15;
UPDATE enterprise SET panel_enterprise = "20.35" WHERE id_enterprise = 16;
UPDATE enterprise SET panel_enterprise = "11.22" WHERE id_enterprise = 17;
UPDATE enterprise SET panel_enterprise = "11.49" WHERE id_enterprise = 18;
UPDATE enterprise SET panel_enterprise = "17.56" WHERE id_enterprise = 19;
UPDATE enterprise SET panel_enterprise = "09.35" WHERE id_enterprise = 20;
UPDATE enterprise SET panel_enterprise = "10.49" WHERE id_enterprise = 21;
UPDATE enterprise SET panel_enterprise = "35.44" WHERE id_enterprise = 22;
UPDATE enterprise SET panel_enterprise = "99.99" WHERE id_enterprise = 23;
UPDATE enterprise SET panel_enterprise = "15.85" WHERE id_enterprise = 24;
UPDATE enterprise SET panel_enterprise = "17.35" WHERE id_enterprise = 25;
UPDATE enterprise SET panel_enterprise = "36.44" WHERE id_enterprise = 26;
UPDATE enterprise SET panel_enterprise = "27.44" WHERE id_enterprise = 27;
UPDATE enterprise SET panel_enterprise = "18.85" WHERE id_enterprise = 28;
UPDATE enterprise SET panel_enterprise = "23.44" WHERE id_enterprise = 29;
UPDATE enterprise SET panel_enterprise = "31.44" WHERE id_enterprise = 30;
UPDATE enterprise SET panel_enterprise = "21.85" WHERE id_enterprise = 31;
UPDATE enterprise SET panel_enterprise = "05.72" WHERE id_enterprise = 32;
UPDATE enterprise SET panel_enterprise = "09.49" WHERE id_enterprise = 33;
UPDATE enterprise SET panel_enterprise = "08.49" WHERE id_enterprise = 34;
UPDATE enterprise SET panel_enterprise = "28.44" WHERE id_enterprise = 35;
UPDATE enterprise SET panel_enterprise = "19.85" WHERE id_enterprise = 36;
UPDATE enterprise SET panel_enterprise = "11.85" WHERE id_enterprise = 37;
UPDATE enterprise SET panel_enterprise = "34.44" WHERE id_enterprise = 38;
UPDATE enterprise SET panel_enterprise = "16.35" WHERE id_enterprise = 39;
UPDATE enterprise SET panel_enterprise = "12.44" WHERE id_enterprise = 40;
UPDATE enterprise SET panel_enterprise = "14.85" WHERE id_enterprise = 41;
UPDATE enterprise SET panel_enterprise = "13.85" WHERE id_enterprise = 42;
UPDATE enterprise SET panel_enterprise = "21.56" WHERE id_enterprise = 43;
UPDATE enterprise SET panel_enterprise = "17.85" WHERE id_enterprise = 44;
UPDATE enterprise SET panel_enterprise = "13.35" WHERE id_enterprise = 45;
UPDATE enterprise SET panel_enterprise = "24.44" WHERE id_enterprise = 46;
UPDATE enterprise SET panel_enterprise = "04.79" WHERE id_enterprise = 47;
UPDATE enterprise SET panel_enterprise = "15.56" WHERE id_enterprise = 48;

-- Cas particuliers LAURY-CHALONGES / SODIRENNES / SODIRETZ (1,2,3)
UPDATE enterprise SET panel_enterprise = "36.44" WHERE id_enterprise IN(115,116);
UPDATE enterprise SET panel_enterprise = "16.35" WHERE id_enterprise IN(117,118);
UPDATE enterprise SET panel_enterprise = "12.44" WHERE id_enterprise IN(119,120);

-- Pour les fournisseurs
UPDATE enterprise SET panel_enterprise = "00.00" WHERE panel_enterprise IS NULL;

-- Génération mots de passe pour toutes les entreprises
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("26H{wsfr")) WHERE id_enterprise = 1;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("68T{jehp")) WHERE id_enterprise = 2;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("39T@xgfy")) WHERE id_enterprise = 3;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("40D[tqzw")) WHERE id_enterprise = 4;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("90W-zkap")) WHERE id_enterprise = 5;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("60S{eucp")) WHERE id_enterprise = 6;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("60T@xave")) WHERE id_enterprise = 7;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("38T_kckn")) WHERE id_enterprise = 8;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("14B}skpj")) WHERE id_enterprise = 9;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("71A}dqik")) WHERE id_enterprise = 10;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("77K[meoz")) WHERE id_enterprise = 11;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("73X[fjls")) WHERE id_enterprise = 12;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("88J{qwnb")) WHERE id_enterprise = 13;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("14H[sbji")) WHERE id_enterprise = 14;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("14L@fsip")) WHERE id_enterprise = 15;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("24Y{uhxb")) WHERE id_enterprise = 16;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("35H@rgzg")) WHERE id_enterprise = 17;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("49N?gqpd")) WHERE id_enterprise = 18;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("84M#flip")) WHERE id_enterprise = 19;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("61X_aiqh")) WHERE id_enterprise = 20;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("31B@pzks")) WHERE id_enterprise = 21;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("88X[rrzj")) WHERE id_enterprise = 22;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("50P#sbco")) WHERE id_enterprise = 23;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("27C}gisd")) WHERE id_enterprise = 24;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("87L}iqvu")) WHERE id_enterprise = 25;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("14Z-pryr")) WHERE id_enterprise = 26;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("75J]qaoy")) WHERE id_enterprise = 27;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("78L?dywg")) WHERE id_enterprise = 28;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("19L+vfyf")) WHERE id_enterprise = 29;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("34N]ybip")) WHERE id_enterprise = 30;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("26X@aica")) WHERE id_enterprise = 31;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("12L}crpm")) WHERE id_enterprise = 32;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("76I}ppdv")) WHERE id_enterprise = 33;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("53W-yhrd")) WHERE id_enterprise = 34;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("70L#jzdq")) WHERE id_enterprise = 35;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("42O_pjhx")) WHERE id_enterprise = 36;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("25C!cmcf")) WHERE id_enterprise = 37;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("43D#zhyp")) WHERE id_enterprise = 38;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("32N?cvhw")) WHERE id_enterprise = 39;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("26E}glec")) WHERE id_enterprise = 40;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("18V?ffno")) WHERE id_enterprise = 41;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("27E@bwpv")) WHERE id_enterprise = 42;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("20X@fgds")) WHERE id_enterprise = 43;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("47Q_seos")) WHERE id_enterprise = 44;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("51I#aipi")) WHERE id_enterprise = 45;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("47N{jtdu")) WHERE id_enterprise = 46;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("80U#bxoy")) WHERE id_enterprise = 47;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("71N!ivhc")) WHERE id_enterprise = 48;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("29H{uelg")) WHERE id_enterprise = 49;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("23E?xyhm")) WHERE id_enterprise = 50;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("59I]gqro")) WHERE id_enterprise = 51;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("32Z?fjmb")) WHERE id_enterprise = 52;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("62I}ukup")) WHERE id_enterprise = 53;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("22L@aewl")) WHERE id_enterprise = 54;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("57N+pgrv")) WHERE id_enterprise = 55;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("33Y+jcyn")) WHERE id_enterprise = 56;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("92W_hqvm")) WHERE id_enterprise = 57;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("84V{dgvt")) WHERE id_enterprise = 58;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("95I@qiaz")) WHERE id_enterprise = 59;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("20G]siuj")) WHERE id_enterprise = 60;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("69Y#erjl")) WHERE id_enterprise = 61;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("91H_vnrb")) WHERE id_enterprise = 62;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("37Q_amoo")) WHERE id_enterprise = 63;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("96E[eixy")) WHERE id_enterprise = 64;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("38N{mjus")) WHERE id_enterprise = 65;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("88S_dxps")) WHERE id_enterprise = 66;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("57K+uteb")) WHERE id_enterprise = 67;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("95T-wsul")) WHERE id_enterprise = 68;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("13M{eyaw")) WHERE id_enterprise = 69;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("51Q+dzmp")) WHERE id_enterprise = 70;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("32L-vbrr")) WHERE id_enterprise = 71;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("63Z@vbqz")) WHERE id_enterprise = 72;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("25R-nzej")) WHERE id_enterprise = 73;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("56G?vxyq")) WHERE id_enterprise = 74;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("44P#icke")) WHERE id_enterprise = 75;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("61N}vnzu")) WHERE id_enterprise = 76;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("99W+kvzp")) WHERE id_enterprise = 77;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("60I]acak")) WHERE id_enterprise = 78;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("61V@djip")) WHERE id_enterprise = 79;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("29D_obfb")) WHERE id_enterprise = 80;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("93N?ofaw")) WHERE id_enterprise = 81;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("39Y+pveo")) WHERE id_enterprise = 82;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("12M?nfto")) WHERE id_enterprise = 83;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("39H?asjd")) WHERE id_enterprise = 85;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("67F!padz")) WHERE id_enterprise = 87;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("26N-niha")) WHERE id_enterprise = 88;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("85U[yhxx")) WHERE id_enterprise = 89;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("29S+eryk")) WHERE id_enterprise = 90;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("82C-omwu")) WHERE id_enterprise = 91;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("69O!vxvy")) WHERE id_enterprise = 92;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("59J+cfck")) WHERE id_enterprise = 93;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("89C{gtry")) WHERE id_enterprise = 94;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("28L{gngo")) WHERE id_enterprise = 95;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("41L_adys")) WHERE id_enterprise = 96;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("86K#sslx")) WHERE id_enterprise = 97;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("49I_wnzq")) WHERE id_enterprise = 98;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("38C#flcb")) WHERE id_enterprise = 99;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("52N]lprr")) WHERE id_enterprise = 104;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("60Z}jwii")) WHERE id_enterprise = 105;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("14I#gpfq")) WHERE id_enterprise = 106;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("23S[srya")) WHERE id_enterprise = 107;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("58Z]ngse")) WHERE id_enterprise = 108;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("79N{tlik")) WHERE id_enterprise = 109;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("27Y_mmes")) WHERE id_enterprise = 110;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("93C!tmwa")) WHERE id_enterprise = 112;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("58V{gdju")) WHERE id_enterprise = 113;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("46D]yhcv")) WHERE id_enterprise = 114;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("57C+ilym")) WHERE id_enterprise = 115;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("19Y[rxzb")) WHERE id_enterprise = 116;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("97U}akxl")) WHERE id_enterprise = 117;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("31C?wodj")) WHERE id_enterprise = 118;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("11W?nixz")) WHERE id_enterprise = 119;
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("98P!wfyn")) WHERE id_enterprise = 120;

-- Cas où certaines sont nouvelles : 12345678 par défaut
UPDATE enterprise SET password_enterprise = UNHEX(SHA1("12345678")) WHERE password_enterprise IS NULL;

-- Fontion MySQL qui retourne le logo FA correspondant à l'extension du fichier passé en paramètre
-- Suppression ssi existe déjà
DROP FUNCTION IF EXISTS getFAIconFromExtensionFile;

DELIMITER $$
CREATE FUNCTION getFAIconFromExtensionFile(nameFile VARCHAR(250)) RETURNS VARCHAR(250)
BEGIN
DECLARE fileExtension VARCHAR(250);
DECLARE faIcon VARCHAR(250);
SET fileExtension = '';
SET faIcon = '';

-- Récupération du chemin d'accès du fichier + stockage dans une variable
SELECT UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(nameFile, '/', -1), '.', -1))		
INTO fileExtension;

-- CASE
CASE fileExtension
	WHEN 'JPG' THEN SET faIcon = '<i class="fa fa-file-image-o" aria-hidden="true"></i>';
	WHEN 'PNG' THEN SET faIcon = '<i class="fa fa-file-image-o" aria-hidden="true"></i>';
	WHEN 'DOC' THEN SET faIcon = '<i class="fa fa-file-word-o text-info" aria-hidden="true"></i>';
	WHEN 'DOCX' THEN SET faIcon = '<i class="fa fa-file-word-o text-info" aria-hidden="true"></i>';
	WHEN 'PPT' THEN SET faIcon = '<i class="fa fa-file-powerpoint-o text-warning" aria-hidden="true"></i>';
	WHEN 'PPTX' THEN SET faIcon = '<i class="fa fa-file-powerpoint-o text-warning" aria-hidden="true"></i>';
	WHEN 'XLS' THEN SET faIcon = '<i class="fa fa-file-excel-o text-success" aria-hidden="true"></i>';
	WHEN 'XSLX' THEN SET faIcon = '<i class="fa fa-file-excel-o text-success" aria-hidden="true"></i>';
	WHEN 'PDF' THEN SET faIcon = '<i class="fa fa-file-pdf-o text-danger" aria-hidden="true"></i>';
	WHEN 'TXT' THEN SET faIcon = '<i class="fa fa-file-text-o" aria-hidden="true"></i>';
	WHEN 'ZIP' THEN SET faIcon = '<i class="fa fa-file-archive-o" aria-hidden="true"></i>';
	WHEN 'RAR' THEN SET faIcon = '<i class="fa fa-file-archive-o" aria-hidden="true"></i>';
	-- AUTRE (DEFAUT)
	ELSE SET faIcon = '<i class="fa fa-file-o" aria-hidden="true"></i>';
 END CASE;

RETURN faIcon;

END$$

DELIMITER ;

-- Suppression données table qrcode_scan (bug)
DELETE FROM qrcode_scan;