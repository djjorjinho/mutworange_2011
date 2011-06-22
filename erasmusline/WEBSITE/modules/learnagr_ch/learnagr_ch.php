<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class learnagr_chController extends PlonkController {

    protected $views = array(
        'learnagrch',
    );
    protected $actions = array(
        'submitchange', 'submit', 'selectuser', 'coor'
    );
    private $errors = '';
    private $mail = '';
    private $position = '1';
    private $student = '';



    /* For Using this PlonkSession::get('id'); must be an id of the student */

    public function checkLogged() {
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                $this->pageTpl->assignOption('oStudent');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
            }
        }
    }

    public function showlearnagrch() {
        //$this->checkLogged();
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/css/Style.css" type="text/css"  media="screen"/>');
        $this->mainTpl->assign('pageJava', '
<script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
<script type="text/javascript" src="./core/js/Custom/custom.js"></script>');
        $this->mainTpl->assign('siteTitle', 'Learning Agreement Change');
        $this->pageTpl->assign('errorString', $this->errors);
        $this->errors = '';
        if (PlonkSession::get('userLevel') == 'Student') {
            $x = learnagr_chDB::checkRecords();
            if (!empty($x)) {
                if ($x[0]['action'] == '2') {
                    $this->pageTpl->assignOption('act2');
                    $this->pageTpl->assignOption('oPending');
                    $this->student = $x[0]['formId'];
                    $this->fillData2('');
                }
                if ($x[0]['action'] == '1') {
                                        $this->pageTpl->assignOption('student');
                    $this->pageTpl->assignOption('oApproved');
                    $this->fillData('main');
                }
                if ($x[0]['action'] == '0') {
                                        $this->pageTpl->assignOption('student');

                    $this->pageTpl->assignOption('oDenied');
                    $this->fillData('main');
                }
                $this->pageTpl->assign('button', '');
            } else {
                
                $this->pageTpl->assignOption('student');
                $this->fillData('main');
            }
        }
        if (PlonkSession::get('userLevel') == 'Erasmus Coordinator') {



            if ($this->position == '1') {
                $this->pageTpl->assignOption('act1');

                $studentsLName = learnagr_chDB::getForms();
                $this->pageTpl->setIteration('iStudentsList');
                foreach ($studentsLName as $key => $value) {
                    $this->pageTpl->assignIteration('studentsList', '<tr>
                <td> ' . $value['userId'] . '</td><td> ' . $value['firstName'] .
                            '</td><td> ' . $value['familyName'] . '</td>
                        <td><form  method="post">
                        <input type="hidden" name="stn" value="' . $value['formId'] . '" />
                            <input type="hidden" name="formAction" id="formLogin" value="doSelectUser" />
                            <input class="nxtBtn" type="submit" value=">"/></form></td></tr>');
                    $this->pageTpl->refillIteration('iStudentsList');
                }
                $this->pageTpl->parseIteration('iStudentsList');
            }

            if ($this->position == '2') {
                $this->pageTpl->assignOption('act2');
                $this->pageTpl->assign('button', '<div class="alCenterDiv" style="padding-top: 30px;">
                    <select name="mot"><option value="1">Approve</option><option value="2">Deny</option></select>
                    <input type="hidden" name="form" value="{$formIdd}" />
                    <input type="hidden" name="formAction" id="formValidate" value="doCoor" />
                    <input class="button" name="postForm" id="postForm" type="submit" value="Submit"/></p></div>');
                $this->fillData2('1');
                $this->pageTpl->assign('formIdd', $this->student);
            }
        }
    }

    public function doSelectUser() {
        $this->student = $_POST["stn"];
        $this->position = '2';
    }

    private function fillData2($x) {

        //Gets User Id
        if ($x == '1') {
            $form = learnagr_chDB::getForm($this->student);
        } else {
            $form = learnagr_chDB::getFormSTUDENT($this->student);
        }
        $this->pageTpl->setIteration('iSCourses');

        $stId = $form[0]['email'];

        //Assigns User Information
        $stuInfo = learnagr_chDB::getStInfo($stId);
        $selcourEcts = learnagr_chDB::getSelectedCourseEcts($stId);
        $studentEcts = learnagr_chDB::getStudentEcts($stId);
        $remEcts = $studentEcts[0]['ectsCredits'];
        $i = 0;
        while (isset($selcourEcts[$i]['ectsCredits'])) {
            $remEcts-=$selcourEcts[$i]['ectsCredits'];
            $i++;
        }
        $this->pageTpl->assign('ECTS', $remEcts);
        $this->pageTpl->assign('ECTStot', $studentEcts[0]['ectsCredits']);

        if (empty($stuInfo)) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=formselect&' . PlonkWebsite::$viewKey . '=main');
        }


        $this->pageTpl->assign('studentsName', $stuInfo[0]['familyName'] . '&nbsp;&nbsp;' . $stuInfo[0]['firstName']);
        $this->pageTpl->assign('instName', $stuInfo[0]['instName']);
        $this->pageTpl->assign('country', $stuInfo[0]['Name']);

        $selectCourses = json_decode($form[0]['content'], true);
        $i = 1;

        foreach ($selectCourses as $key => $value2) {
            $course = learnagr_chDB::getCourse($value2);
            foreach ($course as $key => $value) {
                $this->pageTpl->assignIteration('sCourses', '
 <tr>
<td>' . $value['courseCode'] . ' </td>
<td>' . $value['courseName'] . '</td>
<td id="ects' . $i . '">' . $value['ectsCredits'] . '</td></tr>');
                $this->pageTpl->refillIteration('iSCourses');
                $i++;
            }
        }

        $this->pageTpl->parseIteration('iSCourses');
    }

    public function doCoor() {
        $flag = learnagr_chDB::submitCoor($_POST['form']);

        if ($flag == '1') {

            $form = learnagr_chDB::getFormtoW($_POST['form']);
            $selectCourses = json_decode($form[0]['content'], true);
            $stId = $form[0]['email'];
            learnagr_chDB::courseRemove($stId);
            foreach ($selectCourses as $key => $value)
                learnagr_chDB::courseAdd($value, $stId);
        }

           
    }

    private function fillData($pos) {

        if ($pos == 'mail') {
            $this->pageTpl = $this->mail;
        }
        //Gets User Id
        $stId = PlonkSession::get('id');

        //Assigns User Information
        $stuInfo = learnagr_chDB::getStInfo($stId);
        $selcourEcts = learnagr_chDB::getSelectedCourseEcts($stId);
        $studentEcts = learnagr_chDB::getStudentEcts($stId);
        $remEcts = $studentEcts[0]['ectsCredits'];
        $i = 0;
        while (isset($selcourEcts[$i]['ectsCredits'])) {
            $remEcts-=$selcourEcts[$i]['ectsCredits'];
            $i++;
        }
        $this->pageTpl->assign('ECTS', $remEcts);
        $this->pageTpl->assign('ECTStot', $studentEcts[0]['ectsCredits']);

        if (empty($stuInfo)) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=formselect&' . PlonkWebsite::$viewKey . '=main');
        }


        $this->pageTpl->assign('studentsName', $stuInfo[0]['familyName'] . '&nbsp;&nbsp;' . $stuInfo[0]['firstName']);
        $this->pageTpl->assign('instName', $stuInfo[0]['instName']);
        $this->pageTpl->assign('country', $stuInfo[0]['Name']);


        //Generates The Interactions For Courses (for Host Institution)

        $this->pageTpl->setIteration('iStudentCourses');
        $succeedCourses = learnagr_chDB::getSucceedCourses($stId);
        foreach ($succeedCourses as $key => $value) {
            $this->pageTpl->assignIteration('studentCourses', '
 <tr>
<td>' . $value['courseCode'] . ' </td><td>' . $value['courseName'] . '</td><td>'
                    . $value['ectsCredits'] . '</td><td><span class"succeedCourse">Succeed</span></td></tr>');
            $this->pageTpl->refillIteration('iStudentCourses');
        }

        $selectedCourses = learnagr_chDB::getSelectedCourses($stId);
        $i = 1;
        foreach ($selectedCourses as $key => $value) {
            $this->pageTpl->assignIteration('studentCourses', '
<tr>
<td>' . $value['courseCode'] . '</td>
<td>' . $value['courseName'] . '</td>
<td id="ects' . $i . '">' . $value['ectsCredits'] . '</td>
<td><input onclick="getEcts(' . $i . ')" id="course' . $i . '" type="checkbox" name="coursetitle' . $i . '" value="' . $value['courseId'] . '" checked /></td></tr>');
            $this->pageTpl->refillIteration('iStudentCourses');
            $i++;
        }

        $selectCourses = learnagr_chDB::getSelectCourses($stId);
        foreach ($selectCourses as $key => $value) {
            $this->pageTpl->assignIteration('studentCourses', '
 <tr>
<td>' . $value['courseCode'] . ' </td>
<td>' . $value['courseName'] . '</td>
<td id="ects' . $i . '">' . $value['ectsCredits'] . '</td>
<td><input onclick="getEcts(' . $i . ')" id="course' . $i . '" type="checkbox" name="coursetitle' . $i . '" value="' . $value['courseId'] . '" /></td></tr>');
            $this->pageTpl->refillIteration('iStudentCourses');
            $i++;
        }

        $this->pageTpl->parseIteration('iStudentCourses');
    }

    public function doSubmit() {
        $flag = FALSE;
        $stId = PlonkSession::get('id');
        unset($_POST['formAction'], $_POST['postForm']);
        /* Validation */
        /*  Calculate the Selected ECTS Credits */
        $sum = 0;
        $studentEcts = learnagr_chDB::getStudentEcts($stId);
        foreach ($_POST as $key => $value) {
            $selcourEcts = learnagr_chDB::getCourseEcts($value);
            $sum+=$selcourEcts[0]['ectsCredits'];
        }
        if (($studentEcts[0]['ectsCredits'] - $sum) < 0) {
            $this->errors = '<div class="errorPHP">Not Enough ECTS Credits</div>';
        } else {
            /* If the selected courses CAN Be selected */
            $coursesSel = learnagr_chDB::getSelectedCourses($stId);
            $coursesSelected = learnagr_chDB::getSelectCourses($stId);
            foreach ($_POST as $key => $value) {
                if (($this->checkSelection($coursesSel, $value)) || ($this->checkSelection($coursesSelected, $value))) {
                    $flag = TRUE;
                } else {
                    $flag = FALSE;
                    $this->errors = '<div class="errorPHP">You Are not allowed To select this course</div>';
                }
            }
        }
        if ($flag) {
            learnagr_chDB::SubmitTranscript($_POST);
            $this->errors = '<div class="SuccessPHP">Changes made Successfully</div>';
        }
    }

    private function checkSelection($courseList, $course) {
        $i = 0;
        while (isset($courseList[$i]['courseId'])) {
            if (($course == $courseList[$i]['courseId']))
                return TRUE;
            $i++;
        }
        return FALSE;
    }

}