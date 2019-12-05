<?php
/* Example of test with SimpleTest framework
 * Official website : http://www.simpletest.org/en/start-testing.html
 * Doc : http://simpletest.org/api/
 * Github : https://github.com/simpletest/simpletest
 * 
 * Methods
 *
 * assertTrue($x)                       => Fail unless $x evaluates true
 * assertFalse($x)                      => Fail unless $x evaluates false
 * assertNull($x)                       => Fail unless $x is not set
 * assertNotNull($x)                    => Fail unless $x is set to something
 * assertIsA($x, $t)                    => Fail unless $x is the class or type $t
 * assertNotA($x, $t)                   => Fail unless $x is not the class or type $t
 * assertEqual($x, $y)                  => Fail unless $x == $y is true
 * assertNotEqual($x, $y)               => Fail unless $x == $y is false
 * assertWithinMargin($x, $y, $margin)  => Fail unless $x and $y are separated less than $margin
 * assertOutsideMargin($x, $y, $margin) => Fail unless $x and $y are sufficiently different
 * assertIdentical($x, $y)              => Fail unless $x === $y for variables, $x == $y for objects of the same type
 * assertNotIdentical($x, $y)           => Fail unless $x === $y is false, or two objects are unequal or different types
 * assertReference($x, $y)              => Fail unless $x and $y are the same variable
 * assertCopy($x, $y)                   => Fail unless $x and $y are the different in any way
 * assertSame($x, $y)                   => Fail unless $x and $y are the same objects
 * assertClone($x, $y)                  => Fail unless $x and $y are identical, but separate objects
 * assertPattern($p, $x)                => Fail unless the regex $p matches $x
 * assertNoPattern($p, $x)              => Fail if the regex $p matches $x
 * expectError($e)                      => Triggers a fail if this error does not happen before the end of the test
 * expectException($e)                  => Triggers a fail if this exception is not thrown before the end of the test
 * 
 * Call this page directly from the browser !
 */
require_once('simpletest-1.1.7/autorun.php');

class MySimpleTest extends UnitTestCase {
    
    // Method name must start with the string 'test'
    public function testFirst() {
        $this->assertFalse(1 < 0);
    }
}
?>