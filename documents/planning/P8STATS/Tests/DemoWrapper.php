#!/usr/bin/env php
<?php
//set_time_limit ( $seconds );
error_reporting(-1);
require_once 'lib/DB.php';
class DemoWrapper{
    
    function DemoWrapper(){
        
    }
    
    function run(){
        print "test";
    }
    
}
$obj = new DemoWrapper();
$obj->run();
?>