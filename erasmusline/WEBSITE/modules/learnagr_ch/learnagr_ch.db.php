<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class learnagr_chDB {

    public static function getStInfo($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT u.firstName,u.familyName,ins.instName,c.Name from users as u
               join institutions as ins on ins.instEmail=(SELECT hostInstitutionId from erasmusstudent where users_email='".$db->escape($stId)."')
               left join country as c on c.Code=ins.instCountry 
                where u.email='".$db->escape($stId)."'");
        return $stInfo;
    }

    public static function getInstCourses($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
                    SELECT * FROM education 
                    inner join educationPerInstitute on education.educationId = educationPerInstitute.studyId 
                    inner join institutions on educationPerInstitute.institutionId = institutions.instEmail 
                    inner join coursespereducperinst on institutions.instEmail = coursespereducperinst.instId
                    WHERE educationPerInstitute.institutionId=(SELECT ers.hostInstitutionId from erasmusstudent as ers where ers.users_email='".$db->escape($stId)."')");
       
        return $stInfo;
        
        
    }

    public static function getConfCourses($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT cour.courseCode, cour.courseName, cour.ectsCredits from coursespereducperinst as cour
               where cour.courseId='".$db->escape($stId)."'");
        return $stInfo;
    }

    public static function SubmitLearCh($form,$post) {
           $db = PlonkWebsite::getDB();
           $student=  PlonkSession::get('id');
        $instMail = $db->retrieve("SELECT inst.instEmail FROM institutions as inst where instEmail =(
            SELECT ers.hostInstitutionId FROM erasmusstudent as ers where ers.users_email='".$db->escape($student)."') ");
          
        
        $mail = new PHPMailer();
        $mail->IsSMTP();
        //$mail->SMTPAuth = true;
        //$mail->SMTPSecure = "tls";
        $mail->Host = MAIL_SMTP;
        $mail->Port = 25;
        $mail->SetFrom(MAIL_SENDER);
        $mail->FromName = MAIL_SENDER;
        //$mail->AddAddress($instMail[0]["instEmail"]);
        $mail->AddAddress("nathan.vanassche@kahosl.be");
        $mail->Subject = "Learning Agreement Change";
        $mail->Body = $form;
        $mail->IsHTML(true);
        $mail->SMTPDebug = false;
        $mail->do_debug = 0;
        if (!$mail->Send()) {
            return $mail->ErrorInfo;
            
        } else {
            
            /* Insert To table*/
        $db=  PlonkWebsite::getDB();
        $date=date("y-m-d");
        $post=  json_encode($post);
        $student=  PlonkSession::get('id');
        $levelId = $db->retrieveOne("select levelId from erasmuslevel where levelName =  'LearnAgreement Change'");
        $query = "INSERT INTO forms (type,date,content,studentId,erasmusLevelId) VALUES( 'LearnAgreement Change','".$db->escape($date)."','".$db->escape($post)."',".$db->escape($student)."," .$levelId['levelId'] . ") ";
        $db->execute($query);
            return '1';
        }
    }

}

?>
