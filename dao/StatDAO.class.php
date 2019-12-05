<?php
require_once dirname ( __FILE__ ) . '/SingletonConnectionMySQL.class.php' ;

class StatDAO {
    
    // Constructor
    function __construct() { $this->pdo = SingletonConnectionMySQL::getInstance()->getDbh(); }
    
    // Methods
    public function deactivate($stat) { ; }
    public function delete($stat) { ; }
    public function findAll() { ; }
    public function findById($searchedId) { ; }
    public function insert($stat) { ; }
    public function save($stat) { ; }
    public function update($stat) { ; }
    
    // Calculates the number of purchasing fairs where the enterprise (provider/store) came.
    public function numberOfParticipationsInAPurchasingFair(User $user) {
        
        $result = array("nbParticipations" => 0);
        
//        QUERY VERSION WITH SUBQUERY /!\ SLOWER /!\
//        $query = '
//        SELECT COUNT(*) AS "nbParticipations" FROM (
//            SELECT pf.id_purchasing_fair FROM user AS u
//            -- INNER JOIN etc. --
//            GROUP by pf.id_purchasing_fair
//        ) AS aliasName';
        
        $query = '
        SELECT COUNT(DISTINCT(pf.id_purchasing_fair)) AS "nbParticipations"
        FROM user AS u
        INNER JOIN enterprise AS e ON e.id_enterprise = u.ENTERPRISE_id_enterprise';
        
        // IF User Profile is Provider ("Fournisseur") - A Store must be in column ENTERPRISE_STORE_id_enterprise
        if($user->getOneEnterprise()->getOneProfile()->getIdProfile() == 1)
        $query .= '
        INNER JOIN requirement AS r On r.ENTERPRISE_PROVIDER_id_enterprise = e.id_enterprise';
        
        // IF User Profile is Store ("Magasin") - A Provider must be in column ENTERPRISE_PROVIDER_id_enterprise
        elseif($user->getOneEnterprise()->getOneProfile()->getIdProfile() == 2)
        $query .= '
        INNER JOIN requirement AS r On r.ENTERPRISE_STORE_id_enterprise = e.id_enterprise';
        
        else return -1; // Case of error
                
        $query .= '
        INNER JOIN purchasing_fair AS pf ON pf.id_purchasing_fair = r.PURCHASING_FAIR_id_purchasing_fair
        WHERE u.id_user = ?
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($user->getIdUser()));
            $this->pdo->commit(); // If all goes well the transaction is validated
            if( $row = $qresult->fetch() ) {
                    list ( $nbParticipations) = $row; // Like that $nbParticipations = $row['nbParticipations'] etc.
                    $result["nbParticipations"] += $nbParticipations; // Adds nthe number of participations
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
    
    // Number of monthly connections for the current year -- FOR GRAPH HIGHCHARTS ONLY
    public function numberOfConnectionsByMonth(User $user) {

        $allConnections = array(0,0,0,0,0,0,0,0,0,0,0,0);
        $query = '
        SELECT MONTH(action_datetime) as "month", COUNT(*) AS "monthlyConnections" 
        FROM log 
        WHERE log.USER_id_user = ? AND action_description = "Session ouverte" AND YEAR(action_datetime) = '.date('Y').' 
        GROUP BY month';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($user->getIdUser()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            while( $row = $qresult->fetch() )
                $allConnections[ $row['month'] - 1 ] = $row['monthlyConnections'];

            return $allConnections;
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
    
    // Heatmap connections for the user in parameter for current year - FOR GRAPH HIGHCHARTS ONLY
    public function heatmapConnections(User $user) {

        // The data of the heat map are in the form [x, y, z] with x, y starting at 0, 
        // so we must convert the MySQL day numbers to exploit them
        // Same for time slots, you must start at 0
        // Its 
        $query = '
        SELECT  
        IF(DAYOFWEEK(action_datetime) = 1, 6, 
            IF(DAYOFWEEK(action_datetime) = 2, 0,
                IF(DAYOFWEEK(action_datetime) = 3, 1, 
                    IF(DAYOFWEEK(action_datetime) = 4, 2, 
                        IF(DAYOFWEEK(action_datetime) = 5, 3, 
                            IF(DAYOFWEEK(action_datetime) = 6, 4, 
                                IF(DAYOFWEEK(action_datetime) = 7, 5, "")
        )))))) AS "JourSemaineFr",
        SUM(IF(TIME(action_datetime) BETWEEN "00:00:00" AND "01:59:59", 1, 0)) AS "0",
        SUM(IF(TIME(action_datetime) BETWEEN "02:00:00" AND "03:59:59", 1, 0)) AS "1",
        SUM(IF(TIME(action_datetime) BETWEEN "04:00:00" AND "05:59:59", 1, 0)) AS "2",
        SUM(IF(TIME(action_datetime) BETWEEN "06:00:00" AND "07:59:59", 1, 0)) AS "3",
        SUM(IF(TIME(action_datetime) BETWEEN "08:00:00" AND "09:59:59", 1, 0)) AS "4",
        SUM(IF(TIME(action_datetime) BETWEEN "10:00:00" AND "11:59:59", 1, 0)) AS "5",
        SUM(IF(TIME(action_datetime) BETWEEN "12:00:00" AND "13:59:59", 1, 0)) AS "6",
        SUM(IF(TIME(action_datetime) BETWEEN "14:00:00" AND "15:59:59", 1, 0)) AS "7",
        SUM(IF(TIME(action_datetime) BETWEEN "16:00:00" AND "17:59:59", 1, 0)) AS "8",
        SUM(IF(TIME(action_datetime) BETWEEN "18:00:00" AND "19:59:59", 1, 0)) AS "9",
        SUM(IF(TIME(action_datetime) BETWEEN "20:00:00" AND "21:59:59", 1, 0)) AS "10",
        SUM(IF(TIME(action_datetime) BETWEEN "22:00:00" AND "23:59:59", 1, 0)) AS "11"
        FROM log As l
        WHERE l.USER_id_user = ? AND action_description = "Session ouverte" AND YEAR(action_datetime) = '.date("Y").'
        GROUP BY 1
        ';

        try {
            $this->pdo->beginTransaction(); // Start transaction
            $qresult = $this->pdo->prepare($query); 
            $qresult->execute(array($user->getIdUser()));
            $this->pdo->commit(); // If all goes well the transaction is validated

            // Prepare the data for highcharts
            $result = $qresult->fetchAll();

            // Need 7 days * 12 time slots = 84 data
            $heatmap = array(
            0 => array(0,0,0,0,0,0,0,0,0,0,0,0), 
            1 => array(0,0,0,0,0,0,0,0,0,0,0,0), 
            2 => array(0,0,0,0,0,0,0,0,0,0,0,0), 
            3 => array(0,0,0,0,0,0,0,0,0,0,0,0), 
            4 => array(0,0,0,0,0,0,0,0,0,0,0,0), 
            5 => array(0,0,0,0,0,0,0,0,0,0,0,0), 
            6 => array(0,0,0,0,0,0,0,0,0,0,0,0));

            foreach($result as $value) {
                $heatmap[ $value['JourSemaineFr'] ][0]  = $value[0];
                $heatmap[ $value['JourSemaineFr'] ][1]  = $value[1];
                $heatmap[ $value['JourSemaineFr'] ][2]  = $value[2];
                $heatmap[ $value['JourSemaineFr'] ][3]  = $value[3];
                $heatmap[ $value['JourSemaineFr'] ][4]  = $value[4];
                $heatmap[ $value['JourSemaineFr'] ][5]  = $value[5];
                $heatmap[ $value['JourSemaineFr'] ][6]  = $value[6];
                $heatmap[ $value['JourSemaineFr'] ][7]  = $value[7];
                $heatmap[ $value['JourSemaineFr'] ][8]  = $value[8];
                $heatmap[ $value['JourSemaineFr'] ][9]  = $value[9];
                $heatmap[ $value['JourSemaineFr'] ][10] = $value[10];
                $heatmap[ $value['JourSemaineFr'] ][11] = $value[11];
            }

            return $heatmap;
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