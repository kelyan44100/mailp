<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class LogDAOOVH extends AbstractDAOOVH {
    	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all logs (the returned array may be empty)
     */
    public function findAll() {

        $result = array();
        $query = "SELECT * FROM log ORDER BY action_datetime DESC";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idLog, $idEnterprise, $ipAddress, $actionDescription, $actionDateTime ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newLog = new Log($idEnterprise, $ipAddress, $actionDescription, $actionDateTime, $idLog);
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
    public function findById($searchedIdLog) {

        $result = NULL;
        $query = "SELECT * FROM log WHERE id_log = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdLog));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idLog, $idEnterprise, $ipAddress, $actionDescription, $actionDateTime ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newLog = new Log($idEnterprise, $ipAddress, $actionDescription, $actionDateTime, $idLog);
                    $result = $newLog;
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
        if($log->getIdLog() == - 1) return $this->insert($log);
        else return $this->update($log);
    }

    /*
     * Inserts $log as a new record
     */
    public function insert($log) { 

        $query = "INSERT INTO log (ENTERPRISE_id_enterprise, ip_address, action_description, action_datetime) VALUES(?,?,?,NOW())";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($log->getOneEnterprise()->getIdEnterprise(), $log->getIpAddress(), $log->getActionDescription()));
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

    /*
     * Update one log
     */
    public function update($log) { ; }

    /*
     * Deactivate a log (date_deletion => NOW())
     */
    public function deactivate($log) { ; }	

    /*
     * Delete a log
     */
    public function delete($log) { ; }
    
    /*
     * Returns the collection (a simple array) of all logs (the returned array may be empty) for one Enterprise
     */
    public function findByEnterprise($enterprise) {

        $result = array();
        $query = "SELECT * FROM log WHERE ENTERPRISE_id_enterprise = ? ORDER BY action_datetime DESC";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($enterprise->getIdEnterprise()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idLog, $idEnterprise, $ipAddress, $actionDescription, $actionDateTime ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newLog = new Log($idEnterprise, $ipAddress, $actionDescription, $actionDateTime, $idLog);
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
}
?>