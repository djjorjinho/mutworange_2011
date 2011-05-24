<?php
require_once("lib/DB.php");
require_once("lib/System/Daemon.php");
require_once("lib/Cache.php");
require_once("lib/jsonpath.php");
require_once("lib/Pivot.php");
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
	public $rules;
	
	function __construct($options){
		$this->config = $options;
		$this->cache = ObjectCache::getInstance();
		$this->db = DB::getInstance();
		$this->rules = $this->loadRules();
	}
	
	/**
	 * 
	 * Read the rules config file and returns an associative array
	 * @return array $json - associative array, product o json parsing
	 */
	function loadRules(){
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
		$sql="";
		
		$this->processCube($params, $tables, $cube);
		
		$this->processFields($params,$fields,$tables,$where,$groupby,
				$having,$limit);
		
		$this->processFilters($params,$fields,$tables,$where,$groupby,
				$having,$limit);
				
		#print("fields\n");
		#print_r($fields);
		
		$sql .= "SELECT ".implode(',',$fields);
		
		#print("tables\n");
		#print_r($tables);
		
		$sql .= " FROM ".implode(' JOIN ',$tables);
		
		#print("where\n");
		#print_r($where);
		
		if(!empty($where)) $sql .= " WHERE ".implode(' AND ',$where);
		
		#print("groupby\n");
		#print_r($groupby);
		
		if(!empty($groupby)) $sql .= " GROUP BY ".implode(',',$groupby);
		
		#print $sql."\n";
		
		$result = $this->db->getMany($sql);
		
		$table = $this->tablefyResult($result,$params['columns'],
								$params['rows'],$cube);
		
		//store result in cache and resturn
		$this->cache->store($cache_key, $table,120);
		return $table;
	}
	
	/**
	 * 
	 * Validate cube fact table and add it to the tables array
	 * @param mixed $params
	 * @param array $tables
	 * @param str $cube
	 * @throws Exception
	 */
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
				
				if(!self::valueInArray($tables,$table)) 
					array_push($tables,"${table} using (${id})");
				
				if($field_array[1]!='all'){
					array_push($groupby,$field);
					array_push($fields,$field." as `${field}`");
					
				}else{
					$dim = array_shift(jsonPath($this->rules,
					"$.dimensions[?(@['table']=='${table}')]"
					,array("resultType" => "VALUE")));
					array_push($fields,"'All $dim[name]' as `${field}`");
				}
					
			}
			
		}
		
		if(!$hasMeasure){
			$field = 'M1';
			$this->processMeasure($cube,$field,$fields,
						$tables,$where,$limit);
			array_push($params['columns'],"measure.M1");
		}
		
	}
	
	static function splitField($field){
		$res = preg_split("/\./",$field);
		#print_r($res);
		return $res;
	}
	
	static function valueInArray(&$array,&$value){
		foreach($array as $item){
			if(preg_match("/${value}/", $item))
				return true;
		}
		return false;
	}
	
	/**
	 * 
	 * Processes found measures in the scenario config.
	 * @param str $cube
	 * @param str $measure_id
	 * @param array $fields
	 * @param array $tables
	 * @param array $where
	 * @param str $limit
	 */
	private function processMeasure(&$cube,&$measure_id,&$fields,
										&$tables,&$where,&$limit){
											
		$measure = array_shift(jsonPath($this->rules, 
			"$.cubes[?(@['table']=='${cube}')]".
			".measures[?(@[id]=='${measure_id}')]",
			array("resultType" => "VALUE")));
		
		$aggregator = $measure['aggregator'];
		$column = $measure['column'];
		
		array_push($fields,"'$measure[name]' as `measure.${measure_id}`");
		
		$this->processAggregatorOp($aggregator,"${cube}.${column}",$column,
																	$fields);
	}
	
	private function processAggregatorOp($op,$field,$column,&$fields){
		$aggregator = $op;
		if($op=='sum-dis'){
			$aux='distinct';
			$aggregator='sum';
		}
		array_push($fields,"${aggregator}(${aux} ${field}) as ${column}");
	}
	
	private function processFilters(&$params,&$fields,&$tables,&$where,
				&$groupby,&$having,&$limit){
		
		$filters = $params['filters'];
		foreach($filters as $filter => $value){
			$field_array = self::splitField($filter);
			$op = $field_array[2];
			
			if(!self::valueInArray($tables,$field_array[0])){
				$table = $field_array[0];
				$id = $table."_id";
				array_push($tables,"${table} using (${id})");
			}
					
			$this->processFilterOp($op, $field_array[0].'.'.$field_array[1],
									$value,$where, $limit);
			
		}
	}
	
	/**
	 * 
	 * Processes found filter operations in the scenario config.
	 * @param str $op
	 * @param str $field
	 * @param mixed $value
	 * @param array $where
	 * @param str $limit
	 * @throws Exception
	 */
	private function processFilterOp(&$op,$field,&$value,&$where,&$limit){
		$isarray = is_array($value);
		
		if($op == 'eq'){
			$array = $isarray ? $value : array($value);
			
			$aux = array_map(function($value)use($field){
				return "$field = '${value}'";
			},$array);
			
			array_push($where, '('.implode(" OR ",$aux).')');
			
		}else if($op == 'ne'){
			$array = $isarray ? $value : array($value);
			
			$aux = array_map(function($value)use($field){
				return "$field != '${value}'";
			},$array);
			
			array_push($where, '('.implode(" OR ",$aux).')');
		}else if($op == 'ge'){
			$array = $isarray ? $value : array($value);
			
			$aux = array_map(function($value)use($field){
				return "$field >= '${value}'";
			},$array);
			
			array_push($where, '('.implode(" OR ",$aux).')');
		}else if($op == 'le'){
			$array = $isarray ? $value : array($value);
			
			$aux = array_map(function($value)use($field){
				return "$field <= '${value}'";
			},$array);
			
			array_push($where, '('.implode(" OR ",$aux).')');
		}else if($op == 'lt'){
			$array = $isarray ? $value : array($value);
			
			$aux = array_map(function($value)use($field){
				return "$field < '${value}'";
			},$array);
			
			array_push($where, '('.implode(" OR ",$aux).')');
		}else if($op == 'gt'){
			$array = $isarray ? $value : array($value);
			
			$aux = array_map(function($value)use($field){
				return "$field > '${value}'";
			},$array);
			
			array_push($where, '('.implode(" OR ",$aux).')');
		}else{
			throw new Exception("Invalid Filter Operation");
		}
		
	}
	
	private function tablefyResult(&$result,$columns,$rows,$cube){
		
		$records = array_merge($columns,$rows);
		$measures = array_values(preg_grep("/^measure/",$records));
		
		$columns = array_values(preg_grep("/^measure/",$columns,PREG_GREP_INVERT));
		$rows = array_values(preg_grep("/^measure/",$rows,PREG_GREP_INVERT));
		
		//$columns = array_values(preg_grep("/\.all$/",$columns,PREG_GREP_INVERT));
		//$rows = array_values(preg_grep("/\.all$/",$rows,PREG_GREP_INVERT));
		
		foreach(range(0,count($measures)-1) as $i){
			$measures[$i] = $this->translateMeasure($measures[$i],$cube);
		}
		
		
		$data = Pivot::factory($result)
			->pivotOn($rows)
			->addColumn($columns,$measures)
			->fetch();
			System_Daemon::debug(print_r(
				array($data,$result,$columns,$rows,$measures),true));
				
		return $data;
	}
	
	
	private function translateMeasure(&$field,$cube){
		$arr = self::splitField($field);
		$measure_id = $arr[1];
		
		$measure = array_shift(jsonPath($this->rules, 
			"$.cubes[?(@['table']=='${cube}')]".
			".measures[?(@[id]=='${measure_id}')]",
			array("resultType" => "VALUE")));


		
		return $measure['column'];
	}
	
}
?>