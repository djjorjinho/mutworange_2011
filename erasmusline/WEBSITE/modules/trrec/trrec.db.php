<?php

class trrecDB {
    /* FOR REGISTER */

    //Get Values For Students List
    public static function getStudentList($num) {

        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.userId=ers.studentId
                where ers.studentId not in (select studentId from grades)
                and ers.hostCoordinatorId='$cordId'

                ORDER BY familyName ASC LIMIT 1 OFFSET ".$db->escape($num) ."  ");
        return $stName;
    }

    //checks for DubliCates Entries in DB 
    public static function checkDubl($name) {
        $db = PlonkWebsite::getDB();
        $query = $db->retrieve("SELECT studentId FROM grades where studentId ='".$db->escape($name)."'");
        if (!empty($query)) {
            return 'Dub';
        }
    }

    //Register Student Records
    public static function formAp($name) {


        $db = PlonkWebsite::getDB();

        $i = 1;
        while (!empty($_POST['coursetitle' . $i])) {

            $course = $_POST['coursetitle' . $i];
            $locGrade = $_POST['locGrade' . $i];
            $ectsGrade = $_POST['ecGrade' . $i];
            $corDur = $_POST['corDur' . $i];


            $query = "INSERT INTO grades (courseId, studentId,localGrade,ectsGrade,courseDuration) VALUES('".$db->escape($course)."', '".$db->escape($name)."','".$db->escape($locGrade)."','".$db->escape($ectsGrade)."','".$db->escape($corDur)."') ";
            
            $db->execute($query);
            $i++;
        }
    }

    //Fille tpl with Institution Courses
    public static function getInstCourses($instId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
               SELECT cour.courseCode,cour.courseId, cour.courseName, cour.ectsCredits from coursespereducperinst as cour
               join educationperinstitute as ed on ed.educationPerInstId=cour.educationPerInstId
               where ed.institutionId='".$db->escape($instId)."'");

        return $stInfo;
    }

    public static function SubmitTranscript($form,$student) {

        $db = PlonkWebsite::getDB();
        $homeCoorMail = $db->retrieve("SELECT u.email FROM users as u where u.userId =(
            SELECT ers.homeCoordinatorId FROM erasmusstudent as ers where ers.studentId='".$db->escape($student)."') ");
        
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
        $mail->Subject = "Transscript Of Records";
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

    /* FOR VIEW */

    //Fills mainrecords.tpl withs Student Records
    public static function getRecords($userCours) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
                SELECT cour.courseCode, cour.courseName, cour.ectsCredits, g.localGrade,g.ectsGrade,g.courseDuration
                FROM coursespereducperinst as cour, grades as g
                where g.studentId ='".$db->escape($userCours)."'
                AND cour.courseId=g.courseId");

        return $stInfo;
    }

    //Get Values For Students List 
    public static function getStudentRecords($num) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');

        $stName = $db->retrieve("
                                select u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.userId=ers.studentId
                where ers.studentId in (select studentId from grades)
                                and ers.hostCoordinatorId='".$db->escape($cordId)."'

                ORDER BY familyName ASC LIMIT 1 OFFSET ".$db->escape($num) ." ");

        return $stName;
    }

    /* COMMON */

    //Fills tpl withs Student Info
    public static function getStudentInfo($matrNum) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT ers.homeCoordinatorId,ers.hostCoordinatorId,ers.homeInstitutionId,ers.hostInstitutionId,u.firstName, u.familyName, u.sex,u.tel,u.email,u.birthDate,u.birthPlace FROM users as u 
                JOIN erasmusstudent as ers on u.userId = ers.studentId where u.userId ='".$db->escape($matrNum)."'");


        return $stInfo;
    }

    //Fills tpl with Cordinator
    public static function getCoordInfo($coorId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT u.fax, u.firstName, u.familyName, u.sex,u.tel,u.email FROM users as u where u.userId ='".$db->escape($coorId)."'");

        return $stInfo;
    }

    //Fille tpl with Institution Info
    public static function getInstInfo($instId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT instName FROM institutions where  instId='".$db->escape($instId)."'");

        return $stInfo;
    }

    //Search Engine for Students
    public static function getSearch($SearchFor, $SearchValue, $where) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');


        if ($where == 'register') {
            $stInfo = $db->retrieve("select u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.userId=ers.studentId
                where ers.studentId not in (select studentId from grades)
                AND ".$db->escape($SearchFor)." like '%".$db->escape($SearchValue)."%'
                and ers.hostCoordinatorId='".$db->escape($cordId)."'
");
        }
        if ($where == 'records') {
            $stInfo = $db->retrieve("select u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.userId=ers.studentId
                where ers.studentId in (select studentId from grades)
                AND ".$db->escape($SearchFor)." like '%".$db->escape($SearchValue)."%'
                and ers.hostCoordinatorId='".$db->escape($cordId)."'
");
        }

        return $stInfo;
    }

}

?>