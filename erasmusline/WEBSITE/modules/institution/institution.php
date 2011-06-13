<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class InstitutionController extends PlonkController {

	/**
	 * The views allowed for this module
	 * @var array
	 */
	protected $views = array(
        'institution',
        'courses',
		'newcourse',
		'educations',
		'neweducation',
		'action'
		);
		/**
		 * The actions allowed for this module
		 * @var array
		 */
		protected $actions = array(
        'logout',
        'confirm',
        'infox',
        'submit',
		);


		protected $variables = array(
        'courseDesc', 'eCTs', 'courseName', 'courseCode', 
        'education', 'error', 'educationName', 'educationDesc'
        );


        protected $fields = array();
        protected $errors = array();
        protected $rules = array();

        public function checkLogged() {
        	/*
        	 * TODO: Update to the latest checkLogged
        	 */
        }

        public function showInstitution() {
        	// Main Layout
        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '');
        	$this->mainTpl->assign('siteTitle', 'Admin page');

        	$instDatas = InstitutionDB::getInstData();

        	//show own institution data
        	$this->pageTpl->setIteration('iInst');
        	foreach ($instDatas as $instData) {
        		$this->pageTpl->assignIteration('instemail', INST_EMAIL);
        		$this->pageTpl->assignIteration('instname', $instData['instName']);
        		$this->pageTpl->assignIteration('inststnumb', $instData['instStreetNr']);
        		$this->pageTpl->assignIteration('instcity', $instData['instCity']);
        		$this->pageTpl->assignIteration('instpostcode', $instData['instPostalCode']);
        		$this->pageTpl->assignIteration('insttele', $instData['instTel']);
        		$this->pageTpl->assignIteration('instfax', $instData['instFax']);
        		$this->pageTpl->assignIteration('instdesc', $instData['instDescription']);
        		$this->pageTpl->assignIteration('instweb', $instData['instWebsite']);
        		$this->pageTpl->assignIteration('insttype', $instData['traineeOrStudy']);
        		$this->pageTpl->assignIteration('editUrl', $_SERVER['PHP_SELF']
        		. '?' . PlonkWebsite::$moduleKey . '=institution&' .
        		PlonkWebsite::$viewKey . '=action&t=i&a=e');
        		//TODO: Inst Email, to send or not to send thats the question
        		$this->pageTpl->refillIteration('iInst');
        	}
        	$this->pageTpl->parseIteration('iInst');
        }

        public function showCourses() {

        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '');
        	$this->mainTpl->assign('siteTitle', 'Overview courses');

        	// gets info of all the users
        	$courses = InstitutionDB::getCourseInfo();

        	// assign iterations: overlopen van de gevonden users
        	$this->pageTpl->setIteration('iCourses');

        	// loops through all the users (except for the user with id 1 = admin) and assigns the values
        	foreach ($courses as $course) {
        		$this->pageTpl->assignIteration('name', $course['courseCode']
        		. ' - ' . $course['courseName']);
        		$this->pageTpl->assignIteration('deleteUrl', $_SERVER['PHP_SELF']
        		. '?' . PlonkWebsite::$moduleKey . '=institution&' .
        		PlonkWebsite::$viewKey . '=action&t=c&a=d&id=' .
        		$course['courseId']);

        		// refill the iteration (mandatory!)
        		$this->pageTpl->refillIteration('iCourses');
        	}

        	// parse the iteration
        	$this->pageTpl->parseIteration('iCourses'); // alternative: $tpl->parseIteration();

        }

        public function showEducations() {

        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '');
        	$this->mainTpl->assign('siteTitle', 'Overview educations');

        	// gets info of all the users
        	$educations = InstitutionDB::getEducationInfo();

        	// assign iterations: overlopen van de gevonden users
        	$this->pageTpl->setIteration('iEducations');

        	// loops through all the users (except for the user with id 1 = admin) and assigns the values
        	foreach ($educations as $education) {
        		$this->pageTpl->assignIteration('name', $education['educationName']);
        		$this->pageTpl->assignIteration('deleteUrl', $_SERVER['PHP_SELF']
        		. '?' . PlonkWebsite::$moduleKey . '=institution&' .
        		PlonkWebsite::$viewKey . '=action&t=e&a=d&id=' .
        		$education['educationId']);

        		// refill the iteration (mandatory!)
        		$this->pageTpl->refillIteration('iEducations');
        	}

        	// parse the iteration
        	$this->pageTpl->parseIteration('iEducations'); // alternative: $tpl->parseIteration();

        }

        public function showAction(){

        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '');
        	$this->mainTpl->assign('siteTitle', 'Edit');

        	$op = $_GET["a"];

        	switch($op){
        		case 'd':
        			$id = $_GET["id"];
        			$type = $_GET["t"];
        			if($type=='c'){
        				$where = 'courseId = '.$id;
        				InstitutionDB::delete('coursespereducperinst',$where);
        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=courses');
        			}
        			if($type=='e'){
        				$where = 'studyId = '.$id.'AND institutionId = '.
        				INST_EMAIL;
        				InstitutionDB::delete('educationperinstitute',$where);

        				/**
        				 * TODO: if educations are chared by institutions
        				 * either leave them or make a check if still exists
        				 * an entry in the previous table, if not then delete
        				 */
        				/*$where = 'educationId = '.$id;
        				 InstitutionDB::delete('education',$where);*/
        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=educations');
        			}
        			break;

        		case 'e':
        			$id = $_GET["id"];
        			$type = $_GET["t"];
        			if($type=='c'){
        				InstitutionDB::delete();
        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=courses');
        			}
        			if($type=='e'){
        				InstitutionDB::delete();
        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=educations');
        			}
        			if($type=='i'){
        				InstitutionDB::delete();
        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=institution');
        			}
        			break;
        		default:
        			break;
        	}

        }



        private function fillEducation() {

        	/**
        	 *
        	 * Fill the combo box
        	 * with available educations
        	 */

        	$educations = InstitutionDB::getEducationInfo();
        	try {
        		$this->pageTpl->setIteration('iEducations');
        		foreach ($educations as $key => $value) {
        			$this->pageTpl->assignIteration('education',
        			'<option value="' . $value['educationId'] . '"> ' .
        			$value['educationName'] . '</option>');
        			$this->pageTpl->refillIteration('iEducations');
        		}
        		$this->pageTpl->parseIteration('iEducations');
        	} catch (Exception $e) {

        	}
        }


        public function showNewcourse(){
        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl

        	$this->mainTpl->assign('siteTitle', 'Course insert');
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');

        	//$java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/register.java.tpl');
        	//$this->mainTpl->assign('pageJava', $java->getContent(true));


        	$this->fillEducation();

        	foreach ($this->variables as $value) {
        		if (empty($this->fields)) {
        			$this->pageTpl->assign('msg' . ucfirst($value), '*');
        			$this->pageTpl->assign($value, '');
        		}
        	}
        }


        public function showNeweducation(){
        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('siteTitle', 'Education insert');
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');        	 


        	//$java = new PlonkTemplate(PATH_MODULES . '/' . MODULE . '/layout/register.java.tpl');
        	//$this->mainTpl->assign('pageJava', $java->getContent(true));

        	foreach ($this->variables as $value) {
        		if (empty($this->fields)) {
        			$this->pageTpl->assign('msg' . ucfirst($value), '*');
        			$this->pageTpl->assign($value, '');
        		}
        	}
        }



        private function fillRules() {
        	//TODO
        }



        public function doSubmit() {
        	$op = $_POST['option'];
        	switch($op){

        		case 'newcourse':
        			/*$this->fillRules();
        			 $this->errors = validateFields($_POST, $this->rules);
        			 if (!empty($this->errors)) {
        			 $this->fields = $_POST;
        			 } else {*/
        			 
        			$values = array(
	        		'courseCode' => $_POST["coursecode"],//htmlentities(PlonkFilter::getPostValue('courseCode')),
	                'courseName' => $_POST["coursename"],//htmlentities(PlonkFilter::getPostValue('courseName')),
	                'ectsCredits' => $_POST["ects"],//htmlentities(PlonkFilter::getPostValue('eCTs')),
	                'courseDescription' => $_POST["coursedesc"],//htmlentities(PlonkFilter::getPostValue('courseDesc')),
	                'educationId' => htmlentities(PlonkFilter::getPostValue('education')),
	                'institutionId' => INST_EMAIL //"info@kahosl.be"            
        			);


        			if (PlonkSession::exists('id')) {
        				if (PlonkSession::get('id') == '0') {
        					InstitutionDB::insertDB('coursespereducperinst', $values);
        					PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        					PlonkWebsite::$moduleKey . '=institution&' .
        					PlonkWebsite::$viewKey . '=courses');
        				} else {
        					PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        					PlonkWebsite::$moduleKey . '=home&' .
        					PlonkWebsite::$viewKey . '=userhome');
        				}
        			}
        			break;
        		case 'neweducation':
        			/*$this->fillRules();
        			 $this->errors = validateFields($_POST, $this->rules);
        			 if (!empty($this->errors)) {
        			 $this->fields = $_POST;
        			 } else {*/

        			if (PlonkSession::exists('id')) {
        				if (PlonkSession::get('id') == '0') {
        					/*
        					 * TODO: Check if education already exists?
        					 */
        					$values = array(
	                			'educationName' => $_POST["educationname"],//htmlentities(PlonkFilter::getPostValue('educationName')),
        					);
        					$id=InstitutionDB::insertDB('education', $values);
        					$values2 = array(
				                'studyId' => $id,
				                'Description' => $_POST["educationdesc"],//htmlentities(PlonkFilter::getPostValue('educationDesc')),
				                'institutionId' => INST_EMAIL           
        					);
        					InstitutionDB::insertDB('educationperinstitute', $values2);
        					PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        					PlonkWebsite::$moduleKey . '=institution&' .
        					PlonkWebsite::$viewKey . '=educations');
        				} else {
        					PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        					PlonkWebsite::$moduleKey . '=home&' .
        					PlonkWebsite::$viewKey . '=userhome');
        				}
        			}
        			break;
        		case 'editcourse':
        			/*$this->fillRules();
        			 $this->errors = validateFields($_POST, $this->rules);
        			 if (!empty($this->errors)) {
        			 $this->fields = $_POST;
        			 } else {*/
        			 
        			$values = array(
	        		'courseCode' => $_POST["coursecode"],//htmlentities(PlonkFilter::getPostValue('courseCode')),
	                'courseName' => $_POST["coursename"],//htmlentities(PlonkFilter::getPostValue('courseName')),
	                'ectsCredits' => $_POST["ects"],//htmlentities(PlonkFilter::getPostValue('eCTs')),
	                'courseDescription' => $_POST["coursedesc"],//htmlentities(PlonkFilter::getPostValue('courseDesc')),
	                'educationId' => htmlentities(PlonkFilter::getPostValue('education')),
	                'institutionId' => INST_EMAIL //"info@kahosl.be"            
        			);


        			if (PlonkSession::exists('id')) {
        				if (PlonkSession::get('id') == '0') {
        					InstitutionDB::insertDB('coursespereducperinst', $values);
        					PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        					PlonkWebsite::$moduleKey . '=institution&' .
        					PlonkWebsite::$viewKey . '=courses');
        				} else {
        					PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        					PlonkWebsite::$moduleKey . '=home&' .
        					PlonkWebsite::$viewKey . '=userhome');
        				}
        			}
        			break;
        		case 'editeducation':
        			/*$this->fillRules();
        			 $this->errors = validateFields($_POST, $this->rules);
        			 if (!empty($this->errors)) {
        			 $this->fields = $_POST;
        			 } else {*/

        			if (PlonkSession::exists('id')) {
        				if (PlonkSession::get('id') == '0') {
        					$values = array(
	                			'educationName' => $_POST["educationname"],//htmlentities(PlonkFilter::getPostValue('educationName')),
        					);
        					$id=InstitutionDB::insertDB('education', $values);
        					$values2 = array(
				                'studyId' => $id,
				                'Description' => $_POST["educationdesc"],//htmlentities(PlonkFilter::getPostValue('educationDesc')),
				                'institutionId' => INST_EMAIL           
        					);
        					InstitutionDB::insertDB('educationperinstitute', $values2);
        					PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        					PlonkWebsite::$moduleKey . '=institution&' .
        					PlonkWebsite::$viewKey . '=educations');
        				} else {
        					PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        					PlonkWebsite::$moduleKey . '=home&' .
        					PlonkWebsite::$viewKey . '=userhome');
        				}
        			}
        			break;
        		default:
        			break;

        	}

        }
}
