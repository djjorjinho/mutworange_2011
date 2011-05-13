<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ResidenceDB {
    public static function getResidences() {
        $db = PlonkWebsite::getDB();
        
        $residences = $db->retrieve("select * from residence");
        
        return $residences;
    }
    
    public static function getResidencesByCountry($country) {
        $db = PlonkWebsite:: getDB();
        $residences = $db->retrieve("select * from residence 
            inner join country on residence.country = country.Code 
            where country.Code = '" . $db->escape($country) . "'");
        return $residences;
        
    }
    
    public static function getCountries() {
        $db = PlonkWebsite::getDB();
        
        $items = $db->retrieve("select * from country");
        
        return $items;        
    }
    
    public static function getResidenceById($id) {
        $db = PlonkWebsite::getDB();
        
        $residence = $db->retrieveOne("select * from residence where residenceId =" . $db->escape($id));
        
        return $residence;
    }
    
    public static function getOwnerByResidence($id) {
        $db = PlonkWebsite::getDB();
        
        $owner = $db->retrieveOne("select o.familyName,o.firstName,o.email,o.tel from owner as o
            inner join residence on o.email = residence.ownerId
            where residence.residenceId = '" . $db->escape($id) . "'");
        
        return $owner;                
    }
    
    public static function getErasmusLevel($id) {
        $db = PlonkWebsite::getDB();
        
        $erasmuslevel = $db->retrieveOne("select levelName from erasmuslevel 
            inner join erasmusstudent on erasmuslevel.levelName = erasmusstudent.statusOfErasmus
            where users_email = '" . $db->escape($id) . "'");
        
        return $erasmuslevel;
    }
    
}
?>
