<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class TypeOfProviderDAOOVH extends AbstractDAOOVH {
    	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all TypeOfProvider (the returned array may be empty)
     */
    public function findAll() {

        $result = array();
        $query = "SELECT * FROM typeof_provider ORDER BY 1 ASC";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idTypeOfProvider, $nameTypeOfProvider ) = $row; // Like that $idLog = $row['id_typeof_provider'] etc.
                    $newTypeOfProvider = new TypeOfProvider($nameTypeOfProvider, $idTypeOfProvider);
                    $result[] = $newTypeOfProvider; // Adds new log to array
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
    public function findById($searchedIdTypeOfProvider) {

        $result = NULL;
        $query = "SELECT * FROM typeof_provider WHERE id_typeof_provider = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdTypeOfProvider));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idTypeOfProvider, $nameTypeOfProvider ) = $row; // Like that $idLog = $row['id_typeof_provider'] etc.
                    $newTypeOfProvider = new TypeOfProvider($nameTypeOfProvider, $idTypeOfProvider);
                    $result = $newTypeOfProvider;
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


    public function save($log) { ; }

    public function insert($typeOfProvider) { ; }

    public function update($typeOfProvider) { ; }

    public function deactivate($typeOfProvider) { ; }	

    public function delete($typeOfProvider) { ; }
}
?>