#!/usr/bin/env php
<?php
require_once 'PHPUnit.php';
require_once 'lib/DB.php';
require_once 'lib/Thread.php';
class ThreadTest extends PHPUnit_TestCase {
    
    function testThreadSupport(){
	if( ! Thread::available() ) {
	    $this->assertTrue(false);
	    die( 'Threads not supported' );
	}
	$this->assertTrue(true);
    }
    
    function dummyFunc($go){
	print $go;
	sleep(3);
    }
    
    function testTread(){
	$t1 = new Thread( "dummyFunc", new ThreadTest() );
	$t1->start("HEllo\n");
	$this->assertTrue($t1->isAlive());
	while($t1->isAlive()){
	    sleep(1);
	}
    }
    
}
$suite = new PHPUnit_TestSuite('ThreadTest');
$result = PHPUnit::run($suite);
print $result->toString();
?>