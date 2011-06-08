<?php

class FormsController extends PlonkController {

    public function toDb($params) {

        $db = new FormsDB();
        if (isset($_REQUEST['json'])) {
            $obj = json_decode($params,true);
            echo $obj['data'];
            //$obj = json_decode($objData['data'],true);

            $emailField = $obj['data']['emailField'];
            $table = $obj['table'];
            $data = $obj['data']['data'];
            
            $id = $data[$emailField];
            $record = $db->checkRecordExists($emailField, $id, $table);
            if (!empty($record) || $record[$id] != null) {
                $db->updateBelgium($table, $data, $emailField.' = "'.$id.'"');
                echo "updated";
            } else {

                $db->insertBelgium($table, $data);
                echo "inserted";
            }
        }
    }

}
