<?php

require_once dirname ( __FILE__ ) . '/RandomColor.class.php' ;
require_once dirname ( __FILE__ ) . '/MyEmail.class.php' ;

// https://github.com/PHPMailer/PHPMailer
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once dirname ( __FILE__ ) . '/../phpmailer/src/Exception.php' ;
require_once dirname ( __FILE__ ) . '/../phpmailer/src/PHPMailer.php' ;
require_once dirname ( __FILE__ ) . '/../phpmailer/src/SMTP.php' ;

// Use Random-Compat PHP7 Functions
// https://github.com/paragonie/random_compat
// https://paragonie.com/blog/2015/07/how-safely-generate-random-strings-and-integers-in-php
// http://php.net/manual/fr/function.random-int.php
require_once dirname ( __FILE__ ) . '/../security/random_compat-1.4.3/lib//random.php';

// GitHub - ifsnop/mysqldump-php: PHP version of mysqldump cli that comes with MySQL
// https://github.com/ifsnop/mysqldump-php
// Updated regularly !!!
require_once(dirname(__FILE__) . '/Mysqldump.php');

class Various {
    
    // Attributes
    
    // Constructor
    public function __construct() { ; }
    
    // Getters & setters
    
    // Methods
    
    /* Function to get the user IP address (Source here => http://roshanbh.com.np/2007/12/getting-real-ip-address-in-php.html )
     * In this PHP function, first attempt is to get the direct IP address of client’s machine, if not available then try for forwarded for IP address using HTTP_X_FORWARDED_FOR. 
     * And if this is also not available, then finally get the IP address using REMOTE_ADDR.
     */
    public static function getIP() {

        if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP']; // Check IP from share internet
        elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; // To check IP is pass from proxy
        else $ip = $_SERVER['REMOTE_ADDR'];

        return $ip;
    }
    
    // Returns a formatted date from a MySQL DATE
    public static function myFrenchDate($datetimeMySql) { 
        date_default_timezone_set("Europe/Paris"); // To prevent date errors
        return DateTime::createFromFormat('Y-m-d H:i:s', $datetimeMySql)->format('d/m/Y');  }
    
    // Returns a formatted date from a MySQL DATETIME
    public static function myFrenchDatetime($datetimeMySql) { 
        date_default_timezone_set("Europe/Paris"); // To prevent date errors
        return DateTime::createFromFormat('Y-m-d H:i:s', $datetimeMySql)->format('d/m/Y H:i:s'); }
    
    // Returns a formatted time from a MySQL datetime
    public static function myFrenchTime($datetimeMySql) { 
        date_default_timezone_set("Europe/Paris");  // To prevent date errors
        $h = DateTime::createFromFormat('Y-m-d H:i:s', $datetimeMySql)->format('H'); 
        $m = DateTime::createFromFormat('Y-m-d H:i:s', $datetimeMySql)->format('i'); 
        $s = DateTime::createFromFormat('Y-m-d H:i:s', $datetimeMySql)->format('s'); 
        return $h.'h '.$m.'m '.$s.'s';
    }
    
    // Calculation of the deadline for entering requirements and unavailabilities
    public static function deadline($datetimeMySql) {
        date_default_timezone_set("Europe/Paris"); // To prevent date errors
        // See http://php.net/manual/fr/dateinterval.construct.php
        // The format starts with the letter P, for "period." Each duration period is represented by an integer value followed by a period designator. 
        // If the duration contains time elements, that portion of the specification is preceded by the letter T.
        // Here we remove 4 months to the date passed in parameter (customer request)
        return DateTime::createFromFormat('Y-m-d H:i:s', $datetimeMySql)->sub(new DateInterval('P4M'))->format('Y-m-d H:i:s');
    }
    
    // Check if a purchasing fair must be closed for users
    public static function purchasingFairIsClosedForUser($datetimeMySql) {
        date_default_timezone_set("Europe/Paris"); // To prevent date errors
        $closingDate = DateTime::createFromFormat('Y-m-d H:i:s', self::deadline($datetimeMySql)); // - 4 mois remember !
        $interval = $closingDate->diff(new DateTime()); // Now - closingDate, if '-', closing date is in the future, else not
        // echo $interval->format('Délai : %R %y an(s) %m mois %y jour(s) %h heure(s) %i minute(s) %s seconde(s)');
        return ( $interval->format('%R') == '-') ? 0 : 1; // IF %R equals '+' the purchasing fair must be closed
    }
    
    // Returns a boolean as a String
    public static function bool2str($bool) { return ($bool === false) ?  'FALSE' : 'TRUE'; }
    
    //  When using the identity operator (===), object variables are identical if and only if they refer to the same instance of the same class.
    // http://php.net/manual/fr/language.oop5.object-comparison.php
    public static function compareObjects(&$o1, &$o2) {
//        echo '1) o1 == o2 : '.self::bool2str($o1 == $o2).'<br/>';
//        echo '2) o1 != o2 : '.self::bool2str($o1 != $o2).'<br/>';
//        echo '3) o1 === o2 : '.self::bool2str($o1 === $o2).'<br/>';
//        echo '4) o1 !== o2 : '.self::bool2str($o1 !== $o2).'<br/>';
        return ($o1 === $o2); // See above
    }
    
    // Returns the SQL Query to insert fake users
    public static function generateFakeUsers($howMany) {
        $limitComma = $howMany - 1;
        $strQuery = "INSERT INTO user (surname_user, name_user, login_user, password_user, ENTERPRISE_id_enterprise) VALUES ";

        for( $n = 0 ; $n < $howMany ; $n++) {
            $strQuery .= '("SURNAME_USER_'.$n.'", "NAME_USER_'.$n.'", "LOGIN_USER_'.$n.'", "PASSWORD_USER_'.$n.'", '.(rand(1,52)).')'; // { I = [1,52] | x C I }
            $strQuery .= ($n < $limitComma) ? "," : ""; 
        }
        return $strQuery;
    }
    
    // Returns the SQL Query to insert fake purchasing fairs
    public static function generateFakePF($howMany) {
        $arrayColors = [0 => '#556270', 1 => '#4ECDC4', 2 => '#C7F464', 3 => '#FF6B6B', 4 => '#C44D58'];
        
        $limitComma = $howMany - 1;
        $strQuery = "INSERT INTO purchasing_fair (name_purchasing_fair, hex_color, start_datetime, end_datetime, lunch_break, TYPEOF_PF_id_typeof_pf, date_deletion_purchasing_fair) VALUES ";

        for( $n = 0 ; $n < $howMany ; $n++) {
            $strQuery .= '("unSalon'.$n.'", "'.($arrayColors[mt_rand(0,4)]).'", "20'.(mt_rand(19,20)).'-08-01 00:00:00", "20'.(mt_rand(21,22)).'-08-04 00:00:00", "20:00:00", '.(mt_rand(1,2)).', NULL)';
            $strQuery .= ($n < $limitComma) ? "," : ""; 
        }
        return $strQuery;
    }
    
    // Used to calculate the page generation time
    public static function myChrono() {
        $time = explode( ' ', microtime() );
        return $time[0] + $time[1];
    }
    
    // Return an array of random values generated by the Mersenne Twister Random Number Generator
    public static function sixDigitsGenerator($howManyNumbersDoYouWant) {
        
        // Generate a random number (6 digits) between 000001 and 999999 (inclusive). Note : mt_rand() is four times faster than what the rand() function !
        $arrayNumbersGenerated = array(); // To prevent numbers duplicated

        for( $i = 0 ; $i < $howManyNumbersDoYouWant ; $i++ ) {

            $numberGenerated = sprintf( "%06d", mt_rand(1, 999999) ); // Number generation [000001;999999]

            while( in_array( $numberGenerated, $arrayNumbersGenerated ) ) { // While the number is already registered - && strlen( $numberGenerated ) < 6
                $numberGenerated = sprintf( "%06d", mt_rand(1, 999999) ); // Number generation again [000001;999999]
            }
            $arrayNumbersGenerated[] = $numberGenerated; // number inserted in the array
        }
        return $arrayNumbersGenerated;
    }
    
    // Save PNG QR Codes to server
    // SRC : http://phpqrcode.sourceforge.net/examples/index.php?example=005
    public static function generateQRCodes($purchasingFair, $arrayParticipants) {
        
        require_once dirname ( __FILE__ ) . '/../phpqrcode/qrlib.php'; // Requirements
         
        $tempDir = dirname ( __FILE__ ) . '/../phpqrcode/temp/'; // Where the QR Code will be saved
        
        foreach($arrayParticipants as $value) {

            $codeContents = 'Création QRCode Participant ID '.$value->getIdParticipant(); // Content of QR Code

            $fileName = 'PF_'.$purchasingFair->getIdPurchasingFair().'_PARTICIPANT_'.$value->getIdParticipant().'.png'; // Generate filename (N° Purchasing Fair and User ID)

            $pngAbsoluteFilePath = $tempDir.$fileName; // Absolute Path
            
            // echo 'Server PNG File: '.$pngAbsoluteFilePath; 

            QRcode::png($codeContents, $pngAbsoluteFilePath); // Generating
        }
    }
    
    public static function generateStickers($purchasingFair, $arrayParticipants) {
        
        ob_start(); // Always before include, require etc.

        require_once dirname ( __FILE__ ) . '/../html2pdf-4.4.0/html2pdf.class.php'; // Requirements
        
        self::generateQRCodes($purchasingFair, $arrayParticipants); // Before generate stickers, generate QR Codes !
        
        $tempDir = './phpqrcode/temp/'; // Where the QR Code has been saved
        
        $content = '<page backtop="20mm" backbottom="20mm" backleft="10mm" backright="10mm"><page_header></page_header><page_footer></page_footer></page>'; // For HTML2PDF
        
        $content .= '<table style="width:100%;"><tr><th style="text-align:center;width:100%"><img src="./img/logo_eleclerc_scaouest.png" style="width:378px;height:46px;"></th></tr></table>'; // LOGO E.LEclerc

        $content .= '<h3 style="text-align:center">Salon n°'.$purchasingFair->getIdPurchasingFair().' - '.count($arrayParticipants).' stickers</h3>';
        
        foreach($arrayParticipants as $value) { // FOR EACH Participants
            
            $fileName = 'PF_'.$purchasingFair->getIdPurchasingFair().'_PARTICIPANT_'.$value->getIdParticipant().'.png'; // Name of QR Code File
        
            // Insert into PDF File
            $content .= '
            <table style="border-collapse:collapse;border:1px solid #000000;width:100%">
                <thead>
                    <tr>
                        <td style="border:1px solid black;text-align:center;width:50%">QR CODE</td>
                        <td style="border:1px solid black;text-align:center;width:50%">PARTICIPANT</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="3" style="border:1px solid black;vertical-align:middle;text-align:center;width:50%"><img src="'.$tempDir.$fileName.'" height="100" width="100" ></td>
                        <td style="border:1px solid black;text-align:center;width:50%">${NOM_ENTREPRISE}</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid black;text-align:center;width:50%">${NOM_PRENOM_PARTICIPANT}</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid black;text-align:center;width:50%">${REPAS : O / N}</td>
                    </tr>

                </tbody>
            </table>';
            
            echo $content;
            
            $content = ob_get_clean();

        }
        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'fr'); // Portrait / A4 / French
            $html2pdf -> pdf -> setTitle('stickers_generation_'.date('YmdHis')); // Title in pdf viewer
            $html2pdf -> pdf -> setDisplayMode('fullpage'); // If output not D, display the pdf in the entire page
            $html2pdf -> writeHTML($content);
            $html2pdf-> Output('stickers_generation_'.date('YmdHis').'.pdf', 'I'); // I = Show in browser, Force Download = D
        } catch(HTML2PDF_exception $e) { die($e); }
    }
    
    // strcasecmp — Binary safe case-insensitive string comparison
    // Returns < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
    // Here we compare attributes of Participants objects
    // Make the sort function static
    public static function compareStrings($a, $b) { 
       $result = strcasecmp($a->getOneParticipant()->getSurname(), $b->getOneParticipant()->getSurname());
       // IF equal we compare the name
       return (!$result) ? strcasecmp($a->getOneParticipant()->getName(), $b->getOneParticipant()->getName()) : $result; 
    }
    
    // Sort Purchasing Fairs - Added 03/09/2018
    public static function compareIds($a, $b) {
        
        if ($a->getIdPurchasingFair() == $b->getIdPurchasingFair()) {
            return 0;
        }
        // Remove -1 for ascending order (default)
        return -1 * ( ($a->getIdPurchasingFair() > $b->getIdPurchasingFair()) ? +1 : -1 );
    }
    
    // strcasecmp — Binary safe case-insensitive string comparison
    // Returns < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
    // Here we compare attributes of Participants objects
    // Make the sort function static
    public static function compareStringsBis($a, $b) { 
       $result = strcasecmp($a->getSurname(), $b->getSurname());
       // IF equal we compare the name
       return (!$result) ? strcasecmp($a->getName(), $b->getName()) : $result; 
    }
    
    // Sorts all the elements of the array according to the Participants
    // Use an array for the second parameter of usort() => array('ClassName','sortFunction')
    public static function sortParticipantsBySurnameAndName($arrayOfObjectsThatContainParticipantsObjects) { 
        usort($arrayOfObjectsThatContainParticipantsObjects, array(__CLASS__, 'compareStrings')); // __CLASS__ == Curent class name
        return $arrayOfObjectsThatContainParticipantsObjects; // We use the usort in a function, so we must return the array sorted here 
    }
    
    // Version with array of Participant objects (no object that contains them)
    public static function sortParticipantsBySurnameAndNameBis($arrayOfObjectsThatNotContainParticipantsObjects) { 
        usort($arrayOfObjectsThatNotContainParticipantsObjects, array(__CLASS__, 'compareStringsBis')); // __CLASS__ == Curent class name
        return $arrayOfObjectsThatNotContainParticipantsObjects; // We use the usort in a function, so we must return the array sorted here 
    }
    
    // Version with Purchasing Fairs
    public static function sortPurchasingFairsById($arrayOfPurchasingFairs) {
        usort($arrayOfPurchasingFairs, array(__CLASS__, 'compareIds')); // __CLASS__ == Curent class name
        return $arrayOfPurchasingFairs; // We use the usort in a function, so we must return the array sorted here 
    }
    
    /* Returns multiple colors randomly. Note : \ because of namespace used. */
    public static function generateNColors($nColors) { return \Colors\RandomColor::many($nColors, array('luminosity'=>'light')); } // , 'hue'=>'random'
    
    // Generate a random string (Random-compat)
   public static function random_string($length = 26, $alphabet = 'abcdefghijklmnopqrstuvwxyz234567') {
       if ($length < 1) {
           throw new InvalidArgumentException('Length must be a positive integer');
       }
       $str = '';
       $alphamax = strlen($alphabet) - 1;
       if ($alphamax < 1) {
           throw new InvalidArgumentException('Invalid alphabet');
       }
       for ($i = 0; $i < $length; ++$i) {
           $str .= $alphabet[random_int(0, $alphamax)];
       }
       return $str;
   }
   
   // generate a random password
   // The generated password looks like this : [Number] * 1 | [Uppercase] * 1 | [Symbol] * 1 | [Lowercase] * 4
   // Symbols <> removed due to bugs
    public static function random_password() {
        return random_int(10, 99).
                self::random_string(1, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ').
                    self::random_string(1, '!?@#-_+[]{}').
                        self::random_string(4, 'abcdefghijklmnopqrstuvwxyz');
    }
    
    public static function randomKey() {
        return random_int(10000, 99999).
                self::random_string(50, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ').
                        self::random_string(50, 'abcdefghijklmnopqrstuvwxyz').
                                random_int(10000, 99999).
                                    self::random_string(50, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ').
                                        self::random_string(50, 'abcdefghijklmnopqrstuvwxyz');
    }
    
    public static function castelAccess() { return self::random_string(6, '123456789'); }
    
    // Generate N passwords
    public static function generateNPasswords($nbPasswords) {
    
        // Result array
        $arrayPasswordsGenerated = array();

        // Geerate distinct passwords w/ storage in result array
        for($i = 0 ; $i < $nbPasswords; $i++) {
            do {
                $passwordGenerated = self::random_password();
            } while(in_array($passwordGenerated, $arrayPasswordsGenerated));

            $arrayPasswordsGenerated[] = $passwordGenerated;
           }

           return $arrayPasswordsGenerated;
    }
    
    // Read Panel code for each Store from csv file given and wrtie SQL Queries
    // http://php.net/manual/fr/function.fgetcsv.php
    // CSV format : STORE;PANEL
    public static function generateSQLQueriesPanelCodes() {
        $row = 1;
        if (($handle = fopen('./../doc/magasins_codes_panonceaux.csv', 'r')) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                echo 'UPDATE enterprise SET panel_enterprise = "'.$data[1].'" WHERE id_enterprise = '.$row.';<br/>';
                $row++;
            }
            fclose($handle);
        }
    }
    
    /* public static function sendMail(MyEmail $myEmail) {
        
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        $mail->CharSet = 'UTF-8';                                 // For accents

        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = '205.0.211.32'; //'127.0.0.0';              // Specify main and backup SMTP servers
            $mail->SMTPAuth = false;                              // Enable/Disable SMTP authentication
        //    $mail->Username = 'user@example.com';               // SMTP username
        //    $mail->Password = 'secret';                         // SMTP password
        //    $mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 25;                                     // TCP port to connect to

            //Recipients
            $mail->setFrom('textile@scaouest.fr', 'Application PF_Management');
            $mail->addAddress($myEmail->getRecipient()['recipientAddress'], $myEmail->getRecipient()['recipientName']);     // Add a recipient
//            $mail->addAddress('test@scaouest.fr', 'Civilité NOM Prénom');     // Add a recipient
        //    $mail->addAddress('ellen@example.com');               // Name is optional
        //    $mail->addReplyTo('info@example.com', 'Information');
        //    $mail->addCC('cc@example.com');
        //    $mail->addBCC('bcc@example.com');

            //Attachments
            if(!empty($myEmail->getAttachments())) {
                foreach($myEmail->getAttachments() as $attachmentPath) {
                    $mail->addAttachment($attachmentPath);    // Add attachments w/ Optional name
                }
            }

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $myEmail->getSubject();
            $mail->Body    = $myEmail->getBody();
            $mail->AltBody = $myEmail->getAltBody();

            $mail->send();
//            echo 'Message has been sent';
            return true;
        } catch (Exception $e) { 
            return false;
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo; 
        }
    } */
	public static function sendMail(MyEmail $myEmail) {
        
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        $mail->CharSet = 'UTF-8';                                 // For accents

        try {
            //Server settings
            //$mail->SMTPDebug = 0;                                 // Enable verbose debug output
            //$mail->isSMTP();                                      // Set mailer to use SMTP
            //$mail->Host = '205.0.211.32';                            // Specify main and backup SMTP servers
            //$mail->SMTPAuth = false;                              // Enable/Disable SMTP authentication
        //    $mail->Username = 'user@example.com';               // SMTP username
        //    $mail->Password = 'secret';                         // SMTP password
        //    $mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
            //$mail->Port = 25;                                     // TCP port to connect to
			//$mail->SMTPAuth = true;  // Authentification SMTP active
			//$mail->SMTPSecure = 'tls'; // Gmail REQUIERT Le transfert securise
			//$mail->Host = 'smtp.gmail.com';
			//$mail->Port = 587;
			//$mail->Username = 'scaouest@gmail.com';
			//$mail->Password = 'fedora';
            //Recipients
            $mail->setFrom('textile@scaouest.fr', 'Application PF_Management');
            $mail->addAddress($myEmail->getRecipient()['recipientAddress'], $myEmail->getRecipient()['recipientName']);     // Add a recipient
//            $mail->addAddress('test@scaouest.fr', 'Civilité NOM Prénom');     // Add a recipient
        //    $mail->addAddress('ellen@example.com');               // Name is optional
        //    $mail->addReplyTo('info@example.com', 'Information');
        //    $mail->addCC('cc@example.com');
            $mail->addBCC('fabien.trojet@gmail.com');

            //Attachments
            if(!empty($myEmail->getAttachments())) {
                foreach($myEmail->getAttachments() as $attachmentPath) {
                    $mail->addAttachment($attachmentPath);    // Add attachments w/ Optional name
                }
            }

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $myEmail->getSubject();
            $mail->Body    = $myEmail->getBody();
            $mail->AltBody = $myEmail->getAltBody();

            $mail->send();
//            echo 'Message has been sent';
            return true;
        } catch (Exception $e) { 
            return false;
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo; 
        }
    }
    
    public static function numberFormat($numberToFormat, $numberFormat) {
        switch($numberFormat) {
            case 'french':
                // French notation
                $numberToFormat = number_format($numberToFormat, 2, ',', ' ');
                break;
            case 'english' :
                // English notation without thousands separator (default format in PHP)
                $numberToFormat = number_format($numberToFormat, 2, '.', '');
                break;
            default : 
                $numberToFormat = number_format($numberToFormat, 2, '.', ''); 
                break;
        }
        return $numberToFormat;
    }
    
    // To prevent CORS error
    // https://www.html5rocks.com/en/tutorials/cors/
    // https://forum.ovh.com/showthread.php/104723-Executer-des-Cross-Origin-Request
    // https://stackoverflow.com/questions/8719276/cors-with-php-headers
    public static function cors() {

        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }

    //    echo "You have CORS!";
    }
	
	// Added 08.09.2018
	public static function dumpDatabase() {
		$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=pf_management', 'root', '2607www');
		/* To prevent server misconfigured */
		date_default_timezone_set('Europe/Paris');
		$datetime = new DateTime();
		$dump->start('./../sql/dumps/dump'.$datetime->format('Ymd_His').'.sql');
	}
	
	// Added 08.09.2018
	public static function getServerName() { return $_SERVER['SERVER_NAME']; }
}