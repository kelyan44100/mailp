<?php
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class LogPriseRdvDAO {

    protected $pdo;
    	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all logs (the returned array may be empty)
     */
    public function findAll() {

        $result = array();
        $query = "SELECT * FROM log_prise_rdv";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $id, $idEnterprise, $actionDateTime, $idPurchasingFair, $jourSelect ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newLog = new LogPriseRdv($idEnterprise, $idPurchasingFair, $jourSelect);
                    $result[] = $newLog; // Adds new log to array
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
     * Returns the object of the searched log
     */
    public function findByIdEnterprise($searchedIdEnterprise) {

        $result = NULL;
        $query = "SELECT * FROM log_prise_rdv WHERE id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdEnterprise));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $id, $idEnterprise, $actionDateTime, $idPurchasingFair, $jourSelect ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newLog = new LogPriseRdv($idEnterprise, $idPurchasingFair, $jourSelect);
                    $result[] = $newLog; // Adds new log to array
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

    public function findByTwo($idPurchasingFair, $IdEnterprise) {

        $result = NULL;
        $query = "SELECT * FROM log_prise_rdv WHERE id_purchasingFair = ? and id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $IdEnterprise));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $id, $idEnterprise, $actionDateTime, $idPurchasingFair, $jourSelect ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newLog = new LogPriseRdv($idEnterprise, $idPurchasingFair, $jourSelect);
                    $result[] = $newLog; // Adds new log to array
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

    public function findByThree($idPurchasingFair, $jourSelect, $IdEnterprise) {

        $result = NULL;
        $query = "SELECT * FROM log_prise_rdv WHERE id_purchasingFair = ? and jourSelect = ? and id_enterprise <> ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $jourSelect, $IdEnterprise));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $id, $idEnterprise, $actionDateTime, $idPurchasingFair, $jourSelect ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newLog = new LogPriseRdv($idEnterprise, $idPurchasingFair, $jourSelect);
                    $result[] = $newLog; // Adds new log to array
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

    public function findByThreeBis($idPurchasingFair, $jourSelect, $IdEnterprise) {

        $result = NULL;
        $query = "SELECT * FROM log_prise_rdv WHERE id_purchasingFair = ? and jourSelect = ? and id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $jourSelect, $IdEnterprise));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $id, $idEnterprise, $actionDateTime, $idPurchasingFair, $jourSelect ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newLog = new LogPriseRdv($idEnterprise, $idPurchasingFair, $jourSelect);
                    $result[] = $newLog; // Adds new log to array
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
     * IF the log id is -1, the log does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the log must be updated.
     * It returs the number of lines inserted / modified (0 if failed)
     */
    public function save($log) {
        if($log->getId() == - 1) return $this->insert($log);
        else return $this->update($log);
    }

    /*
     * Inserts $log as a new record
     */
    public function insert($log) { 

        $query = "INSERT INTO log_prise_rdv (id_enterprise, action_date_time, id_purchasingFair, jourSelect) VALUES(?,NOW(),?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($log->getIdEnterprise(), $log->getIdPurchasingFair(), $log->getJourSelect()));
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            return ($qresult->rowCount()) ? $lastInsertId : 0; // Returns the last inserted row if insert is OK
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

    public function update($log) { ; }

    public function delete($IdEnterprise) {
        $query = "DELETE FROM log_prise_rdv WHERE id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($IdEnterprise));
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

    public function deleteBis($IdEnterprise, $idPurchasingFair/*, $jourSelect*/) {
        $query = "DELETE FROM log_prise_rdv WHERE id_enterprise = ? and id_purchasingFair = ?/* and jourSelect <> ?*/";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($IdEnterprise, $idPurchasingFair/*, $jourSelect*/));
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