<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../domain/EnterpriseContact.class.php';

class EnterpriseContactTest extends UnitTestCase {
    
    // Methods
    public function testCreateEnterpriseContact() {
        $appService = AppServiceImpl::getInstance();
        $ecCreated = $appService->createEnterpriseContact('Monsieur', 'MOLINARO', 'Nicolas', 'nicolasmolinaro@scaouest.fr', 51,'','-1');
        $this->assertIsA($ecCreated, 'EnterpriseContact');
    }
    
    public function testSaveEnterpriseContactInsert() {
        $appService = AppServiceImpl::getInstance();
        $ecInsert = new EnterpriseContact('Monsieur', 'MOLINARO', 'Nicolas', 'nicolasmolinaro@scaouest.fr', $appService->findOneEnterprise(51));
        $this->assertTrue($appService->saveEnterpriseContact($ecInsert));
    }
    
    public function testFindOneEnterpriseContact() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->findOneEnterpriseContact(1), 'EnterpriseContact');
    }
    
    public function testFindByProviderSuccess() {
        $appService = AppServiceImpl::getInstance();
        $this->assertNotNull($appService->findOneEnterpriseContactByEnterprise(51));
    }
    
    public function testFindByProviderFail() {
        $appService = AppServiceImpl::getInstance();
        $this->assertNull($appService->findOneEnterpriseContactByEnterprise(123456));
    }
        
    public function testSaveEnterpriseContactUpdate() {
        $appService = AppServiceImpl::getInstance();
        $ecUpdate = $appService->findOneEnterpriseContact(1);
        $ecUpdate->setCivility("Mister");
        $ecUpdate->setSurname("NEW_SURNAME");
        $ecUpdate->setName("NEW_NAME");
        $ecUpdate->setEmail("toto@test.fr");
        // $ecUpdate->setOneEnterprise($appService->findOneEnterprise(51));
        $this->assertTrue($appService->saveEnterpriseContact($ecUpdate));
    }
    
    public function testDeleteEnterpriseContact() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->deleteEnterpriseContact($appService->findOneEnterpriseContact(1)));
    }
}