<?php
require_once("lib/System/Daemon.php");
/**
 * 
 * JSON-RPC message dispatcher class that parses the method and parameters
 * and calls back to a predefined object
 * 
 * @author daniel
 *
 */
class JsonRpcDispatcher{
	
	private $callback_obj;
	private $allowed_methods;
	
	/**
	 * 
	 * Constructor sets the callback object
	 * @param object object to issue callbacks
	 */
	function __construct($obj){
		$this->callback_obj = $obj;
		$this->allowed_methods = $obj->rpcMethods();
	}
	
	/**
	 * 
	 * De-Serializes the rpc message and issues a callback to the 
	 * defined object
	 * @param str string reference with the JSON-RPC message
	 */
	function dispatch(&$msg){
		
		try{
			
			System_Daemon::debug("Encoded Message: ".$msg);
			
			$json = json_decode($msg,true);
			
			System_Daemon::debug("Decoded Message: ".print_r($json,true));
			#error_log("Decoded Message: ".print_r($json,true));
			
			if($json==null){
				return $this->jsonError("NOT_JSON_MSG");
			}
			
			$method = $json['method'];
			#error_log($method);
			
			#error_log("Methods: ".print_r($this->allowed_methods,true));
			
			if(!$this->allowed_methods[$method]){
				return $this->jsonError("NO_METHOD",0,$json['id']);
			}
			
			$runnable = array($this->callback_obj,$method);
			if(is_callable($runnable)){
				$result = call_user_func_array($runnable,array($json['params']));
			}
			
			System_Daemon::debug("Result Message: ".print_r($result,true));
			
			if(!isset($result) or $result == false){
				# false fails, if you need to return false,
				# wrap it in a json object
				
				return $this->jsonError("FUNC_NOT_AVAILABLE",0,$json['id']);
				
			}else{
				return $this->jsonResult($result,$json['id']);
			}
			
		}catch(Exception $e){
			System_Daemon::err("EXCEPTION:\n".$e->getMessage());
			return $this->jsonError($e->getMessage);
		}
		
	}
	
	/**
	 * 
	 * Generates a JSON-RPC error message
	 * @param string error message
	 * @param string error code
	 * @param int id number to match incomming json-rpc message (optional)
	 * @param string exception message if you catch it
	 */
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
	
	/**
	 * 
	 * Generates a JSON-RPC result message
	 * @param mixed result variable, can be array, string, etc.
	 * @param int id number to match incomming json-rpc message (optional)
	 */
	function jsonResult($result,$id=0){
		
		$obj = array(
			jsonrpc => '2.0',
			result => $result,
			id => $id
		);
		
		return json_encode($obj);
	}
	
}

interface JsonRpcI{
	function rpcMethods();
}

?>