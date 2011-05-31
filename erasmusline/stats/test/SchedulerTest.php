<?php
error_reporting(0);
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}"));
require_once 'PHPUnit.php';
require_once("lib/Scheduler.php");
class SchedulerTest extends PHPUnit_TestCase {

    function SchedulerTest($name){
        $this->PHPUnit_TestCase($name);
    }
    
	function dummyFunc(){
		error_log("Hello World!");
		$this->assertTrue(true);
	}
	
	function testSchedule3(){
		print "SchedulerTest::testSchedule: wait a minute\n";
		$row = array(
				timeout => 0,
				every_seconds => 0,
				every_minute => 1,
				every_hour => 0,
				every_day => 0,
				method => 'dummyFunc',
				'class' => get_class($this),
				startup => 0
				);
		
		$task = new ScheduledTask($row);
		
		$sched = new Scheduler(
				array($task),
				array( get_class($this) => $this));
				
		sleep(65);
		$sched->shutdown();
	}
	
	function testSchedule2(){
		
		$row = array(
				timeout => 0,
				every_seconds => 0,
				every_minute => 1,
				every_hour => 0,
				every_day => 0,
				method => 'dummyFunc',
				'class' => get_class($this),
				startup => 1
				);
		
		$task = new ScheduledTask($row);
		
		$sched = new Scheduler(
				array($task),
				array( get_class($this) => $this));
				
		sleep(5);
		$sched->shutdown();
	}
	
	function testSchedule(){
		$dt = new DateTime('@'.(time()+20));
		print($dt->format('Y-m-d H:i:s'));
		$row = array(
				timeout => 0,
				every_seconds => 0,
				every_minute => 0,
				every_hour => 0,
				every_day => 0,
				method => 'dummyFunc',
				'class' => get_class($this),
				startup => 0,
				at => $dt->format('Y-m-d H:i:s'),
				next_months => 1
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
$phpu = new PHPUnit();
$result = $phpu->run($suite);
print $result->toString();
?>
