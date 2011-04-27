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
		$items = $db->retrieveOne('select * from erasmusStudent inner join educationPerInstitute on erasmusStudent.educationPerInstId = educationPerInstitute.educationperInstId inner join education on studyId = educationId where idStudent = ' . $db->escape($id ));

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
		$items = $db->retrieveOne('select institutions.name, country.Name from erasmusStudent inner join istitutions on erasmusStudent.hostInstitutionId = institutions.instId inner join country on institution.instCountry = country.Code where erasmusStudent.studentId = ' . $db->escape($id));

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
		$items = $db->retrieveOne('select * from users where userId = ' . $db->escape($id ));

		// return the result
		return $items;
    }
}
