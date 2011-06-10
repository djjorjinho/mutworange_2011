<?php

include('curl.php');
include('infox.db.php');

class InfoxController extends PlonkController {

    protected $views = array('infox', 'transfer', 'admin', 'airport');
    protected $actions = array('transfer');
    protected $debug = false;
    protected $debugMsg = "";
    protected $path = "./files/nathan.vanassche@kahosl.be/";

/*    public function showInfox() {
        if (PlonkFilter::getGetValue('debug') == 1) {
            $this->debug = true;
        }
// set JSON String
        if (PlonkFilter::getPostValue('table') != null) {
            if (PlonkFilter::getPostValue('admin') != null) {
                $_POST['data'] = array();
                for ($i = 0; $i < count($_POST['row']); $i++) {
                    array_push($_POST['data'], InfoxDB::getRowFromDB(PlonkFilter::getPostValue('table'), $_POST['row'][$i]));
                }
            }
            $array = array('table' => PlonkFilter::getPostValue('table'), 'data' => $_POST['data']);
            $json = json_encode($array);
            $json = str_replace('"', "'", $json);
//          $json = str_replace("''","'",$json);
            // if university is set
            if (PlonkFilter::getPostValue('idUni')) {
                PlonkSession::set('infoxJSON', $json);
                PlonkWebsite::redirect('index.php?module=infox&view=transfer&transfer=1&idUni=' . PlonkFilter::getPostValue('idUni'));
            }
        } else if (PlonkFilter::getPostValue('file')) {
            if (file_exists($this->path . PlonkFilter::getPostValue('file'))) {
                PlonkWebsite::redirect('index.php?module=infox&view=transfer&transfer=2&idUni=' . PlonkFilter::getPostValue('idUni') . "&file=" . PlonkFilter::getPostValue('file'));
            }
        } else if (PlonkSession::exists('infoxJSON')) {
            $json = PlonkSession::get('infoxJSON');
        } else
            PlonkWebsite::redirect('index.php');

        $this->mainTpl->assign('pageTitle', 'ERASMUS line');

        if (PlonkSession::exists('id')) {

            $this->mainTpl->assign('breadcrumb', 'Home ==> Infox');

// set Iteration with all universities
            $uni = InfoxDB::getUniversity();
            $this->pageTpl->setIteration('iUniversity');
            foreach ($uni as $key => $value) {
                $this->pageTpl->assignIteration('university', '<option value=' . $value['instId'] . '> ' . $value['instName'] . '</option>');
                $this->pageTpl->refillIteration('iUniversity');
            }
            $this->pageTpl->parseIteration('iUniversity');

            $this->pageTpl->assign('json', $json);

            if ($this->debug) {
                $this->debugMsg['JSON'] = $json;
            }

            if ($this->debug) {
                $msg = "# # # # # # DEBUG # # # # # #<br />" . $this->getDebug($this->debugMsg);
                $this->pageTpl->assign("debug", $msg);
            } else {
                $this->pageTpl->assign("debug", "");
            }
        }
    }
*/    
    public function showInfox() {
    }
    public function showAirport() {
        $da = 151;
        $m = 255;

        if (PlonkFilter::getPostValue('params')) {
            // Start decoding
              $c = explode("#", PlonkFilter::getPostValue('params'));
              $decoded = "";
              for ($i = 0; $i < count($c); $i++) {
               	$decoded .= InfoxDB::UTF8_CharEncode(InfoxDB::powmod($c[$i], $da, $m));
              }
              $obj = json_decode($decoded);
              $array = explode(":", $obj->method);
              $tmp = $this->loadController($array[0]);
              $tmp->$array[1]($_FILES);
             PlonkWebsite::redirect('http://127.0.0.1/mutw/modules/infox/layout/airport.html');     
        } else {

          if (PlonkFilter::getPostValue('json')) {
          // Start decoding
              $c = explode("#", PlonkFilter::getPostValue('json'));
              $decoded = "";
              for ($i = 0; $i < count($c); $i++) {
               	$decoded .= InfoxDB::UTF8_CharEncode(InfoxDB::powmod($c[$i], $da, $m));
              }
  
    
              $obj = json_decode($decoded);
              for ($i = 0; $i < count($obj); $i++) {
                  $array = explode(":", $obj[$i]->method);
                  $tmp = $this->loadController($array[0]);
              
                  $tmp->$array[1]($obj[$i]->params);
              }
          
             PlonkWebsite::redirect('http://127.0.0.1/mutw/modules/infox/layout/airport.html');     
          }
        }
    }
    
    private function loadController($module) {
		// check if the controller file exists
  		  if (!file_exists(PATH_MODULES . '/' . $module . '/'.strtolower($module).'.php'))
            throw new Exception('Cannot initialize website: module "' . $module . '" does not exist');
  	// include the controller
  	  	require_once(PATH_MODULES . '/' . $module . '/'.strtolower($module).'.php');
  	// include the DB
  	  	require_once(PATH_MODULES . '/' . $module . '/'.strtolower($module).'.db.php');		
  	// build name of the class 
  	  	$controller = ucfirst($module).'Controller';
  	// return new instance of the controller
  	
      	return new $controller();
    }
    
    public function test1($params) {
    echo $params;
    }
    public function test2($params) {
    echo $params;
    }
    public function filetest($params) {
    print_r($_FILES);
    }

/*
    public function showTransfer() {
        if (PlonkFilter::getGetValue('debug') == 1) {
            $this->debug = true;
        }
        if (PlonkFilter::getGetValue('transfer') == 1) {
            $this->transfer();
        } else if (PlonkFilter::getGetValue('transfer') == 2) {
            $this->fileTransfer();
        }
        if (curl::getHTTPCode() == 200) {
            $this->pageTpl->assign('success', curl::getResult());
        } else {
            $this->pageTpl->assign('success', "Error");
        }
        if ($this->debug) {
            $msg = "# # # # # # DEBUG # # # # # #<br />" . $this->getDebug($this->debugMsg);
            $this->pageTpl->assign("debug", $msg);
        } else {
            $this->pageTpl->assign("debug", "");
        }
    }

    public function showAdmin() {
        $this->mainTpl->assign('breadcrumb', 'Home ==> Infox ==> Admin');
        $tables = InfoxDB::getAllTables();
        $this->pageTpl->setIteration('iTables');
        foreach ($tables as $key => $value) {
            $this->pageTpl->assignIteration('tables', '<option value=' . $value['Tables_in_' . DB_NAME] . '> ' . $value['Tables_in_' . DB_NAME] . '</option>');
            $this->pageTpl->refillIteration('iTables');
        }
        $this->pageTpl->parseIteration('iTables');

        if (PlonkFilter::getPostValue('table')) {
            $this->pageTpl->assignOption('oTable');
            $this->pageTpl->assign('table', PlonkFilter::getPostValue('table'));
            $data = InfoxDB::getAllDataFromTable(PlonkFilter::getPostValue('table'));
            $this->pageTpl->setIteration('iData');
            if (!empty($data))
                foreach ($data as $key => $value) {
                    $str = $value[1];
                    for ($i = 1; $i < count($value); $i++) {
                        $str .= " | " . $value[$i];
                    }
                    $this->pageTpl->assignIteration('data', '<input type="checkbox" name="row[]" value=' . $value[0] . ' /> ' . $str . '<br />');
                    $this->pageTpl->refillIteration('iData');
                }
            $this->pageTpl->parseIteration('iData');
        }
        if ($this->debug) {
            $msg = "# # # # # # DEBUG # # # # # #<br />" . $this->getDebug($this->debugMsg);
            $this->pageTpl->assign("debug", $msg);
        } else {
            $this->pageTpl->assign("debug", "");
        }
    }

    public function doTransfer() {
        if (PlonkSession::exists('id')) {

            if (PlonkFilter::getPostValue('idUni') == 0) {
                PlonkSession::set('infoxJSON', PlonkFilter::getPostValue('json'));
                PlonkWebsite::redirect('index.php?module=infox');
            } else {
                if (PlonkSession::exists('infoxJSON')) {
                    $json = PlonkSession::get('infoxJSON');
                    PlonkSession::remove('infoxJSON');
                } else {
                    $json = PlonkFilter::getPostValue('json');
                }
                if (PlonkFilter::getPostValue('idUni')) {
                    $idUni = PlonkFilter::getPostValue('idUni');
                } else {
                    if (PlonkFilter::getGetValue('idUni'))
                        $idUni = PlonkFilter::getGetValue('idUni');
                }
                curl::start();
                curl::setOption(CURLOPT_URL, InfoxDB::getURL($idUni) . "/modules/infox/airport/airport.php");
                curl::setOption(CURLOPT_POST, 1);
                curl::setOption(CURLOPT_RETURNTRANSFER, true);
                curl::setOption(CURLOPT_USERPWD, InfoxDB::getAuthUsername() . ":" . InfoxDB::getAuthPwd());
                curl::setOption(CURLOPT_POSTFIELDS, "json=" . $json);
                curl::setOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl::execute();

                if ($this->debug) {
                    $this->debugMsg['curl'] = curl::getInfo();
                }
            }
        }
    }

    public function transfer() {
    $method = array('meth1', 'meth2');
    $table = array('tab1', 'tab2');
    $data = array(array('data11','data12','data13'),array('data21','data22','data23'));
    $this->dataTransfer($method, $table, $data, 4);
        if (PlonkSession::exists('id')) {
            if (PlonkFilter::getGetValue('idUni') == 0) {
                PlonkSession::set('infoxJSON', PlonkFilter::getPostValue('json'));
                PlonkWebsite::redirect('index.php?module=infox');
            } else {
                if (PlonkSession::exists('infoxJSON')) {
                    $json = PlonkSession::get('infoxJSON');
                    PlonkSession::remove('infoxJSON');
                } else {
                    $json = PlonkFilter::getPostValue('json');
                }
                if (PlonkFilter::getPostValue('idUni')) {
                    $idUni = PlonkFilter::getPostValue('idUni');
                } else {
                    if (PlonkFilter::getGetValue('idUni'))
                        $idUni = PlonkFilter::getGetValue('idUni');
                }
                curl::start();
                curl::setOption(CURLOPT_URL, InfoxDB::getURL($idUni) . "/index.php?module=infox&view=airport");
                curl::setOption(CURLOPT_POST, 1);
                curl::setOption(CURLOPT_RETURNTRANSFER, true);
                curl::setOption(CURLOPT_USERPWD, InfoxDB::getAuthUsername() . ":" . InfoxDB::getAuthPwd());
                curl::setOption(CURLOPT_POSTFIELDS, "json=" . $json);
                curl::setOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl::execute();

                if ($this->debug) {
                    $this->debugMsg['curl'] = curl::getInfo();
                }
                
                return curl::getInfo();
            }
        }
    }

    public function fileTransfer() {
        if (file_exists($this->path . PlonkFilter::getGETValue('file'))) {
            $array = array('file' => "@" . $this->path . PlonkFilter::getGETValue('file'));
//			$array = array('name' => 'foo', 'file' => "@C:/Program Files/GIT/mutworange/erasmusline/WEBSITE/test.txt");
            $idUni = PlonkFilter::getGetValue('idUni');
            curl::start();
            curl::setOption(CURLOPT_URL, InfoxDB::getURL($idUni) . "/modules/infox/airport/airport.php");
            curl::setOption(CURLOPT_POST, 1);
            curl::setOption(CURLOPT_RETURNTRANSFER, true);
            curl::setOption(CURLOPT_USERPWD, InfoxDB::getAuthUsername() . ":" . InfoxDB::getAuthPwd());
            curl::setOption(CURLOPT_POSTFIELDS, $array);
            curl::setOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl::execute();
        }
    }

    public function getDebug($a) {
        $dbg = "";
        if (is_array($a)) {
            $dbg = "Array (<br />";
            foreach ($a as $key => $value) {
                $dbg .= $key . " => ";
                if (is_array($value))
                    $dbg .= $this->getDebug($value);
                else
                    $dbg .= $value . ",<br />";
            }
            $dbg .= ")<br />";
        } else
            $dbg .= $a;
        return $dbg;
    }

    public function TransferBelgium($json, $idInst) {
        curl::start();
        InfoxDB::getURL($idInst) . "/modules/infox/airport/airport2.php";
        curl::setOption(CURLOPT_URL, InfoxDB::getURL($idInst) . "/modules/infox/airport/airport2.php");
        curl::setOption(CURLOPT_POST, 1);
        curl::setOption(CURLOPT_RETURNTRANSFER, true);
        curl::setOption(CURLOPT_USERPWD, InfoxDB::getAuthUsername() . ":" . InfoxDB::getAuthPwd());
        curl::setOption(CURLOPT_POSTFIELDS, "json=" . $json);
        curl::setOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl::execute();
        curl::close();
    }

    public function FileTransferBelgium($file, $idInst, $id) {

        $request_url =InfoxDB::getURL($idInst)."/modules/infox/airport/airport2.php";
        $realpath = realpath($file);
        $post_params['file'] = "@".$realpath;
        $post_param['id'] = $id;
 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        $result = curl_exec($ch);
        curl_close($ch);
    }
*/    
    public function dataTransfer($method, $table, $data, $idInst) {
        $ea = 23;
        $m = 255;
    
        $c1 = count($method);
        $c2 = count($table);
        $c3 = count($data);
        if ($c1 == $c2 && $c2 == $c3) {
            $array = array();
            for ($i = 0; $i < $c1; $i++) {
                $a1 = array('table' => $table[$i],
                            'data' => $data[$i]);
                $json = json_encode($a1);
                $a2 = array('method' => $method[$i],
                            'params' => $json);
                array_push($array, $a2);
            }
            $json = json_encode($array);
            
            // Start encoding json String
            $c = array();
            for ($i = 0; $i < strlen($json); $i++) {
              array_push($c, InfoxDB::UTF8_CharDecode($json[$i]));
            }
            $encoded = "";
            for ($i = 0; $i < count($c); $i++) {
              if ($i > 0)
                $encoded .= "#";
              $encoded .= InfoxDB::powmod($c[$i], $ea, $m);
            }
            
            if (PlonkSession::exists('id')) {
                if ($idInst != 0) {
                    curl::start();
                    curl::setOption(CURLOPT_URL, InfoxDB::getURL($idInst) . "/index.php?module=infox&view=airport");
                    curl::setOption(CURLOPT_POST, 1);
                    curl::setOption(CURLOPT_RETURNTRANSFER, true);
                    curl::setOption(CURLOPT_POSTFIELDS, "json=" . $encoded);
                    curl::execute();
                }
            }
            
            print_r(curl::getResult());
        } else {
            Plonk::dump('Arrays not same length');
        }
    }
    
    public function fileTransfer($method, $file, $idInst) {
        $ea = 23;
        $m = 255;

        if (file_exists($file)) {
            $a = array('method' => $method);
            $json = json_encode($a);
            $c = array();
            for ($i = 0; $i < strlen($json); $i++) {
              array_push($c, InfoxDB::UTF8_CharDecode($json[$i]));
            }
            $encoded = "";
            for ($i = 0; $i < count($c); $i++) {
              if ($i > 0)
                $encoded .= "#";
              $encoded .= InfoxDB::powmod($c[$i], $ea, $m);
            }
            $array = array('file' => "@" . realpath($file), "params" => $encoded);
            curl::start();
            curl::setOption(CURLOPT_URL, InfoxDB::getURL($idInst) . "/index.php?module=infox&view=airport");
            curl::setOption(CURLOPT_POST, 1);
            curl::setOption(CURLOPT_RETURNTRANSFER, true);
            curl::setOption(CURLOPT_POSTFIELDS, $array);
            curl::execute();
            
            print_r(curl::getResult());
        }
    }
}

?>
