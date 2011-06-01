<?php
/**
 * 
 * Wrapper to load configuration files with a mechanism to search first for 
 * configuration files untracked by source code management software.
 * @author daniel
 *
 */
class JSONConfig{

	/**
	 * 
	 * Creates a new Config object if the config file is properly configured.
	 * Tries to find the optional untracked config file (with .inc.json), 
	 * if it doesn't exist, it defaults to the main config file.
	 * @param str $path - file path
	 * @param str $filename - file name without the extension
	 * @return ref var - the object itself
	 */
	public static function load($path,$filename){
		$filename = preg_replace("/\.json/","",$filename);
		$sep = DIRECTORY_SEPARATOR;
		$filepath = realpath($path."${sep}${filename}.json");
		$filepathInc = realpath($path."${sep}${filename}.inc.json");
		
		$path = file_exists($filepathInc) ? $filepathInc : $filepath;
		
		$json =	json_decode(file_get_contents($path),true);
		
		if(!isset($json)){
			throw new Exception("Invalid JSON config: $path");
		}
		
		return $json;
	}
	
}
?>