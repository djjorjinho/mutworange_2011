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
        'submitchange'
    );
    private $errors = '';
    private $fields = array();
    private $post = '';


    /* For Using this PlonkSession::get('id'); must be an id of the student */

    public function showlearnagrch() {
        
        $this->mainTpl->assign('pageCSS', '<link rel="stylesheet" href="core/css/validationEngine.jquery.css" type="text/css"/>');
        $this->mainTpl->assign('pageMeta', '<script src="./core/js/jquery/jquery-1.5.js" type="text/javascript"></script>
        <script src="./core/js/jquery/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"> </script>
        <script src="./core/js/jquery/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
        <script src="./core/js/custom.js" type="text/javascript" charset="utf-8"> </script>');
        $this->mainTpl->assign('pageTitle', 'Learning Agreement Change');
        $this->pageTpl->assign('errorString', $this->errors);
        $this->errors = '';

        //Gets User Id
        $stId = PlonkSession::get('id');

        //Assigns User Information
        $stuInfo = learnagr_chDB::getStInfo($stId);
        if (empty($stuInfo)) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=formselect&' . PlonkWebsite::$viewKey . '=main');
        }


        $this->pageTpl->assign('studentsName', $stuInfo[0]['familyName'] . '&nbsp;&nbsp;' . $stuInfo[0]['firstName']);
        $this->pageTpl->assign('instName', $stuInfo[0]['instName']);
        $this->pageTpl->assign('country', $stuInfo[0]['Name']);


        //Generates The Interactions For Courses (for Host Institution)
        $studentCourses = learnagr_chDB::getInstCourses($stId);
        $this->pageTpl->setIteration('iStudentCourses');
        foreach ($studentCourses as $key => $value) {
            $this->pageTpl->assignIteration('studentCourses', '<option value="' . $value['courseId'] . '" name="' . $value['ectsCredits'] . '">' . $value['courseCode'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $value['courseName'] . '</option> ');
            $this->pageTpl->refillIteration('iStudentCourses');
        }
        $this->pageTpl->parseIteration('iStudentCourses');
    }

    


    public function doSubmitchange() {

        $i = 1;
        $rules = array();
        $array = array();
        $errors = array();


        foreach ($_POST AS $key => $value) {

            if (stristr($key, 'coursetitle')) {

                $check = $_POST['coursetitle' . $i];
                if ((in_array($check, $array)) && ($i != 1)) {

                    $double = 'You have Dublicate Courses Selected';
                    $er = '1';
                }
                $array[] = $_POST['coursetitle' . $i];


                $rules[] = "required," . $key . ",Course Title " . $i . " is required.";
                $rules[] = "required,rad" . $i . ",Please Choise Add/Remove in field " . $i;



                $this->fields[] = 'coursetitle' . $i;
                $this->fields[] = 'rad' . $i;


                $i++;
            }
        }
        foreach ($_POST AS $key => $value) {
            $errors = validateFields($_POST, $rules);
        }
        if (!empty($errors)) {
            $er = '1';
        }



        if (!empty($er)) {
            $errorString = '<div class="errorPHP"> ';

            $errorString .= '<p><p>There was an error processing the form.</p>';
            if (!empty($double)) {
                $errorString .="<p>" . $double . "</p>";
            }
            if (!empty($errors)) {
                $errorString .= '<p>Please fill all fields marked with *</p>';
                $errorString .= '</div><div>';

                $errorString .= '<ul>';
                foreach ($errors as $key) {
                    $errorString .= "<li>$key</li>";
                }
                $errorString .= '</ul>';
            }

            $errorString .= '</div>';
            $er = '';
            // display the previous form */
            $this->errors = $errorString;
            $this->fields = $_POST;
        } else {
            $this->confirm($_POST);
}
    }
public function confirm($post) {

 $this->mail = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/confirm.tpl');
  //Gets User Id
        $stId = PlonkSession::get('id');
        
        //Assigns User Information
        $stuInfo = learnagr_chDB::getStInfo($stId);
        $this->mail->assign('studentsName', $stuInfo[0]['familyName'] . '&nbsp;&nbsp;' . $stuInfo[0]['firstName']);
        $this->mail->assign('instName', $stuInfo[0]['instName']);
        $this->mail->assign('country', $stuInfo[0]['Name']);

        //Gets The Post
        $this->post = $post;
        unset($this->post['formAction'], $this->post['num'], $this->post['postForm']);

        //Generates The Interactions For Courses (for Host Institution)
        $i = 1;

        $this->mail->setIteration('iStudentCoursesChange');
        foreach ($this->post as $key => $value) {

            if ((!empty($this->post['coursetitle' . $i])) && ($key == 'coursetitle' . $i)) {
                $course = learnagr_chDB::getConfCourses($this->post['coursetitle' . $i]);

                $this->mail->assignIteration('studentCoursesChange', '<tr><td align="center">' . $course[0]['courseCode'] . '</td>
                     <td align="center">' . $course[0]['courseName'] . '</td>
                     <td align="center">' . $course[0]['ectsCredits'] . '</td>
                     <td align="center">' . $this->post['rad' . $i] . '</td></tr>'
                );
                $this->mail->refillIteration('iStudentCoursesChange');

                $i++;
            }
        }
        $this->mail->parseIteration('iStudentCoursesChange');
$return = learnagr_chDB::SubmitLearCh($this->mail->getContent(),$this->post);
              
        if ($return == '1') {
            $this->errors='<div class="SuccessPHP">Application Submited</div>';

        } else {
                        $this->errors='<div class="errorPHP"><p>Application Failed</p><p>'.$return.'</p></div>';


        }
    }
    


}