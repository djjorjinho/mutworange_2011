<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PrecandidateDB {

    public static function getUserById($id) {
        $db = PlonkWebsite::getDB();

        $user = $db->retrieveOne("SELECT * FROM users inner join erasmusStudent on users.userId = erasmusStudent.studentId inner join institutions on erasmusStudent.homeInstitutionId = institutions.instId  where userId = " . $db->escape($id));

        return $user;
    }
    
    public static function getUserTest($id) {
        $db = PlonkWebsite::getDB();
        $user = $db->retrieveOne("select * from users inner join institutions on users.institutionId = institutions.instId where userId ='" . $db->escape($id) . "'");

        
        return $user;
    }

}

?>
