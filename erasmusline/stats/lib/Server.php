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
	
	function __construct($options){

		$tcpSock = new TCPSocketSource($options,$this);
		$this->tcpLoop = new LooPHP_EventLoop( $tcpSock );
		
		$unixSock = new UnixSocketSource($options,$this);
		$this->unixLoop = new LooPHP_EventLoop( $unixSock );
		
		$this->tcpLoop->run();
		$this->unixLoop->run();
	}
	
    function onMessage(&$message,&$response,$event_loop){

		System_Daemon::info("Event Triggered ");

		$response = "HTTP/1.0 200 OK\n".
					"Content-Type: text/html\n".
					"Server: Erasmusline EIS"."\r\n\r\n".
					"<body>Hello World</body>";
    }
	
	
}

?>