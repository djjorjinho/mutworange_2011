<?php
require_once("lib/System/Daemon.php");
require_once("lib/DB.php");
require_once("lib/Server.php");
require_once("lib/JsonRpcDispatcher.php");
require_once("lib/Scheduler.php");
require_once("lib/Cache.php");
require_once("lib/pqp/classes/PhpQuickProfiler.php");
/**
 * Daemon that runs tasks saved in the DB 
 *
 */
class MQueued extends Server implements JsonRpcI{

	private $db;
	private $config;
	private $dispatcher;
	private $scheduler;
	private $olap;
	private $etl;
	private $cache;
	private $profiler;
	
	function __construct($options){
		
		$this->config = $options;
		
		$this->profiler = new PhpQuickProfiler(
					PhpQuickProfiler::getMicroTime());
					
		$this->cache = ObjectCache::getInstance();
		
		try{
			$this->db = DB::getInstance($options['dbconfig']);
		}catch(Exception $e){
			System_Daemon::emerg($e->getMessage());
		}
		
		$this->dispatcher = new JsonRpcDispatcher($this);
		
		# leave these for last
		$this->scheduler = new Scheduler(
						array(),
						array( get_class($this) => $this));
		
		parent::__construct($options);
	}
	
	/**
	 * 
	 * Receives messages from the socket and sends them to the Json dispatcher.
	 * Messages should be valid Json-Rpc messages otherwise it doesn't work.
	 * @param str $message
	 * @param str $response
	 * @param obj $event_loop
	 */
    function onMessage(&$message,&$response,$event_loop){
		$response = $this->dispatcher->dispatch($message);
    }
    
    /**
     * 
     * Allowed methods by the JsonRpcDispatcher object
     * @return unknown_type var
     */
    function rpcMethods(){
    	
    	$methods = array(
    		'ping' => true,
    		'runTasks' => true,
    	);
    	
    	return $methods;
    }
    
    /**
     * 
     * Dummy ping method for rpc call tests.
     * @param array $params
     * @return array $array
     */
	function ping($params){
		return array(ping=>$params['name']);
	}
	
	function getTasks(){
    	$dt = new DateTime('@'.time());
    	$tasks = array(
			new ScheduledTask(array(
				'timeout' => 0,
				'every_seconds' => 0,
				'every_minute' => 1,
				'every_hour' => 0,
				'every_day' => 0,
				'method' => 'runTasks',
				'class' => get_class($this),
				'startup' => 1,
				'runs' => 0
			))
    	);
    	
    	return $tasks;
    }
    
    function runTasks(){
    	$db = $this->db;
    	$records = $db->getMany("select * from mqueue where finished=0");
    	
    	foreach($records as $item){
    		try{
    			
    		}catch(Exception $e){
    			
    		}
    	}
    	
    	
    }
    
}

?>