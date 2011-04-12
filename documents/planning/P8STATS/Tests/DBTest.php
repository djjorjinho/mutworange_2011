<?php
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
		   code => 'O',
		   description => 'Other'
		   );
		$id = $db->insert($obj,'dim_gender');
		$this->assertRegexp("/^\d+$/",$id);

	}
	
	function testGetObj(){
		$db = $this->db;
		$obj = $db->getObj("dim_gender.O");
		
		$this->assertTrue($obj['code'] == 'O');
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
$result = PHPUnit::run($suite);
print $result->toString();
?>