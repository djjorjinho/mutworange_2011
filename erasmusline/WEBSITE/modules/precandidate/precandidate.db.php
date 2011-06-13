<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PrecandidateDB {
    
    public static function getUser($id) {
        $db = PlonkWebsite::getDB();
        $user = $db->retrieveOne("select * from users inner join institutions on users.institutionId = institutions.instEmail where email ='" . $db->escape($id) . "'");
       
        return $user;
    }
    public static function insertJson($table,$values) {
        $db = PlonkWebsite::getDB();
        
        $insertId = $db->insert($table, $values);
        
        return $insertId;
    }
    
    public static function getStudentStatus($id) {
        $db = PlonkWebsite::getDB();
        
        $status = $db->retrieveOne("select statusOfErasmus from erasmusStudent where users_email = '" . $db->escape($id)."'");
        
        return $status;
    }
    public static function getJson($id) {
        $db = PlonkWebsite::getDB();
        $string = $db->retrieveOne("select * from forms where formId ='" . $db->escape($id) . "' AND type = 'Precandidate'");
        
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
                                where institutionId ="'  . $db->escape($instId) . '" AND studyId =' . $db->escape($educationId));
        return $id;
    }
    
    public static function getEducation($name) {
        $db = PlonkWebsite::getDB();
        
        $education = $db->retrieveOne('select * from education where educationName = "' . $name .'"');
        
        return $education;
    }
    
    public static function getEducations() {
        $db = PlonkWebsite::getDB();
        
        $educations = $db->retrieve('select educationName from education inner join educationPerInstitute 
            on education.educationId = educationPerInstitute.studyId 
            inner join institutions on educationPerInstitute.institutionId = institutions.instEmail 
            WHERE institutions.instName = "'.INSTITUTE.'"');
        
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
        
        $uploadedWhat = $db->retrieveOne("select uploadedWhat from erasmusstudent where users_email ='" . $db->escape($id)."'");
        
        return $uploadedWhat;
    }
    
    public static function getIdLevel($level) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne('select levelId from erasmusLevel where levelName = "' . $db->escape($level) . '"');

        return $id;
    }
    
    public static function updateErasmusStudent($table, $values, $where) {
        $db = PlonkWebsite::getDB();

        $true = $db->update($table, $values, $where);
    }
    
    public static function getEmail($id) {
        $db = PlonkWebsite::getDB();
        
        $email = $db->retrieveOne('select email from users where userId = "'.$db->escape($id).'"');
        
        return $email['email'];
    }
    
    public static function getStudentByForm($id) {
        $db = PlonkWebsite::getDB();
        
        $student = $db->retrieveOne("select studentId from forms where formId = '".$id."'");
        
        return $student['studentId'];
    }
    
    public static function getForm($id) {
        $db = PlonkWebsite::getDB();
        
        $form = $db->retrieveOne("select * from forms where formId = '".$id."'");
         
        return $form;
        
       
    }

}

?>
