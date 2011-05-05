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
	
	function sendMessage(&$message,&$response){
		
		stream_socket_sendto($this->socket,$message);
		$response = stream_socket_recvfrom($this->socket,1500);

	}
	
}

?>