<?php
class Crypt{
	
	private $key="can't be TOO large of a password. just substring it!";
	private $iv='11845370';# block size must be 8
	
    function __construct(){      
        // prepare encryption
        $this->key = substr($this->key,0,24);
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
	function encrypt($string) {
		$key = $this->key;
    	$enc = "";
    	$iv = $this->iv;
    	$enc=mcrypt_cbc(MCRYPT_TripleDES, $key, $string, MCRYPT_ENCRYPT,$iv);
		
  		return $this->urlsafe_b64encode($enc);
	}

	// 3DES Decrypting 
	function decrypt($string) {
		$key = $this->key;
    	$dec = "";
    	$string = trim($this->urlsafe_b64decode($string));
    	$iv = $this->iv;
    	$dec = mcrypt_cbc(MCRYPT_TripleDES, $key, $string, MCRYPT_DECRYPT,$iv);
    	$dec = rtrim($dec, "\0");
  		return $dec;
	}
    
}
?>