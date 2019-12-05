<?php
require_once dirname ( __FILE__ ) . '/AbstractDAOOVH.class.php' ;
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQLOVH.class.php' ;

class PurchasingFairDAOOVH extends AbstractDAOOVH {
	
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQLOVH::getInstance()->getDbh(); }

    // Methods

    /*
     * Returns the collection (a simple array) of all purchasing fairs (the returned array may be empty)
     * IF (today + 4 months) < start_datetime of purchasing fair, the purchasing fair is selected
     */
    public function findAll() {

        $result = array ();
        $query = "
        SELECT * 
        FROM purchasing_fair 
        WHERE NOW() < registration_closing_date AND date_deletion_purchasing_fair IS NULL 
        ORDER BY start_datetime ASC, end_datetime ASC";
        // ... DATE_ADD(CURDATE(), INTERVAL 4 MONTH) <= DATE(start_datetime)... OLD VERSION WITH 4 MONTHS 

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idPurchasingFair, $namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $idTypeOf, $registrationClosingDate, $dateDeletion ) = $row; // Like that $idPurchasingFair = $row['id_purchasing_fair'] etc.
                    $newPurchasingFair = new PurchasingFair($namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $idTypeOf, $registrationClosingDate, $dateDeletion, $idPurchasingFair);
                    $result[] = $newPurchasingFair; // Adds new PurchasingFair to array
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
     * Returns the collection (a simple array) of all purchasing fairs (the returned array may be empty)
     * IF (today + 4 months) < start_datetime of purchasing fair, the purchasing fair is selected
     */
    public function findAllAdmin() {

        $result = array ();
        $query = "
        SELECT * 
        FROM purchasing_fair 
        WHERE date_deletion_purchasing_fair IS NULL 
        ORDER BY start_datetime ASC, end_datetime ASC";
        // ... DATE_ADD(CURDATE(), INTERVAL 4 MONTH) <= DATE(start_datetime)... OLD VERSION WITH 4 MONTHS 

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->query($query);
            $this->pdo->commit(); // If all goes well the transaction is validated
            while( $row = $qresult->fetch() ) {
                    list ( $idPurchasingFair, $namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $idTypeOf, $registrationClosingDate, $dateDeletion ) = $row; // Like that $idPurchasingFair = $row['id_purchasing_fair'] etc.
                    $newPurchasingFair = new PurchasingFair($namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $idTypeOf, $registrationClosingDate, $dateDeletion, $idPurchasingFair);
                    $result[] = $newPurchasingFair; // Adds new PurchasingFair to array
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
     * Returns the object of the searched purchasing fair
     */
    public function findById($searchedIdPurchasingFair) {

        $result = NULL;
        $query = "SELECT * FROM purchasing_fair WHERE id_purchasing_fair = ? AND date_deletion_purchasing_fair IS NULL";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($searchedIdPurchasingFair));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idPurchasingFair, $namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $idTypeOf, $registrationClosingDate, $dateDeletion ) = $row; // Like that $idPurchasingFair = $row['id_purchasing_fair'] etc.
                    $newPurchasingFair = new PurchasingFair($namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $idTypeOf, $registrationClosingDate, $dateDeletion, $idPurchasingFair);
                    $result = $newPurchasingFair;
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
     * IF the purchasing fair id is -1, the purchasing fair does not exist in the database and has been instantiated so it must be inserted.
     * ELSE the purchasing fair must be updated.
     */
    public function save($purchasingFair) {
        if($purchasingFair->getIdPurchasingFair() == - 1) return $this->insert($purchasingFair);
        else return $this->update($purchasingFair);
    }

    /*
     * Inserts $purchasingFair as a new record
     */
    public function insert($purchasingFair) { 

        $query = "INSERT INTO purchasing_fair (name_purchasing_fair, hex_color, start_datetime, end_datetime, lunch_break, TYPEOF_PF_id_typeof_pf, registration_closing_date) VALUES(?,?,?,?,?,?,?)";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($purchasingFair->getNamePurchasingFair(), $purchasingFair->getHexColor(), $purchasingFair->getStartDatetime(), $purchasingFair->getEndDateTime(), $purchasingFair->getLunchBreak(), $purchasingFair->getOneTypeOfPf(), $purchasingFair->getRegistrationClosingDate()));
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
     * Update one purchasing fair
     */
    public function update($purchasingFair) { 

        $query = "UPDATE purchasing_fair SET name_purchasing_fair = ?, hex_color = ?, start_datetime = ?, end_datetime = ?, lunch_break = ?, TYPEOF_PF_id_typeof_pf = ? , registration_closing_date = ? WHERE id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($purchasingFair->getNamePurchasingFair(), $purchasingFair->getHexColor(), $purchasingFair->getStartDatetime(), $purchasingFair->getEndDateTime(), $purchasingFair->getLunchBreak(), $purchasingFair->getOneTypeOfPf()->getIdTypeOfPf(), $purchasingFair->getRegistrationClosingDate(), $purchasingFair->getIdPurchasingFair()));
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
     * Deactivate a purchasing fair (date_deletion => NOW())
     */
    public function deactivate($purchasingFair) {

        $query = "UPDATE purchasing_fair SET date_deletion_purchasing_fair = NOW() WHERE id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($purchasingFair->getIdPurchasingFair()));
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
    public function delete($purchasingFair) {

        $query = "DELETE FROM purchasing_fair WHERE id_purchasing_fair = ?";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($purchasingFair->getIdPurchasingFair()));
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
     * Get the unavailability purchasing fair (return an object)
     */
    public function findByUnavailability(Unavailability $unavailability) {

        $result = null;

        $query = "
        SELECT p.id_purchasing_fair, p.name_purchasing_fair, p.hex_color, p.start_datetime, p.end_datetime, p.lunch_break, p.TYPEOF_PF_id_typeof_pf, p.registration_closing_date, p.date_deletion_purchasing_fair
        FROM unavailability AS u 
        INNER JOIN purchasing_fair AS p ON p.id_purchasing_fair = u.PURCHASING_FAIR_id_purchasing_fair 
        WHERE u.date_deletion_unavailability IS NULL AND p.date_deletion_purchasing_fair IS NULL AND u.id_unavailability = ?
        ";

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($unavailability->getIdUnavailability()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            if( $row = $qresult->fetch() ) {
                    list ( $idPurchasingFair, $namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $idTypeOf, $registrationClosingDate, $dateDeletion ) = $row; // Like that $idPurchasingFair = $row['id_purchasing_fair'] etc.
                    $newPurchasingFair = new PurchasingFair($namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $idTypeOf, $registrationClosingDate, $dateDeletion, $idPurchasingFair);
                    $result = $newPurchasingFair;
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