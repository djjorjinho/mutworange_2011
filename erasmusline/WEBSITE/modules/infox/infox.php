<?php

include('curl.php');
include('infox.db.php');

class InfoxController extends PlonkController {

    protected $views = array('infox', 'transfer', 'admin');
    protected $actions = array('transfer');
    protected $debug = false;
    protected $debugMsg = "";
    protected $path = "./files/nathan.vanassche@kahosl.be/";

    public function showInfox() {
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

}

?>
