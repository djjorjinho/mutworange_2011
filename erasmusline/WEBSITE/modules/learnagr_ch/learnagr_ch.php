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
        'submitchange', 'add', 'remove'
    );
    private $errors = '';


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

        //Gets User Id
        $stId = PlonkSession::get('id');

        //Assigns User Information
        $stuInfo = learnagr_chDB::getStInfo($stId);


        $selcourEcts = learnagr_chDB::getSelectedCourseEcts($stId);
        $studentEcts = learnagr_chDB::getStudentEcts($stId);
        $i = 0;
        while (isset($selcourEcts[$i]['ectsCredits'])) {
            $studentEcts[0]['ectsCredits']-=$selcourEcts[$i]['ectsCredits'];
            $i++;
        }
        $this->pageTpl->assign('ECTS', $studentEcts[0]['ectsCredits']);

        if (empty($stuInfo)) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=formselect&' . PlonkWebsite::$viewKey . '=main');
        }


        $this->pageTpl->assign('studentsName', $stuInfo[0]['familyName'] . '&nbsp;&nbsp;' . $stuInfo[0]['firstName']);
        $this->pageTpl->assign('instName', $stuInfo[0]['instName']);
        $this->pageTpl->assign('country', $stuInfo[0]['Name']);


        //Generates The Interactions For Courses (for Host Institution)

        $this->pageTpl->setIteration('iStudentCourses');
        $i = 1;

        $succeedCourses = learnagr_chDB::getSucceedCourses($stId);
        foreach ($succeedCourses as $key => $value) {
            $this->pageTpl->assignIteration('studentCourses', '
 <tr>
<td>' . $value['courseCode'] . ' </td><td>' . $value['courseName'] . '</td><td>'
                    . $value['ectsCredits'] . '</td><td><span class"succeedCourse">Succeed</span></td>');
            $this->pageTpl->refillIteration('iStudentCourses');
        }

        $selectedCourses = learnagr_chDB::getSelectedCourses($stId);
        foreach ($selectedCourses as $key => $value) {
            $this->pageTpl->assignIteration('studentCourses', '
<tr>
<td>' . $value['courseCode'] . '</td>
<td>' . $value['courseName'] . '</td>
<td>' . $value['ectsCredits'] . '</td>
<td><form id="form1"  method="post">
<input type="hidden" name="coursetitle" value="' . $value['courseId'] . '" />    
<input type="hidden" name="formAction" id="formValidate" value="doRemove" />
<input class="button" name="postForm" id="postForm" type="submit" value="Remove"/>
</form></td></tr>');
            $this->pageTpl->refillIteration('iStudentCourses');
        }

        $selectCourses = learnagr_chDB::getSelectCourses($stId);
        foreach ($selectCourses as $key => $value) {
            $this->pageTpl->assignIteration('studentCourses', '
 <tr>
<td>' . $value['courseCode'] . ' </td>
<td>' . $value['courseName'] . '</td>
<td>' . $value['ectsCredits'] . '</td>
<td><form id="form1" name="form1" method="post">
<input type="hidden" name="coursetitle" value="' . $value['courseId'] . '" />    
<input type="hidden" name="formAction" id="formValidate" value="doAdd" />
            <input class="button" name="postForm" id="postForm" type="submit" value="Add"/></form></td></tr>');
            $this->pageTpl->refillIteration('iStudentCourses');
        }

        $this->pageTpl->parseIteration('iStudentCourses');
    }

    public function doAdd() {
        $stId = PlonkSession::get('id');
        $courseId = $_POST['coursetitle'];
        /* Validates For the ECTS CREDITS */
        $selcourEcts = learnagr_chDB::getSelectedCourseEcts($stId);
        $studentEcts = learnagr_chDB::getStudentEcts($stId);
        $courseEcts = learnagr_chDB::getCourseEcts($courseId);
        $i = 0;
        while (isset($selcourEcts[$i]['ectsCredits'])) {
            $studentEcts[0]['ectsCredits']-=$selcourEcts[$i]['ectsCredits'];
            $i++;
        }
        $ects = $studentEcts[0]['ectsCredits'] - $courseEcts[0]['ectsCredits'];
        if ($ects < 0) {
            $this->errors = '<div class="errorPHP">Not Enough ECTS Credits</div>';
        } else {
            /* Checks If The course Can Selected */
            $coursesSel = learnagr_chDB::getSelectedCourses($stId);
            $coursesSelected = learnagr_chDB::getSelectCourses($stId);

            if (($this->checkSelection($coursesSel, $courseId)) || ($this->checkSelection($coursesSelected, $courseId))) {
                learnagr_chDB::courseAdd($courseId, $stId);
                $this->errors = '<div class="SuccessPHP">Successfully Added</div>';
            } else {
                $this->errors = '<div class="errorPHP">You Are not allowed To select this course</div>';
            }
        }
    }

    public function doRemove() {
        $stId = PlonkSession::get('id');
        $coursesSel = learnagr_chDB::getSelectedCourses($stId);
        $coursesSelected = learnagr_chDB::getSelectCourses($stId);
        $courseId = $_POST['coursetitle'];

        if (($this->checkSelection($coursesSel, $courseId)) || ($this->checkSelection($coursesSelected, $courseId))) {
            learnagr_chDB::courseRemove($_POST['coursetitle'], $stId);
            $this->errors = '<div class="SuccessPHP">Successfully Removed</div>';
        } else {
            $this->errors = '<div class="errorPHP">You Are not allowed To select this course</div>';
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