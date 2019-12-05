<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class ParticipantDAOOVH extends AbstractDAOOVH {
        
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all participants (the returned array may be empty)
     */
    public function findAll() {

        $result = array();
        $query = "SELECT * FROM participant WHERE date_deletion_participant IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $civility, $surname, $name, $email, $dateDeletion ) = $row; // Like that $idParticipant = $row['id_participant'] etc.
                    $newParticipant = new Participant($civility, $surname, $name, $email, $dateDeletion, $idParticipant);
                    $result[] = $newParticipant; // Adds new Participant to array
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
     * Returns the collection (a simple array) of all participants (the returned array may be empty) as Salespersons
     * This means that if a non-empty result is returned, the participant is associated with a provider, so he's a Salesperson
     */
    public function findAllAsSalespersons() {

        $result = array();
        $query = '
        SELECT DISTINCT(p.id_participant), p.civility_participant, p.surname_participant, p.name_participant, p.email_participant, p.date_deletion_participant
        FROM participant p 
        INNER JOIN assignment_participant_enterprise ape ON ape.PARTICIPANT_id_participant = p.id_participant 
        INNER JOIN enterprise e ON e.id_enterprise = ape.ENTERPRISE_id_enterprise 
        INNER JOIN profile pr ON pr.id_profile = e.PROFILE_id_profile 
        WHERE pr.id_profile = 1 AND p.date_deletion_participant IS NULL';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $civility, $surname, $name, $email, $dateDeletion ) = $row; // Like that $idParticipant = $row['id_participant'] etc.
                    $newParticipant = new Participant($civility, $surname, $name, $email, $dateDeletion, $idParticipant);
                    $result[] = $newParticipant; // Adds new Participant to array
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
     * Returns the collection (a simple array) of all participants (the returned array may be empty) as Salespersons for a Provider given
     * This means that if a non-empty result is returned, the participant is associated with a provider, so he's a Salesperson
     */
    public function findAllParticipantsAsSalespersonsByProvider($idProvider) {

        $result = array();
        $query = '
        SELECT DISTINCT(p.id_participant), p.civility_participant, p.surname_participant, p.name_participant, p.email_participant, p.date_deletion_participant
        FROM participant p 
        INNER JOIN assignment_participant_enterprise ape ON ape.PARTICIPANT_id_participant = p.id_participant 
        INNER JOIN enterprise e ON e.id_enterprise = ape.ENTERPRISE_id_enterprise 
        INNER JOIN profile pr ON pr.id_profile = e.PROFILE_id_profile 
        WHERE pr.id_profile = 1 AND e.id_enterprise = ? AND p.date_deletion_participant IS NULL';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idProvider));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $civility, $surname, $name, $email, $dateDeletion ) = $row; // Like that $idParticipant = $row['id_participant'] etc.
                    $newParticipant = new Participant($civility, $surname, $name, $email, $dateDeletion, $idParticipant);
                    $result[] = $newParticipant; // Adds new Participant to array
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
     * Returns the collection (a simple array) of all participants (the returned array may be empty) as Salespersons for a Provider given and a Purchasing Fair
     * This means that if a non-empty result is returned, the participant is associated with a provider, so he's a Salesperson
     */
    public function findAllParticipantsAsSalespersonsByProviderAndPf($idProvider, $idPurchasingFair) {

        $result = array();
        $query = '
        SELECT DISTINCT(p.id_participant), p.civility_participant, p.surname_participant, p.name_participant, p.email_participant, p.date_deletion_participant
        FROM participant p 
        INNER JOIN assignment_participant_enterprise ape ON ape.PARTICIPANT_id_participant = p.id_participant 
        INNER JOIN enterprise e ON e.id_enterprise = ape.ENTERPRISE_id_enterprise 
        INNER JOIN profile pr ON pr.id_profile = e.PROFILE_id_profile
        INNER JOIN assignment_sp_store ass ON ass.PARTICIPANT_id_participant = p.id_participant
        INNER JOIN purchasing_fair pf ON pf.id_purchasing_fair = ass.PURCHASING_FAIR_id_purchasing_fair
        WHERE pr.id_profile = 1 AND e.id_enterprise = ? AND p.date_deletion_participant IS NULL AND pf.id_purchasing_fair = ?
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idProvider, $idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $civility, $surname, $name, $email, $dateDeletion ) = $row; // Like that $idParticipant = $row['id_participant'] etc.
                    $newParticipant = new Participant($civility, $surname, $name, $email, $dateDeletion, $idParticipant);
                    $result[] = $newParticipant; // Adds new Participant to array
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
     * Returns the object of the searched participant
     */
    public function findById($searchedIdParticipant) {

        $result = NULL;
        $query = "SELECT * FROM participant WHERE id_participant = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdParticipant));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idParticipant, $civility, $surname, $name, $email, $dateDeletion ) = $row; // Like that $idParticipant = $row['id_participant'] etc.
                    $newParticipant = new Participant($civility, $surname, $name, $email, $dateDeletion, $idParticipant);
                    $result = $newParticipant;
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
     * IF the participant id is -1, the participant does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the participant must be updated.
     * It returs the number of lines inserted / modified (0 if failed)
     */
    public function save($participant) {
        if($participant->getIdParticipant() == - 1) { return $this->insert($participant); }
        else { return $this->update($participant); }
    }

    /*
     * Inserts $participant as a new record
     */
    public function insert($participant) { 

        $query = "INSERT INTO participant (civility_participant, surname_participant, name_participant, email_participant) VALUES(?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($participant->getCivility(), $participant->getSurname(), $participant->getName(), $participant->getEmail()));
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
     * Update one participant
     */
    public function update($participant) { 

        $query = "UPDATE participant SET civility_participant = ?, surname_participant = ?, name_participant = ?, email_participant = ? WHERE id_participant = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($participant->getCivility(), $participant->getSurname(), $participant->getName(), $participant->getEmail(), $participant->getIdparticipant()));
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
     * Deactivate a participant (date_deletion => NOW())
     */
    public function deactivate($participant) {

        $query = "UPDATE participant SET date_deletion_participant = NOW() WHERE id_participant = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($participant->getIdParticipant()));
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
     * Delete a participant
     */
    public function delete($participant) {

        $query = "DELETE FROM participant WHERE id_participant = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($participant->getIdParticipant()));
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
     * Returns the collection (a simple array) of all Participants data for the Purchasing Fair
     * Here we select the VIEW stored in database
     */
    public function summaryParticipants($idPurchasingFair) {

        $result = array();
        
        // Participants (Stores) U Participants (Providers)
        // Warning: PhpMyAdmin crash the request, in MySql console or PHP script it works normally
        // Src : http://forum.wampserver.com/read.php?1,147750
        $query = '
        (SELECT 
        ppant.civility_participant, 
        ppant.surname_participant, 
        ppant.name_participant, 
        ppant.email_participant, 
        ent.name_enterprise, 
        pro.name_profile
        FROM participation AS ption
        INNER JOIN participant AS ppant ON ppant.id_participant = ption.PARTICIPANT_id_participant
        INNER JOIN assignment_participant_enterprise AS ape ON ape.PARTICIPANT_id_participant = ppant.id_participant
        INNER JOIN enterprise AS ent ON ent.id_enterprise = ape.ENTERPRISE_id_enterprise
        INNER JOIN profile AS pro ON pro.id_profile = ent.PROFILE_id_profile
        INNER JOIN purchasing_fair AS pf ON pf.id_purchasing_fair = ption.PURCHASING_FAIR_id_purchasing_fair
        WHERE pro.id_profile = 2 AND pf.id_purchasing_fair = ?)
        UNION
        (SELECT 
        couplesSalespersonsProviders.civility_participant, 
        couplesSalespersonsProviders.surname_participant, 
        couplesSalespersonsProviders.name_participant, 
        couplesSalespersonsProviders.email_participant, 
        GROUP_CONCAT(couplesSalespersonsProviders.name_enterprise SEPARATOR ","), 
        couplesSalespersonsProviders.name_profile
        FROM 
        (SELECT 
        ppant.civility_participant, 
        ppant.surname_participant, 
        ppant.name_participant, 
        ppant.email_participant, 
        CONCAT(ent.name_enterprise, "(", LEFT(tp.name_typeof_provider, 1), ")") AS "name_enterprise",
        pro.name_profile
        FROM assignment_sp_store AS ass
        INNER JOIN participant AS ppant ON ppant.id_participant = ass.PARTICIPANT_id_participant
        INNER JOIN enterprise AS ent ON ent.id_enterprise = ass.ENTERPRISE_PROVIDER_id_enterprise
        INNER JOIN profile AS pro ON pro.id_profile = ent.PROFILE_id_profile
        INNER JOIN purchasing_fair AS pf ON pf.id_purchasing_fair = ass.PURCHASING_FAIR_id_purchasing_fair
        INNER JOIN typeof_provider AS tp ON tp.id_typeof_provider = ent.TYPEOF_PROVIDER_id_typeof_provider
        WHERE pro.id_profile = 1 AND pf.id_purchasing_fair = ?
	GROUP BY 1,2,3,4,5,6) 
        AS couplesSalespersonsProviders
        GROUP BY 1,2,3,4,6
        )
        ORDER BY 2 ASC, 3 ASC, 4 ASC
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch(PDO::FETCH_ASSOC) ) { $result[] = $row; }

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