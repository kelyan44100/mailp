<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class RequirementDAOOVH extends AbstractDAOOVH {
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all requirements (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM requirement ORDER BY PURCHASING_FAIR_id_purchasing_fair DESC, id_requirement DESC";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idRequirement, $idStore, $idProvider, $idPurchasingFair, $numberOfHours ) = $row; // Like that $idPurchasingFair = $row['id_purchasing_fair'] etc.
                    $newRequirement = new Requirement($idStore, $idProvider, $idPurchasingFair, $numberOfHours, $idRequirement);
                    $result[] = $newRequirement; // Adds new PurchasingFair to array
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
     * Returns the collection (a simple array) of all requirements (the returned array may be empty) for one purchasing fair and one store
     */
    public function findAllFiltered($idStore, $idPurchasingFair) {

        $result = array ();
        $query = "SELECT * FROM requirement WHERE id_enterprise_requirement_store = ? AND id_purchasing_fair_requirement = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idStore, $idPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idRequirement, $idStore, $idProvider, $idPurchasingFair, $numberOfHours ) = $row; // Like that $idPurchasingFair = $row['id_purchasing_fair'] etc.
                    $newRequirement = new Requirement($idStore, $idProvider, $idPurchasingFair, $numberOfHours, $idRequirement);
                    $result[] = $newRequirement; // Adds new PurchasingFair to array
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
     * Returns the object of the searched requirement
     */
    public function findById($searchedIdRequirement) {

        $result = NULL;
        $query = "SELECT * FROM requirement WHERE id_requirement = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdRequirement));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idRequirement, $idStore, $idProvider, $idPurchasingFair, $numberOfHours ) = $row;
                    $newRequirement = new Requirement($idStore, $idProvider, $idPurchasingFair, $numberOfHours, $idRequirement );
                    $result = $newRequirement;
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
     * Returns the object of the searched requirement for a duo { store - purchasing fair }
     */
    public function findByDuo($oneStore, $onePurchasingFair) {

        $result = array();
        $query = "SELECT * FROM requirement WHERE ENTERPRISE_STORE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($oneStore->getIdEnterprise(), $onePurchasingFair->getIdPurchasingFair()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRequirement, $idStore, $idProvider, $idPurchasingFair, $numberOfHours ) = $row; // Like that $idPurchasingFair = $row['id_purchasing_fair'] etc.
                    $newRequirement = new Requirement($idStore, $idProvider, $idPurchasingFair, $numberOfHours, $idRequirement);
                    $result[] = $newRequirement;
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
     * Returns the object of the searched requirement for a duo { store - purchasing fair }
     */
    public function findTotalNumberHours($oneStore, $onePurchasingFair) {

        $result = 00.00;
        $query = '
        SELECT SUM(rq.number_of_hours) AS "totNumberOfHours" 
        FROM requirement rq 
        WHERE rq.ENTERPRISE_STORE_id_enterprise = ? AND rq.PURCHASING_FAIR_id_purchasing_fair = ?
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($oneStore->getIdEnterprise(), $onePurchasingFair->getIdPurchasingFair()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $totNumberOfHours ) = $row; // Like that $totNumberOfHours = $row['totNumberOfHours'] etc.
                    $result = $totNumberOfHours;
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
     * Delete all requirements for a duo { store - purchasing fair }
     */
    public function deleteByDuo($oneStore, $onePurchasingFair) {

        $query = "DELETE FROM requirement WHERE ENTERPRISE_STORE_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($oneStore->getIdEnterprise(), $onePurchasingFair->getIdPurchasingFair()));
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
     * Returns the object of the searched requirement for a trio { store - provider - purchasing fair }
     */
    public function findByTrio($oneStore, $oneProvider, $onePurchasingFair) {

        $result = NULL;
        $query = "SELECT * FROM requirement WHERE ENTERPRISE_STORE_id_enterprise = ? AND ENTERPRISE_PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($oneStore->getIdEnterprise(), $oneProvider->getIdEnterprise(), $onePurchasingFair->getIdPurchasingFair()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idRequirement, $idStore, $idProvider, $idPurchasingFair, $numberOfHours ) = $row; // Like that $idPurchasingFair = $row['id_purchasing_fair'] etc.
                    $newRequirement = new Requirement($idStore, $idProvider, $idPurchasingFair, $numberOfHours, $idRequirement);
                    $result = $newRequirement;
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
     * IF the requirement id is -1, the purchasing fair does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the purchasing fair must be updated.
     */
    public function save($requirement) {
        if($requirement->getIdRequirement() == - 1) return $this->insert($requirement);
        else return $this->update($requirement);
    }

    /*
     * Inserts $requirement as a new record
     */
    public function insert($requirement) { 

        $query = "INSERT INTO requirement (ENTERPRISE_STORE_id_enterprise, ENTERPRISE_PROVIDER_id_enterprise, PURCHASING_FAIR_id_purchasing_fair, number_of_hours) VALUES(?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($requirement->getOneStore()->getIdEnterprise(), $requirement->getOneProvider()->getIdEnterprise(), $requirement->getOnePurchasingFair()->getIdPurchasingFair(), $requirement->getNumberOfHours()));
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
     * Update one requirement
     */
    public function update($requirement) { 

        $query = "UPDATE requirement SET number_of_hours = ? WHERE id_requirement = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($requirement->getNumberOfHours(),$requirement->getIdRequirement()));
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
     * Deactivate a requirement (date_deletion => NOW())
     */
    public function deactivate($requirement) { ; }	

    /*
     * Delete a requirement
     */
    public function delete($requirement) {

        $query = "DELETE FROM requirement WHERE id_requirement = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($requirement->getIdRequirement()));
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