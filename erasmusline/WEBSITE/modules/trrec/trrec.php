<?php

class trrecController extends PlonkController {

    protected $views = array('select',);
    protected $actions = array('search', 'prev', 'next', 'selectuser', 'selectuserrec', 'postdata', 'resend', 'viewsended');
    private $errors = ''; // set the errors array to empty, by default
    private $fields = array(); // stores the field values


    /* For Using this PlonkSession::get('id'); must be an id of the coordinator
     * Transcript of records returns students that is not in Grades table and
     * student hostCoordinatorId == PlonkSession::get('id'); 
     */
    private $position = 'selectSend';
    private $searchFor = '';
    private $rows = 0;
    private $resultRows = 20;
    private $searchSt = '';
    private $mail = '';
    private $form = '';

        public function checkLogged() {
        if (!PlonkSession::exists('id')) {
            
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            //Plonk::dump('test');
            if (PlonkSession::get('id') === 0) {
                $this->id = PlonkSession::get('id');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            } else if ((PlonkSession::get('userLevel')!='Teaching Staff') && (PlonkSession::get('userLevel')!='Erasmus Coordinator')){
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
            }
        }
    }
    
    public function showselect() {
        $this->checkLogged();
        $this->mainTpl->assign('pageJava', '<script src="core/js/jquery/jquery-1.5.js" type="text/javascript"></script>
        <script src="core/js/jquery/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"> </script>
        <script src="core/js/jquery/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
        <script src="core/js/custom.js" type="text/javascript" charset="utf-8"> </script>
        <script src="core/js/jquery/sorttable.js" type="text/javascript"></script>');
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="core/css/Style.css" type="text/css"/>
            <link rel="stylesheet" href="core/css/validationEngine.jquery.css" type="text/css"/>');
        $this->pageTpl->assign('errorString', $this->errors);
        $this->errors = '';


        /* First Step : Select a Student From the List to send a Transcript of Records */

        if ($this->position == 'selectSend') {
            $this->pageTpl->assignOption('showSelectTranscriptSelect');
            $this->pageTpl->assign('view', 'Sended TranScripts');
            $this->mainTpl->assign('siteTitle', "Transcript of Records");


            if (!empty($this->searchSt)) {

                $studentsLName = trrecDB::getSearch($this->searchFor, $this->searchSt, 'register');

                if (empty($studentsLName)) {
                    $this->errors = '<div class="errorPHP">Entry Not Found</div>';

                    $this->pageTpl->assign('selectError', $this->errors);
                }
                $this->pageTpl->assign('hiddenP', 'type="hidden"');
                $this->pageTpl->assign('hiddenN', 'type="hidden"');
            } else {
                if ($this->rows != 0) {
                    $this->pageTpl->assign('prev', $this->rows - $this->resultRows);
                } else {
                    $this->pageTpl->assign('hiddenP', 'type="hidden"');
                }
                $this->pageTpl->assign('next', $this->rows + $this->resultRows);
                $next = trrecDB::getStudentList($this->rows + $this->resultRows);
                if (empty($next)) {
                    $this->pageTpl->assign('hiddenN', 'type="hidden"');
                } else {
                    $this->pageTpl->assign('hiddenN', '');
                }
                $studentsLName = trrecDB::getStudentList($this->rows);
            }
            $this->pageTpl->assign('selectError', '');

            $this->pageTpl->setIteration('iStudentsList');
            foreach ($studentsLName as $key => $value) {
                $this->pageTpl->assignIteration('studentsList', '<tr>
                <td> ' . $value['userId'] . '</td><td> ' . $value['firstName'] .
                        '</td><td> ' . $value['familyName'] . '</td>
                        <td><form  method="post">
                        <input type="hidden" name="stn" value="' . $value['email'] . '" />
                            <input type="hidden" name="formAction" id="formLogin" value="doSelectUser" />
                            <input class="nxtBtn" type="submit" value=">"/></form></td></tr>');
                $this->pageTpl->refillIteration('iStudentsList');
            }
            $this->pageTpl->parseIteration('iStudentsList');
        }



        /* or select to see the records */

        if ($this->position == 'selectSended') {

            $this->pageTpl->assignOption('showSelectTranscriptSelect');
            $this->pageTpl->assign('view', 'Send a TranScript');

            $this->mainTpl->assign('siteTitle', "Sended TranScripts Of Records");


            if (!empty($this->searchSt)) {

                $studentsLName = trrecDB::getSearch($this->searchFor, $this->searchSt, 'records');

                if (empty($studentsLName)) {
                    $this->errors = '<div class="errorPHP">Entry Not Found</div>';

                    $this->pageTpl->assign('selectError', $this->errors);
                }
                $this->pageTpl->assign('hiddenP', 'type="hidden"');
                $this->pageTpl->assign('hiddenN', 'type="hidden"');
            } else {
                if ($this->rows != 0) {
                    $this->pageTpl->assign('prev', $this->rows - $this->resultRows);
                } else {
                    $this->pageTpl->assign('hiddenP', 'type="hidden"');
                }
                $this->pageTpl->assign('next', $this->rows + $this->resultRows);
                $next = trrecDB::getStudentRecords($this->rows + $this->resultRows);
                if (empty($next)) {
                    $this->pageTpl->assign('hiddenN', 'type="hidden"');
                } else {
                    $this->pageTpl->assign('hiddenN', '');
                }

                //Generates Student List
                $studentsLName = trrecDB::getStudentRecords($this->rows);
            }

            $this->pageTpl->assign('selectError', '');

            $this->pageTpl->setIteration('iStudentsList');
            foreach ($studentsLName as $key => $value) {
                $this->pageTpl->assignIteration('studentsList', '<tr><td> ' . $value['userId'] . '</td><td> ' . $value['firstName'] . '</td><td> ' . $value['familyName'] . '</td><td><form  method="post"><input type="hidden" name="stn" value="' . $value['email'] . '" /><input type="hidden" name="form" value="' . $value['formId'] . '" /><input type="hidden" name="formAction" id="formLogin" value="doSelectUserRec" /><input class="nxtBtn" type="submit" value=">"/></form></td></tr>');
                $this->pageTpl->refillIteration('iStudentsList');
            }
            $this->pageTpl->parseIteration('iStudentsList');
        }


        /* Second Step: Fill and Send the Form */

        if ($this->position == 'sendTranscript') {
            $this->pageTpl->assign('back', 'index.php?module=trrec&view=select');
            $this->pageTpl->assignOption('showTranscript');
            $this->pageTpl->assignOption('showSendAtrBtn');
            $this->mainTpl->assign('siteTitle', "TranScript Of Records");
            $this->pageTpl->assign('action', 'doPostdata');
            $this->pageTpl->assignOption('showSendAtr');
            $this->getDBdata('reg', $this->student);
        }


        /* Or resend the Form */

        if ($this->position == 'sendedTranscript') {
            $this->pageTpl->assign('back', 'index.php?module=trrec&view=select');
            $name = $this->student;
            $this->pageTpl->assign('form', $this->form);

            $this->pageTpl->assignOption('showTranscript');
            $this->pageTpl->assignOption('showSendedTr');
            $this->pageTpl->assign('action', 'doResend');
            $this->mainTpl->assign('siteTitle', "TranScript Of Records");
            $this->pageTpl->assign('num', $name);
            $json = trrecDB::getRecords($this->form);
            $this->getDBdata('viewRec', $name);
            $this->pageTpl->setIteration('iStudentRec');
            $i = 1;
            while (isset($json['coursetitle' . $i . ''])) {
                $stRec = trrecDB::getCoursName($json['coursetitle' . $i . '']);
                $this->pageTpl->assignIteration('studentRec', '<tr><td> ' . $stRec[0]['courseCode'] . '</td><td> ' . $stRec[0]['courseName'] . '</td><td> ' . $stRec[0]['ectsCredits'] . '</td><td> ' . $json['corDur' . $i . ''] . '</td><td> ' . $json['locGrade' . $i . ''] . '</td><td> ' . $json['ecGrade' . $i . ''] . '</td></tr>');
                $this->pageTpl->refillIteration('iStudentRec');
                $i++;
            }
            $this->pageTpl->parseIteration('iStudentRec');
        }
    }

    public function doResend() {
        $this->student = $_POST['num'];
        $this->form = $_POST['form'];
        $this->formSend('selectSended');
    }

    public function doPostdata() {
        $name = $_POST['num'];
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

                $dtDubl = trrecDB::checkDubl($name, $value);

                if (($dtDubl == "Dub" ) || (empty($name))) {

                    $dbDub = 'Entry Exists in Database';
                    $er = '1';
                }


                $rules[] = "required," . $key . ",Course Title " . $i . " is required.";
                $rules[] = "required,locGrade" . $i . ",Local Grade " . $i . " is required.";
                $rules[] = "digits_only,locGrade" . $i . ",Local Grade " . $i . "  may only contain digits.";
                $rules[] = "required,ecGrade" . $i . ",ECTS Grade " . $i . " is required.";


                $this->fields[] = 'coursetitle' . $i;
                $this->fields[] = 'locGrade' . $i;
                $this->fields[] = 'ecGrade' . $i;

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
            if (!empty($dbDub)) {
                $errorString .="<p>" . $dbDub . "</p>";
            }
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
            $this->position = 'sendTranscript';
            $this->student = $name;
            $this->errors = $errorString;
            $this->fields = $_POST;
        } else {
            $this->student = $name;
            $this->form = trrecDB::formAp($name);
            ;
            $this->formSend('selectSend');
        }
    }

    public function formSend($pos) {
        $name = $this->student;
        $this->mail = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/confirm.tpl');
        $json = trrecDB::getRecords($this->form);
        $this->getDBdata('mail', $name);
        $this->mail->setIteration('iStudentRec');
        $i = 1;
        while (isset($json['coursetitle' . $i . ''])) {
            $stRec = trrecDB::getCoursName($json['coursetitle' . $i . '']);
            $this->mail->assignIteration('studentRec', '<tr><td> ' . $stRec[0]['courseCode'] . '</td><td> ' . $stRec[0]['courseName'] . '</td><td> ' . $stRec[0]['ectsCredits'] . '</td><td> ' . $json['corDur' . $i . ''] . '</td><td> ' . $json['locGrade' . $i . ''] . '</td><td> ' . $json['ecGrade' . $i . ''] . '</td></tr>');
            $this->mail->refillIteration('iStudentRec');
            $i++;
        }
        $this->mail->parseIteration('iStudentRec');
        $return = trrecDB::SubmitTranscript($this->mail->getContent(), $name);
        $this->position = $pos;
        if ($return == '1') {
            $this->errors = '<div class="SuccessPHP">Your Application was Success</div>';
        } else {
            $this->errors = '<div class="errorPHP"><p>' . $return . '</p><p>Try Sending it again</p></div>';
        }
    }

    public function doViewsended() {
        if ($_POST['postForm'] == 'Send a TranScript') {
            $this->position = 'selectSend';
        } else {
            $this->position = 'selectSended';
        }
    }

    public function doNext() {
        $this->rows = $_POST['next'];
        $this->checkPos($_POST['pos']);
    }

    public function doPrev() {
        $this->rows = $_POST['prev'];
        $this->checkPos($_POST['pos']);
    }

    public function doSearch() {
        if (!empty($_POST['Search'])) {
            $this->searchSt = $_POST['Search'];
            $this->searchFor = $_POST['selection'];
        }
        $this->checkPos($_POST['pos']);
    }

    public function checkPos($pos) {
        if ($pos == 'Send a TranScript') {
            $this->position = 'selectSended';
        } else {
            $this->position = 'selectSend';
        }
    }

    public function doSelectUser() {
        $this->student = $_POST["stn"];
        $this->position = 'sendTranscript';
    }

    public function doSelectUserrec() {
        $this->student = $_POST["stn"];
        $this->form = $_POST["form"];
        $this->position = 'sendedTranscript';
    }

    public function getDBdata($act, $name) {

        if ($act == 'mail') {
            $this->pageTpl = $this->mail;
        }
        $query = trrecDB::getStudentInfo($name);


        foreach ($query as $key => $value) {
            $this->pageTpl->assign('stFirstName', $value['firstName']);
            $this->pageTpl->assign('stLastName', $value['familyName']);
            $this->pageTpl->assign('stGender', $value['sex'] > 0 ? 'Male' : 'Female');
            $this->pageTpl->assign('stDtBirh', $value['birthDate']);
            $this->pageTpl->assign('stPlBirh', $value['birthPlace']);
            $this->pageTpl->assign('stMatrDate', $value['startDate']);
            $this->pageTpl->assign('stMatrNum', $value['userId']);
            $this->pageTpl->assign('stMail', $value['email']);

            $query2 = trrecDB::getCoordInfo($query[0]['hostCoordinatorId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('seCorName', $value['familyName'] . '&nbsp;' . $value['firstName']);
                $this->pageTpl->assign('seCorMail', $value['email']);
                $this->pageTpl->assign('seCorTel', $value['tel']);
                $this->pageTpl->assign('seCorFax', $value['fax']);
            }

            $query2 = trrecDB::getCoordInfo($query[0]['homeCoordinatorId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('reCorName', $value['familyName'] . '&nbsp;' . $value['firstName']);
                $this->pageTpl->assign('reCorMail', $value['email']);
                $this->pageTpl->assign('reCorTel', $value['tel']);
                $this->pageTpl->assign('reCorFax', $value['fax']);
            }

            $query2 = trrecDB::getInstInfo($query[0]['homeInstitutionId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('reInName', $value['instName']);
            }
            $query2 = trrecDB::getInstInfo($query[0]['hostInstitutionId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('seInName', $value['instName']);
            }


            if ($act == "reg") {

                $stRec = trrecDB::getSelCourses($name);

                $this->pageTpl->setIteration('iCourses');
                $i = 0;
                while (isset($stRec[$i]['courseCode'])) {
                    $j = $i + 1;
                    $this->pageTpl->assignIteration('courses', '

                    
<tr>
<td> <input type="hidden" name="coursetitle' . $j . '" value="' . $stRec[$i]['courseId'] . '" />' . $stRec[$i]['courseCode'] . '</td><td> ' . $stRec[$i]['courseName'] . '</td>
<td> ' . $stRec[$i]['ectsCredits'] . '</td>
<td> <select  name="corDur' . $j . '" id="corDur' . $j . '" ><option></option><option>Y</option><option>1S</option><option>1T</option><option>2S</option><option>2T</option></select></td>
<td><input class="validate[required,custom[integer]]" type="text" size="5" maxlength="2" name="locGrade' . $j . '" id="locGrade' . $j . '" /></td>
<td><select  name="ecGrade' . $j . '" id="ecGrade' . $j . '" class="validate[required]"><option></option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>FX</option></select></td>
</tr>');
                    $this->pageTpl->refillIteration('iCourses');
                    $i++;
                }

                $this->pageTpl->assign('num', $name);

                $this->pageTpl->parseIteration('iCourses');
            }
        }
    }

}