<?php
error_reporting(0);
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}"));
require_once 'PHPUnit.php';
require_once 'lib/DB.php';
class DBTest extends PHPUnit_TestCase {
	var $db;
	function DBTest($name){
		$this->db = DB::getInstance();
		$this->PHPUnit_TestCase($name);
        }
	
	function testConnection(){
		$db = $this->db;
		$this->assertEquals(true,$db->connect());
	}
	
	function testTableInfo(){
		$db = $this->db;
		$tinfo = $db->tableInfo('dim_date');
		$this->assertEquals(true, is_array($tinfo) );
	}
	
	function testInsert(){
		$db = $this->db;
		
		$obj=array(
		   dim_gender_id => 'O',
		   description => 'Other'
		   );
		$id = $db->insert($obj,'dim_gender');
		$this->assertRegexp("/O/",$id);

	}
	
	function testGetObj(){
		$db = $this->db;
		$obj = $db->getObj("dim_gender.O");
		
		$this->assertTrue($obj['dim_gender_id'] == 'O');
	}
	
	function testDBKEY(){
		list($table,$id) = preg_split("/\./","slaves.192.168.0.1",2);
		$this->assertEquals("slaves",$table);
		$this->assertEquals("192.168.0.1",$id);
	}
	
	function testUpdate(){
		$db = $this->db;
		$obj = $db->getObj("dim_gender.O");
		$obj['table'] = 'dim_gender';
		$obj['description'] = 'Mascul';
		
		$db->update($obj);
		
		$obj = $db->getObj("dim_gender.O");
		
		$this->assertTrue($obj['description'] == 'Mascul');
	}
	
	function testDelete(){
		$db = $this->db;
		$obj = $db->getObj("dim_gender.O");
		$obj['table'] = 'dim_gender';
		$db->delete($obj);

		$obj = $db->getObj("dim_gender.O");
		$this->assertTrue($obj == null);
	}
	
	function testRandom(){
		$db = $this->db;
		$obj = $db->getRandom('dim_gender');
		$this->assertTrue( is_array($obj) );
	}
	
}

$suite = new PHPUnit_TestSuite('DBTest');
$phpu = new PHPUnit();
$result = $phpu->run($suite);
print $result->toString();
?>