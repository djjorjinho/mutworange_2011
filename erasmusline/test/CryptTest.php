#!/usr/bin/env php
<?php
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}WEBSITE${sep}modules"));
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}WEBSITE${sep}library"));
require_once 'PHPUnit.php';
class CryptTest extends PHPUnit_TestCase {
	
	private $key="can't be TOO large of a password. just substring it!";
	private $iv='11845370';# block size must be 8
	
    function CryptTest($name){
        $this->PHPUnit_TestCase($name);
        
        // prepare encryption
        $this->key = substr($this->key,0,24);
    }
    
	function alt_mcrypt_create_iv($size){
	    $iv = '';
	    for($i = 0; $i < $size; $i++) {
	        $iv .= chr(rand(0,255));
	    }
	    return $iv;
	}
	
	function urlsafe_b64encode($string){
  		$data = base64_encode($string);
  		$data = str_replace(array('+','/','='),array('-','_','.'),$data);
  		return $data;
	}

	function urlsafe_b64decode($string){
  		$data = str_replace(array('-','_','.'),array('+','/','='),$string);
  		$mod4 = strlen($data) % 4;
  		if ($mod4) {
    		$data .= substr('====', $mod4);
  		}
  		return base64_decode($data);
	}
	
	// 3DES Encrypting
	function encrypt($string, $key) {
    	$enc = "";
    	$iv = $this->iv;
    	$enc=mcrypt_cbc(MCRYPT_TripleDES, $key, $string, MCRYPT_ENCRYPT,$iv);
		
  		return $this->urlsafe_b64encode($enc);
	}

	// 3DES Decrypting 
	function decrypt($string, $key) {
    	$dec = "";
    	$string = trim($this->urlsafe_b64decode($string));
    	$iv = $this->iv;
    	$dec = mcrypt_cbc(MCRYPT_TripleDES, $key, $string, MCRYPT_DECRYPT,$iv);
    	$dec = rtrim($dec, "\0");
  		return $dec;
	}
	
 	function getString(){
 		
 		$arr = array(
 			"Olá" => "Mundo!",
 			"ÀÉÍóú" => true
 		);
 		
 		return json_encode($arr);
 	}
	
	function testCrypt(){
		$string = $this->getString();
		$key = $this->key;
		$crypted = $this->encrypt($string,$key);
		$decrypted = $this->decrypt($crypted,$key);
		
		$this->assertEquals($string,$decrypted);
	}
    
}
$suite = new PHPUnit_TestSuite('CryptTest');
$phpu = new PHPUnit();
$result = $phpu->run($suite);
print $result->toString();
?>