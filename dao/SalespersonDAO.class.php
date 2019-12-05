<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class SalespersonDAO extends AbstractDAO {

    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all salespersons (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM salesperson WHERE date_deletion_salesperson IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idSalesperson, $civility, $surname, $name, $dateDeletion ) = $row; // Like that $idSalesperson = $row['id_salesperson'] etc.
                    $newSalesperson = new Salesperson($civility, $surname, $name, $dateDeletion, $idSalesperson);
                    $result[] = $newSalesperson; // Adds new salesperson to array
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
     * Returns the object of the searched salesperson
     */
    public function findById($searchedIdSalesperson) {

        $result = NULL;
        $query = "SELECT * FROM salesperson WHERE id_salesperson = ? AND date_deletion_salesperson IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdSalesperson));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idSalesperson, $civility, $surname, $name, $dateDeletion ) = $row; // Like that $idProfile = $row['id_profile'] etc.
                    $newSalesperson = new Salesperson($civility, $surname, $name, $dateDeletion, $idSalesperson);
                    $result = $newSalesperson;
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
     * IF the salesperson id is -1, the salesperson does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the salesperson must be updated.
     */
    public function save($salesperson) {
        if($salesperson->getIdSalesperson() == - 1) return $this->insert($salesperson);
        else return $this->update($salesperson);
    }

    /*
     * Inserts $salesperson as a new record
     */
    public function insert($salesperson) { 

        $query = "INSERT INTO salesperson (civility_salesperson, surname_salesperson, name_salesperson) VALUES(?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($salesperson->getCivility(),$salesperson->getSurname(),$salesperson->getName()));
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
     * Update one salesperson
     */
    public function update($salesperson) { 

        $query = "UPDATE salesperson SET civility_salesperson = ?, surname_salesperson = ?, name_salesperson = ? WHERE id_salesperson = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($salesperson->getCivility(),$salesperson->getSurname(),$salesperson->getName(),$salesperson->getIdSalesperson()));
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
     * Deactivate a salesperson (date_deletion => NOW())
     */
    public function deactivate($salesperson) {

        $query = "UPDATE salesperson SET date_deletion_salesperson = NOW() WHERE id_salesperson = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($salesperson->getIdSalesperson()));
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
     * Delete a salesperson
     */
    public function delete($salesperson) {

        $query = "DELETE FROM salesperson WHERE id_salesperson = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($salesperson->getIdSalesperson()));
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