<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class EnterpriseTest extends UnitTestCase {
    
    // Methods
//    public function testAuthenticationFailStore() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertNull($appService->authentication(1, 'AAAA', 'store'));
//    }
//    
//    public function testAuthenticationFailProvider() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertNull($appService->authentication(94, 'BBB', 'provider'));
//    }
//    
//    public function testAuthenticationSuccessStore() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertNotNull($appService->authentication(1, '15.44', 'store'));
//    }
//    
//    public function testAuthenticationSuccessprovider() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertNotNull($appService->authentication(51, '59I]gqro', 'provider'));
//    }
//    
//    public function testFindAllEnterprisesNoDistinction() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertTrue($appService->findAllEnterprises());
//    }
//    
//    public function testFindAllEnterprisesAsProviders() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertTrue($appService->findAllEnterprisesAsProviders());
//    }
//    
//    public function testFindAllEnterprisesAsStores() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertEqual(54, count($appService->findAllEnterprisesAsStores())); // {48 stores, 3 stores are x3 so 48 + (3 * 3) - 3 = 54}
//    }
    
//    public function testCreateEnterprise() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertIsA(new Enterprise('NEW_ENTERPRISE_CREATE', '1234', '00.00', 'aaaa', 'bbbb', 'cccc', 'dddd', 'typeProvider', $appService->findOneProfile(1), $appService->findOneDepartment(95)), 'Enterprise');        
//    }
    
    public function testFindOneEnterprise() {
        $appService = AppServiceImpl::getInstance();
        
        var_dump($appService->findOneEnterprise(1));
        $this->assertIsA($appService->findOneEnterprise(1), 'Enterprise');
    }
//    
//    public function testSaveEnterpriseInsert() {
//        $appService = AppServiceImpl::getInstance();
//        $enterpriseInsert = new Enterprise('NEW_ENTERPRISE_CREATE', '1234', '00.00', 'aaaa', 'bbbb', 'cccc', 'dddd', $appService->findOneProfile(1), $appService->findOneDepartment(95));
//        $this->assertTrue($appService->saveEnterprise($enterpriseInsert));
//    }
//    
//    public function testSaveEnterpriseUpdate() {
//        $appService = AppServiceImpl::getInstance();
//        $enterpriseUpdate = $appService->findOneEnterprise(49);
//        $enterpriseUpdate->setName("__ADMINISTRATOR__");
//        $enterpriseUpdate->setPassword("MYPASSWORD");
//        $this->assertTrue($appService->saveEnterprise($enterpriseUpdate));
//    }
//    
//    public function testDeleteEnterprise() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertTrue($appService->deleteEnterprise($appService->findOneEnterprise(102)));
//    }
//    
//    public function testDeactivateEnterprise() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertTrue($appService->deactivateEnterprise($appService->findOneEnterprise(104)));
//    }
}