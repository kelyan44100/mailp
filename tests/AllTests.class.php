<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;

class AllTests extends TestSuite {
    
    // Constructor
    function __construct() {
        parent::__construct();
        
        // Do all the tests
        $this->addFile(dirname ( __FILE__ ) .'/AssignmentParticipantDepartmentTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/AssignmentParticipantEnterpriseTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/AssignmentSpStoreTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/DepartmentTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/EnterpriseTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/LogTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/MySimpleTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/ParticipantTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/ParticipationTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/ProfileTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/PurchasingFairTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/RequirementTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/SalespersonTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/SingletonConnectionMySQLTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/SingletonTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/TypeOfPfTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/UnavailabilitySpTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/UnavailabilityTest.class.php');
        $this->addFile(dirname ( __FILE__ ) .'/VariousTest.class.php');
    }
}
?>