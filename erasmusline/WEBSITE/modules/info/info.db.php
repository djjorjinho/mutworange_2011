<?php

class InfoDB {
    
    public static function getPartners() {
        $db = PlonkWebsite::getDB();
        
        $partners = $db->retrieve("select * from institutions");
        
        return $partners;
    }
    
    public static function getInstitute($email) {
        $db = PlonkWebsite::getDB();
        
        $institute = $db->retrieveOne("select * from institutions 
            where instEmail = '" . $email . "'");
        return $institute;
                
    }
}