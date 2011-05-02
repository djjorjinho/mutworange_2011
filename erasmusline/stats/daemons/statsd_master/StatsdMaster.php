<?php
require_once("lib/System/Daemon.php");
require_once("lib/DB.php");
require_once("lib/Server.php");
require_once("lib/JsonRpcDispatcher.php");
require_once("lib/Scheduler.php");
require_once("lib/OLAP.php");
require_once("lib/ETL.php");
require_once("lib/Cache.php");
/**
 * Deamon that communicates with Erasmusline and delivers summary information
 * to EIS interface.
 * Main daemon class 
 *
 */
class StatsdMaster extends Server implements JsonRpcI{

	private $db;
	private $config;
	private $dispatcher;
	private $scheduler;
	private $olap;
	private $etl;
	private $cache;
	
	function __construct($options){
		
		$this->config = $options;
		
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
						array(),
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
		$response = $this->dispatcher->dispatch($message);
    }
    
    /**
     * 
     * Allowed methods by the JsonRpcDispatcher object
     * @return unknown_type var
     */
    function rpcMethods(){
    	
    	$methods = array(
    		'ping' => true
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
}

?>