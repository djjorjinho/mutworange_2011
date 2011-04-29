<?php
require_once("lib/System/Daemon.php");
require_once("lib/DB.php");
require_once("lib/Server.php");
require_once("lib/JsonRpcDispatcher.php");
require_once("lib/Scheduler.php");
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
	
	function __construct($options){
		
		$this->config = $options;
		
		try{
			$this->db = DB::getInstance($options['dbconfig']);
		}catch(Exception $e){
			System_Daemon::emerg($e->getMessage());
		}
		
		$this->dispatcher = new JsonRpcDispatcher($this);
		
		$this->scheduler = new Scheduler(
						array(),
						array( get_class($this) => $this ));
		
		parent::__construct($options); # leave this for last
	}
	
	function ping($params){
		return array(ping=>$params['name']);
	}
	
    function onMessage(&$message,&$response,$event_loop){
		//$response = "HTTP/1.0 200 OK\n".
		//			"Content-Type: text/html\n".
		//			"Server: LooPHP"."\r\n\r\n".
		//			"<body>Hello World</body>";
		
		$response = $this->dispatcher->dispatch($message);
    }
    
    // allowed methods by the JsonRpcDispatcher object
    function rpcMethods(){
    	
    	$methods = array(
    		'ping' => true
    	);
    	
    	return $methods;
    }
}

?>