<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "./modules/infox/infox.php";

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
               and (localGrade IS NULL OR localGrade<'" . (int) $scale[0]['scale'] . "')";
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
    public static function SubmitTranscript($post) {
        $db = PlonkWebsite::getDB();
        
        $formTable = json_encode($post);
        $formId = Functions::createRandomString();
        $student = PlonkSession::get('id');
        $date = date("y-m-d");
        $query = "
               DELETE FROM forms
               WHERE studentId='" . $db->escape($student) . "'
               and erasmusLevelId=13";
        $db->execute($query);
        
        $query = "INSERT INTO forms (formId,type,date,content,studentId,erasmusLevelId) VALUES( '" . $db->escape($formId) . "','Learning Agreement Change','" . $db->escape($date) . "','" . $db->escape($formTable) . "','" . $db->escape($student) . "','13') ";
        $db->execute($query);
    }

    public static function checkRecords() {
        $db = PlonkWebsite::getDB();
        $stId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select f.date,f.action,f.formId,u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                join forms as f on f.studentId=ers.users_email
                where f.erasmusLevelId=13
                and f.studentId='" . $db->escape($stId) . "'
                ORDER BY f.date DESC");
        return $stName;
    }

    public static function getForms() {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select f.formId,u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                join forms as f on f.studentId=ers.users_email
                where (ers.hostCoordinatorId='$cordId' OR ers.homeCoordinatorId='$cordId')
                AND f.erasmusLevelId=13
                AND f.action=2
                AND (f.motivationHome is NULL or f.motivationHost is NULL)
                ORDER BY u.familyName ASC ");
        return $stName;
    }

    public static function getForm($id) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select f.content,u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                join forms as f on f.studentId=ers.users_email
                where (ers.hostCoordinatorId='$cordId' OR ers.homeCoordinatorId='$cordId')
                AND f.erasmusLevelId=13
                AND f.action=2
                AND (f.motivationHome is NULL or f.motivationHost is NULL)
                AND f.formId='" . $db->escape($id) . "'
                ORDER BY u.familyName ASC ");
        return $stName;
    }

    public static function getFormSTUDENT($id) {
        $db = PlonkWebsite::getDB();
        $stId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select f.content,u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                join forms as f on f.studentId=ers.users_email
                where f.studentId='" . $db->escape($stId) . "'
                AND f.erasmusLevelId=13
                AND f.formId='" . $db->escape($id) . "'
                ORDER BY u.familyName ASC ");
        return $stName;
    }

    public static function getFormtoW($id) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select f.content,u.email,u.userId,u.firstName,u.familyName from users as u
                join erasmusstudent as ers on u.email=ers.users_email
                join forms as f on f.studentId=ers.users_email
                where (ers.hostCoordinatorId='$cordId' OR ers.homeCoordinatorId='$cordId')
                AND f.erasmusLevelId=13
                AND f.action=1
                AND (f.motivationHome=1 AND f.motivationHost=1)
                AND f.formId='" . $db->escape($id) . "'");
        return $stName;
    }

    public static function getCourse($id) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $course = $db->retrieve("
                select * from coursespereducperinst as c
                where courseId='" . $db->escape($id) . "'");
        return $course;
    }

    public static function submitCoor($f) {
        $db = PlonkWebsite::getDB();
        $cordId = PlonkSession::get('id');
        $stName = $db->retrieve("
                select ers.homeCoordinatorId, ers.hostCoordinatorId, ers.users_email from erasmusstudent as ers
                join forms as f on f.studentId=ers.users_email
                where (ers.hostCoordinatorId='$cordId' OR ers.homeCoordinatorId='$cordId')
                AND f.erasmusLevelId=13
                AND (f.motivationHome is NULL or f.motivationHost is NULL)
                AND f.formId='" . $db->escape($f) . "'");

        
        
        if ($stName[0]['homeCoordinatorId'] == $cordId) {
            $query = "UPDATE forms SET 
                motivationHome='" . $db->escape($_POST['mot']) . "'     
                WHERE formId='" . $db->escape($f) . "'
                            ";

            $db->execute($query);
            learnagr_chDB::sendInfox('hostInstitutionId');
        }

        if ($stName[0]['hostCoordinatorId'] == $cordId) {
            $query = "UPDATE forms SET 
                motivationHost='" . $db->escape($_POST['mot']) . "'     
                WHERE formId='" . $db->escape($f) . "'
                            ";
            $db->execute($query);
            learnagr_chDB::sendInfox('homeInstitutionId');
        }

        $stName2 = $db->retrieve("
                select motivationHost,motivationHome from forms as f
                WHERE f.erasmusLevelId=13
                AND f.formId='" . $db->escape($f) . "'");

        if (($stName2[0]['motivationHost'] == '1') && ($stName2[0]['motivationHome'] == '1')) {
            $query = "UPDATE forms SET 
                action=1
                WHERE formId='" . $db->escape($f) . "'
                            ";
            $db->execute($query);
            if ($stName[0]['hostCoordinatorId'] == $cordId) {
                learnagr_chDB::sendInfox('homeInstitutionId');
            } else {
                learnagr_chDB::sendInfox('hostInstitutionId');
            }
            return '1';
        } else if (($stName2[0]['motivationHost'] == '2') || ($stName2[0]['motivationHome'] == '2')) {
            $query = "UPDATE forms SET 
                action=0
                WHERE formId='" . $db->escape($f) . "'
                            ";
            $db->execute($query);
            if ($stName[0]['hostCoordinatorId'] == $cordId) {
                learnagr_chDB::sendInfox('homeInstitutionId');
            } else {
                learnagr_chDB::sendInfox('hostInstitutionId');
            }
        }
    }

    public static function updateErasmusStudent($table, $values, $where) {
        $db = PlonkWebsite::getDB();

        $true = $db->update($table, $values, $where);
    }

    public static function getFormLOL($id) {
        $db = PlonkWebsite::getDB();

        $form = $db->retrieveOne("select * from forms where formId = '" . $id . "'");

        return $form;
    }

    public function sendInfox($where) {
        $form = learnagr_chDB::getFormLOL($_POST['form']);

        try {


            $er = array(
                'table' => 'erasmusstudent',
                'data' => array('statusOfErasmus' => 'Change of Learning Agreement'),
                'emailField' => 'users_email'
            );
            $form = array(
                'table' => 'forms',
                'data' => $form,
                'emailField' => 'formId'
            );

            $b = new InfoxController;
            //$b->TransferBelgium($jsonStringUser, $hostInst['instId']);
            $methods = array('forms:toDb', 'forms:toDb');
            $tables = array('erasmusstudent', 'forms');
            $data = array($er, $form);
            $idInst = $erasmus['hostInstitutionId'];
            $success = $b->dataTransfer($methods, $tables, $data, $idInst);
        } catch (Exception $e) {
            Plonk::dump('failed');
        }
    }

}

?>