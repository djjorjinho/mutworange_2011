<?php 
require_once("lib/DB.php");
require_once("lib/System/Daemon.php");
require_once("lib/Cache.php");
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
	private $cache;
	
	private $efficacy_table = "fact_efficacy";
	private $efficiency_table = "fact_efficiency";
	private $efficiency_ods = "ods_efficiency";
	
	function __construct($options){
		$this->config = $options;
		$this->cache = new ObjectCache();
		$this->db = DB::getInstance();
	}
	
	/**
	 * 
	 * Performs ETL on efficiency ods table and loads info to data warehouse
	 */
	function processEfficiency(){
		$db = $this->db;
		$efficiency_ods = $this->efficiency_ods;
		$efficiency_table = $this->efficiency_table;
		
		// get academic date
		list($year,$semester) = self::getAcademicDate(); 
		
		// create efficiency merge table
		list($mrg_table,$prev_table) = 
							self::createEfficiencyFactTable($year,$semester);
		$db->disableKeys($mrg_table);
		
		// gather info into fact table
		$last_id = self::getLastOSDId($efficiency_ods);
		
		$query = "select * from $efficiency_ods".
				" where ${efficiency_ods}_id > ${last_id}";
		
		$res = $db->getMany($query);
		
		// trasnformation rules
		$rules = $this->etl->getEfficiencyTransformationRules();
		
		// transform,load and calculate statistical values by context
		$context = array();
		foreach($res as $row){
			$NRow = $this->etl->transformODSRow($rules,$row,$context);
			$db->insert($NRow,$efficiency_table);
		}
		
		// data mine - needs previous table for comparisons
		
		
		// finished -  merge tables
		$db->enableKeys($mrg_table);
		self::mergeTables($efficiency_table);
		self::setLastODSId($efficiency_ods,$last_id);
	}
	
	/**
	 * 
	 * Based on current date and meta-information,retrieves
	 * @return array $list - year and semester
	 */
	function getAcademicDate($year,$month){
		$db = $this->db;
		$res = $this->cache->cacheFunc("getAcademicDate:${year}:${month}",1800,
			function()use($year,$month,$db){
				$res = $db->getMany("select `range`,semester from meta_semester".
					" where meta_semester_id='default'");
					
				foreach($res as $R){
					if(preg_match("/$R[range]/",$month)){
						return array($year,$R['semester']);
					}
				}	
				return null;
			});
		return $res;
	}
	
	/**
	 * 
	 * return year / month / day
	 * @param unknown_type $date
	 * @return array var
	 */
	function getDate(DateTime $dt){
		$year = $dt->format('Y');
		$month = $dt->format('n');
		$day = $dt->format('d');
		
		return array($year,$month,$day);
	}
	
	/**
	 * 
	 * creates a fresh efficiency merge fact table
	 * returns table name and previous semester table name
	 * @param int $year - academic year
	 * @param int $semester - academic semester
	 * @return array var - new table name, previously created table
	 */
	function createEfficiencyFactTable($year,$semester){
		$db = $this->db;
		$efficiency_table = $this->efficiency_table;
		
		// create a merging table based on template
        $mrg_table = $efficiency_table."_${year}_${semester}s";
        $db->execute("drop table if exists $mrg_table");
        
        $mrg_tables = $db->getMergedTables($this->efficiency_table);
        
		$db->execute("create table $mrg_table like $efficiency_table");
        $db->execute("alter table $mrg_table engine=MyISAM");

		return array($mrg_table,end($mrg_tables));
	}
	
	/**
	 * 
	 * Takes the main fact table name, determines the merging tables by their 
	 * suffixes and performs a union to the main table
	 * @param unknown_type $table
	 * @return unknown_type var
	 */
	function mergeTables($table){
		$db = $ths->db;
		// uniting merging tables
        $mrg_tables = $db->getMergedTables($table);
        $db->execute("alter table $table UNION=(".
        									implode(",",$mrg_tables).")");
	}
	
	/**
	 * 
	 * Sets last processed ODS row id in the meta table
	 * @param str $table
	 * @param int $ods_id
	 */
	function setLastODSId($table,$ods_id){
		$db = $this->db;
		$db->execute("update meta_last_ods_table set last_id='${ods_id}'".
			" where meta_last_ods_table_id = '${table}'");
		
	}
	
	/**
	 * 
	 * Retrieves from the meta table the last ODS id to be processed
	 * Creates new meta data if not found and returns zero
	 * @param str $table
	 * @return int var
	 */
	function getLastOSDId($table){
		$db = $this->db;
		try{
			$db->insert(
					array(meta_last_ods_table_id => $table), 
					"meta_last_ods_table");
		}catch(Exception $e){}
		
		$dbkey = "meta_last_ods_table.ods_efficiency";
		$R = $db->getObj($dbkey);
		
		return $R['last_id'];
	}
	
	/**
	 * 
	 * Transformation rules consist of an associative array
	 * where the keys are the target table fields and the values are closure
	 * functions that prepare the value to be saved in each field, based on the
	 * data to be transformed.
	 * @return array $map - transform rules
	 */
	function getEfficiencyTransformationRules(){
		$db = $this->db;
		$obj = $this;
		$map = array(
			example => function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){},
			
			dim_date_id => function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
				$NRow[$field] = $obj->getDimId(
						array(year => $row['year'],
							semester => $row['semester'])
						,"dim_date");
			},
			
			dim_phase_id => function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
				$NRow[$field] = $obj->getDimId(
						array(dim_phase_id => $row['dim_phase_id'])
						,"dim_phase",array(description=>$row['dim_phase_id']));
			},
			
			dim_institution_id => function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
				
			},
			
			dim_institution_host_id => 
					function(&$field,&$row,&$NRow)use($db,$obj){
						
			},
			
			dim_bobility_id => function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
				
			},
			
			dim_gender_id => function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
				
			},
		
		);
		return $map;
	}
	
	/**
	 * 
	 * Traverses the rules array and executes the closure functions 
	 * to fill up the new row to be inserted in the target fact table
	 * @param array $rules - transformation rules
	 * @param array $row - ods table row
	 * @param array $context - hold context info for statistical values
	 * @return array $NRow - the new row for the fact table
	 */
	function transformODSRow(&$rules,&$row,$context){
		$NRow = array();
		
		foreach($rules as $field => $trans){
			if(is_callable($trans)){
				$trans($field,$row,$NRow,$context);
			}else{
				$NRow[$field] = $row[$trans];
			}
		}
		
		return $NRow;
	}
	
	/**
	 * 
	 * Searches for an inserted dimensional row and returns the id.
	 * Creates a new row if not found.
	 * Supports result caching.
	 * @param array $obj - the search/insert parameters
	 * @param str $table - the dimension table
	 * @param array $add - extra data to be inserted
	 * @return str $id - the dimensional row id
	 */
	function getDimId($obj,$table,$add=array()){
		$key = "getDimId:${table}:".md5(json_encode($obj));
		$result = $this->cache->get($key);
		if(isset($result)) return $result;
		
		$db = $this->db;
		$select='*';
		$where = array();
		foreach($obj as $field => $val){
			array_push($where,"$field = '$val'");
		}
		
		$sql = "SELECT ${table}_id from ${table} WHERE ".
									implode(" AND ", $where);

		$R = $db->getOne($sql);
		
		$id;
		if(isset($R)){
			$id =  $R["${table}_id"];
		}else{
			$obj = array_merge($obj,$add);
			$id = $db->insert($obj,$table);
		}
		
		$this->cache->store($key,$id,1800);

		return $id;
	}
	
}
?>