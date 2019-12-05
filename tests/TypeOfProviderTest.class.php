<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class TypeOfProviderTest extends UnitTestCase {

    // Methods
    public function testCreateTypeOfProvider() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->createTypeOfProvider('Type Fournisseur'), 'TypeOfProvider');
    }
   
    public function testFindAllTypeOfProvider() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllTypeOfProvider()));
    }
    
    public function testFindOneTypeofProvider() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->findOneTypeOfProvider(2), 'TypeOfProvider');
        var_dump($appService->findOneTypeOfProvider(2));
    }
}
