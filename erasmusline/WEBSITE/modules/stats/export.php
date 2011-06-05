<?php 
error_reporting(0);
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}..${sep}"));
require_once('library/eis/StatsCall.php');

function exportCSV($data){
	header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=download.csv');
	
	$first=true;
    foreach (array_keys($data[0]) as $item) {
    	$sep = $first ? '' : ',';
        echo $sep.'"'.$item.'"';
        $first=false;
    }
    
    echo "\r\n";

    foreach ($data as $row) {
		$first=true;
        foreach ($row as $item) {
        	$sep = $first ? '' : ',';
        	echo $sep.'"'.$item.'"';
        	$first=false;
	        
        }
        echo "\r\n";
    }

}

$call = new StatsCall();

$json = '{"jsonrpc":"2.0","id":'.
$call->nextId()
.',"method":"'.
$_POST['method']
.'","params":'.
$_POST['params']
.'}';


$call->makeCall($json,$msg);
$obj = json_decode($msg,true);

exportCSV($obj['result']);

?>