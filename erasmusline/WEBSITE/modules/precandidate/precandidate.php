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
        'submit'
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
<<<<<<< HEAD

        $status = PrecandidateDB::getStudentStatus(PlonkSession::get('id'));
        if (!empty($status)) {
            if ($status['statusOfErasmus'] === "Precandidate") {
=======
        
        //Plonk::dump(PlonkSession::get('id'));
        $status = PrecandidateDB::getStudentStatus(PlonkSession::get('id'));
        
        if (!empty($status)) {
            $status = PrecandidateDB::getStudentStatus(PlonkSession::get('id'));
            $erasmusLevel = PrecandidateDB::getIdLevel($status['statusOfErasmus']);
            $erasmusLevel2 = PrecandidateDB::getIdLevel('Student Application Form');
            
            if ($erasmusLevel['levelId'] > $erasmusLevel2['levelId']) {
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
                $this->filledPrecandidate();
                
            } else {
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
        $uploadedWhat = PrecandidateDB::getUploadedWhat(PlonkSession::get('id'));
        $upload = explode(',', $uploadedWhat['uploadedWhat']);
        $this->pageTpl->assign('cv', $upload[0]);
        $this->pageTpl->assign('transcript',$upload[1]);
        $this->pageTpl->assign('certificate',$upload[2]);
        
        
        $json = PrecandidateDB::getJson(PlonkSession::get('id'));
        $jsonArray = json_decode($json['content']);

        foreach ($jsonArray as $key => $value) {
            $this->pageTpl->assign($key, $value);
            $this->pageTpl->assign('msg' . ucfirst($key), '');
        }
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

    private function fillVariables() {
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
        $this->fields['cv'] = $_FILES['cv']['name'];
        $this->fields['transcript'] = $_FILES['transcript']['name'];
        $this->fields['certificate'] = $_FILES['certificate']['name'];
        $this->errors = validateFields($this->fields, $this->rules);
        
        $erasmusLevelId = PrecandidateDB::getErasmusLevelId('Precandidate');
        if (empty($this->errors)) {
            $testArray = $_POST;
            $newArray = array_slice($testArray, 0, count($_POST) - 2);

            $jsonArray = json_encode($newArray);
            $values = array(
                'type' => 'precandidate',
                'date' => date("Y-m-d"),
                'content' => $jsonArray,
                'studentId' => PlonkSession::get('id'),
                'erasmusLevelId' => $erasmusLevelId['levelId']
            );
            $institution = PrecandidateDB::getHomeInstitution();
            $education = PrecandidateDB::getEducation($this->fields['study']);
            $educationPerInstId = PrecandidateDB::getEducationPerInstituteId($institution['instId'], $education['educationId']);
            $valueStatus = array(
                'studentId' => PlonkSession::get('id'),
                'educationPerInstId' => $educationPerInstId['educationPerInstId'],
                'homeInstitutionId' => $institution['instId'],
                'statusOfErasmus' => 'Precandidate',
                'uploadedWhat' => $_FILES['cv']['name'] . ',' . $_FILES['transcript']['name'] . ',' . $_FILES['certificate']['name'],
            );
            PrecandidateDB::insertErasmusStudent('erasmusstudent', $valueStatus);

            $valueEvent = array(
                'event' => 'sdfsdf',
                'timestamp' => date("Y-m-d"),
                'motivation' => '',
                'erasmusStudentId' => PlonkSession::get('id'),
                'action' => 'pending',
                'erasmusLevelId' => $erasmusLevelId['levelId']
            );

            PrecandidateDB::insertStudentEvent('studentsEvents', $valueEvent);
            PrecandidateDB::insertJson('forms', $values);
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        }
    }

    private function extraShow() {
<<<<<<< HEAD

=======
        
        
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
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
<<<<<<< HEAD
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
                Plonk::dump($value);
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
=======
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
                Plonk::dump($value);
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
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
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
            $this->mainTpl->assignOption('oLogged');
            if (PlonkSession::get('id') == 0) {
                $this->mainTpl->assignOption('oAdmin');
                $this->id = PlonkSession::get('id');
            } else {
                $this->user = PrecandidateDB::getUser(PlonkSession::get('id'));
            }
        }
    }

}

?>
