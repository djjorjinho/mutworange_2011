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
        'firstChoice', 'secondChoice', 'thirdChoice', 'motivation', 'study'
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
        $this->fillVariables();
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
        $this->rules[] = "required,firstChoice,First choice is required.";
        $this->rules[] = "required,secondChoice,Second choice is required.";
        $this->rules[] = "required,thirdChoice,Third choice is required.";
        $this->rules[] = "required,motivation,Motivation is required";
        $this->rules[] = "reg_exp,cv,^.+.(pdf)$,only PDF-files allowed";
        $this->rules[] = "reg_exp,transcript,^.+.(pdf)$,only PDF-files allowed";
        $this->rules[] = "reg_exp,certificate,^.+.(pdf)$,only PDF-files allowed";
        $this->rules[] = "required,study,Study is required";
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

        if (empty($this->errors)) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        }
    }

    private function belgiumShow() {
        $this->variablesRequired[] = 'aanvraag';
        $this->variablesRequired[] = 'kot';
        $this->variablesRequired[] = 'kotOverlaten';
        $this->variablesRequired[] = 'subsidie';
        $this->optionBelgium[] = '...';
        $this->optionBelgium[] = 'Study';
        $this->optionBelgium[] = 'Placement in bedrijf, onderzoeks- of opleidingscentrum';
        $this->optionBelgium[] = 'Belgica uitwisseling';
        $this->optionBelgium[] = 'Ontwikkelingssamenwerking';

        $this->pageTpl->assign('kotOverlatenTrue', 'checked');
        $this->pageTpl->assign('kotTrue', 'checked');
        $this->pageTpl->assign('subsidieTrue', 'checked');

        $this->pageTpl->setIteration('iAanvraag');
        foreach ($this->optionBelgium as $value) {
            if (!empty($this->fields)) {
                if ($value === $this->fields['aanvraag']) {
                    $this->pageTpl->assignIteration('aanvraag', '<option selected=\"true\" value="' . $value . '">' . $value . '</option>');
                } else {
                    $this->pageTpl->assignIteration('aanvraag', '<option value="' . $value . '"> ' . $value . '</option>');
                }
                $this->pageTpl->refillIteration('iAanvraag');
            } else {
                $this->pageTpl->assignIteration('aanvraag', '<option value="' . $value . '">' . $value . '</option>');
                $this->pageTpl->refillIteration('iAanvraag');
            }
        }
        $this->pageTpl->parseIteration('iAanvraag');
    }

    private function germanyShow() {
        
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
        $this->rules[] = "reg_exp,aanvraag,^((?!\.\.\.).+)$,Aanvraag keuze is verplicht";
        $rules[] = "required,kot,Kot vraag verplicht.";
        $rules[] = "required,kotOverlaten,Kot overlaten verplicht";
    }

    private function checkLogged() {
        
        if (!PlonkSession::exists('loggedIn')) {
            $this->mainTpl->assignOption('oNotLogged');
            $this->pageTpl->assignOption('oNotLogged');

            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            
            $this->mainTpl->assignOption('oLogged');
            $this->pageTpl->assignOption('oLogged');
            $id = PlonkSession::get('id');
            //$this->user = PrecandidateDB::getUserById($id);
            $this->user = PrecandidateDB::getUserTest($id);
            
        }
    }

}

?>
