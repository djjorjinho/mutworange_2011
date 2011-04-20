<?php
$ipath = get_include_path();
set_include_path($ipath.":".dirname(__FILE__)."/../");
require_once 'PHPUnit.php';
require_once 'lib/TSample.php';
class TSampleTest extends PHPUnit_TestCase {
	
        var $rnd;
        
        function TSampleTest($name){
            $this->PHPUnit_TestCase($name);
        }
        
        function setUp(){
            $this->rnd = new TSample;
        }
        
        function testWords(){
            $arr = $this->rnd->words(5);
            $this->assertEquals(true,is_array($arr));
        }
        
        function testDate(){ 
            $dt1 = new DateTime('2010/2/1 23:11:12'); 
            $dt2 = new DateTime('2011/1/1 23:11:12');
            $dt1 =  $dt1->getTimestamp(); // mktime(23,11,12,2,1,2010)
            $dt2 =  $dt2->getTimestamp();
            $dt = $this->rnd->datetime($dt1,$dt2);
            $this->assertRegExp("/\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d/",$dt);
        }
	
	function tearDown(){}
	
}
 
$suite = new PHPUnit_TestSuite('TSampleTest');
$result = PHPUnit::run($suite);
print $result->toString();
?>