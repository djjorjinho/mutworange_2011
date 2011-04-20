<?php
require_once("lib/Thread.php");
class Scheduler {
	private $thread;
	public $tasks;
	private $callback_obj;
	
	function __construct($tasks,$callback_obj){
		$this->tasks = $tasks;
		$this->callback_obj = $callback_obj;
		
		$this->thread = new Thread( "process", $this );
		$this->thread->start();
	}
	
	public function process(){
		
		while(true){
			
			$obj = $this->callback_obj;
			$tasks = $this->tasks;
			
			foreach($tasks as $task){
				$time = time();
				#error_log($task->timeout.":::".$time  );
				if($task->timeout <= $time){
					
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

class ScheduledTask{
	
	public $record;
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