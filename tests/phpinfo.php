<h1>Infos Apache/PHP</h1>
<?php phpinfo(); ?>
<hr>
<h1>Infos MySQL</h1>
<?php 
require_once dirname ( __FILE__ ) . '/../dao/SingletonConnectionMySQL.class.php';
$attributes = array(
    "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
    "ORACLE_NULLS", "PERSISTENT", "SERVER_INFO", "SERVER_VERSION"  
);
// "PREFETCH", "TIMEOUT" Driver does not support this function: driver does not support that attribute
foreach ($attributes as $val) {
    echo "PDO::ATTR_$val: ";
    echo SingletonConnectionMySQL::getInstance()->getDbh()->getAttribute(constant("PDO::ATTR_$val")) . "<br/>";
}
?>
