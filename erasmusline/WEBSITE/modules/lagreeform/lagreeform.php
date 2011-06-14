<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "./modules/infox/infox.php";

class LagreeformController extends PlonkController {

    protected $formid;
    protected $userid;
    private $works = 0;
    private $languages = 0;
    private $courses = 0;
    private $errors = array(); // set the errors array to empty, by default
    private $fields = array(); // stores the field values
    private $inputs = array('abroad', 'acaYear', 'study', 'sendDepCoorName', 'sendDepCoorTel', 'sendDepCoorMail', 'sendDepCoorName', 'sendDepCoorTel', 'sendDepCoorMail', 'cAddress', 'daateValid', 'cTel', 'pTel', 'pAddress', 'recInstitut', 'coountry', 'daateFrom', 'daateUntill', 'duration',
        'ectsPoints', 'motivation', 'motherTongue', 'instrLanguage', 'diplome', 'yEducation', 'whichInst');
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
        'applic', 'agree', 'motivateapplic', 'motivateagree', 'tohostapplic', 'tohostagree'
    );

    /**
     * check if user is logged in
     */
    public function checkLogged() {
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else if (PlonkSession::get('userLevel') == 'Student') {
                $erasmus = LagreeformDB::getErasmusInfo(PlonkSession::get('id'));
                $this->pageTpl->assignOption('oStudent');
            } else if (PlonkSession::get('userLevel') == 'International Relations Office Staff') {
                $formid = PlonkFilter::getGetValue('form');
                $studentId = LagreeformDB::getStudentByForm($formid);
                $erasmusInfo = LagreeformDB::getErasmusInfo($studentId);

                if ($erasmusInfo['homeInstitutionId'] != INST_EMAIL) {
                    $this->pageTpl->assignOption('oHost');
                } else {
                    $this->pageTpl->assignOption('oOffice');
                }

                $this->pageTpl->assignOption('oCoor');
            } else {
                if (PlonkFilter::getGetValue('form') != null) {
                    $this->pageTpl->assignOption('oCoor');
                } else {
                    PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&' . PlonkWebsite::$viewKey . '=staff');
                }
            }
        }
    }

    private function filledLagreement() {
        $this->mainTpl->assign('pageMeta', '');
        $this->pageTpl->assignOption('oFilled');
        PlonkFilter::getGetValue('form');
        $json = LagreeformDB::getJson(PlonkFilter::getGetValue('form'), 'Learning Agreement');
        $jsonArray = json_decode($json['content'], true);

        $courses = (int) $jsonArray['courseCount'];

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
        
        $formAction = LagreeformDB::getForm($this->formid);

        if ($formAction['action'] == 1) {
            $this->pageTpl->assignOption('oApproved');
            $this->pageTpl->assign('motivationHome', $formAction['motivationHome']);
            $this->pageTpl->assign('motivationHost', $formAction['motivationHost']);
            $this->pageTpl->assign('returndAgree', '<a href="./files/' . $this->userid . '/' . $this->formid . '.pdf" title="Application Form">Student Application Form.pdf</a>');
        } else if ($formAction['action'] == 0) {
            $this->pageTpl->assignOption('oDenied');
            $this->pageTpl->assign('motivationHome', $formAction['motivationHome']);
            $this->pageTpl->assign('motivationHost', $formAction['motivationHost']);
            $this->pageTpl->assign('returndAgree', '<a href="./files/' . $this->userid . '/' . $this->formid . '.pdf" title="Application Form">Student Application Form.pdf</a>');
        } else {
            $this->pageTpl->assignOption('oPending');
            $this->pageTpl->assign('returndApplic', '');
        }
    }

    public function showLagreement() {

        // Main Layout
        // Logged or not logged, that is the question...
        $inputs = array('signDate', 'signDepSignDate', 'signInstSignDate', 'signDepSignDate2', 'signInstSignDate2', 'sign', 'signDepSign', 'signInstSign', 'signDepSign2', 'signInstSign2');

        $this->checkLogged();

        if (PlonkFilter::getGetValue('error') != null) {
            if (PlonkFilter::getGetValue('error') == 1) {
                $this->pageTpl->assign('error', "You can't select more credits than you applied for.");
            }
            if (PlonkFilter::getGetValue('error') == 2) {
                $this->pageTpl->assign('error', "You selected multiple times the same course");
            }
        } else {
            $this->pageTpl->assign('error', '');
        }

        if (PlonkFilter::getGetValue('form') != null) {
            $this->formid = PlonkFilter::getGetValue('form');
            $this->userid = LagreeformDB::getstudentByForm($this->formid);
        } else {
            $this->userid = PlonkSession::get('id');
            $this->pageTpl->assignOption('oNotFilled');
        }

        $this->courses = PlonkFilter::getPostValue('courseCount');
        $this->pageTpl->assign('courseCount', $this->courses);

        //Plonk::dump($this->id);
        $status = LagreeformDB::getStudentStatus($this->userid);
        $erasmusLevel = LagreeformDB::getIdLevel($status['statusOfErasmus']);
        $erasmusLevel2 = LagreeformDB::getIdLevel('Student Application and Learning Agreement');
        //Plonk::dump($erasmusLevel['levelId']. ' - '.$erasmusLevel2['levelId']);
        //Plonk::dump($erasmusLevel['levelId'].' - '.$erasmusLevel2['levelId']);

        $this->mainTpl->assign('siteTitle', 'Learning Agreement');

        if (PlonkFilter::getGetValue('form') != null) {
            $this->formid = PlonkFilter::getGetValue('form');
            $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=home&amp;view=userhome" title="Home">Home</a><a href="index.php?module=lagreeform&amp;view=lagreement&form=' . $this->formid . '" title="Learning Agreement">Learning Agreement</a>');
            $this->filledLagreement();
            if (PlonkFilter::getGetValue('student') == null) {
                $this->mainTpl->assign('pageJava', '');
            }
            $this->fillFixed();
            //Plonk::dump('before');
            return;
        } else {
            $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=home&amp;view=userhome" title="Home">Home</a><a href="index.php?module=lagreeform&amp;view=lagreement" title="Learning Agreement">Learning Agreement</a>');
        }

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/><link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/>');


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

        $this->fillFixed();


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

        $receivingInst = LagreeformDB::getHostInstitution($this->userid);

        $education = LagreeformDB::getStudyById($this->userid);

        $this->pageTpl->assign('study', $education['educationName']);
        $this->pageTpl->assign('nameStudent', $education['firstName'] . ' ' . $education['familyName']);
        $this->pageTpl->assign('credits', $education['ectsCredits']);

        $this->pageTpl->assign('acaYear', ACADEMICYEAR);
        $this->pageTpl->assign('sendingInstitution', INSTITUTE);
        $this->pageTpl->assign('countrySendingInstitution', $sendCountry['Name']);

        $this->pageTpl->assign('receivingInstitution', $receivingInst['instName']);
        $this->pageTpl->assign('countryReceivingInstitution', $receivingInst['Name']);
    }

    private function filledApplicform() {

        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/><script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
                    <script type="text/javascript" src="./core/js/datepicker/js/jquery-ui-1.8.9.custom.min.js"></script>
                    <script>
        $(function() {
		$( "#signInstSignDate" ).datepicker();
	});
        $(function() {
		$( "#signDepSignDate" ).datepicker();
	});


	</script>');
        $this->pageTpl->assignOption('oFilled');

        $json = LagreeformDB::getJson($this->formid, 'Student Application Form');
        $jsonArray = json_decode($json['content'], true);

        $formAction = LagreeformDB::getForm($this->formid);

        if ($formAction['action'] == 1) {
            $this->pageTpl->assignOption('oApproved');
            $this->pageTpl->assign('motivationHost', $formAction['motivationHost']);
            $this->pageTpl->assign('returndApplic', '<a href="./files/' . $this->userid . '/' . $this->formid . '.pdf" title="Application Form">Student Application Form.pdf</a>');
        } else if ($formAction['action'] == 0) {
            $this->pageTpl->assignOption('oDenied');
            $this->pageTpl->assign('motivationHost', $formAction['motivationHost']);
            $this->pageTpl->assign('returndApplic', '<a href="./files/' . $this->userid . '/' . $this->formid . '.pdf" title="Application Form">Student Application Form.pdf</a>');
        } else {
            $this->pageTpl->assignOption('oPending');
            $this->pageTpl->assign('returndApplic', '');
        }

        $language = (int) $jsonArray['languageCount'];

        $work = (int) $jsonArray['workCount'];

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

        for ($i = 0; $i <= $work; $i++) {

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

        //Plonk::dump($jsonArray);
        foreach ($jsonArray as $key => $value) {
            $this->pageTpl->assign($key, $value);
            $this->pageTpl->assign('msg' . ucfirst($key), '');
        }

        if (file_exists('./files/' . $this->userid . '/Transcript.pdf')) {
            $this->pageTpl->assign('trrec', '<a href="./files/' . $this->userid . '/Transcript.pdf" title="Transcript of Record">Transcript of Record</a>');
        } else {
            $this->pageTpl->assign('trrec', 'No Transcript Of Records Attached');
        }
    }

    public function showApplicform() {
        $this->checkLogged();


        if (PlonkFilter::getGetValue('form') != null) {
            $this->formid = PlonkFilter::getGetValue('form');
            $this->userid = LagreeformDB::getstudentByForm($this->formid);
        } else {
            $this->userid = PlonkSession::get('id');
        }

        $status = LagreeformDB::getStudentStatus($this->userid);
        $erasmusLevel = LagreeformDB::getIdLevel($status['statusOfErasmus']);
        $erasmusLevel2 = LagreeformDB::getIdLevel('Student Application and Learning Agreement');
        $this->mainTpl->assign('siteTitle', 'Student Application Form');
        if ($erasmusLevel['levelId'] >= $erasmusLevel2['levelId'] && $this->formid != null) {
            $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=home&view=userhome" title="Home">Home</a><a href="index.php?module=lagreeform&view=applicform&form=' . $this->formid . '" title="Student Application Form">Student Application Form</a>');

            $this->filledApplicform();
            if (PlonkFilter::getGetValue('student') == null) {
                $this->mainTpl->assign('pageJava', '');
            }
            return;
        } else {
            $this->mainTpl->assign('breadcrumb', '<a href="index.php?module=home&view=userhome" title="Home">Home</a><a href="index.php?module=lagreeform&view=applicform" title="Student Application Form">Student Application Form</a>');
            $this->pageTpl->assignOption('oNotFilled');
        }

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/><link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/>');
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

        $infoStudent = LagreeformDB::getStudentById($this->userid);
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


        $this->works = PlonkFilter::getPostValue('workCount');
        $this->languages = PlonkFilter::getPostValue('languageCount');
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

        $this->userid = PlonkSession::get('id');
        //Plonk::dump('test');
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
        $rules[] = "required,abroad,This field is required";

        $rules[] = "letters_only,study,Please only enter letters.";

        $rules[] = "is_alpha,sendInstName,This field is required.";
        $rules[] = "is_alpha,sendInstAddress,This field is required.";

        $rules[] = "is_alpha,sendDepCoorName,Please only enter letters.";
        $rules[] = "digits_only,sendDepCoorTel,Please only enter letters.";
        $rules[] = "valid_email,sendDepCoorMail,Must be of form john@example.com.";

        $rules[] = "custom_alpha,acaYear,xxxx\-xxxx,Invalid input";
        $rules[] = "is_alpha,pAddress,Invalid characters.";
        $rules[] = "digits_only,pTel,Please enter only numbers";
        $rules[] = "is_alpha,cAddress,Invalid characters";
        $rules[] = "valid_date,daateValid,later_date,Date must be after present";
        $rules[] = "digits_only,cTel,Invalid telephone number";
        $rules[] = "is_alpha,recInstitut,Only letters and numbers";
        $rules[] = "letters_only,coountry,Please only enter letters";
        $rules[] = "valid_date,daateFrom,later_date,Date must be after present";
        $rules[] = "valid_date,daateUntill,later_date,Date must be after present";
        $rules[] = "digits_only,duration,Please only enter numbers";
        $rules[] = "digits_only,ectsPoints,Please only enter numbers";
        $rules[] = "textarea,motivation,Only letters and numbers";
        $rules[] = "letters_only,motherTongue,Please enter only letters";
        $rules[] = "letters_only,instrLanguage,Please enter only letters";
        $rules[] = "letters_only,diplome,Please only enter letters";
        $rules[] = "digits_only,yEducation, Please enter only digits";
        $rules[] = "letters_only,whichInst,Please only enter letters";

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
            Plonk::dump($this->errors);
        } else {
            $homeCoor = LagreeformDB::getIdUsers(htmlentities(PlonkFilter::getPostValue('sendDepCoorMail')));
            $homeInst = LagreeformDB::getIdInst(htmlentities(PlonkFilter::getPostValue('sendInstName')));
            $hostInst = LagreeformDB::getIdInst(htmlentities(PlonkFilter::getPostValue('recInstitut')));
            $educations = LagreeformDB::getEducation(htmlentities(PlonkFilter::getPostValue('study')));
            $education = LagreeformDB::getEducationPerInstId($homeInst['instEmail'], $educations['educationId']);

            if (empty($homeCoor) || empty($hostInst) || empty($homeInst) || empty($education)) {
                $this->fields = $_POST;
                Plonk::dump('stage2');
            } else {

                $prevStat = LagreeformDB::getStudentStatus($this->userid);
                $prev = $prevStat['action'];
                $status;

                if ($prevStat['statusOfErasmus'] == 'Student Application and Learning Agreement') {
                    if ($prev == 1) {
                        $status = 21;
                    }
                    if ($prev == 2) {
                        $status = 22;
                    }
                    if ($prev == 0) {
                        $status = 20;
                    }
                } else {
                    $status = 30;
                }


                $values = array(
                    'homeCoordinatorId' => $homeCoor['email'],
                    'homeInstitutionId' => $homeInst['instEmail'],
                    'hostInstitutionId' => $hostInst['instEmail'],
                    'startDate' => htmlentities(PlonkFilter::getPostValue('daateFrom')),
                    'endDate' => htmlentities(PlonkFilter::getPostValue('daateUntill')),
                    'educationPerInstId' => $education['educationPerInstId'],
                    'statusOfErasmus' => 'Student Application and Learning Agreement',
                    'traineeOrStudy' => 1,
                    'ectsCredits' => htmlentities(PlonkFilter::getPostValue('ectsPoints')),
                    'motherTongue' => htmlentities(PlonkFilter::getPostValue('motherTongue')),
                    'beenAbroad' => htmlentities(PlonkFilter::getPostValue('abroad')),
                    'action' => $status
                );

                LagreeformDB::updateErasmusStudent('erasmusstudent', $values, 'users_email = "' . PlonkSession::get('id') . '"');

                $this->upload('Transcript.pdf');

                $testArray = $_POST;
                $newArray = array_slice($testArray, 0, count($_POST) - 2);

                $jsonArray = json_encode($newArray);
                $erasmusLevel = LagreeformDB::getErasmusLevelId('Student Application and Learning Agreement');
                $valuess = array(
                    'formId' => Functions::createRandomString(),
                    'type' => 'Student Application Form',
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
                    'eventDescrip' => 'Filled in Student Application Form',
                    'readIt' => 0
                );

                LagreeformDB::insertStudentEvent('studentsEvents', $valueEvent);

                LagreeformDB::insertJson('forms', $valuess);

                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=lagreeform&' . PlonkWebsite::$viewKey . '=lagreement');
            }
        }

        //Plonk::dump($this->languages.$this->works);
        //PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=lagreeform&' . PlonkWebsite::$viewKey . '=applicform&l='.$this->languages.'&w='.$this->works);
    }

    public function doAgree() {
        $rules = array();
        $rules[] = "valid_date,signDate,Invalid date. Must be in YYYY-MM-DD.";

        $this->courses = (int) $_POST['courseCount'];

        for ($i = 0; $i < $this->courses; $i++) {
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
            $ects = 0;
            $courses = array();
            for ($j = 0; $j <= $this->courses; $j++) {
                $courses[] = PlonkFilter::getPostValue('code' . $j);
            }

            $ectsCredits = LagreeformDB::getStudyById(PlonkSession::get('id'));
            $unique = array_unique($courses);

            if (count($unique) < count($courses)) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=lagreeform&' . PlonkWebsite::$viewKey . '=lagreement&error=2');
            }

            for ($i = 0; $i <= $this->courses; $i++) {
                $courseId = LagreeformDB::getCourseIdByCode(PlonkFilter::getPostValue('code' . $i));
                $ects = $ects + (int) PlonkFilter::getPostValue('ects' . $i);
                $grade = array(
                    'courseId' => $courseId['courseId'],
                    'studentId' => PlonkSession::get('id'),
                );

                if ($ects > $ectsCredits['ectsCredits']) {

                    PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=lagreeform&' . PlonkWebsite::$viewKey . '=lagreement&error=1');
                }

                LagreeformDB::insertStudentEvent('grades', $grade);
            }

            $values = array(
                'statusOfErasmus' => 'Student Application and Learning Agreement',
                'action' => 22
            );

            LagreeformDB::updateErasmusStudent('erasmusstudent', $values, 'users_email = "' . PlonkSession::get('id') . '"');

            $testArray = $_POST;
            $newArray = array_slice($testArray, 0, count($_POST) - 2);

            $jsonArray = json_encode($newArray);
            $erasmusLevel = LagreeformDB::getErasmusLevelId('Student Application and Learning Agreement');
            $values = array(
                'formId' => Functions::createRandomString(),
                'type' => 'Learning Agreement',
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
                'action' => 22,
                'erasmusLevelId' => $erasmusLevel['levelId'],
                'eventDescrip' => 'Filled in Learning Agreement',
                'readIt' => 0
            );

            LagreeformDB::insertStudentEvent('studentsEvents', $valueEvent);
            LagreeformDB::insertJson('forms', $values);

            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
        }
    }

    public function doMotivateapplic() {
        $erasmusLevelId = LagreeformDB::getIdlevel('Student Application and Learning Agreement');
        $this->formid = PlonkFilter::getGetValue('form');
        $this->userid = LagreeformDB::getStudentByForm($this->formid);
        $prevStat = LagreeformDB::getStudentStatus($this->userid);
        $prev = $prevStat['action'];
        $formArray;
        $descrip = "";
        $status;
        if (PlonkFilter::getPostValue('accepted') == 1) {
            $descrip = "Student Application Form is approved";
            if ($prev == 21) {
                $status = 11;
            }
            if ($prev == 22) {
                $status = 12;
            }
            if ($prev == 20) {
                $status = 10;
            }
            $formArray = array(
                'action' => 1,
                'formId' => $this->formid,
                'motivationHost' => PlonkFilter::getPostValue('coordinator')
            );
        } else {
            $descrip = "Student Application Form is denied.";

            if ($prev == 21) {
                $status = 1;
            }
            if ($prev == 22) {
                $status = 2;
            }
            if ($prev == 20) {
                $status = 0;
            }
            $formArray = array(
                'action' => 0,
                'formId' => $this->formid,
                'motivationHost' => PlonkFilter::getPostValue('coordinator')
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


        LagreeformDB::updateErasmusStudent('erasmusstudent', $values, 'users_email = "' . $this->userid . '"');
        LagreeformDB::updateErasmusStudent('forms', $formArray, 'formId = "' . $this->formid . '"');

        $user = LagreeformDB::getInfoUser($this->userid);
        $erasmus = LagreeformDB::getErasmusInfo($this->userid);


        try {

            $er = array(
                'table' => 'erasmusstudent',
                'data' => array('action' => $status, 'users_email' => $erasmus['users_email']),
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
            $idInst = $erasmus['homeInstitutionId'];
            $b->dataTransfer($methods, $tables, $data, $idInst);

            if (!empty($_FILES['pic']['tmp_name'][0])) {

                $this->upload($this->formid . '.pdf');
                $b->fileTransfer('forms:saveFile', 'files/' . $this->userid . '/' . $this->formid . '.pdf', $idInst, $this->userid);
            }

            //Plonk::dump($success);
            if ($success !== '0') {
                PlonkWebsite::redirect('index.php?module=office&view=office&success=true');
            } else {
                PlonkWebsite::redirect('index.php?module=office&view=office&success=false');
            }
        } catch (Exception $e) {
            Plonk::dump('failed');
        }
    }

    public function doMotivateagree() {
        $erasmusLevelId = LagreeformDB::getIdlevel('Student Application and Learning Agreement');
        $this->formid = PlonkFilter::getGetValue('form');
        $this->userid = LagreeformDB::getStudentByForm($this->formid);
        $prevStat = LagreeformDB::getStudentStatus($this->userid);
        $formArray;
        $prev = $prevStat['action'];
        $descrip = "";
        $status;
        if (PlonkFilter::getPostValue('acceptedHost') == 1) {
            $descrip = "Learning Agreement is approved by host";
            if ($prev == 2) {
                $status = 1;
            }
            if ($prev == 12) {
                $status = 11;
            }
            if ($prev == 22) {
                $status = 21;
            }
            $formArray = array(
                'action' => 1,
                'motivationHost' => PlonkFilter::getPostValue('coordinator'),
                'formId' => $this->formid
            );
        } else {
            $descrip = "Learning Angreement is denied by host.";

            if ($prev == 2) {
                $status = 0;
            }
            if ($prev == 12) {
                $status = 10;
            }
            if ($prev == 22) {
                $status = 20;
            }
            $formArray = array(
                'action' => 0,
                'motivationHost' => PlonkFilter::getPostValue('coordinator'),
                'formId' => $this->formid
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

        LagreeformDB::updateErasmusStudent('erasmusstudent', $values, 'users_email = "' . $this->userid . '"');
        LagreeformDB::updateErasmusStudent('forms', $formArray, 'formId = "' . $this->formid . '"');

        $erasmus = LagreeformDB::getErasmusInfo($this->userid);

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
            $idInst = $erasmus['homeInstitutionId'];
            $success = $b->dataTransfer($methods, $tables, $data, $idInst);

            if (!empty($_FILES['pic']['tmp_name'][0])) {

                $this->upload($this->formid . '.pdf');
                $b->fileTransfer('forms:saveFile', 'files/' . $this->userid . '/' . $this->formid . '.pdf', $idInst, $this->userid);
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

    private function fillInAutograph() {
        $digital = LagreeformDB::getSignature();

//Plonk::dump($digital);
        if ($digital['digital'] == 1) {

            $this->pageTpl->assignOption('oDigital');
            if (PlonkSession::get('userLevel') === "International Relations Office Staff") {
                $srcInst = 'files/' . $this->userid . '/sign.jpg';
                $this->pageTpl->assign('sourceInst', $srcInst);
            } else if (PlonkSession::get('userLevel') == "Erasmus Coordinator") {
                $srcDep = 'files/' . $this->userid . '/sign.jpg';
                $isHome = LagreeformDB::checkOrigin($this->userid);
                if ($isHome == 1) {
                    $this->pageTpl->assign('sourceDepRec', $srcDep);
                } else if (PlonkSession::get('userLevel') == 'Student') {
                    $this->pageTpl->assign('sourceDepSend', $srcDep);
                }
            } else {
                $this->pageTpl->assign('sourceStud', 'files/' . $this->userid . '/sign.jpg');
            }
        } else {
            $this->pageTpl->assignOption('oPaper');
        }
    }

    public function doTohostapplic() {
        $this->userid = LagreeformDB::getStudentByForm(PlonkFilter::getGetValue('form'));
        $user = LagreeformDB::getInfoUser($this->userid);
        $erasmus = LagreeformDB::getErasmusInfo($this->userid);
        $form = LagreeformDB::getForm(PlonkFilter::getGetValue('form'));
        foreach ($erasmus as $key => $value) {
            if ($value === null) {
                unset($erasmus[$key]);
            }
        }
        $erasmusLevel = LagreeformDB::getErasmusLevelId('Student Application and Learning Agreement');


        $valueEvent = array(
            'reader' => 'Student',
            'timestamp' => date("Y-m-d"),
            'motivation' => '',
            'studentId' => $this->userid,
            'action' => 99,
            'erasmusLevelId' => $erasmusLevel['levelId'],
            'eventDescrip' => 'Student Application sent to host institution.',
            'readIt' => 0
        );

        LagreeformDB::insertStudentEvent('studentsEvents', $valueEvent);

        try {

            $us = array(
                'table' => 'users',
                'data' => $user,
                'emailField' => 'email');
            $er = array(
                'table' => 'erasmusstudent',
                'data' => $erasmus,
                'emailField' => 'users_email'
            );
            $form = array(
                'table' => 'forms',
                'data' => $form,
                'emailField' => 'formId'
            );

            $b = new InfoxController;
            //$b->TransferBelgium($jsonStringUser, $hostInst['instId']);
            $methods = array('forms:toDb', 'forms:toDb', 'forms:toDb');
            $tables = array('users', 'erasmusstudent', 'forms');
            $data = array($us, $er, $form);
            $idInst = $erasmus['hostInstitutionId'];
            $success = $b->dataTransfer($methods, $tables, $data, $idInst);
            if ($success !== '0') {
                PlonkWebsite::redirect('index.php?module=office&view=office&success=true');
            } else {
                PlonkWebsite::redirect('index.php?module=office&view=office&success=false');
            }
        } catch (Exception $e) {
            Plonk::dump('failed');
        }
    }

    public function doTohostagree() {
        $this->userid = LagreeformDB::getStudentByForm(PlonkFilter::getGetValue('form'));
        $user = LagreeformDB::getInfoUser($this->userid);
        $erasmus = LagreeformDB::getErasmusInfo($this->userid);
        $this->formid = PlonkFilter::getGetValue('form');
        $success;

        foreach ($erasmus as $key => $value) {
            if ($value === null) {
                unset($erasmus[$key]);
            }
        }

        $prevStat = LagreeformDB::getStudentStatus($this->userid);
        $formArray;
        $prev = $prevStat['action'];
        $descrip = "";
        $status;

        if (PlonkFilter::getPostValue('acceptedHome') == 1) {

            $descrip = "Learnign Agreement approved by home institute and sent to host.";

            $formMot = array(
                'motivationHome' => PlonkFilter::getPostValue('coordinator')
            );

            LagreeformDB::updateErasmusStudent('forms', $formMot, 'formId = "' . $this->formid . '"');
            $form = LagreeformDB::getForm($this->formid);

            try {

                $us = array(
                    'table' => 'users',
                    'data' => $user,
                    'emailField' => 'email');
                $er = array(
                    'table' => 'erasmusstudent',
                    'data' => $erasmus,
                    'emailField' => 'users_email'
                );
                $form = array(
                    'table' => 'forms',
                    'data' => $form,
                    'emailField' => 'formId'
                );

                $b = new InfoxController;
                //$b->TransferBelgium($jsonStringUser, $hostInst['instId']);
                $methods = array('forms:toDb', 'forms:toDb', 'forms:toDb');
                $tables = array('users', 'erasmusstudent', 'forms');
                $data = array($us, $er, $form);
                $idInst = $erasmus['hostInstitutionId'];
                $success = $b->dataTransfer($methods, $tables, $data, $idInst);

                //Plonk::dump($_FILES);
                if (!empty($_FILES['pic']['tmp_name'][0])) {

                    $this->upload($this->formid . '.pdf');
                    $b->fileTransfer('forms:saveFile', 'files/' . $this->userid . '/' . $this->formid . '.pdf', $idInst, $this->userid);
                }
            } catch (Exception $e) {
                Plonk::dump('failed');
            }
            $status = $prev;
        } else {
            $descrip = "Learning Angreement is denied by home.";

            if ($prev == 2) {
                $status = 0;
            }
            if ($prev == 12) {
                $status = 10;
            }
            if ($prev == 22) {
                $status = 20;
            }
            $formArray = array(
                'action' => 0,
                'motivationHome' => PlonkFilter::getPostValue('coordinator')
            );

            $values = array('action' => $status);

            LagreeformDB::deleteCourses('grades', 'studentId = "' . $this->userid . '"');

            LagreeformDB::updateErasmusStudent('erasmusstudent', $values, 'users_email = "' . $this->userid . '"');
            LagreeformDB::updateErasmusStudent('forms', $formArray, 'formId = "' . $this->formid . '"');

            $success = "denied";
        }

        $erasmusLevel = LagreeformDB::getErasmusLevelId('Student Application and Learning Agreement');

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

        LagreeformDB::insertStudentEvent('studentsEvents', $valueEvent);

        //Plonk::dump($success);
        if ($success == "denied") {
            PlonkWebsite::redirect('index.php?module=office&view=office');
        }
        if ($success !== '0') {
            PlonkWebsite::redirect('index.php?module=office&view=office&success=true');
        } else {
            PlonkWebsite::redirect('index.php?module=office&view=office&success=false');
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
