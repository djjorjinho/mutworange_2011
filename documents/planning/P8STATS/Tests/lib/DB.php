<?php

class DB{
	
	var $db;
	var $conn;
	var $config;
	
	/**
	 * Table info cache
	 * @var array
	 */
	var $tinfo_cache=array();
	
	/**
	 * The constructor reads a json configuration file and stores it in the
	 * instance var $config
	 */
	private function DB(){
		$this->config =
			json_decode(file_get_contents('dbconfig.json'),true);
	}
	
	/**
	 * Singleton function
	 * @return DB the DB class object
	 */
	function getInstance(){
		if(!isset($db)){
			$db = new DB();
		}
		return $db;
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
		$config = $this->getConfig();
		$this->conn = mysql_connect($config['host'].":".$config['port'],
			$config['user'],
			$config['password']) or die(mysql_error());
		$ok = mysql_select_db($config['schema'],$this->conn);
		//print mysql_error();
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
	
	function getOne($query){
		$result = mysql_query($query);
		if (!$result) {
		    throw new Exception('Could not run query: ' .
					mysql_error()); 
		}
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_assoc($result);
			return $row;
		}
		return null;
	}
	
	function getObj($dbkey){
		list($table,$id) = preg_split("/\./",$dbkey);
		
		$tinfo = $this->tableInfo($table);
		$field = array_key_exists('id',$tinfo) ? 'id' : 'code';
		$sql = "SELECT * FROM $table WHERE $field = '$id'";
		
		return $this->getOne($sql);
	}
	
	/**
	 * Executes a query and returns a resultset array.
	 * @param string $query - sql query
	 * @return array|null result set or null value
	 */
	function getMany($query){
		$rset=array();
		$result = mysql_query($query);
		if (!$result) {
		    throw new Exception('Could not run query: ' .
					mysql_error()); 
		}
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
		$fields;
		$values;
		$comma=false;
		
		foreach(array_keys($obj) as $key){
			if(key_exists($key,$tinfo)){
				if($comma){$fields.=","; $values.=",";}
				$fields .= $key;
				$val = $obj[$key];
				if(preg_match("/^<\?(.*)\?>/",$val,$matches)){
					$values .= mysql_real_escape_string($matches[1]);
				}else{
					$values .= "'".
					mysql_real_escape_string($val)
					."'";
				}
				
				$comma=true;
			}
			
		}
		
		$sql = "INSERT INTO ".$table." (".$fields.")VALUES(".$values.");";

		$result = mysql_query($sql);
		
		if (!$result) {
		    throw new Exception($sql ." ". 
					mysql_error()); 
		}
		return mysql_insert_id();
	}
	
	function update($obj){
		if(!is_array($obj)) throw new Exception("Not a valid object!");
		list($table,$id) = preg_split("/\./",$obj['dbkey']);
		if(empty($table)) $table = $obj['table'];
		if(empty($id)) $id = $obj['id'];
		if(empty($id)) $id = $obj['code'];
		
		if(empty($table) or empty($id))
			throw new Exception("Can't update! check id or table");
		
		$tinfo = $this->tableInfo($table);
		
		$update;
		$comma=false;
		foreach(array_keys($obj) as $key){
			if(key_exists($key,$tinfo)){
				if($comma){$update .= ",";}
				$val = $obj[$key];
				
				if(preg_match("/^<\?(.*)\?>/",$val,$matches)){
					$update .= mysql_real_escape_string($matches[1]);
				}else{
					$update .= $key." = '".mysql_real_escape_string($val)."'";
				}
				
				$comma=true;
			}
		}
		
		$tinfo = $this->tableInfo($table);
		$field = array_key_exists('id',$tinfo) ? 'id' : 'code';
		
		$sql = "UPDATE $table SET $update WHERE $field = '$id'";
		
		$result = mysql_query($sql);
		
		if (!$result) {
		    throw new Exception($sql." ".mysql_error()); 
		}
		
		return $result;
	}
	
	
	function delete($obj){
		if(!is_array($obj)) throw new Exception("Not a valid object!");
		list($table,$id) = preg_split("/\./",$obj['dbkey']);
		if(empty($table)) $table = $obj['table'];
		if(empty($id)) $id = $obj['id'];
		if(empty($id)) $id = $obj['code'];

		if(empty($table) or empty($id))
			throw new Exception("Can't update! check id or table");
		
		$tinfo = $this->tableInfo($table);
		$field = array_key_exists('id',$tinfo) ? 'id' : 'code';
		$sql = "DELETE FROM $table WHERE $field = '$id'";
		
		$result = mysql_query($sql);
		
		if (!$result) {
		    throw new Exception($sql ." ". 
					mysql_error()); 
		}
		return $result;
	}
	
	function getRandom($table){
		if(empty($table))
			throw new Exception("no table to fetch random row!");
		return $this->getOne("select * from $table order by rand() limit 1");	
	}
	
}

?>