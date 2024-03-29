<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class OfficeDB {

    public static function getForms($formtype) {
        // create db instance
        $db = PlonkWebsite::getDB();

        // retrieve info from table gebruikers
        $pres = $db->retrieve("SELECT * FROM forms inner join users on forms.studentId = users.email WHERE forms.type = '" . $formtype . "' AND forms.action = 2");

        return $pres;
    }

    public static function getLagree() {
        // create db instance
        $db = PlonkWebsite::getDB();

        // retrieve info from table gebruikers
        $lagrees = $db->retrieve("SELECT * FROM forms inner join users on forms.studentId = users.email WHERE (forms.type = 'Learning Agreement') AND (forms.action = 2)");

        //Plonk::dump("SELECT * FROM users inner join studentsEvents on users.email = studentsEvents.studentId WHERE studentsEvents.action == 2 OR studentsEvents.action == 12 OR studentsEvents.action == 22 AND studentsEvents.erasmusLevelId = ".$id['levelId']);
        return $lagrees;
    }

    public static function getApplics() {
        // create db instance
        $db = PlonkWebsite::getDB();

        // retrieve info from table gebruikers
        $applics = $db->retrieve("SELECT * FROM forms inner join users on forms.studentId = users.email WHERE (forms.type = 'Student Application Form') AND (forms.action = 2)");

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

        $eventInfo = $db->retrieveOne("SELECT * from studentsEvents inner join erasmusLevel on studentsEvents.erasmusLevelId = erasmusLevel.levelId where erasmusStudentId = '" . $db->escape($id) . "' ORDER BY timestamp,eventId DESC");
        return $eventInfo;
    }

}
