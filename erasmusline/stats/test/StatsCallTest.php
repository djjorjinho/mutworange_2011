<?php
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".dirname(__FILE__)."${sep}..${sep}");
require_once 'PHPUnit.php';
require_once("lib/StatsCall.php");
class StastCallTest extends PHPUnit_TestCase {
	
	private $daemon;
	private $call;
	
    function StastCallTest($name){
        $this->PHPUnit_TestCase($name);
        
   		//exec(dirname(__FILE__)."/../bin/start_daemons &> /dev/null &");
   		//sleep(2);
   		
        print "new StatsCall";
        $this->call = new StatsCall();
    }
	
	function testCall(){
		
		$call = $this->call;
		
		$rsp = $call->call('ping',array('name'=>'world'));
		
		$this->kill();
		
		print_r($rsp);
		
		$this->assertTrue($rsp['ping']=='world');
	}
	
	function testRedirectCall(){
		
		$call = $this->call;
		
		$rsp = $call->call('ping',array('name'=>'world', "method" => "query",
										"table" => 'fact_efficacy'));
		
		$this->kill();
		
		$this->assertTrue($rsp['ping']=='world');
	}
	
	function kill(){
		//exec(dirname(__FILE__)."/../bin/stop_daemons &> /dev/null &");
	}
    
}
$suite = new PHPUnit_TestSuite('StastCallTest');
$result = PHPUnit::run($suite);
print $result->toString();
?>