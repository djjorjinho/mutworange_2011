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
    private $inputs = array('accepted', 'abroad', 'acaYear', 'study', 'sendInstName', 'sendInstAddress', 'sendDepCoorName', 'sendDepCoorTel', 'sendDepCoorFax', 'sendDepCoorMail', 'sendDepCoorName', 'sendDepCoorTel', 'sendDepCoorFax', 'sendDepCoorMail', 'sendInstName', 'sendInstAddress', 'sendInstCoorName', 'sendInstCoorTel', 'sendInstCoorFax', 'sendInstCoorMail', 'cAddress', 'daateValid', 'cTel', 'recInstitut', 'coountry', 'daateFrom', 'daateUntill', 'duration',
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

        if (!PlonkSession::exists('loggedIn')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            $this->id = PlonkSession::get('id');
            if ($this->id === '1') {
                $this->mainTpl->assignOption('oAdmin');
            } else {
                $this->mainTpl->assignOption('oLogged');

                $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
                $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile');
            }
        }
    }

    public function showLagreement() {

        // Main Layout
        // Logged or not logged, that is the question...
        $inputs = array('signDate', 'signDepSignDate', 'signInstSignDate', 'signDepSignDate2', 'signInstSignDate2', 'sign', 'signDepSign', 'signInstSign', 'signDepSign2', 'signInstSign2');

        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/><link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/><link rel="stylesheet" href="./core/css/form.css" type="text/css"/>');
        $this->mainTpl->assign('siteTitle', 'Learning Agreement');

        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile');

        $g = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/lagreement.java.tpl');

        $this->mainTpl->assign('pageJava', $g->getContent(true));

        $this->pageTpl->assign('courseCount', $this->courses);

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
                        <td><input class="validate[required, custom[onlyLetterNumber]]" type="text" id="title' . $i . '" name="title' . $i . '" value="' . $this->fields["title" . $i] . '" /></td>
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

    public function showApplicform() {

        $this->checkLogged();

        // assign vars in our main layout tpl
        $this->mainTpl->assign('pageMeta', '<link rel="stylesheet" href="./core/js/datepicker/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen"/><link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"/><link rel="stylesheet" href="./core/css/form.css" type="text/css"/>');
        $this->mainTpl->assign('siteTitle', 'Student Application Form');

        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=userhome');
        $this->mainTpl->assign('home', $_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile');

        $java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/applicform.java.tpl');

        $this->mainTpl->assign('pageJava', $java->getContent(true));

        $infoStudent = LagreeformDB::getStudentById($this->id);
        //$studyStudent = LagreeformDB::getStudyById($this->id);
        //$homeInst = LagreeformDB::getHomeInstitution($this->id);
        //$hostInst = LagreeformDB::getHostInstitution($this->id);


        $this->pageTpl->assign('fName', $infoStudent['firstName']);
        $this->pageTpl->assign('faName', $infoStudent['familyName']);
        $this->pageTpl->assign('dateBirth', $infoStudent['birthDate']);
        $this->pageTpl->assign('sex', $infoStudent['sex'] > 0 ? 'Male' : 'Female');
        $this->pageTpl->assign('nation', $infoStudent['country']);
        $this->pageTpl->assign('birthPlace', $infoStudent['birthPlace']);
        $this->pageTpl->assign('cAddress', $infoStudent['streetNr'] . ' - ' . $infoStudent['PostalCode'] . ' ' . $infoStudent['City']);
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
                                                        <td><input type="text" id="language' . $i . '" name="language' . $i . '" value="' . $this->fields["language" . $i] . '" /></td>
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

        $this->errors = null;
    }

    public function doApplic() {

        $rules = array();

        $rules[] = "required,study,This field is required.";

        $rules[] = "required,sendInstName,This field is required.";
        $rules[] = "required,sendInstAddress,This field is required.";

        $rules[] = "required,sendDepName,This field is required.";
        $rules[] = "required,sendDepAddress,This field is required.";

        $rules[] = "required,sendDepCoorName,This field is required.";
        $rules[] = "required,sendDepCoorTel,This field is required.";
        $rules[] = "required,sendDepCoorMail,This field is required.";

        $rules[] = "required,sendInstCoorName,This field is required.";
        $rules[] = "required,sendInstCoorTel,This field is required.";
        $rules[] = "required,sendInstMail,This field is required.";

        $rules[] = "required,acaYear,xxxx\/xxxx,This field is required.";
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

        $rules[] = "letters_only,sendInstName,Please only enter letters.";
        $rules[] = "is_alpha,sendInstAddress, Field can only contain letters and numbers";

        $rules[] = "letters_only,sendDepCoorName,Please only enter letters.";
        $rules[] = "letters_only,sendDepCoorTel,Please only enter letters.";
        $rules[] = "letters_only,sendDepCoorFax,Please only enter letters.";
        $rules[] = "valid_email,sendDepCoorMail,Must be of form john@example.com.";

        $rules[] = "letters_only,sendInstName,Please only enter letters.";
        $rules[] = "is_alpha,sendInstAddress, Field can only contain letters and numbers";

        $rules[] = "letters_only,sendInstCoorName,Please only enter letters.";
        $rules[] = "letters_only,sendInstCoorTel,Please only enter letters.";
        $rules[] = "letters_only,sendInstCoorFax,Please only enter letters.";
        $rules[] = "valid_email,sendInstCoorMail,Must be of form john@example.com.";

        $rules[] = "custom_alpha,acaYear,xxxx\/xxxx,Invalid input";
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
        $rules[] = "valid_date,signInstSignDate,Invalid date";

        $this->works = $_POST['workCount'];
        $this->languages = $_POST['languageCount'];

        for ($i = 0; $i <= $this->works; $i++) {
            //Plonk::dump($_POST);

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
        }
    }

}
