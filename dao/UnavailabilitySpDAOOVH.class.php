<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class UnavailabilitySpDAOOVH extends AbstractDAOOVH {
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all unavailabilities (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM unavailability_sp WHERE date_deletion_unavailability_sp IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idUnavailability, $startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion) = $row; // Like that $idUnavailability = $row['id_unavailability'] etc.
                    $newUnavailabilitySp = new UnavailabilitySp($startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion, $idUnavailability);
                    $result[] = $newUnavailabilitySp; // Adds new unavailability to array
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
     * Returns the object of the searched unavailabilitySp
     */
    public function findById($searchedIdUnavailabilitySp) {

        $result = NULL;
        $query = "SELECT * FROM unavailability_sp WHERE id_unavailability_sp = ? AND date_deletion_unavailability_sp IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdUnavailabilitySp));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idUnavailability, $startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion) = $row; // Like that $idUnavailability = $row['id_unavailability'] etc.
                    $newUnavailabilitySp = new UnavailabilitySp($startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion, $idUnavailability);
                    $result = $newUnavailabilitySp;
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
     * Returns the object of the searched unavailabilitySp
     */
    public function findByIdParticipant($searchedIdParticipant) {

        $result = array();
        $query = "SELECT * FROM unavailability_sp WHERE PARTICIPANT_id_participant = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdParticipant));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idUnavailability, $startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion) = $row; // Like that $idUnavailability = $row['id_unavailability'] etc.
                    $newUnavailabilitySp = new UnavailabilitySp($startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion, $idUnavailability);
                    $result[] = $newUnavailabilitySp;
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
     * IF the unavailability id is -1, the unavailability does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the unavailability must be updated.
     */
    public function save($unavailabilitySp) {
        if($unavailabilitySp->getIdUnavailability() == - 1) return $this->insert($unavailabilitySp);
        else return $this->update($unavailabilitySp);
    }

    /*
     * Inserts $unavailabilitySp as a new record
     */
    public function insert($unavailabilitySp) { 

        $query = "INSERT INTO unavailability_sp (start_datetime, end_datetime, PARTICIPANT_id_participant, PURCHASING_FAIR_id_purchasing_fair) VALUES(?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($unavailabilitySp->getStartDatetime(), $unavailabilitySp->getEndDatetime(), $unavailabilitySp->getOneParticipant()->getIdParticipant(), $unavailabilitySp->getOnePurchasingFair()->getIdPurchasingFair()));
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            // return $qresult->rowCount(); // Returns the number of rows affected by the last SQL exec()
            return $lastInsertId;
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
     * Update one unavailabilitySp
     */
    public function update($unavailabilitySp) { 

        $query = "UPDATE unavailability_sp SET start_datetime = ?, end_datetime = ?, PARTICIPANT_id_participant = ?, PURCHASING_FAIR_id_purchasing_fair = ? WHERE id_unavailability_sp = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($unavailabilitySp->getStartDatetime(), $unavailabilitySp->getEndDatetime(), $unavailabilitySp->getOneParticipant()->getIdParticipant(), $unavailabilitySp->getOnePurchasingFair()->getIdPurchasingFair(), $unavailabilitySp->getIdUnavailability()));
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
     * Deactivate a an unavailabilitySp (date_deletion => NOW())
     */
    public function deactivate($unavailabilitySp) {

        $query = "UPDATE unavailability_sp SET date_deletion_unavailability_sp = NOW() WHERE id_unavailability_sp = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($unavailabilitySp->getIdUnavailability()));
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
     * Delete an unavailabilitySp
     */
    public function delete($unavailabilitySp) {

        $query = "DELETE FROM unavailability_sp WHERE id_unavailability_sp = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($unavailabilitySp->getIdUnavailability()));
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
     * Get the participant unavailabilities (return an array of UnavailabilitySp objects, it can be empty)
     */
    public function findParticipantUnavailabilitiesSp(Participant $participant, PurchasingFair $purchasingFair) {

        $result = array();

        $query = "
        SELECT u.id_unavailability_sp, u.start_datetime, u.end_datetime, u.PARTICIPANT_id_participant, u.PURCHASING_FAIR_id_purchasing_fair, u.date_deletion_unavailability_sp
        FROM unavailability_sp AS u 
        INNER JOIN purchasing_fair AS p ON p.id_purchasing_fair = u.PURCHASING_FAIR_id_purchasing_fair 
        WHERE u.PARTICIPANT_id_participant = ? AND p.id_purchasing_fair = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($participant->getIdParticipant(), $purchasingFair->getIdPurchasingFair()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idUnavailability, $startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion) = $row; // Like that $idUnavailability = $row['id_unavailability'] etc.
                    $newUnavailability = new UnavailabilitySp($startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion, $idUnavailability);
                    $result[] = $newUnavailability;
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
     * Returns the collection (a simple array) of all unavailabilitiesSp for a given purchasing fair (the returned array may be empty)
     */
    public function findPurchasingFairUnavailabilitiesSp(PurchasingFair $purchasingFair) {

        $result = array();

        $query = "
        SELECT u.id_unavailability_sp, u.start_datetime, u.end_datetime, u.PARTICIPANT_id_participant, u.PURCHASING_FAIR_id_purchasing_fair, u.date_deletion_unavailability_sp
        FROM unavailability_sp AS u 
        INNER JOIN purchasing_fair AS p ON p.id_purchasing_fair = u.PURCHASING_FAIR_id_purchasing_fair
        WHERE p.id_purchasing_fair = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($purchasingFair->getIdPurchasingFair()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idUnavailabilitySp, $startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion) = $row; // Like that $idUnavailability = $row['id_unavailability'] etc.
                    $newUnavailabilitySp = new UnavailabilitySp($startDatetime, $endDatetime, $idParticipant, $idPurchasingFair, $dateDeletion, $idUnavailabilitySp);
                    $result[] = $newUnavailabilitySp;
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
     * Returns an array containing the Salespersons and their unavailabilities concatenated, to explode
     */
    public function findSpWithUnavByEntAndPf($idEnterprise, $idPurchasingFair) {
        
        $result = array();

        $query = '
        SELECT pa.id_participant, GROUP_CONCAT(CONCAT(usp.start_datetime, "|", usp.end_datetime) SEPARATOR ",") AS unav
        FROM enterprise AS e
        INNER JOIN assignment_participant_enterprise AS ape ON ape.ENTERPRISE_id_enterprise = e.id_enterprise
        INNER JOIN participant AS pa ON pa.id_participant = ape.PARTICIPANT_id_participant
        INNER JOIN unavailability_sp AS usp ON usp.PARTICIPANT_id_participant = pa.id_participant
        WHERE e.id_enterprise = ? and usp.PURCHASING_FAIR_id_purchasing_fair = ?
        GROUP BY 1
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idEnterprise, $idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $unavailabilities) = $row; // Like that $idParticipant = $row['$idParticipant'] etc.
                    // $result[] = array($idParticipant => $unavailabilities);
                    $arrayUnav = explode(',', $unavailabilities);
                    foreach($arrayUnav as $key => $unav) { 
                        $arrayUnav[$key] = explode('|', $unav); 
                        $arrayUnav[$key][0] = new DateTime($arrayUnav[$key][0]); 
                        $arrayUnav[$key][1] = new DateTime($arrayUnav[$key][1]); 
                    }
                    
//                    foreach($arrayUnav as $key => $unav) { 
//                        echo $arrayUnav[$key][0]->format('Y-m-d H:i:s'); 
//                        echo $arrayUnav[$key][1]->format('Y-m-d H:i:s').'<br>'; 
//                    }
                    $result[$idParticipant] = $arrayUnav;
                    
                    /*
                    Format :
                    array (size=2) 
                        41 => array (size=5) 
                            0 => 
                                array (size=2)
                                    0 => 
                                    object(DateTime)[295]
                                    ...
                                    1 => 
                                      object(DateTime)[296]
                                    ...
                            ..
                        ..
                     */
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