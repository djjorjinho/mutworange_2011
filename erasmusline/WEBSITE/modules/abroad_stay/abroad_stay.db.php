<?php

class abroad_stayDB {

    //checks for DubliCates Entries in DB  (done)
    public static function checkDubl($name, $field) {
        $db = PlonkWebsite::getDB();
        $query = $db->retrieve("SELECT ". $db->escape($field)." FROM erasmusstudent as ers where ers.users_email ='" . $db->escape($name) . "'");
        if (isset($query[0]["$field"])) {
            return 'Dub';
        }
    }

    //Fills tpl withs Student Info (DONE)
    public static function getStudentInfo($matrNum) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT ers.startDate,ers.homeCoordinatorId,ers.hostCoordinatorId,ers.homeInstitutionId,ers.hostInstitutionId,u.firstName, u.familyName, u.sex,u.tel,u.email,u.birthDate,u.birthPlace,u.userId FROM users as u 
                JOIN erasmusstudent as ers on u.email = ers.users_email where u.email ='" . $db->escape($matrNum) . "'");


        return $stInfo;
    }
    public static function getCoordInfo($coorId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT u.fax, u.firstName, u.familyName, u.sex,u.tel,u.email FROM users as u where u.email ='" . $db->escape($coorId) . "'");

        return $stInfo;
    }
    public static function getInstInfo($instId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT instName FROM institutions where  instEmail='" . $db->escape($instId) . "'");

        return $stInfo;
    }

    //Get Values For Students List (DONE)
    public static function getStudentList($num) {

        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                where ers.hostCoordinatorId='$cordId'
                AND (startDate is NULL OR endDate is NULL )
                ORDER BY familyName ASC LIMIT 20 OFFSET " . $db->escape($num) . "  ");
        return $stName;
    }
    public static function getStudentListRecords($num) {

        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                where ers.hostCoordinatorId='" . $db->escape($cordId) . "'
                AND startDate is not NULL 
                AND endDate is not NULL
                ORDER BY familyName ASC LIMIT 20 OFFSET " . $db->escape($num) . " ");
        return $stName;
    }

    public static function getSearch($SearchValue, $SearchFor) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stInfo = $db->retrieve("select u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
               WHERE (startDate is NULL OR endDate is NULL)
                AND " . $db->escape($SearchFor) . " like '%" . $db->escape($SearchValue) . "%'
                and ers.hostCoordinatorId='" . $db->escape($cordId) . "'");

        return $stInfo;
    }

    public static function getSearchRecords($SearchValue, $SearchFor) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stInfo = $db->retrieve("select u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
               where ers.hostCoordinatorId='" . $db->escape($cordId) . "'
                AND startDate is not NULL 
                AND endDate is not NULL
                AND " . $db->escape($SearchFor) . " like '%" . $db->escape($SearchValue) . "%'
                and ers.hostCoordinatorId='" . $db->escape($cordId) . "'");

        return $stInfo;
    }

    public static function checkRecords($name, $for) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stInfo = $db->retrieve("select ers.$for from erasmusstudent as ers
                WHERE ers.users_email='" . $db->escape($name) . "'
                AND ers.hostCoordinatorId = '" . $db->escape($cordId) . "'
                AND ers." . $db->escape($for) . " is not NULL");

        return $stInfo;
    }

    public static function PostForm($name) {
        $db = PlonkWebsite::getDB();
        $studentId = $name['User'];
        unset($name['User']);
        foreach ($name as $key => $value) {
            $query = "UPDATE erasmusstudent SET " . $db->escape($key) . " = '" . $db->escape($value) . "' WHERE users_email='" . $db->escape($studentId) . "'";
        }
        $db->execute($query);
    }

    public static function SubmitTranscript($form, $student) {

        $db = PlonkWebsite::getDB();
        $homeCoorMail = $db->retrieve("SELECT u.email FROM users as u where u.email =(
            SELECT ers.homeCoordinatorId FROM erasmusstudent as ers where ers.users_email='" . $db->escape($student) . "') ");

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "erasmusline@gmail.com";
        $mail->Password = "stvakis1";
        $mail->SetFrom('stvakis@gmail.com', 'Erasmus Line');
        $mail->FromName = "Erasmus Line";
        $mail->AddAddress($homeCoorMail[0]['email']);
        $mail->Subject = "Certificate";
        $mail->Body = $form;
        $mail->IsHTML(true);
        $mail->SMTPDebug = false;
        $mail->do_debug = 0;
        if (!$mail->Send()) {
            return $mail->ErrorInfo;
        } else {
            return '1';
        }
    }

}

?>
