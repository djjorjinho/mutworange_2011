<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class learnagr_chDB {

    /* Gets Student Info (DONE) */
    public static function getStInfo($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT u.firstName,u.familyName,ins.instName,c.Name from users as u
               join institutions as ins on ins.instEmail=(SELECT hostInstitutionId from erasmusstudent as ers where ers.users_email='" . $db->escape($stId) . "')
               left join country as c on c.Code=ins.instCountry 
                where u.email='" . $db->escape($stId) . "'");
        return $stInfo;
    }
    
    
    /* Gets Student Succeed Courses (DONE) */
    public static function getSucceedCourses($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT cour.courseCode,cour.courseId, cour.courseName, cour.ectsCredits from coursespereducperinst as cour
               join educationperinstitute as ed on ed.educationPerInstId=cour.educationId
               join grades as g on cour.courseId=g.courseId
               where cour.educationId=(SELECT ers.educationPerInstId from erasmusstudent as ers where ers.users_email='" . $db->escape($stId) . "')
               and g.localGrade>=(
                SELECT scale from institutions where instEmail=(
                select hostInstitutionId from erasmusstudent as ers where ers.users_email='" . $db->escape($stId) . "'))
               and g.studentId='" . $db->escape($stId) . "'");
        return $stInfo;
    }

    public static function getSelectedCourses($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT cour.courseCode,cour.courseId, cour.courseName, cour.ectsCredits from coursespereducperinst as cour
               join educationperinstitute as ed on ed.educationPerInstId=cour.educationId
               right join grades as g on cour.courseId=g.courseId
               where cour.educationId=(SELECT ers.educationPerInstId from erasmusstudent as ers where ers.users_email='" . $db->escape($stId) . "')
               and g.localGrade is NULL
               and g.studentId='" . $db->escape($stId) . "'");
        return $stInfo;
    }

    public static function getSelectCourses($stId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT cour.courseCode,cour.courseId, cour.courseName, cour.ectsCredits from coursespereducperinst as cour
               join educationperinstitute as ed on ed.educationPerInstId=cour.educationId
               where cour.educationId=(SELECT ers.educationPerInstId from erasmusstudent as ers where ers.users_email='" . $db->escape($stId) . "')
               and cour.courseId not in (select g.courseId from grades as g where 
               g.studentId='" . $db->escape($stId) . "'
               and (g.localGrade is NULL OR g.localGrade>=(
                SELECT scale from institutions where instEmail=(
                select hostInstitutionId from erasmusstudent as ers where ers.users_email='" . $db->escape($stId) . "'))) )  ");
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
                where ers.users_email='" . $db->escape($stId) . "'");
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

    public static function courseRemove($student) {
        $db = PlonkWebsite::getDB();
        $scale = $db->retrieve("
        SELECT scale from institutions where instEmail=(
        select hostInstitutionId from erasmusstudent as ers where ers.users_email='" . $db->escape($student) . "')
         ");
        
        $query = "
               DELETE FROM grades
               WHERE studentId='" . $db->escape($student) . "'
               and (localGrade IS NULL OR localGrade<'".(int) $scale[0]['scale']."')";
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
               VALUES ('" . $db->escape($courseId) . "', '" . $db->escape($student) . "') ";
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

    

    //Sends Email (changes done)
     public static function SubmitTranscript($form, $post) {
    $student=  PlonkSession::get('id');
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
        $mail->Subject = "Learning Agreement Change";
        $mail->Body = $form;
        $mail->IsHTML(true);
        $mail->SMTPDebug = false;
        $mail->do_debug = 0;
        if (!$mail->Send()) {
            return $mail->ErrorInfo;
        } else {
                       
                    $formTable = json_encode($post);
        $date = date("y-m-d");
        $query = "INSERT INTO forms (type,date,content,studentId,erasmusLevelId) VALUES( 'Learning Agreement Change','" . $db->escape($date) . "','" . $db->escape($formTable) . "','" . $db->escape($student) . "','13') ";
        $db->execute($query);
        return '1';

        }
    }
}

?>