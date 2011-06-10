<?php

class FormsController extends PlonkController {

    public function toDb($params) {

        $db = new FormsDB();
        if (isset($_REQUEST['json'])) {
            $obj = json_decode($params, true);
            //$obj = json_decode($objData['data'],true);

            $emailField = $obj['data']['emailField'];
            $table = $obj['table'];
            $data = $obj['data']['data'];

            $id = $data[$emailField];
            $record = $db->checkRecordExists($emailField, $id, $table);
            if (!empty($record) || $record[$id] != null) {

                $db->updateBelgium($table, $data, $emailField . ' = "' . $id . '"');

                echo "updated";
            } else {

                $db->insertBelgium($table, $data);
                echo "inserted";
            }
        }
    }

    public function insertInDb($params) {
        $db = new FormsDB();
        if (isset($_REQUEST['json'])) {
            $obj = json_decode($params, true);
            //$obj = json_decode($objData['data'],true);
            $table = $obj['table'];
            $data = $obj['data']['data'];

            $db->insertBelgium($table, $data);
            echo "inserted";
        }
    }

    public function saveFile($id, $fileName) {
        Plonk::dump('sdfsqdf');
        if (isset($_FILES['pic'])) {
            $uploaddir = '../../../files/' . $id;
            $uploadfile = "";

            $tmp_name = $_FILES["pic"]["tmp_name"][0];
            $name = $_FILES["pic"]["name"][0];

            if(!PlonkDirectory::exists($uploaddir)) {
            mkdir($uploaddir);
            }

            $uploadfile = $uploaddir . '/' . $fileName;
            echo $uploadfile;
            echo $_FILES['pic']['tmp_name'][0];

            $msg = move_uploaded_file($tmp_name, $uploadfile);
            echo $msg . '("';
        }
    }

}
