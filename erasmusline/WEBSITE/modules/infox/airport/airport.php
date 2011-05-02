<?php

include('db.class.php');

$db = new DB();

if (isset($_REQUEST['json'])) {

  $json = str_replace("'",'"',$_REQUEST['json']);
  $obj = json_decode($json);

  if ($db->checkTable($obj->table)) {
    foreach($obj->data as $key => $value) {
      echo $db->insertData($key, $value)."<br>";
    }
  }
  if ($db->checkInsert()) {
    echo "Data Transfer succeed";
  } else {
    echo "Data Transfer failed";
  }
}

?>