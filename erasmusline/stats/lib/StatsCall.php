<?php
/**
 * 
 * Handles unix server calls to the local statsd deamon
 * @author daniel
 *
 */
class StatsCall{
	
	private $socket;
	private $sock_path;
	private static $id;
	
	function __construct(){
		$daemon = "statsd_slave";
		$this->sock_path = "unix:///tmp/erasmusline/${daemon}/${daemon}.sock";
		$this->socket = stream_socket_client($this->sock_path);
		self::$id = 0;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param str $method - method name
	 * @param mixed $params - method parameters, 
	 * 				recommended you use assoc. arrays
	 * @return mixed - the response value
	 */
	function call($method,$params=array()){
		$rpc = array(
			'jsonrpc' => '2.0',
			'id' => ++self::$id,
			'method' => $method,
			'params' => $params
		);
		
		$this->directCall($rpc,$msg);
		
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
		stream_socket_sendto($this->socket,$json);
		
		// receive response message
		$msg = stream_socket_recvfrom($this->socket,1500);
	}
	
}

?>