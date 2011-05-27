<?php

include('db.class.php');
$id =  $_REQUEST['id'];
$db = new DB();
if (isset($_REQUEST['json'])) {
    $obj = json_decode($_REQUEST['json'], true);

    $emailField = $obj['emailField'];
    $table = $obj['table'];
    $data = $obj['data'];
    $id = $data[$emailField];
    $record = $db->checkRecordExists($emailField, $id, $table);
    if (!empty($record) || $record[$id] != null) {
        $db->updateBelgium($table, $data, $emailField, $id);
        echo "updated";
    } else {

        $db->insertBelgium($table, $data);
        echo "inserted";
    }
} else if (isset($_FILES['file'])) {
    $uploaddir = '../../../files/'.$id;
    $uploadfile = "";

    $tmp_name = $_FILES["file"]["tmp_name"];
    $name = $_FILES["file"]["name"];
    
    mkdir($uploaddir);

    $uploadfile = $uploaddir .'/'. basename($name);
    echo $uploadfile;
    echo $_FILES['file']['tmp_name'];

    $msg = move_uploaded_file($tmp_name, $uploadfile);
    echo $msg . '("';
}
?>
