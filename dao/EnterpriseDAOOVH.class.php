<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class EnterpriseDAOOVH extends AbstractDAOOVH {
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all enterprises (the returned array may be empty)
     */
    public function findAll() {

        $result = array ();
        $query = "SELECT * FROM enterprise WHERE date_deletion_enterprise IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();			
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                    $newEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise );
                    $result[] = $newEnterprise; // Adds new enterprise to array
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
     * Returns the collection (a simple array) of all enterprises (the returned array may be empty) with the role of Provider ("Fournisseur")
     */
    public function findAllProviders() {

        $result = array ();
        $query = "SELECT * FROM enterprise WHERE PROFILE_id_profile = 1 AND date_deletion_enterprise IS NULL ORDER BY name_enterprise ASC";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();			
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                    $newEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise );
                    $result[] = $newEnterprise; // Adds new enterprise to array
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
     * 08/05/2018 Returns the collection (a simple array) of all enterprises (the returned array may be empty) with the role of Provider ("Fournisseur")
     * WITH 'TEXTILE (T)' PRIORITY AND CHECK IF PROVIDER IS PRESENT FOR PF
     */
    public function findAllProvidersWithTextilePriorityByPf($idPurchasingFair) {

        $result = array ();
        $query = '
        SELECT 
        e.id_enterprise, 
        e.name_enterprise, 
        e.password_enterprise, 
        e.panel_enterprise,
        e.postal_address,
        e.postal_code,
        e.city,
        e.vat,
        e.TYPEOF_PROVIDER_id_typeof_provider, 
        e.PROFILE_id_profile, 
        e.DEPARTMENT_id_department, 
        e.date_deletion_enterprise
        FROM enterprise e
        INNER JOIN provider_present pp ON pp.PROVIDER_id_enterprise = e.id_enterprise
        WHERE e.PROFILE_id_profile = 1 AND e.date_deletion_enterprise IS NULL AND pp.PURCHASING_FAIR_id_purchasing_fair = ?
        ORDER BY e.TYPEOF_PROVIDER_id_typeof_provider DESC, e.name_enterprise ASC';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idPurchasingFair));			
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                    $newEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise );
                    $result[] = $newEnterprise; // Adds new enterprise to array
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
     * Returns the collection (a simple array) of all enterprises (the returned array may be empty) with the role of Store ("Magasin")
     */
    public function findAllStores() {

        $result = array ();
        $query = "SELECT * FROM enterprise WHERE PROFILE_id_profile = 2 AND date_deletion_enterprise IS NULL ORDER BY name_enterprise ASC";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute();			
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                    $newEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise );
                    $result[] = $newEnterprise; // Adds new enterprise to array
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
     * Returns the object of the searched enterprise
     */
    public function findById($searchedIdEnterprise) {

        $result = NULL;
        $query = "SELECT * FROM enterprise WHERE id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdEnterprise));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion ) = $row;
                    $newEnterprise = new Enterprise ($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise);
                    $result = $newEnterprise;
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
     * By Fabien : Returns an array of Enterprise (as Providers) objects of the searched puchasing fair.
     */
    public function findByIdPf($searchedIdPurchasingFair) {

        $result = NULL;
        
        /* -- DOES NOT WORK -- INCOMPATIBLE WITH SQL_MODE=ONLY_FULL_GROUP_BY (MYSQL LOCALHOST NICOLAS)
         * Test mode values :
         * mysql> SELECT @@sql_mode
         * Disable mode : 
         * mysql> GLOBAL SET sql_mode = (SELECT REPLACE (@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));
         * See Explanations : 
         * http://cedric-duprez.developpez.com/tutoriels/mysql/demythifier-group-by/
         * http://mechanics.flite.com/blog/2013/02/12/why-i-use-only-full-group-by/
         */
        
//        $queryFabien = 
//        'SELECT * 
//        FROM enterprise e left join assignment_sp_store asp on id_enterprise=ENTERPRISE_PROVIDER_id_enterprise 
//        WHERE asp.PURCHASING_FAIR_id_purchasing_fair = ? 
//        group by e.id_enterprise 
//        order by e.name_enterprise ASC';
        
        $query = '
        SELECT 
        e.id_enterprise, 
        e.name_enterprise, 
        e.password_enterprise, 
        e.panel_enterprise, 
        e.postal_address,
        e.postal_code,
        e.city,
        e.vat,
        e.TYPEOF_PROVIDER_id_typeof_provider, 
        e.PROFILE_id_profile, 
        e.DEPARTMENT_id_department, 
        e.date_deletion_enterprise 
        FROM enterprise e 
        INNER JOIN assignment_sp_store asp ON asp.ENTERPRISE_PROVIDER_id_enterprise = e.id_enterprise
        WHERE asp.PURCHASING_FAIR_id_purchasing_fair = ?  AND e.date_deletion_enterprise IS NULL
        GROUP BY e.id_enterprise, e.name_enterprise, e.password_enterprise, e.PROFILE_id_profile, e.DEPARTMENT_id_department, e.date_deletion_enterprise
        ORDER BY e.TYPEOF_PROVIDER_id_typeof_provider DESC, e.name_enterprise ASC
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                $newEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise );
                $result[] = $newEnterprise; // Adds new enterprise to array
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
     * By Me : Returns an array of Enterprise (as Stores) objects of the searched puchasing fair.
     */
    public function findByIdPf2($searchedIdPurchasingFair) {

        $result = NULL;
        
        $query = '
        SELECT 
        e.id_enterprise, 
        e.name_enterprise, 
        e.password_enterprise, 
        e.panel_enterprise, 
        e.postal_address,
        e.postal_code,
        e.city,
        e.vat,
        e.TYPEOF_PROVIDER_id_typeof_provider, 
        e.PROFILE_id_profile, 
        e.DEPARTMENT_id_department, 
        e.date_deletion_enterprise,
        (SELECT SUM(rq.number_of_hours)
         FROM requirement rq 
         WHERE rq.ENTERPRISE_STORE_id_enterprise = e.id_enterprise AND rq.PURCHASING_FAIR_id_purchasing_fair = ?
        ) AS "totNumberOfHours"
        FROM enterprise e 
        INNER JOIN profile pro ON pro.id_profile = e.PROFILE_id_profile
        INNER JOIN assignment_participant_enterprise ape ON ape.ENTERPRISE_id_enterprise = e.id_enterprise
        INNER JOIN participation pion ON pion.PARTICIPANT_id_participant = ape.PARTICIPANT_id_participant
        WHERE pion.PURCHASING_FAIR_id_purchasing_fair = ? AND pro.id_profile = 2 AND e.date_deletion_enterprise IS NULL
        GROUP BY e.id_enterprise, e.name_enterprise, e.password_enterprise, e.PROFILE_id_profile, e.DEPARTMENT_id_department, e.date_deletion_enterprise
        ORDER BY 9 DESC, e.name_enterprise ASC
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdPurchasingFair, $searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $totNumberOfHours ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                $newEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise );
                $result[] = $newEnterprise; // Adds new enterprise to array
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
     * IF the enterprise id is -1, the enterprise does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the enterprise must be updated.
     */
    public function save($enterprise) {
        if($enterprise->getIdEnterprise() == - 1) return $this->insert($enterprise);
        else return $this->update($enterprise);
    }

    /*
     * Inserts $enterprise as a new record
     */
    public function insert($enterprise) { 

        $query = "INSERT INTO enterprise (name_enterprise, password_enterprise, panel_enterprise, TYPEOF_PROVIDER_id_typeof_provider, PROFILE_id_profile, DEPARTMENT_id_department) VALUES(?,UNHEX(SHA1(?)),?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($enterprise->getName(), $enterprise->getPassword(), $enterprise->getPanel(), $enterprise->getOneTypeOfProvider()->getIdTypeOfProvider(), $enterprise->getOneProfile()->getIdProfile(), $enterprise->getOneDepartment()->getIdDepartment()));
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
     * Update one enterprise
     */
    public function update($enterprise) { 

        $query = '
            UPDATE enterprise 
            SET name_enterprise = ?, 
            password_enterprise = UNHEX(SHA1(?)), 
            panel_enterprise = ?, 
            postal_address = ?,
            postal_code = ?,
            city = ?,
            vat = ?,
            TYPEOF_PROVIDER_id_typeof_provider = ?, 
            PROFILE_id_profile = ?, 
            DEPARTMENT_id_department = ? 
            WHERE id_enterprise = ?';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array(
                $enterprise->getName(), 
                $enterprise->getPassword(), 
                $enterprise->getPanel(), 
                $enterprise->getPostalAddress(),
                $enterprise->getPostalCode(),
                $enterprise->getCity(),
                $enterprise->getVat(),
                ( ( is_null( $enterprise->getOneTypeOfProvider() ) ) ? 'NULL' : $enterprise->getOneTypeOfProvider()->getIdTypeOfProvider() ), 
                $enterprise->getOneProfile()->getIdProfile(), 
                $enterprise->getOneDepartment()->getIdDepartment(), 
                $enterprise->getIdEnterprise()));
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
     * Deactivate an enterprise (date_deletion => NOW())
     */
    public function deactivate($enterprise) {

        $query = "UPDATE enterprise SET date_deletion_enterprise = NOW() WHERE id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($enterprise->getIdEnterprise()));
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
     * Delete an enterprise
     */
    public function delete($enterprise) {

        $query = "DELETE FROM enterprise WHERE id_enterprise = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($enterprise->getIdEnterprise()));
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
     * Get the user enterprise (return an object)
     */
    public function findByUser(User $user) {

        $result = null;

        $query = "
        SELECT 
        e.id_enterprise, 
        e.name_enterprise, 
        e.password_enterprise, 
        e.panel_enterprise,
        e.postal_address,
        e.postal_code,
        e.city,
        e.vat,
        e.TYPEOF_PROVIDER_id_typeof_provider, 
        e.PROFILE_id_profile, 
        e.DEPARTMENT_id_department, 
        e.date_deletion_enterprise
        FROM user AS u
        INNER JOIN enterprise AS e ON e.id_enterprise = u.ENTERPRISE_id_enterprise
        WHERE u.date_deletion_user IS NULL AND e.date_deletion_enterprise IS NULL AND u.id_user = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($user->getIdUser()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                    $newEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise);
                    $result = $newEnterprise;
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
     * Check enterprise id and password : return an Enterprise object (not null or null)
     */
    public function authentication($idEnterprise, $password, $profileEnterprise) {

        $result        = null;
        $queryStore    = "SELECT * FROM enterprise WHERE id_enterprise = ? AND panel_enterprise = ? AND date_deletion_enterprise IS NULL";
        $queryProvider = "SELECT * FROM enterprise WHERE id_enterprise = ? AND password_enterprise = UNHEX(SHA1(?)) AND date_deletion_enterprise IS NULL";
        $query = ( $profileEnterprise == 'store' ) ? $queryStore : $queryProvider;
        
        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($idEnterprise, $password));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                list ( $idEnterprise, $name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                $newEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $typeOfProvider, $idProfile, $idDepartment, $dateDeletion, $idEnterprise);
                $result = $newEnterprise;
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
     * Returns the collection (a simple array) of all stores ids(the returned array may be empty) not available for one time slot given And Pf
     */
    public function findAllStoresNotAvailableForTimeSlotAndPf($onePurchasingFair, $startTimeSlot, $endTimeSlot) {

        $result = array ();
        $query = '
        SELECT e.id_enterprise
        FROM enterprise e
        INNER JOIN unavailability unav ON unav.ENTERPRISE_id_enterprise = e.id_enterprise
        WHERE 
        unav.date_deletion_unavailability IS NULL 
        AND 
        unav.PURCHASING_FAIR_id_purchasing_fair = ? 
        AND
        e.date_deletion_enterprise IS NULL
        AND 
        unav.start_datetime <= ?
        AND 
        unav.end_datetime >= ?
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($onePurchasingFair->getIdPurchasingFair(), $startTimeSlot-> format('Y-m-d H:i:s'), $endTimeSlot-> format('Y-m-d H:i:s')));			
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() ) {
                    list ( $idEnterprise ) = $row; // Like that $identerprise = $row['id_enterprise'] etc.
                    $result[] = $idEnterprise; // Adds new enterprise to array
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