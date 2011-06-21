<?php

class Teardown_finishController extends PlonkController {

    protected $views = array(
        'evaluation'
    );
    protected $actions = array(
        'submit'
    );
    protected $motivation = array(
        'a' => 'Aspect',
        'b' => 'Cultural',
        'c' => "Abroad",
        'd' => 'Language',
        'e' => 'Friends',
        'f' => 'Career',
        'g' => 'Experience',
        'h' => 'Independent',
        'i' => 'other'
    );
    protected $info = array(
        'a' => 'International Office Home',
        'b' => 'Professor Home',
        'c' => "International Office Host",
        'd' => 'Professor Host',
        'e' => 'Students',
        'f' => 'Friends',
        'g' => 'Web Host',
        'h' => 'Website',
        'i' => 'Association',
        'j' => 'Other'
    );
    protected $support = array(
        'a' => 'Home Institute',
        'b' => 'Home Professors',
        'c' => "Host Institute",
        'd' => 'Host Professors',
        'e' => 'Erasmus',
        'f' => 'Association Host',
        'g' => 'Host STudends',
        'h' => 'Other Erasmus Studends',
        'i' => 'Services',
    );
    protected $integration = array(
        'a' => 'Cultural',
        'b' => 'Students',
        'c' => "Other",
    );
    protected $accomodation = array(
        'a' => 'Accomodation Search',
        'b' => 'Living',
        'c' => "Bib",
        'd' => 'Electronics'
    );
    protected $learning = array(
        'a' => 'Global',
        'b' => 'Cultural',
        'c' => 'Talen',
        'd' => 'Independency',
        'e' => 'Independency2',
        'f' => 'Horizon'
    );
    protected $how = array(
        'a' => 'Home Institute',
        'b' => 'Host Institute',
        'c' => 'Colleaques',
        'd' => 'Social',
        'e' => 'Internet',
        'f' => 'PROAVL',
        'g' => 'Other'
    );
    protected $residence = array(
        'a' => 'University residence',
        'b' => 'Private Apartement',
        'c' => 'Shared Apartement',
        'e' => 'Other'
    );
    protected $residenceDiscovery = array(
        'a' => 'Home Institute',
        'b' => 'Friends',
        'c' => 'Private Market',
        'd' => 'Association Host',
        'e' => 'Other'
    );
    protected $scolarship = array(
        'a' => 'Before Start',
        'b' => 'At the Start',
        'c' => 'Betwee',
        'd' => 'Final',
        'e' => 'after'
    );
    protected $finances = array(
        'a' => 'scolarship',
        'b' => 'family',
        'c' => 'savings',
        'd' => 'private',
        'e' => 'work',
        'f' => 'social',
        'g' => 'other'
    );
    protected $languagePrepare = array(
        'a' => 'Yes',
        'b' => 'No'
    );
    protected $languagePrepareWhere = array(
        'a' => 'Host',
        'b' => 'Home',
        'c' => 'Other'
    );
    protected $languageKnowledge = array(
        'a' => 'Before',
        'b' => 'After'
    );
    protected $arrival = array(
        'a' => 'Reception',
        'b' => 'explication',
        'c' => 'Orientation'
    );
    protected $events = array(
        'a' => 'Yes',
        'b' => 'No'
    );
    protected $sufficient = array(
        'a' => 'Sufficient',
    );
    protected $exams = array(
        'a' => 'None',
        'b' => 'Written',
        'c' => 'Oral',
        'd' => 'Multiple Choice',
        'e' => 'Language',
        'f' => 'Other'
    );
    protected $contract = array(
        'a' => 'Contract',
    );
    protected $arrayLanguages = array(
        'Bulgarian', 'German', 'Czech', 'Danish', 'Slovenian', 'Slovak', 'Spanish', 'Ests', 'Finnish', 'French', 'Greek', 'Hungarian', 'English', 'Italian', 'Irish', 'Latvian', 'Lithuanian', 'Maltese', 'Dutch', 'Polish', 'Portugese', 'Romanian', 'Swedish'
    );
    protected $where = array(
        'a' => 'aulas',
        'b' => 'seminars',
        'c' => 'labs',
        'd' => 'team work',
        'e' => 'individual',
        'f' => 'projects',
        'g' => 'online',
        'h' => 'other'
    );
    protected $period = array(
        'a' => 'Period',
    );
    protected $objectives = array(
        'a' => 'Objectives'
    );
    protected $form;

    public function showEvaluation() {
        if (PlonkFilter::getGetValue('form') != null) {
            $formm = Teardown_finishDB::getForm(PlonkFilter::getGetValue('form'));
            $this->form = json_decode($formm['content'],true);
            $this->pageTpl->assign('problems',$this->form['problems']);
            $this->pageTpl->assign('suggestions',$this->form['suggestions']);
        }
        else {
            $this->pageTpl->assign('problems','');
            $this->pageTpl->assign('suggestions','');
        }

        $this->mainTplAssigns();
        $this->fillUser();
        $this->fillInstitute();
        $this->fillRadio($this->motivation, 5, 'iMotivation', 'motivation');
        $this->fillRadio($this->info, 5, 'iInfo', 'info');
        $this->fillRadio($this->support, 5, 'iSupport', 'support');
        $this->fillRadio($this->integration, 5, 'iIntegration', 'integration');
        $this->fillRadio($this->accomodation, 5, 'iAccomodation', 'accomodation');
        $this->fillRadio($this->motivation, 5, 'iMotivationPersonal', 'motivationPersonal');
        $this->fillRadio($this->learning, 5, 'iLearning', 'learning');
        $this->fillRadio($this->languageKnowledge, 5, 'iKnowledge', 'knowledge');
        $this->fillRadio($this->sufficient, 5, 'iSufficient', 'sufficient');
        $this->fillRadio($this->contract, 2, 'iContract', 'contract');
        $this->fillRadio($this->period, 3, 'iPeriod', 'period');
        $this->fillRadio($this->objectives, 3, 'iObjective', 'objective');

        $this->fillCheckbox($this->residence, 5, 'iResidence', 'residence');
        $this->fillCheckbox($this->how, 7, 'iHow', 'how');
        $this->fillCheckbox($this->residenceDiscovery, 7, 'iResidenceDiscovery', 'residenceDiscovery');
        $this->fillCheckbox($this->scolarship, 7, 'iScolarship', 'scolarship');
        $this->fillCheckbox($this->finances, 7, 'iFinances', 'finances');
        $this->fillCheckbox($this->exams, 7, 'iExams', 'exams');
        $this->fillCheckbox($this->languagePrepare, 7, 'iLanguagePrepare', 'languagePrepare');
        $this->fillCheckbox($this->languagePrepareWhere, 7, 'iLanguagePrepareWhere', 'languagePrepareWhere');
        $this->fillCheckbox($this->arrival, 7, 'iArrival', 'arrival');
        $this->fillCheckBox($this->events, 2, 'iEvents', 'events');
        $this->fillCheckBox($this->where, 5, 'iWhere', 'where');

        $this->fillLanguages($this->arrayLanguages, 'iArrayLanguages', 'arrayLanguage');
    }

    public function mainTplAssigns() {
        $this->mainTpl->assign('siteTitle', 'Evaluation');
        $this->mainTpl->assign("pageMeta", '<link rel="stylesheet" href="./core/css/form.css" type="text/css" />');

        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/evaluation.java.tpl');
        $this->mainTpl->assign('pageJava', $java->getContent(true));
        $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=home&amp;view=home">Home</a><a href="index.php?module=teardown_finish&amp;view=evaluation">Evaluation</a>');
    }

    public function fillUser() {
        $this->id = PlonkSession::get('id');
        $info = Teardown_finishDB::getItemsById($this->id);
        $erasmuslevel = Teardown_finishDB::getErasmusById($this->id);

        $this->pageTpl->assign('fName', $info['firstName']);
        $this->pageTpl->assign('faName', $info['familyName']);
        $this->pageTpl->assign('postal', $info['postalCode']);
        $this->pageTpl->assign('street', $info['streetNr']);
        $this->pageTpl->assign('city', $info['city']);
        if ($info['sex'] === '1') {
            $this->pageTpl->assign('sex', 'Male');
        } else {
            $this->pageTpl->assign('sex', 'Female');
        }
        $this->pageTpl->assign('sex', $info['sex']);
        $this->pageTpl->assign('userLevel', $info['userLevel']);


        $this->pageTpl->assign('nationality', $info['country']);
    }

    public function fillInstitute() {
        $erasmus = Teardown_finishDB::getErasmusById($this->id);
        //Plonk::dump($erasmus);
        if (!empty($erasmus)) {
            $this->pageTpl->assign('start', $erasmus['startDate']);
            $this->pageTpl->assign('end', $erasmus['endDate']);
        } else {
            $this->pageTpl->assign('start', '');
            $this->pageTpl->assign('end', '');
        }

        $home = Teardown_finishDB::getHome($this->id);
        if (!empty($home)) {
            $this->pageTpl->assign('home', $home['instName']);
            $this->pageTpl->assign('hCooordinator', $home['familyName'] . ' ' . $home['firstName']);
        } else {
            $this->pageTpl->assign('home', '');
            $this->pageTpl->assign('hCooordinator', '');
        }

        $host = Teardown_finishDB::getHost($this->id);
        if (!empty($host)) {
            $this->pageTpl->assign('destination', $host['instName']);
            $this->pageTpl->assign('dCoordinator', $host['familyName'] . ' ' . $host['firstName']);
        } else {
            $this->pageTpl->assign('destination', '');
            $this->pageTpl->assign('dCoordinator', '');
        }

        $study = Teardown_finishDB::getStudy($this->id);
        if (!empty($study)) {
            $this->pageTpl->assign('study', $study['educationName']);
        } else {
            $this->pageTpl->assign('study', '');
        }
    }

    public function fillRadio($array, $number, $iteration, $iterationValue) {
        $this->pageTpl->setIteration($iteration);
        $count = ceil($number / 2) - 1;
        foreach ($array as $key => $value) {
            $string = "<td>" . $value . "</td>";
            for ($i = 0; $i < $number; $i++) {
                if ($this->form != null) {
                    if($this->form[$iterationValue.$key] == $i) {
                        $checked = 'checked="checked"';
                    }
                    else {
                        $checked = "";
                    }
                } else {
                    if ($i == $count) {
                        $checked = 'checked="checked"';
                    } else {
                        $checked = "";
                    }
                }
                $string .= '<td><input type="radio" ' . $checked . 'id="' . $key . $i . '" name="' . $iterationValue . $key . '" value="' . $i . '"  /></td>';
            }
            $this->pageTpl->assignIteration($iterationValue, $string);
            $this->pageTpl->refillIteration($iteration);
        }
        $this->pageTpl->parseIteration($iteration);
    }

    public function fillCheckBox($array, $number, $iteration, $iterationValue) {
        $this->pageTpl->setIteration($iteration);
        foreach ($array as $key => $value) {
            
            if($this->form != null) {
                if(in_array($array[$key],$this->form[$iterationValue])) {
                    $checked = 'checked="checked"';
                }
                else {
                    $checked = "";
                }
            
            }
            else {
                $checked = "";
            }
            
            $string = '<input type="checkbox" '.$checked.' id="' . $iterationValue . $key . '" name="' . $iterationValue.'[]" value="' . $value . '" class="validate[minCheckbox[1]] checkbox" />' . $value . '<br />';

            $this->pageTpl->assignIteration($iterationValue, $string);
            $this->pageTpl->refillIteration($iteration);
        }
        $this->pageTpl->parseIteration($iteration);
    }

    public function fillLanguages($array, $iteration, $iterationValue) {
        $this->pageTpl->setIteration($iteration);

        foreach ($array as $key) {
           if($this->form != null) {
                if(in_array($key,$this->form[$iterationValue])) {
                    $checked = 'checked="checked"';
                }
                else {
                    $checked = "";
                }
            
            }
            else {
                $checked = "";
            }
            $string = '<input type="checkbox" '.$checked.' id="' . $iterationValue . $key . '" name="' . $iterationValue.'[]" value="' . $key . '" class="validate[minCheckbox[1]] checkbox" />' . $key . '<br />';

            $this->pageTpl->assignIteration($iterationValue, $string);
            $this->pageTpl->refillIteration($iteration);
        }
        $this->pageTpl->parseIteration($iteration);
    }

    public function doSubmit() {

        $testArray = $_POST;
        $newArray = array_slice($testArray, 0, count($_POST) - 2);

        $jsonArray = json_encode($newArray);
        $erasmusLevel = Teardown_finishDB::getErasmusLevelId('Evaluation Questionaire');
        $valuess = array(
            'formId' => Functions::createRandomString(),
            'type' => 'Evaluation Questionaire',
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
            'action' => 30,
            'erasmusLevelId' => $erasmusLevel['levelId'],
            'eventDescrip' => 'Filled in Student Evaluation Questionaire',
            'readIt' => 0
        );
        
        $erasmusStudent = array(
            'statusOfErasmus' => "Evaluation Questionaire",
            'action' => 2
        );

        Teardown_finishDB::insertStudentEvent('studentsEvents', $valueEvent);

        Teardown_finishDB::insertStudentEvent('forms', $valuess);
        
        Teardown_finishDB::updateErasmusStudent('erasmusStudent', $erasmusStudent, 'users_email = "'.PlonkSession::get('id').'"');

        PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
    }

}

?>
