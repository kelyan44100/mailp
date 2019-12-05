<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class AssignmentParticipantDepartmentDAOOVH extends AbstractDAOOVH {
    
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods
    
   /*
     * Returns the collection (a simple array) of all AssignmentSalespersonDepartment (ASPD) (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM assignment_participant_department";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idDepartment ) = $row; // Like that $idParticipant = $row['PARTICIPANT_id_participant'] etc.
                    $newAPD = new AssignmentParticipantDepartment($idParticipant, $idDepartment);
                    $result[] = $newAPD; // Adds new profile to array
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

    public function findById($searchedId) { ; }

    /*
     * Returns the object of the searched profile
     */
    public function findByTwoIds($searchedIdParticipant, $searchedIdDepartment) {

        $result = NULL;
        $query = "SELECT * FROM assignment_participant_department WHERE PARTICIPANT_id_participant = ? AND DEPARTMENT_id_department = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdParticipant, $searchedIdDepartment));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idDepartment ) = $row; // Like that $idSalesperson = $row['SALESPERSON_id_salesperson'] etc.
                    $newAPD = new AssignmentParticipantDepartment($idParticipant, $idDepartment);
                    $result = $newAPD;
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
     * Returns the object of the searched ape
     */
    public function findByParticipant($searchedIdParticipant) {

        $result = array();
        $query = "SELECT * FROM assignment_participant_department WHERE PARTICIPANT_id_participant = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdParticipant));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idDepartment ) = $row; // Like that $idSalesperson = $row['SALESPERSON_id_salesperson'] etc.
                    $newAPD = new AssignmentParticipantDepartment($idParticipant, $idDepartment);
                    $result[] = $newAPD;
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
    
    public function save($assignmentParticipantDepartment) { ; }

    /*
     * Inserts $assignmentparticipantDepartment as a new record
     */
    public function insert($assignmentParticipantDepartment) { 

        $query = "INSERT INTO assignment_participant_department (PARTICIPANT_id_participant, DEPARTMENT_id_department) VALUES(?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($assignmentParticipantDepartment->getOneParticipant()->getIdParticipant(), $assignmentParticipantDepartment->getOneDepartment()->getIdDepartment()));
//            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            return ($qresult->rowCount()) ? 1 : 0; // Returns the last inserted row if insert is OK
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
     * Update one APD
     */
    public function update($assignmentParticipantDepartment) { ; }

    /*
     * Deactivate an APD (date_deletion => NOW())
     */
    public function deactivate($assignmentParticipantDepartment) { ; }	

    /*
     * Delete an APD
     */
    public function delete($assignmentParticipantDepartment) {

        $query = "DELETE FROM assignment_participant_department WHERE PARTICIPANT_id_participant = ? AND DEPARTMENT_id_department = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($assignmentParticipantDepartment->getOneParticipant()->getIdParticipant(), $assignmentParticipantDepartment->getOneDepartment()->getIdDepartment()));
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