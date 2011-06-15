<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "./modules/partnership/partnership.php";


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
		'action',
		'editcourse',
		'editeducation',
		'editinstitution'
		);
		/**
		 * The actions allowed for this module
		 * @var array
		 */
		protected $actions = array(
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
        	/**
        	 * TODO: Update to the latest checkLogged
        	 */
        }

        public function showInstitution() {
        	// Main Layout
        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');
        	$this->mainTpl->assign('siteTitle', 'Admin page');

        	$instDatas = InstitutionDB::getInstData();
        	 
        	 
       	
        	foreach ($instDatas as $instData) {
        		$this->pageTpl->assign('instname', $instData['instName']);
        		$this->pageTpl->assign('editUrl', $_SERVER['PHP_SELF']
        		. '?' . PlonkWebsite::$moduleKey . '=institution&' .
        		PlonkWebsite::$viewKey . '=action&t=i&a=e');
        		//TODO: Inst Email, to send or not to send thats the question
        	}
        }

        public function showCourses() {

        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');
        	$this->mainTpl->assign('siteTitle', 'Overview courses');

        	// gets course list
        	$courses = InstitutionDB::getCourseInfo();

        	$this->pageTpl->setIteration('iCourses');

        	// loops through all the users (except for the user with id 1 = admin) and assigns the values
        	foreach ($courses as $course) {
        		$this->pageTpl->assignIteration('name', $course['courseCode']
        		. ' - ' . $course['courseName']);
        		$this->pageTpl->assignIteration('deleteUrl', $_SERVER['PHP_SELF']
        		. '?' . PlonkWebsite::$moduleKey . '=institution&' .
        		PlonkWebsite::$viewKey . '=action&t=c&a=d&id=' .
        		$course['courseId']);
        		$this->pageTpl->assignIteration('editUrl', $_SERVER['PHP_SELF']
        		. '?' . PlonkWebsite::$moduleKey . '=institution&' .
        		PlonkWebsite::$viewKey . '=action&t=c&a=e&id=' .
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
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');
        	$this->mainTpl->assign('siteTitle', 'Overview educations');

        	// gets education list
        	$educations = InstitutionDB::getEducationInfo();

        	$this->pageTpl->setIteration('iEducations');

        	// loops through all the users (except for the user with id 1 = admin) and assigns the values
        	foreach ($educations as $education) {
        		$this->pageTpl->assignIteration('name', $education['educationName']);
        		$this->pageTpl->assignIteration('deleteUrl', $_SERVER['PHP_SELF']
        		. '?' . PlonkWebsite::$moduleKey . '=institution&' .
        		PlonkWebsite::$viewKey . '=action&t=e&a=d&id=' .
        		$education['educationId']);
        		$this->pageTpl->assignIteration('editUrl', $_SERVER['PHP_SELF']
        		. '?' . PlonkWebsite::$moduleKey . '=institution&' .
        		PlonkWebsite::$viewKey . '=action&t=e&a=e&id=' .
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

        				/**
        				 * TODO: creat a check for courses using it first
        				 * and then allowing the delete
        				 *
        				 */

        				$where = "studyId = ".$id." AND institutionId = '".
        				INST_EMAIL."'";
        				InstitutionDB::delete('educationperinstitute',$where);

        				$where = 'educationId = '.$id;
        				InstitutionDB::delete('education',$where);

        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=educations');
        			}
        			break;

        		case 'e':
        			$id = $_GET["id"];
        			$type = $_GET["t"];
        			if($type=='c'){
        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=editcourse&id='.$id);
        			}
        			if($type=='e'){
        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=editeducation&id='.$id);
        			}
        			if($type=='i'){
        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=editinstitution&id='.$id);
        			}
        			break;
        		default:
        			break;
        	}

        }

        public function showEditcourse() {

        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');
        	$this->mainTpl->assign('siteTitle', 'Edit course');




        	// gets info of selected course
        	$where = "institutionId = '" . INST_EMAIL . "' AND courseId = '" .
        	$_GET['id']."'";

        	$course_datas = InstitutionDB::select('coursespereducperinst',$where);
        	foreach ($course_datas as $course_data) {
        		$this->pageTpl->assign('courseCode',$course_data['courseCode']);
        		$this->pageTpl->assign('courseName',$course_data['courseName']);
        		$this->pageTpl->assign('eCTs',$course_data['ectsCredits']);
        		$this->pageTpl->assign('courseDesc',$course_data['courseDescription']);
        		$this->fillEducation($course_data['educationId']);
        		$this->pageTpl->assign('hid',$_GET['id']);
        	}


        	foreach ($this->variables as $value) {
        		if (empty($this->fields)) {
        			$this->pageTpl->assign('msg' . ucfirst($value), '*');
        			$this->pageTpl->assign($value, '');
        		}
        	}

        }

        public function showEditeducation() {

        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');
        	$this->mainTpl->assign('siteTitle', 'Edit education');




        	// gets info of selected course
        	$where = "institutionId = '" . INST_EMAIL . "' AND studyId = '" .
        	$_GET['id']."'";

        	$education_d1 = InstitutionDB::select('educationperinstitute',$where);

        	$where = "educationId = '" . $_GET['id']."'";

        	$education_d2 = InstitutionDB::select('education',$where);
        	foreach ($education_d1 as $education_v1){
        		foreach ($education_d2 as $education_v2) {
        			$this->pageTpl->assign('educationName',$education_v2['educationName']);
        			$this->pageTpl->assign('educationDesc',$education_v1['Description']);
        			$this->pageTpl->assign('hid',$_GET['id']);
        		}
        	}


        	foreach ($this->variables as $value) {
        		if (empty($this->fields)) {
        			$this->pageTpl->assign('msg' . ucfirst($value), '*');
        			$this->pageTpl->assign($value, '');
        		}
        	}

        }

        public function showEditinstitution() {

        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');
        	$this->mainTpl->assign('siteTitle', 'Edit education');

			
        	
        	$params = array(
				           'instData' => InstitutionDB::getInstData(),
				           'courseData' => InstitutionDB::getCourseInfo(),
                   		   'educationData' => InstitutionDB::getEducationInfo(),
        	);
			$obj = new PartnershipController();
			$obj->send('http://10.0.28.143/erasmusline', 'partnership:newInstitution', $params);
			
			
        	
        	
        	
        	$inst_data = InstitutionDB::getInstData();

        	foreach ($inst_data as $inst){
        		$this->pageTpl->assign('institutionName',$inst['instName']);

        	}


        	foreach ($this->variables as $value) {
        		if (empty($this->fields)) {
        			$this->pageTpl->assign('msg' . ucfirst($value), '*');
        			$this->pageTpl->assign($value, '');
        		}
        	}

        }

        private function fillEducation($selectedID = '') {

        	/**
        	 *
        	 * Fill the combo box
        	 * with available educations
        	 */

        	$educations = InstitutionDB::getEducationInfo();
        	try {
        		$this->pageTpl->setIteration('iEducations');
        		foreach ($educations as $key => $value) {
        			if($selectedID == $value['educationId']){
        				$this->pageTpl->assignIteration('education',
        				"<option value='" . $value['educationId'] . "' selected = 'selected'> " .
        				$value['educationName'] . '</option>');
        			}
        			else{
        				$this->pageTpl->assignIteration('education',
        				'<option value="' . $value['educationId'] . '"> ' .
        				$value['educationName'] . '</option>');
        			}
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
        					$obj = new PartnershipController();

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
        					/**
        				 	* TODO: since they are shared, make a selection
        				 	* of the list of this inst, and if name equals then
        				 	* add that id, else add new entry
        				 	*/


        					$educations = InstitutionDB::getEducationInfo();
        					$res = true;
        					$id;
        					foreach ($educations as $key => $value) {
        						if($_POST["educationname"] == $value['educationName']){
        							$res = false;
        							break;
        						}
        					}

        					if($res){

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

        					}
        					else{

        						//error of choice
        					}
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
        			'courseId' => $_POST["hiddenid"],
	        		'courseCode' => $_POST["coursecode"],//htmlentities(PlonkFilter::getPostValue('courseCode')),
	                'courseName' => $_POST["coursename"],//htmlentities(PlonkFilter::getPostValue('courseName')),
	                'ectsCredits' => $_POST["ects"],//htmlentities(PlonkFilter::getPostValue('eCTs')),
	                'courseDescription' => $_POST["coursedesc"],//htmlentities(PlonkFilter::getPostValue('courseDesc')),
	                'educationId' => htmlentities(PlonkFilter::getPostValue('education')),
	                'institutionId' => INST_EMAIL //"info@kahosl.be"            
        			);


        			if (PlonkSession::exists('id')) {
        				if (PlonkSession::get('id') == '0') {
        					InstitutionDB::update('coursespereducperinst', $values);
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
        						'educationId' => $_POST["hiddenid"],
	                			'educationName' => $_POST["educationname"],//htmlentities(PlonkFilter::getPostValue('educationName')),
        					);
        					InstitutionDB::update('education', $values);
        					$values2 = array(
				                'Description' => $_POST["educationdesc"],//htmlentities(PlonkFilter::getPostValue('educationDesc')),
        					);
        					$where = "institutionId = '" . INST_EMAIL . "' AND
        					studyId ='".$_POST["hiddenid"]."'";
        					InstitutionDB::update('educationperinstitute',
        					$values2, $where);
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
        		case 'editinstitution':
        			/*$this->fillRules();
        			 $this->errors = validateFields($_POST, $this->rules);
        			 if (!empty($this->errors)) {
        			 $this->fields = $_POST;
        			 } else {*/

        			if (PlonkSession::exists('id')) {
        				if (PlonkSession::get('id') == '0') {
        					$values = array(
        						'instEmail' => INST_EMAIL,
	                			'educationName' => $_POST["educationname"],//htmlentities(PlonkFilter::getPostValue('educationName')),
        					);
        					InstitutionDB::update('education', $values);
        					$values2 = array(
				                'Description' => $_POST["educationdesc"],//htmlentities(PlonkFilter::getPostValue('educationDesc')),
        					);
        					$where = "institutionId = '" . INST_EMAIL . "' AND
        					studyId ='".$_POST["hiddenid"]."'";
        					InstitutionDB::update('educationperinstitute',
        					$values2, $where);
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
