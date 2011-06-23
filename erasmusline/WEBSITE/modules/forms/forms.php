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
	
	public function addToDb($params) {
        $edb = new FormsDB();
        if (isset($_REQUEST['json'])) {
            $obj = json_decode($params, true);
            
			$table = $obj['table'];
			$data = array(
				'erasmusId' => (int)$obj['data']['erasmusId'],
				'courseId' => (int)$obj['data']['courseId']
				);

            $edb->insertBelgium($table, $data);
            
        }
    }
	
	public function updateExamsToDb($params) {
        $edb = new FormsDB();
        if (isset($_REQUEST['json'])) {
            $obj = json_decode($params, true);
            
			$table = $obj['table'];
			$data =	$obj['data'];
			
            $edb->updateBelgium($table, array('isRequested' => '1'), "`coursespereducperinst`.`courseName` = \"" . $data . "\" 
												AND `homecoursestoerasmus`.`courseId` = `coursespereducperinst`.`courseId`");          
        }
    }
	
	public function updateToDb($params) {
		
        $edb = PlonkWebsite::getDB();
        if (isset($_REQUEST['json'])) {
            $obj = json_decode($params, true);
            
			$table = $obj['table'];
			$data = array(
				'set' => $obj['data']['set'],
				'where' => $obj['data']['where']
				);
			
            $edb->execute("UPDATE " . $table . " SET " . $data['set'] . " WHERE " . $data['where']);          
        }
    }

}
