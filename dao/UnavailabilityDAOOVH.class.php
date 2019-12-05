<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class UnavailabilityDAOOVH extends AbstractDAOOVH {
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all unavailabilities (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM unavailability WHERE date_deletion_unavailability IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idUnavailability, $startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair, $dateDeletion) = $row; // Like that $idUnavailability = $row['id_unavailability'] etc.
                    $newUnavailability = new Unavailability($startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair, $dateDeletion, $idUnavailability);
                    $result[] = $newUnavailability; // Adds new unavailability to array
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
     * Returns the object of the searched unavailability
     */
    public function findById($searchedIdUnavailability) {

        $result = NULL;
        $query = "SELECT * FROM unavailability WHERE id_unavailability = ? AND date_deletion_unavailability IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdUnavailability));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idUnavailability, $startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair, $dateDeletion) = $row; // Like that $idUnavailability = $row['id_unavailability'] etc.
                    $newUnavailability = new Unavailability($startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair, $dateDeletion, $idUnavailability);
                    $result = $newUnavailability;
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
     * IF the unavailability id is -1, the unavailability does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the unavailability must be updated.
     */
    public function save($unavailability) {
        if($unavailability->getIdUnavailability() == - 1) return $this->insert($unavailability);
        else return $this->update($unavailability);
    }

    /*
     * Inserts $unavailability as a new record
     */
    public function insert($unavailability) { 

        $query = "INSERT INTO unavailability (start_datetime, end_datetime, ENTERPRISE_id_enterprise, PURCHASING_FAIR_id_purchasing_fair) VALUES(?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($unavailability->getStartDatetime(), $unavailability->getEndDatetime(), $unavailability->getOneEnterprise()->getIdEnterprise(), $unavailability->getOnePurchasingFair()->getIdPurchasingFair()));
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            // return $qresult->rowCount(); // Returns the number of rows affected by the last SQL exec()
            return $lastInsertId;
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
     * Update one unavailability
     */
    public function update($unavailability) { 

        $query = "UPDATE unavailability SET start_datetime = ?, end_datetime = ?, ENTERPRISE_id_enterprise = ?, PURCHASING_FAIR_id_purchasing_fair = ? WHERE id_unavailability = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($unavailability->getStartDatetime(), $unavailability->getEndDatetime(), $unavailability->getOneEnterprise()->getIdEnterprise(), $unavailability->getOnePurchasingFair()->getIdPurchasingFair(), $unavailability->getIdUnavailability()));
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
     * Deactivate a an unavailability (date_deletion => NOW())
     */
    public function deactivate($unavailability) {

        $query = "UPDATE unavailability SET date_deletion_unavailability = NOW() WHERE id_unavailability = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($unavailability->getIdUnavailability()));
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
     * Delete a unavailability
     */
    public function delete($unavailability) {

        $query = "DELETE FROM unavailability WHERE id_unavailability = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($unavailability->getIdUnavailability()));
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
     * Get the enterprise unavailabilities (return an array of Unavailability objects, it can be empty)
     */
    public function findEnterpriseUnavailabilities(Enterprise $enterprise, PurchasingFair $purchasingFair) {

        $result = array();

        $query = "
        SELECT u.id_unavailability, u.start_datetime, u.end_datetime, u.ENTERPRISE_id_enterprise, u.PURCHASING_FAIR_id_purchasing_fair, u.date_deletion_unavailability 
        FROM unavailability AS u 
        INNER JOIN purchasing_fair AS p ON p.id_purchasing_fair = u.PURCHASING_FAIR_id_purchasing_fair 
        WHERE u.ENTERPRISE_id_enterprise = ? AND p.id_purchasing_fair = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($enterprise->getIdEnterprise(), $purchasingFair->getIdPurchasingFair()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idUnavailability, $startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair, $dateDeletion) = $row; // Like that $idUnavailability = $row['id_unavailability'] etc.
                    $newUnavailability = new Unavailability($startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair, $dateDeletion, $idUnavailability);
                    $result[] = $newUnavailability;
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
     * Returns the collection (a simple array) of all unavailabilities for a given purchasing fair (the returned array may be empty)
     */
    public function findPurchasingFairUnavailabilities(PurchasingFair $purchasingFair) {

        $result = array();

        $query = "
        SELECT u.id_unavailability, u.start_datetime, u.end_datetime, u.ENTERPRISE_id_enterprise, u.PURCHASING_FAIR_id_purchasing_fair, u.date_deletion_unavailability 
        FROM unavailability AS u 
        INNER JOIN purchasing_fair AS p ON p.id_purchasing_fair = u.PURCHASING_FAIR_id_purchasing_fair
        WHERE p.id_purchasing_fair = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($purchasingFair->getIdPurchasingFair()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idUnavailability, $startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair, $dateDeletion) = $row; // Like that $idUnavailability = $row['id_unavailability'] etc.
                    $newUnavailability = new Unavailability($startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair, $dateDeletion, $idUnavailability);
                    $result[] = $newUnavailability;
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