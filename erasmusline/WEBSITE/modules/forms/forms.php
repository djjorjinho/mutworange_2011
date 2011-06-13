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
            if (!empty($record)) {

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

    public function saveFile($params, $folder) {

        $uploaddir = 'files/' . $folder;
        $uploadfile = '';

        $tmp_name = $_FILES["file"]["tmp_name"];
        $fileName = $_FILES["file"]["name"];

        if (!PlonkDirectory::exists($uploaddir)) {
            mkdir($uploaddir);
        }

        $uploadfile = $uploaddir . '/' . $fileName;

        $msg = move_uploaded_file($tmp_name, $uploadfile);
    }

}
