<?php

class RegisterDB {
    
    public static function getCountries() {
        $db = PlonkWebsite::getDB();
        
        $items = $db->retrieve("select Name,Code from country");
        
        return $items;        
    }
    
    public static function getUserById($id) {
        $db = PlonkWebsite::getDB();
        
        $user = $db->retrieveOne("select * from users where userId = " . $db->escape($id));
        
        return $user;        
    }
    public static function getMaxId($tableName,$idField) {
        $db = PlonkWebsite::getDB();
        
        $maxId = $db->retrieveOne("select MAX(" . $db->escape($idField) . ") from " . $db->escape($tableName));
        
        return $maxId;
    }
    
    public static function updateUserField($value, $where) {
        $db = PlonkWebsite::getDB();
        
        $updated = $db->update('users',$value,$where);
        
        return $updated;
    }    
    
}
?>