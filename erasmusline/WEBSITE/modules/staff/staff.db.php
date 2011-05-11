<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class StaffDB {

    public static function getForms($formtype) {
        // create db instance
        $db = PlonkWebsite::getDB();

        // retrieve info from table gebruikers
        $pres = $db->retrieve("SELECT * FROM users inner join erasmusStudent on users.userId = erasmusStudent.studentId WHERE erasmusStudent.statusOfErasmus = '".$formtype."' AND erasmusStudent.action = 2");

        return $pres;
    }
    
    public static function getLagree($level, $user) {
        // create db instance
        $db = PlonkWebsite::getDB();

        // retrieve info from table gebruikers
        $applics = $db->retrieve("SELECT * FROM users inner join studentsEvents on users.userId = studentsEvents.studentId WHERE studentsEvents.action = 2 AND studentsEvents = ".$level." AND userId = ".$user);

        return $applics;
    }
    
    public static function getIdLevel($level) {
        $db = PlonkWebsite::getDB();

        $id = $db->retrieveOne('select levelId from erasmusLevel where levelName = "' . $db->escape($level) . '"');

        return $id;
    }
    
    public static function getLatestEvent($id) {
            $id = (int) $id;
            
            $db = PlonkWebsite::getDB();
            
            $eventInfo = $db ->retrieveOne("SELECT * from studentsEvents inner join erasmusLevel on studentsEvents.erasmusLevelId = erasmusLevel.levelId where erasmusStudentId = ".$db->escape($id)." ORDER BY timestamp,eventId DESC");
            return $eventInfo;
        }
     

}
