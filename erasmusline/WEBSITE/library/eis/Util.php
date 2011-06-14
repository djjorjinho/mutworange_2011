<?php
class Util{

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
	
	
}