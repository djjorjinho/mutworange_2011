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
			$items = $db->retrieveOne('select familyName,firstName,educationName from erasmusStudent
	                                            inner join educationPerInstitute on erasmusStudent.educationPerInstId = educationPerInstitute.educationperInstId
	                                            inner join education on studyId = educationId
	                                            inner join users on studentId = userId
	                                            where studentId = ' . $db->escape($id ));

			// return the result
			return $items;
	    }

	    public static function getHomeInstitution($id) {
	        // get DB instance
			$db = PlonkWebsite::getDB();

			// query DB
			$items = $db->retrieveOne('select * from erasmusStudent inner join institutions on erasmusStudent.homeInstitutionId  = institutions.instId inner join country on institution.instCountry = country.Code where erasmusStudent.studentId = ' .$db->escape($id));

			// return the result
			return $items;
	    }

	    public static function getHostInstitution($id) {
	        // get DB instance
			$db = PlonkWebsite::getDB();

			// query DB
			$items = $db->retrieveOne('select i.instName,c.Name from erasmusStudent as e inner join institutions as i on e.hostInstitutionId = i.instId inner join country as c on i.instCountry = c.Code where e.studentId = ' . $db->escape($id));

			// return the result
			return $items;
	    }




public static function getHomeCoordinator($id) {
        // get DB instance
		$db = PlonkWebsite::getDB();

		// query DB
		$items = $db->retrieveOne('select * from erasmusStudent inner join users on homeCoordinatorId = userId where studentId = ' . $db->escape($id ));

		// return the result
		return $items;
    }

    public static function getHostCoordinator($id) {
        // get DB instance
		$db = PlonkWebsite::getDB();

		// query DB
		$items = $db->retrieveOne('select * from erasmusStudent inner join users on hostCoordinatorId = userId where studentId = ' .$db->escape( $id) );

		// return the result
		return $items;
    }

    public static function getStudentById($id) {
        // get DB instance
		$db = PlonkWebsite::getDB();

		// query DB
		$items = $db->retrieveOne('select * from users as u inner join country as c on u.country = c.Code where userId = ' . $db->escape($id ));

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

        $id = $db->retrieveOne('select userId from users where email = "'.$db->escape($email).'"');

        return $id;
    }

    public static function getIdInst($name) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne('select instId from institutions where instName = "'.$db->escape($name).'"');

        return $id;
    }
    
    public static function getStudies() {
        $db = PlonkWebsite::getDB();
        
        $studies = $db->retrieve('SELECT educationName FROM education 
                    inner join educationPerInstitute on education.educationId = educationPerInstitute.studyId 
                    inner join institutions on educationPerInstitute.institutionId = institutions.instId 
                    WHERE institutions.instName = "'.INSTITUTE.'"');
        return $studies;
    }
    
    public static function updateErasmusStudent($table,$values, $where) {
        $db = PlonkWebsite::getDB();
        
        $true = $db->update($table,$values, $where);
    }
    
    public static function getEducationPerInstId($inst,$edu) {
        $db = PlonkWebsite::getDB();
        
        $education = $db->retrieveOne("select educationPerInstId from educationPerInstitute where institutionId = ".$inst." and studyId = ".$edu);
        
        return $education;
    }
    
    public static function getEducation($name) {
        $db = PlonkWebsite::getDB();
        
        $education = $db->retrieveOne('select * from education where educationName = "' . $name .'"');
        
        return $education;
    }
}
