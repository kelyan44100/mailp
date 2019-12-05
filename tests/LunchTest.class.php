<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class LunchTest extends UnitTestCase {

    // Methods
//    public function testCreateLunch() {
//        $appService = AppServiceImpl::getInstance();
//        $lunch = $appService->createLunch('ONE_ENTERPRISE', 'ONE_PF', '0', '1', 'jsonDATA');
//        var_dump($lunch);
//        $this->assertIsA($lunch, 'Lunch');
//    }
    
//    public function testSaveLunch() {
//
//        $appService = AppServiceImpl::getInstance();
//        
//        $enterprise = $appService->findOneEnterprise(1);
//        $purchasingFair = $appService->findOnePurchasingFair(3);
//        
//        $jsonData = json_encode(array(666,777));
//        
//        $lunch = $appService->createLunch($enterprise, $purchasingFair, 11, 22, $jsonData);
//        
//        $checkInsert = $appService->saveLunch($lunch);
//        
//        //var_dump($checkInsert);
//        $this->assertTrue($checkInsert);
//    }
    
//        public function testUpdateLunch() {
//
//            $appService = AppServiceImpl::getInstance();
//
//            $lunchToUpdate = $appService->findLunchForOneEnterpriseAndPf(1,3);
//            $lunchToUpdate->setLunchesCanceled(255); // MAX 255 TINYINT
//
//            $checkUpdate = $appService->updateLunch($lunchToUpdate);
//
//            $this->assertTrue($checkUpdate);
//        }
    
//    public function testLunchesCalculated() {
//        $appService = AppServiceImpl::getInstance();
//        $lunch = $appService->lunchesCalculated(3);
//        $this->assertTrue($lunch);
//    }
    
//    public function testDeleteAllLunchesByPf() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertTrue($appService->deleteAllLunchesByPf(4));
//    }
    
//    public function testfindLuncheForOneEnterpriseAndPf() {
//        
//        $appService = AppServiceImpl::getInstance();
//        
//        $lunches = $appService->findLunchForOneEnterpriseAndPf(1,3);
//        
//        
//        var_dump($lunches);
//        $this->assertTrue(!empty($lunches));
//    }
    
    // https://subinsb.com/php-check-if-string-is-json/
//    public function testJSON() {
//        $appService = AppServiceImpl::getInstance();
//        
//        $lunch = $appService->createLunch($appService->findOneEnterprise(1), $appService->findOnePurchasingFair(2), '0', '1', array(1,2,3));
//        
//        $json = json_encode($lunch);
//        
//        var_dump($json);
//        
//        $this->assertTrue(is_string($json) && is_array(json_decode($json, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false);
//    }
    
    public function testDeleteAllLunchForProviders() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->deleteAllLunchesForProviders());
    }
    
//    public function testFindAllLunchForProviders() {
//        
//        $appService = AppServiceImpl::getInstance();
//        
//        $lunches = $appService->findAllLunchesForProviders();
//        
//        var_dump($lunches);
//        $this->assertTrue(!empty($lunches));
//    }
//            
}
