<?php 
require_once("lib/DB.php");
require_once("lib/System/Daemon.php");
/**
 * 
 * Extraction-Tranformation-Load class.
 * Gathers information from erasmusline db to insert in the Data Warehouse
 * @author daniel
 *
 */
class ETL{
	private $db;
	private $db_source;
	private $datamining;
	private $config;
	
	function __construct($options){
		$this->config = $options;
		$this->db = DB::getInstance();
	}
	
	
}
?>