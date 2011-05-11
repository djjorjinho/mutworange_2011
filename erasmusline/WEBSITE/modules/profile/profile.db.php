<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ProfileDB {

    /**
     * Geeft informatie van de gebruiker terug
     * @param int $id
     * @return array
     */
    public static function getItemsById($id) {

        // rework params
        $id = (string) $id;

        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $item = $db->retrieveOne('SELECT * FROM users WHERE userId = ' . $db->escape($id));

        // return the result
        return $item;
    }

    public static function getErasmusById($id) {

        // get DB instance
        $db = PlonkWebsite::getDB();

        // query DB
        $item = $db->retrieveOne('SELECT * FROM erasmusStudent WHERE studentId = ' . $db->escape($id));

        // return the result
        return $item;
    }

    public static function getHost($id) {
        $db = PlonkWebsite::getDB();

        $host = $db->retrieveOne("select institutions.instName,users.familyName,users.firstName from institutions 
            inner join erasmusstudent on institutions.instId = erasmusstudent.hostInstitutionId
            inner join users on erasmusstudent.hostCoordinatorId = users.userId
            where studentId = " . $db->escape($id));

        return $host;
    }

    public static function getHome($id) {
        $db = PlonkWebsite::getDB();

        $host = $db->retrieveOne("select institutions.instName,users.familyName,users.firstName from institutions 
            inner join erasmusstudent on institutions.instId = erasmusstudent.homeInstitutionId
            inner join users on erasmusstudent.homeCoordinatorId = users.userId
            where studentId = " . $db->escape($id));

        return $host;
    }
    
    public static function getStudy($id) {
        $db = PlonkWebsite::getDB();
        
        $study = $db->retrieveOne("select education.educationName from erasmusstudent
            inner join educationperinstitute on erasmusstudent.educationPerInstId = educationperinstitute.educationPerInstId
            inner join education on educationperinstitute.studyId = education.educationId
            where erasmusstudent.studentId = " . $db->escape($id));
        
        return $study;
    }
    
    public static function getCourses($id) {
        $db = PlonkWebsite::getDB();
        
        $courses = $db->retrieve("select coursespereducperinst.courseCode, coursespereducperinst.courseName, coursespereducperinst.ectsCredits from coursespereducperinst
            inner join grades on coursespereducperinst.courseId = grades.courseId 
            where grades.studentId = " . $db->escape($id));
        return $courses;
    }

}
