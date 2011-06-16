<?php
error_reporting(E_ERROR);
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".realpath(dirname(__FILE__)."${sep}..${sep}..${sep}"));
require_once('library/curl.php');
require_once('library/eis/Util.php');
function parseArgs($argv){
    array_shift($argv); $o = array();
    foreach ($argv as $a){
        if (substr($a,0,2) == '--'){ $eq = strpos($a,'=');
            if ($eq !== false){ $o[substr($a,2,$eq-2)] = substr($a,$eq+1); }
            else { $k = substr($a,2); if (!isset($o[$k])){ $o[$k] = true; } } }
        else if (substr($a,0,1) == '-'){
            if (substr($a,2,1) == '='){ $o[substr($a,1,1)] = substr($a,3); }
            else { foreach (str_split(substr($a,1)) as $k){ if (!isset($o[$k])){ $o[$k] = true; } } } }
        else { $o[] = $a; } }
    return $o;
}

$args = parseArgs($argv);

Util::log("Args:".print_r($args,true));

$curl = new curl();    								
$curl->start();
$curl->setOption(CURLOPT_URL, $args['url']);
$curl->setOption(CURLOPT_POST, 1);
$curl->setOption(CURLOPT_RETURNTRANSFER, true);
$curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
$curl->setOption(CURLOPT_POSTFIELDS, "payload=" . $args['payload']);
$curl->execute();
?>
