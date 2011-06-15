<?php
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
	private $courses_t = 'coursespereducationperinst';
    
    
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
    	
    	$encrypted = $this->crypt->encrypt(
    						json_encode(
    							array('method' => $method,
    							'params' => $params)));
    	
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
        Util::log("result message: ".$message);
        
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
	    	
	    	Util::log("incomming message: ".$message);
	    	
	    	list($module,$method) = preg_split("/:/", $message['method']);
	    	
	    	$obj = ($module=='partnership' || $module=='loopback') ? 
	    				$this : Util::loadController($module);
	    	
	    	$runnable = array($obj,$method);
	    	
	    	if(!is_callable($runnable)){
	    		throw new Exception("Invalid invocation of ${method} method");
	    	}
	    	
	    	$result = call_user_func_array($runnable,array($message['params']));
	    	
	    	$encrypted = $this->crypt->encrypt(
	    						json_encode($result));
	    	
	    	Util::log("encrypted: ".$encrypted);
	    	$this->output($encrypted);
	    	
    	}catch(Exception $e){
    		Util::log("Exception: ".$e->getMessage());
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
		
		$eduTrans = array(); // education id's dictionary
		
		$institution = array_shift($params['instData']);
		$institutionId = $institution['instEmail'];
		$educations = $params['educationData'];
		$courses = $params['courseData'];
		
		$db->beginTransaction();
		
		// insert institution
		unset($institution['instId']);
		$instId = $db->insert($institution, $institution_t);
		
		// insert educations by order and return new id's array by order
		$educations_id = array_map(
		function($item)use($db,$educations_t,$eduTrans){
			$oldId = $item['educationId'];
			unset($item['educationId']);
			$id = $db->insert($item, $educations_t);
			$eduTrans[$oldId] = $id; // id translation hash
			return $id;
		}, $educations);
		
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
	
	function newCourse($params){
		$db = $this->db;

		
		$courses_t = $this->courses_t;
		$educations_t = $this->educations_t;
		
		$item = $params['courseData'];
		unset($item['courseId']);
		
		$education = $params['educationData'];
		$education = $db->getOne("select * from ${educations_t} ".
				"where educationName ='$education[educationName]'");
		
		$item['educationId'] = $education['educationId'];
		
		$id = $db->insert($item, $courses_t);
		

		return array('OK'=>true, 'id'=>$id);
	}
	
	function newEducation($params){
		$db = $this->db;

		$educations_t = $this->educations_t;
		
		$item = $params['educationData'];
		unset($item['educationId']);
		$id = $db->insert($item, $educations_t);
		

		return array('OK'=>true,'id'=>$id);
	}
	
	function updateInstitution($params){
		$db = $this->db;
		
		$item = $params['instData'];
		unset($item['instId']);
		$item['table'] = $this->institution_t;
		
		$num = $db->update($item, "instEmail='$item[instEmail]'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
	function updateEducation($params){
		$db = $this->db;
		$educations_t = $this->educations_t;
		
		$item = $params['educationData'];
		unset($item['educationId']);
		
		$education = $db->getOne("select * from ${educations_t} ".
				"where educationName ='$item[educationName]'");
		
		$item['table'] = $educations_t;
		$num = $db->update($item, "educationId='$education[educationId]'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
	function updateCourse($params){
		$db = $this->db;
		$educations_t = $this->educations_t;
		$courses_t = $this->courses_t;
		
		$education = $params['educationData'];
		$education = $db->getOne("select * from ${educations_t} ".
				"where educationName ='$education[educationName]'");
		
		$item = $params['courseData'];
		$instId = $item['institutionId'];
		$code = $item['courseCode'];
		
		unset($item['courseId']);
		unset($item['courseCode']);
		unset($item['institutionId']);
		unset($item['educationId']);
		
		$item['table'] = $courses_t;
		$num = $db->update($item, "educationId='$education[educationId]'".
			" and institutionId='${instId}' and courseCode='${code}'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
	function deleteEducation($params){
		$db = $this->db;
		$educations_t = $this->educations_t;
		$courses_t = $this->courses_t;
		
		$education = $params['educationData'];
		$education = $db->getOne("select * from ${educations_t} ".
				"where educationName ='$education[educationName]'");
		
		$c = $db->getOne("select count(*) as cnt from $courses_t".
							" where educationId='$education[educationId]'");
		
		if($c['cnt']>0){
			throw new Exception("EDU_HAS_COURSES");
		}
		
		$num = $db->delete($educations_t, 
			"educationId='$education[educationId]'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
	function deleteCourse($params){
		$db = $this->db;
		$courses_t = $this->courses_t;
		
		$inst = $params['instData'];
		$course = $params['courseData'];
		
		$num = $db->delete($courses_t, 
			"courseCode='$course[courseCode]' ".
			" and institutionId='$inst[instEmail]'");
		
		return array('OK'=>true,'num'=>$num);
	}
	
}
?>