<?php
require_once("lib/Thread.php");
/**
 * 
 * Runs object methods after a defined timeout.
 * The loop runs in a separate thread, so be carefull with it ok?
 * @author daniel
 *
 */
class Scheduler {
	private $thread;
	public $tasks;
	private $obj_registry;
	
	/**
	 * 
	 * Sets-up a task list and a list of referenced objects in a registry map
	 * to callback upon
	 * @param array $tasks - array with <ScheduledTask> objects
	 * @param array $obj_registry - assoc. array that maps classname to obj ref
	 */
	function __construct($tasks,$obj_registry){
		$this->tasks = $tasks;
		$this->obj_registry = $obj_registry;
		
		$this->thread = new Thread( "process", $this );
		$this->thread->start();
	}
	
	public function process(){
		
		while(true){
			
			$tasks = $this->tasks;
			
			foreach($tasks as $task){
				$time = time();
				#error_log($task->timeout.":::".$time  );
				if($task->timeout <= $time){
					
					$obj = $this->obj_registry[$task->record['class']];
					
					$call = array($obj,$task->record['method']);
					call_user_func_array( $call ,array());
					
					$task->runs++;
					$task->nextTimeout();
				}
				
			}
			sleep(60); // every minute is the minimum timeout
		}
	}
	
	function shutdown(){
		$this->thread->stop();
	}
	function __destruct(){
		$this->shutdown();
	}
	
	static function makeSchedule($array){
		return array_map(function($row){
					return new ScheduledTask($row);
				},$array);
	}
	
	static function newTask(){
		
	}
}

/**
 * 
 * Task wrapper class that indicates the method, the next execution time 
 * and the number of executions
 * @author daniel
 *
 */
class ScheduledTask{
	
	public $record;
	
	/**
	 * 
	 * Timeout which the task will be executed
	 * @var int epoch time
	 */
	public $timeout;
	
	public $runs;
	
	function __construct($record){
		$this->record = $record;
		$this->runs=0;
		$this->nextTimeout();
	}
	
	function save(){
		if(class_exists('DB')){
			$db = DB::getInstance();
			$db->update($record);
		}
		
	}
	
	/**
	 * 
	 * Calculates the next time the task will be run.
	 */
	function nextTimeout(){
		$rec = $this->record;
		
		$timeout = $rec['every_seconds'];
		$timeout += $rec['every_minute'] * 60;
		$timeout += $rec['every_hour'] * 60 * 60;
		$timeout += $rec['every_day'] * 60 * 60;
		
		$timeout += time();
		
		$this->timeout = $timeout;
	}
	
}

?>