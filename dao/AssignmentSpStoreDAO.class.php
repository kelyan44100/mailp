<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class AssignmentSpStoreDAO extends AbstractDAO {
    
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods
    
   /*
     * Returns the collection (a simple array) of all AssignmentSalespersonDepartment (ASPD) (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM assignment_sp_store";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idEnterprise, $idPurchasingFair ) = $row; // Like that $idParticipant = $row['PARTICIPANT_id_participant'] etc.
                    $newASS = new AssignmentSpStore($idParticipant, $idEnterprise, $idPurchasingFair);
                    $result[] = $newASS; // Adds new profile to array
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

    public function findById($searchedId) { ; }
	
    /*
     * Returns the object of the searched profile
     */
    public function findByFourIds($searchedIdParticipant, $searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair) {

        $result = NULL;
        $query = "
        SELECT * 
        FROM assignment_sp_store
        WHERE PARTICIPANT_id_participant = ? AND ENTERPRISE_STORE_id_enterprise = ? AND ENTERPRISE_PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdParticipant, $searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idStore, $idProvider, $idPurchasingFair ) = $row; // Like that $idSalesperson = $row['SALESPERSON_id_salesperson'] etc.
                    $newASS = new AssignmentSpStore($idParticipant, $idStore, $idProvider, $idPurchasingFair);
                    $result = $newASS;
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
     * Returns the object of the searched profile
     */
    public function findAllByParticipant($searchedIdParticipant) {

        $result = array();
        $query = "SELECT * FROM assignment_sp_store WHERE PARTICIPANT_id_participant = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdParticipant));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idStore, $idProvider, $idPurchasingFair ) = $row; // Like that $idSalesperson = $row['SALESPERSON_id_salesperson'] etc.
                    $newASS = new AssignmentSpStore($idParticipant, $idStore, $idProvider, $idPurchasingFair);
                    $result[] = $newASS;
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

    public function findDistinctProviders($idPurchasingFair) {

        $result = array();
        $query = "SELECT Distinct ENTERPRISE_PROVIDER_id_enterprise FROM assignment_sp_store WHERE PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    $result[] = $row;
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

    public function findAssignmentSpStore($searchedIdProvider, $searchedIdPurchasingFair) {

        $result = array();
        $query = "SELECT Distinct PARTICIPANT_id_participant FROM assignment_sp_store WHERE PURCHASING_FAIR_id_purchasing_fair = ? and ENTERPRISE_PROVIDER_id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdPurchasingFair, $searchedIdProvider));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    $result[] = $row[0];
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
     * Returns the object of the searched profile
     */
    public function findByTwoIds($searchedIdProvider, $searchedIdPurchasingFair) {

        $result = array();
        $query = "SELECT * FROM assignment_sp_store WHERE ENTERPRISE_PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idStore, $idProvider, $idPurchasingFair ) = $row; // Like that $idSalesperson = $row['SALESPERSON_id_salesperson'] etc.
                    $newASS = new AssignmentSpStore($idParticipant, $idStore, $idProvider, $idPurchasingFair);
                    $result[] = $newASS;
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

    public function findDistinctSpStore($searchedIdProvider, $searchedIdPurchasingFair) {

        $result = array();
        $query = "SELECT Distinct PARTICIPANT_id_participant FROM assignment_sp_store WHERE ENTERPRISE_PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    $result[] = $row[0];
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

    /*-------------------rajout----------------------*/
    public function findParticipants($searchedIdProvider, $searchedIdPurchasingFair) {

        $result = array();
        $query = "SELECT distinct PARTICIPANT_id_participant FROM assignment_sp_store WHERE ENTERPRISE_PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    /*list ( $idParticipant, $idStore, $idProvider, $idPurchasingFair ) = $row; // Like that $idSalesperson = $row['SALESPERSON_id_salesperson'] etc.
                    $newASS = new AssignmentSpStore($idParticipant, $idStore, $idProvider, $idPurchasingFair);*/
                    $result[] = $row;
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
     * Returns the object of the searched profile
     */
    public function findByThreeIds($searchedIdParticipant, $searchedIdProvider, $searchedIdPurchasingFair) {

        $result = array();
        $query = "SELECT * FROM assignment_sp_store WHERE PARTICIPANT_id_participant = ? AND ENTERPRISE_PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdParticipant, $searchedIdProvider, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idStore, $idProvider, $idPurchasingFair ) = $row; // Like that $idSalesperson = $row['SALESPERSON_id_salesperson'] etc.
                    $newASS = new AssignmentSpStore($idParticipant, $idStore, $idProvider, $idPurchasingFair);
                    $result[] = $newASS;
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
     * Returns the object of the searched ASS
     */
    public function findByThreeIdsBis($searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair) {

		$result = null;
        // $result = array();
        $query = "SELECT * FROM assignment_sp_store WHERE ENTERPRISE_STORE_id_enterprise = ? AND ENTERPRISE_PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

			if( $row = $qresult->fetch() ) {
            // while( $row = $qresult->fetch() ) { - use it when many Salespersons of one Provider assigned to one Store 12.07.2018
                    list ( $idParticipant, $idStore, $idProvider, $idPurchasingFair ) = $row; // Like that $idSalesperson = $row['SALESPERSON_id_salesperson'] etc.
                    $newASS = new AssignmentSpStore($idParticipant, $idStore, $idProvider, $idPurchasingFair);
                    // $result[] = $newASS;
					$result = $newASS;
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

    public function findByThreeIdsBisTwo($searchedIdStore, $searchedIdPurchasingFair) {

        $result = null;
        // $result = array();
        $query = "SELECT * FROM assignment_sp_store WHERE ENTERPRISE_STORE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdStore, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idStore, $idProvider, $idPurchasingFair ) = $row; // Like that $idSalesperson = $row['SALESPERSON_id_salesperson'] etc.
                    $newASS = new AssignmentSpStore($idParticipant, $idStore, $idProvider, $idPurchasingFair);
                    $result[] = $newASS;
                    //$result = $newASS;
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
     * Returns an array of Enterprises assigned for one Provider and one PurchasingFair
     */
    public function summaryOfAssignedStores($searchedIdProvider, $searchedIdPurchasingFair) {

        $result = array();
        $query = '
        SELECT 
        DISTINCT(e.id_enterprise), 
        e.name_enterprise, 
        e.password_enterprise, 
        e.panel_enterprise,
        e.postal_address,
        e.postal_code,
        e.city,
        e.vat,
        e.TYPEOF_PROVIDER_id_typeof_provider, 
        e.PROFILE_id_profile, 
        e.DEPARTMENT_id_department, 
        e.date_deletion_enterprise
        FROM assignment_sp_store AS ass
        INNER JOIN enterprise AS e ON e.id_enterprise = ass.ENTERPRISE_STORE_id_enterprise
        WHERE ENTERPRISE_PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?
        ORDER BY 2 ASC';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                    $newEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise );
                    $result[] = $newEnterprise;
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

    public function save($assignmentSpStore) { ; }

    /*
     * Inserts $assignmentSpStore as a new record
     */
    public function insert($assignmentSpStore) { 

        $query = "INSERT INTO assignment_sp_store (PARTICIPANT_id_participant, ENTERPRISE_STORE_id_enterprise, ENTERPRISE_PROVIDER_id_enterprise, PURCHASING_FAIR_id_purchasing_fair) VALUES(?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($assignmentSpStore->getOneParticipant()->getIdParticipant(),$assignmentSpStore->getOneStore()->getIdEnterprise(),$assignmentSpStore->getOneProvider()->getIdEnterprise(),$assignmentSpStore->getOnePurchasingFair()->getIdPurchasingFair()));
//            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            return ($qresult->rowCount()) ? 1 : 0; // Returns the last inserted row if insert is OK
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
     * Update one ASS
     */
    public function update($assignmentSpStore) { ; }

    /*
     * Deactivate an ASS (date_deletion => NOW())
     */
    public function deactivate($assignmentSpStore) { ; }	

    /*
     * Delete an ASS
     */
    public function delete($assignmentSpStore) {

        $query = "DELETE FROM assignment_sp_store WHERE PARTICIPANT_id_participant = ? AND ENTERPRISE_PROVIDER_id_enterprise  = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($assignmentSpStore->getOneParticipant()->getIdParticipant(), $assignmentSpStore->getOneProvider()->getIdEnterprise(),$assignmentSpStore->getOnePurchasingFair()->getIdPurchasingFair()));
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
     * Delete an ASS
     */
    public function deleteBis($assignmentSpStore) {

        $query = "DELETE FROM assignment_sp_store WHERE PARTICIPANT_id_participant = ? AND ENTERPRISE_STORE_id_enterprise  = ? AND ENTERPRISE_PROVIDER_id_enterprise  = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($assignmentSpStore->getOneParticipant()->getIdParticipant(), $assignmentSpStore->getOneStore()->getIdEnterprise(), $assignmentSpStore->getOneProvider()->getIdEnterprise(),$assignmentSpStore->getOnePurchasingFair()->getIdPurchasingFair()));
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