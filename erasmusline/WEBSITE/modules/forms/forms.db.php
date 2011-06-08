<?php

class FormsDB {

    public static function checkRecordExists($key, $value, $table) {
        $SQL = "SELECT * FROM " . $table . " WHERE " . $key . " = '" . $value . "'";
        
        $db = PlonkWebsite::getDB();
        
        $values = $db->retrieveOne($SQL);
        
        return $values;
    }

    public static function updateBelgium($table, $values, $where) {
        $db = PlonkWebsite::getDB();
        
        $values = $db->update($table, $values, $where);
        
    }

    public static function insertBelgium($table, $values) {
        $db = PlonkWebsite::getDB();
        
        $db->insert($table, $values);
    }

}

?>