<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LagreeformDB {

    public static function getStudyById($id) {
        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $items = $db->retrieveOne('select familyName,firstName,educationName,ectsCredits from erasmusStudent
	                                            inner join educationPerInstitute on erasmusStudent.educationPerInstId = educationPerInstitute.educationperInstId
	                                            inner join education on studyId = educationId
	                                            inner join users on users_email = email
	                                            where users_email = "' . $db->escape($id).'"');

        // return the result
        return $items;
    }

    public static function getHomeInstitution($id) {
        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $items = $db->retrieveOne('select * from erasmusStudent inner join institutions on erasmusStudent.homeInstitutionId  = institutions.instEmail inner join country on institution.instCountry = country.Code where erasmusStudent.users_email = "' . $db->escape($id).'"');

        // return the result
        return $items;
    }

    public static function getHostInstitution($id) {
        // get DB instance
        $db = PlonkWebsite::getDB();
        //Plonk::dump($id);
        // query DB
        $items = $db->retrieveOne('select i.instName,c.Name from erasmusStudent as e inner join institutions as i on e.hostInstitutionId = i.instEmail inner join country as c on i.instCountry = c.Code where e.users_email = "' . $db->escape($id).'"');
        // return the result
        return $items;
    }

    public static function getHomeCoordinator($id) {
        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $items = $db->retrieveOne('select * from erasmusStudent inner join users on homeCoordinatorId = email where users_email = "' . $db->escape($id).'"');

        // return the result
        return $items;
    }

    public static function getHostCoordinator($id) {
        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $items = $db->retrieveOne('select * from erasmusStudent inner join users on hostCoordinatorId = email where users_email = "' . $db->escape($id).'"');

        // return the result
        return $items;
    }

    public static function getStudentById($id) {
        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $items = $db->retrieveOne('select * from users as u inner join country as c on u.country = c.Code where email = "' . $db->escape($id).'"');

        // return the result
        return $items;
    }

    public static function getInstitCoor() {
        $db = PlonkWebsite::getDB();

        $user = $db->retrieveOne('select * from users where userLevel = "International relations office staff"');

        return $user;
    }

    public static function getSendInst() {
        $db = PlonkWebsite::getDB();

        $inst = $db->retrieveOne('select * from institutions as i inner join country as c on i.instCountry = c.Code where instName = "' . INSTITUTE . '"');

        return $inst;
    }

    public static function getCourseId($name) {
        $db = PlonkWebsite::getDB();

        $course = $db->retrieveOne('select * from coursespereducperinst where courseName = "' . $db->escape($name) . '"');

        return $course;
    }

    public static function getIdUsers($email) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne('select email from users where email = "' . $db->escape($email) . '"');

        return $id;
    }

    public static function getIdInst($name) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne('select instEmail from institutions where instName = "' . $db->escape($name) . '"');

        return $id;
    }

    public static function getStudies() {
        $db = PlonkWebsite::getDB();

        $studies = $db->retrieve('SELECT educationName FROM education 
                    inner join educationPerInstitute on education.educationId = educationPerInstitute.studyId 
                    inner join institutions on educationPerInstitute.institutionId = institutions.instEmail 
                    WHERE institutions.instName = "' . INSTITUTE . '"');
        return $studies;
    }

    public static function updateErasmusStudent($table, $values, $where) {
        $db = PlonkWebsite::getDB();

        $true = $db->update($table, $values, $where);
    }

    public static function getEducationPerInstId($inst, $edu) {
        $db = PlonkWebsite::getDB();

        $education = $db->retrieveOne("select educationPerInstId from educationPerInstitute where institutionId = '" . $inst . "' and studyId = " . $edu);

        return $education;
    }

    public static function getEducation($name) {
        $db = PlonkWebsite::getDB();

        $education = $db->retrieveOne('select * from education where educationName = "' . $name . '"');

        return $education;
    }

    public static function getErasmusLevelId($name) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne("select * from erasmuslevel where levelName = '" . $name . "'");

        return $id;
    }

    public static function insertStudentEvent($table, $values) {
        $db = PlonkWebsite::getDB();
        $update = $db->insert($table, $values);
    }

    public static function insertJson($table, $values) {
        $db = PlonkWebsite::getDB();

        $insertId = $db->insert($table, $values);

        return $insertId;
    }

    public static function getJson($id, $type) {
        $db = PlonkWebsite::getDB();

        $string = $db->retrieveOne("select content from forms where studentId ='" . $db->escape($id) . "' AND type = '".$db->escape($type)."'");

        return $string;
    }

    public static function getStudentStatus($id) {
        $db = PlonkWebsite::getDB();

        $status = $db->retrieveOne("select statusOfErasmus, action from erasmusStudent where users_email ='" . $db->escape($id) . "'");

        return $status;
    }

    public static function getCourseIdByCode($code) {
        $db = PlonkWebsite::getDB();

        $course = $db->retrieveOne('select * from coursespereducperinst where courseCode = "' . $db->escape($code) . '"');

        return $course;
    }
    
    public static function getIdLevel($level) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne('select levelId from erasmusLevel where levelName = "' . $db->escape($level) . '"');

        return $id;
    }
    
    public static function getEmail($id) {
        $db = PlonkWebsite::getDB();
        
        $email = $db->retrieveOne('select email from users where userId = '.$db->escape($id));
        
        return $email['email'];
    }
    
    public static function getSignature() {
        $db = PlonkWebsite::getDB();
        
        $digital = $db->retrieveOne("select digital from institutions where instName = '".INSTITUTE."'");
        
        return $digital;
    }

}
