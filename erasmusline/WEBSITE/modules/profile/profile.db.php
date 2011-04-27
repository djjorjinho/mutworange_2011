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
	public static function getItemsById($id)
	{

		// rework params
		$id = (string) $id;

		// get DB instance
		$db = PlonkWebsite::getDB();

		// query DB
		$item = $db->retrieveOne('SELECT * FROM users WHERE userId = '. $db->escape($id));

		// return the result
		return $item;

	}

        public static function getErasmusById($id)
	{

		// rework params
		$id = (string) $id;

		// get DB instance
		$db = PlonkWebsite::getDB();

		// query DB
		

		

	}
}
