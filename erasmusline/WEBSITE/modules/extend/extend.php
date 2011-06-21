<?php

require_once "./modules/infox/infox.php";

class ExtendController extends PlonkController {

    protected $views = array(
        'extend'
    );
    protected $actions = array(
        'extend', 'motivate', 'tohome'
    );
    protected $variables = array(
        'from', 'until', 'notes', 'months'
    );
    protected $errors = array();
    protected $rules = array();
    protected $fields = array();
    protected $userid;
    protected $formid;

    public function showExtend() {
        if (PlonkFilter::getGetValue('form') != null) {
            $this->formid = PlonkFilter::getGetValue('form');
            $this->userid = ExtendDB::getstudentByForm($this->formid);
            $this->filledExtend();
        } else {
            $this->userid = PlonkSession::get('id');
            $this->fillVariables();
            $this->pageTpl->assignOption('oNotFilled');
        }

        $this->checklogged();
        $this->mainTplAssigns();
        $this->fillUser();

        //Plonk::dump($this->id);
        $status = ExtendDB::getStudentStatus($this->userid);
        $erasmusLevel = ExtendDB::getIdLevel($status['statusOfErasmus']);
        $erasmusLevel2 = ExtendDB::getIdLevel('Extend Mobility Period');
    }

    public function filledExtend() {
        $this->pageTpl->assignOption('oFilled');

        $json = ExtendDB::getJson($this->formid, 'Extend Mobility Period');
        $jsonArray = json_decode($json['content'], true);

        $formAction = ExtendDB::getForm($this->formid);

        if ($formAction['action'] == 1) {
            $this->pageTpl->assignOption('oApproved');
            $this->pageTpl->assign('motivationHost', $formAction['motivationHost']);
             $this->pageTpl->assign('motivationHome', $formAction['motivationHome']);
            $this->pageTpl->assign('returndExtend', '<a href="./files/' . $this->userid . '/' . $this->formid . '.pdf" title="Application Form">Student Application Form.pdf</a>');
        } else if ($formAction['action'] == 0) {
            $this->pageTpl->assignOption('oDenied');
            $this->pageTpl->assign('motivationHome', $formAction['motivationHome']);
             $this->pageTpl->assign('motivationHost', $formAction['motivationHost']);
            $this->pageTpl->assign('returndExtend', '<a href="./files/' . $this->userid . '/' . $this->formid . '.pdf" title="Application Form">Student Application Form.pdf</a>');
        } else {
            $this->pageTpl->assignOption('oPending');
        }

        foreach ($jsonArray as $key => $value) {
            $this->pageTpl->assign($key, $value);
            $this->pageTpl->assign('msg' . ucfirst($key), '');
        }
    }

    private function mainTplAssigns() {
        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/extend.java.tpl');
        $this->pageTpl->assign('pageJava', $java->getContent(true));
        $this->mainTpl->assign('pageMeta', '');
        $this->mainTpl->assign('pageJava', '');
        $this->mainTpl->assign('siteTitle', 'Extend Mobility Period');
        $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=home&amp;view=userhome" title="Home">Home</a><a href="index.php?module=extend&amp;view=extend&form=' . $this->formid . '" title="Extend Mobility Period">Extend Mobility Period</a>');

        $this->pageTpl->assign('from', date('Y-m-d'));
        $this->pageTpl->assign('until', date('Y-m-d'));
    }

    private function checklogged() {
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                $erasmus = ExtendDB::getErasmusInfo(PlonkSession::get('id'));
                $this->pageTpl->assignOption('oStudent');
            } else if (PlonkSession::get('userLevel') == 'International Relations Office Staff') {
                $formid = PlonkFilter::getGetValue('form');
                $studentId = ExtendDB::getStudentByForm($formid);
                $erasmusInfo = ExtendDB::getErasmusInfo($studentId);

                if ($erasmusInfo['homeInstitutionId'] != INST_EMAIL) {
                    $this->pageTpl->assignOption('oHost');
                } else {
                    $this->pageTpl->assignOption('oOffice');
                }
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&' . PlonkWebsite::$viewKey . '=staff');
            }
        }
    }

    private function fillUser() {
        $this->pageTpl->assign('months', '');
        $id = $this->userid;
        $user = ExtendDB::getUserById($id);
        $this->pageTpl->assign('familyName', $user['familyName']);
        $this->pageTpl->assign('firstName', $user['firstName']);
        $this->pageTpl->assign('startDate', $user['startDate']);
        $this->pageTpl->assign('endDate', $user['endDate']);

        $host = ExtendDB::getHostInstitution($id);
        $this->pageTpl->assign('hostinstitution', $host['instName']);

        $home = ExtendDB::getHomeInstitution($id);
        $this->pageTpl->assign('homeInstitution', $home['instName']);

        $education = ExtendDB::getEducation($id);
        $this->pageTpl->assign("education", $education['educationName']);
    }

    private function fillRules() {
        $this->rules[] = "required,from,Date From is required.";
        $this->rules[] = "required,until,Date Until is required.";
        $this->rules[] = "required,months,# Months is required.";
        $this->rules[] = "required,notes,Nots is required";
    }

    private function fillVariables() {
        foreach ($this->variables as $value) {
            if (empty($this->fields)) {
                $this->pageTpl->assign('msg' . ucfirst($value), '*');
                $this->pageTpl->assign($value, '');
            } else {
                if (!array_key_exists($value, $this->errors)) {
                    $this->pageTpl->assign('msg' . ucfirst($value), '');
                    $this->pageTpl->assign($value, $this->fields[$value]);
                } else {
                    $this->pageTpl->assign('msg' . ucfirst($value), $this->errors[$value]);
                    $this->pageTpl->assign($value, $this->fields[$value]);
                }
            }
        }
    }

    public function doExtend() {
        $this->fillRules();
        $this->fields = $_POST;
        $this->errors = validateFields($this->fields, $this->rules);

        // if there were errors, re-populate the form fields
        if (!empty($this->errors)) {
            $this->fields = $_POST;
            Plonk::dump($this->errors);
        } else {

            $testArray = $_POST;
            $newArray = array_slice($testArray, 0, count($_POST) - 2);

            $jsonArray = json_encode($newArray);
            $erasmusLevel = ExtendDB::getErasmusLevelId('Extend Mobility Period');
            $valuess = array(
                'formId' => Functions::createRandomString(),
                'type' => 'Extend Mobility Period',
                'date' => date("Y-m-d"),
                'content' => $jsonArray,
                'studentId' => PlonkSession::get('id'),
                'erasmusLevelId' => $erasmusLevel['levelId'],
                'action' => 2
            );

            $valueEvent = array(
                'reader' => 'Student',
                'timestamp' => date("Y-m-d"),
                'motivation' => '',
                'studentId' => PlonkSession::get('id'),
                'action' => 2,
                'erasmusLevelId' => $erasmusLevel['levelId'],
                'eventDescrip' => 'Filled in Extend Mobility Period',
                'readIt' => 0
            );

            $er = array(
                'statusOfErasmus' => 'Extend Mobility Period',
                'action' => 2
            );

            ExtendDB::insertValues('studentsEvents', $valueEvent);
            ExtendDB::insertValues('forms', $valuess);
            ExtendDB::updateErasmusStudent('erasmusStudent', $er, 'users_email = "' . PlonkSession::get('id') . '"');

            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
        }

        //Plonk::dump($this->languages.$this->works);
        //PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=lagreeform&' . PlonkWebsite::$viewKey . '=applicform&l='.$this->languages.'&w='.$this->works);
    }

    public function doTohome() {
        $this->userid = ExtendDB::getStudentByForm(PlonkFilter::getGetValue('form'));
        $user = ExtendDB::getInfoUser($this->userid);
        $erasmus;
        $this->formid = PlonkFilter::getGetValue('form');
        $success;

        $formArray;
        $descrip = "";

        if (PlonkFilter::getPostValue('acceptedHost') == 1) {
            $erasmus = ExtendDB::getErasmusInfo($this->userid);

            $descrip = "Mobility Extension Period is accepted by host and sent to home.";

            $testArray = $_POST;
            $newArray = array_slice($testArray, 0, count($_POST) - 2);

            $jsonArray = json_encode($newArray);
            $formMot = array(
                'motivationHost' => PlonkFilter::getPostValue('coordinator'),
                'content' => $jsonArray,
            );

            ExtendDB::updateErasmusStudent('forms', $formMot, 'formId = "' . $this->formid . '"');
            $form = ExtendDB::getForm($this->formid);

            try {
                $form = array(
                    'table' => 'forms',
                    'data' => $form,
                    'emailField' => 'formId'
                );

                $b = new InfoxController;
                //$b->TransferBelgium($jsonStringUser, $hostInst['instId']);
                $methods = array('forms:toDb');
                $tables = array('forms');
                $data = array($form);
                $idInst = $erasmus['homeInstitutionId'];
                //$success = $b->dataTransfer($methods, $tables, $data, $idInst);
                //Plonk::dump($_FILES);
                if (!empty($_FILES['pic']['tmp_name'][0])) {

                    $this->upload($this->formid . '.pdf');
                    //$b->fileTransfer('forms:saveFile', 'files/' . $this->userid . '/' . $this->formid . '.pdf', $idInst, $this->userid);
                }
            } catch (Exception $e) {
                Plonk::dump('failed');
            }
        } else {
            $testArray = $_POST;
            $newArray = array_slice($testArray, 0, count($_POST) - 2);

            $jsonArray = json_encode($newArray);

            $descrip = "Extend Mobility Period is denied by host.";

            $formArray = array(
                'action' => 0,
                'motivationHost' => PlonkFilter::getPostValue('coordinator'),
                'content' => $jsonArray
            );

            $values = array('action' => 0);

            ExtendDB::updateErasmusStudent('erasmusstudent', $values, 'users_email = "' . $this->userid . '"');
            ExtendDB::updateErasmusStudent('forms', $formArray, 'formId = "' . $this->formid . '"');

            $success = "denied";
        }

        $erasmusLevel = ExtendDB::getErasmusLevelId('Extend Mobility Period');

        $valueEvent = array(
            'reader' => 'Student',
            'timestamp' => date("Y-m-d"),
            'motivation' => PlonkFilter::getPostValue('coordinator'),
            'studentId' => $this->userid,
            'action' => $status,
            'erasmusLevelId' => $erasmusLevel['levelId'],
            'eventDescrip' => $descrip,
            'readIt' => 0
        );

        ExtendDB::insertValues('studentsEvents', $valueEvent);

        //Plonk::dump($success);
        if ($success == "denied") {
            PlonkWebsite::redirect('index.php?module=office&view=office');
        }
        if ($success != '0') {
            PlonkWebsite::redirect('index.php?module=office&view=office&success=true');
        } else {
            PlonkWebsite::redirect('index.php?module=office&view=office&success=false');
        }
    }

    public function doMotivate() {
        $erasmusLevelId = ExtendDB::getIdlevel('Extend Mobility Period');
        $this->formid = PlonkFilter::getGetValue('form');
        $this->userid = ExtendDB::getStudentByForm($this->formid);
        $formArray;
        $descrip = "";
        $status;

        $testArray = $_POST;
        $newArray = array_slice($testArray, 0, count($_POST) - 2);

        $jsonArray = json_encode($newArray);

        if (PlonkFilter::getPostValue('acceptedHome') == 1) {
            $descrip = "Extend Mobility Period is approved by home";
            $status = 1;
            $formArray = array(
                'action' => 1,
                'motivationHost' => PlonkFilter::getPostValue('coordinator'),
                'formId' => $this->formid,
                'content' => $jsonArray
            );
            $extend = array(
                'endDate' => PlonkFilter::getPostValue('until')
            );
            ExtendDB::updateErasmusStudent('erasmusstudent', $extend, 'users_email = "' . $this->userid . '"');
        } else {
            $descrip = "Extend Mobility Period is denied by home.";
            $status = 0;
            $formArray = array(
                'action' => 0,
                'motivationHome' => PlonkFilter::getPostValue('coordinator'),
                'formId' => $this->formid,
                'content' => $jsonArray
            );
        }

        $valueEvent = array(
            'reader' => 'Student',
            'timestamp' => date("Y-m-d"),
            'motivation' => PlonkFilter::getPostValue('coordinator'),
            'studentId' => $this->userid,
            'action' => $status,
            'erasmusLevelId' => $erasmusLevelId['levelId'],
            'eventDescrip' => $descrip,
            'readIt' => 0
        );

        $values = array(
            'action' => $status
        );

        ExtendDB::updateErasmusStudent('erasmusstudent', $values, 'users_email = "' . $this->userid . '"');
        ExtendDB::updateErasmusStudent('forms', $formArray, 'formId = "' . $this->formid . '"');
        ExtendDB::insertValues('studentsEvents', $valueEvent);

        $erasmus = ExtendDB::getErasmusInfo($this->userid);

        try {

            $er = array(
                'table' => 'erasmusstudent',
                'data' => array('action' => $erasmus['action'], 'users_email' => $erasmus['users_email']),
                'emailField' => 'users_email'
            );

            $event = array(
                'table' => 'studentsEvents',
                'data' => $valueEvent
            );

            $form = array(
                'table' => 'forms',
                'data' => $formArray,
                'emailField' => 'formId'
            );

            $b = new InfoxController;

            $methods = array('forms:insertInDb', 'forms:toDb', 'forms:toDb');
            $tables = array('studentsEvents', 'erasmusstudent', 'forms');
            $data = array($event, $er, $form);
            $idInst = $erasmus['hostInstitutionId'];
            //$success = $b->dataTransfer($methods, $tables, $data, $idInst);

            if (!empty($_FILES['pic']['tmp_name'][0])) {

                $this->upload($this->formid . '.pdf');
                //$b->fileTransfer('forms:saveFile', 'files/' . $this->userid . '/' . $this->formid . '.pdf', $idInst, $this->userid);
            }

            if ($success !== '0') {
                PlonkWebsite::redirect('index.php?module=office&view=office&success=true');
            } else {
                PlonkWebsite::redirect('index.php?module=office&view=office&success=false');
            }
        } catch (Exception $e) {
            Plonk::dump('failed');
        }
    }

    private function upload($fileName) {
        $uploaddir = "files/" . $this->userid . "";

        if (!PlonkDirectory::exists($uploaddir)) {
            mkdir($uploaddir);
        }

        foreach ($_FILES["pic"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["pic"]["tmp_name"][$key];
                $name = $fileName;
                $uploadfile = $uploaddir . "/" . basename($name);

                if (move_uploaded_file($tmp_name, $uploadfile)) {
                    $cover = $uploadfile;
                } else {
                    echo "Error: File " . $name . " cannot be uploaded.<br/>";
                }
            }
        }
    }

}

?>
