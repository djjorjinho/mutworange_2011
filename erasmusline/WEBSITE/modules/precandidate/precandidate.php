<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PrecandidateController extends PlonkController {

    protected $views = array(
        'precandidate'
    );
    protected $actions = array(
        'submit', 'motivate'
    );
    protected $variablesFixed = array(
        'familyName', 'firstName', 'streetNr', 'tel', 'mobilePhone', 'email', 'instName'
    );
    protected $variablesRequired = array(
        'choice1', 'choice2', 'choice3', 'motivation', 'study', 'cribb', 'cribRent', 'traineeOrStudy', 'scolarship'
    );
    protected $variablesOptional = array(
        'cv', 'transcript', 'certificate'
    );
    protected $optionBelgium = array();
    protected $errors = array();
    protected $rules = array();
    protected $user;
    protected $userid;
    protected $formid;

    private function MainTplAssigns() {
        $this->mainTpl->assign('siteTitle', 'ErasmusLine');
        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/precandidate.java.tpl');
        $this->mainTpl->assign('pageJava', $java->getContent(true));
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="core/css/form.css" type="text/css" media="screen"/>');
    }

    public function showPrecandidate() {
        $this->checkLogged();
        $this->CountryAssign();
        $this->MainTplAssigns();

        //Plonk::dump(PlonkSession::get('id'));
        if (PlonkFilter::getGetValue('form') != null) {

            $this->formid = PlonkFilter::getGetValue('form');
            $this->userid = PrecandidateDB::getstudentByForm($this->formid);
            //Plonk::dump($this->id);
            $this->pageTpl->assign('coordinator', '');
            $this->pageTpl->assign('msgCoordinator', '');
            $this->pageTpl->assign('msgApprove', '');
        } else {
            $this->userid = PlonkSession::get('id');
        }

        $status = PrecandidateDB::getStudentStatus($this->userid);
        if (!empty($status)) {

            $status = PrecandidateDB::getStudentStatus($this->userid);
            $erasmusLevel = PrecandidateDB::getIdLevel($status['statusOfErasmus']);
            $erasmusLevel2 = PrecandidateDB::getIdLevel('Precandidate');

            if ($erasmusLevel['levelId'] >= $erasmusLevel2['levelId']) {

                $this->filledPrecandidate();
                if (PlonkFilter::getGetValue('form') == null) {
                    $this->pageTpl->assign('pageJava', '<script language="Javascript">
                                  window.onload = disable;
                                  function disable() {                                  
                                    var limit = document.forms["precandidate"].elements.length;
                                    for (i=0;i<limit;i++) {
                                      document.forms["precandidate"].elements[i].disabled = true;
                                    }
                                  }
                                </script>');
                }
            } else if ($erasmusLevel['levelId'] < $erasmusLevel2['levelId']) {
                   PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');       
            }            
            else {
                $this->pageTpl->assignOption('oNotFilled');
                $this->fillVariables();
                $this->extraShow();
            }
        } else {
            $this->pageTpl->assignOption('oNotFilled');
            $this->fillVariables();
            $this->extraShow();
        }
    }

    private function filledPrecandidate() {

        $this->pageTpl->assignOption('oFilled');
        $uploadedWhat = PrecandidateDB::getUploadedWhat($this->userid);
        $upload = explode(',', $uploadedWhat['uploadedWhat']);
        $this->pageTpl->assign('cv', '<a href="./files/'.$this->userid.'/'.$upload[0].'" title="CV">'.$upload[0].'</a>');
        $this->pageTpl->assign('transcript', '<a href="./files/'.$this->userid.'/'.$upload[1].'" title="Transcript of Records">'.$upload[1].'</a>');
        $this->pageTpl->assign('certificate', '<a href="./files/'.$this->userid.'/'.$upload[2].'" title="Certificate of foreign language">'.$upload[2].'</a>');


        $json = PrecandidateDB::getJson($this->formid);
        $jsonArray = json_decode($json['content']);

        foreach ($jsonArray as $key => $value) {
            $this->pageTpl->assign($key, $value);
            $this->pageTpl->assign('msg' . ucfirst($key), '');
        }
    }

    private function fillVariables() {
        //Plonk::dump($this->id);
        $this->user = PrecandidateDB::getUser($this->userid);
        foreach ($this->variablesFixed as $value) {
            $this->pageTpl->assign($value, $this->user[$value]);
        }
        foreach ($this->variablesRequired as $value) {
            if (empty($this->fields)) {
                $this->pageTpl->assign('msg' . ucfirst($value), '*');
                $this->pageTpl->assign($value, '');
            } else {
                if ($value === 'kot' || $value === 'kotOverlaten' || $value === 'subsidie') {
                    if ($this->fields[$value] == '1') {
                        $this->pageTpl->assign($value . 'True', 'checked');
                    } else {
                        $this->pageTpl->assign($value . 'False', 'checked');
                    }
                }
                if (!array_key_exists($value, $this->errors)) {
                    $this->pageTpl->assign('msg' . ucfirst($value), '');
                    $this->pageTpl->assign($value, $this->fields[$value]);
                } else {
                    $this->pageTpl->assign('msg' . ucfirst($value), $this->errors[$value]);
                    $this->pageTpl->assign($value, $this->fields[$value]);
                }
            }
        }
        foreach ($this->variablesOptional as $value) {
            if (empty($this->fields)) {
                $this->pageTpl->assign('msg' . ucfirst($value), '');
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

    private function CountryAssign() {
        switch ($this->user['country']) {
            case "BEL":
                $this->pageTpl->assignOption('oBelgium');
                $this->belgiumShow();
                break;
            case "DEU":
                $this->pageTpl->assignOption('oGermany');
                $this->germanyShow();
                break;
            case "GRC":
                $this->pageTpl->assignOption('oGreece');
                $this->greeceShow();
                break;
            case "ISL":
                $this->pageTpl->assignOption('oIsland');
                $this->islandShow();
                break;
            case "BGR":
                $this->pageTpl->assignOption('oBulgary');
                $this->bulgaryShow();
                break;
            case "PRT":
                $this->pageTpl->assignOption('oPortugal');
                $this->portugalShow();
                break;
        }
    }

    private function fillRules() {
        $this->rules[] = "required,choice1,First choice is required.";
        $this->rules[] = "required,choice2,Second choice is required.";
        $this->rules[] = "required,choice3,Third choice is required.";
        $this->rules[] = "required,motivation,Motivation is required";
        $this->rules[] = "reg_exp,cv,^.+.(pdf)$,only PDF-files allowed";
        $this->rules[] = "reg_exp,transcript,^.+.(pdf)$,only PDF-files allowed";
        $this->rules[] = "reg_exp,certificate,^.+.(pdf)$,only PDF-files allowed";
        $this->rules[] = "required,study,Study is required";
        $this->rules[] = "reg_exp,traineeOrStudy,^((?!\.\.\.).+)$,Choice is required";
        $this->rules[] = "required,cribb,Kot vraag verplicht.";
        $this->rules[] = "required,cribRent,Kot overlaten verplicht";
    }

    public function doSubmit() {

        switch ($this->user['country']) {
            case "BEL":
                $this->belgiumSubmit();
                break;
            case "DEU":
                $this->germanySubmit();
                break;
            case "GRC":
                $this->greeceSubmit();
                break;
            case "ISL":
                $this->islandSubmit();
                break;
            case "BGR":
                $this->bulgaryShow();
                break;
            case "PRT":
                $this->portugalSubmit();
                break;
        }
        $this->fillRules();
        $this->mainSubmit();
    }

    private function mainSubmit() {
        $this->fields = $_POST;
        $this->fields['cv'] = $_FILES['pic']['name']['0'];
        
        $this->fields['transcript'] = $_FILES['pic']['name']['1'];
        $this->fields['certificate'] = $_FILES['pic']['name']['2'];
        $this->upload();
        
        $this->errors = validateFields($this->fields, $this->rules);

        $erasmusLevelId = PrecandidateDB::getErasmusLevelId('Precandidate');
        if (empty($this->errors)) {
            $testArray = $_POST;
            $newArray = array_slice($testArray, 0, count($_POST) - 2);

            $jsonArray = json_encode($newArray);
            $values = array(
                'type' => 'Precandidate',
                'date' => date("Y-m-d"),
                'content' => $jsonArray,
                'studentId' => PlonkSession::get('id'),
                'erasmusLevelId' => $erasmusLevelId['levelId'],
                'action' => 2
            );
            $institution = PrecandidateDB::getHomeInstitution();
            $education = PrecandidateDB::getEducation($this->fields['study']);
            $educationPerInstId = PrecandidateDB::getEducationPerInstituteId($institution['instEmail'], $education['educationId']);
            $valueStatus = array(
                'users_email' => PlonkSession::get('id'),
                'educationPerInstId' => $educationPerInstId['educationPerInstId'],
                'homeInstitutionId' => $institution['instEmail'],
                'statusOfErasmus' => 'Precandidate',
                'uploadedWhat' => $_FILES['pic']['name']['0'] . ',' . $_FILES['pic']['name']['1'] . ',' . $_FILES['pic']['name']['2'],
                'action' => 2,
                'traineeOrStudy' => PlonkFilter::getPostValue('traineeOrStudy')
            );
            PrecandidateDB::insertErasmusStudent('erasmusstudent', $valueStatus);

            $valueEvent = array(
                'reader' => 'Student',
                'timestamp' => date("Y-m-d"),
                'motivation' => '',
                'studentId' => PlonkSession::get('id'),
                'action' => '2',
                'erasmusLevelId' => $erasmusLevelId['levelId'],
                'eventDescrip' => 'Precandidate ingevuld.',
                'readIt' => 0
            );

            PrecandidateDB::insertStudentEvent('studentsEvents', $valueEvent);
            PrecandidateDB::insertJson('forms', $values);
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
        }
        else {
            Plonk::dump($this->errors);
        }
    }

    public function doMotivate() {
        $erasmusLevelId = PrecandidateDB::getErasmusLevelId('Precandidate');
        $descrip = "";
        $this->formid = PlonkFilter::getGetValue('form');
            $this->userid = PrecandidateDB::getstudentByForm($this->formid);
        
        if (PlonkFilter::getPostValue('approve') == 1) {
            $descrip = "Precandidate approved";
            $user = array(
                'isValidUser' => 2
            );
            $this->formid = PlonkFilter::getGetValue('form');
            PrecandidateDB::updateErasmusStudent('users', $user, 'email = "' . $this->userid.'"');
        } else {
            $descrip = "Precandidate denied";
        }
        $valueEvent = array(
            'reader' => 'Student',
            'timestamp' => date("Y-m-d"),
            'motivation' => PlonkFilter::getPostValue('coordinator'),
            'studentId' => $this->userid,
            'action' => (int) PlonkFilter::getPostValue('approve'),
            'erasmusLevelId' => $erasmusLevelId['levelId'],
            'eventDescrip' => $descrip,
            'readIt' => 0
        );

        $values = array(
            'action' => (int) PlonkFilter::getPostValue('approve')
        );

        PrecandidateDB::updateErasmusStudent('erasmusstudent', $values, 'users_email = "' . $this->userid.'"');
        PrecandidateDB::updateErasmusStudent('forms', $values, 'formId = '.$this->formid);

        PrecandidateDB::insertStudentEvent('studentsEvents', $valueEvent);
        PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&' . PlonkWebsite::$viewKey . '=precandidates');
    }

    private function extraShow() {


        $this->extra[] = '...';
        $this->extra[] = 'Study';
        $this->extra[] = 'Internship';
        $this->extra[] = 'Study and internship';

        $this->pageTpl->assign('rentYes', 'checked');
        $this->pageTpl->assign('cribYes', 'checked');
        $this->pageTpl->assign('scolYes', 'checked');

        $studies = PrecandidateDB::getEducations();
        $countries = PrecandidateDB::getCountries();

        $this->pageTpl->setIteration('iChoice1');
        foreach ($countries as $value) {
            if (!empty($this->fields)) {
                if ($value['Name'] === $this->fields['choice1']) {
                    $this->pageTpl->assignIteration('choic1', '<option selected=\"true\" value="' . $value['Name'] . '">' . $value['Name'] . '</option>');
                } else {
                    $this->pageTpl->assignIteration('choic1', '<option value="' . $value['Name'] . '"> ' . $value['Name'] . '</option>');
                }
                $this->pageTpl->refillIteration('iChoice1');
            } else {
                $this->pageTpl->assignIteration('choic1', '<option value="' . $value['Name'] . '">' . $value['Name'] . '</option>');
                $this->pageTpl->refillIteration('iChoice1');
            }
        }
        $this->pageTpl->parseIteration('iChoice1');

        $this->pageTpl->setIteration('iChoice2');
        foreach ($countries as $value) {
            if (!empty($this->fields)) {
                if ($value['Name'] === $this->fields['choice2']) {
                    $this->pageTpl->assignIteration('choic2', '<option selected=\"true\" value="' . $value['Name'] . '">' . $value['Name'] . '</option>');
                } else {
                    $this->pageTpl->assignIteration('choic2', '<option value="' . $value['Name'] . '"> ' . $value['Name'] . '</option>');
                }
                $this->pageTpl->refillIteration('iChoice2');
            } else {
                $this->pageTpl->assignIteration('choic2', '<option value="' . $value['Name'] . '">' . $value['Name'] . '</option>');
                $this->pageTpl->refillIteration('iChoice2');
            }
        }
        $this->pageTpl->parseIteration('iChoice2');

        $this->pageTpl->setIteration('iChoice3');
        foreach ($countries as $value) {
            if (!empty($this->fields)) {
                if ($value['Name'] === $this->fields['choice3']) {
                    $this->pageTpl->assignIteration('choic3', '<option selected=\"true\" value="' . $value['Name'] . '">' . $value['Name'] . '</option>');
                } else {
                    $this->pageTpl->assignIteration('choic3', '<option value="' . $value['Name'] . '"> ' . $value['Name'] . '</option>');
                }
                $this->pageTpl->refillIteration('iChoice3');
            } else {
                $this->pageTpl->assignIteration('choic3', '<option value="' . $value['Name'] . '">' . $value['Name'] . '</option>');
                $this->pageTpl->refillIteration('iChoice3');
            }
        }
        $this->pageTpl->parseIteration('iChoice3');

        $this->pageTpl->setIteration('iStudy');
        foreach ($studies as $value) {
            if (!empty($this->fields)) {
                if ($value['educationName'] === $this->fields['study']) {
                    $this->pageTpl->assignIteration('stud', '<option selected=\"true\" value="' . $value['educationName'] . '">' . $value['educationName'] . '</option>');
                } else {
                    $this->pageTpl->assignIteration('stud', '<option value="' . $value['educationName'] . '"> ' . $value['educationName'] . '</option>');
                }
                $this->pageTpl->refillIteration('iStudy');
            } else {
                $this->pageTpl->assignIteration('stud', '<option value="' . $value['educationName'] . '">' . $value['educationName'] . '</option>');
                $this->pageTpl->refillIteration('iStudy');
            }
        }
        $this->pageTpl->parseIteration('iStudy');

        $this->pageTpl->setIteration('iDemand');
        foreach ($this->extra as $value) {
            if (!empty($this->fields)) {
                if ($value['traineeOrStudy'] === $this->fields['traineeOrStudy']) {
                    $this->pageTpl->assignIteration('demand', '<option selected=\"true\" value="' . $value . '">' . $value . '</option>');
                } else {
                    $this->pageTpl->assignIteration('demand', '<option value="' . $value . '"> ' . $value . '</option>');
                }
                $this->pageTpl->refillIteration('iDemand');
            } else {
                $this->pageTpl->assignIteration('demand', '<option value="' . $value . '">' . $value . '</option>');
                $this->pageTpl->refillIteration('iDemand');
            }
        }
        $this->pageTpl->parseIteration('iDemand');
    }

    private function germanyShow() {
        
    }

    private function belgiumShow() {
        
    }

    private function greeceShow() {
        
    }

    private function islandShow() {
        
    }

    private function bulgaryShow() {
        
    }

    private function portugalShow() {
        
    }

    private function belgiumSubmit() {
        
    }

    public function checkLogged() {
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                $this->userid = PlonkSession::get('id');
            } else {
                if (PlonkFilter::getGetValue('form') != null) {
                    $this->pageTpl->assignOption('oCoor');
                } else {
                    PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&' . PlonkWebsite::$viewKey . '=staff');
                }
            }
        }
    }
    private function upload() {
        $id = PlonkSession::get('id');
        $uploaddir = "files/" . $id . "/";

        foreach ($_FILES["pic"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["pic"]["tmp_name"][$key];
                $name = $_FILES["pic"]["name"][$key];
                $uploadfile = $uploaddir . basename($name);

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
