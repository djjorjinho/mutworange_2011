<?php

class InfoxDB {
    
    public static function getUniversity() {
        $db = PlonkWebsite::getDB();
        
        $rs = $db->retrieve("SELECT instId, instName FROM institutions");
        if (!empty($rs)) {
          return $rs;
        } else
          return 0;
    }
    
    public static function getURL($id) {
    	$db = PlonkWebsite::getDB();
    	$rs = $db->retrieveOne("SELECT url FROM institutions WHERE instEmail = '".$id."'");
      if (!empty($rs)) {
        return $rs['url'];
      } else
        return 0;
    }

    public function getAuthUsername() {
    	$db = PlonkWebsite::getDB();
    	$rs = $db->retrieveOne("SELECT email FROM users WHERE userLevel = '8'");
      if (!empty($rs)) {
        return $rs['email'];
      } else
        return 0;
    }

    public function getAuthPwd() {
    	$db = PlonkWebsite::getDB();
    	$rs = $db->retrieveOne("SELECT password FROM users WHERE userLevel = '8'");
      if (!empty($rs)) {
        return $rs['password'];
      } else
        return 0;
    }
    public function getAllTables() {
    	$db = PlonkWebsite::getDB();
      $rs = $db->retrieve("SHOW TABLES");
      if (!empty($rs))
        return $rs;
      else
        return 0;
    }
    public function getAllDataFromTable($tbl) {
      $db = PlonkWebsite::getDB();
      $rs = $db->retrieve("SELECT * FROM ".$tbl);
	  $back = array();
      if (!empty($rs)) {
	    for ($i = 0; $i < count($rs); $i++) {
		  $c = count($rs[$i]);
		  $a = array_keys($rs[$i]);
		  for ($z = 0; $z < count($a); $z++) {
		    $back[$i][$z] = $rs[$i][$a[$z]];
		  }
		}
		
		return $back;
      } else
        return 0;
    }
	public function getRowFromDB($tbl, $id) {
      $db = PlonkWebsite::getDB();
      $rs = $db->retrieve("SELECT * FROM " . $tbl);
	  $a = array_keys($rs[0]);

      $rs = $db->retrieve("SELECT * FROM " . $tbl . " WHERE " . $a[0] . " = '" . $id . "'");
      if (!empty($rs)) {
        return $rs[0];
      } else
        return 0;
	}
}

?>
