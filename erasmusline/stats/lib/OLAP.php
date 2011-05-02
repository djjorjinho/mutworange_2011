<?php
require_once("lib/DB.php");
require_once("lib/System/Daemon.php");
require_once("lib/Cache.php");
/**
 * 
 * OLAP class to interpret the EIS scenarios into a query for processing and 
 * measuring statistical values
 * @author daniel
 *
 */
class OLAP{
	private $db;
	private $config;
	private $cache;
	
	function __construct($options){
		$this->config = $options;
		$this->cache = ObjectCache::getInstance();
		$this->db = DB::getInstance();
	}
	
	/**
	 * 
	 * Processes the scenario settings and generates an sql query
	 * @param array $params - scenario parameters to generate a query
	 * @return mixed - the scenario resultset
	 */
	function runScenario($params){
		
	}
	
}
?>