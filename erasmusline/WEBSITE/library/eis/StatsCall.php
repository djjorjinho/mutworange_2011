<?php
/**
 * 
 * Handles unix server calls to the local statsd deamon
 * @author daniel
 *
 */
require('JSONConfig.php');
class StatsCall{
	
	private $socket;
	private $sock_path;
	private static $id;
	private $config;
	
	function __construct(){
		
		$this->config =
			JSONConfig::load(dirname(__FILE__),'statscall_config');

		$this->sock_path = $this->config[$this->config['sockType'].'Addr'];
		$this->socket = stream_socket_client($this->sock_path);
		self::$id = 0;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param str $method - method name
	 * @param mixed $params - method parameters, 
	 * 				recommended you use assoc. arrays
	 * @param bool $direct - if true, returns json string without decoding
	 * @return mixed - the response value
	 */
	function call($method,$params=array(),$direct=false){
		$rpc = array(
			'jsonrpc' => '2.0',
			'id' => $this->nextId(),
			'method' => $method,
			'params' => $params
		);
		
		$this->directCall($rpc,$msg);

		
		if($direct) return $msg;
		
		// parse response message
		$rsp = json_decode($msg,true);
		
		if($rsp == null ){
			throw new Exception("NOT_JSON_MSG");
		}
		
		if(array_key_exists('error',$rsp)){
			$error = $rsp['error'];
			throw new Exception($error[message],$error['code']);
		}
		
		
		return $rsp['result'];
	}
	
	function directCall($rpc,&$msg){
		// send message
		$json = json_encode($rpc);
		
		$this->makeCall($json,$msg);
	}
	
	function makeCall(&$json,&$msg){
		stream_socket_sendto($this->socket,$json);
		
		$msg="";
		// receive response message
		#while((
		$buff = stream_socket_recvfrom($this->socket,4096);
		#) != ""){
			$msg .= $buff;
		#}

	}
	
	function nextId(){
		return ++self::$id;
	}
}

?>