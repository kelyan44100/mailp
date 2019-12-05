<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class PresentDAO extends AbstractDAO {
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all Present (the returned array may be empty)
     */
    public function findAll() { ; }
    
    /*
     * Returns the object of the searched Present
     */
    public function findById($searchedIdPresent) { ; }
    
    /*
     * Returns an array of Present objects for one Enterprise and one PurchasingFair
     */
    public function findAllByDuo($idEnterprise, $idPurchasingFair) {

        $result = array();
        $query = 'SELECT * '
                . 'FROM present '
                . 'WHERE ENTERPRISE_id_enterprise = ? '
                . 'AND PURCHASING_FAIR_id_purchasing_fair = ? ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idEnterprise, $idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $oneEnterprise, $oneParticipant, $onePurchasingFair, $presentDetails ) = $row; // Like that $oneEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newPresent = new Present( $oneEnterprise, $oneParticipant, $onePurchasingFair, json_decode($presentDetails, true) );
                    $result[] = $newPresent;
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
     * Returns an array of Present objects for one Enterprise and one PurchasingFair
     */
    public function findAllByTrio($idEnterprise, $idParticipant, $idPurchasingFair) {

        $result = null;
        $query = 'SELECT * '
                . 'FROM present '
                . 'WHERE ENTERPRISE_id_enterprise = ? '
                . 'AND PARTICIPANT_id_participant = ? '
                . 'AND PURCHASING_FAIR_id_purchasing_fair = ? ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idEnterprise, $idParticipant, $idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $oneEnterprise, $oneParticipant, $onePurchasingFair, $presentDetails ) = $row; // Like that $oneEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newPresent = new Present( $oneEnterprise, $oneParticipant, $onePurchasingFair, json_decode($presentDetails, true) );
                    $result = $newPresent;
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
     * Returns an array of Present objects for all Providers
     */
    public function findAllByProviders() {

        $result = array();
        $query = '
        SELECT 
        p.ENTERPRISE_id_enterprise,
        p.PARTICIPANT_id_participant,
        p.PURCHASING_FAIR_id_purchasing_fair,
        p.present_details
        FROM present p
        INNER JOIN enterprise e ON e.id_enterprise = p.ENTERPRISE_id_enterprise
        WHERE e.PROFILE_id_profile = 1
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $oneEnterprise, $oneParticipant, $onePurchasingFair, $presentDetails ) = $row; // Like that $oneEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newPresent = new Present( $oneEnterprise, $oneParticipant, $onePurchasingFair, json_decode($presentDetails, true) );
                    $result[] = $newPresent;
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
     * IF the Present id is -1, the Present does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the Present must be updated.
     */
    public function save($present) { ; }

    /*
     * Inserts $present as a new record
     */
    public function insert($present) { 

        $queryPresent = 'INSERT INTO present '
                . '(ENTERPRISE_id_enterprise, PARTICIPANT_id_participant, PURCHASING_FAIR_id_purchasing_fair, present_details) '
                . 'VALUES(?,?,?,?)';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($queryPresent); 
            $qresult->execute(array(
                $present->getOneEnterprise()->getIdEnterprise(), 
                $present->getOneParticipant()->getIdParticipant(),
                $present->getOnePurchasingFair()->getIdPurchasingFair(),
                $present->getPresentDetails()
            ));
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            return  ($qresult->rowCount()) ? 1 : 0; // Returns the last inserted row if insert is OK // here 1 because no ID !
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
     * Update one Present
     */
    public function update($present) { 

        $query = "UPDATE present SET present_details = ? WHERE ENTERPRISE_id_enterprise = ? AND PARTICIPANT_id_participant = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array(
                $present->getPresentDetails(),
                $present->getOneEnterprise()->getIdEnterprise(), 
                $present->getOneParticipant()->getIdParticipant(), 
                $present->getOnePurchasingFair()->getIdPurchasingFair()
                ));
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

    /*
     * Deactivate a Present (date_deletion => NOW())
     */
    public function deactivate($present) { ; }
    	

    /*
     * Delete a Present
     */
    public function delete($present) {

        $query = 'DELETE FROM present WHERE ENTERPRISE_id_enterprise = ? AND PARTICIPANT_id_participant = ? AND PURCHASING_FAIR_id_purchasing_fair = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($present->getOneEnterprise()->getIdEnterprise(), $present->getOneParticipant()->getIdParticipant(), $present->getOnePurchasingFair()->getIdPurchasingFair()));
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
    
    /*
     * Delete all Present for Providers
     */
    public function deleteAllByProviders() {

        $query = 'DELETE p.* FROM present p '
                . 'INNER JOIN enterprise e ON e.id_enterprise = p.ENTERPRISE_id_enterprise '
                . 'WHERE e.PROFILE_id_profile = 1';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
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