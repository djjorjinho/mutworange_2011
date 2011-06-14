<?php
require_once('JSONConfig.php');
/**
 * 
 * DB abstraction class 
 * @author daniel
 *
 */
class ODB{
	
	private static $db;
	private $conn;
	private $config;
	
	/**
	 * Table info cache
	 * @var array
	 */
	public $tinfo_cache=array();
	
	/**
	 * The constructor reads a json configuration file and stores it in the
	 * instance var $config
	 */
	private function DB($options=null){
		
		if(isset($options)){
			$this->config = $options;
		}else{
			$this->config =
				JSONConfig::load(dirname(__FILE__),'dbconfig');
		}
		
	}
	
	/**
	 * Singleton function
	 * @return DB the DB class object
	 */
	public static function getInstance($options=null){
		if(!isset(self::$db)){
			self::$db = new ODB($options);
			self::$db->connect();
		}
		return self::$db;
	}
	
	/**
	 * Returns instance db access configuration
	 * @return array the assoc. array containing the db access config
	 */
	function getConfig(){
		return $this->config;
	}
	
	/**
	 * Connects to the database according to the config data in
	 * the assoc. array
	 */
	function connect(){
		if(isset($this->conn)) return true;
		
		$config = $this->getConfig();
		$this->conn = mysql_connect($config['host'].":".$config['port'],
			$config['user'],
			$config['password']) or die(mysql_error());
		$ok = mysql_select_db($config['schema'],$this->conn);

		return $ok;
	}
	/**
	 * Returns table information. No duplicate field names please.
	 * @param string $table
	 * @return array|null an assoc. array with the table info or null value
	 */
	function tableInfo($table){
		
		$tinfo = $this->tinfo_cache[$table];
		if(!empty($tinfo)) return $tinfo;
		
		$tinfo=array();
		$result = mysql_query("SHOW COLUMNS FROM $table");
		if (!$result) {
		    throw new Exception('Could not run query: ' .
					mysql_error()); 
		}
		if (mysql_num_rows($result) > 0) {
		    while($row = mysql_fetch_assoc($result)){
			$tinfo[$row['Field']] = $row;
		    }
		    $this->tinfo_cache[$table] = $tinfo;
		    return $tinfo;
		}
		return null;
	}
	
	/**
	 * 
	 * Used for quick queries with no need to be tracked
	 * @param str sql query string
	 */
	function pureQuery($query){
		return mysql_query($query);
	}
	
	/**
	 * 
	 * Main query method. Logs and tracks queries for profiler. 
	 * @param str sql query string
	 */
	function query($query){
		$result = mysql_query($query);
		if (!$result) {
		    throw new Exception("Could not run query:\n".$query."\n".
					mysql_error()); 
		}
		return $result;
	}
	
	/**
	 * 
	 * Calls query() and imediately returns the resultset
	 * @param str sql query string
	 */
	function execute($query){
		return $this->query($query);
	}
	
	/**
	 * 
	 * Executes a query and returns the first result
	 * @param str sql query
	 * @return array associative array with the db row values
	 */
	function getOne($query){
		$result = $this->query($query);
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_assoc($result);
			return $row;
		}
		return null;
	}
	
	/**
	 * Executes a query and returns a resultset array.
	 * @param string $query - sql query
	 * @return array|null result set or null value
	 */
	function getMany($query){
		$rset=array();
		$result = $this->query($query);
		if (mysql_num_rows($result) > 0) {
		    while($row = mysql_fetch_assoc($result)){
				array_push($rset,$row);
		    }
		    return $rset;
		}
		return null;
	}
	
	/**
	 * Takes an assoc. array, prepares an insert statement and executes it.
	 * @param array assoc array with the db fields and values
	 */
	function insert($obj,$table){
		if(!is_array($obj)) throw new Exception("Not a valid object!");
		
		$tinfo = $this->tableInfo($table);
		
		$nobj=array();
		$fields="";
		$values="";
		$comma=false;
		
		foreach(array_keys($obj) as $key){
			if(key_exists($key,$tinfo)){
				if($comma){$fields.=","; $values.=",";}
				$fields .= $key;
				$val = $obj[$key];
				if(preg_match("/^<\?(.*)\?>/",$val,$matches)){
					$values .= mysql_real_escape_string(
								$matches[1]);
				}else{
					$values .= "'".
					mysql_real_escape_string($val)
					."'";
				}
				
				$comma=true;
			}
			
		}
		
		$sql = "INSERT INTO ".$table." (".$fields.")VALUES(".$values.");";
		//error_log($sql);
		$result = $this->query($sql);
		
		return key_exists("${table}_id",$obj) ? $obj["${table}_id"] : 
												mysql_insert_id();
	}
	
	/**
	 * 
	 * Takes an array with the table, id|code and 
	 * the other values to be updated
	 * @param array associative array containing the the data to be updated
	 * @return mixed query result
	 */
	function update($obj,$where){
		if(!is_array($obj)) throw new Exception("Not a valid object!");

		$table = $obj['table'];

		if(empty($table) || empty($where))
			throw new Exception("Can't update! table:'${table}' where:'$where'");
		
		$tinfo = $this->tableInfo($table);
		
		$update;
		$comma=false;
		foreach(array_keys($obj) as $key){
			if(key_exists($key,$tinfo)){
				if($comma){$update .= ",";}
				$val = $obj[$key];
				
				if(preg_match("/^<\?(.*)\?>/",$val,$matches)){
					$update .=
						mysql_real_escape_string(
							$matches[1]);
				}else{
					$update .= $key." = '".
						mysql_real_escape_string($val)
							."'";
				}
				
				$comma=true;
			}
		}
		
		$sql = "UPDATE $table SET $update ";
		$wh = " WHERE ".$where;
		
		$result = $this->query($sql.$wh);
		
		return $result;
	}
	
	function updateMany($obj,$table,$where="1=1"){
		if(!is_array($obj)) throw new Exception("Not a valid object!");
		
		$tinfo = $this->tableInfo($table);
		
		$update;
		$comma=false;
		foreach(array_keys($obj) as $key){
			if(key_exists($key,$tinfo)){
				if($comma){$update .= ",";}
				$val = $obj[$key];
				
				if(preg_match("/^<\?(.*)\?>/",$val,$matches)){
					$update .=
						mysql_real_escape_string(
							$matches[1]);
				}else{
					$update .= $key." = '".
						mysql_real_escape_string($val)
							."'";
				}
				
				$comma=true;
			}
		}
		
		$sql = "UPDATE $table SET $update WHERE ${where}";
		//print $sql."\n";
		$result = $this->query($sql);
		
		return $result;
	}
	
	/**
	 * Deletes a row object from the database table
	 * @param table
	 * @param field
	 * @param id
	 * @return int success confirmation
	 */
	function delete($table,$field,$id){

		if(empty($table) || empty($id) || empty($id))
			throw new Exception("Can't update! check id or table");
		
		$sql = "DELETE FROM ${table} WHERE ${field} = '${id}'";
		
		$result = $this->query($sql);

		return $result;
	}
	
	/**
	 * Fetches a random row from a table
	 * @param string table name
	 * @param string where statement (optional)
	 * @return array the table row assoc. array
	 */
	function getRandom($table,$where="1=1"){

		if(empty($table))
			throw new Exception("no table to fetch random row!");
		
		return $this->getOne("select * from $table ".
					"where ${where} order by rand() limit 1");
	}

	function flushTableInfoCache(){
		$this->tinfo_cache = array();
	}
	
	function enableKeys($table){
		if(!isset($table)) throw new Exception("Not a valid table!");
		$this->execute("alter table $table enable keys");
	}
	
	function disableKeys($table){
		if(!isset($table)) throw new Exception("Not a valid table!");
		$this->execute("alter table $table disable keys");
	}
}

?>