<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class LogTest extends UnitTestCase {

    // Methods
    public function testCreateLog() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->createLog($appService->findOneEnterprise(50), 'A.B.C.D', 'MA_DESCRIPTION'), 'Log');
    }
    
    public function testSaveLog() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->saveLog($appService->createLog($appService->findOneEnterprise(50), 'A.B.C.D', 'MA_DESCRIPTION')));
    }
    
    public function testFindAllLogs() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllLogs()));
    }
    
    public function testFindOneLog() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->findOneLog(1), 'Log');
    }
    
    public function testFindAllLogsForOneEnterprise() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllLogsForOneEnterprise($appService->findOneEnterprise(50))));
    }
}
