<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ExtendDB {

    public static function getUserById($id) {
        $db = PlonkWebsite::getDB();

        $user = $db->retrieveOne("select u.familyName,u.firstName,e.startDate,e.endDate from  users as u
            inner join erasmusstudent as e on u.email = e.users_email 
            where e.users_email = '" . $db->escape($id) . "'");
        return $user;
    }

    public static function getHomeInstitution($id) {
        $db = PlonkWebsite::getDB();

        $institution = $db->retrieveOne("select i.instName from  erasmusstudent as e
            inner join institutions as i on e.homeInstitutionId = i.instEmail 
            where e.users_email = '" . $db->escape($id) . "'");
        return $institution;
    }

    public static function getHostInstitution($id) {
        $db = PlonkWebsite::getDB();

        $institution = $db->retrieveOne("select i.instName from  erasmusstudent as e
            inner join institutions as i on e.hostInstitutionId = i.instEmail 
            where e.users_email = '" . $db->escape($id) . "'");
        return $institution;
    }

    public static function getEducation($id) {
        $db = PlonkWebsite::getDB();

        $education = $db->retrieveOne("select e.educationName from education as e
            inner join educationperinstitute as i on e.educationId = i.educationPerInstId
            inner join erasmusstudent as s on i.educationPerInstId = s.educationPerInstId
            where s.users_email = '" . $db->escape($id) . "'");

        return $education;
    }
    
    public static function getStudentById($id) {
        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $items = $db->retrieveOne('select * from users as u inner join country as c on u.country = c.Code where email = "' . $db->escape($id).'"');

        // return the result
        return $items;
    }
    
    public static function getStudentByForm($id) {
        $db = PlonkWebsite::getDB();
        
        $student = $db->retrieveOne("select studentId from forms where formId = '".$id."'");
        
        return $student['studentId'];
    }
    
    public static function getInfoUser($id) {
        $db = PlonkWebsite::getDB();
        
        $student = $db->retrieveOne("select * from users where email = '".$id."'");
        
        return $student;
    }
    
    public static function getErasmusInfo($id) {
        $db = PlonkWebsite::getDB();
        
        $student = $db->retrieveOne("select * from erasmusStudent where users_email = '".$id."'");
        
        return $student;
    }
    
     public static function updateErasmusStudent($table, $values, $where) {
        $db = PlonkWebsite::getDB();

        $true = $db->update($table, $values, $where);
    }
    
    public static function getForm($id) {
        $db = PlonkWebsite::getDB();
        
        $form = $db->retrieveOne("select * from forms where formId = '".$id."'");
        
        return $form;
    }
    
    public static function getIdLevel($level) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne('select levelId from erasmusLevel where levelName = "' . $db->escape($level) . '"');

        return $id;
    }
    
    public static function insertValues($table, $values) {
        $db = PlonkWebsite::getDB();

        $insertId = $db->insert($table, $values);

        return $insertId;
    }
    
    public static function getJson($id, $type) {
        $db = PlonkWebsite::getDB();

        $string = $db->retrieveOne("select content from forms where formId ='" . $db->escape($id) . "' AND type = '".$db->escape($type)."'");
        
        return $string;
    }
    
    public static function getStudentStatus($id) {
        $db = PlonkWebsite::getDB();

        $status = $db->retrieveOne("select statusOfErasmus, action from erasmusStudent where users_email ='" . $db->escape($id) . "'");

        return $status;
    }
    
    public static function getErasmusLevelId($name) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne("select * from erasmuslevel where levelName = '" . $name . "'");

        return $id;
    }
    
    

}

?>
