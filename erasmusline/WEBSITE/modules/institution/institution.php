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
		'editinstitution',
		'newpartner',
		'partnererror'
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
        'education', 'error', 'educationName', 'educationDesc',
        'institutionEmail','institutionName','institutionStrNr','institutionCity',
        'institutionPostalCode','institutionCountry','institutionTel',
        'institutionFax','institutionDesc','institutionWeb','institutionType',
        'institutionUrl','institutionUrl','institutionScale','institutionDigital',
        'institutionIban','institutionBic','url'
        );


        protected $fields = array();
        protected $errors = array();
        protected $rules = array();

        public function checkLogged() {
        	if (!PlonkSession::exists('id')) {

        		PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&amp;' . PlonkWebsite::$viewKey . '=home');
        	} else {
        		//Plonk::dump('test');
        		if (PlonkSession::get('id') === 0) {
        			$this->id = PlonkSession::get('id');
        		} else if (PlonkSession::get('userLevel') == 'Student') {
        			PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=home&amp;' . PlonkWebsite::$viewKey . '=userhome');
        		} else {
        			PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' . PlonkWebsite::$moduleKey . '=staff&amp;' . PlonkWebsite::$viewKey . '=staff');
        		}
        	}
        }

        public function showInstitution() {
        	$this->mainTpl->assign('pageJava', '');
        	$this->mainTpl->assign('breadcrumb', '');
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


        	}
        }

        public function showNewpartner(){
        	$this->mainTpl->assign('pageJava', '');
        	$this->mainTpl->assign('breadcrumb', '');
        	// Main Layout
        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');
        	$this->mainTpl->assign('siteTitle', 'Admin page');


        	foreach ($this->variables as $value) {
        		if (empty($this->fields)) {
        			$this->pageTpl->assign('msg' . ucfirst($value), '*');
        			$this->pageTpl->assign($value, '');
        		}
        	}

        }

        public function showPartnererror(){
        	$this->mainTpl->assign('pageJava', '');
        	$this->mainTpl->assign('breadcrumb', '');
        	// Main Layout
        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');
        	$this->mainTpl->assign('siteTitle', 'Admin page');
        }

        public function showCourses() {
        	$this->mainTpl->assign('pageJava', '');

        	$this->mainTpl->assign('breadcrumb', '');
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
        	$this->mainTpl->assign('pageJava', '');

        	$this->mainTpl->assign('breadcrumb', '');
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
        	$this->mainTpl->assign('pageJava', '');

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

        				$data = array_shift(InstitutionDB::select('coursespereducperinst',
        				'courseId = '.$id));
        				$course_code = $data['courseCode'];

        				$where = 'courseId = '.$id;
        				InstitutionDB::delete('coursespereducperinst',$where);

        				$params['courseCode'] = $course_code;
        				$params['institutionId'] = INST_EMAIL;
        				$this->institutionLoop('deleteCourse', $params);

        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=courses');
        			}
        			if($type=='e'){

        				/**
        				 * TODO: creat a check for courses using it first
        				 * and then allowing the delete if nones associated
        				 *
        				 */
        				 
        				$data = array_shift(InstitutionDB::select('education',
        				'educationId = '.$id));
        				$name = $data['educationName'];

        				$where = "studyId = ".$id." AND institutionId = '".
        				INST_EMAIL."'";
        				InstitutionDB::delete('educationperinstitute',$where);

        				$where = 'educationId = '.$id;
        				InstitutionDB::delete('education',$where);

        				$values['institutionId']=INST_EMAIL;
        				$values['educationName']=$name;
        				$this->institutionLoop('partnership:deleteEducation',$values);

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
        				PlonkWebsite::$viewKey . '=editinstitution');
        			}
        			break;
        		default:
        			break;
        	}

        }

        public function showEditcourse() {
        	$this->mainTpl->assign('pageJava', '');
        	$this->mainTpl->assign('breadcrumb','');
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
        		$this->pageTpl->assign('ccode',$course_data['courseCode']);
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
        	$this->mainTpl->assign('pageJava', '');

        	$this->mainTpl->assign('breadcrumb', '');
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
			$this->mainTpl->assign('pageJava', '');
        	$this->mainTpl->assign('breadcrumb', '');
        	// Logged or not logged, that is the question...
        	$this->checkLogged();

        	// assign vars in our main layout tpl
        	$this->mainTpl->assign('pageMeta', '<link rel="stylesheet"
        	href="./core/css/form.css" type="text/css" />');
        	$this->mainTpl->assign('siteTitle', 'Edit education');

        	$inst_data = array_shift(InstitutionDB::getInstData());

        	$this->pageTpl->assign('institutionEmail',$inst_data['instEmail']);
        	$this->pageTpl->assign('institutionName',$inst_data['instName']);
        	$this->pageTpl->assign('institutionStrNr',$inst_data['instStreetNr']);
        	$this->pageTpl->assign('institutionCity',$inst_data['instCity']);
        	$this->pageTpl->assign('institutionPostalCode',$inst_data['instPostalCode']);
        	$this->pageTpl->assign('institutionCountry',$inst_data['instCountry']);
        	$this->pageTpl->assign('institutionTel',$inst_data['instTel']);
        	$this->pageTpl->assign('institutionFax',$inst_data['instFax']);
        	$this->pageTpl->assign('institutionDesc',$inst_data['instDescription']);
        	$this->pageTpl->assign('institutionWeb',$inst_data['instWebsite']);
        	$this->pageTpl->assign('institutionType',$inst_data['traineeOrStudy']);
        	$this->pageTpl->assign('institutionUrl',$inst_data['url']);
        	$this->pageTpl->assign('institutionScale',$inst_data['scale']);
        	$this->pageTpl->assign('institutionDigital',$inst_data['digital']);
        	$this->pageTpl->assign('institutionIban',$inst_data['iBan']);
        	$this->pageTpl->assign('institutionBic',$inst_data['bic']);


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
        	$this->mainTpl->assign('pageJava', '');

        	$this->mainTpl->assign('breadcrumb', '');
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
        	$this->mainTpl->assign('pageJava', '');

        	$this->mainTpl->assign('breadcrumb', '');
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



        			InstitutionDB::insertDB('coursespereducperinst', $values);

        			$where = 'educationId = '.$values['educationId'];
        			$edu_data=InstitutionDB::select('education', $where);
        			$var = array_shift($edu_data);
        			$values['educationName'] = $var['educationName'];
        			unset($values['educationId']);
        			$this->institutionLoop('partnership:newCourse',$values);

        			PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        			PlonkWebsite::$moduleKey . '=institution&' .
        			PlonkWebsite::$viewKey . '=courses');


        			break;
        		case 'neweducation':
        			/*$this->fillRules();
        			 $this->errors = validateFields($_POST, $this->rules);
        			 if (!empty($this->errors)) {
        			 $this->fields = $_POST;
        			 } else {*/

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

        				$values2['educationName'] = $values['educationName'];
        				unset($values2['studyId']);
        				$this->institutionLoop('partnership:newEducation', $values2);

        			}
        			else{

        				//error of choice
        			}
        			PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        			PlonkWebsite::$moduleKey . '=institution&' .
        			PlonkWebsite::$viewKey . '=educations');


        			break;
        		case 'editcourse':
        			/*$this->fillRules();
        			 $this->errors = validateFields($_POST, $this->rules);
        			 if (!empty($this->errors)) {
        			 $this->fields = $_POST;
        			 } else {*/

        			$values = array(
        			'courseId' => $_POST["hiddenid"],
	        		'courseCode' => $_POST["hiddenccode"],//htmlentities(PlonkFilter::getPostValue('courseCode')),
	                'courseName' => $_POST["coursename"],//htmlentities(PlonkFilter::getPostValue('courseName')),
	                'ectsCredits' => $_POST["ects"],//htmlentities(PlonkFilter::getPostValue('eCTs')),
	                'courseDescription' => $_POST["coursedesc"],//htmlentities(PlonkFilter::getPostValue('courseDesc')),
	                'educationId' => htmlentities(PlonkFilter::getPostValue('education')),
	                'institutionId' => INST_EMAIL //"info@kahosl.be"            
        			);



        			InstitutionDB::update('coursespereducperinst', $values);

        			$where = 'educationId = '.$values['educationId'];
        			$edu_data=InstitutionDB::select('education', $where);
        			$var = array_shift($edu_data);
        			$values['educationName'] = $var['educationName'];
        			unset($values['educationId']);
        			unset($values['courseId']);
        			$this->institutionLoop('partnership:updateCourse',$values);

        			PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        			PlonkWebsite::$moduleKey . '=institution&' .
        			PlonkWebsite::$viewKey . '=courses');

        			break;
        		case 'editeducation':
        			/*$this->fillRules();
        			 $this->errors = validateFields($_POST, $this->rules);
        			 if (!empty($this->errors)) {
        			 $this->fields = $_POST;
        			 } else {*/


        			$values = array(
        						'educationId' => $_POST["hiddenid"],
	                			'educationName' => $_POST["educationname"],//htmlentities(PlonkFilter::getPostValue('educationName')),
        			);

        			$old_data = array_shift(InstitutionDB::select('education',
        					'educationId = '.$_POST["hiddenid"]));
        			$old_name = $old_data['educationName'];

        			InstitutionDB::update('education', $values);
        			$values2 = array(
				                'Description' => $_POST["educationdesc"],//htmlentities(PlonkFilter::getPostValue('educationDesc')),
        			);
        			$where = "institutionId = '" . INST_EMAIL . "' AND
        					studyId ='".$_POST["hiddenid"]."'";
        			InstitutionDB::update('educationperinstitute',
        			$values2, $where);

        			$values2['educationName'] = $values['educationName'];
        			$values2['instEmail'] = INST_EMAIL;
        			$values2['oldName'] = $old_name;
        			unset($values2['studyId']);
        			$this->institutionLoop('partnership:updateEducation', $values2);

        			PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        			PlonkWebsite::$moduleKey . '=institution&' .
        			PlonkWebsite::$viewKey . '=educations');

        			break;
        		case 'editinstitution':
        			/*$this->fillRules();
        			 $this->errors = validateFields($_POST, $this->rules);
        			 if (!empty($this->errors)) {
        			 $this->fields = $_POST;
        			 } else {*/

        			$values = array(
        					'instName' => $_POST['institutionName'],
	                		'instStreetNr' => $_POST['institutionStrNr'],
        					'instCity' => $_POST['institutionCity'],
        					'instPostalCode' => $_POST['institutionPostalCode'],
        					'instCountry' => $_POST['institutionCountry'],
        					'instTel' => $_POST['institutionTel'],
        					'instFax' => $_POST['institutionFax'],
        					'instDescription' => $_POST['institutionDesc'],
			       			'instWebsite' => $_POST['institutionWeb'],
        					'traineeOrStudy' => $_POST['institutionType'],
        					'url' => $_POST['institutionUrl'],
        					'scale' => $_POST['institutionScale'],
        					'digital' => $_POST['institutionDigital'],
        			        'iBan' => $_POST['institutionIban'],
        			        'bic' => $_POST['institutionBic'],
        			);
        			 
        			 
        			$where = "instEmail = '" . INST_EMAIL . "'";
        			InstitutionDB::update('institutions',
        			$values, $where);
        			
        			$this->institutionLoop('partnership:updateInstitution', $values);
        			
        			PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        			PlonkWebsite::$moduleKey . '=institution&');

        			break;
        		case 'newpartner':
        			$params = array(
		        	 'instData' => array_shift(InstitutionDB::getInstData()),
		        	 'courseData' => InstitutionDB::getCourseInfo(),
		        	 'educationData' => InstitutionDB::getEducationInfo(),
		        	 'ownerData' => InstitutionDB::getOwnerInfo(),
		        	 'residenceData' => InstitutionDB::getResidenceInfo(),
        			);
        			 
        			$temp = array_shift(InstitutionDB::getInstData());
        			$country_temp =
        			array_shift(InstitutionDB::getCountry($temp['instCountry']));
        			$params['Name'] = $country_temp['Name'];
        			 
        			$obj = new PartnershipController();

        			try{
        				Util::log($obj->send($_POST['url'], 'partnership:newInstitution', $params));
        			}
        			catch (Exception $e){
        				PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        				PlonkWebsite::$moduleKey . '=institution&' .
        				PlonkWebsite::$viewKey . '=partnererror');
        			}

        			PlonkWebsite::redirect($_SERVER['PHP_SELF'] . '?' .
        			PlonkWebsite::$moduleKey . '=institution&');
        			break;
        		default:
        			break;

        	}
        }

        private function institutionLoop($method,$params){
        	$obj = new PartnershipController();
        	$where = "instEmail != '". INST_EMAIL ."'";
        	$result = InstitutionDB::select('institutions', $where);
        	foreach($result as $data){
        		try{
        			Util::log($obj->send($data['url'], $method, $params));
        		}
        		catch (Exception $e){}
        	}
        }
}
