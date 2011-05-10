<?php

include('db.class.php');

$db = new DB();

if (isset($_REQUEST['json'])) {
  $json = str_replace("'",'"',$_REQUEST['json']);
  $obj = json_decode($json);

  if ($db->checkTable($obj->table)) {
    if (is_array($obj->data)) {
	  for ($i = 0; $i < count($obj->data);$i++) {
        $field = null;		//Variable for the fieldnames which will be written to db
		$row = null;			//Variable for the fieldvalues which will be written to db
		$id = false;
        foreach($obj->data[$i] as $key => $value) {
		  if ($id == false) {
		    $id = true;
			continue;
		  }
		  if (!empty($row)) {
		    $field .= ",";
		    $row .= ",";
	      }
		  $field .= "$key";
		  $row .= "'" . $value . "'";
		}
		$db->insertData($field, $row);
	  }
    } else {
        $field = null;		//Variable for the fieldnames which will be written to db
		$row = null;			//Variable for the fieldvalues which will be written to db
        foreach($obj->data[$i] as $key => $value) {
		  if (!empty($row)) {
		    $field .= ",";
		    $row .= ",";
	      }
		  $field .= "$key";
		  $row .= "'" . $value . "'";
		}
		$db->insertData($field, $row);
    }
  }
  if ($db->checkInsert()) {
    echo "Data Transfer succeed";
  } else {
    echo "Data Transfer failed";
  }
}

?>