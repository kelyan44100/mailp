<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class AssignmentParticipantEnterpriseDAOOVH extends AbstractDAOOVH {
    
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods
    
   /*
     * Returns the collection (a simple array) of all AssignmentParticipantEnterprise (APE) (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM assignment_participant_enterprise";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idEnterprise ) = $row; // Like that $idParticipant = $row['PARTICIPANT_id_participant'] etc.
                    $newAPE = new AssignmentParticipantEnterprise($idParticipant, $idEnterprise);
                    $result[] = $newAPE; // Adds new profile to array
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
    public function findByTwoIds($searchIdParticipant, $searchedIdEnterprise) {

        $result = NULL;
        $query = "SELECT * FROM assignment_participant_enterprise WHERE PARTICIPANT_id_participant = ? AND ENTERPRISE_id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchIdParticipant, $searchedIdEnterprise));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idEnterprise ) = $row; // Like that $idParticipant = $row['PARTICIPANT_id_participant'] etc.
                    $newAPE = new AssignmentParticipantEnterprise($idParticipant, $idEnterprise);
                    $result = $newAPE;
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
     * Returns the object of the searched profile
     */
    public function findByOneId($searchedIdParticipant) {

        $result = array();
        $query = "SELECT * FROM assignment_participant_enterprise WHERE PARTICIPANT_id_participant = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdParticipant));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idEnterprise ) = $row; // Like that $idParticipant = $row['PARTICIPANT_id_participant'] etc.
                    $newAPE = new AssignmentParticipantEnterprise($idParticipant, $idEnterprise);
                    $result[] = $newAPE;
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

    public function save($assignmentParticipantEnterprise) { ; }

    /*
     * Inserts $assignmentParticipantEnterprise as a new record
     */
    public function insert($assignmentParticipantEnterprise) { 

        $query = "INSERT INTO assignment_participant_enterprise (PARTICIPANT_id_participant, ENTERPRISE_id_enterprise) VALUES(?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($assignmentParticipantEnterprise->getOneParticipant()->getIdParticipant(), $assignmentParticipantEnterprise->getOneEnterprise()->getIdEnterprise()));
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
     * Update one APE
     */
    public function update($assignmentParticipantEnterprise) { ; }

    /*
     * Deactivate an APE (date_deletion => NOW())
     */
    public function deactivate($assignmentParticipantEnterprise) { ; }	

    /*
     * Delete an APE
     */
    public function delete($assignmentParticipantEnterprise) {

        $query = "DELETE FROM assignment_participant_enterprise WHERE PARTICIPANT_id_participant = ? AND ENTERPRISE_id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($assignmentParticipantEnterprise->getOneParticipant()->getIdParticipant(), $assignmentParticipantEnterprise->getOneEnterprise()->getIdEnterprise()));
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
     * Returns the collection (a simple array) of all AssignmentParticipantEnterprise (APE) (the returned array may be empty) for a given Enterprise
     */
    public function findAllAssignmentsParticipantEnterpriseForOneEnterprise($searchedIdEnterprise) {

        $result = array();
        $query = "SELECT * FROM assignment_participant_enterprise ape WHERE ape.ENTERPRISE_id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdEnterprise));
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idEnterprise ) = $row; // Like that $idParticipant = $row['PARTICIPANT_id_participant'] etc.
                    $newAPE = new AssignmentParticipantEnterprise($idParticipant, $idEnterprise);
                    $result[] = $newAPE; // Adds new profile to array
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