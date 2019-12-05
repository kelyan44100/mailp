<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class StoreWorkforceDAO extends AbstractDAO {

    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all StoreWorkForce (the returned array may be empty)
     */
    public function findAll() { ; }

    /*
     * Returns the object of the searched StoreWorkforce
     */
    public function findById($searchedIdStoreWorkforce) { ; }

    /*
     * IF the StoreWorkforce id is -1, the StoreWorkforce does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the StoreWorkforce must be updated.
     */
    public function save($storeWorkforce) { ; }

    /*
     * Inserts $storeWorkforce as a new record
     */
    public function insert($storeWorkforce) { 

        $query = "INSERT INTO store_workforce (ENTERPRISE_id_enterprise, outer_clothing, under_clothing, shoes) VALUES(?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(
                array(
                $storeWorkforce->getOneEnterprise()->getIdEnterprise(),
                $storeWorkforce->getOuterClothing(),
                $storeWorkforce->getUnderClothing(),
                $storeWorkforce->getShoes()
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
     * Update one StoreWorkforce
     */
    public function update($storeWorkforce) { 

        $query = "UPDATE store_workforce SET outer_clothing = ?, under_clothing = ?, shoes = ? WHERE ENTERPRISE_id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($storeWorkforce->getOuterClothing(), $storeWorkforce->getUnderClothing(), $storeWorkforce->getShoes(), $storeWorkforce->getOneEnterprise()->getIdEnterprise()));
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
     * Deactivate a StoreWorkforce (date_deletion => NOW())
     */
    public function deactivate($storeWorkforce) { ; }	

    /*
     * Delete a StoreWorkforce
     */
    public function delete($storeWorkforce) { ; }

    /*
     * Get the StoreWorkforce for one Enterprise (return an object)
     */
    public function findByEnterprise($idEnterprise) {

        $result = null;

        $query = "
        SELECT *
        FROM store_workforce
        WHERE ENTERPRISE_id_enterprise = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idEnterprise));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $outerClothing, $underClothing, $shoes ) = $row; // Like that $idEnterprise = $row['ENTERPRISE_id_enterprise'] etc.
                    $newStoreWorkforce = new StoreWorkforce($idEnterprise, $outerClothing, $underClothing, $shoes);
                    $result = $newStoreWorkforce;
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