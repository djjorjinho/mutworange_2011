<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ExtendDB {

    public static function getUserById($id) {
        $db = PlonkWebsite::getDB();

        $user = $db->retrieveOne("select u.familyName,u.firstName,e.startDate,e.endDate from  users as u
            inner join erasmusstudent as e on u.email = e.users_email 
            where e.users_email = '" . $db->escape($id) . "'");
        return $user;
    }

    public static function getHomeInstitution($id) {
        $db = PlonkWebsite::getDB();

        $institution = $db->retrieveOne("select i.instName from  erasmusstudent as e
            inner join institutions as i on e.homeInstitutionId = i.instEmail 
            where e.users_email = '" . $db->escape($id) . "'");
        return $institution;
    }

    public static function getHostInstitution($id) {
        $db = PlonkWebsite::getDB();

        $institution = $db->retrieveOne("select i.instName from  erasmusstudent as e
            inner join institutions as i on e.hostInstitutionId = i.instEmail 
            where e.users_email = '" . $db->escape($id) . "'");
        return $institution;
    }

    public static function getEducation($id) {
        $db = PlonkWebsite::getDB();

        $education = $db->retrieveOne("select e.educationName from education as e
            inner join educationperinstitute as i on e.educationId = i.educationPerInstId
            inner join erasmusstudent as s on i.educationPerInstId = s.educationPerInstId
            where s.users_email = '" . $db->escape($id) . "'");

        return $education;
    }

}

?>
