<?php
$ipath = get_include_path();
set_include_path($ipath.":".dirname(__FILE__)."/../");
require_once 'PHPUnit.php';
require_once("lib/Scheduler.php");
class SchedulerTest extends PHPUnit_TestCase {

    function SchedulerTest($name){
        $this->PHPUnit_TestCase($name);
    }
    
	function dummyFunc(){
		error_log("Hello World!");
	}
	
	function testSchedule(){
		print "SchedulerTest::testSchedule: wait a minute\n";
		$row = array(
				timeout => 0,
				every_seconds => 0,
				every_minute => 1,
				every_hour => 0,
				every_day => 0,
				method => 'dummyFunc',
				'class' => get_class($this),
				startup => 0,
				runs => 0
				);
		
		$task = new ScheduledTask($row);
		
		$sched = new Scheduler(
				array($task),
				array( get_class($this) => $this));
				
		sleep(65);
		$sched->shutdown();
	}
	
    
}
$suite = new PHPUnit_TestSuite('SchedulerTest');
$result = PHPUnit::run($suite);
print $result->toString();
?>
