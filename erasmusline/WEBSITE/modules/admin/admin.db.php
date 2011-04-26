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
	public function getUsersInfo()
	{
		// create db instance
		$db = PlonkWebsite::getDB();

		// retrieve info from table gebruikers
		$usersInfo = $db->retrieve('SELECT * FROM users inner join erasmusStudent on users.userId  = erasmusStudent.studentId');

		return $usersInfo;

	}
}
