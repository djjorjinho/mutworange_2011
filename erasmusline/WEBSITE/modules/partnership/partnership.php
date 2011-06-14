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
    protected $views = array('receive');
    
    public function __construct(){
   	
    	$this->crypt = new Crypt();
    	$this->odb = new ODB();
    }
    
    public function send($intitutionId,$method,$params){
    	
    	if(!PlonkSession::exists('id')){
    		throw new Exception("Invalid user!");
    	}
    	
    	if(!isset($intitutionId) || empty($intitutionId)){
    		throw new Exception("Invalid Institution!");
    	}
    	
    	if(preg_match(":", $method)<1){
    		throw new Exception("Invalid method invocation! ".
    							"must be \$module:\$methodname");
    	}
    	
    	if(!is_array($params)){
    		throw new Exception("Invalid Parameters! must be assoc. array!");
    	}
    	
    	$encrypted = $this->encrypt(
    						json_encode(
    							array('method' => $method,
    							'params' => $params)));
    	
    	$url = (preg_match("/^loopback/",$method)>0) ? "http://127.0.0.1" : 
    								PartnershipDB::getURL($intitutionId);
    							
    	curl::start();
        curl::setOption(CURLOPT_URL, $url.
        			"/index.php?module=partnership&view=receive");
        curl::setOption(CURLOPT_POST, 1);
        curl::setOption(CURLOPT_RETURNTRANSFER, true);
        curl::setOption(CURLOPT_POSTFIELDS, "payload=" . $encrypted);
        curl::execute();
        
        // json message
        $result = curl::getResult();
        $message = json_decode($this->crypt->decrypt($result),true);
        return $message;
    }
    
    function showReceive(){
    	$payload = PlonkFilter::getPostValue('payload');
    	if(empty($payload)){
    		throw new Exception('Invalid Infox payload!');
    	}
    	
    	$message = json_decode($this->crypt->decrypt($payload),true);
    	
    	if(!isset($message)){
    		throw new Exception("Invalid JSON message");
    	}
    	
    	list($module,$method) = preg_split(":", $message['method']);
    	
    	$obj = ($module=='partnership') ? 
    				$this : Util::loadController($module);
    	
    	$runnable = array($obj,$method);
    	
    	if(is_callable($runnable)){
    		throw new Exception('Invalid invocation');
    	}
    	
    	$result = call_user_func_array($runnable,array($message['params']));
    	$encrypted = $this->encrypt(
    						json_encode($result));
    	
    	print $encrypted;
    	
    	exit(0);
    }
    
    function ping($params){
    	return array("hello"=>"world!!!");
    }
    
}
?>