<?php

class LoginDB {

    public static function getUserByEmail($email) {
        $db = PlonkWebsite::getDB();

        $user = $db->retrieveOne("select * from users where email = '" . $db->escape($email) . "'");

        return $user;
    }
    
    public static function updatePassword($values,$email) {
        $db = PlonkWebsite::getDB();
        
        $i = $db->update('users', $values,'email = "'.$db->escape($email).'"');
    }

}
