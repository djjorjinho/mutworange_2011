<?php 
error_reporting(0);
header('Content-type: application/json');

$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}..${sep}"));
require_once('library/eis/StatsCall.php');

$call = new StatsCall();

$json = '{"jsonrpc":"2.0","id":'.
$call->nextId()
.',"method":"'.
$_POST['method']
.'","params":'.
$_POST['params']
.'}';


$call->makeCall($json,$msg);

echo $msg;

?>

