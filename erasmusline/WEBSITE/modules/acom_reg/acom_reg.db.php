<?php

class acom_regDB {


   /* COMMON */

    
    //Fills tpl withs Student Info
    public static function getStudentInfo($matrNum) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT ers.homeInstitutionId,ers.hostInstitutionId,u.firstName, u.familyName, u.sex,u.tel,u.email,u.birthDate,u.birthPlace FROM users as u 
                JOIN erasmusstudent as ers on u.userEmail = ers.studentId where u.userEmail ='$matrNum'");


        return $stInfo;
    }


    //Fille tpl with Institution Info
    public static function getInstInfo($instId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT instName FROM institutions where  instEmail='$instId'");

        return $stInfo;
    }
}

?>
