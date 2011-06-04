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
		$len = strlen($json);
		$send_data = pack('N', $len) . $json; //Pack the length in a network-friendly way, then prepend it to the data.
		stream_socket_sendto($this->socket,$send_data);
		
		$packed_len = stream_get_contents($this->socket, 4); //The first 4 bytes contain our N-packed length
		$hdr = unpack('Nlen', $packed_len);
		$len = $hdr['len'];
		$msg = stream_get_contents($this->socket, $len);

	}
	
	function nextId(){
		return ++self::$id;
	}
}

?>