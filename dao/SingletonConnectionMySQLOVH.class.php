<?php
/**
 * Singleton to connect to the database, used by all classes that need access to the database.  
 *
 * This class only allows the instantiation of a PDO object to connect to the database. 
 * You can use this class like this:
 * $db = connexion::getInstance();
 * $con = $db->getDbh();
 * OR $con = connexion::getInstance()->getDbh() 1 LINE
 *
 * http://fr3.php.net/manual/fr/book.pdo.php PDO class
 *
 */
class SingletonConnectionMySQLOVH
{
    /**
     * Instance of the connection class
     */
    private static $instance;

    /**
     * Type of the database
     */
    private $type = "mysql";

    /**
     * Host Address
     */
    private $host = "localhost";

    /**
     * Name of the database
     */
    private $dbname = "pf_management_tmp";

    /**
     * Username for the connection to the database
     */
    private $username = "root";

    /**
     * Password for connection to the database
     */
    private $password = "root";

    private $dbh;

    /**
	 * Constructor in private.
     * Starts the connection to the database by putting it
	 * in a PDO object that is stored in the $dbh variable
     */
    private function __construct()
    {
        try{
            $this->dbh = new PDO(
                $this->type.':host='.$this->host.'; dbname='.$this->dbname, 
                $this->username, 
                $this->password,
                array(PDO::ATTR_PERSISTENT => true)
            );

            $req = "SET NAMES UTF8";
            $result = $this->dbh->prepare($req);
            $result->execute();
        }
        catch(PDOException $e){
            echo "<div class=\"error\">Erreur !: ".$e->getMessage()."</div>";
            die();
        }
    }
	
	private function __clone() { } // Method of cloning also in private

    /**
	 * See if a connection object has already been instantiated,
     * If it is the case then it returns the already existing object
     * Otherwise it will create another
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof self) // OR if (!isset(self::$instance)) OR if(is_null(self::$instance))
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
	 * Retrieves the PDO object to manipulate the database
     */
    public function getDbh()
    {
        return $this->dbh;
    }
}
?>