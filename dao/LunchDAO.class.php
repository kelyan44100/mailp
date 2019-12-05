<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class LunchDAO extends AbstractDAO {

    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all lunches (the returned array may be empty)
     */
    public function findAll() { ; }

    /*
     * Returns the object of the searched lunch
     */
    public function findById($searchedIdLunch) { ; }

    /*
     * IF the lunch id is -1, the lunch does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the lunch must be updated.
     */
    public function save($lunch) { ; }

    /*
     * Inserts $lunch as a new record
     */
    public function insert($lunch) {

        $query = "INSERT INTO lunch (ENTERPRISE_id_enterprise, PURCHASING_FAIR_id_purchasing_fair, lunches_planned, lunches_canceled, lunches_details, id_participant) VALUES(?,?,?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(
                array(
                $lunch->getOneEnterprise(),
                $lunch->getOnePurchasingFair(),
                $lunch->getLunchesPlanned(),
                $lunch->getLunchesCanceled(),
                $lunch->getLunchesDetails(),
                $lunch->getIdParticipant(),
                ));
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            return ($qresult->rowCount()) ? 1 : 0; // Returns the last inserted row if insert is OK - Here there is no ID so we choose 1 not last inserted id
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
     * Update one lunch
     */
    public function update($lunch) { 
        //print_r($lunch);

        $query = "UPDATE lunch SET lunches_planned = ?, lunches_canceled = ? WHERE ENTERPRISE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ? and lunches_details = ? and id_participant = ? ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($lunch->getLunchesPlanned(), $lunch->getLunchesCanceled(), $lunch->getOneEnterprise(), $lunch->getOnePurchasingFair(), $lunch->getLunchesDetails(), $lunch->getIdParticipant()));
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
     * Deactivate a lunch (date_deletion => NOW())
     */
    public function deactivate($lunch) { ; }	

    /*
     * Delete a lunch
     */
    public function delete($lunch) { ; }
    
    /*
     * Delete a lunch
     */
    public function deleteAllByPf($idPurchasingFair) {

        $query = "DELETE FROM lunch WHERE PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair));
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

    public function DeleteLunchByThree($idEnterprise, $idPurchasingFair, $Day) {

        $query = "DELETE FROM lunch WHERE PURCHASING_FAIR_id_purchasing_fair = ? and ENTERPRISE_id_enterprise = ? and lunches_details = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $idEnterprise, $Day));
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

    public function DeleteLunchByFour($idEnterprise, $idPurchasingFair, $Day, $id_participant) {

        $query = "DELETE FROM lunch WHERE PURCHASING_FAIR_id_purchasing_fair = ? and ENTERPRISE_id_enterprise = ? and lunches_details = ? and id_participant = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $idEnterprise, $Day, $id_participant));
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
     * Delete all Lunch for one Enterprise
     * https://stackoverflow.com/questions/8598791/sql-delete-with-inner-join
     */
    public function deleteAllByProviders() {

        $query = "DELETE l.* FROM lunch l "
                . "INNER JOIN enterprise e ON e.id_enterprise = l.ENTERPRISE_id_enterprise "
                . "WHERE e.PROFILE_id_profile = 1";

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

    /*
     * Get the lunch for one Enterprise and Pf(return an object)
     */
    public function findByEnterpriseAndPF($idEnterprise, $idPurchasingFair) {

        $result = null;

        $query = "
        SELECT *
        FROM lunch
        WHERE ENTERPRISE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idEnterprise, $idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated
            //print_r($qresult);
            while( $row = $qresult->fetch() ) {
                    list ( $idLunch, $idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant ) = $row; // Like that $idEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newLunch = new Lunch($idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant, $idLunch);
                    $result[] = $newLunch;
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

    public function findLunchByPfAndDay($idPurchasingFair, $Day) {

        $result = null;

        $query = "
        SELECT *
        FROM lunch
        WHERE PURCHASING_FAIR_id_purchasing_fair = ? and lunches_details = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $Day));
            $this->pdo->commit(); // If all goes well the transaction is validated
            //print_r($qresult);
            while( $row = $qresult->fetch() ) {
                    list ( $idLunch, $idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant ) = $row; // Like that $idEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newLunch = new Lunch($idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant, $idLunch);
                    $result[] = $newLunch;
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

    public function findArrayDayLunch($idEnterprise, $idPurchasingFair) {

        $result = null;

        $query = "
        SELECT distinct lunches_details
        FROM lunch
        WHERE ENTERPRISE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idEnterprise, $idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated
            //print_r($qresult);
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

    public function findByEnterpriseAndPFAndDay($idEnterprise, $idPurchasingFair, $Day) {
        //print_r($Day);

        $result = null;

        $query = "
        SELECT *
        FROM lunch
        WHERE ENTERPRISE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ? and lunches_details = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query);
            $qresult->execute(array($idEnterprise, $idPurchasingFair, $Day));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                //print_r($row);
                    list ( $idLunch, $idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant ) = $row; // Like that $idEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newLunch = new Lunch($idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant, $idLunch);
                    $result = $newLunch;
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

    public function findByEnterpriseAndPFAndDay2($idEnterprise, $idPurchasingFair, $Day) {
        //print_r($Day);

        $result = null;

        $query = "
        SELECT *
        FROM lunch
        WHERE ENTERPRISE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ? and lunches_details = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query);
            $qresult->execute(array($idEnterprise, $idPurchasingFair, $Day));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idLunch, $idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant ) = $row; // Like that $idEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newLunch = new Lunch($idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant, $idLunch);
                    $result[] = $newLunch;
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

    public function findByEnterpriseAndPFAndDayBis($idEnterprise, $idPurchasingFair, $Day, $idParticipant) {
        //print_r($Day);

        $result = null;

        $query = "
        SELECT *
        FROM lunch
        WHERE ENTERPRISE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ? and lunches_details = ? and id_participant = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query);
            $qresult->execute(array($idEnterprise, $idPurchasingFair, $Day, $idParticipant));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idLunch, $idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant ) = $row; // Like that $idEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newLunch = new Lunch($idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant, $idLunch);
                    $result[] = $newLunch;
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
     * Get all the lunches for all Providers (return an array)
     */
    public function findAllByProviders() {

        $result = array();

        $query = "
        SELECT 
        l.id_lunch,
        l.ENTERPRISE_id_enterprise,
        l.PURCHASING_FAIR_id_purchasing_fair,
        l.lunches_planned,
        l.lunches_canceled,
        l.lunches_details,
        l.id_participant
        FROM lunch l
        INNER JOIN enterprise e ON e.id_enterprise = l.ENTERPRISE_id_enterprise
        WHERE e.PROFILE_id_profile = 1
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idLunch, $idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant ) = $row; // Like that $idEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newLunch = new Lunch($idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant, $idLunch);
                    $result = $newLunch;
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
     * Check if lunches exist for one purchasing fair
     */
    public function lunchesCalculated($idPurchasingFair) {
        
        $result = 0;

        $query = 'SELECT COUNT(*) AS "totLunches" FROM lunch WHERE PURCHASING_FAIR_id_purchasing_fair = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair));
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated
            
            if( $row = $qresult->fetch() ) {
                $result = $row['totLunches'];
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
}
?>