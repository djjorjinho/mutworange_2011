<?php
require_once("lib/System/Daemon.php");
class JsonRpcDispatcher{
	
	private $callback_obj;
	
	function __construct($obj){
		$this->callback_obj = $obj;
	}
	
	function dispatch(&$msg){
		
		try{
			
			#System_Daemon::info("Encoded Message: ".$msg);
			
			$json = json_decode($msg,true);
			
			#System_Daemon::info("Decoded Message: ".print_r($json,true));
			
			if($json==null){
				return $this->jsonError("NOT_JSON_MSG");
			}
			
			$runnable = array($this->callback_obj,$json['method']);
			if(is_callable($runnable)){
				$result = call_user_func_array($runnable,array($json['params']));
			}
			
			#System_Daemon::info("Result Message: ".print_r($result,true));
			
			if(!isset($result) or $result == false){
				# false fails, if you need to return false,
				# wrap it in a json object
				
				return $this->jsonError("FUNC_NOT_AVAILABLE");
				
			}else{
				return $this->jsonResult($result);
			}
			
		}catch(Exception $e){
			System_Daemon::err("EXCEPTION",0,0,$e->getMessage());
			return $this->jsonError($e->getMessage);
		}
		
	}
	
	function jsonError($error,$code=0,$id=0,$exception=''){
		$obj = array(
			jsonrpc => '2.0',
			error => array(
						message => $error,
						code => $code,
						exception => $exception
					),
			id => $id
		);
		
		return json_encode($obj);
	}
	
	function jsonResult($result,$id=0){
		
		$obj = array(
			jsonrpc => '2.0',
			result => $result,
			id => $id
		);
		
		return json_encode($obj);
	}
	
}

?>