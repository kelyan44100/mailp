<?php
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class Prise_rdv_storeDAO{

    protected $pdo;
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all prise_rdv_store (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "
        SELECT * 
        FROM prise_rdv_store  
        ORDER BY idStore ASC, idRDV ASC";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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
     * Returns the object of the searched IdRDV
     */
    public function findByIdRDV($searchedIdRDV) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idRDV = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdRDV));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findByIdCommercial($searchedIdCommercial,$searchedIdStore) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idCommercial = ? and idStore = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query);
            $qresult->execute(array($searchedIdCommercial, $searchedIdStore));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findByIdCommercialAndPF($searchedIdCommercial,$searchedIdStore,$searchedIdPurchasingFair) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idCommercial = ? and idStore = ? and idPurchasingFair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query);
            $qresult->execute(array($searchedIdCommercial, $searchedIdStore, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findByIdPfBis($searchedIdPurchasingFair) {

        $result = NULL;
        $query = "SELECT DISTINCT idStore FROM prise_rdv_store WHERE idPurchasingFair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query);
            $qresult->execute(array($searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    $result[] = $row["idStore"];
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

    public function findByIdPfBisProvider($searchedIdPurchasingFair) {

        $result = NULL;
        $query = "SELECT DISTINCT idFournisseur FROM prise_rdv_store WHERE idPurchasingFair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query);
            $qresult->execute(array($searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    $result[] = $row["idFournisseur"];
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

    public function findIndisposOtherProvider($searchedIdStore,$searchedIdPurchasingFair,$searchedIdFournisseur,$jourString) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idStore = ? and idPurchasingFair = ? and  idFournisseur <> ? and jour_string = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdStore, $searchedIdPurchasingFair, $searchedIdFournisseur, $jourString));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
            }

            // $this->pdo = NULL;
            return $result; // si la requette renvoie NULL, la condition est passé et c'est OK !!!
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
     * Liste des RDV pris durant le même horaire par un autre magasin avec le commerciale
     */
    public function findIndispoSameHourWithOtherStoreWithSp($searchedIdPurchasingFair,$searchedIdCommercial,$jourString,$searchedIdStore) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idPurchasingFair = ? and  idCommercial = ? and jour_string = ? and idStore <> ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdPurchasingFair, $searchedIdCommercial, $jourString, $searchedIdStore));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
            }

            // $this->pdo = NULL;
            return $result; // si la requette renvoie NULL, la condition est passé et c'est OK !!!
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
     * Returns the object of the searched IdRDV
     */
    public function findByTwo($searchedIdStore, $searchedIdPurchasingFair) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idStore = ? and idPurchasingFair = ? ORDER BY idFournisseur ASC";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdStore, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findPriseRdvByTwo($jourString, $searchedIdPurchasingFair) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE jour_string = ? and idPurchasingFair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($jourString, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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
     * Returns the object of the searched IdRDV
     */
    public function findByThree($searchedIdStore,$searchedIdPurchasingFair,$searchedIdFournisseur) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idStore = ? and idPurchasingFair = ? and  idFournisseur = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdStore, $searchedIdPurchasingFair, $searchedIdFournisseur));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findByQuatre($searchedIdStore,$searchedIdPurchasingFair,$searchedIdFournisseur,$jourString) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idStore = ? and idPurchasingFair = ? and idFournisseur = ? and jour_string <> ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdStore, $searchedIdPurchasingFair, $searchedIdFournisseur, $jourString));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findByThreeBis($searchedIdStore, $searchedIdPurchasingFair, $jourString) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idStore = ? and idPurchasingFair = ? and jour_string = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdStore, $searchedIdPurchasingFair, $jourString));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findRdvFournisseurByThree($searchedIdProvider, $searchedIdPurchasingFair, $jourString) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idFournisseur = ? and idPurchasingFair = ? and jour_string = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair, $jourString));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findRdvCommercialByThree($searchedIdCommecial, $searchedIdPurchasingFair, $jourString) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idCommercial = ? and idPurchasingFair = ? and jour_string = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdCommecial, $searchedIdPurchasingFair, $jourString));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findCommerciauxFournisseurByThree($searchedIdProvider, $searchedIdPurchasingFair) {

        $result = NULL;
        $query = "SELECT DISTINCT idCommercial FROM prise_rdv_store WHERE idFournisseur = ? and idPurchasingFair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findCommerciauxFournisseurByThreeBis($searchedIdProvider, $searchedIdPurchasingFair, $idCommercial) {

        $result = NULL;
        $query = "SELECT DISTINCT jour_string FROM prise_rdv_store WHERE idFournisseur = ? and idPurchasingFair = ? and idCommercial = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair, $idCommercial));
            $this->pdo->commit(); // If all goes well the transaction is validated
            //print_r($qresult);

            while( $row = $qresult->fetch() ) {
                //print_r($row);
                    $result[] = $row;
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

    public function findRdvFournisseurByQuattro($searchedIdProvider, $searchedIdPurchasingFair, $jourString, $idStore) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idFournisseur = ? and idPurchasingFair = ? and jour_string = ? and idStore = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair, $jourString, $idStore));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findRdvCommercialByQuattro($searchedIdCommecial, $searchedIdPurchasingFair, $jourString, $idStore) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idCommercial = ? and idPurchasingFair = ? and jour_string = ? and idStore = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdCommecial, $searchedIdPurchasingFair, $jourString, $idStore));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findRdvFournisseurByQuattroBis($searchedIdProvider, $searchedIdPurchasingFair, $jourString, $idCommercial) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idFournisseur = ? and idPurchasingFair = ? and jour_string = ? and idCommercial = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair, $jourString, $idCommercial));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findRdvFournisseurByCinq($searchedIdProvider, $searchedIdPurchasingFair, $jourString, $idStore, $idCommercial) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idFournisseur = ? and idPurchasingFair = ? and jour_string = ? and idStore = ? and idCommercial = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdProvider, $searchedIdPurchasingFair, $jourString, $idStore, $idCommercial));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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
     * Recherche par tous les attribut de la table sauf : jourString, startString, endString
     * Returns the object of the searched IdRDV
     */
    public function findByAll($idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idStore = ? and idFournisseur = ? and idCommercial = ? and idPurchasingFair = ? and  start_datetime = ? and end_datetime = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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

    public function findByAllBis($idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idStore = ? and idFournisseur = ? and idCommercial = ? and idPurchasingFair = ? and  start_datetime = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
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
     * Liste des RDV du même jour avec d'autres fournisseurs
     */
    public function findRdvSameDayWithOtherProvider($searchedIdStore,$searchedIdPurchasingFair,$searchedIdFournisseur,$jourString,$startString, $endString) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idStore = ? and idPurchasingFair = ? and jour_string = ? and (( start_string < ? and start_string >= ? ) or ( start_string <= ? and end_string >= ? ) or ( start_string >= ? and end_string <= ? ) or ( end_string <= ? and end_string > ? ))";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdStore, $searchedIdPurchasingFair, $jourString, $endString, $startString, $startString, $endString, $startString, $endString, $endString, $startString));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
            }

            // $this->pdo = NULL;
            return $result; // si la requette renvoie NULL, la condition est passé et c'est OK !!!
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
     * Liste des RDV pris durant le même horaire par un autre magasin avec le commerciale
     */
    public function findRdvSameHourWithOtherStoreWithSp($searchedIdPurchasingFair,$searchedIdCommercial,$jourString,$startString, $endString,$idstore) {

        $result = NULL;
        $query = "SELECT * FROM prise_rdv_store WHERE idPurchasingFair = ? and  idCommercial = ? and jour_string = ? and idStore<> ? and (( start_string < ? and start_string >= ? ) or ( start_string <= ? and end_string >= ? ) or ( start_string >= ? and end_string <= ? ) or ( end_string <= ? and end_string > ? ))";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdPurchasingFair, $searchedIdCommercial, $jourString, $idstore, $endString, $startString, $startString, $endString, $startString, $endString, $endString, $startString));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idRDV, $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString ) = $row;
                    $newPrise_rdv_store = new prise_rdv_store( $idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV );
                    $result[] = $newPrise_rdv_store;
            }

            // $this->pdo = NULL;
            return $result; // si la requette renvoie NULL, la condition est passé et c'est OK !!!
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
     * IF the purchasing fair id is -1, the purchasing fair does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the purchasing fair must be updated.
     */
    public function save($prise_rdv_store) {
        if($prise_rdv_store->getIdRDV() == - 1) return $this->insert($prise_rdv_store);
        else return $this->update($prise_rdv_store);
    }

    /*
     * Inserts $purchasingFair as a new record
     */
    public function insert($prise_rdv_store) { 

        $query = "INSERT INTO prise_rdv_store (idStore, idFournisseur, idCommercial, idPurchasingFair, start_datetime, end_dateTime, jour_string, start_string, end_string ) VALUES(?,?,?,?,?,?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($prise_rdv_store->getIdStore(), $prise_rdv_store->getIdFournisseur(), $prise_rdv_store->getIdCommercial(), $prise_rdv_store->getIdPurchasingFair(), $prise_rdv_store->getStartDatetime(), $prise_rdv_store->getEndDateTime(), $prise_rdv_store->getJourString(), $prise_rdv_store->getStartString(), $prise_rdv_store->getEndString()));
            $this->pdo->commit(); // If all goes well the transaction is validated

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
     * Update one purchasing fair
     */
    public function update($prise_rdv_store) { 

        $query = "UPDATE prise_rdv_store SET idStore = ?, idFournisseur = ?, idCommercial = ?, idPurchasingFair = ?, startDatetime = ? , endDateTime = ?, jourString = ?, startString = ?, endString = ? WHERE idRDV = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($prise_rdv_store->getIdStore(), $prise_rdv_store->getIdFournisseur(), $prise_rdv_store->getIdCommercial(), $prise_rdv_store->getIdPurchasingFair(), $prise_rdv_store->getStartDatetime(), $prise_rdv_store->getEndDateTime(), $prise_rdv_store->getJourString(), $prise_rdv_store->getStartString(), $prise_rdv_store->getEndString(), $prise_rdv_store->getIdRDV()));
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
     * Delete a purchasing fair
     */
    public function delete($idPurchasingFair, $idStore, $idFournisseur, $start_datetime) {

        $query = "DELETE FROM prise_rdv_store WHERE idStore = ? and idPurchasingFair = ? and idFournisseur = ? and start_datetime = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idStore, $idPurchasingFair, $idFournisseur, $start_datetime));
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