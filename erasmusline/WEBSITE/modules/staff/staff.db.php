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
        $pres = $db->retrieve("SELECT * FROM users inner join erasmusStudent on users.email = erasmusStudent.users_email WHERE erasmusStudent.statusOfErasmus = '".$formtype."' AND erasmusStudent.action = 2");

        return $pres;
    }
    
    public static function getLagree($id) {
        // create db instance
        $db = PlonkWebsite::getDB();

        // retrieve info from table gebruikers
        $lagrees = $db->retrieve("SELECT * FROM users inner join erasmusStudent on users.email = erasmusStudent.users_email WHERE (action = 12 OR action = 22 OR action = 2) && (statusOfErasmus = 'Student Application and Learning Agreement')");
        
        //Plonk::dump("SELECT * FROM users inner join studentsEvents on users.email = studentsEvents.studentId WHERE studentsEvents.action == 2 OR studentsEvents.action == 12 OR studentsEvents.action == 22 AND studentsEvents.erasmusLevelId = ".$id['levelId']);
        return $lagrees;
        
    }
    
    public static function getApplics($id) {
        // create db instance
        $db = PlonkWebsite::getDB();

        // retrieve info from table gebruikers
        $applics = $db->retrieve("SELECT * FROM users inner join erasmusStudent on users.email = erasmusStudent.users_email WHERE (action = 20 OR action = 21 OR action = 22) && (statusOfErasmus = 'Student Application and Learning Agreement')");
        
        //Plonk::dump($applics);
        //Plonk::dump("SELECT * FROM users inner join studentsEvents on users.email = studentsEvents.studentId WHERE studentsEvents.action == 2 OR studentsEvents.action == 12 OR studentsEvents.action == 22 AND studentsEvents.erasmusLevelId = ".$id['levelId']);
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
            
            $eventInfo = $db ->retrieveOne("SELECT * from studentsEvents inner join erasmusLevel on studentsEvents.erasmusLevelId = erasmusLevel.levelId where erasmusStudentId = '".$db->escape($id)."' ORDER BY timestamp,eventId DESC");
            return $eventInfo;
        }
        
        
     

}
