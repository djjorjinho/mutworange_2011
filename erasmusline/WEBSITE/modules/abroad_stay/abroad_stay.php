<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Abroad_stayController extends PlonkController {

    protected $id;
    private $courses = 0;
    private $errors = array(); // set the errors array to empty, by default
    private $fields = array(); // stores the field values
    protected $views = array(
        'cert_arrival', 'cert_stay', 'cert_departure', 'changagreement'
    );
    protected $actions = array(
        'submit', 'agree'
    );
    protected $variables = array(
        'hostInstitution', 'dateArrival', 'dateDeparture', 'student'
    );

    public function showCert_departure() {
        $this->mainTplAssigns();
    }

    public function showCert_arrival() {
        $this->mainTplAssigns();
    }

    public function showCert_stay() {
        $this->mainTplAssigns();
    }

    private function mainTplAssigns() {
        // Assign main properties
        $this->mainTpl->assign('siteTitle', 'ErasmusLine');
        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/cert.java.tpl');
        $this->mainTpl->assign('pageMeta', $java->getContent(true));
        $this->mainTpl->assign('pageJava', '<script type="text/javascript">         
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$(\'#suggestions\').hide();
		} else {
			$.post("modules/abroad_stay/rpc.php", {queryString: ""+inputString+""}, function(data){
                        
				if(data.length >0) {
					$(\'#suggestions\').show();
					$(\'#autoSuggestionsList\').html(data);
				}
			});
		}
	} // lookup
	function fill(User,Institution) {
		$(\'#inputString\').val(User);
                $(\'#hostInstitution\').val(Institution);
		setTimeout("$(\'#suggestions\').hide();", 200);
                
	}</script>');


        $this->pageTpl->assign('academicYear', " 2010-2011");
        foreach ($this->variables as $value)
            if (empty($this->fields)) {
                $this->pageTpl->assign('msg' . ucfirst($value), '*');
                $this->pageTpl->assign($value, '');
                $this->pageTpl->assign('dateArrival', date("Y-m-d"));
                $this->pageTpl->assign('dateDeparture', date("Y-m-d"));
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

    private function fillRules() {
        $this->rules[] = "required,datepickerArrival,Date of Arrival Required";
        $this->rules[] = "required,datepickerDeparture,Date of departure is required.";
        $this->rules[] = "required,student,Student name required.";
        $this->rules[] = "required,hostInstitution,Host Institution required.";
    }

    public function doSubmit() {
        $this->fillRules();
        $this->errors = validateFields($_POST, $this->rules);
        if (!empty($this->errors)) {
            $this->fields = $_POST;
        }
    }

    public function showChangagreement() {
        // Main Layout
        // Logged or not logged, that is the question...
        $inputs = array('signDate', 'signDate', 'signDate2', 'signDepSignDate', 'signInstSignDate', 'signDepSignDate2', 'signInstSignDate2', 'date');

        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/><link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/><link rel="stylesheet" href="./core/css/form.css" type="text/css"/>');
        $this->mainTpl->assign('siteTitle', 'Changes to Learning Agreement');

        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile');

        $g = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/changagreement.java.tpl');

        $this->mainTpl->assign('pageJava', $g->getContent(true));

        $this->pageTpl->assign('courseCount', $this->courses);

        if (empty($this->errors)) {
            $this->fields = array('code0' => '', 'ects0' => '', 'title0' => '', 'delOrAdd0' => '0');
        }

        if (!empty($this->errors)) {
            foreach ($inputs as $key => $value) {
                if (array_key_exists($value, $this->errors)) {
                    $this->pageTpl->assign('msg' . ucfirst($value), $this->errors[$value]);
                } else {
                    $this->pageTpl->assign('msg' . ucfirst($value), '*');
                }
                $this->pageTpl->assign($value, $this->fields[$value]);
            }
        } else {
            foreach ($inputs as $value) {
                if (strrpos($value, 'Date') !== false) {
                    $this->pageTpl->assign($value, date('Y-m-d'));
                } else {
                    $this->pageTpl->assign($value, '');
                }
                $this->pageTpl->assign('msg' . ucfirst($value), '*');
            }
        }

        $this->pageTpl->setIteration('iCoursesOrNot');
        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        for ($i = 0; $i <= $this->courses; $i++) {
            $add = $this->fields["delOrAdd" . $i] == '1' ? "checked" : "";
            $del = $this->fields["delOrAdd" . $i] == '1' ? "" : "checked";

            if (array_key_exists('code' . $i, $this->errors)) {
                $error = $this->errors['code' . $i];
            } else if (array_key_exists('title' . $i, $this->errors)) {
                $error = $this->errors['title' . $i];
            } else if (array_key_exists('ects' . $i, $this->errors)) {
                $error = $this->errors['ects' . $i];
            } else {
                $error = '*';
            }


            // loops through all the users (except for the user with id 1 = admin) and assigns the values
            $this->pageTpl->assignIteration('row', '<tr>
                       <td><input class="validate[required, custom[onlyLetterNumber]]" type="text" id="code' . $i . '" name="code' . $i . '" value="' . $this->fields["code" . $i] . '" /></td>
                        <td><input class="validate[required, custom[onlyLetterNumber]]" type="text" id="title' . $i . '" name="title' . $i . '" value="' . $this->fields["title" . $i] . '" /></td>      
                        <td><input type="radio" name="delOrAdd' . $i . '" value="1" class="validate[required] radio" id="een2' . $i . '" ' . $add . ' /><input type="radio" name="delOrAdd' . $i . '" value="0" id="nul2' . $i . '" ' . $del . '/></td>
                        <td><input class="validate[required,custom[onlyNumberSp]]" type="text" id="ects' . $i . '" name="ects' . $i . '" value="' . $this->fields["ects" . $i] . '" /></td>
                        <td><span class="req">' . $error . '</span></td> 
                        </tr>');

            $this->pageTpl->refillIteration('iCoursesOrNot');
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iCoursesOrNot'); // alternative: $tpl->parseIteration();
    }

    /**
     * check if user is logged in
     */
    public function checkLogged() {
         if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') == 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                $this->id = PlonkSession::get('id');
            } else {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&' . PlonkWebsite::$viewKey . '=staff');
            }
        }
    }

}

