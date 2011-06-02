<?php
require_once("lib/DB.php");
require_once("lib/System/Daemon.php");
require_once("lib/Cache.php");
require_once("lib/jsonpath.php");
require_once("lib/Pivot.php");
require_once('lib/JSONConfig.php');
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
		$json = JSONConfig::load(dirname(__FILE__).'/../config/',
					$this->config['rulefile']);

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
		$distinct="";
		if($fields[0]=='distinct'){
			$distinct = array_shift($fields);
		}
				
		$sql .= "SELECT ${distinct} ".implode(',',$fields);
		
		#print("tables\n");
		#print_r($tables);
		$join = array_values(preg_grep("/\ as\ /",$tables,PREG_GREP_INVERT));
		$other = array_values(preg_grep("/\ as\ /",$tables));
		
		$sql .= " FROM ".implode(' JOIN ',$join);
		if(!empty($other)) $sql .= ',';
		$sql .= implode(',',$other);
		
		
		#print("where\n");
		#print_r($where);
		
		if(!empty($where)) $sql .= " WHERE ".implode(' AND ',$where);
		
		#print("groupby\n");
		#print_r($groupby);
		
		if(!empty($groupby)) $sql .= " GROUP BY ".implode(',',$groupby);
		
		#print $sql."\n";
		System_Daemon::debug("QUERY: ".$sql);
		
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
		$hasMeasure=false;
		$cube = $params['cube'];
		$columns = array_merge($params['columns'],$params['rows']);
		
		foreach($columns as $field){
			$field_array = self::splitField($field);
			
			if($field_array[0] == 'measure'){
				$hasMeasure=true;
				$this->processMeasure($cube,$field_array[1],$fields,
						$tables,$where,$limit);				
			}else{
				$table = $field_array[0];
				$id = $table."_id";
				

				if($table!='dim_institution_host'){
					$val = "${table} using (${id})";
					if(!in_array($val,$tables)) array_push($tables,$val);
				}else{
					$val = "dim_institution as ${table}";
					$wh = "(${cube}.dim_institution_host_id = ".
							"${table}.dim_institution_id)";
					if(!in_array($val,$tables)) array_push($tables,$val);
					if(!in_array($wh,$where)) array_push($where,$wh);
				}	
				
				if($field_array[1]!='all'){
					array_push($groupby,$field);
					
					$dim = array_shift(jsonPath($this->rules,
					"$.dimensions[?(@['table']=='${table}')]".
					".levels[?(@['column']=='${field_array[1]}')]"
					,array("resultType" => "VALUE")));
					
					array_push($fields,$field." as `$dim[name]`");
					
				}else{
					$dim = array_shift(jsonPath($this->rules,
					"$.dimensions[?(@['table']=='${table}')]"
					,array("resultType" => "VALUE")));
					
					$aux = preg_match("/concat/", $dim['desc']) ? 
						$dim['desc'] : 
						"$dim[table].$dim[desc]";
					
					array_push($fields,"$aux as `$dim[name]`");
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
		
		//array_push($fields,"'measure.${measure_id}' as `$measure[name]`");
		
		$this->processAggregatorOp($aggregator,"${cube}.${column}",$column,
													$fields,$measure[name]);
	}
	
	private function processAggregatorOp($op,$field,$column,&$fields,$measure_name){
		$aggregator = $op;
		if($op=='sum-dis'){
			$aux='distinct';
			$aggregator='sum';
		}elseif($op=='distinct'){
			array_unshift($fields, "distinct");
			$aggregator='';
			$aux='';
		}elseif($op=='avg'){
			$aggregator='round';
			$aux='avg';
		}
		
		array_push($fields,"${aggregator}(${aux}(${field})) as `${measure_name}`");
	}
	
	private function processFilters(&$params,&$fields,&$tables,&$where,
				&$groupby,&$having,&$limit){
		
		$filters = $params['filters'];
		foreach($filters as $filter => $value){
			if(preg_match("/\_hash/",$filter)) 
				continue;
				
			$field_array = $this->splitField($filter);
			$op = $field_array[2];
			
			if(!$this->valueInArray($tables,$field_array[0])){
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
		
		$columns = array_values(preg_grep("/^measure/",$columns,
				PREG_GREP_INVERT));
		$rows = array_values(preg_grep("/^measure/",$rows,
				PREG_GREP_INVERT));
		
		//$columns = array_values(preg_grep("/\.all$/",$columns,PREG_GREP_INVERT));
		//$rows = array_values(preg_grep("/\.all$/",$rows,PREG_GREP_INVERT));
		
		foreach(range(0,count($measures)-1) as $i){
			$measures[$i] = $this->translateMeasure($measures[$i],$cube);
		}
		
		foreach(range(0,count($rows)-1) as $i){
			$rows[$i] = $this->translateDimension($rows[$i],$cube);
		}
		
		foreach(range(0,count($columns)-1) as $i){
			$columns[$i] = $this->translateDimension($columns[$i],$cube);
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


		
		return $measure['name'];
	}
	
	private function translateDimension(&$field,$cube){
		$arr = self::splitField($field);
		
		$expr = ($arr[1] == 'all') ? 
			"$.dimensions[?(@['table']=='$arr[0]')]" :
			"$.dimensions[?(@['table']=='$arr[0]')]".
			".levels[?(@['column']=='$arr[1]')]";
		
		$dim = array_shift(jsonPath($this->rules,$expr,
				array("resultType" => "VALUE")));
	
		
		return $dim['name'];
	}
	
}
?>