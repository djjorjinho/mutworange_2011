<?php

class acom_regDB {
        public static function checkDB() {
        $db = PlonkWebsite::getDB();
        $studentId=  PlonkSession::get('id');
        $stInfo = $db->retrieve("SELECT * FROM forms 
            where  studentId='".$db->escape($studentId)."'
            and erasmusLevelId=10");

        return $stInfo;
    }
    
    public static function checkLevel() {
        $db = PlonkWebsite::getDB();
        $studentId=  PlonkSession::get('id');
        $stInfo = $db->retrieve("SELECT statusOfErasmus FROM erasmusstudent 
            where  users_email='".$db->escape($studentId)."'");

        return $stInfo;
    }
    
    public static function getInst($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT instName,iBan,bic,instStreetNr,instPostalCode,instCity FROM institutions where  instEmail=(select hostInstitutionId from 
            erasmusstudent where users_email='".$db->escape($stId)."')");

        return $stInfo;
    }

    
    //Fills tpl withs Student Info
    public static function getStudentInfo($matrNum) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT ers.homeInstitutionId,ers.hostInstitutionId,u.firstName, u.familyName, u.sex,u.tel,u.email,u.birthDate,u.birthPlace FROM users as u 
                JOIN erasmusstudent as ers on u.email = ers.users_email where u.email ='".$db->escape($matrNum)."'");


        return $stInfo;
    }


    //Fille tpl with Institution Info
    public static function getInstInfo($instId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT instName FROM institutions where  instEmail='".$db->escape($instId)."'");

        return $stInfo;
    }
    
    //Gets Available Residences
    public static function getResidences($stId){
        $db = PlonkWebsite::getDB();
        $acom = $db->retrieve("
                SELECT * FROM residence as r where r.available='1' and country=(
                SELECT ins.instCountry from institutions as ins where ins.instEmail=(
                SELECT ers.hostInstitutionId from erasmusstudent as ers where ers.users_email='".$db->escape($stId)."')
                   ) ORDER BY r.price ASC ");
        return $acom;
    }
    public static function getResidence($stId,$resId){
        $db = PlonkWebsite::getDB();
        $acom = $db->retrieve("
                SELECT * FROM residence as r where country=(
                SELECT ins.instCountry from institutions as ins where ins.instEmail=(
                SELECT ers.hostInstitutionId from erasmusstudent as ers where ers.users_email='".$db->escape($stId)."')
                   ) And r.residenceId='".$db->escape($resId)."'");
        return $acom;
    }
    public static function getResidenceAV($stId,$resId){
        $db = PlonkWebsite::getDB();
        $acom = $db->retrieve("
                SELECT * FROM residence as r where country=(
                SELECT ins.instCountry from institutions as ins where ins.instEmail=(
                SELECT ers.hostInstitutionId from erasmusstudent as ers where ers.users_email='".$db->escape($stId)."')
                   ) And r.residenceId='".$db->escape($resId)."' AND r.available=1");
        return $acom;
    }
    
         public static function SubmitTranscript($form, $name,$_POST) {

        $db = PlonkWebsite::getDB();
        $homeInst = $db->retrieve("SELECT ers.hostInstitutionId FROM erasmusstudent as ers where ers.users_email =(
            SELECT ers.homeCoordinatorId FROM erasmusstudent as ers where ers.users_email='" . $db->escape($name) . "') ");
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
        $mail->AddAddress($homeInst[0]['hostInstitutionId']);
        $mail->Subject = "Accomodation Register";
        $mail->Body = $form;
        $mail->IsHTML(true);
        $mail->SMTPDebug = false;
        $mail->do_debug = 0;
        if (!$mail->Send()) {
            return $mail->ErrorInfo;
        } else {
            unset($_POST['formAction'], $_POST['postForm']);
        $formTable = json_encode($_POST);
        $date = date("y-m-d");
        $query2 = "INSERT INTO forms (type,date,content,studentId,erasmusLevelId) VALUES( 'Accomodation Register','" . $db->escape($date) . "','" . $db->escape($formTable) . "','" . $db->escape($name) . "','10') ";
        $db->execute($query2);
        if (isset($_POST['startDate'])){
        $query1 = "UPDATE residence SET 
                available = '0'
                WHERE residenceId='" . $db->escape($_POST['res']) . "'
                ";
        $db->execute($query1);           
        }
            return '1';
        }
    }

}

?>
