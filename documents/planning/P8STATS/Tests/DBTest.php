<?php
require_once 'PHPUnit.php';
require_once 'lib/DB.php';
class DBTest extends PHPUnit_TestCase {
	
	function DBTest($name){
            $this->PHPUnit_TestCase($name);
        }
	
	function testConnection(){
		$db = DB::getInstance();
		$this->assertEquals(true,$db->connect());
	}
	
	function testTableInfo(){
		$db = DB::getInstance();
		$tinfo = $db->tableInfo('dim_date');
		$this->assertEquals(true, is_array($tinfo) );
	}
	
	function testInsert(){
		$db = DB::getInstance();
		
		$obj=array(
		   code => 'O',
		   description => 'Other'
		   );
		$id = $db->insert($obj,'dim_gender');
		$this->assertRegexp("/^\d+$/",$id);

	}
	
	function testGetObj(){
		$db = DB::getInstance();
		$obj = $db->getObj("dim_gender.O");
		
		$this->assertTrue($obj['code'] == 'O');
	}
	
	function testUpdate(){
		$db = DB::getInstance();
		$obj = $db->getObj("dim_gender.O");
		$obj['table'] = 'dim_gender';
		$obj['description'] = 'Mascul';
		
		$db->update($obj);
		
		$obj = $db->getObj("dim_gender.O");
		
		$this->assertTrue($obj['description'] == 'Mascul');
	}
	
	function testDelete(){
		$db = DB::getInstance();
		$obj = $db->getObj("dim_gender.O");
		$obj['table'] = 'dim_gender';
		$db->delete($obj);

		$obj = $db->getObj("dim_gender.O");
		$this->assertTrue($obj == null);
	}
	
	function testRandom(){
		$db = DB::getInstance();
		$obj = $db->getRandom('dim_gender');
		$this->assertTrue( is_array($obj) );
	}
	
}
 
$suite = new PHPUnit_TestSuite('DBTest');
$result = PHPUnit::run($suite);
print $result->toString();
?>