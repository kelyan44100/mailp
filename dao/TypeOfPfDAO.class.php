<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class TypeOfPfDAO extends AbstractDAO {
    	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all logs (the returned array may be empty)
     */
    public function findAll() {

        $result = array();
        $query = "SELECT * FROM typeof_pf ORDER BY 1 ASC";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idTypeOfPf, $nameTypeOfPf ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newTypeOfPf = new TypeOfPf($nameTypeOfPf, $idTypeOfPf);
                    $result[] = $newTypeOfPf; // Adds new log to array
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
    public function findById($searchedIdTypeOfPf) {

        $result = NULL;
        $query = "SELECT * FROM typeof_pf WHERE id_typeof_pf = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdTypeOfPf));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idTypeOfPf, $nameTypeOfPf ) = $row; // Like that $idLog = $row['id_log'] etc.
                    $newTypeOfPf = new TypeOfPf($nameTypeOfPf, $idTypeOfPf);
                    $result = $newTypeOfPf;
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

    public function insert($typeOfPf) { ; }

    public function update($typeOfPf) { ; }

    public function deactivate($typeOfPf) { ; }	

    public function delete($typeOfPf) { ; }
    
}
?>