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
        'submitchange', 'submit'
    );
    private $errors = '';
    private $mail = '';


    /* For Using this PlonkSession::get('id'); must be an id of the student */

    public function showlearnagrch() {
        $this->mainTpl->assign('pageCSS', '<link rel="stylesheet" href="core/css/validationEngine.jquery.css" type="text/css"/>');
        $this->mainTpl->assign('pageMeta', '<script src="core/js/jquery-1.5.1.min.js" type="text/javascript"></script>
        <script src="core/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"> </script>
        <script src="core/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
        <script src="core/js/custom.js" type="text/javascript" charset="utf-8"> </script>');
        $this->mainTpl->assign('pageTitle', 'Learning Agreement Change');
        $this->pageTpl->assign('errorString', $this->errors);
        $this->errors = '';
        $this->fillData('main');
    }

    public function fillData($pos) {

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

    public function formSend($post) {
        $name = PlonkSession::get('id');
        $this->mail = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/confirm.tpl');
        $this->fillData('mail');
        $send = learnagr_chDB::SubmitTranscript($this->mail->getContent(), $post);
        if ($send == '1') {
            return TRUE;
        } else {
            $this->errors = '<div class="errorPHP">' . $send . '</div>';
            return FALSE;
        }
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

            learnagr_chDB::courseRemove($stId);
            foreach ($_POST as $key => $value)
                learnagr_chDB::courseAdd($value, $stId);
            if ($this->formSend($_POST)) {
                $this->errors = '<div class="SuccessPHP">Changes made Successfully</div>';
            }
        }
    }

    public function checkSelection($courseList, $course) {
        $i = 0;
        while (isset($courseList[$i]['courseId'])) {
            if (($course == $courseList[$i]['courseId']))
                return TRUE;
            $i++;
        }
        return FALSE;
    }

}