<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class ProviderPresentDAOOVH extends AbstractDAOOVH {
    
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods
    
   /*
     * Returns the collection (a simple array) of all ProviderPresent (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM provider_present";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $idPurchasingFair ) = $row; // Like that $idEnterprise = $row['PROVIDER_id_enterprise'] etc.
                    $newPP = new ProviderPresent($idEnterprise, $idPurchasingFair);
                    $result[] = $newPP; // Adds new ProviderPresent to array
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
     * Returns the object of the searched ProviderPresent
     */
    public function findByTwoIds($searchIdProvider, $searchedIdPurchasingFair) {

        $result = NULL;
        $query = "SELECT * FROM provider_present WHERE PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchIdProvider, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $idPurchasingFair ) = $row; // Like that $idEnterprise = $row['PROVIDER_id_enterprise'] etc.
                    $newPP = new ProviderPresent($idEnterprise, $idPurchasingFair);
                    $result = $newPP;
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
     * Returns the object of the searched ProviderPresent
     */
    public function findByOneId($searchedIdProvider) {

        $result = array();
        $query = "SELECT * FROM provider_present WHERE PROVIDER_id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $idPurchasingFair ) = $row; // Like that $idEnterprise = $row['PROVIDER_id_enterprise'] etc.
                    $newPP = new ProviderPresent($idEnterprise, $idPurchasingFair);
                    $result[] = $newPP;
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

    public function save($providerPresent) { ; }

    /*
     * Inserts $providerPresent as a new record
     */
    public function insert($providerPresent) { 

        $query = "INSERT INTO provider_present (PROVIDER_id_enterprise, PURCHASING_FAIR_id_purchasing_fair) VALUES(?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($providerPresent->getOneProvider()->getIdEnterprise(), $providerPresent->getOnePurchasingFair()->getIdPurchasingFair()));
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
    public function update($providerPresent) { ; }

    /*
     * Deactivate an APE (date_deletion => NOW())
     */
    public function deactivate($providerPresent) { ; }	

    /*
     * Delete a PP without distinction
     */
    public function delete($providerPresent) {

        $query = "DELETE FROM provider_present WHERE PROVIDER_id_enterprise = ? AND PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($providerPresent->getOneProvider()->getIdEnterprise(), $providerPresent->getOnePurchasingFair()->getIdPurchasingFair()));
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
     * Delete all PP for one PurchasingFair
     */
    public function deleteForOnePurchasingFair($idPurchasingFair) {

        $query = "DELETE FROM provider_present WHERE PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair));
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
     * Returns the collection (a simple array) of all ProviderPresent (the returned array may be empty) for a given PurchasingFair
     */
    public function findAllProviderPresentForOnePurchasingFair($searchedIdPurchasingFair) {

        $result = array();
        $query = "SELECT * FROM provider_present pp WHERE pp.PURCHASING_FAIR_id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $idPurchasingFair ) = $row; // Like that $idEnterprise = $row['PROVIDER_id_enterprise'] etc.
                    $newPP = new ProviderPresent($idEnterprise, $idPurchasingFair);
                    $result[] = $newPP; // Adds new ProviderPresent to array
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