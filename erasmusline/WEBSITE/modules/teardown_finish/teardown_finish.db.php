<?php

class Teardown_finishDB {

    public static function getItemsById($id) {

        // rework params
        $id = (string) $id;

        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $item = $db->retrieveOne('SELECT * FROM users WHERE email = "' . $db->escape($id) . '"');

        // return the result
        return $item;
    }

    public static function getErasmusById($id) {

        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $item = $db->retrieveOne('SELECT * FROM erasmusStudent WHERE users_email = "' . $db->escape($id) . '"');

        // return the result
        return $item;
    }

    public static function getHost($id) {
        $db = PlonkWebsite::getDB();

        $host = $db->retrieveOne("select institutions.instName,users.familyName,users.firstName from institutions 
            inner join erasmusstudent on institutions.instEmail = erasmusstudent.hostInstitutionId
            inner join users on erasmusstudent.hostCoordinatorId = users.email
            where users_email = '" . $db->escape($id) . "'");

        return $host;
    }

    public static function getHome($id) {
        $db = PlonkWebsite::getDB();

        $host = $db->retrieveOne("select institutions.instName,users.familyName,users.firstName from institutions 
            inner join erasmusstudent on institutions.instEmail = erasmusstudent.homeInstitutionId
            inner join users on erasmusstudent.homeCoordinatorId = users.email
            where users_email = '" . $db->escape($id) . "'");

        return $host;
    }

    public static function getStudy($id) {
        $db = PlonkWebsite::getDB();

        $study = $db->retrieveOne("select education.educationName from erasmusstudent
            inner join educationperinstitute on erasmusstudent.educationPerInstId = educationperinstitute.educationPerInstId
            inner join education on educationperinstitute.studyId = education.educationId
            where erasmusstudent.users_email = '" . $db->escape($id) . "'");

        return $study;
    }

    public static function insertStudentEvent($table, $values) {
        $db = PlonkWebsite::getDB();
        $update = $db->insert($table, $values);
    }

    public static function getErasmusLevelId($name) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne("select * from erasmuslevel where levelName = '" . $name . "'");

        return $id;
    }
    
    public static function getForm($id) {
        $db = PlonkWebsite::getDB();
        
        $form = $db->retrieveOne("select * from forms where formId = '".$id."'");
        
        return $form;
    }
    
    public static function updateErasmusStudent($table, $values, $where) {
        $db = PlonkWebsite::getDB();

        $true = $db->update($table, $values, $where);
    }

}
