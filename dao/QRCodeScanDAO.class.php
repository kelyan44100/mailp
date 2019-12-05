<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class QRCodeScanDAO extends AbstractDAO {
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all QRCodeScan (the returned array may be empty)
     */
    public function findAll() {

	$result = array ();
	$query = 'SELECT * FROM qrcode_scan ORDER BY 5 DESC';

	try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();			
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                list ( $idQRCodeScan, $onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime ) = $row; // Like that $idQRCodeScan = $row['id_qrcode_scan'] etc.
                $newQRCodeScan = new QRCodeScan($onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime, $idQRCodeScan);
                $result[] = $newQRCodeScan; // Adds new QRCodeScan to array
            }

            // $this->pdo = NULL;
            return $result;
	}
	catch(Exception $e) // In case of error
	{
            // The transaction is cancelled
            $this->pdo->rollback();

            // An error message is displayed
            print 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
            print 'Erreur : '.$e->getMessage().'<br />';
            print 'N° : '.$e->getCode();

            // Execution of the code is stopped
            die();
	}
    }
    
    /*
     * Returns the object of the searched QRCodeScan
     */
    public function findById($searchedIdQRCodeScan) {

        $result = NULL;
        $query = 'SELECT * FROM qrcode_scan WHERE id_qrcode_scan = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdQRCodeScan));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idQRCodeScan, $onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime ) = $row; // Like that $idQRCodeScan = $row['id_qrcode_scan'] etc.
                    $newQRCodeScan = new QRCodeScan($onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime, $idQRCodeScan);
                    $result = $newQRCodeScan;
            }

            // $this->pdo = NULL;
            return $result;
        }
        catch(Exception $e) // In case of error
        {
            // The transaction is cancelled
            $this->pdo->rollback();

            // An error message is displayed
            print 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
            print 'Erreur : '.$e->getMessage().'<br />';
            print 'N° : '.$e->getCode();

            // Execution of the code is stopped
            die();
        }
    }
    
    /*
     * Returns an array of QRCodeScan objects for one Enterprise and one PurchasingFair
     */
    public function findAllByTrio($idPurchasingFair, $idEnterprise, $idParticipant) {

        $result = array();
        $query = 'SELECT * '
                . 'FROM qrcode_scan '
                . 'WHERE PURCHASING_FAIR_id_purchasing_fair = ? '
                . 'AND ENTERPRISE_id_enterprise = ? '
                . 'AND PARTICIPANT_id_participant = ? '
                . 'ORDER BY 5 DESC';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $idEnterprise, $idParticipant));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idQRCodeScan, $onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime ) = $row; // Like that $idQRCodeScan = $row['id_qrcode_scan'] etc.
                    $newQRCodeScan = new QRCodeScan($onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime, $idQRCodeScan);
                    $result[] = $newQRCodeScan;
            }

            // $this->pdo = NULL;
            return $result;
        }
        catch(Exception $e) // In case of error
        {
            // The transaction is cancelled
            $this->pdo->rollback();

            // An error message is displayed
            print 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
            print 'Erreur : '.$e->getMessage().'<br />';
            print 'N° : '.$e->getCode();

            // Execution of the code is stopped
            die();
        }
    }

    /*
     * IF the QRCodeScan id is -1, the QRCodeScan does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the QRCodeScan must be updated.
     */
    public function save($qrcodeScan) {
        if($qrcodeScan->getIdQRCodeScan() == - 1) return $this->insert($qrcodeScan);
        else return $this->update($qrcodeScan);
    }

    /*
     * Inserts $qrcodeScan as a new record
     */
    public function insert($qrcodeScan) { 

        $queryQRCodeScanTable = 'INSERT INTO qrcode_scan '
                . '(PURCHASING_FAIR_id_purchasing_fair, ENTERPRISE_id_enterprise, PARTICIPANT_id_participant, scan_datetime) '
                . 'VALUES(?,?,?,NOW())';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($queryQRCodeScanTable); 
            $qresult->execute(array(
                $qrcodeScan->getOnePurchasingFair()->getIdPurchasingFair(), 
                $qrcodeScan->getOneEnterprise()->getIdEnterprise(), 
                $qrcodeScan->getOneParticipant()->getIdParticipant()
            ));
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            return  ($qresult->rowCount()) ? $lastInsertId : 0; // Returns the last inserted row if insert is OK
        }
        catch(Exception $e) // In case of error
        {
            // The transaction is cancelled
            $this->pdo->rollback();

            // An error message is displayed
            print 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
            print 'Erreur : '.$e->getMessage().'<br />';
            print 'N° : '.$e->getCode();

            // Execution of the code is stopped
            die();
        }
    }

    /*
     * Update one QRCodeScan
     */
    public function update($qrcodeScan) { ; }

    /*
     * Deactivate a QRCodeScan (date_deletion => NOW())
     */
    public function deactivate($qrcodeScan) { ; }
    	

    /*
     * Delete a QRCodeScan
     */
    public function delete($idQRCodeScan) {

        $query = 'DELETE FROM qrcode_scan WHERE id_qrcode_scan = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idQRCodeScan));
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            return $qresult->rowCount(); // Returns the number of rows affected by the last SQL exec()
        }
        catch(Exception $e) // In case of error
        {
            // The transaction is cancelled
            $this->pdo->rollback();

            // An error message is displayed
            print 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
            print 'Erreur : '.$e->getMessage().'<br />';
            print 'N° : '.$e->getCode();

            // Execution of the code is stopped
            die();
        }	
    }
}
?>