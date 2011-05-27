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
	
	private $dim_tables = array('dim_gender','dim_lodging','dim_mobility',
                            'dim_institution','dim_date','dim_phase',
                            'dim_study');
	
	
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
		
		// hot cache
		$this->checkHotCache();
		
		// get academic date
		$dt = new DateTime();
		list($year,$month) = $this->getDate($dt);
		list($year,$semester) = self::getAcademicDate($year,$month);
		
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
		$rules = self::getEfficiencyTransformationRules();
		
		// transform,load and calculate statistical values by context
		$context = array(count=>0,max_rsp=>0,min_rsp=>0,
						total_rsp=>0,lodg_avail=>0);
		foreach($res as $row){
			$context['count']++;
			$NRow = self::transformODSRow($rules,$row,$context);
			$db->insert($NRow,$mrg_table);
			$last_id = $row["${efficiency_ods}_id"];
		}
		
		// data mine - needs previous table for comparisons
		if(isset($prev_table)){
			$prev_table_row = 
					$db->getOne("select * from ${prev_table} limit 1");
			
			if(isset($prev_table_row))
				self::dataMineEfficiency($prev_table_row,$context);
		}
		
		// update records
		$upd = array(
			val_participants => $context['count'],
			avg_response_days => $context['avg_rsp'],
			max_response_days => $context['max_rsp'],
			min_response_days => $context['min_rsp'],
			perc_students => $context['perc_students'],
			lodging_available => $context['lodg_avail'],
			perc_lodging => $context['perc_lodging'],
			prev_participants => $context['prev_participants'],
			prev_avg_response_days => $context['prev_avg_rsp'],
			prev_max_response_days => $context['prev_max_rsp'],
			prev_min_response_days => $context['prev_min_rsp'],
			prev_lodging => $context['prev_lodging']
		);
		
		$db->updateMany($upd,$mrg_table);
		
		// finished -  merge tables
		$db->enableKeys($mrg_table);
		self::mergeTables($efficiency_table);
		self::setLastODSId($efficiency_ods,$last_id);
		self::loadHotCache();
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
		$db = $this->db;
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
			
			dim_institution_id => 
					function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
						$NRow[$field] = $obj->getDimId(
						array(institution_code => $row['institution_code'],
							country_code=>$row['country_code'])
						,"dim_institution",
						array(description=>$row['country_code']."-".
									$row['institution_code']));
			},
			
			dim_institution_host_id => 
					function(&$field,&$row,&$NRow)use($db,$obj){
						$NRow[$field] = $obj->getDimId(
						array(institution_code => $row['institution_host_code'],
							country_code=>$row['country_host_code'])
						,"dim_institution",
						array(description=>$row['country_host_code']."-".
									$row['institution_host_code']));
			},
			
			dim_mobility_id => 
					function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
						$NRow[$field] = $obj->getDimId(
						array(dim_mobility_id => $row['dim_mobility_id'])
						,"dim_mobility",
						array(description=>$row['dim_mobility_id']));
			},
			
			dim_gender_id => function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
				$NRow[$field] = $obj->getDimId(
						array(dim_gender_id => $row['dim_gender_id'])
						,"dim_gender",
						array(description=>$row['dim_gender_id']));
			},
			
			response_days => function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
				$begin = new DateTime($row['create_date']);
				$end_date = isset($row['approve_date']) ? $row['approve_date'] :
													$row['reject_date'];
				$end = new DateTime($end_date);
				
				$day_diff = $begin->diff($end);
				$days = $day_diff->d;
				
				if($ctx['count']==1) $ctx['min_rsp']=$days;
				
				if($ctx['min_rsp'] > $days) $ctx['min_rsp']=$days;
				
				if($ctx['max_rsp'] < $days) $ctx['max_rsp']=$days;
				
				$ctx['total_rsp'] += $days;
				
				$ctx['avg_rsp'] = $ctx['total_rsp'] / $ctx['count'];
				
				$NRow[$field] = $days;
			},
			
			rejected => function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
				$rejected = isset($row['approve_date']) ? 0 : 1;
				$NRow[$field] = $rejected;
			},
			
			lodging_available => 
				function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
					if($row['lodging_available']==1) $ctx['lodg_avail'] += 1;
			},
			
			student_lodging => 
				function(&$field,&$row,&$NRow,&$ctx)use($db,$obj){
					$NRow[$field] = $row['lodging_available'];
				}
		
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
	function transformODSRow(&$rules,&$row,&$context){
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
	
	/**
	 * 
	 * Takes the values from the previous merged table, 
	 * compares with current values and saves stats data in context array. 
	 * @param str $prev_row - analytical data from previous etl process
	 * @param array $ctx - context array that holds all analytical data
	 */
	function dataMineEfficiency($prev_row,&$ctx){
		$ctx['prev_avg_rsp'] = $prev_row['avg_response_days'];
		$ctx['prev_max_rsp'] = $prev_row['max_response_days'];
		$ctx['prev_min_rsp'] = $prev_row['min_response_days'];
		$ctx['prev_participants'] = $prev_row['val_participants'];
		$ctx['prev_lodging'] =  $prev_row['lodging_available'];
		
		// growth percentage
		$ctx['perc_students'] = 
				round((($ctx['count'] / $ctx['prev_participants'])-1) * 100);
		
		$ctx['perc_lodging'] = 
				round((($ctx['lodg_avail'] / $ctx['prev_lodging'])-1) * 100);
		
	}
	
	function checkHotCache(){
		try{
		$db = $this->db;
		
		$C = $db->getOne("SELECT @@global.hot_cache.key_buffer_size".
							" as hot_cache");
		
		if($C['hot_cache']==0){
			$db->execute("SET GLOBAL hot_cache.key_buffer_size=402653184;");
		}
		}catch(Exception $e){}
		
	}
	
	function loadHotCache(){
		try{
		$db = $this->db;
		$dtb = $this->dim_tables;
		$db->execute("CACHE INDEX ".implode(",",$dtb)." IN hot_cache");
        $db->execute("LOAD INDEX INTO CACHE ".
        				implode(",",$dtb)." IGNORE LEAVES");
        }catch(Exception $e){}
	}
	
}
?>