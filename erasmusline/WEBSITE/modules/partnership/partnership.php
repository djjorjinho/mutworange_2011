<?php
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}..${sep}"));
require_once('library/eis/ODB.php');
require_once('library/eis/Crypt.php');
require_once('library/curl.php');
class PartnershipController extends PlonkController {
	private $crypt;
	private $odb;
    protected $views = array('receive');
    
    public function __construct(){
    	parent::__construct();
    	
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
    	
    	curl::start();
        curl::setOption(CURLOPT_URL, PartnershipDB::getURL($intitutionId). 
        			"/index.php?module=partnership&view=receive");
        curl::setOption(CURLOPT_POST, 1);
        curl::setOption(CURLOPT_RETURNTRANSFER, true);
        curl::setOption(CURLOPT_POSTFIELDS, "payload=" . $encrypted);
        curl::execute();
        
        
        $result = curl::getResult();
    }
    
    function showReceive(){
    	
    }
    
}
?>