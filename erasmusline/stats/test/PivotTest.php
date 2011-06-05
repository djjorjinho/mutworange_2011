<?php
//error_reporting(0);
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}"));
require_once 'PHPUnit.php';
require_once("lib/Pivot.php");

class PivotTest extends PHPUnit_TestCase {
	
	private $result = array(
		    array('host' => 1, 'country' => 'fr', 'year' => 2010,
		        'month' => 1, 'clicks' => 123, 'users' => 4),
		    array('host' => 1, 'country' => 'fr', 'year' => 2010,
		        'month' => 2, 'clicks' => 134, 'users' => 5),
		    array('host' => 1, 'country' => 'fr', 'year' => 2010,
		        'month' => 3, 'clicks' => 341, 'users' => 2),
		    array('host' => 1, 'country' => 'es', 'year' => 2010,
		        'month' => 1, 'clicks' => 113, 'users' => 4),
		    array('host' => 1, 'country' => 'es', 'year' => 2010,
		        'month' => 2, 'clicks' => 234, 'users' => 5),
		    array('host' => 1, 'country' => 'es', 'year' => 2010,
		        'month' => 3, 'clicks' => 421, 'users' => 2),
		    array('host' => 1, 'country' => 'es', 'year' => 2010,
		        'month' => 4, 'clicks' => 22,  'users' => 3),
		    array('host' => 2, 'country' => 'es', 'year' => 2010,
		        'month' => 1, 'clicks' => 111, 'users' => 2),
		    array('host' => 2, 'country' => 'es', 'year' => 2010,
		        'month' => 2, 'clicks' => 2,   'users' => 4),
		    array('host' => 3, 'country' => 'es', 'year' => 2010,
		        'month' => 3, 'clicks' => 34,  'users' => 2),
		    array('host' => 3, 'country' => 'es', 'year' => 2010,
		        'month' => 4, 'clicks' => 1,   'users' => 1),
		);
	
    function __construct($name){
        $this->PHPUnit_TestCase($name);
        
        parent::PHPUnit_TestCase($name);
    }
    
    function runPivot($result,$columns,$rows,$measures){
    	$data = Pivot::factory($result)
    	->pivotOn($rows)
    	->addColumn($columns,$measures)
    	->fetch();
    	return $data;
    }
    
    function testPivot(){
    	$columns = array('year', 'month');
    	$rows = array('country','host');
    	$measures = array('users','clicks');
    	$res = $this->runPivot($this->result, $columns, $rows, $measures);
    	//print_r($res);
    }
    
    function testNewPivot(){
    	$columns = array('year','month');
    	$rows = array('country','host');
    	$measures = array('users','clicks');
    	
    	$out = Pivot::factory($this->result)
    				->newFetch($columns, $rows, $measures);
    	
    	print("new output: ".print_r($out,true));
    }
    
}
$suite = new PHPUnit_TestSuite('PivotTest');
$phpu = new PHPUnit();
$result = $phpu->run($suite);
print $result->toString();
?>