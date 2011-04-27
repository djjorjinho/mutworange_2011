<?php

class AboutDB {

    public static function getCountries() {
        $db = PlonkWebsite::getDB();

        $items = $db->retrieve("select Name from institutions");

        return $items;
    }
    
    public static function getNames($queryString) {
        $db = PlonkWebsite::getDB();

        $users = $db->retrieve("SELECT * FROM users WHERE FamilyName LIKE '$queryString%' LIMIT 10");

        return $users;
        
        
    }

}

?>
