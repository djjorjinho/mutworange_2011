<?php
//error_reporting(0);
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}"));
require_once 'PHPUnit.php';
require_once("lib/OLAP.php");
require_once('lib/JSONConfig.php');

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
		$config = JSONConfig::load(dirname(__FILE__).'/../config/','eis_scenario_config');
    	
    	$results = $this->olap->runScenario($config['params']);
		print substr(print_r($results,true),-850,850);
    	$this->assertTrue(is_array($results));
    }
    
    function testOLAP2(){
    	$params=array(
    		cube => 'fact_efficiency',
    		columns => array("dim_gender.dim_gender_id",
    					"dim_mobility.dim_mobility_id"),
    		rows => array("dim_date.all"),
    		filters => array()
    	);
    	 
    	$results = $this->olap->runScenario($params);
    	print substr(print_r($results,true),-850,850);
    	$this->assertTrue(is_array($results));
    }
    
    function testOLAP3(){
    	$params=array(
    	cube => 'fact_efficacy',
    	columns => array("dim_gender.dim_gender_id"),
    	rows => array("dim_institution.institution_code","dim_date.year",
    					"dim_institution_host.institution_code"),
    	filters => array('dim_date.year.le' => '2010')
    	);
    
    	$results = $this->olap->runScenario($params);
    	print substr(print_r($results,true),-850,850);
    	$this->assertTrue(is_array($results));
    }
    
    function testOLAP4(){
    	$params=array(
    	cube => 'fact_efficacy',
    	columns => array("dim_gender.dim_gender_id","dim_date.year","measure.M1","measure.M5"),
    	rows => array("dim_institution.institution_code","dim_date.semester",
        					"dim_institution_host.institution_code",
        					"dim_study.area_code","dim_study.degree_code","measure.M3"),
    	filters => array()
    	);
    
    	$results = $this->olap->runScenario($params);
    	print substr(print_r($results,true),-850,850);
    	
    	$this->assertTrue(is_array($results));
    }
    
    
}
$suite = new PHPUnit_TestSuite('OLAPTest');
$phpu = new PHPUnit();
$result = $phpu->run($suite);
print $result->toString();
?>