<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class LunchDAOOVH extends AbstractDAOOVH {

    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

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

        $query = "INSERT INTO lunch (ENTERPRISE_id_enterprise, PURCHASING_FAIR_id_purchasing_fair, lunches_planned, lunches_canceled) VALUES(?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(
                array(
                $lunch->getOneEnterprise()->getIdEnterprise(),
                $lunch->getOnePurchasingFair()->getIdPurchasingFair(),
                $lunch->getLunchesPlanned(),
                $lunch->getLunchesCanceled()
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

        $query = "UPDATE lunch SET lunches_canceled = ? WHERE ENTERPRISE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($lunch->getLunchesCanceled(), $lunch->getOneEnterprise()->getIdEnterprise(), $lunch->getOnePurchasingFair()->getIdPurchasingFair()));
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

            if( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled ) = $row; // Like that $idEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newLunch = new Lunch($idEnterprise, $idPurchasingFair, $lunchesPlanned, $lunchesCanceled);
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