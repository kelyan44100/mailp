<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class DepartmentDAOOVH extends AbstractDAOOVH {

    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all departments (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM department";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idDepartment, $name) = $row; // Like that $idProfile = $row['id_department'] etc.
                    $newDepartment = new Department($name, $idDepartment);
                    $result[] = $newDepartment; // Adds new profile to array
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
     * Returns the object of the searched department
     */
    public function findById($searchedIdDepartment) {

        $result = NULL;
        $query = "SELECT * FROM department WHERE id_department = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdDepartment));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idDepartment, $name ) = $row; // Like that $idProfile = $row['id_department'] etc.
                    $newDepartment = new Department($name, $idDepartment);
                    $result = $newDepartment;
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
     * IF the department id is -1, the department does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the department must be updated.
     */
    public function save($department) {
        if($department->getIdDepartment() == - 1) return $this->insert($department);
        else return $this->update($department);
    }

    /*
     * Inserts $department as a new record
     */
    public function insert($department) { ; }

    /*
     * Update one department
     */
    public function update($department) { ; }

    /*
     * Deactivate a department (date_deletion => NOW())
     */
    public function deactivate($department) { ; }	

    /*
     * Delete a department
     */
    public function delete($department) {

        $query = "DELETE FROM department WHERE id_department = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($department->getIdDepartment()));
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
     * Get the department enterprise (return an object)
     */
    public function findByEnterprise(Enterprise $enterprise) {

        $result = null;

        $query = "
        SELECT d.id_department, d.name_department
        FROM enterprise AS e
        INNER JOIN department As d ON d.id_department = e.DEPARTMENT_id_department
        WHERE e.id_enterprise = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($enterprise->getIdEnterprise()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idDepartment, $name ) = $row; // Like that $idProfile = $row['id_department'] etc.
                    $newDepartment = new Department($name, $idDepartment);
                    $result = $newDepartment;
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