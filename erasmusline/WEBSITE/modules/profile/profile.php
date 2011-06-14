<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ProfileController extends PlonkController {

    /**
     * The views allowed for this module
     * @var array
     */
    protected $views = array(
        'profile',
        'ownprofile',
        'edit'
    );
    /**
     * The actions allowed for this module
     * @var array
     */
    protected $actions = array('edit'
    );
    private $id, $erasmusLevel;

    /**
     * Assign variables that are main and the same for every view
     * @param	= null
     * @return  = void
     */
    private function mainTplAssigns($pageTitle) {
        // Assign main properties
        $this->mainTpl->assign('pageJava', '');
        $this->mainTpl->assign('breadcrumb', '');
        $this->mainTpl->assign('siteTitle', $pageTitle);
        $this->mainTpl->assign('pageMeta', '
            <script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("UA-1120774-3");
			pageTracker._trackPageview();
			} catch(err) {}
		</script>
		<script type="text/javascript" src="http://t.wits.sg/misc/js/jQuery/jquery.js"></script>
		<script type="text/javascript" src="./core/js/progressbar/js/jquery.progressbar.js"></script>
		<script type="text/javascript">

			$(document).ready(function() {
				$("#pb1").progressBar({$progress});
			});


		</script>
                <style type="text/css">
			table tr { vertical-align: top; }
			table td { padding: 3px; }
			div.contentblock { padding-bottom: 25px; }	
		</style>
                ');
    }

    public function showOwnprofile() {
        $this->showProfile();

        $erasmus = ProfileDB::getErasmusById($this->id);
        //Plonk::dump($erasmus);
        if (!empty($erasmus)) {
            $this->pageTpl->assign('start', $erasmus['startDate']);
            $this->pageTpl->assign('end', $erasmus['endDate']);
        } else {
            $this->pageTpl->assign('start', '');
            $this->pageTpl->assign('end', '');
        }

        $home = ProfileDB::getHome($this->id);
        if (!empty($home)) {
            $this->pageTpl->assign('home', $home['instName']);
            $this->pageTpl->assign('hCooordinator', $home['familyName'] . ' ' . $home['firstName']);
        } else {
            $this->pageTpl->assign('home', '');
            $this->pageTpl->assign('hCooordinator', '');
        }

        $host = ProfileDB::getHost($this->id);
        if (!empty($host)) {
            $this->pageTpl->assign('destination', $host['instName']);
            $this->pageTpl->assign('dCoordinator', $host['familyName'] . ' ' . $host['firstName']);
        } else {
            $this->pageTpl->assign('destination', '');
            $this->pageTpl->assign('dCoordinator', '');
        }

        $study = ProfileDB::getStudy($this->id);
        if (!empty($study)) {
            $this->pageTpl->assign('study', $study['educationName']);
        } else {
            $this->pageTpl->assign('study', '');
        }

        $this->pageTpl->assign('profile', './files/' . $this->id . '/profile.jpg');

        $courses = ProfileDB::getCourses($this->id);

        if (!empty($courses)) {
            $this->pageTpl->setIteration('iCourses');
            $i = 0;
            foreach ($courses as $course) {
                $this->pageTpl->assignIteration('course', $course['courseName']);
                $this->pageTpl->assignIteration('ects', $course['ectsCredits']);
                $i += $course['ectsCredits'];
                $this->pageTpl->refillIteration();
            }
            $this->pageTpl->parseIteration();
            $this->pageTpl->assign('total', $i);
        } else {
            $this->pageTpl->assign('total', '');
        }

        $erasmuslevel = ProfileDB::getErasmusById($this->id);
        
        if (!empty($erasmuslevel)) {
            if ($erasmuslevel['statusOfErasmus'] == 'Precandidate') {
                if ($erasmuslevel['action'] == 2)
                    $this->erasmusLevel = 5;
                if ($erasmuslevel['action'] == 1)
                    $this->erasmusLevel = 10;
            }
            if ($erasmuslevel['statusOfErasmus'] == 'Student Application and Learning Agreement') {
                if ($erasmuslevel['action'] == 22)
                    $this->erasmusLevel = 15;
                if ($erasmuslevel['action'] == 21 || $erasmuslevel['action'] == 12 || $erasmuslevel['action'] == 10 || $erasmuslevel['action'] == 1)
                    $this->erasmusLevel = 25;
                if ($erasmuslevel['action'] == 11)
                    $this->erasmusLevel = 40;
                else
                    $this->erasmusLevel = 10;
            }
            if ($erasmuslevel['statusOfErasmus'] == 'Accomodation Registration Form') {
                if ($erasmuslevel['action'] == 1)
                    $this->erasmusLevel = 50;
                if ($erasmuslevel['action'] == 2)
                    $this->erasmusLevel = 45;
                else
                    $this->erasmusLevel = 40;
            }
        }

        $this->mainTpl->assign('progress', $this->erasmusLevel);
    }

    public function showProfile() {

        // Main Layout
        // Logged or not logged, that is the question...

        $this->checkLogged();
        $this->mainTplAssigns('Profile');

        // assign menu active state
        $this->mainTpl->assignOption('oNavProfile');
        $this->id = PlonkSession::get('id');
        $info = ProfileDB::getItemsById($this->id);
        $erasmuslevel = ProfileDB::getErasmusById($this->id);

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

    public function showEdit() {
        $variables = array(
            'familyName', 'firstName', 'sex',
            'birthDate', 'birthPlace', 'tel', 'mobilePhone', 'streetNr',
            'city', 'postalCode', 'country'
        );

        $this->checkLogged();
        $this->mainTplAssigns('Profile - Edit personal info');

        $user = ProfileDB::getItemsById(PlonkSession::get('id'));

        foreach ($variables as $value) {
            if ($value === 'sex') {
                if ($user[$value] == '1') {
                    $this->pageTpl->assign($value . 'True', 'checked');
                } else {
                    $this->pageTpl->assign($value . 'False', 'checked');
                }
            } else {
                $this->pageTpl->assign($value, $user[$value]);
            }

            $this->pageTpl->assign('msg' . ucfirst($value), '*');
        }

        $countries = ProfileDB::getCountries();
        try {
            $this->pageTpl->setIteration('iCountries');
            foreach ($countries as $key => $value) {
                if ($value == $user['country']) {
                    $this->pageTpl->assignIteration('nationality', '<option selected=\"true\" value=' . $value['Code'] . '> ' . $value['Name'] . '</option>');
                } else {
                    $this->pageTpl->assignIteration('nationality', '<option value="' . $value['Code'] . '"> ' . $value['Name'] . '</option>');
                }
                $this->pageTpl->refillIteration('iCountries');
            }
            $this->pageTpl->parseIteration('iCountries');
        } catch (Exception $e) {
            
        }
    }

    public function doEdit() {
        $this->fillRules();
        $this->errors = validateFields($_POST, $this->rules);
        if (!empty($this->errors)) {
            $this->fields = $_POST;
        } else {
            $values = array(
                'familyName' => htmlentities(PlonkFilter::getPostValue('familyName')),
                'firstName' => htmlentities(PlonkFilter::getPostValue('firstName')),
                'streetNr' => htmlentities(PlonkFilter::getPostValue('streetNr')),
                'city' => htmlentities(PlonkFilter::getPostValue('city')),
                'postalCode' => htmlentities(PlonkFilter::getPostValue('postalCode')),
                'tel' => htmlentities(PlonkFilter::getPostValue('tel')),
                'mobilePhone' => htmlentities(PlonkFilter::getPostValue('mobilePhone')),
                'birthDate' => htmlentities(PlonkFilter::getPostValue('birthDate')),
                'birthPlace' => htmlentities(PlonkFilter::getPostValue('birthPlace')),
                'country' => htmlentities(PlonkFilter::getPostValue('country')),
                'sex' => htmlentities(PlonkFilter::getPostValue('sex')),
                'origin' => 0
            );

            ProfileDB::updateUser('users', $values, 'email = "'.PlonkSession::get('id').'"');

            if (!empty($_FILES['pic'])) {
                $this->upload();
            }

            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=profile&' . PlonkWebsite::$viewKey . '=ownprofile');
        }
    }

    private function upload() {

        mkdir("files/" . PlonkFilter::getPostValue('email') . '/', 0777);
        $uploaddir = "files/" . PlonkFilter::getPostValue('email') . "/";

        foreach ($_FILES["pic"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["pic"]["tmp_name"][$key];
                $name = 'profile.jpg';
                $uploadfile = $uploaddir . basename($name);

                if (move_uploaded_file($tmp_name, $uploadfile)) {
                    $cover = $uploadfile;
                } else {
                    echo "Error: File " . $name . " cannot be uploaded.<br/>";
                }
            }
        }
    }

    private function fillRules() {
        $this->rules[] = "required,familyName,Last name is required.";
        $this->rules[] = "required,firstName,First name is required.";
        $this->rules[] = "required,birthPlace,Birthplace is required";
        $this->rules[] = "required,mobilePhone,Phone number is required";
        $this->rules[] = "required,tel,Phone number is required";
        $this->rules[] = "required,streetNr,Street + NR = required";
        $this->rules[] = "required,city,City is required";
        $this->rules[] = "required,postalCode,Postal Code is required";
        $this->rules[] = "digits_only,postalCode,Only digits allowed in Postal Code";
        $this->rules[] = "required,country,Choose a country";
        $this->rules[] = "required,birthDate,Date wrong format";
    }

    public function checkLogged() {
        //Plonk::dump(PlonkSession::get('id').'hgdjdh');
        if (!PlonkSession::exists('id')) {
            PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&' . PlonkWebsite::$viewKey . '=home');
        } else {
            if (PlonkSession::get('id') === 0) {
                PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=admin&' . PlonkWebsite::$viewKey . '=admin');
            } else {
                if (PlonkSession::get('userLevel') == "Student") {
                    $this->pageTpl->assignOption('oStudent');
                }
            }
        }
    }

}
