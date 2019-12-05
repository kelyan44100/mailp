<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class ParticipantTest extends UnitTestCase {

    // Methods
    public function testCreateParticipant() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->createParticipant('Monsieur', 'MOLINARO', 'Nicolas', "nmolinaro@scaouest.fr"), 'Participant');
    }
    
    public function testSaveParticipantInsert() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->saveParticipant($appService->createParticipant('Monsieur', 'VERATTI', 'MARCO', "mverrati")));
    }
    
    public function testFindAllParticipants() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllParticipants()));
    }
    
    public function testFindOneParticipant() {
        $appService = AppServiceImpl::getInstance();
        $this->assertIsA($appService->findOneParticipant(1), 'Participant');
    }
    
    public function testSaveParticipantUpdate() {
        $appService = AppServiceImpl::getInstance();
        $participantTest = $appService->findOneParticipant(1);
        $participantTest->setName("ZLATAN");
        $participantTest->setEmail("IBRAHIMOVIC");
        $this->assertTrue($appService->saveParticipant($participantTest));
    }
    
    public function testDeleteParticipant() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->deleteParticipant($appService->findOneParticipant(26)));
    }
    
    public function testDeactivateParticipant() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue($appService->deactivateParticipant($appService->findOneParticipant(10)));
    }
}