<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PrecandidateDB {
    
    public static function getUser($id) {
        $db = PlonkWebsite::getDB();
        $user = $db->retrieveOne("select * from users inner join institutions on users.institutionId = institutions.instId where userId ='" . $db->escape($id) . "'");
        return $user;
    }
    public static function insertJson($table,$values) {
        $db = PlonkWebsite::getDB();
        
        $insertId = $db->insert($table, $values);
        
        return $insertId;
    }
    
    public static function getStudentStatus($id) {
        $db = PlonkWebsite::getDB();
        
        $status = $db->retrieveOne("select statusOfErasmus from erasmusStudent where studentId ='" . $db->escape($id) . "'");
        
        return $status;
    }
    public static function getJson($id) {
        $db = PlonkWebsite::getDB();
        
        $string = $db->retrieveOne("select content from forms where studentId ='" . $db->escape($id) . "'");
        
        return $string;
    }
    
    public static function insertErasmusStudent($table,$value) {
        $db = PlonkWebsite::getDB();
        
        $update = $db->insert($table, $value);
        
        return $update;
    }
    
    public static function insertStudentEvent($table,$values) {
        $db = PlonkWebsite::getDB();
        $update = $db->insert($table, $values);
    }
    
    public static function getHomeInstitution() {
        $db = PlonkWebsite::getDB();
        
        $institution = $db->retrieveOne('select * from institutions where instName = "' . INSTITUTE . '"');
        
        return $institution;                
    }
    
    public static function getEducationPerInstituteId($instId,$educationId) {
        $db = PlonkWebsite::getDB();
        $id = $db->retrieveOne('select * from educationperinstitute 
                                where institutionId ='  . $db->escape($instId) . ' AND studyId =' . $db->escape($educationId));
        return $id;
    }
    
    public static function getEducation($name) {
        $db = PlonkWebsite::getDB();
        
        $education = $db->retrieveOne('select * from education where educationName = "' . $name .'"');
        
        return $education;
    }
    
    public static function getEducations() {
        $db = PlonkWebsite::getDB();
        
        $educations = $db->retrieve('select educationName from education');
        
        return $educations;
    }
    
    public static function getCountries() {
        $db = PlonkWebsite::getDB();
        
        $countries = $db->retrieve('select Name from country');

        return $countries;
    }
    
    public static function getErasmusLevelId($name) {
        $db = PlonkWebsite::getDB();
        
        $id = $db->retrieveOne("select * from erasmuslevel where levelName = '" . $name . "'");
        
        return $id;       
    }
    
    public static function getUploadedWhat($id) {
        $db = PlonkWebsite::getDB();
        
        $uploadedWhat = $db->retrieveOne("select uploadedWhat from erasmusstudent where studentId =" . $db->escape($id));
        
        return $uploadedWhat;
    }
    
   

}

?>
