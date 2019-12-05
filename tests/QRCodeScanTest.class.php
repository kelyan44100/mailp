<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class QRCodeScanTest extends UnitTestCase {

    // Methods
//    public function testCreateQRCodeScan() {
//        
//        date_default_timezone_set('Europe/Paris');
//        $appService   = AppServiceImpl::getInstance();
//        
//        $datetime = (new DateTime('now'))->format('Y-m-d H:i:s');
//        
//        $qrcodeScan = $appService->createQRCodeScan('ONE_PF', 'ONE_ENTERPRISE', 'ONE_PARTICIPANT', $datetime);
//        $this->assertIsA($qrcodeScan, 'QRCodeScan');
//    }
    
//    public function testSaveQRCodeScan() {
//        
//        date_default_timezone_set('Europe/Paris');
//        $appService = AppServiceImpl::getInstance();
//        
//        $purchasingFair = $appService->findOnePurchasingFair(3);
//        $enterprise     = $appService->findOneEnterprise(1);
//        $participant    = $appService->findOneParticipant(2);
//        
//        $qrcodeScan = $appService->createQRCodeScan($purchasingFair, $enterprise, $participant, '');
//        $this->assertTrue($appService->saveQRCodeScan($qrcodeScan));
//    }
    
//    public function testFindAllQRCodeScan() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertTrue(is_array($appService->findAllQRCodeScan()));
//    }
    
//        public function testFindAllQRCodeScanByTrio() {
//            $appService = AppServiceImpl::getInstance();
//            $this->assertTrue(is_array($appService->findAllQRCodeScanByTrio(3, 1, 189))); // OR 3|93|106
//        }
    
//    public function testFindOneQRCodeScan() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertIsA($appService->findOneQRCodeScan(2), 'QRCodeScan');
//        var_dump($appService->findOneQRCodeScan(2));
//    }

//    public function testDeleteQRCodeScan() {
//        $appService = AppServiceImpl::getInstance();
//        $this->assertTrue($appService->deleteQRCodeScan(1));
//    }
}
