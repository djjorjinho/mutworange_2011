<?php
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".dirname(__FILE__)."${sep}..${sep}");
require_once 'PHPUnit.php';
require_once 'lib/ETL.php';
require_once 'lib/DB.php';

class ETLTest extends PHPUnit_TestCase {
	
	private $etl;
	private $db;
        
	function __construct($name){
		$this->PHPUnit_TestCase($name);
	}
        
	function setUp(){
		$config = array();
		$this->etl = new ETL($config);
		$this->db = DB::getInstance();
	}
	
	function tearDown(){}
	
	function testAcademicDate(){
		$dt = new DateTime("2011-01-01 00:00:00");
		list($year,$month) = $this->etl->getDate($dt);
		
		$this->assertEquals("2011", $year);
		$this->assertEquals("1", $month);
		
		$res = $this->etl->getAcademicDate($year, $month);
		list($year,$semester) = $res; 
		
		$this->assertEquals("2011", $year);
		$this->assertEquals("1", $semester);
	}
	
	function testCreateEfficiencyMergeTable(){
		$db = $this->db;
		list($mrg_table,$prev_table) = 
					$this->etl->createEfficiencyFactTable(2010, 1);
		$this->assertEquals("fact_efficiency_2010_1s",$mrg_table);
		
		$mrg_tables = $db->getMergedTables("fact_efficiency");
		array_pop($mrg_tables);
		$this->assertEquals( end($mrg_tables), $prev_table);
	}
	
	function testGetLastOSDId(){
		$id = $this->etl->getLastOSDId("ods_efficiency");
		$this->assertTrue(is_numeric($id));
	}
	
	function testTransformODSRow(){
		$rules = $this->etl->getEfficiencyTransformationRules();
		$context = array();
		$row = array(
			year => 2011,
			semester => 1,
			dim_phase_id => 'douche'
			
		);
		
		$NRow = $this->etl->transformODSRow($rules,$row,$context);
		print_r($NRow);
	}
	
}
 
$suite = new PHPUnit_TestSuite('ETLTest');
$result = PHPUnit::run($suite);
print $result->toString();
?>