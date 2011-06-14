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
	private $odb;
	private $debug=false;
    protected $views = array('partnership','receive');
    
    public function __construct(){
    	$this->crypt = new Crypt();
    	$this->odb = new ODB();
    }
    
    public static function log($message){
    	if(!$this->debug) return;
    	$sep = DIRECTORY_SEPARATOR;
    	error_log($message."\n",3,dirname(__FILE__)."${sep}error.log");
    }
    
    public function send($intitutionId,$method,$params){
    	
    	if(!PlonkSession::exists('id')){
    		throw new Exception("Invalid user!");
    	}
    	
    	if(!isset($intitutionId) || empty($intitutionId)){
    		throw new Exception("Invalid Institution!");
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
    								PartnershipDB::getURL($intitutionId);
    	
    	$curl = new curl();
    								
    	$curl->start();
        $curl->setOption(CURLOPT_URL, $url.
        			"/index.php?module=partnership&view=receive");
        $curl->setOption(CURLOPT_POST, 1);
        $curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOption(CURLOPT_POSTFIELDS, "payload=" . $encrypted);
        $curl->execute();
        
        // json message
        $result = $curl->getResult();
        self::log("result response message: ".$result);
        $message = json_decode($this->crypt->decrypt($result),true);
        self::log("result message: ".$message);
        
        return $message;
    }
    
    function showReceive(){
    	$module=$method="";
    	try{
    		
	    	$payload = PlonkFilter::getPostValue('payload');
	    	if(empty($payload)){
	    		self::log("error Invalid Infox payload!");
	    		throw new Exception('Invalid Infox payload!');
	    	}
	    	
	    	$message = json_decode($this->crypt->decrypt($payload),true);
	    	
	    	if(!isset($message)){
	    		self::log("error Invalid JSON message");
	    		throw new Exception("Invalid JSON message");
	    	}
	    	
	    	self::log("incomming message: ".$message);
	    	
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
	    	
	    	self::log("encrypted: ".$encrypted);
	    	$this->output($encrypted);
	    	
    	}catch(Exception $e){
    		self::log("Exception: ".$e->getMessage());
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
    	$res = $this->send("xpto","loopback:lol",array(
    					"hello" => "User!"
    				));
    	$out = print_r($res,true);
    	$this->output($out);
    	
    }
    
    static function curDomainURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
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
	
	function newInstitution($params){}
	
	function newCourse($params){}
	
	function newEducation($params){}
	
	function updateInstitution($params){}
	
	function updateEducation($params){}
	
	function updateCourse($params){}
	
	function deleteEducation($params){}
	
	function deleteCourse($params){}
	
	
    
}
?>