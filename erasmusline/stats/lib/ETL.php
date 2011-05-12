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
		
		// get academic date
		list($year,$semester) = self::getAcademicDate(); 
		
		// create efficiency merge table
		list($mrg_table,$prev_table) = 
							self::createEfficiencyFactTable($year,$semester);
		$db->disableKeys($mrg_table);
		
		// gather info into fact table
		$last_id = self::getLastOSDId($this->efficiency_ods);
		
		
		// data mine - needs previous table for comparisons
		
		// finished -  merge tables
		$db->enableKeys($mrg_table);
		self::setLastODSId($this->efficiency_ods);
		self::mergeTables($this->efficiency_table);
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
	
	function mergeTables($table){
		$db = $ths->db;
		// uniting merging tables
        $mrg_tables = $db->getMergedTables($table);
        $db->execute("alter table $table UNION=(".
        									implode(",",$mrg_tables).")");
	}
	
	function setLastODSId($table,$ods_id){
		$db = $this->db;
		$db->execute("update meta_last_ods_table set last_id='${ods_id}'".
			" where meta_last_ods_table_id = '${table}'");
		
	}
	
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
	
	
}
?>