<?php
$ipath = get_include_path();
set_include_path($ipath.":".dirname(__FILE__)."/../");
require_once 'PHPUnit.php';
require_once 'lib/JsonRpcDispatcher.php';
class JsonRpcDispatcherTest extends PHPUnit_TestCase implements JsonRpcI{
	
	var $dispatcher;
	
	function JsonRpcDispatcherTest($name){
		$this->PHPUnit_TestCase($name);
	}
	
	function setUp(){
		$this->dispatcher = new JsonRpcDispatcher($this);
	}
	
	function dummy($params){
		#print(print_r($params,true));
		return true;
	}
	
	function testDispatchOK(){
		// wellformed json string
		$json = <<<JSON
		{
			"method" : "dummy",
			"params" : {
				"hello" : "world"
			}
		}
JSON;
		$res = $this->dispatcher->dispatch($json);
		$this->assertRegExp('/result/',$res);
		$this->assertTrue(array_key_exists('result',json_decode($res,true)));
	}
	
	function testDispatchERR(){
		// malformed json string
		$json = <<<JSON
		{
			method : "dummy",
			params : {
				hello : "world"
			}
		}
JSON;
		$res = $this->dispatcher->dispatch($json);
		$this->assertRegExp( '/error/', $res );
		$this->assertTrue(array_key_exists('error',json_decode($res,true)));
	}
        
	function tearDown(){
		unset($this->dispatcher);
	}
	
// allowed methods by the JsonRpcDispatcher object
    function rpcMethods(){
    	
    	$methods = array(
    		'dummy' => true
    	);
    	
    	return $methods;
    }
}
 
$suite = new PHPUnit_TestSuite('JsonRpcDispatcherTest');
$result = PHPUnit::run($suite);
print $result->toString();
?>