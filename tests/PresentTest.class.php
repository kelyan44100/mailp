<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class PresentTest extends UnitTestCase {

    // Methods
//    public function testCreatePresent() {
//        
//        $appService = AppServiceImpl::getInstance();
//        $present = $appService->createPresent('ONE_ENT', 'ONE_PART', 'ONE_PF', json_encode(array(1)));
//        
//        var_dump($present);
//        $this->assertIsA($present, 'Present');
//    }
//    
//    public function testSavePresent() {
//        
//        $appService = AppServiceImpl::getInstance();
//        
//        $enterprise     = $appService->findOneEnterprise(1);
//        $participant    = $appService->findOneParticipant(1);
//        $purchasingFair = $appService->findOnePurchasingFair(3);
//        $presentDetails = json_encode(array(1));
//        
//        $present = $appService->createPresent($enterprise, $participant, $purchasingFair, $presentDetails);
//        $this->assertTrue($appService->savePresent($present));
//    }
    
//    public function testUpdatePresent() {
//        
//        $appService = AppServiceImpl::getInstance();
//        $presentFinded = $appService->findPresentByTrio(1,1,3);
//        $presentDetails = array(999,888,2,1);
//        $presentFinded->setPresentDetails(json_encode(array_unique(array_merge($presentDetails, $presentFinded->getPresentDetails()))));
//        $this->assertTrue($appService->updatePresent($presentFinded));
//    }
    
//    public function testFindPresentByDuo() {
//        $appService = AppServiceImpl::getInstance();
//        
//        var_dump($appService->findPresentByDuo(1,3));
//
//        $this->assertTrue(is_array($appService->findPresentByDuo(1,3)));
//    }
    
        
//    public function testFindPresentByTrio() {
//        $appService = AppServiceImpl::getInstance();
//
//        var_dump($appService->findPresentByTrio(1,1,3));
//        $this->assertIsA($appService->findPresentByTrio(1,1,3), 'Present');
//    }
//    
//    public function testDeletePresent() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertTrue($appService->deletePresent($appService->findPresentByTrio(1,1,3)));
//    }

        // https://subinsb.com/php-check-if-string-is-json/
//    public function testJSON() {
//        $appService = AppServiceImpl::getInstance();
//        
//        $present = $appService->createPresent($appService->findOneEnterprise(1), $appService->findOneParticipant(2), $appService->findOnePurchasingFair(3), array(1,2,3));
//        
//        $json = json_encode($present);
//        
//        var_dump($json);
//        
//        $this->assertTrue(is_string($json) && is_array(json_decode($json, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false);
//    }
    
//    public function testDeleteAllpresentsForProviders() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertTrue($appService->deleteAllPresentsForProviders());
//    }
    
    public function testFindAllPresentsForProviders() {
        
        $appService = AppServiceImpl::getInstance();
        
        $presents = $appService->findAllPresentsForProviders();
        
        var_dump($presents);
        $this->assertTrue(!empty($presents));
    }
        
}
