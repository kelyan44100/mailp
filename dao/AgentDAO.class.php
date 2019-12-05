<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class AgentDAO extends AbstractDAO {
        
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all agents (the returned array may be empty)
     */
    public function findAll() {

        $result = array();
        $query = "SELECT * FROM agent";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idAgent, $civility, $surname, $name, $addressLine1, $addressLine2, $providersAgent, $dateDeletion ) = $row; // Like that $idAgent = $row['id_agent'] etc.
                    $newAgent = new Agent($civility, $surname, $name, $addressLine1, $addressLine2, $providersAgent, $dateDeletion, $idAgent);
                    $result[] = $newAgent; // Adds new Agent to array
            }

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
     * Returns the object of the searched agent
     */
    public function findById($searchedIdAgent) {

        $result = NULL;
        $query = "SELECT * FROM agent WHERE id_agent = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdAgent));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idAgent, $civility, $surname, $name, $addressLine1, $addressLine2, $providersAgent, $dateDeletion ) = $row; // Like that $idAgent = $row['id_agent'] etc.
                    $newAgent = new Agent($civility, $surname, $name, $addressLine1, $addressLine2,  $providersAgent, $dateDeletion, $idAgent);
                    $result = $newAgent;
            }

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
     * IF the agent id is -1, the agent does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the agent must be updated.
     * It returs the number of lines inserted / modified (0 if failed)
     */
    public function save($agent) {
        if($agent->getIdAgent() == - 1) { return $this->insert($agent); }
        else { return $this->update($agent); }
    }

    /*
     * Inserts $agent as a new record
     */
    public function insert($agent) { 

        $query = "INSERT INTO agent (civility_agent, surname_agent, name_agent, address_line_1_agent, address_line_2_agent, providers_agent) VALUES(?,?,?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array( $agent->getCivility(), $agent->getSurname(), $agent->getName(), $agent->getAddressLine1(), $agent->getAddressLine2(), $agent->getProviders() ));
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

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
     * Update one agent
     */
    public function update($agent) { 

        $query = "UPDATE agent SET civility_agent = ?, surname_agent = ?, name_agent = ?, address_line_1_agent = ?, address_line_2_agent = ?, providers_agent = ? WHERE id_agent = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($agent->getCivility(), $agent->getSurname(), $agent->getName(),$agent->getAddressLine1(), $agent->getAddressLine2(), $agent->getProviders(), $agent->getIdAgent()));
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
     * Deactivate a agent (date_deletion => NOW())
     */
    public function deactivate($agent) {

        $query = "UPDATE agent SET date_deletion_agent = NOW() WHERE id_agent = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($agent->getIdAgent()));
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
     * Delete a agent
     */
    public function delete($agent) {

        $query = "DELETE FROM agent WHERE id_agent = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($agent->getIdAgent()));
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