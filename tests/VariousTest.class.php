<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class VariousTest extends UnitTestCase {
    
    // Test the generation of n six-digits
    public function testSixDigitsGenerator() {
        $appService = AppServiceImpl::getInstance();
        $howManyCombinationsDoYouWant = 100;
        $this->assertEqual(count($appService->sixDigitsGenerator($howManyCombinationsDoYouWant)), $howManyCombinationsDoYouWant);
    }
    
    public function testRandomKey() {
        $appService = AppServiceImpl::getInstance();
        $this->assertEqual(strlen($appService->randomKey()), 210);
        var_dump($appService->randomKey());
    }
}
