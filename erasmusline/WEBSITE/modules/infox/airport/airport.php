<?php

include('db.class.php');

$path = "./upload/";

$db = new DB();

if (isset($_REQUEST['json'])) {
  $mail = null;
  $update = null;
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
		  if ($obj->table == "users" && $key == "email") {
		    $mail = $db->checkUserExist($value);
			if ($mail != null)
			  continue;
		  }
		  $field .= "$key";
		  $row .= "'" . $value . "'";
		}
		
		if ($mail == null) {
			$db->insertData($field, $row);
		} else {
			$update = $db->updateUser($field, $row,$mail);
		}
	  }
    } else {
        $field = null;		//Variable for the fieldnames which will be written to db
		$row = null;			//Variable for the fieldvalues which will be written to db
        foreach($obj->data as $key => $value) {
		  if (!empty($row)) {
		    $field .= ",";
		    $row .= ",";
	      }
		  if ($obj->table == "users" && $key == "email") {
		    $mail = $db->checkUserExist($value);
			if ($mail != null)
			  continue;
		  }
		  $field .= "$key";
		  $row .= "'" . $value . "'";
		}
		if ($mail == null) {
			$db->insertData($field, $row);
		} else {
			$update = $db->updateUser($field, $row, $mail);
		}
	}
  }
  if ($db->checkInsert() || $update != null) {
    echo "Data Transfer succeed";
  } else {
    echo "Data Transfer failed";
  }
} else if(isset($_FILES['file'])) {
	if (!file_exists($path.$_FILES['file']['name'])) {
		move_uploaded_file($_FILES['file']['tmp_name'], $path.$_FILES['file']['name']);
		if (file_exists($path.$_FILES['file']['name'])) {
			echo "File successfully transferred.";
		} else {
			echo "File transfer failed.";
		}
	} else {
		echo "File already exist at Host University";
	}
}

?>