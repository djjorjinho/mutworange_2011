<?php
require_once("lib/UnixSocketSource.php");
require_once("lib/TCPSocketSource.php");
/**
 * Simple Server class that implements Unix and TCP server sockets.
 * Uses LooPHP with threading for event loop activation.
 *
 */
class Server{

	private $tcpLoop;
	private $unixLoop;
	private $config;
	
	function __construct($options){
		$this->config = $options;
		
		$tcpSock = new TCPSocketSource($options,$this);
		$this->tcpLoop = new LooPHP_EventLoop( $tcpSock );
		
		if(! preg_match("/WIN/", PHP_OS)){
			$unixSock = new UnixSocketSource($options,$this);
			$this->unixLoop = new LooPHP_EventLoop( $unixSock );
		}
		
		$this->tcpLoop->run(true);
		
		if(isset($this->unixLoop)) $this->unixLoop->run();
	}
	
    function onMessage(&$message,&$response,$event_loop){

		System_Daemon::info("Event Triggered ");

		$response = "HTTP/1.0 200 OK\n".
					"Content-Type: text/html\n".
					"Server: Erasmusline EIS"."\r\n\r\n".
					"<body>Hello World</body>";
    }
	
	function getIP() {
		/*
		$ip = getenv('HTTP_CLIENT_IP');
		if(isset($ip) && $ip != 'unknown') return $ip;
		
		$ip = getenv('HTTP_X_FORWARDED_FOR');
		if(isset($ip) && $ip != 'unknown') return $ip;
		
		$ip = getenv('REMOTE_ADDR');
		if(isset($ip) && $ip != 'unknown') return $ip;
		
		$ip = isset($_SERVER['REMOTE_ADDR']) ?
					$_SERVER['REMOTE_ADDR'] : 'unknown';
		*/
		preg_match("/Current IP Address: ([0-9.]*)/", 
			`curl http://checkip.dyndns.org/`,$matches);
		
		$ip = $matches[1];		
		return $ip;
	}
	
}

?>