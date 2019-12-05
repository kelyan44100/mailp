<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class ParticipationDAO extends AbstractDAO {
    
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods
    
   /*
     * Returns the collection (a simple array) of all Participations (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM participation";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idPurchasingFair, $passwordParticipant, $lunch ) = $row; // Like that $idParticipant = $row['PARTICIPANT_id_participant'] etc.
                    $newParticipation = new Participation($idParticipant, $idPurchasingFair, $passwordParticipant, $lunch);
                    $result[] = $newParticipation; // Adds new profile to array
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
     * Returns the collection (a simple array) of all Participations for one Enterprise and one PurchasingFair
     */
    public function findAllByEnterpriseAndPurchasingFair($oneEnterprise, $onePurchasingFair) {

        $result = array();
        $query = "
        SELECT pion.PARTICIPANT_id_participant, pion.PURCHASING_FAIR_id_purchasing_fair, pion.password_participant, pion.lunch
        FROM participation AS pion
        INNER JOIN purchasing_fair ON purchasing_fair.id_purchasing_fair = pion.PURCHASING_FAIR_id_purchasing_fair
        INNER JOIN participant ON participant.id_participant = pion.PARTICIPANT_id_participant
        INNER JOIN assignment_participant_enterprise AS ape ON ape.PARTICIPANT_id_participant = participant.id_participant
        WHERE ape.ENTERPRISE_id_enterprise = ? AND purchasing_fair.id_purchasing_fair = ? AND participant.date_deletion_participant IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($oneEnterprise->getIdEnterprise(), $onePurchasingFair->getIdPurchasingFair()));
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idPurchasingFair, $passwordParticipant, $lunch ) = $row; // Like that $idParticipant = $row['PARTICIPANT_id_participant'] etc.
                    $newParticipation = new Participation($idParticipant, $idPurchasingFair, $passwordParticipant, $lunch);
                    $result[] = $newParticipation; // Adds new profile to array
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
     * Returns the object of the searched participation
     */
    public function findByTwoIds($searchedIdParticipant, $searchedIdPurchasingFair) {

        $result = NULL;
        $query = "SELECT * FROM participation WHERE PARTICIPANT_id_participant = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdParticipant, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $idPurchasingFair, $passwordParticipant, $lunch ) = $row; // Like that $idParticipant = $row['PARTICIPANT_id_participant'] etc.
                    $newparticipation = new Participation($idParticipant, $idPurchasingFair, $passwordParticipant, $lunch);
                    $result = $newparticipation;
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

    public function save($participation) { ; }

    /*
     * Inserts $assignmentParticipantEnterprise as a new record
     */
    public function insert($participation) { 

        $query = "INSERT INTO participation (PARTICIPANT_id_participant, PURCHASING_FAIR_id_purchasing_fair, password_participant, lunch) VALUES(?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($participation->getOneParticipant()->getIdParticipant(), $participation->getOnePurchasingFair()->getIdPurchasingFair(), $participation->getPasswordparticipant(), $participation->getLunch()));
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
    public function update($participation) { ; }

    /*
     * Deactivate an APE (date_deletion => NOW())
     */
    public function deactivate($participation) { ; }	

    /*
     * Delete an APE
     */
    public function delete($participation) {

        $query = "DELETE FROM participation WHERE PARTICIPANT_id_participant = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($participation->getOneParticipant()->getIdParticipant(), $participation->getOnePurchasingFair()->getIdPurchasingFair()));
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