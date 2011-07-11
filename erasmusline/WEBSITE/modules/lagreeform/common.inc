<?php 


if(file_exists("../../core/includes/config.inc.php")){
			require_once "../../core/includes/config.inc.php";
}else{
			require_once "../../core/includes/config.php";	
}

$connection = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("please contact support@exploremyblog.com!");
mysql_select_db(DB_NAME, $connection);
?>
