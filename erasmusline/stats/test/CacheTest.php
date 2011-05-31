<?php
error_reporting(0);
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}"));
require_once 'PHPUnit.php';
require_once 'lib/Cache.php';
class CacheTest extends PHPUnit_TestCase {
	
	private $cache;
        
	function CacheTest($name){
		$this->PHPUnit_TestCase($name);
	}
        
	function setUp(){
		$this->cache = new ObjectCache();
	}

	function dummy(){
		return true;
	}
	
	function tearDown(){}
	
	function testCacheFunc(){
		$cache = $this->cache;
		$arg = "World";
		
		$res = $cache->cacheFunc("helloworld",120,function()use($arg){
			print "Hello ${arg}\n";
			return true;
		});
		$this->assertTrue($res);
		
		$res = $cache->cacheFunc("helloworld",120,function()use($arg){
			return false;
		});
		$this->assertTrue($res);
	}
	
	function testCache(){
		$cache = $this->cache;
		$cache->store("ID1",$this,20);
		
		$obj = $cache->get("ID1");
		$this->assertTrue($obj->dummy());
		
		print "wait 40s\n";
		sleep(40);
		
		$obj = $cache->get("ID1");
		$this->assertTrue($obj == null);
		
	}
	
}
 
$suite = new PHPUnit_TestSuite('CacheTest');
$phpu = new PHPUnit();
$result = $phpu->run($suite);
print $result->toString();
?>