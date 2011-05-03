<?php
require_once("lib/DB.php");
require_once("lib/System/Daemon.php");
require_once("lib/Cache.php");
require_once("lib/jsonpath.php");
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
	private $rules;
	
	function __construct($options){
		$this->config = $options;
		$this->cache = ObjectCache::getInstance();
		$this->db = DB::getInstance();
		$this->rules = $this->getRules();
	}
	
	function getRules(){
    	$file = dirname(__FILE__).'/../config/'.$this->config['rulefile'];
    	if(!file_exists($file)){
    		throw new Exception("Not Found: ${file}");
    	}
		$json = json_decode(file_get_contents($file),true);
		if(!isset($json)){
			throw new Exception("Invalid JSON: ${file}");
		}
		return $json;
    }
	
	/**
	 * 
	 * Processes the scenario settings and generates an sql query
	 * @param array $params - scenario parameters to generate a query
	 * @return mixed - the scenario resultset
	 */
	function runScenario($params){
		//check scenario result in cache 
		$cache_key = md5(json_encode($params,true));
		$result = $this->cache->get($cache_key);
		if(isset($result)){
			#print("cache hit!\n");
			return $result;
		}
		
		//process scenario
		$fields = array();
		$tables = array();
		$where = array();
		$groupby = array();
		$having = array();
		$limit;
		$cube;
		$sql;
		
		$this->processCube($params, $tables, $cube);
		
		$this->processFields($params,$fields,$tables,$where,$groupby,
				$having,$limit);
		
		$this->processFilters($params,$fields,$tables,$where,$groupby,
				$having,$limit);
				
		print("fields\n");
		print_r($fields);
		
		print("tables\n");
		print_r($tables);
		
		print("where\n");
		print_r($where);
		
		print("groupby\n");
		print_r($groupby);
		
		//store result in cache and resturn
		$this->cache->store($cache_key, $result,120);
		return $result;
	}
	
	private function processCube(&$params,&$tables,&$cube){
		$cube = $params['cube'];
		if(!isset($cube)){
			throw new Exception("Invalid Cube");
		}
		
		array_push($tables, $cube);
	} 
	
	private function processFields(&$params,&$fields,&$tables,&$where,
		&$groupby,&$having,&$limit){
			
		$cube = $params['cube'];
		$columns = array_merge($params['columns'],$params['rows']);
		
		foreach($columns as $field){
			$field_array = self::splitField($field);
			
			if($field_array[0] == 'measure'){
				$this->processMeasure($cube,$field_array[1],$fields,
						$tables,$where,$limit);				
			}else{
				$table = $field_array[0];
				$id = $table."_id";
				//TODO no repeat tables
				array_push($tables,"${table} using (${id})");
				array_push($groupby,$field);
			}
			
		}
		
		
	}
	
	static function splitField($field){
		$res = preg_split("/\./",$field);
		#print_r($res);
		return $res;
	}
	
	private function processMeasure(&$cube,&$measure_id,&$fields,
										&$tables,&$where,&$limit){
											
		$measure = array_shift(jsonPath($this->rules, 
			"$.cubes[?(@['table']=='${cube}')]".
			".measures[?(@[id]=='${measure_id}')]",
			array("resultType" => "VALUE")));
		
		$aggregator = $measure['aggregator'];
		$column = $measure['column'];
		
		//TODO process aggregators
		
		array_push($fields,"${aggregator}(${cube}.${column}) as ${column}");
	}
	
	private function processFilters(&$params,&$fields,&$tables,&$where,
		&$groupby,&$having,&$limit){
		//TODO
	}
}
?>