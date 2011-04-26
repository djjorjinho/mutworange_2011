<?php

/**
 * Plonk - Plonk PHP Library
 * CSV Class - class to working with CSV files
 *  
 * @package		Plonk
 * @subpackage	filesystem
 * @author		Bramus Van Damme <bramus.vandamme@kahosl.be>
 */

// load dependencies
require_once 'plonk/filesystem/file.php';

class PlonkCsv
{
    
	/**
	 * Path to the CSV file
	 * @var string
	 */
    private $path;
	
	
	/**
	 * Separator used in the CSV file
	 * @var string
	 */
    private $separator = ";";
	
	
	/**
	 * The version of this class
	 */
	const version = 1.0;
	
	
	/**
	 * Constructor
	 * @param string $path
	 * @param string $separator [optional]
	 * @param bool $createFileIfNotExists [optional]
	 * @return 
	 */
	public function __construct($path, $separator = ';', $createFileIfNotExists = false)
	{
		
		// store vars
		$this->path 		= (string) $path;
		$this->separator 	= (string) $separator;
		
		// check if the file exists. If it doesn't create it (if allowed) or throw an Eception
		if (!PlonkFile::exists($path))
		{
		
			// we can create the file: do it!
			if ($createFileIfNotExists === true)
			{
				PlonkFile::create($path);
			} 
			
			// we can't create the file: throw an exception!
			else {
				throw new Exception('Cannot open ' . $path .' as a CSV file: it does not exist. Set the $createFileIfNotExists flag to true if you wish to create it');
			}
			
		}
		
	}
	
	
	/**
	 * Adds a row to the CSV file
	 * @param mixed $val
	 * @param int $index [optional]
	 * @return bool
	 */
	public function addRow($val, $index = -1) 
	{
		
		// redefine vars
        $val	= (is_array($val)?implode($this->separator, $val):$val);
		$index 	= (int) $index;
		
		// get the file contents, as an array
		try {
			$rows = PlonkFile::getContents($this->path, 'array');
		} catch (Exception $e) { throw $e; }
		
		// index is -1: add at the end
        if ($index === -1) $rows[] .= $val;
		
		// index is not -1: insert at position given
        if ($index !== -1) {
            $rows = array_merge(array_slice($rows, 0, $index), (array) $val, array_slice($rows, $index));
        }
		
		// save the new contents
		try {
			return $this->setContents($rows);
		} catch (Exception $e) { throw $e; }
    }


	/**
	 * Gets the number of rows
	 * @return int
	 */
	public function numRows() 
	{
		
		// get the file contents, as an array
		try {
			$rows = PlonkFile::getContents($this->path, 'array');
		} catch (Exception $e) { throw $e; }
		
		// return rowcount
		return sizeof($rows);
		
	}
    
	
	/**
	 * Checks whether a row exists in a CSV file or not. Returns true upon existance.
	 * @param string 	$valueToCheckAgainst
	 * @param int 		$columnNumber [optional] The position where $valueToCheckAgainst can be found "inside" a row
	 * @return bool
	 */
    public function rowExists($valueToCheckAgainst, $columnNumber = 0) 
	{
    	
		// redefine vars
		$valueToCheckAgainst 	= (string) $valueToCheckAgainst; 
		$columnNumber 			= (int) $columnNumber;
		
		// get the file contents, as an array
		try {
			$rows = PlonkFile::getContents($this->path, 'array');
		} catch (Exception $e) { throw $e; }
		
		// loop all rows and extract the 'columns'
		foreach ($rows as $row) {
		
			$cells = explode($this->separator, $row);
			
			// check the current 'cell' against the $valueToCheckAgainst
			if ((string) $cells[$columnNumber] === (string) $valueToCheckAgainst) return true;
			
		}
		
		// nothing found
		return false;
		
    }
    
 
 	/**
 	 * Removes a row at the given index
 	 * @param int $index
 	 * @return bool
 	 */
    public function removeRow($index) 
	{
    	
		// redefine vars
		$index	= (int) $index;
		
		// get the file contents, as an array
		try {
			$rows = PlonkFile::getContents($this->path, 'array');
		} catch (Exception $e) { throw $e; }
		
		// invalid $index value
		if ($index > (sizeof($rows) - 1)) throw new Exception('Cannot remove row at index ' . $index . ' from CSV file: The index is invalid (The CSV file contains ' . sizeof($rows) . ' row(s))');
		
		// remove row via splice function		
		array_splice($rows, $index, 1);
		
		// save the new contents
		try {
			return $this->setContents($rows);
		} catch (Exception $e) { throw $e; }
		
    }
	
	
	/**
	 * Saves the content of the CSV file.
	 * @param mixed $content The content to save. May be an array or a string.
	 * @return bool
	 */
	private function setContents($content)
	{
		
		// redefine vars
		if (is_array($content))
		{
			$content = implode("\n", $content);
		} else {
			$content = (string) $content;
		}
		
		// cleanup content
        $content = preg_replace("/\n+/", "\n", $content);
		
		// call PlonkFile to store the contents
		try {
			return PlonkFile::setContents($this->path, $content);
		} catch (Exception $e) { throw $e; }
		
	}
	
	
	/**
	 * Returns the version of this class
	 * @return double
	 */
	public static function version() 
	{
		return (float) self::version;
	}
	
	
}

//EOF