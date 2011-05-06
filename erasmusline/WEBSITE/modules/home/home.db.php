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

            $items = $db->retrieveOne("select firstName from users where userId = " . $db->escape($id));

            return $items;
        }
        
        public static function getLatestEvent($id) {
            $id = (int) $id;
            
            $db = PlonkWebsite::getDB();
            
            $eventInfo = $db ->retrieveOne("SELECT * from studentsEvents inner join erasmusLevel on studentsEvents.erasmusLevelId = erasmusLevel.levelId where erasmusStudentId = ".$db->escape($id).' ORDER BY timestamp,eventId DESC');
            return $eventInfo;
        }
        
        public static function getForms($id) {
            $id = (int) $id;
            
            $db = PlonkWebsite::getDB();
            
            $forms = $db->retrieve('SELECT type, date, module, view FROM forms inner join erasmusLevel on forms.erasmusLevelId = erasmusLevel.levelId WHERE studentId ='.$db->escape($id));
            
            return $forms;
        }
        
        public static function getNext($next) {
            $db = PlonkWebsite::getDB();
            
            $nextLevel = $db->retrieveOne("select * from erasmuslevel where levelName = '" . $db->escape($next). "'");
            
            return $nextLevel;
        }
}
