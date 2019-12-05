<?php
require_once dirname ( __FILE__ ) . '/AbstractDAO.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class SpecialGuestDAO extends AbstractDAO {
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all SpecialGuest (the returned array may be empty)
     */
    public function findAll() {

	$result = array ();
	$query = 'SELECT * FROM special_guest';

	try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();			
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                list ( $idSpecialGuest, $oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days ) = $row; // Like that $idSpecialGuest = $row['id_special_guest'] etc.
                $newSpecialGuest = new SpecialGuest($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days, $idSpecialGuest);
                $result[] = $newSpecialGuest; // Adds new SpecialGuest to array
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
     * Returns the object of the searched SpecialGuest
     */
    public function findById($searchedIdSpecialGuest) {

        $result = NULL;
        $query = 'SELECT * FROM special_guest WHERE id_special_guest = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdSpecialGuest));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idSpecialGuest, $oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days ) = $row; // Like that $idSpecialGuest = $row['id_special_guest'] etc.
                    $newSpecialGuest = new SpecialGuest($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days, $idSpecialGuest);
                    $result = $newSpecialGuest;
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

    public function findByDay($idPurchasingFair, $Day) {

        $result = NULL;
        $query = 'SELECT * FROM special_guest WHERE PURCHASING_FAIR_id_purchasing_fair = ? and days = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $Day));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idSpecialGuest, $oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days ) = $row; // Like that $idSpecialGuest = $row['id_special_guest'] etc.
                    $newSpecialGuest = new SpecialGuest($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days, $idSpecialGuest);
                    $result[] = $newSpecialGuest;
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

    public function findOneSpecialGuestByThree($idPurchasingFair, $Day, $idProvider) {

        $result = NULL;
        $query = 'SELECT * FROM special_guest WHERE PURCHASING_FAIR_id_purchasing_fair = ? and days = ? and ENTERPRISE_id_enterprise = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair, $Day, $idProvider));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idSpecialGuest, $oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days ) = $row; // Like that $idSpecialGuest = $row['id_special_guest'] etc.
                    $newSpecialGuest = new SpecialGuest($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days, $idSpecialGuest);
                    $result[] = $newSpecialGuest;
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
     * Returns an array of all the SpecialGuest for one Enterprise And Purchasing Fair
     */
    public function findByEnterpriseAndPf($idEnterprise, $idPurchasingFair) {

        $result = array();
        $query = 'SELECT * '
                . 'FROM special_guest '
                . 'WHERE ENTERPRISE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ? '
                . 'ORDER BY surname_special_guest ASC, name_special_guest ASC';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idEnterprise, $idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idSpecialGuest, $oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days ) = $row; // Like that $idSpecialGuest = $row['id_special_guest'] etc.
                    $newSpecialGuest = new SpecialGuest($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days, $idSpecialGuest);
                    $result[] = $newSpecialGuest;
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
     * IF the SpecialGuest id is -1, the SpecialGuest does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the SpecialGuest must be updated.
     */
    public function save($specialGuest) {
        if($specialGuest->getIdSpecialGuest() == - 1) return $this->insert($specialGuest);
        else return $this->update($specialGuest);
    }

    /*
     * Inserts $specialGuest as a new record
     */
    public function insert($specialGuest) { 

        $querySpecialGuestTable = 'INSERT INTO special_guest '
                . '(ENTERPRISE_id_enterprise, PURCHASING_FAIR_id_purchasing_fair, civility_special_guest, surname_special_guest, name_special_guest, days) '
                . 'VALUES(?,?,?,?,?,?)';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($querySpecialGuestTable); 
            $qresult->execute(array(
                $specialGuest->getOneEnterprise()->getIdEnterprise(), 
                $specialGuest->getOnePurchasingFair()->getIdPurchasingFair(), 
                $specialGuest->getCivility(), 
                $specialGuest->getSurname(), 
                $specialGuest->getName(),
                $specialGuest->getDays()
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
     * Update one SpecialGuest
     */
    public function update($specialGuest) { 

        $query = 'UPDATE special_guest '
                . 'SET ENTERPRISE_id_enterprise = ?,'
                . 'PURCHASING_FAIR_id_purchasing_fair = ?,'
                . 'civility_special_guest = ?,'
                . 'surname_special_guest = ?,'
                . 'name_special_guest = ?,'
                . 'days = ? '
                . 'WHERE id_special_guest = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array(
                $specialGuest->getOneEnterprise()->getIdEnterprise(), 
                $specialGuest->getOnePurchasingFair()->getIdPurchasingFair(), 
                $specialGuest->getCivility(), 
                $specialGuest->getSurname(), 
                $specialGuest->getName(),
                $specialGuest->getDays(),
                $specialGuest->getIdSpecialGuest()
            ));
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
     * Deactivate a SpecialGuest (date_deletion => NOW())
     */
    public function deactivate($specialGuest) { ; }
    	

    /*
     * Delete a SpecialGuest
     */
    public function delete($idSpecialGuest) {

        $query = 'DELETE FROM special_guest WHERE id_special_guest = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            // throw new Exception('BOUM !'); TEST TRANSACTION FAILED
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idSpecialGuest));
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
     * Delete all SpecialGuest
     */
    public function deleteAll() {

        $query = 'DELETE FROM special_guest';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            // throw new Exception('BOUM !'); TEST TRANSACTION FAILED
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array());
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