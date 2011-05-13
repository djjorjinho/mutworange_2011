<?php
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}"));
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
		$context = array(count=>1,max_rsp=>0,min_rsp=>0,total_rsp=>0);
		$row = array(
			year => 2011,
			semester => 1,
			dim_phase_id => 'precandidate',
			institution_code => 'fkl',
			country_code => 'de',
			institution_host_code => 'isep',
			country_host_code => 'pt',
			dim_mobility_id => 'study',
			dim_gender_id => 'M',
			create_date => '2011-01-05 00:00:00',
			approve_date => '2011-01-30 00:00:00',
			lodging_available => 1
		);
		
		$NRow = $this->etl->transformODSRow($rules,$row,$context);
		#print_r($NRow);
		#print_r($context);
		
		$this->assertEquals(25,$context['avg_rsp']);
		
		$context['count']++;
		$row = array(
			year => 2011,
			semester => 1,
			dim_phase_id => 'precandidate',
			institution_code => 'fkl',
			country_code => 'de',
			institution_host_code => 'isep',
			country_host_code => 'pt',
			dim_mobility_id => 'study',
			dim_gender_id => 'M',
			create_date => '2011-01-05 00:00:00',
			approve_date => '2011-01-20 00:00:00',
			lodging_available => 0
		);
		$NRow = $this->etl->transformODSRow($rules,$row,$context);
		#print_r($NRow);
		#print_r($context);
		
		$this->assertEquals(20,$context['avg_rsp']);
	}
	
	function testDataMineEfficiency(){
		$prev_row = array(
			val_participants => 2,
			avg_response_days => 25,
			max_response_days => 30,
			min_response_days => 20,
			lodging_available => 1,
		);
		
		$ctx = array(
			count=>1,
			max_rsp=>40,
			min_rsp=>20,
			total_rsp=>60,
			lodg_avail=>2,
		);
		$this->etl->dataMineEfficiency($prev_row,$ctx);
		
		#print_r($prev_row);
		#print_r($ctx);
		
		$this->assertEquals(-50,$ctx['perc_students']);
		$this->assertEquals(100,$ctx['perc_lodging']);
	}
	
}
 
$suite = new PHPUnit_TestSuite('ETLTest');
$result = PHPUnit::run($suite);
print $result->toString();
?>