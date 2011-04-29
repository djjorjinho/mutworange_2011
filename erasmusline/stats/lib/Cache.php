<?php
require_once("lib/TSample.php");
class ObjectCache{
	
	private $cache;
	private $ttl;
	private $rnd;
	
	function __construct(){
		$this->cache = array();
		$this->rnd = new TSample();
		$this->ttl = 180;
	}
	
	function get($key){
		$time = mktime();
		$arr =  $this->cache[$key];
		
		if(! is_array($arr)){
			return null;
		}
		
		if($arr[0] <= $time){
			unset($this->cache[$key]);
			return null;
		}
		
		return $arr[1];
	}
	
	/**
	 * 
	 * Stores object, assigns an expire time.
	 * @param str $key - object id key
	 * @param mixed $obj - the object to be stored
	 * @param int $ttl - the object ttl
	 */
	function store($key,$obj,$ttl=0){
		$ttl = ($ttl==0 ? $this->ttl : $ttl);
		$ttl += $this->rnd->range(-10,10);
		$timeout = $ttl + mktime();
		
		$this->cache[$key] = array($timeout,$obj);
	}
	
	/**
	 * 
	 * Deletes expired Objects
	 */
	function flushExpired(){
		$time = mktime();
		foreach($this->cache as $key => $arr){
			if($this->cache[$key][0] <= $time){
				unset($this->cache[$key]);
			}
		}
	}
	
	function flushCache(){
		$this->cache = array();
	}
	
}

?>