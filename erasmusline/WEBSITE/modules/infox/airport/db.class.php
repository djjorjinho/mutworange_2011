<?php

class DB {
  protected $connect;
  protected $tbl;
  protected $db;
  protected $id;

  function DB() {
    include('../../../core/includes/config.php');
    $this->connect = mysql_connect(DB_HOST, DB_USER, DB_PASS);
    $this->db = DB_NAME;
    $this->id = 0;
    if ($this->connect)
      @mysql_select_db(DB_NAME, $this->connect);
  }

  public function checkTable($tbl) {
    $SQL = "SHOW TABLES";
    $rs = mysql_query($SQL);
    while ($row = mysql_fetch_row($rs))
      if ($row[0] == $tbl) {
        $this->tbl = $tbl;
        return true;
      }
    
    return false;
  }
  
  public function checkUserEntry($mail) {
    $SQL = "SELECT * FROM users WHERE email = '".$mail."'";
	$rs = mysql_query($SQL);
	
	$row = mysql_fetch_assoc($rs);
	if (empty($row))
	  return true;
	else
	  return false;
  }
  
  function insertData($key, $value) {
    $SQL = "INSERT INTO ".$this->tbl." (".$key.") VALUES (".$value.")";
    mysql_query($SQL);
    $this->id = mysql_insert_id();
  }
  
  function updateUser($key, $value, $mail) {
    $array = explode(",", $key);
    $array2 = explode(",", $value);
	for ($i = 0; $i < count($array); $i++) {
	  $c = count($array2[$i]);
	  $array2[$i] = substr($array2[$i],1,($c - 2));
	  $SQL = "UPDATE users SET ".$array[$i]." = '".$array2[$i]."' WHERE email = '".$mail."'";
	  mysql_query($SQL);
	}
	
	return true;
  }
  
  function checkInsert() {
    if ($this->id != 0)
      return true;
    else
      return false;
  }
  
  function checkUserExist($mail) {
    $SQL = "SELECT * FROM users WHERE email = '".$mail."'";
	$rs = mysql_query($SQL);
	$row = mysql_fetch_assoc($rs);
	
	if (empty($row))
	  return null;
	else
	  return $mail;
  }

}

?>