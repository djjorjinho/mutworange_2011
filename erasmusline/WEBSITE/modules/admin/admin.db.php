<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminDB {
    /**
	 * Gets all the info of all the users
	 *
	 * @return array
	 */
	public static function getStudentInfo()
	{
		// create db instance
		$db = PlonkWebsite::getDB();

		// retrieve info from table gebruikers
		$usersInfo = $db->retrieve("SELECT * FROM users inner join erasmusStudent on users.email = erasmusStudent.users_email WHERE userLevel = 'Student' AND isValidUser = 2");

		return $usersInfo;

	}
        
        public static function getStaffInfo() {
            // create db instance
		$db = PlonkWebsite::getDB();

		// retrieve info from table gebruikers
		$staffInfo = $db->retrieve("SELECT * FROM users WHERE userLevel != 'Student'");

		return $staffInfo;
        }
        
        public static function deleteStudents($table,$where,$which) {
            // create db instance
		$db = PlonkWebsite::getDB();
                
                $db->deleteMultiple($table,$where,$which);

        }
        
        public static function updateStudents($table,$what,$where,$value,$which) {
            // create db instance
		$db = PlonkWebsite::getDB();
                
                $db->updateOneInMany($table,$what,$where,$value,$which);
        }
        
        public static function getNonConfirmed() {
            // create db instance
		$db = PlonkWebsite::getDB();

		// retrieve info from table gebruikers
		$nonConfirmed = $db->retrieve("SELECT * FROM users WHERE isValidUser = 1");

		return $nonConfirmed;
        }
}
