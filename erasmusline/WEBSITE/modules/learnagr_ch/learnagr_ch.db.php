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
<<<<<<< HEAD
               join institutions as ins on ins.instEmail=(SELECT hostInstitutionId from erasmusstudent where users_email='".$db->escape($stId)."')
               left join country as c on c.Code=ins.instCountry 
                where u.email='".$db->escape($stId)."'");
=======
               join institutions as ins on ins.instId=(SELECT hostInstitutionId from erasmusstudent where studentId='" . $db->escape($stId) . "')
               left join country as c on c.Code=ins.instCountry 
                where u.userId='" . $db->escape($stId) . "'");
>>>>>>> c9bcc785e50c55798bc2fb155ac89a4604fd45b5
        return $stInfo;
    }

    public static function getSucceedCourses($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
<<<<<<< HEAD
                    SELECT * FROM education 
                    inner join educationPerInstitute on education.educationId = educationPerInstitute.studyId 
                    inner join institutions on educationPerInstitute.institutionId = institutions.instEmail 
                    inner join coursespereducperinst on institutions.instEmail = coursespereducperinst.instId
                    WHERE educationPerInstitute.institutionId=(SELECT ers.hostInstitutionId from erasmusstudent as ers where ers.users_email='".$db->escape($stId)."')");
       
=======
               SELECT cour.courseCode,cour.courseId, cour.courseName, cour.ectsCredits from coursespereducperinst as cour
               join educationperinstitute as ed on ed.educationPerInstId=cour.educationPerInstId
               join grades as g on cour.courseId=g.courseId
               where cour.educationPerInstId=(SELECT ers.educationPerInstId from erasmusstudent as ers where ers.studentId='" . $db->escape($stId) . "')
               and g.localGrade>=(
                SELECT scale from institutions where instId=(
                select hostInstitutionId from erasmusstudent where studentId='" . $db->escape($stId) . "'))   ");
>>>>>>> c9bcc785e50c55798bc2fb155ac89a4604fd45b5
        return $stInfo;
    }

    public static function getSelectedCourses($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT cour.courseCode,cour.courseId, cour.courseName, cour.ectsCredits from coursespereducperinst as cour
               join educationperinstitute as ed on ed.educationPerInstId=cour.educationPerInstId
               right join grades as g on cour.courseId=g.courseId
               where cour.educationPerInstId=(SELECT ers.educationPerInstId from erasmusstudent as ers where ers.studentId='" . $db->escape($stId) . "')
               and g.localGrade is NULL
               and g.studentId='" . $db->escape($stId) . "'");

        return $stInfo;
    }

    public static function getSelectCourses($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT cour.courseCode,cour.courseId, cour.courseName, cour.ectsCredits from coursespereducperinst as cour
               join educationperinstitute as ed on ed.educationPerInstId=cour.educationPerInstId
               where cour.educationPerInstId=(SELECT ers.educationPerInstId from erasmusstudent as ers where ers.studentId='" . $db->escape($stId) . "')
               and cour.courseId not in (select g.courseId from grades as g where 
               g.studentId='" . $db->escape($stId) . "'
               and g.localGrade is NULL OR g.localGrade>=(
                SELECT scale from institutions where instId=(
                select hostInstitutionId from erasmusstudent where studentId='" . $db->escape($stId) . "')))   ");
        return $stInfo;
    }

    public static function getCourseEcts($courseId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT cour.ectsCredits from coursespereducperinst as cour
               where cour.courseId='" . $db->escape($courseId) . "'
                   ");
        return $stInfo;
    }

    public static function getStudentEcts($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT ers.ectsCredits from erasmusstudent as ers
                where ers.studentId='" . $db->escape($stId) . "'");
        return $stInfo;
    }

    public static function getSelectedCourseEcts($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT cour.ectsCredits from coursespereducperinst as cour
               join grades as g on g.courseId=cour.courseId
               where g.studentId='" . $db->escape($stId) . "'
               and g.localGrade is NULL
                   ");
        return $stInfo;
    }

<<<<<<< HEAD
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
=======
    public static function courseRemove($courseId, $student) {
        $db = PlonkWebsite::getDB();
        $query = "
               DELETE FROM grades
               WHERE (courseId='" . $db->escape($courseId) . "'
               AND studentId='" . $db->escape($student) . "') ";
>>>>>>> c9bcc785e50c55798bc2fb155ac89a4604fd45b5
        $db->execute($query);
    }

    public static function courseAdd($courseId, $student) {
        $db = PlonkWebsite::getDB();

        /* Check if the course is in the db */
        $dub = $db->retrieve(" SELECT g.courseId,g.studentId from grades as g
            where g.courseId='" . $db->escape($courseId) . "'
            AND g.studentId='" . $db->escape($student) . "'");
        /* If not, it inserts it in the table */
        if (empty($dub)) {
            $query = "
               INSERT INTO grades (courseId,studentId)
               VALUES (" . $db->escape($courseId) . ", " . $db->escape($student) . ") ";
            $db->execute($query);
        }
        /* Else It makes it null */ else {
            $query = "UPDATE grades SET 
                localGrade = NULL,
                ectsGrade = NULL,
                courseDuration = NULL
                    WHERE studentId='" . $db->escape($student) . "'
                        AND courseId='" . $db->escape($courseId) . "'
                            ";

            $db->execute($query);
        }
    }

}

?>
