<?php
$ipath = get_include_path();
set_include_path($ipath.":".dirname(__FILE__)."/../");
require_once 'PHPUnit.php';
require_once("lib/OLAP.php");
class OLAPTest extends PHPUnit_TestCase {
	
	private $olap;
	
    function __construct($name){
        $this->PHPUnit_TestCase($name);
		
        $options = array("rulefile" => "eis_scenario_rules.json");
        
        $this->olap = new OLAP($options);
        
        parent::PHPUnit_TestCase($name);
    }
    
    function getJSON($path){
    	$file = dirname(__FILE__).'/'.$path;
    	if(!file_exists($file)){
    		throw new Exception("Not Found: ${file}");
    	}
		$json = json_decode(file_get_contents($file),true);
		if(!isset($json)){
			throw new Exception("Invalid JSON: ${file}");
		}
		return $json;
    }
	
    function testOLAP1(){
    	$config = $this->getJSON('../config/eis_scenario_config.json');
    	
    	$results = $this->olap->runScenario($config['params']);
    	#print_r($results);
    	$this->assertTrue(is_array($results));
    }
    
}
$suite = new PHPUnit_TestSuite('OLAPTest');
$result = PHPUnit::run($suite);
print $result->toString();
?>