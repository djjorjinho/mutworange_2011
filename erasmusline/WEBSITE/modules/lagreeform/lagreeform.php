<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class LagreeformController extends PlonkController {

    protected $id;
    private $works = 0;
    private $languages = 0;
    private $courses = 0;
    private $errors = array(); // set the errors array to empty, by default
    private $fields = array(); // stores the field values
    private $inputs = array('accepted', 'abroad', 'acaYear', 'study', 'sendDepCoorName', 'sendDepCoorTel', 'sendDepCoorMail', 'sendDepCoorName', 'sendDepCoorTel', 'sendDepCoorMail', 'cAddress', 'daateValid', 'cTel', 'pTel', 'pAddress', 'recInstitut', 'coountry', 'daateFrom', 'daateUntill', 'duration',
        'ectsPoints', 'motivation', 'motherTongue', 'instrLanguage', 'diplome', 'yEducation', 'whichInst', 'signDepSignDate', 'signInstSignDate');
    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
        'lagreement', 'applicform'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array(
        'applic', 'agree'
    );

    /**
     * check if user is logged in
     */
    public function checkLogged() {
        //Plonk::dump(PlonkSession::get('id').'hgdjdh');
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            $this->id = PlonkSession::get('id');
            $this->mainTpl->assignOption('oLogged');
            if (PlonkSession::get('id') == 0) {
                $this->mainTpl->assignOption('oAdmin');
            }
        }
    }

    private function filledLagreement() {
        $this->mainTpl->assign('pageMeta', '');
        $this->pageTpl->assignOption('oFilled');

        $json = LagreeformDB::getJson(PlonkSession::get('id'), 'Learning Agreement');
        $jsonArray = json_decode($json['content'], true);


        $courses = (int)$jsonArray['courseCount'];

        $this->pageTpl->setIteration('iCourses');

        for ($i = 0; $i <= $courses; $i++) {

            $this->pageTpl->assignIteration('row', '<tr>
	                        <td><input class="validate[required, custom[onlyLetterNumber]]" type="text" id="code' . $i . '" name="code' . $i . '" value="' . $jsonArray["code" . $i] . '" /></td>
	                        <td><input onkeyup="lookup(' . $i . ',this.value);" onclick="fill();" class="validate[required, custom[onlyLetterNumber]]" type="text" id="title' . $i . '" name="title' . $i . '" value="' . $jsonArray["title" . $i] . '" /><div class="suggestionsBox' . $i . '" id="suggestions' . $i . '" style="display: none;">
			<div class="suggestionList' . $i . '" id="autoSuggestionsList' . $i . '">
				&nbsp;
	                </div>
		</div></td>
	                        <td><input class="validate[required,custom[onlyNumberSp]]" type="text" id="ects' . $i . '" name="ects' . $i . '" value="' . $jsonArray["ects" . $i] . '" /></td>
	                          </tr>');
            $this->pageTpl->refillIteration('iCourses');
            unset($jsonArray['code' . $i]);
            unset($jsonArray['ects' . $i]);
            unset($jsonArray['title' . $i]);
        }

        $this->pageTpl->parseIteration('iCourses');
        foreach ($jsonArray as $key => $value) {
            $this->pageTpl->assign($key, $value);
            $this->pageTpl->assign('msg' . ucfirst($key), '');
        }
        $this->mainTpl->assign('pageJava', '<script language="Javascript">
                                  window.onload = disable;
                                  function disable() {                                  
                                    var limit = document.forms["lagreement"].elements.length;
                                    for (i=0;i<limit;i++) {
                                      document.forms["lagreement"].elements[i].disabled = true;
                                    }
                                  }
                                </script>');
    }

    public function showLagreement() {

        // Main Layout
        // Logged or not logged, that is the question...
        $inputs = array('signDate', 'signDepSignDate', 'signInstSignDate', 'signDepSignDate2', 'signInstSignDate2', 'sign', 'signDepSign', 'signInstSign', 'signDepSign2', 'signInstSign2');

        $this->checkLogged();

        $status = LagreeformDB::getStudentStatus(PlonkSession::get('id'));
        $erasmusLevel = LagreeformDB::getIdLevel($status['statusOfErasmus']);
        $erasmusLevel2 = LagreeformDB::getIdLevel('Learning Agreement');
        //Plonk::dump($erasmusLevel['levelId'].' - '.$erasmusLevel2['levelId']);
        if ($erasmusLevel['levelId'] >= $erasmusLevel2['levelId']) {
            $this->filledLagreement();
            $this->fillFixed();
            return;
        } else {
            $this->pageTpl->assignOption('oNotFilled');
        }

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/><link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/><link rel="stylesheet" href="./core/css/form.css" type="text/css"/>');
        $this->mainTpl->assign('siteTitle', 'Learning Agreement');

        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile');

        $g = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/lagreement.java.tpl');

        $this->mainTpl->assign('pageJava', $g->getContent(true) . '<script type="text/javascript">
	                function lookup(id,inputString) {
	                var id = id;
			if(inputString.length == 0) {
				// Hide the suggestion box.
				$(\'#suggestions\'+id).hide();
			} else {
				$.post("modules/lagreeform/rpc4.php", {id: ""+id+"",queryString: ""+inputString+""}, function(data){

					if(data.length >0) {
						$(\'#suggestions\'+id).show();
						$(\'#autoSuggestionsList\'+id).html(data);
					}
				});
			}
		} // lookup
		function fill(id, course, code, ects) {
			$(\'#title\'+id).val(course);
	                $(\'#code\'+id).val(code);
	                $(\'#ects\'+id).val(ects);
			setTimeout("$(\'#suggestions" + id + "\').hide();", 200);

		}</script>');

<<<<<<< HEAD
        $sendCountry = LagreeformDB::getSendInst();

        $receivingInst = LagreeformDB::getHostInstitution($this->id);

        $education = LagreeformDB::getStudyById($this->id);

        $this->pageTpl->assign('study', $education['educationName']);
        $this->pageTpl->assign('nameStudent', $education['firstName'] . ' ' . $education['familyName']);



        $this->pageTpl->assign('acaYear', ACADEMICYEAR);
        $this->pageTpl->assign('sendingInstitution', INSTITUTE);
        $this->pageTpl->assign('countrySendingInstitution', $sendCountry['Name']);

        $this->pageTpl->assign('receivingInstitution', $receivingInst['instName']);
        $this->pageTpl->assign('countryReceivingInstitution', $receivingInst['Name']);
=======
        $this->fillFixed();
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd


        if (empty($this->errors)) {
            $this->fields = array('code0' => '', 'ects0' => '', 'title0' => '');
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

        $this->pageTpl->setIteration('iCourses');
        // loops through all the users (except for the user with id 1 = admin) and assigns the values
        for ($i = 0; $i <= $this->courses; $i++) {
            if (array_key_exists('code' . $i, $this->errors)) {
                $error = $this->errors['code' . $i];
            } else if (array_key_exists('title' . $i, $this->errors)) {
                $error = $this->errors['title' . $i];
            } else if (array_key_exists('ects' . $i, $this->errors)) {
                $error = $this->errors['ects' . $i];
            } else {
                $error = '*';
            }
            $this->pageTpl->assignIteration('row', '<tr>
	                        <td><input class="validate[required, custom[onlyLetterNumber]]" type="text" id="code' . $i . '" name="code' . $i . '" value="' . $this->fields["code" . $i] . '" /></td>
	                        <td><input onkeyup="lookup(' . $i . ',this.value);" onclick="fill();" class="validate[required, custom[onlyLetterNumber]]" type="text" id="title' . $i . '" name="title' . $i . '" value="' . $this->fields["title" . $i] . '" /><div class="suggestionsBox' . $i . '" id="suggestions' . $i . '" style="display: none;">
			<div class="suggestionList' . $i . '" id="autoSuggestionsList' . $i . '">
				&nbsp;
	                </div>
		</div></td>
	                        <td><input class="validate[required,custom[onlyNumberSp]]" type="text" id="ects' . $i . '" name="ects' . $i . '" value="' . $this->fields["ects" . $i] . '" /></td>
	                        <td><span class="req">' . $error . '</span></td>
	                          </tr>');
            // refill the iteration (mandatory!)
            $this->pageTpl->refillIteration('iCourses');
        }

        // parse the iteration
        $this->pageTpl->parseIteration('iCourses'); // alternative: $tpl->parseIteration();

        $this->errors = null;
    }
    
    private function fillFixed() {
        $sendCountry = LagreeformDB::getSendInst();

        $receivingInst = LagreeformDB::getHostInstitution($this->id);

        $education = LagreeformDB::getStudyById($this->id);

        $this->pageTpl->assign('study', $education['educationName']);
        $this->pageTpl->assign('nameStudent', $education['firstName'] . ' ' . $education['familyName']);

        $this->pageTpl->assign('acaYear', ACADEMICYEAR);
        $this->pageTpl->assign('sendingInstitution', INSTITUTE);
        $this->pageTpl->assign('countrySendingInstitution', $sendCountry['Name']);

        $this->pageTpl->assign('receivingInstitution', $receivingInst['instName']);
        $this->pageTpl->assign('countryReceivingInstitution', $receivingInst['Name']);
    }

    private function filledApplicform() {
        $this->mainTpl->assign('pageMeta', '');
        $this->pageTpl->assignOption('oFilled');

        $json = LagreeformDB::getJson(PlonkSession::get('id'),'Student Application Form');
        $jsonArray = json_decode($json['content'], true);


        $language = (int)$jsonArray['languageCount'];

        $work = (int)$jsonArray['workCount'];

        //Plonk::dump($work.' - '.$language);

        $this->pageTpl->setIteration('iLanguages');
        //Plonk::dump($jsonArray);

        for ($i = 0; $i <= $language; $i++) {
            $studyThisYes = $jsonArray["studyThis" . $i] == '1' ? "checked" : "";
            $studyThisNo = $jsonArray["studyThis" . $i] == '1' ? "" : "checked";
            $knowledgeThisYes = $jsonArray["knowledgeThis" . $i] == '1' ? "checked" : "";
            $knowledgeThisNo = $jsonArray["knowledgeThis" . $i] == '1' ? "" : "checked";
            $extraPrepYes = $jsonArray["extraPrep" . $i] == '1' ? "checked" : "";
            $extraPrepNo = $jsonArray["extraPrep" . $i] == '1' ? "" : "checked";

            $this->pageTpl->assignIteration('language', '<tr>
                                                        <td><input type="text" class="validate[required,custom[onlyLetterSp]] text-input" id="language' . $i . '" name="language' . $i . '" value="' . $jsonArray["language" . $i] . '" /></td>
                                                        <td><input type="radio" name="studyThis' . $i . '" value="1" ' . ' class="validate[required] radio" id="een' . $i . '"' . $studyThisYes . ' /><input type="radio" name="studyThis' . $i . '" value="0" id="nul' . $i . '" ' . $studyThisNo . ' ></td>
                                                        <td><input type="radio" name="knowledgeThis' . $i . '" value="1" class="validate[required] radio" id="een2' . $i . '" ' . $knowledgeThisYes . ' /><input type="radio" name="knowledgeThis' . $i . '" value="0" id="nul2' . $i . '" ' . $knowledgeThisNo . '></td>
                                                        <td><input type="radio" name="extraPrep' . $i . '" value="1" class="validate[required] radio" id="een3' . $i . '" ' . $extraPrepYes . ' /><input type="radio" name="extraPrep' . $i . '" value="0" id="nul3' . $i . '" ' . $extraPrepNo . '></td>
                                                        </tr>');
            $this->pageTpl->refillIteration('iLanguages');
            unset($jsonArray['studyThis' . $i]);
            unset($jsonArray['knowledgeThis' . $i]);
            unset($jsonArray['extraPrep' . $i]);
            unset($jsonArray['language' . $i]);
        }

        $this->pageTpl->parseIteration('iLanguages');

        $this->pageTpl->setIteration('iWorks');

        for ($i = 0; $i < $work; $i++) {

            $this->pageTpl->assignIteration('work', '<tr>
                                                    <td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="type' . $i . '" name="type' . $i . '" value="' . $jsonArray["type" . $i] . '" /></td>
                                                    <td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="firm' . $i . '" name="firm' . $i . '" value="' . $jsonArray["firm" . $i] . '" /></td>
                                                    <td><input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" id="date' . $i . '" name="date' . $i . '" value="' . $jsonArray["date" . $i] . '" /></td>
                                                    <td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="country' . $i . '" name="country' . $i . '" value="' . $jsonArray["country" . $i] . '" /></td>
                                                    </tr>');
            $this->pageTpl->refillIteration('iWorks');
            unset($jsonArray['type' . $i]);
            unset($jsonArray['firm' . $i]);
            unset($jsonArray['date' . $i]);
            unset($jsonArray['country' . $i]);
        }

        $this->pageTpl->parseIteration('iWorks');

        Plonk::dump($jsonArray);
        foreach ($jsonArray as $key => $value) {
            $this->pageTpl->assign($key, $value);
            $this->pageTpl->assign('msg' . ucfirst($key), '');
        }
        $this->mainTpl->assign('pageJava', '<script language="Javascript">
                                  window.onload = disable;
                                  function disable() {                                  
                                    var limit = document.forms["studApplicForm"].elements.length;
                                    for (i=0;i<limit;i++) {
                                      document.forms["studApplicForm"].elements[i].disabled = true;
                                    }
                                  }
                                </script>');
    }

    public function showApplicform() {

        $this->checkLogged();

        $status = LagreeformDB::getStudentStatus(PlonkSession::get('id'));
        $erasmusLevel = LagreeformDB::getIdLevel($status['statusOfErasmus']);
        $erasmusLevel2 = LagreeformDB::getIdLevel('Student Application Form');
        if ($erasmusLevel['levelId'] >= $erasmusLevel2['levelId']) {
            $this->filledApplicform();
            return;
        } else {
            $this->pageTpl->assignOption('oNotFilled');
        }

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/><link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/><link rel="stylesheet" href="./core/css/form.css" type="text/css"/>');
        $this->mainTpl->assign('siteTitle', 'Student Application Form');

        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile');

        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/applicform.java.tpl');

        $this->mainTpl->assign('pageJava', $java->getContent(true) . '<script type="text/javascript">
	
	function lookup2(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$(\'#suggestions2\').hide();
		} else {
			$.post("modules/lagreeform/rpc2.php", {queryString: ""+inputString+""}, function(data){

				if(data.length >0) {
					$(\'#suggestions2\').show();
					$(\'#autoSuggestionsList2\').html(data);
				}
			});
		}
	} // lookup
	function fill2(Name,Tel,Email) {
		$(\'#sendDepCoorName\').val(Name);
                $(\'#sendDepCoorTel\').val(Tel);
                $(\'#sendDepCoorMail\').val(Email);
		setTimeout("$(\'#suggestions2\').hide();", 200);

	}</script>
        <script type="text/javascript">
	function lookup3(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$(\'#suggestions3\').hide();
		} else {
			$.post("modules/lagreeform/rpc3.php", {queryString: ""+inputString+""}, function(data){

				if(data.length >0) {
					$(\'#suggestions3\').show();
					$(\'#autoSuggestionsList3\').html(data);
				}
			});
		}
	} // lookup
	function fill3(Name,Country) {
		$(\'#recInstitut\').val(Name);
                $(\'#coountry\').val(Country);
		setTimeout("$(\'#suggestions3\').hide();", 200);

	}</script>');

        $infoStudent = LagreeformDB::getStudentById($this->id);
        $infoInstCoor = LagreeformDB::getInstitCoor();
        $infoSendInst = LagreeformDB::getSendInst();
        //$studyStudent = LagreeformDB::getStudyById($this->id);
        //$homeInst = LagreeformDB::getHomeInstitution($this->id);
        //$hostInst = LagreeformDB::getHostInstitution($this->id);

        $this->pageTpl->assign('instName', '"' . INSTITUTE . '"');

        $this->pageTpl->assign('acaYear', ACADEMICYEAR);

        $this->pageTpl->assign('sendInstName', $infoSendInst['instName']);
        $this->pageTpl->assign('sendInstAddress', $infoSendInst['instStreetNr'] . ' - ' . $infoSendInst['instPostalCode'] . ' - ' . $infoSendInst['instCity'] . ' - ' . $infoSendInst['Name']);
        $this->pageTpl->assign('sendInstCoorName', $infoInstCoor['familyName'] . ' ' . $infoInstCoor['firstName']);
        $this->pageTpl->assign('sendInstCoorTel', $infoInstCoor['tel']);
        $this->pageTpl->assign('sendInstCoorMail', $infoInstCoor['email']);


        $this->pageTpl->assign('fName', $infoStudent['firstName']);
        $this->pageTpl->assign('faName', $infoStudent['familyName']);
        $this->pageTpl->assign('dateBirth', $infoStudent['birthDate']);
        $this->pageTpl->assign('sex', $infoStudent['sex'] > 0 ? 'Male' : 'Female');
        $this->pageTpl->assign('nation', $infoStudent['Name']);
        $this->pageTpl->assign('birthPlace', $infoStudent['birthPlace']);
        $this->pageTpl->assign('cAddress', $infoStudent['streetNr'] . ' - ' . $infoStudent['postalCode'] . ' ' . $infoStudent['city']);
        $this->pageTpl->assign('cTel', $infoStudent['tel']);
        $this->pageTpl->assign('mail', $infoStudent['email']);
        


        $this->pageTpl->assign('workCount', $this->works);
        $this->pageTpl->assign('languageCount', $this->languages);

        $this->pageTpl->assign('abroadYes', 'checked');


        if (empty($this->errors)) {
            $this->fields = array('language0' => '', 'type0' => '', 'firm0' => '', 'country0' => '', 'date0' => '', 'studyThis0' => '0', 'knowledgeThis0' => '0', 'extraPrep0' => '0');
        }

        $this->pageTpl->setIteration('iLanguages');

        for ($i = 0; $i <= $this->languages; $i++) {
            $studyThisYes = $this->fields["studyThis" . $i] == '1' ? "checked" : "";
            $studyThisNo = $this->fields["studyThis" . $i] == '1' ? "" : "checked";
            $knowledgeThisYes = $this->fields["knowledgeThis" . $i] == '1' ? "checked" : "";
            $knowledgeThisNo = $this->fields["knowledgeThis" . $i] == '1' ? "" : "checked";
            $extraPrepYes = $this->fields["extraPrep" . $i] == '1' ? "checked" : "";
            $extraPrepNo = $this->fields["extraPrep" . $i] == '1' ? "" : "checked";
            $error = (array_key_exists("language" . $i, $this->errors)) ? $this->errors["language" . $i] : "*";

            $this->pageTpl->assignIteration('language', '<tr>
                                                        <td><input type="text" class="validate[required,custom[onlyLetterSp]] text-input" id="language' . $i . '" name="language' . $i . '" value="' . $this->fields["language" . $i] . '" /></td>
                                                        <td><input type="radio" name="studyThis' . $i . '" value="1" ' . ' class="validate[required] radio" id="een' . $i . '"' . $studyThisYes . ' /><input type="radio" name="studyThis' . $i . '" value="0" id="nul' . $i . '" ' . $studyThisNo . ' ></td>
                                                        <td><input type="radio" name="knowledgeThis' . $i . '" value="1" class="validate[required] radio" id="een2' . $i . '" ' . $knowledgeThisYes . ' /><input type="radio" name="knowledgeThis' . $i . '" value="0" id="nul2' . $i . '" ' . $knowledgeThisNo . '></td>
                                                        <td><input type="radio" name="extraPrep' . $i . '" value="1" class="validate[required] radio" id="een3' . $i . '" ' . $extraPrepYes . ' /><input type="radio" name="extraPrep' . $i . '" value="0" id="nul3' . $i . '" ' . $extraPrepNo . '></td>
                                                        <td><span class="req">' . $error . '</span></td>
                                                        </tr>');
            $this->pageTpl->refillIteration('iLanguages');
        }

        $this->pageTpl->parseIteration('iLanguages');

        $this->pageTpl->setIteration('iWorks');

        for ($i = 0; $i <= $this->works; $i++) {

            if (array_key_exists('type' . $i, $this->errors)) {
                $error = $this->errors['type' . $i];
            } else if (array_key_exists('country' . $i, $this->errors)) {
                $error = $this->errors['country' . $i];
            } else if (array_key_exists('firm' . $i, $this->errors)) {
                $error = $this->errors['firm' . $i];
            } else if (array_key_exists('date' . $i, $this->errors)) {
                $error = $this->errors['date' . $i];
            } else {
                $error = '*';
            }

            $this->pageTpl->assignIteration('work', '<tr>
                                                    <td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="type' . $i . '" name="type' . $i . '" value="' . $this->fields["type" . $i] . '" /></td>
                                                    <td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="firm' . $i . '" name="firm' . $i . '" value="' . $this->fields["firm" . $i] . '" /></td>
                                                    <td><input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" id="date' . $i . '" name="date' . $i . '" value="' . $this->fields["date" . $i] . '" /></td>
                                                    <td><input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="country' . $i . '" name="country' . $i . '" value="' . $this->fields["country" . $i] . '" /></td>
                                                    <td><span class="req">' . $error . '</span></td>
                                                    </tr>');
            $this->pageTpl->refillIteration('iWorks');
        }

        $this->pageTpl->parseIteration('iWorks');

        foreach ($this->inputs as $value) {

            if (count($this->fields) < 9) {
                if (strpos($value, 'Date') != false || strpos($value, 'daate') != false) {
                    $this->pageTpl->assign($value, date('Y-m-d'));
                }
                $this->pageTpl->assign('msg' . ucfirst($value), '*');
                $this->pageTpl->assign($value, '');
            } else if (array_key_exists($value, $this->errors)) {

                $this->pageTpl->assign('msg' . ucfirst($value), $this->errors[$value]);
                $this->pageTpl->assign($value, $this->fields[$value]);
            } else {
                $this->pageTpl->assign('msg' . ucfirst($value), '*');
                $this->pageTpl->assign($value, $this->fields[$value]);

                if ($value === 'abroad') {
                    if ($this->fields[$value] == '1') {
                        $this->pageTpl->assign('abroadYes', 'checked');
                    } else {
                        $this->pageTpl->assign('abroadNo', 'checked');
                    }
                }
                if (($value == 'accepted') && ($this->fields[$value] === '0')) {

                    $this->pageTpl->assign('acceptedYes', '');
                    $this->pageTpl->assign('acceptedNo', 'checked');
                } else {
                    $this->pageTpl->assign('acceptedYes', 'checked');
                    $this->pageTpl->assign('acceptedNo', '');
                }
            }
        }

        $studies = LagreeformDB::getStudies();
        //Plonk::dump($studies);
        $this->pageTpl->setIteration('iStudy');
        foreach ($studies as $value) {
            if (count($this->fields) > 8) {
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

        $this->errors = null;
    }

    public function doApplic() {

        $rules = array();

        $rules[] = "required,study,This field is required.";

        $rules[] = "required,sendInstName,This field is required.";
        $rules[] = "required,sendInstAddress,This field is required.";

        $rules[] = "required,sendDepCoorName,This field is required.";
        $rules[] = "required,sendDepCoorTel,This field is required.";
        $rules[] = "required,sendDepCoorMail,This field is required.";

        $rules[] = "required,acaYear,This field is required.";
        $rules[] = "required,cAddress,This field is required.";
        $rules[] = "required,daateValid,later_date,This field is required.";
        $rules[] = "required,cTel,This field is required.";
        $rules[] = "required,recInstitut,This field is required.";
        $rules[] = "required,coountry,This field is required.";
        $rules[] = "required,daateFrom,later_date,This field is required.";
        $rules[] = "required,daateUntill,later_date,This field is required.";
        $rules[] = "required,duration,This field is required.";
        $rules[] = "required,ectsPoints,This field is required.";
        $rules[] = "required,motivation,This field is required.";
        $rules[] = "required,motherTongue,This field is required.";
        $rules[] = "required,instrLanguage,This field is required.";
        $rules[] = "required,diplome,This field is required.";
        $rules[] = "required,yEducation,This field is required.";
        $rules[] = "required,whichInst,This field is required.";
        $rules[] = "required,signDepSignDate,any_date,This field is required.";
        $rules[] = "required,signInstSignDate,This field is required.";
        $rules[] = "required,abroad,This field is required";
        $rules[] = "required,accepted,This field is required";

        $rules[] = "letters_only,study,Please only enter letters.";
<<<<<<< HEAD
        
        $rules[] = "is_alpha,sendInstName,This field is required.";
        $rules[] = "is_alpha,sendInstAddress,This field is required.";
        
=======

        $rules[] = "is_alpha,sendInstName,This field is required.";
        $rules[] = "is_alpha,sendInstAddress,This field is required.";

>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
        $rules[] = "is_alpha,sendDepCoorName,Please only enter letters.";
        $rules[] = "digits_only,sendDepCoorTel,Please only enter letters.";
        $rules[] = "valid_email,sendDepCoorMail,Must be of form john@example.com.";

        $rules[] = "custom_alpha,acaYear,xxxx\-xxxx,Invalid input";
        $rules[] = "is_alpha,cAddress,Invalid characters";
        $rules[] = "valid_date,daateValid,later_date,Date must be after present";
        $rules[] = "digits_only,cTel,Invalid telephone number";
        $rules[] = "is_alpha,recInstitut,Only letters and numbers";
        $rules[] = "letters_only,coountry,Please only enter letters";
        $rules[] = "valid_date,daateFrom,later_date,Date must be after present";
        $rules[] = "valid_date,daateUntill,later_date,Date must be after present";
        $rules[] = "digits_only,duration,Please only enter numbers";
        $rules[] = "digits_only,ectsPoints,Please only enter numbers";
        $rules[] = "is_alpha,motivation,Only letters and numbers";
        $rules[] = "letters_only,motherTongue,Please enter only letters";
        $rules[] = "letters_only,instrLanguage,Please enter only letters";
        $rules[] = "letters_only,diplome,Please only enter letters";
        $rules[] = "digits_only,yEducation, Please enter only digits";
        $rules[] = "letters_only,whichInst,Please only enter letters";
        $rules[] = "valid_date,signDepSignDate,any_date,Invalid date";
        $rules[] = "valid_date,signInstSignDate,any_date,Invalid date";

        $this->works = $_POST['workCount'];
        $this->languages = $_POST['languageCount'];


        for ($i = 0; $i <= $this->works; $i++) {

            $rules[] = "required,type" . $i . ",All fields are required";
            $rules[] = "required,firm" . $i . ",Organization required";
            $rules[] = "required,date" . $i . ",All fields are required";
            $rules[] = "required,country" . $i . ",All fields are required";

            $rules[] = "letters_only,type" . $i . ",Experience: Please only enter letters.";
            $rules[] = "is_alpha,firm" . $i . ",Organisation: Only letters and numbers.";
            $rules[] = "is_alpha,date" . $i . ",Dates: Only letters and numbers.";
            $rules[] = "letters_only,country" . $i . ",Country: Please only enter letters.";
        }


        for ($i = 0; $i <= $this->languages; $i++) {

            $rules[] = "letters_only,language" . $i . ",Please only enter letters";

            $rules[] = "required,language" . $i . ",All fields are required";
            $rules[] = "required,studyThis" . $i . ",All fields are required";
            $rules[] = "required,knowledgeThis" . $i . ",All fields are required";
            $rules[] = "required,extraPrep" . $i . ",All fields are required";
        }


        $this->errors = validateFields($_POST, $rules);

        // if there were errors, re-populate the form fields
        if (!empty($this->errors)) {
            $this->fields = $_POST;
            //Plonk::dump($this->errors);
        } else {
<<<<<<< HEAD
            $homeCoor = LagreeformDB::getIdUsers(htmlentities(PlonkFilter::getPostValue('sendDepCoorMail')));
            $hostCoor = LagreeformDB::getIdUsers(htmlentities(PlonkFilter::getPostValue('sendCoorMail')));
            $homeInst = LagreeformDB::getIdInst(htmlentities(PlonkFilter::getPostValue('sendInstName')));
            $hostInst = LagreeformDB::getIdInst(htmlentities(PlonkFilter::getPostValue('recInstitute')));
            $education = LagreeformDB::getEducation(htmlentities(PlonkFilter::getPostValue('study')));
            $education = LagreeformDB::getEducationPerInstId($homeInst['instId'],$education['educationId']);
            if (empty($homecoor) || empty($hosCoor) || empty($hostInst) || empty($homeInst) || empty($education)) {
                
            } else {
                
                $values = array(
                    'homeCoordinatorId' => $homeCoor,
                    'hostCoordinatorId' => $hostCoor,
                    'homeInstitutionId' => $homeInst,
                    'hostInstitutionId' => $hostInst,
                    'startDate' => htmlentities(PlonkFilter::getPostValue('daateFrom')),
                    'endDate' => htmlentities(PlonkFilter::getPostValue('daateUntill')),
                    'educationPerInstId' => $education['educationId'],
=======

            $homeCoor = LagreeformDB::getIdUsers(htmlentities(PlonkFilter::getPostValue('sendDepCoorMail')));
            $homeInst = LagreeformDB::getIdInst(htmlentities(PlonkFilter::getPostValue('sendInstName')));
            $hostInst = LagreeformDB::getIdInst(htmlentities(PlonkFilter::getPostValue('recInstitut')));
            $educations = LagreeformDB::getEducation(htmlentities(PlonkFilter::getPostValue('study')));
            $education = LagreeformDB::getEducationPerInstId($homeInst['instId'], $educations['educationId']);
            if (empty($homeCoor) || empty($hostInst) || empty($homeInst) || empty($education)) {
                Plonk::dump($homeInst);
            } else {

                $values = array(
                    'homeCoordinatorId' => $homeCoor['userId'],
                    'homeInstitutionId' => $homeInst['instId'],
                    'hostInstitutionId' => $hostInst['instId'],
                    'startDate' => htmlentities(PlonkFilter::getPostValue('daateFrom')),
                    'endDate' => htmlentities(PlonkFilter::getPostValue('daateUntill')),
                    'educationPerInstId' => $education['educationPerInstId'],
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
                    'statusOfErasmus' => 'Student Application Form',
                    'traineeOrStudy' => '1',
                    'ectsCredits' => htmlentities(PlonkFilter::getPostValue('ectsPoints')),
                    'motherTongue' => htmlentities(PlonkFilter::getPostValue('motherTongue')),
                    'beenAbroad' => htmlentities(PlonkFilter::getPostValue('abroad')),
                );

<<<<<<< HEAD
=======
                LagreeformDB::updateErasmusStudent('erasmusstudent', $values, 'studentId = ' . PlonkSession::get('id'));


>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
                $testArray = $_POST;
                $newArray = array_slice($testArray, 0, count($_POST) - 2);

                $jsonArray = json_encode($newArray);
<<<<<<< HEAD
                $erasmusLevel = PrecandidateDB::getErasmusLevelId('Student Application Form');
=======
                $erasmusLevel = LagreeformDB::getErasmusLevelId('Student Application Form');
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
                $values = array(
                    'type' => 'Student Application Form',
                    'date' => date("Y-m-d"),
                    'content' => $jsonArray,
                    'studentId' => PlonkSession::get('id'),
                    'erasmusLevelId' => $erasmusLevel['levelId']
                );
<<<<<<< HEAD
                     $valueStatus = array(
                    'statusOfErasmus' => 'Student Application Form',
                    
                );
                LagreeformDB::updateErasmusStudent('erasmusstudent', $valueStatus, 'studentId = '.PlonkSession::get('id'));
=======
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd

                $valueEvent = array(
                    'event' => 'sdfsdf',
                    'timestamp' => date("Y-m-d"),
                    'motivation' => '',
                    'erasmusStudentId' => PlonkSession::get('id'),
                    'action' => 'pending',
                    'erasmusLevelId' => $erasmusLevel['levelId']
                );

<<<<<<< HEAD
                PrecandidateDB::insertStudentEvent('studentsEvents', $valueEvent);
                PrecandidateDB::insertJson('forms', $values);
=======
                LagreeformDB::insertStudentEvent('studentsEvents', $valueEvent);
                LagreeformDB::insertJson('forms', $values);

                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
            }
        }

        //Plonk::dump($this->languages.$this->works);
        //PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=lagreeform&' . PlonkWebsite::$viewKey . '=applicform&l='.$this->languages.'&w='.$this->works);
    }

    public function doAgree() {

        $rules = array();
        $rules[] = "valid_date,signDate,Invalid date. Must be in YYYY-MM-DD.";
        $rules[] = "valid_date,signDepSignDate,Invalid date. Must be in YYYY-MM-DD.";
        $rules[] = "valid_date,signInstSignDate,Invalid date. Must be in YYYY-MM-DD.";
        $rules[] = "valid_date,signDepSignDate2,Invalid date. Must be in YYYY-MM-DD.";
        $rules[] = "valid_date,signInstSignDate2,Invalid date. Must be in YYYY-MM-DD.";

        $this->courses = $_POST['courseCount'];

        for ($i = 0; $i <= $this->courses; $i++) {
            //Plonk::dump($_POST);

            $rules[] = "required,ects" . $i . ",All fields are required";
            $rules[] = "required,title" . $i . ",Organization required";
            $rules[] = "required,code" . $i . ",All fields are required";

            $rules[] = "is_alpha,title" . $i . ",Title: Only letters and numbers.";
            $rules[] = "is_alpha,code" . $i . ",Code: Only letters and numbers.";
            $rules[] = "digits_only,ects" . $i . ",ECTS: Please only enter digits.";
        }

        $this->errors = validateFields($_POST, $rules);

        // if there were errors, re-populate the form fields
        if (!empty($this->errors)) {
            $this->fields = $_POST;
        } else {
<<<<<<< HEAD
            
=======
            for ($i = 0; $i < $this->courses; $i++) {
                $courseId = LagreeformDB::getCourseIdByCode(PlonkFilter::getPostValue('code' . $i));
                $grade = array(
                    'courseId' => $courseId['courseId'],
                    'studentId' => PlonkSession::get('id'),
                );
                LagreeformDB::insertStudentEvent('grades', $grade);
            }

            $values = array(
                'statusOfErasmus' => 'Learning Agreement',
            );

            LagreeformDB::updateErasmusStudent('erasmusstudent', $values, 'studentId = ' . PlonkSession::get('id'));


            $testArray = $_POST;
            $newArray = array_slice($testArray, 0, count($_POST) - 2);

            $jsonArray = json_encode($newArray);
            $erasmusLevel = LagreeformDB::getErasmusLevelId('Learning Agreement');
            $values = array(
                'type' => 'Learning Agreement',
                'date' => date("Y-m-d"),
                'content' => $jsonArray,
                'studentId' => PlonkSession::get('id'),
                'erasmusLevelId' => $erasmusLevel['levelId']
            );

            $valueEvent = array(
                'event' => 'sdfsdf',
                'timestamp' => date("Y-m-d"),
                'motivation' => '',
                'erasmusStudentId' => PlonkSession::get('id'),
                'action' => 'pending',
                'erasmusLevelId' => $erasmusLevel['levelId']
            );

            LagreeformDB::insertStudentEvent('studentsEvents', $valueEvent);
            LagreeformDB::insertJson('forms', $values);

            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
        }
    }

}
