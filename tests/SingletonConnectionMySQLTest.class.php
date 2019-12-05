<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../dao/SingletonConnectionMySQL.class.php';

class SingletonConnectionMySQLTest extends UnitTestCase {

    // Methods
    
    // Test the creation of an object SingletonConnection
    public function testCreateSingletonConnectionMySQL() {
        $this->assertIsA(SingletonConnectionMySQL::getInstance(), 'SingletonConnectionMySQL');
    }
        
}
