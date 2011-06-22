<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class acom_regController extends PlonkController {

    protected $views = array(
        'acom_reg'
    );
    protected $actions = array(
        'next', 'submit', 'submitno'
    );
    private $error = '';
    private $position = '1';
    private $mail = '';
    protected $view = '';

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

    public function showacom_reg() {
        $this->checkLogged();
        $level = acom_regDB::checkLevel();
        if (($level[0]['statusOfErasmus'] != 'Student Application and Learning Agreement') && ($level[0]['action'] == 11)) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        }
        $x = PlonkFilter::getGetValue('institutionId');
        if (isset($x)) {
            $this->position = '2yesSession';
        }

        $this->mainTpl->assign('siteTitle', 'Accomodation Registration');
        $this->mainTpl->assign('pageMeta', '
            <link type="text/css" rel="stylesheet" href="./core/css/validationEngine.jquery.css"/>
            <link type="text/css" rel="stylesheet" href="./core/css/Style.css"/>');
        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/java.tpl');
        $this->mainTpl->assign('pageJava', $java->getContent(true));
        $this->pageTpl->assign('errorString', $this->error);
        $this->mainTpl->assign('breadcrumb','');
        $this->error = '';

        $checkFilled = acom_regDB::checkDB();
        if (!empty($checkFilled)) {
            $this->position = '3';
        }

        if ($this->position == '1') {
            $this->pageTpl->assignOption('showSelectAccommodationYN');
            $this->getDBdata(PlonkSession::get('id'), '');
        }

        if ($this->position == '2yes') {
            $this->mainTpl->assign('pageMeta', '');
            $this->mainTpl->assign('pageCSS', '');
            $this->pageTpl->assignOption('showSelectAccomodation');

            $resAvail = acom_regDB::getResidences(PlonkSession::get('id'));
            $this->fillResidence($resAvail, '');
        }
        if ($this->position == '2yesSession') {
            $this->mainTpl->assign('pageMeta', '');
            $this->mainTpl->assign('pageCSS', '');
            $this->pageTpl->assignOption('showSelectAccomodation');
            $resAvail = acom_regDB::getResidenceAV(PlonkSession::get('id'), $x);
            $this->fillResidence($resAvail, 'session');
        }

        if ($this->position == '2no') {
            $this->pageTpl->assignOption('showAccomNo');
            $this->getDBdata(PlonkSession::get('id'), '');
        }

        if ($this->position == '3') {
            $filled = acom_regDB::checkDB();
            $x = json_decode($filled[0]['content'], true);
            $this->pageTpl->assignOption('showComplete');
            $this->view = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/viewFilled.tpl');

            if (isset($x['res'])) {
                $this->view->assignOption('Yes');
                $this->view->assign('startDate', $x['startDate']);
                $this->view->assign('endDate', $x['endDate']);
                $this->view->assign('stAcName', $x['stAcName']);
                $this->view->assign('stAcIban', $x['stAcIban']);
                $this->view->assign('stAcBic', $x['stAcBic']);
                $this->fillResidence(acom_regDB::getResidence(PlonkSession::get('id'), $x['res']), 'view');
            } else {
                $this->getDBdata(PlonkSession::get('id'), 'view');
                $this->view->assignOption('No');
            }
            $this->pageTpl->assign('success', $this->view->getContent());
        }
    }

    public function doNext() {
        if ($this->position == '1') {
            if (empty($_POST['option1'])) {
                $this->error = '<div class="errorPHP">Please Select An Option</div>';
            } elseif ($_POST['option1'] == 'con') {
                $this->position = '2yes';
            } elseif ($_POST['option1'] == 'nocon') {
                $this->position = '2no';
            }
        }
    }

    public function doSubmit() {
        /* Validation */
        if (isset($_POST['startDate']) && isset($_POST['startDate']) && isset($_POST['startDate']) &&
                isset($_POST['res']) && isset($_POST['stAcName']) && isset($_POST['stAcIban']) && isset($_POST['stAcBic'])) {

            if ((is_valid_date($_POST['startDate'], FALSE)) && (is_valid_date($_POST['endDate'], FALSE))) {
                if ($_POST['startDate'] < $_POST['endDate']) {
                    $this->sendMail($_POST);
                } else {
                    $this->error = '<div class="errorPHP">Date of Departure should be after Date of Arrival</div>';
                    $this->position = '2yes';
                }
            } else {
                $this->error = '<div class="errorPHP">Please enter a Valid Date</div>';
                $this->position = '2yes';
            }
        } else {
            $this->error = '<div class="errorPHP">Please fill All Fields</div>';
            $this->position = '2yes';
        }
    }

    public function doSubmitno() {
        $name = PlonkSession::get('id');
        $this->mail = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/confirmNo.tpl');
        $this->getDBdata($name, 'mail');
        $post['sel'] = 'I confirm that I dont want to make a reservation for a student room';
        $return = acom_regDB::SubmitTranscript($this->mail->getContent(), $name, $post);
        if ($return == '1') {
            $this->position = '3';
            $this->error = '<div class="SuccessPHP"><p>Your Application Was Success</p></div>';
            $erasmusLevel = ExtendDB::getErasmusLevelId('Accomodation Registration Form');
            
            $valueEvent = array(
                'reader' => 'Student',
                'timestamp' => date("Y-m-d"),
                'motivation' => '',
                'studentId' => PlonkSession::get('id'),
                'action' => 2,
                'erasmusLevelId' => $erasmusLevel['levelId'],
                'eventDescrip' => 'Filled in Accomodation Registration Form but you didn\'t want a room.',
                'readIt' => 0
            );

            $er = array(
                'statusOfErasmus' => 'Accomodation Registration Form',
                'action' => 2
            );

            ExtendDB::insertValues('studentsEvents', $valueEvent);
            ExtendDB::insertValues('erasmusStudent', $er, 'users_email = "'.PlonkSession::get('id').'"');
        } else {
            $this->position = '2no';
            $this->error = '<div class="errorPHP"><p>' . $return . '</p><p>Try Sending it again</p></div>';
        }
    }

    private function sendMail($post) {
        $name = PlonkSession::get('id');
        $this->mail = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/confirm.tpl');
        $this->getDBdata($name, 'mail');
        $this->fillResidence(acom_regDB::getResidence($name, $post['res']), 'mail');
        $this->mail->assign('startDate', $post['startDate']);
        $this->mail->assign('endDate', $post['endDate']);
        $this->mail->assign('acName', $post['stAcName']);
        $this->mail->assign('stiban', $post['stAcIban']);
        $this->mail->assign('stbic', $post['stAcBic']);
        $return = acom_regDB::SubmitTranscript($this->mail->getContent(), $name, $post);
        if ($return == '1') {
            $this->position = '3';
            $this->error = '<div class="SuccessPHP"><p>Your Application Was Success</p></div>';
            
            $erasmusLevel = ExtendDB::getErasmusLevelId('Accomodation Registration Form');
            
            $valueEvent = array(
                'reader' => 'Student',
                'timestamp' => date("Y-m-d"),
                'motivation' => '',
                'studentId' => PlonkSession::get('id'),
                'action' => 2,
                'erasmusLevelId' => $erasmusLevel['levelId'],
                'eventDescrip' => 'Filled in Accomodation Registration Form and sent it to the owner.',
                'readIt' => 0
            );

            $er = array(
                'statusOfErasmus' => 'Accomodation Registration Form',
                'action' => 2
            );

            ExtendDB::insertValues('studentsEvents', $valueEvent);
            ExtendDB::insertValues('erasmusStudent', $er, 'users_email = "'.PlonkSession::get('id').'"');
            
        } else {
            $this->position = '2yes';
            $this->error = '<div class="errorPHP"><p>' . $return . '</p><p>Try Sending it again</p></div>';
        }
    }

    private function getDBdata($name, $act) {
        if ($act == 'mail') {
            $this->pageTpl = $this->mail;
        }
        if ($act == 'view') {
            $this->pageTpl = $this->view;
        }


        $query = acom_regDB::getStudentInfo($name);
        foreach ($query as $key => $value) {
            $this->pageTpl->assign('stFirstName', $value['firstName']);
            $this->pageTpl->assign('stLastName', $value['familyName']);
            $this->pageTpl->assign('stGender', $value['sex'] > 0 ? 'Male' : 'Female');
            $this->pageTpl->assign('stMail', $value['email']);

            $query2 = acom_regDB::getInstInfo($query[0]['homeInstitutionId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('reInName', $value['instName']);
            }
            $query2 = acom_regDB::getInstInfo($query[0]['hostInstitutionId']);
            foreach ($query2 as $key => $value) {
                $this->pageTpl->assign('seInName', $value['instName']);
            }
        }
    }

    private function fillResidence($resAvail, $act) {
        if ($act == 'mail') {
            $this->pageTpl = $this->mail;
        }
        if ($act == 'view') {
            $this->pageTpl = $this->view;
        }
        $this->pageTpl->setIteration('iResidence');

        $i = 0;
        while (isset($resAvail[$i]['residenceId'])) {
            $row = '
              <div class="accomodationList"><p class="acHead"><input {$Acselection} class="validate[required]"  type="radio" id="ac' . $i . '" name="res" value="' . $resAvail[$i]['residenceId'] . '"/><label for="ac' . $i . '" class="acHead"><b> ' . $resAvail[$i]['price'] . '&#128</b> '
                    . $resAvail[$i]['city'] . ', ' . $resAvail[$i]['streetNr'] . ', PC : ' . $resAvail[$i]['postalCode'] . '</label></p><p>'
                    . $resAvail[$i]['beds'] . ' beds, ';
            foreach ($resAvail[$i] as $key => $value) {

                if ($key == "kitchen" || $key == "bathroom") {
                    if ($value == 1) {
                        $row .= 'Personal ' . $key . ', ';
                    } else {
                        $row .= 'Communal ' . $key . ', ';
                    }
                }
                if ($key == "water" || $key == "elektricity" || $key == "television" || $key == "internet") {
                    if ($value == 1) {
                        $row .= ucfirst($key) . " available but not included in the price, ";
                    } else if ($value == 2) {
                        $row .= ucfirst($key) . " available and included in the price, ";
                    } else {
                        $row .= ucfirst($key) . " not available, ";
                    }
                }

                if ($key == "elektricity") {
                    if ($value == 1) {
                        $row .= "Electricity available but not included in the price";
                    } else if ($value == 2) {
                        $row .= "Electricity available and included in the price";
                    } else {
                        $row .= "Electricity not available ";
                    }
                }
            }
            $this->pageTpl->assignIteration('resid', $row . '</p></div>');
            $this->pageTpl->refillIteration('iResidence');
            $i++;
        }

        if ($act == 'mail' || $act == 'view' || $act == 'session') {
            $this->pageTpl->assign('Acselection', 'checked');
        } else {
            $this->pageTpl->assign('Acselection', '');
        }


        if (!isset($resAvail[0]['residenceId'])) {
            $this->pageTpl->assignIteration('resid', '<p class="minHead"><b>No Rooms Available</b></p>');
            $this->pageTpl->refillIteration('iResidence');
        }
        $this->pageTpl->parseIteration('iResidence');
        $instInfo = acom_regDB::getInst(PlonkSession::get('id'));
        $this->pageTpl->assign('iban', $instInfo[0]['iBan']);
        $this->pageTpl->assign('bic', $instInfo[0]['bic']);
        $this->pageTpl->assign('insName', $instInfo[0]['instName']);
    }

}

?>
