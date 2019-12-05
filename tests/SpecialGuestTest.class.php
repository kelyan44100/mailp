<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class SpecialGuestTest extends UnitTestCase {

    // Methods
    public function testCreateSpecialGuest() {
        $appService   = AppServiceImpl::getInstance();
        $specialGuest = $appService->createSpecialGuest('ONE_ENTERPRISE', 'ONE_PF', 'CIVILITY', 'SURNAME', 'NAME', 'DAYS');
        $this->assertIsA($specialGuest, 'SpecialGuest');
    }
    
    public function testSaveSpecialGuest() {
        $appService = AppServiceImpl::getInstance();
        $specialGuest = $appService->createSpecialGuest($appService->findOneEnterprise(1), $appService->findOnePurchasingFair(3), 'Monsieur', 'MOLINARO', 'NICOLAS', '2018-08-23');
        $this->assertTrue($appService->saveSpecialGuest($specialGuest));
    }
    
    public function testFindAllSpecialGuest() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllSpecialGuest()));
    }
    
    public function testFindOneSpecialGuest() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->findOneSpecialGuest(5), 'SpecialGuest');
        var_dump($appService->findOneSpecialGuest(5));
    }
    
    public function testFindAllSpecialGuestForOneEnterpriseAndPf() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllSpecialGuestForOneEnterpriseAndPf(1,3)));
    }
    
    public function testDeleteSpecialGuest() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->deleteSpecialGuest(5));
    }
}
