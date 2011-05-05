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
    	$rs = $db->retrieveOne("SELECT instInfoxUrl FROM institutions WHERE instId = '".$id."'");
      if (!empty($rs)) {
        return $rs['instInfoxUrl'];
      } else
        return 0;
    }

    public function getAuthUsername() {
    	$db = PlonkWebsite::getDB();
    	$rs = $db->retrieveOne("SELECT name FROM users WHERE iduserlevel = '8'");
      if (!empty($rs)) {
        return $rs['name'];
      } else
        return 0;
    }

    public function getAuthPwd() {
    	$db = PlonkWebsite::getDB();
    	$rs = $db->retrieveOne("SELECT password FROM users WHERE iduserlevel = '8'");
      if (!empty($rs)) {
        return $rs['password'];
      } else
        return 0;
    }
}

?>
