<?php
/**
 * 
 * EISMiner class for form workflow events
 * @author daniel
 *
 */
class EISMiner{
	
	private $instance;
	
	private $events = array(
		'new_form' => true,
		'approve_form' => true,
		'reject_form' => true,
	);
	
	private function __construct(){}
	
	public static function getInstance(){
		if(! isset($this->instance)){
			$this->instance = new EISMiner();
		}
		return $this->instance;
	}
	
	/**
	 * 
	 * Erasmusline application calls this method to register an event 
	 * and then gathering data no store in the ODS table. 
	 * @param str $event_name
	 * @param array $obj - associative array containing 
	 * 						the partial data to be stored in the ODS
	 * @throws Exception
	 */
	public function triggerEvent($event_name,$obj){
		if(!$this->events[$event_name]){
			throw new Exception("Invalid EISMiner event!");
		}
		
		if(! is_array($obj)){
			throw new Exception("Invalid EISMiner event payload!");
		}
		
		error_log("EISMiner::triggerEvent not implemented yet");
	}
	
}

?>