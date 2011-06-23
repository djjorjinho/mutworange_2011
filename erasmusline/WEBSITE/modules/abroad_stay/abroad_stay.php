<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "./modules/infox/infox.php";

class abroad_stayController extends PlonkController {

    protected $views = array(
        'select'
    );
    protected $actions = array(
        'submit', 'selectuser', 'selectuserrec', 'selectaction', 'next', 'prev', 'search', 'viewsended', 'resend', 'resenddep'
    );
    protected $variables = array(
        'hostInstitution', 'dateArrival', 'dateDeparture', 'student'
    );
    private $errors = '';
    private $student = '';
    private $form = '1';
    private $position = 'showSelect';
    private $searchSt = '';
    private $rows = 0;
    private $resultRows = 20;
    private $searchFor = '';
    private $mail = '';

    public function checkLogged() {
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
            } else {
                $this->id = PlonkSession::get('id');
                
            }
        }
    }

    public function showselect() {
        $this->checkLogged();
        $this->mainTpl->assign('pageMeta', '');
        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/java.tpl');
        $this->mainTpl->assign('pageJava', $java->getContent(true));
        $this->pageTpl->assign('errorString', $this->errors);
        $this->pageTpl->assign('back', 'index.php?module=abroad_stay&view=select');
        $this->mainTpl->assign('breadcrumb', '');


        /* First Step : Show Select User */

        if ($this->position == 'showSelect') {
            $this->showList();
        }


        /* Second Step : Show Cerificate Form Select  */

        if ($this->position == 'showCertificates') {
            $this->Certificates();
        }




        /* Third Step : Show Records Select */

        if ($this->position == 'showSended') {
            $this->sendedList();
        }
    }

    public function Certificates() {
        $this->pageTpl->assignOption('showCertificates');
        $this->pageTpl->assign('student', $this->student);
        //For List
        $this->pageTpl->assign('seluser', $this->student);
        $this->getDBdata($this->student, 'Cert');

        if ($this->form == '1') {

            $this->pageTpl->assignOption('showCertS');
            $this->mainTpl->assign('siteTitle', 'Certificate of Arrival');
            $this->pageTpl->assign('form', 'Date of Arrival');
            $query = abroad_stayDB::checkRecords($this->student, 'startDate');
            if (!empty($query)) {
                $this->pageTpl->assign('field', '<div class="TRdiv">Date : ' . $query[0]['startDate'] . '</div>');
                $this->pageTpl->assignOption('showResend');
            } else {
                $this->pageTpl->assign('field', '<div class="TRdiv"><label for="date">Date (yyyy-mm-dd) :  </label><input type="text" name="startDate"  id="startDate" class="validate[required,custom[date]] text-input"  /></div>');
                $this->pageTpl->assignOption('showSubmit');
            }
        }

        if ($this->form == '2') {

            $this->pageTpl->assignOption('showCertS');

            $this->mainTpl->assign('siteTitle', 'Certificate of Departure');
            $this->pageTpl->assign('form', 'Date of Departure');
            $query = abroad_stayDB::checkRecords($this->student, 'endDate');
            if (!empty($query)) {
                $this->pageTpl->assign('field', '<div class="TRdiv">Date : ' . $query[0]['endDate'] . '</div>');
                $this->pageTpl->assignOption('showResend');
            } else {
                $this->pageTpl->assign('field', '<div class="TRdiv"><label for="date">Date (yyyy-mm-dd) :  </label><input type="text" name="endDate"  id="dateDep" class="validate[required,custom[date]] text-input"  /></div>');
                $this->pageTpl->assignOption('showSubmit');
            }
        }

        if ($this->form == '3') {
            $this->pageTpl->assignOption('showResenddep');

            $this->mainTpl->assign('siteTitle', 'Certificate of Stay');
            $this->pageTpl->assign('form', 'Staying Period');
            $query = abroad_stayDB::checkRecords($this->student, 'endDate');
            $query2 = abroad_stayDB::checkRecords($this->student, 'startDate');
            if (!empty($query)) {
                $this->pageTpl->assign('field', '<div class="TRdiv">From : ' . $query2[0]['startDate'] . '</div>
                        <div class="TRdiv">To : ' . $query[0]['endDate'] . '</div>');
            } else {
                $this->pageTpl->assign('field', '');
            }
        }
    }

    public function sendedList() {
        $this->pageTpl->assign('view', 'Send a Cerification');
        $this->pageTpl->assignOption('showSelectAboardUser');
        $this->mainTpl->assign('siteTitle', "Select Student Records");
        $this->pageTpl->assignOption('showSendedCertificatesList');

        ////Generates Student List


        if (!empty($this->searchSt)) {
            $studentsLName = abroad_stayDB::getSearchRecords($this->searchSt, $this->searchFor);
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
            $next = abroad_stayDB::getStudentListRecords($this->rows + $this->resultRows);
            if (empty($next)) {
                $this->pageTpl->assign('hiddenN', 'type="hidden"');
            } else {
                $this->pageTpl->assign('hiddenN', '');
            }
            $studentsLName = abroad_stayDB::getStudentListRecords($this->rows);
        }

        $this->pageTpl->assign('selectError', '');


        $this->pageTpl->setIteration('iStudentsList');
        foreach ($studentsLName as $key => $value) {
            $this->pageTpl->assignIteration('studentsList', '<tr>
                <td> ' . $value['userId'] . '</td><td> ' . $value['firstName'] .
                    '</td><td> ' . $value['familyName'] . '</td>
                        <td><form  method="post">
                        <input type="hidden" name="stn" value="' . $value['email'] . '" />
                            <input type="hidden" name="formAction" id="formLogin" value="doSelectUserrec" />
                            <input class="nxtBtn" type="submit" value=">"/></form></td></tr>');
            $this->pageTpl->refillIteration('iStudentsList');
        }
        $this->pageTpl->parseIteration('iStudentsList');
    }

    public function showList() {
        $this->pageTpl->assign('view', 'Sended Certificates');
        $this->pageTpl->assignOption('showSelectAboardUser');
        $this->mainTpl->assign('siteTitle', "Select Student");
        $this->mainTpl->assign('breadcrumb', '');



        //Generates Student List

        /* Checks for Search Post */
        if (!empty($this->searchSt)) {
            $studentsLName = abroad_stayDB::getSearch($this->searchSt, $this->searchFor);
            $this->pageTpl->assign('hiddenP', 'type="hidden"');
            $this->pageTpl->assign('hiddenN', 'type="hidden"');
            if (empty($studentsLName)) {
                $this->errors = '<div class="errorPHP">Entry Not Found</div>';
                $this->pageTpl->assign('selectError', $this->errors);
            }
            $this->pageTpl->assign('selectError', '');
        } else {
            /* else continue to main page */
            $this->pageTpl->assign('selectError', '');

            if ($this->rows != 0) {
                $this->pageTpl->assign('prev', $this->rows - $this->resultRows);
            } else {
                $this->pageTpl->assign('hiddenP', 'type="hidden"');
            }

            $this->pageTpl->assign('next', $this->rows + $this->resultRows);
            $next = abroad_stayDB::getStudentList($this->rows + $this->resultRows);
            if (empty($next)) {
                $this->pageTpl->assign('hiddenN', 'type="hidden"');
            } else {
                $this->pageTpl->assign('hiddenN', '');
            }

            $studentsLName = abroad_stayDB::getStudentList($this->rows);
        }


        $this->pageTpl->setIteration('iStudentsList');
        foreach ($studentsLName as $key => $value) {
            $this->pageTpl->assignIteration('studentsList', '<tr>
                <td> ' . $value['userId'] . '</td><td> ' . $value['firstName'] .
                    '</td><td> ' . $value['familyName'] . '</td>
                        <td><form  method="post">
                        <input type="hidden" name="stn" value="' . $value['email'] . '" />
                            <input type="hidden" name="formAction" id="formLogin" value="doSelectUser" />
                            <input type="submit" value=">"/></form></td></tr>');
            $this->pageTpl->refillIteration('iStudentsList');
        }
        $this->pageTpl->parseIteration('iStudentsList');
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
        if ($pos == 'Send a Cerification') {
            $this->position = 'showSended';
        } else {
            $this->position = 'showSelect';
        }
    }

    public function doSelectUser() {
        $this->student = $_POST["stn"];
        $this->position = 'showCertificates';
    }

    public function doSelectUserrec() {
        $this->student = $_POST["stn"];
        $this->position = 'showCertificates';
        $this->form = '3';
    }

    public function doSelectaction() {
        unset($_POST['formAction']);
        $this->position = 'showCertificates';
        $this->student = $_POST["User"];
        if ($_POST['postForm'] == 'Certificate of Arrival') {
            $this->form = '1';
        }
        if ($_POST['postForm'] == 'Certificate of Departure') {
            $this->form = '2';
        }
    }

    public function getDBdata($name, $pos) {

        if ($pos == 'mail') {
            $this->pageTpl = $this->mail;
        }
        $query = abroad_stayDB::getStudentInfo($name);


        foreach ($query as $key => $value) {
            $this->pageTpl->assign('stFirstName', $value['firstName']);
            $this->pageTpl->assign('stLastName', $value['familyName']);
            $this->pageTpl->assign('stGender', $value['sex'] > 0 ? 'Male' : 'Female');
            $this->pageTpl->assign('stDtBirh', $value['birthDate']);
            $this->pageTpl->assign('stPlBirh', $value['birthPlace']);
            $this->pageTpl->assign('stMatrDate', $value['startDate']);
            $this->pageTpl->assign('stMatrNum', $value['userId']);
            $this->pageTpl->assign('stMail', $value['email']);

            $query2 = abroad_stayDB::getCoordInfo($query[0]['hostCoordinatorId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('seCorName', $value['familyName'] . '&nbsp;' . $value['firstName']);
                $this->pageTpl->assign('seCorMail', $value['email']);
                $this->pageTpl->assign('seCorTel', $value['tel']);
                $this->pageTpl->assign('seCorFax', $value['fax']);
            }

            $query2 = abroad_stayDB::getCoordInfo($query[0]['homeCoordinatorId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('reCorName', $value['familyName'] . '&nbsp;' . $value['firstName']);
                $this->pageTpl->assign('reCorMail', $value['email']);
                $this->pageTpl->assign('reCorTel', $value['tel']);

                $this->pageTpl->assign('reCorFax', $value['fax']);
            }

            $query2 = abroad_stayDB::getInstInfo($query[0]['homeInstitutionId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('reInName', $value['instName']);
            }
            $query2 = abroad_stayDB::getInstInfo($query[0]['hostInstitutionId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('seInName', $value['instName']);
            }
        }
    }

    public function doSubmit() {

        unset($_POST['formAction'], $_POST['postForm']);
        if ((isset($_POST['startDate']) && is_valid_date($_POST['startDate'], FALSE))) {
            $dub = abroad_stayDB::checkDubl($_POST['User'], 'startDate');
            if ($dub == 'Dub') {
                $this->errors = '<div class="errorPHP">Entry Exists In DataBase</div>';
            } else {
                $this->confirm($_POST, TRUE);
            }
        } elseif ((isset($_POST['endDate']) && is_valid_date($_POST['endDate'], FALSE))) {
            $query2 = abroad_stayDB::checkRecords($_POST['User'], 'startDate');

            if ((isset($query2[0]['startDate'])) && ($_POST['endDate'] > $query2[0]['startDate'])) {
                $dub = abroad_stayDB::checkDubl($_POST['User'], 'endDate');
                if ($dub == 'Dub') {
                    $this->errors = '<div class="errorPHP">Entry Exists In DataBase</div>';
                } else {
                    $this->confirm($_POST, TRUE);
                }
            } else {
                $this->errors = '<div class="errorPHP">Departure Date Should be After Arrival Date</div>';
                $this->position = 'showCertificates';
                $this->student = $_POST['User'];
            }
        } else {
            $this->errors = '<div class="errorPHP">Please Enter a Valid Date</div>';
            $this->position = 'showCertificates';
            $this->student = $_POST['User'];
        }
    }

    public function confirm($post, $flag) {
        $this->mail = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/cert_ar.tpl');
        $this->getDBdata($post['User'], 'mail');
        if (isset($post['startDate'])) {
            $this->mail->assign('header', 'Certificate Of Arrival');
            $this->mail->assign('form', 'Start Date');
            $this->mail->assign('field', '<div class="TRdiv">Date : ' . $post['startDate'] . '</div>');

            $infoStudent = abroad_stayDB::getStudentInfo($post['User']);
            $infoInst = abroad_stayDB::getInstInfo($infoStudent[0]['hostInstitutionId']);

            $erasmusLevel = abroad_stayDB::getErasmusLevelId('Certificate Of Arrival');

            $valueEvent = array(
                'reader' => $infoStudent[0]['homeCoordinatorId'],
                'timestamp' => date("Y-m-d"),
                'motivation' => '',
                'studentId' => $post['User'],
                'action' => 1,
                'erasmusLevelId' => $erasmusLevel['levelId'],
                'eventDescrip' => $infoStudent[0]['firstName'] . ' ' . $infoStudent[0]['familyName'] . ' is arrived at ' . $infoInst[0]['instName'],
                'readIt' => 0
            );

            $erasmus = abroad_stayDB::getErasmusInfo($post['User']);

            try {

                $event = array(
                    'table' => 'studentsEvents',
                    'data' => $valueEvent
                );

                $b = new InfoxController;

                $methods = array('forms:insertInDb');
                $tables = array('studentsEvents');
                $data = array($event);
                $idInst = $erasmus['homeInstitutionId'];
                $success = $b->dataTransfer($methods, $tables, $data, $idInst);
            } catch (Exception $e) {
                
            }

            $return = abroad_stayDB::SubmitTranscript($this->mail->getContent(), $post['User']);
            if ($return == '1') {
                abroad_stayDB::PostForm($_POST);
                $this->errors = '<div class="SuccessPHP"><p>Certificate of Arrival SuccessFully Send</p></div>';
            } else {
                $this->errors = '<div class="errorPHP"><p>There was an Error Sending Cerificate of Arrival</p><p>' . $return . '</p></div>';
                $this->position = 'showCertificates';
                $this->student = $post['User'];
            }
        }
        if ((isset($post['endDate'])) && ($flag)) {
            $this->mail->assign('header', 'Certificate Of Departure');
            $this->mail->assign('form', 'Departure Date');
            $this->mail->assign('field', '<div class="TRdiv">Date : ' . $post['endDate'] . '</div>');

            $return = abroad_stayDB::SubmitTranscript($this->mail->getContent(), $post['User']);
            if ($return == '1') {
                abroad_stayDB::PostForm($_POST);
                $this->confirm($post, FALSE);
            } else {
                $this->errors = '<div class="errorPHP"><p>There was an Error Sending Cerificate of Departure</p><p>' . $return . '</p></div>';
                $this->position = 'showCertificates';
                $this->student = $post['User'];
            }
        } elseif (!$flag) {

            $this->mail->assign('header', 'Certificate of Stay');
            $this->mail->assign('form', 'Staying Period');
            $query = abroad_stayDB::checkRecords($post['User'], 'endDate');
            $query2 = abroad_stayDB::checkRecords($post['User'], 'startDate');
            $this->mail->assign('field', '<div class="TRdiv">From : ' . $query2[0]['startDate'] . '</div>
                        <div class="TRdiv">To : ' . $query[0]['endDate'] . '</div>');

            $return = abroad_stayDB::SubmitTranscript($this->mail->getContent(), $post['User']);
            if ($return == '1') {
                $this->errors = '<div class="SuccessPHP"><p>Certificate of Stay SuccessFully Send</p></div>';
                $infoStudent = abroad_stayDB::getStudentInfo($post['User']);
                $infoInst = abroad_stayDB::getInstInfo($infoStudent[0]['hostInstitutionId']);

                $erasmusLevel = abroad_stayDB::getErasmusLevelId('Certificate Of Departure');

                $valueEvent = array(
                    'reader' => $infoStudent[0]['homeCoordinatorId'],
                    'timestamp' => date("Y-m-d"),
                    'motivation' => '',
                    'studentId' => $post['User'],
                    'action' => 1,
                    'erasmusLevelId' => $erasmusLevel['levelId'],
                    'eventDescrip' => $infoStudent[0]['firstName'] . ' ' . $infoStudent[0]['familyName'] . ' is arrived at ' . $infoInst[0]['instName'],
                    'readIt' => 0
                );

                $er = array(
                    'statusOfErasmus' => 'Certificate Of Departure',
                    'action' => 1
                );

                abroad_stayDB::updateErasmusStudent('erasmusStudent', $er, 'users_email = "' . $post['User'] . '"');
                abroad_stayDB::insertValues('studentsEvents', $valueEvent);

                $erasmus = abroad_stayDB::getErasmusInfo($post['User']);

                try {

                    $event = array(
                        'table' => 'studentsEvents',
                        'data' => $valueEvent
                    );

                    $erasss = array(
                        'table' => 'erasmusStudent',
                        'data' => $er,
                        'emailField' => 'users_email'
                    );

                    $b = new InfoxController;

                    $methods = array('forms:insertInDb', 'forms:toDb');
                    $tables = array('studentsEvents', 'erasmusStudent');
                    $data = array($event, $erasss);
                    $idInst = $erasmus['homeInstitutionId'];
                    $success = $b->dataTransfer($methods, $tables, $data, $idInst);
                } catch (Exception $e) {
                    
                }
            } else {
                $this->errors = '<div class="errorPHP"><p>There was an Error Sending Certificate of Stay</p><p>' . $return . '</p></div>';
                $this->position = 'showCertificates';
                $this->form = '3';
                $this->student = $post['User'];
            }
        }
    }

    public function doViewsended() {
        if ($_POST['postForm'] == 'Send a Cerification') {
            $this->position = 'showSelect';
        } else {
            $this->position = 'showSended';
        }
    }

    public function doResend() {
        $query = abroad_stayDB::checkRecords($_POST['User'], 'startDate');
        $_POST['startDate'] = $query[0]['startDate'];

        $this->confirm($_POST, TRUE);
    }

    public function doResenddep() {
        $this->confirm($_POST, FALSE);
    }

}

?>
