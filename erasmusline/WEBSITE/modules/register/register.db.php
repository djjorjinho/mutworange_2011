<?php

class RegisterDB {
    
    public static function getCountries() {
        $db = PlonkWebsite::getDB();
        
        $items = $db->retrieve("select Name,Code from country");
        
        return $items;        
    }
    
    public static function getUserById($id) {
        $db = PlonkWebsite::getDB();
        
        $user = $db->retrieveOne("select * from users where userId = '" . $db->escape($id)."'");
        
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
    
    public static function insertUser($table, $values) {
        $db = PlonkWebsite::getDB();
        
        $insertId = $db->insert($table, $values);
    }
    
    public static function getInstituteId($name) {
        $db = PlonkWebsite::getDB();
        
        $id = $db->retrieveOne("select instEmail from institutions where instName = '" . $db->escape($name)."'");
        
        return $id;
    }
    
    public static function userExists($email) {
        $db = PlonkWebsite::getDB();
        
        $user = $db->retrieveOne("select * from users where email = '".$db->escape($email)."'");
        
        return $user;
    }
    
}
?>