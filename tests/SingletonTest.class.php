<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../dao/SingletonConnectionMySQL.class.php';

class SingletonTest extends UnitTestCase {

    // Methods
    
    // Compare two AppServiceImpl objects
    public function testSingletonAppServiceImpl() {
        $appService = AppServiceImpl::getInstance();
        $oSingletonA = AppServiceImpl::getInstance();
        $oSingletonB = AppServiceImpl::getInstance();
        $this->assertTrue($appService->compareObjects($oSingletonA, $oSingletonB));
    }
    
    // Compare two PDO objects
    public function testSingletonPDO() {
        $appService = AppServiceImpl::getInstance();
        $oSingletonA = SingletonConnectionMySQL::getInstance()->getDbh();
        $oSingletonB = SingletonConnectionMySQL::getInstance()->getDbh();
        $this->assertTrue($appService->compareObjects($oSingletonA, $oSingletonB));
    }
    
    // Compare two Log objects
    public function testSingletonLog() {
        $appService = AppServiceImpl::getInstance();
        $createdLog = $appService->createLog($appService->findOneEnterprise(1), '127.0.0.1', 'MA_DESCRIPTION_1');
        $createdLog2 = $appService->createLog($appService->findOneEnterprise(1), '::1', 'MA_DESCRIPTION_2');
        $this->assertFalse($appService->compareObjects($createdLog, $createdLog2));
    }
    
}
