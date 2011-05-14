<?php

class trrecDB {
    /* FOR REGISTER */

    //Get Values For Students List (Changes Done)
    public static function getStudentList($num) {

        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                where ers.users_email in (select g.studentId from grades as g where localGrade is NULL)
                AND ers.hostCoordinatorId='$cordId'
                ORDER BY u.familyName ASC LIMIT 20 OFFSET " . $db->escape($num) . "  ");
        return $stName;
    }

    //checks for DubliCates Entries in DB 
    public static function checkDubl($name, $course) {
        $db = PlonkWebsite::getDB();
        $query = $db->retrieve("SELECT studentId FROM grades
            where studentId ='" . $db->escape($name) . "'
                and courseId='" . $db->escape($course) . "'
             and localGrade is not NULL");
        if (!empty($query)) {
            return 'Dub';
        }
    }

    //Register Student Records (changes done)
    public static function formAp($name) {
        $db = PlonkWebsite::getDB();
        $i = 1;
        while (!empty($_POST['coursetitle' . $i])) {

            $course = $_POST['coursetitle' . $i];
            $locGrade = $_POST['locGrade' . $i];
            $ectsGrade = $_POST['ecGrade' . $i];
            $corDur = $_POST['corDur' . $i];


            $query = "UPDATE grades SET 
                localGrade = '" . $db->escape($locGrade) . "',
                ectsGrade = '" . $db->escape($ectsGrade) . "',
                courseDuration = '" . $db->escape($corDur) . "'             
                WHERE studentId='" . $db->escape($name) . "'
                        AND courseId='" . $db->escape($course) . "'
                            ";

            $db->execute($query);
            $i++;
        }
        unset($_POST['num'], $_POST['formAction'], $_POST['postForm']);
        $formTable = json_encode($_POST);
        $date = date("y-m-d");
        $query2 = "INSERT INTO forms (type,date,content,studentId,erasmusLevelId) VALUES( 'TranScript Of Records','" . $db->escape($date) . "','" . $db->escape($formTable) . "','" . $db->escape($name) . "','14') ";
        $db->execute($query2);
        $formId = $db->retrieve("Select f.formId from forms as f 
           where f.studentId='" . $db->escape($name) . "'
               and f.type='TranScript Of Records'
               ORDER BY f.formId DESC");
        return $formId[0]['formId'];
    }

    //Sends Email (changes done)
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
        $mail->Username = "stvakis@gmail.com";
        $mail->Password = "";
        $mail->SetFrom('stvakis@gmail.com', 'Erasmus Line');
        $mail->FromName = "Erasmus Line";
        $mail->AddAddress($homeCoorMail[0]['email']);
        $mail->Subject = "Transcript Of Records";
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
        $return = $db->retrieve("
                SELECT f.content
                FROM forms as f
               where f.formId ='" . $db->escape($userCours) . "'");
        $re = json_decode($return[0]['content'], true);

        return $re;
    }

    public static function getCoursName($courseId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
                SELECT cour.courseCode, cour.courseName, cour.ectsCredits
                FROM coursespereducperinst as cour
                where cour.courseId ='" . $db->escape($courseId) . "'");
        return $stInfo;
    }

    public static function getSelCourses($userCours) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("
                SELECT cour.courseId,cour.courseCode, cour.courseName, cour.ectsCredits
                FROM coursespereducperinst as cour, grades as g
                where g.studentId ='" . $db->escape($userCours) . "'
                AND cour.courseId=g.courseId
                AND g.localGrade is NULL");

        return $stInfo;
    }

    //Get Values For Students List (done)
    public static function getStudentRecords($num) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');

        $stName = $db->retrieve("
                select u.email,f.formId,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                left join forms as f on ers.users_email=f.studentId
                where f.type='TranScript Of Records'
                and erasmusLevelId='14'
                and ers.hostCoordinatorId='" . $db->escape($cordId) . "'
                ORDER BY date DESC LIMIT 20 OFFSET " . $db->escape($num) . " ");

        return $stName;
    }

    /* COMMON */

    //Fills tpl withs Student Info (changes Done)
    public static function getStudentInfo($matrNum) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT u.userId,ers.startDate,ers.homeCoordinatorId,ers.hostCoordinatorId,ers.homeInstitutionId,ers.hostInstitutionId,u.firstName, u.familyName, u.sex,u.tel,u.email,u.birthDate,u.birthPlace FROM users as u 
                JOIN erasmusstudent as ers on u.email = ers.users_email where u.email ='" . $db->escape($matrNum) . "'");


        return $stInfo;
    }

    //Fills tpl with Cordinator (changes Done)
    public static function getCoordInfo($coorId) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT u.fax, u.firstName, u.familyName, u.sex,u.tel,u.email FROM users as u where u.email ='" . $db->escape($coorId) . "'");

        return $stInfo;
    }

    //Fille tpl with Institution Info (changes Done)
    public static function getInstInfo($instEmail) {
        $db = PlonkWebsite::getDB();
        $stInfo = $db->retrieve("SELECT instName FROM institutions where  instEmail='" . $db->escape($instEmail) . "'");

        return $stInfo;
    }

    //Search Engine for Students
    public static function getSearch($SearchFor, $SearchValue, $where) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');


        if ($where == 'register') {
            $stInfo = $db->retrieve("select u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                where ers.users_email in (select g.studentId from grades as g where localGrade is NULL)
                AND " . $db->escape($SearchFor) . " like '%" . $db->escape($SearchValue) . "%'
                and ers.hostCoordinatorId='" . $db->escape($cordId) . "'
");
        }
        if ($where == 'records') {
            $stInfo = $db->retrieve("select u.email,f.formId,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                left join forms as f on ers.users_email=f.studentId
                where f.type='TranScript Of Records'
                AND f.erasmusLevelId='14'
                AND " . $db->escape($SearchFor) . " like '%" . $db->escape($SearchValue) . "%'
                and ers.hostCoordinatorId='" . $db->escape($cordId) . "'
");
        }

        return $stInfo;
    }

}

?>