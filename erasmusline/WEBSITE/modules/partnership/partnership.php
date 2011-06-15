<?php
error_reporting(E_ERROR);
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}..${sep}"));
require_once('library/eis/ODB.php');
require_once('library/eis/Crypt.php');
require_once('library/eis/Util.php');
require_once('library/curl.php');
class PartnershipController extends PlonkController {
	private $crypt;
	private $db;
	private static $debug=false;
    protected $views = array('partnership','receive');

    private $institution_t = 'institutions';
	private $educations_t = 'education';
	private $courses_t = 'coursespereducperinst';
	private $study_t = 'educationperinstitute';
    
    
    public function __construct(){
    	$this->crypt = new Crypt();
    	$this->db = ODB::getInstance();
    }
    
    
    
    public function sendInstitution($intitutionId,$method,$params){
    	if(!isset($intitutionId) || empty($intitutionId)){
    		throw new Exception("Invalid Institution!");
    	}
    	$url = PartnershipDB::getURL($intitutionId);
    	return $this->send($url,$method,$params);
    }
    
    public function send($instUrl,$method,$params){
    	
    	if(!PlonkSession::exists('id')){
    		throw new Exception("Invalid user!");
    	}
    	
    	if(!isset($instUrl) || empty($instUrl)){
    		throw new Exception("Invalid url");
    	}
    	
    	if(preg_match("/:/", $method)<1){
    		throw new Exception("Invalid method invocation! ".
    							"must be \$module:\$methodname");
    	}
    	
    	if(!is_array($params)){
    		throw new Exception("Invalid Parameters! must be assoc. array!");
    	}
    	$json = json_encode(array('method' => $method,
    							'params' => $params));
    	Util::log("Outgoing JSON: ".$json);
    	$encrypted = $this->crypt->encrypt($json);
    	
    	$url = (preg_match("/^loopback/",$method)>0) ? self::curDomainURL() : 
    								$instUrl;
    	
    	$url .= "/index.php?module=partnership&view=receive";
    	
		Util::log("Outgoing URL: ".$url);
    	
    	$curl = new curl();
    								
    	$curl->start();
        $curl->setOption(CURLOPT_URL, $url);
        $curl->setOption(CURLOPT_POST, 1);
        $curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOption(CURLOPT_POSTFIELDS, "payload=" . $encrypted);
        $curl->execute();
        
        // json message
        $result = $curl->getResult();
        Util::log("result response message: ".$result);
        $message = json_decode($this->crypt->decrypt($result),true);
        Util::log("result message: ".print_r($message,true));
        
        if(!isset($message) || empty($message)){
        	throw new Exception("Invalid JSON response!");
        }
        
        return $message;
    }
    
    function showReceive(){
    	$module=$method="";
    	try{
    		
	    	$payload = PlonkFilter::getPostValue('payload');
	    	if(empty($payload)){
	    		Util::log("error Invalid Infox payload!");
	    		throw new Exception('Invalid Infox payload!');
	    	}
	    	
	    	$message = json_decode($this->crypt->decrypt($payload),true);
	    	
	    	if(!isset($message)){
	    		Util::log("error Invalid JSON message");
	    		throw new Exception("Invalid JSON message");
	    	}
	    	
	    	Util::log("incomming message: ".print_r($message,true));
	    	
	    	list($module,$method) = preg_split("/:/", $message['method']);
	    	
	    	$obj = ($module=='partnership' || $module=='loopback') ? 
	    				$this : Util::loadController($module);
	    	
	    	$runnable = array($obj,$method);
	    	
	    	if(!is_callable($runnable)){
	    		throw new Exception("Invalid invocation of ${method} method");
	    	}
	    	
	    	$result = call_user_func_array($runnable,array($message['params']));
	    	
	    	$json = json_encode($result);
	    	Util::log("json result: ".$json);
	    	$encrypted = $this->crypt->encrypt($json);
	    	
	    	Util::log("encrypted result: ".$encrypted);
	    	$this->output($encrypted);
	    	
    	}catch(Exception $e){
    		Util::log("Exception on (${module}:${method}): ".$e->getMessage()
    							."\n");
    		$out = $this->crypt->encrypt($this->jsonError($e->getMessage(),
    						"${module}:${method}"));
    		$this->output($out);
    	}
    }
    
    function ping($params){
    	return array("Hi"=>$params['hello']);
    }
    
    function showPartnership(){
    	header('Content-Type: text/plain');
    	$res = $this->send("xpto","loopback:ping",array(
    					"hello" => "User!"
    				));
    	$out = print_r($res,true);
    	$this->output($out);
    	
    }
    
    static function curDomainURL() {
		 $pageURL = 'http';
		 if ( key_exists('HTTPS', $_SERVER) && 
		 		$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		 } else {
		  	$pageURL .= $_SERVER["SERVER_NAME"];
		 }
		 
		 $pageURL .= (preg_match("/WEBSITE/",$_SERVER["REQUEST_URI"])) ? 
		 			"/erasmusline/WEBSITE" : "/erasmusline";
		 
		 return $pageURL;
	}
	
	
	function output(&$out){
		header('Content-Type: text/plain');
		ob_start();
		echo $out;
    	flush();
    	ob_flush(); 
    	exit(0);
	}
	
	/**
	 * 
	 * Generates a JSON-RPC error message
	 * @param string error message
	 * @param string error code
	 * @param int id number to match incomming json-rpc message (optional)
	 * @param string exception message if you catch it
	 */
	function jsonError($error,$method){
		$obj = array(
			"error" => $error,
			"method" => $method
		);
		
		return json_encode($obj);
	}
	
	/**
	 * 
	 * Generates a JSON-RPC result message
	 * @param mixed result variable, can be array, string, etc.
	 */
	function jsonResult(&$result){
		
		$obj = array(
			"result" => $result
		);
		
		return json_encode($obj);
	}
	
	/**
	 * 
	 * Synchronization methods
	 * 
	 */
	
	function newInstitution($params){
		$db = $this->db;
		
		$institution_t = $this->institution_t;
		$educations_t = $this->educations_t;
		$courses_t = $this->courses_t;
		$study_t = $this->study_t;
		
		$eduTrans = array(); // education id's dictionary
		
		$institution = $params['instData'];
		$institutionId = $institution['instEmail'];
		$educations = $params['educationData'];
		$courses = $params['courseData'];
		
		Util::log(print_r($institution,true));
		Util::log(print_r($educations,true));
		Util::log(print_r($courses,true));
		
		$db->beginTransaction();
		
		$c = $db->getOne("select count(instId) as cnt from $institution_t ".
					" where instEmail='$institutionId'");
		
		if($c['cnt']>0){
			throw new Exception("INST_EXISTS");
		}
		
		// insert institution
		unset($institution['instId']);
		$instId = $db->insert($institution, $institution_t);
		
		// insert educations by order and return new id's array by order
		$educations_id = array_map(
		function($item)
			use($db,$educations_t,&$eduTrans,$institutionId,$study_t){
			$oldId = $item['educationId'];
			unset($item['educationId']);
			$id = $db->insert($item, $educations_t);
			$eduTrans[$oldId] = $id; // id translation hash
			
			$study = array('studyId' => $id,
				'Description' => $item['educationName'],
				'institutionId' => $institutionId);
			
			$db->insert($study, $study_t);
			
			return $id;
		}, $educations);
		
		#Util::log(print_r($eduTrans,true));
		
		// replace education id's and insert courses
		$courses_id = array_map(
		function($item)use($db,$courses_t,$eduTrans){
			unset($item['courseId']);
			$oldId = $item['educationId'];
			$item['educationId'] = $eduTrans[$oldId]; // swap for new id
			$id = $db->insert($item, $courses_t);
			return $id;
		}, $courses);
		
		$db->commitTransaction();
		
		return array('OK'=>true,'educations_ids'=>$educations_id,
					'courses_id'=>$courses_id);
	}
	
	function newEducation($params){
		$db = $this->db;

		$educations_t = $this->educations_t;
		$study_t = $this->study_t;
		
		$item = $params;
		unset($item['educationId']);
		$instId = $item['institutionId'];
		$id = $db->insert($item, $educations_t);
		
		$study = array('studyId' => $id,
				'Description' => $item['educationName'],
				'institutionId' => $instId);
			
		$db->insert($study, $study_t);

		return array('OK'=>true,'id'=>$id);
	}
	
	function newCourse($params){
		$db = $this->db;

		
		$courses_t = $this->courses_t;
		$educations_t = $this->educations_t;
		
		$item = $params;
		unset($item['courseId']);
		
		$education = $db->getOne("select * from ${educations_t} ".
				"where educationName ='$item[educationName]'");
		
		$item['educationId'] = $education['educationId'];
		
		$id = $db->insert($item, $courses_t);
		

		return array('OK'=>true, 'id'=>$id);
	}

	function updateInstitution($params){
		$db = $this->db;
		
		$item = $params;
		unset($item['instId']);
		$item['table'] = $this->institution_t;
		
		$num = $db->update($item, "instEmail='$item[instEmail]'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
	function updateEducation($params){
		$db = $this->db;
		$educations_t = $this->educations_t;
		$study_t = $this->study_t;
		
		$item = $params;
		unset($item['educationId']);
		
		// todo inner join
		$education = $db->getOne("select * from ${educations_t} ".
				"where educationName ='$item[oldName]'");
		
		$instId = $item['instEmail'];
		
		$item['table'] = $educations_t;
		$num = $db->update($item, "educationId='$education[educationId]'");
		
		$study = array('studyId' => $education['educationId'],
				'Description' => $item['Description'],
				'institutionId' => $instId,
				'table' => $study_t);
		
		$db->update($study,"institutionId='$instId'".
							" and studyId='$education[educationId]'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
	function updateCourse($params){
		$db = $this->db;
		$educations_t = $this->educations_t;
		$courses_t = $this->courses_t;
		
		$item = $params;
		$education = $db->getOne("select * from ${educations_t} ".
				"where educationName ='$item[educationName]'");
		
		$instId = $item['institutionId'];
		$code = $item['courseCode'];
		
		unset($item['courseId']);
		unset($item['courseCode']);
		unset($item['institutionId']);
		$item['educationId'] = $education[educationId];
		
		$item['table'] = $courses_t;
		$num = $db->update($item, "institutionId='${instId}'".
								" and courseCode='${code}'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
	function deleteEducation($params){
		$db = $this->db;
		$educations_t = $this->educations_t;
		$courses_t = $this->courses_t;
		$study_t = $this->study_t;
				
		$education = $params;
		$instId = $education['institutionId'];
		
		$education = $db->getOne("select * from ${educations_t} ".
				"where educationName ='$education[educationName]'");
		
		$c = $db->getOne("select count(*) as cnt from $courses_t".
							" where educationId='$education[educationId]'");
		
		if($c['cnt']>0){
			throw new Exception("EDU_HAS_COURSES");
		}
		
		$db->delete($study_t, 
			"studyId='$education[educationId]' and institutionId='$instId'");
		
		$num = $db->delete($educations_t, 
			"educationId='$education[educationId]'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
	function deleteCourse($params){
		$db = $this->db;
		$courses_t = $this->courses_t;
		
		$course = $params;
		
		$num = $db->delete($courses_t, 
			"courseCode='$course[courseCode]' ".
			" and institutionId='$course[institutionId]'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
}
?>