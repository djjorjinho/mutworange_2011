<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class HomeDB {
    /**
	 * Return User, controll on PW
	 *
	 * @return array
	 */
	public static function userExist($email, $password)
	{
		// get DB instance
		$db = PlonkWebsite::getDB();

		// query DB
		$items = $db->retrieveOne("select userId from users where email = '" . $db->escape($email) . "' AND password ='" . $db->escape($password) . "'");
		
		// return the result
		return $items;
	}

        public static function getNameById($id) {
            $id = (int) $id;

            $db = PlonkWebsite::getDB();

            $items = $db->retrieveOne("select firstName from users where userId =" . $db->escape($id));

            return $items;
        }
}
