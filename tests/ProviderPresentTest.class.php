<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class LogTest extends UnitTestCase {

    // Methods
    public function testCreatePP() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->createProviderPresent($appService->findOneEnterprise(1), $appService->findOnePurchasingFair(3)), 'ProviderPresent');
    }
    
    public function testSavePP() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->saveProviderPresent($appService->createProviderPresent($appService->findOneEnterprise(1), $appService->findOnePurchasingFair(2))));
    }
    
    public function testDeleteForOnePurchasingFairPP() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->deletePPForOnePurchasingFair(3));
    }
    
    public function testDeletePP() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->deleteProviderPresent($appService->createProviderPresent($appService->findOneEnterprise(1), $appService->findOnePurchasingFair(3))));
    }
    
    public function testFindAllproviderPresent() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllProviderPresent()));
        var_dump($appService->findAllProviderPresent());
    }
    
    public function testFindOneProviderPresent() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->findOneProviderPresent(1,3), 'ProviderPresent');
    }
    
    public function testFindAllProviderPresentForOneProvider() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllProviderPresentForOneProvider(1)));
    }
    
    public function testFindAllProviderPresentForOnePurchasingFair() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllProviderPresentForOnePurchasingFair(1)));
        var_dump($appService->findAllProviderPresentForOnePurchasingFair(3));
    }
}
