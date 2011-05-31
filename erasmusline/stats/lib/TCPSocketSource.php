<?php

require_once( "LooPHP/Autoload.php" );
require_once("System/Daemon.php");

class TCPSocketSource extends LooPHP_EventSource{
	
	private $_listen_socket;
	private $_socket_array = array();
	private $options;
	private $callback_obj;
	
	function __construct($options,$callback_obj){
		$this->options = $options['serverconfig'];
		
		$this->callback_obj = $callback_obj;
		
		# not blocking unix socket
		$this->_listen_socket =
			stream_socket_server("tcp://".$this->options['serverIP'].
								 ":".$this->options['serverPort']);
			
		if(!stream_set_blocking($this->_listen_socket,0))
			System_Daemon::emerg("TCP Socket is Blocking!");
		
		if( ! $this->_listen_socket ) throw new Exception( "failed to bind to port" );
		$this->_socket_array[(int)$this->_listen_socket] = $this->_listen_socket;
	}
	
	function __destruct(){
		stream_socket_shutdown($this->_listen_socket);
	}
	
	public function process( LooPHP_EventLoop $event_loop, $timeout )
	{
		$read_resource_array = $this->_socket_array;
		$write_resource_array = NULL;
		$exception_resource_array = $this->_socket_array;
		$results = stream_select(
			$read_resource_array,
			$write_resource_array,
			$exception_resource_array,
			is_null( $timeout ) ? NULL : floor( $timeout ),
			is_null( $timeout ) ? NULL : fmod( $timeout, 1 )*1000000
		);
		if( $results === FALSE ) {
			throw new Exception( "stream_select failed" );
		} else if( $results > 0 ) {
			foreach( $read_resource_array as $read_resource ) {
				if( $this->_listen_socket === $read_resource ) {
					$client_resource = @stream_socket_accept( $this->_listen_socket );
					$this->_socket_array[(int)$client_resource] = $client_resource;
				} else {
					$obj = $this->callback_obj;
					$event_loop->addEvent( function() use ( $read_resource,$obj, $event_loop) {
						
						$msg="";
						// receive response message
						#while((
						$buff = 
							stream_socket_recvfrom($read_resource,4096);
						#) != ""){
								
							$msg .= $buff;
						#}
	
						$obj->onMessage($msg,$send_data,$event_loop);
						
						stream_socket_sendto( $read_resource, $send_data );
						fclose( $read_resource );
					}, 0 );
					unset( $this->_socket_array[(int)$read_resource] );
				}
			}
			foreach( $exception_resource_array as $exception_resource ) {
				unset( $this->_socket_array[(int)$exception_resource] );
			}
		}
		return TRUE;
	}
	
}

?>
