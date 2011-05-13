<?php

class abroad_stayDB {

    //checks for DubliCates Entries in DB 
    public static function checkDubl($name, $field) {
        $db = PlonkWebsite::getDB();
        $query = $db->retrieve("SELECT $field FROM erasmusstudent where studentId ='" . $db->escape($name) . "'");

        if ($query[0]["$field"] != '0000-00-00') {
            return 'Dub';
        }
    }

    //Fills tpl withs Student Info
    public static function getStudentInfo($matrNum) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT ers.startDate,ers.homeCoordinatorId,ers.hostCoordinatorId,ers.homeInstitutionId,ers.hostInstitutionId,u.firstName, u.familyName, u.sex,u.tel,u.email,u.birthDate,u.birthPlace FROM users as u 
                JOIN erasmusstudent as ers on u.userId = ers.studentId where u.userId ='" . $db->escape($matrNum) . "'");


        return $stInfo;
    }

    //Fills tpl with Cordinator
    public static function getCoordInfo($coorId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT u.fax, u.firstName, u.familyName, u.sex,u.tel,u.email FROM users as u where u.userId ='" . $db->escape($coorId) . "'");

        return $stInfo;
    }

    //Fille tpl with Institution Info
    public static function getInstInfo($instId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT instName FROM institutions where  instId='" . $db->escape($instId) . "'");

        return $stInfo;
    }

    //Get Values For Students List
    public static function getStudentList($num) {

        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.userId=ers.studentId
                where ers.hostCoordinatorId='$cordId'
                AND (startDate='0000-00-00' OR endDate='0000-00-00')
                ORDER BY familyName ASC LIMIT 20 OFFSET " . $db->escape($num) . "  ");
        return $stName;
    }

    public static function getStudentListRecords($num) {

        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.userId=ers.studentId
                where ers.hostCoordinatorId='" . $db->escape($cordId) . "'
                AND startDate!='0000-00-00' 
                AND endDate!='0000-00-00'
                ORDER BY familyName ASC LIMIT 20 OFFSET " . $db->escape($num) . " ");
        return $stName;
    }

    public static function getSearch($SearchValue, $SearchFor) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stInfo = $db->retrieve("select u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.userId=ers.studentId
               WHERE (startDate='0000-00-00' OR endDate='0000-00-00')
                AND " . $db->escape($SearchFor) . " like '%" . $db->escape($SearchValue) . "%'
                and ers.hostCoordinatorId='" . $db->escape($cordId) . "'");

        return $stInfo;
    }

    public static function getSearchRecords($SearchValue, $SearchFor) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stInfo = $db->retrieve("select u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.userId=ers.studentId
               where ers.hostCoordinatorId='" . $db->escape($cordId) . "'
                AND startDate!='0000-00-00' 
                AND endDate!='0000-00-00'
                AND " . $db->escape($SearchFor) . " like '%" . $db->escape($SearchValue) . "%'
                and ers.hostCoordinatorId='" . $db->escape($cordId) . "'");

        return $stInfo;
    }

    public static function checkRecords($name, $for) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stInfo = $db->retrieve("select ers.$for from erasmusstudent as ers
                WHERE ers.studentId='" . $db->escape($name) . "'
                AND ers.hostCoordinatorId = '" . $db->escape($cordId) . "'
                AND ers." . $db->escape($for) . " != '0000-00-00'");

        return $stInfo;
    }

    public static function PostForm($name) {

        $db = PlonkWebsite::getDB();
        $studentId = $name['User'];
        unset($name['User']);
        foreach ($name as $key => $value) {
            $query = "UPDATE erasmusstudent SET " . $db->escape($key) . " = '" . $db->escape($value) . "' WHERE studentId='" . $db->escape($studentId) . "'";
        }
        $db->execute($query);
    }

    public static function SubmitTranscript($form, $student) {

        $db = PlonkWebsite::getDB();
        $homeCoorMail = $db->retrieve("SELECT u.email FROM users as u where u.userId =(
            SELECT ers.homeCoordinatorId FROM erasmusstudent as ers where ers.studentId='" . $db->escape($student) . "') ");

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "stvakis@gmail.com";
        $mail->Password = "";
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
