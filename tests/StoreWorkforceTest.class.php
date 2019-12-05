<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class StoreWorkforceTest extends UnitTestCase {

    // Methods
//    public function testCreateSW() {
//        $appService = AppServiceImpl::getInstance();
//        $sw = $appService->createStoreWorkforce('ONE_ENTERPRISE', 'Dessus', 'Dessous', 'Chaussures');
//        var_dump($sw);
//        $this->assertIsA($sw, 'StoreWorkforce');
//    }
    
//    public function testSaveSW() {
//
//        $appService = AppServiceImpl::getInstance();
//        
//        $enterprise = $appService->findOneEnterprise(2);
//                
//        $sw = $appService->createStoreWorkforce($enterprise, 1, 2, 3);
//        
//        $checkInsert = $appService->saveStoreWorkforce($sw);
//        
//        //var_dump($checkInsert);
//        $this->assertTrue($checkInsert);
//    }
    
//        public function testUpdateLunch() {
//
//            $appService = AppServiceImpl::getInstance();
//
//            $swToUpdate = $appService->findStoreWorkforceForOneEnterprise(1);
//            $swToUpdate->setOuterClothing(253); // MAX 255 TINYINT
//            $swToUpdate->setUnderClothing(254); // MAX 255 TINYINT
//            $swToUpdate->setShoes(255); // MAX 255 TINYINT
//
//            $checkUpdate = $appService->updateStoreWorkforce($swToUpdate);
//
//            $this->assertTrue($checkUpdate);
//        }
    
    public function testfindSWForOneEnterprise() {
        
        $appService = AppServiceImpl::getInstance();
        
        $sw = $appService->findStoreWorkforceForOneEnterprise(2);
        
        
        var_dump($sw);
        $this->assertTrue(!empty($sw));
    }
    
}
