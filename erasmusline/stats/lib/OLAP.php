<?php
require_once("lib/DB.php");
require_once("lib/System/Daemon.php");
/**
 * 
 * OLAP class to interpret the EIS scenarios into a query for processing and 
 * measuring statistical values
 * @author daniel
 *
 */
class OLAP{
	private $db;
	
	function __construct(){
		$this->db = DB::getInstance();
	}
	
	/**
	 * 
	 * Processes the scenario settings and generates an sql query
	 * @param unknown_type $params
	 * @return unknown_type var
	 */
	function runScenario($params){
		
	}
	
}
?>