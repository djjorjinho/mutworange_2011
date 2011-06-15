<?php
class Util{
	static $debug = true;
	
	public static function loadController($module){
		// check if the controller file exists
  		if (!file_exists(PATH_MODULES . '/' . $module . '/'.strtolower($module).'.php'))
            throw new Exception('Cannot initialize website: module "' . $module . '" does not exist');
  		// include the controller
  	  	require_once(PATH_MODULES . '/' . $module . '/'.strtolower($module).'.php');
  		// include the DB
  	  	require_once(PATH_MODULES . '/' . $module . '/'.strtolower($module).'.db.php');		
  		// build name of the class 
  	  	$controller = ucfirst($module).'Controller';
  		// return new instance of the controller
  	
      	return new $controller();
	}

	public static function getPartnership(){
		return self::loadController('partnership');
	}
	
	public static function log($message){
    	if(!self::$debug) return;
    	
    	$sep = DIRECTORY_SEPARATOR;
    	$path = realpath(dirname(__FILE__)."${sep}..${sep}..")."${sep}error.log";
    	//throw new Exception("PATH: ".$path);
    	if(!file_exists($path)){
    		touch($path);
    	}
    	
    	$level=1;
    	$trace = debug_backtrace();
    	$file   = basename($trace[$level]['file']);
    	$line   = $trace[$level]['line'];
    	$where = " ${file}:${line} ";
		$dt = new DateTime();
    	$ts = '['.$dt->format("Y-m-d H:i:s").'] ';
    	error_log($ts.$where.$message."\n",3,$path);
    }
    
}