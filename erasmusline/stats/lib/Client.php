<?php 
require_once("lib/System/Daemon.php");
class Client{
	
	private $socket;
	
	function __construct($options){
		
		if($options['sockType'] == 'unix'){
			$this->socket = stream_socket_client("unix://$options[sockFile]");
		}else if($options['sockType'] == 'tcp'){
			$this->socket = stream_socket_client("tcp://$options[serverIP]".
													":$options[serverPort]");
		}
		
	}
	
	function sendMessage(&$msg,&$response){
		
		$len = strlen($msg);
		$send_data = pack('N', $len) . $msg; //Pack the length in a network-friendly way, then prepend it to the data.
		stream_socket_sendto($this->socket,$send_data);
		
		// receive response message
		$packed_len = stream_get_contents($this->socket, 4); //The first 4 bytes contain our N-packed length
		$hdr = unpack('Nlen', $packed_len);
		$len = $hdr['len'];
		$response = stream_get_contents($this->socket, $len);
	}
	
}

?>