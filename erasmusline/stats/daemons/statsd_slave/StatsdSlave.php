<?php
require_once("lib/System/Daemon.php");
require_once("lib/DB.php");
require_once("lib/Server.php");
require_once("lib/JsonRpcDispatcher.php");
require_once("lib/Scheduler.php");
require_once("lib/OLAP.php");
require_once("lib/ETL.php");
require_once("lib/Cache.php");
require_once("lib/Client.php");
require_once("lib/pqp/classes/PhpQuickProfiler.php");
/**
 * Deamon that communicates with Erasmusline and delivers summary information
 * to EIS interface.
 * Main daemon class 
 *
 */
class StatsdSlave extends Server implements JsonRpcI{

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
		
		$this->olap = new OLAP($options['olapconfig']);
		
		$this->etl = new ETL($options['etlconfig']);
		
		# leave these for last
		$this->scheduler = new Scheduler(
						$this->getTasks(),
						array( get_class($this) => $this,
								get_class($this->etl) => $this->etl ));
								
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
    	$config = $this->config;
    	
    	// redirect rules
    	foreach($config['jsonrpc_redirect'] as $rule){
    		if(preg_match($rule['regex'],$message) > 0){
    			
    			$client = new Client($config[$rule['connect_config']]);
    			$client->sendMessage($message,$response);
    			
    			return;
    		}
    	}
    	
    	// no rules matched
		$response = $this->dispatcher->dispatch($message);
    }
    
    function getTasks(){
    	$tasks = array(
			new ScheduledTask(array(
				timeout => 0,
				every_seconds => 0,
				every_minute => 0,
				every_hour => 0,
				every_day => 1,
				method => 'pingMaster',
				'class' => get_class($this),
				startup => 1,
				runs => 0
			))
    	);
    	
    	return $tasks;
    }
    
    /**
     * 
     * Allowed methods by the JsonRpcDispatcher object
     * @return unknown_type var
     */
    function rpcMethods(){
    	
    	$methods = array(
    		'ping' => true,
    		'query' => true,
    		'profile' => true
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
		return array(ping=>$params['name'],ip=>$this->getIP());
	}
	
	function query($params){
		return $this->olap->runScenario($params);
	}
	
	function pingMaster(){
		$client = new Client($this->config['master_serverconfig']);
		$message = json_encode(array(
			method => 'pingSlave',
			params => array(
					ipAdress => $this->getIP(),
					port => $this->config['serverconfig']['serverPort'] 
				)
		));
    	$client->sendMessage($message,$response);
	}
	
	function profile(){
		return $this->profiler->display();
	}
}

?>