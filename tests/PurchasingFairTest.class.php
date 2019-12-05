<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class PurchasingFairTest extends UnitTestCase {

    // Methods
    
    // Test the creation of an object PurchasingFair
    public function testCreatePurchasingFair() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->createPurchasingFair('NAME','COLOR','DATETIME_1', 'DATETIME_2', 'TIME', 'TYPEOF', 'CLOSING_DATE_MAGASIN', 'CLOSING_DATE_FOURNISSEUR'), 'PurchasingFair');
    }

    // Test the insertion of an object PurchasingFair
    public function testSavePurchasingFairInsert() {
        $appService = AppServiceImpl::getInstance();
        $purchasingFairTest = $appService->createPurchasingFair('testName123','#abcdef','2111-01-01 01:01:01', '2222-02-02 02:02:02', '06:06:06', 1, '2018-08-08 08:08:08', '2018-08-08 08:08:08');
        $this->assertNotEqual(0, $appService->savePurchasingFair($purchasingFairTest));
    }

    // Test the search for all PurchasingFair
    public function testFindAllPurchasingFair() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(!empty($appService->findAllPurchasingFairs()));
    }

    // Test the search for one PurchasingFair
    public function testFindOnePurchasingFair() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->findOnePurchasingFair(2), 'PurchasingFair');
    }

    // Test the search for the last PurchasingFair inserted
    public function testFindLastPurchasingFair() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->findLastPurchasingFair(), 'PurchasingFair');
    }

    // Test the update for one PurchasingFair
    public function testSavePurchasingFairUpdate() {
        $appService = AppServiceImpl::getInstance();
        $pfTest = $appService->findOnePurchasingFair(1);
        $pfTest->setNamePurchasingFair("newName789");
        $pfTest->setHexColor("#hexhex");
        $pfTest->setStartDatetime("1905-01-02 03:04:05");
        $pfTest->setEndDateTime("1906-06-07 08:09:10");
        $pfTest->setLunchBreak("11:12:13");
        $pfTest->setOneTypeOfPf($appService->findOneTypeOfPf(2));
        $pfTest->setRegistrationClosingDateMagasin("2010-11-12 13:14:15");
        $pfTest->setRegistrationClosingDateFournisseur("2010-11-12 13:14:15");
        $this->assertNotEqual(0, $appService->savePurchasingFair($pfTest));
    }
    
    // Test the deactivation for one PurchasingFair (the last)
    public function testDeactivatePurchasingFair() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->deactivatePurchasingFair($appService->findOnePurchasingFair(1)));
    }
    
    // Test the deletion for one PurchasingFair (the last)
    public function testDeletePurchasingFair() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->deletePurchasingFair($appService->findLastPurchasingFair()));
    }
    

}