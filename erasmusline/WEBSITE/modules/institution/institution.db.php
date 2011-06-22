<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class InstitutionDB {

	public static function getCourseInfo()
	{
		// create db instance
		$db = PlonkWebsite::getDB();

		$coursesInfo = $db->retrieve("SELECT * FROM coursespereducperinst
		WHERE institutionId = '".INST_EMAIL."'");

		return $coursesInfo;

	}

	public static function getResidenceInfo()
	{
		// create db instance
		$db = PlonkWebsite::getDB();

		$residenceInfo = $db->retrieve("SELECT * FROM residence
		WHERE institutionId = '".INST_EMAIL."'");

		return $residenceInfo;

	}

	public static function getOwnerInfo()
	{
		// create db instance
		$db = PlonkWebsite::getDB();

		$ownerInfo = $db->retrieve("SELECT * FROM owner
		WHERE institutionId = '".INST_EMAIL."'");

		return $ownerInfo;

	}
	
	public static function getCountry($code)
	{
		// create db instance
		$db = PlonkWebsite::getDB();

		$countryInfo = $db->retrieve("SELECT * FROM country
		WHERE Code = '".$code."'");

		return $countryInfo;

	}
	


	public static function getEducationInfo()
	{
		// create db instance
		$db = PlonkWebsite::getDB();

		$educationInfo = $db->retrieve("SELECT * FROM education inner join
		educationperinstitute on education.educationId = 
		educationperinstitute.studyId WHERE educationperinstitute.institutionId 
		= '".INST_EMAIL."'");

		return $educationInfo;

	}

	public static function getInstData()
	{
		// create db instance
		$db = PlonkWebsite::getDB();

		$instInfo = $db->retrieve("SELECT * FROM institutions
		WHERE instEmail = '".INST_EMAIL."'");

		return $instInfo;

	}

	public static function insertDB($table, $values) {

		// create db instance
		$db = PlonkWebsite::getDB();

		return $insertId = $db->insert($table, $values);
	}


	public static function delete($table,$where) {
		// create db instance
		$db = PlonkWebsite::getDB();

		$db->delete($table,$where);

	}

	public static function update($table,$value,$where='') {
		// create db instance
		$db = PlonkWebsite::getDB();

		$db->update($table,$value,$where);
	}

	public static function select($table,$where) {
		// create db instance
		$db = PlonkWebsite::getDB();

		// retrieve info from table gebruikers
		$select = $db->retrieve("SELECT * FROM " . $table . "
		WHERE " . $where);

		return $select;
	}
}
