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
    protected $views = array('partnership','receive');
    
    public function __construct(){
    	$this->crypt = new Crypt();
    	$this->odb = new ODB();
    }
    
    public static function log($message){
    	return;
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
        self::log("result message: ".$result);
        $message = json_decode($this->crypt->decrypt($result),true);
        self::log("result message: ".$message);
        
        return $message;
    }
    
    function showReceive(){
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
    	ob_start();
    	header('Content-Type: text/plain');
    	echo $encrypted;
    	flush();
    	ob_flush(); 
    	exit(0);
    }
    
    function ping($params){
    	return array("Hi"=>$params['hello']);
    }
    
    function showPartnership(){
    	header('Content-Type: text/plain');
    	$res = $this->send("xpto","loopback:ping",array(
    					"hello" => "User!"
    				));
    	
    	ob_start();
    	echo print_r($res,true);
    	flush();
    	ob_flush(); 
    	exit(0);
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
	
	
    
}
?>