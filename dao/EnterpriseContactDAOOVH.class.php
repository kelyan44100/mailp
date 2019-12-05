<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class EnterpriseContactDAOOVH extends AbstractDAOOVH {
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all EnterpriseContact (the returned array may be empty)
     */
    public function findAll() {

	$result = array ();
	$query = '
	SELECT 
	ec.id_enterprise_contact,
	ec.civility_enterprise_contact,
	ec.surname_enterprise_contact,
	ec.name_enterprise_contact,
	ec.email_enterprise_contact,
	ec.registration_date,
	ec.ENTERPRISE_id_enterprise
	FROM enterprise_contact ec
	ORDER BY ec.email_enterprise_contact ASC';

	try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();			
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                list ( $idEnterpriseContact, $civility, $surname, $name, $email, $registrationDate, $oneEnterprise) = $row; // Like that $idEnterpriseContact = $row['id_enterprise_contact'] etc.
                $newEnterpriseContact = new EnterpriseContact($civility, $surname, $name, $email, $oneEnterprise, $registrationDate, $idEnterpriseContact);
                $result[] = $newEnterpriseContact; // Adds new EnterpriseContact to array
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
     * Returns the object of the searched EnterpriseContact
     */
    public function findById($searchedIdEnterpriseContact) {

        $result = NULL;
        $query = "
        SELECT 
        ec.id_enterprise_contact,
        ec.civility_enterprise_contact,
        ec.surname_enterprise_contact,
        ec.name_enterprise_contact,
        ec.email_enterprise_contact,
        ec.registration_date,
        ec.ENTERPRISE_id_enterprise
        FROM enterprise_contact ec
        WHERE ec.id_enterprise_contact = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdEnterpriseContact));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idEnterpriseContact, $civility, $surname, $name, $email, $registrationDate, $oneEnterprise) = $row; // Like that $idEnterpriseContact = $row['id_enterprise_contact'] etc.
                    $newEnterpriseContact = new EnterpriseContact($civility, $surname, $name, $email, $oneEnterprise, $registrationDate, $idEnterpriseContact);
                    $result = $newEnterpriseContact;
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
     * Returns the object of the searched EnterpriseContact
     */
    public function findByEnterprise($idEnterprise) {

        $result = NULL;
        $query = "
        SELECT 
        ec.id_enterprise_contact,
        ec.civility_enterprise_contact,
        ec.surname_enterprise_contact,
        ec.name_enterprise_contact,
        ec.email_enterprise_contact,
        ec.registration_date,
        ec.ENTERPRISE_id_enterprise
        FROM enterprise_contact ec 
        WHERE ec.ENTERPRISE_id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idEnterprise));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idEnterpriseContact, $civility, $surname, $name, $email, $registrationDate, $oneEnterprise) = $row; // Like that $idEnterpriseContact = $row['id_enterprise_contact'] etc.
                    $newEnterpriseContact = new EnterpriseContact($civility, $surname, $name, $email, $oneEnterprise, $registrationDate, $idEnterpriseContact);
                    $result = $newEnterpriseContact;
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
     * IF the EnterpriseContact id is -1, the EnterpriseContact does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the EnterpriseContact must be updated.
     */
    public function save($enterpriseContact) {
        if($enterpriseContact->getIdEnterpriseContact() == - 1) return $this->insert($enterpriseContact);
        else return $this->update($enterpriseContact);
    }

    /*
     * Inserts $enterpriseContact as a new record
     */
    public function insert($enterpriseContact) { 

        $queryEnterpriseContactTable = "INSERT INTO enterprise_contact "
                . "(civility_enterprise_contact, surname_enterprise_contact, name_enterprise_contact, email_enterprise_contact, registration_date, ENTERPRISE_id_enterprise) "
                . "VALUES(?,?,?,?,NOW(),?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($queryEnterpriseContactTable); 
            $qresult->execute(array(
                $enterpriseContact->getCivility(), 
                $enterpriseContact->getSurname(), 
                $enterpriseContact->getName(), 
                $enterpriseContact->getEmail(), 
                $enterpriseContact->getOneEnterprise()->getIdEnterprise()
            ));
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit(); // If all goes well the transaction is validated

            // $this->pdo = NULL;
            return  ($qresult->rowCount()) ? $lastInsertId : 0; // Returns the last inserted row if insert is OK
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
     * Update one EnterpriseContact
     */
    public function update($enterpriseContact) { 

        $query = "UPDATE enterprise_contact "
                . "SET civility_enterprise_contact = ?, "
                . "surname_enterprise_contact = ?, "
                . "name_enterprise_contact = ?, "
                . "email_enterprise_contact = ?, "
                . "registration_date = NOW(), "
                . "ENTERPRISE_id_enterprise = ? "
                . "WHERE id_enterprise_contact = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array(
                $enterpriseContact->getCivility(), 
                $enterpriseContact->getSurname(), 
                $enterpriseContact->getName(), 
                $enterpriseContact->getEmail(), 
                $enterpriseContact->getOneEnterprise()->getIdEnterprise(),
                $enterpriseContact->getIdEnterpriseContact()));
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
     * Deactivate a EnterpriseContact (date_deletion => NOW())
     */
    public function deactivate($enterpriseContact) { ; }
    	

    /*
     * Delete a EnterpriseContact
     */
    public function delete($enterpriseContact) {

        $query = "DELETE FROM enterprise_contact WHERE id_enterprise_contact = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($enterpriseContact->getIdEnterpriseContact()));
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